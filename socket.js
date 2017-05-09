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
            feedNamespace.to(channel).emit("message",message);
            publicNamespace.to(channel).emit("message",message);
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

http.listen(3001, function(){
    //console.log('Listening on Port 3001');
});
