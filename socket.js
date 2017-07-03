require('newrelic');
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var requester = require("http");

var logErr = function(err,count){
    if(err !== null) console.log(err);
};
//socketio namespaces
    //private profile feed
        var feedNamespace = io.of('/feed');
        var feedEmit = function(pattern, channel, message){

        };
        var feed = new Redis();
        feed.psubscribe('feed.*', logErr);
        feed.on('pmessage', feedEmit);

    //public and network profile feed
        var publicNamespace = io.of("/public");

        var network = new Redis();
        network.psubscribe('network.*',logErr);
        network.on('pmessage',feedEmit);

        var public = new Redis();
        public.psubscribe('public.*',logErr);
        public.on('pmessage',function(pattern,channel,message){
            var message = JSON.parse(message);
            console.log(message);
            feedNamespace.to(channel).emit("message",message);
            publicNamespace.to(channel).emit("message",message);
        });


        //notification push

        var notificationNamespace=io.of('/notification');

        var notification=new Redis();
        notification.psubscribe('notification-channel',logErr);
        notification.on('pmessage',function(pattern,channel,message){
            var message=JSON.parse(message);
            console.log(message);
            notificationNamespace.to(channel+'.'+message.profile_id).emit("message",message);
        });

    //public company feed
        var companyFeedNamespace = io.of("/company/public");
        var companyPublicEmit = function(pattern, channel, message){
            var message = JSON.parse(message);
            companyFeedNamespace.to(channel).emit("message", message);
        };

        var companyPublic = new Redis();
        companyPublic.psubscribe('company.public.*',logErr);
        companyPublic.on('pmessage',companyPublicEmit);

        //chats

        var chatNamespace = io.of("/chat");
        var chatEmit = function(pattern,channel,message){
            console.log(message);
            chatNamespace.to(channel).emit("message",message);
        };

        var chat = new Redis();
        chat.psubscribe("chat.*",logErr);
        chat.on('pmessage',chatEmit);

        chatNamespace.on('connection',function(socket){
            var token = socket.handshake.query['token'];
            var options = {
                host: 'testapi.tagtaste.com',
                port: 8080,
                path : '/api/chatrooms',
                method: 'get',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization' : "Bearer " + token
                }
            };
            requester.request(options, function(response) {
                if(response.statusCode !== 200){
                    socket.disconnect(true);
                }
                response.setEncoding('utf8');
                response.on('data',function(body){
                    body = JSON.parse(body);
                    if(body.error){
                        console.log(body.error);
                        return;
                    }
                    body = body.data;
                    var rooms = Object.keys(body).map(function(k) { return "chat." + body[k].id });
                    for(var i in rooms){
                        socket.join(rooms[i]);
                    }
                })
            }).end();
        });

io.on('disconnect', function(){
    //console.log('user disconnected');
});

var makeConnection = function(socket){
    //console.log('connected');
    var token = socket.handshake.query['token'];
    var profileId = socket.handshake.query['id'];
    var path = '/api/channels';
    if(profileId){
        path = path + '/' + profileId + "/public";
    }

    var options = {
        host: 'testapi.tagtaste.com',
        port: 8080,
        path : path,
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'Authorization' : "Bearer " + token
        }
    };
    requester.request(options, function(response) {
        if(response.statusCode !== 200){
            socket.disconnect(true);
        }
        response.setEncoding('utf8');
        response.on('data',function(body){
            body = JSON.parse(body);
            if(body.error){
                console.log(body.error);
                return;
            }
            var rooms = Object.keys(body).map(function(k) { return body[k] });
            for(var i in rooms){
                socket.join(rooms[i]);
            }
        })
    }).end();
};

feedNamespace.on('connection', makeConnection);
publicNamespace.on('connection', makeConnection);
companyFeedNamespace.on('connection', makeConnection);

notificationNamespace.on('connection',function(socket){
        var token = socket.handshake.query['token'];
        var channelName;
            var path = '/api/profile';

            var options = {
                host: 'testapi.tagtaste.com',
                port: 8080,
                path : path,
                method: 'get',
                headers: {
                    'Content-Type': 'application/json',
                        'Authorization' : "Bearer " + token
                }
        };
        requester.request(options, function(response) {
                if(response.statusCode !== 200){
                        socket.disconnect(true);
                    }
                response.setEncoding('utf8');
                response.on('data',function(body){
                        body = JSON.parse(body);
                        if(body.error){
                            console.log(body.error);
                            return;
                        }
                        channelName='notification-channel.'+body.profile.id;
                        socket.join(channelName);
                    })
            }).end();
        });

http.listen(3001, function(){
    console.log('Listening on Port 3001');
});
