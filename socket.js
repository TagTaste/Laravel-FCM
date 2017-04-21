var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var requester = require("http");

var logErr = function(err,count){
    if(err !== null) console.log(err);
};

var emit = function(pattern, channel, message){
    message = JSON.parse(message);
    io.emit(channel, message);
};

var feed = new Redis();
feed.psubscribe('feed.*', logErr);
feed.on('pmessage', emit);

var network = new Redis();
network.psubscribe('network.*',logErr);
network.on('pmessage',emit);

var public = new Redis();
public.psubscribe('public.*',logErr);
public.on('pmessage',emit);

io.on('disconnect', function(){
    //console.log('user disconnected');
});

io.on('connection', function(socket){
    //console.log('connected');
    var token = socket.handshake.query['token'];
    var options = {
        host: 'web.app',
        path : '/api/channels',
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'Authorization' : "Bearer " + token
        }
    };
    requester.request(options, function(response) {
        console.log(response.statusCode);
        if(response.statusCode !== 200){
            socket.disconnect(true);
        }
        response.setEncoding('utf8');
        response.on('data',function(body){
            socket.join(body);
        })
    }).end();
});

http.listen(3000, function(){
    //console.log('Listening on Port 3001');
});
