require('newrelic');
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var requester = require("http");
var queryString = require('querystring');

var logErr = function(err,count){
    if(err !== null) console.log(err);
};
//socketio namespaces

    //listen to everything

    //private profile feed
        var feedNamespace = io.of('/feed');
        var feedEmit = function(pattern, channel, message){
            var message = JSON.parse(message);
            feedNamespace.to(channel).emit("message",message);
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
            feedNamespace.to(channel).emit("message",message);
            publicNamespace.to(channel).emit("message",message);
        });


        //notification push

        var notificationNamespace=io.of('/notifications');

        var notification = new Redis();
        notification.psubscribe('private-App.Notify.Profile.*',logErr);
        notification.on('pmessage',function(pattern,channel,message){
            console.log(message);
            notificationNamespace.to(channel).emit("message",message);
        });

    //public company feed
        var companyFeedNamespace = io.of("/company/public");
        var companyPublicEmit = function(pattern, channel, message){
            var message = JSON.parse(message);
            console.log(channel);
            console.log(message);
            feedNamespace.to(channel).emit("message",message);
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
                },
            };
            requester.request(options, function(response) {
                if(response.statusCode !== 200){
                    socket.disconnect(true);
                }
                response.setEncoding('utf8');
                response.on('data',function(body){
                    console.log(body);
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

            //which room to send the message to
            socket.on('message',function(chatId, message){
                var data = queryString.stringify({
                    "message" : message
                });

                var optionsChat = {
                    host: 'testapi.tagtaste.com',
                    port: 8080,
                    path : '/api/chats/' + chatId + '/messages',
                    method: 'post',
                    headers: {
                         'Content-Type': 'application/x-www-form-urlencoded',
                        'Authorization' : "Bearer " + token
                    },
                };
                var req = requester.request(optionsChat, function(response) {
                    if(response.statusCode !== 200){
                        socket.disconnect(true);
                    }
                    response.setEncoding('utf8');
                    response.on('data',function(body){
                        //do nothing.
                    })
                });
                req.write(data);
                req.end();

            });

            socket.on("message-read",function(chatId,messageId){
                var optionsChat = {
                    host: 'testapi.tagtaste.com',
                    port: 8080,
                    path : '/api/chats/' + chatId + '/messages/' + messageId + "/markRead",
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Authorization' : "Bearer " + token
                    },
                };
                requester.request(optionsChat, function(response) {
                    if(response.statusCode !== 200){
                        socket.disconnect(true);
                    }
                    response.setEncoding('utf8');
                    response.on('data',function(body){
                        //do nothing.
                    })
                }).end();
            });

            //create new chat
            socket.on("new-chat",function(data){
                data = JSON.parse(data);
                var newChat = {
                    host: 'testapi.tagtaste.com',
                    port: 8080,
                    path : '/api/chats/',
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Authorization' : "Bearer " + token
                    },
                };

                var req = requester.request(newChat, function(response) {
                    if(response.statusCode !== 200){
                        socket.disconnect(true);
                    }
                    response.setEncoding('utf8');
                    response.on('data',function(body){
                        try {
                            body = JSON.parse(body);
                            socket.join("chat." + body.data.id);
                            body.data.profiles.forEach(function(profile){
                                notificationNamespace.to("private-App.Notify.Profile." + profile.id).emit("new-chat",JSON.stringify(body));
                            });
                        } catch (e){
                            console.log(e);
                        }
                    })
                });
                data = queryString.stringify(data);
                req.write(data);
                req.end();


            });
        });

io.on('disconnect', function(){
    //console.log('user disconnected');
});
var makeCompanyConnection = function(socket){
    //console.log('connected');
    var token = socket.handshake.query['token'];
    var companyId = socket.handshake.query['id'];
    var path = '/api/channels';
    if(companyId){
        path = path + '/companies/' + companyId + "/public";
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
            console.log("connecting on");
            console.log(body);
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
companyFeedNamespace.on('connection', makeCompanyConnection);

notificationNamespace.on('connection',function(socket){
        var token = socket.handshake.query['token'];

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
                var responseData = "";
                if(response.statusCode !== 200){
                        socket.disconnect(true);
                    }
                response.setEncoding('utf8');
                response.on('data',function(chunk){
                    responseData += chunk;

                    });
            response.on('end',function(){
                try {
                    body = JSON.parse(responseData);
                } catch (e) {
                    console.log("path");
                    console.log(path);
                    console.log("body");
                    console.log(response);
                    return console.error(e);
                }
                if(body.error){
                    console.log(body.error);
                    return;
                }
                socket.join('private-App.Notify.Profile.'+body.profile.id);
            })
            }).end();
        });

var request = function(path,token,data){
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
        var rawData = "";
        if(response.statusCode !== 200){
            return false;
        }
        response.setEncoding('utf8');
        response.on('data',function(chunk){
            rawData += chunk;
        });
        response.on('end',function(){
            try {
                data = JSON.parse(rawData);
            } catch (e) {
                console.log("path");
                console.log(path);
                console.log("body");
                console.log(response);
                return console.error(e);
            }
            if(body.error){
                console.log(body.error);
                return;
            }
        })
    }).end();
};
http.listen(3001, function(){
    console.log('Listening on Port 3001');
});
