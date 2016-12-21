var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();

app.get('/', function(req, res){
    res.send('<h1>Hello world</h1>');
});

redis.subscribe('notifications', function(err, count) {
    console.log(err);
});
redis.on('message', function(channel, message) {
    console.log(channel);
    console.log('Message Recieved: ' + message);
    message = JSON.parse(message);
    io.emit(channel, message.data);
});

io.on('connection', function(socket){
    console.log('a user connected');
});
http.listen(3000, function(){
    console.log('Listening on Port 3000');
});