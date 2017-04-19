var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();

app.get('/', function(req, res){
    res.send('<h1>Hello world</h1>');
});

redis.psubscribe('feed.*', function(err, count) {
    console.log(err);
});
redis.on('pmessage', function(pattern, channel, message) {
    console.log(pattern);
    console.log(channel);
    console.log('Message Recieved: ' + message);
    message = JSON.parse(message);
    io.emit(channel, message.data);
});

io.on('connection', function(socket){
    console.log(socket);
    console.log('a user connected');
});
http.listen(3001, function(){
    console.log('Listening on Port 3001');
});