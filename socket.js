var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');

var logErr = function(err,count){
    if(err !== null) console.log(err);
};

var emit = function(pattern, channel, message){
    console.log(pattern);
    console.log(channel);
    console.log('Message Recieved: ' + message);
    message = JSON.parse(message);
    io.emit(channel, message.data);
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

io.on('connection', function(socket){
    console.log(socket);
    console.log('a user connected');
});

http.listen(3001, function(){
    console.log('Listening on Port 3001');
});