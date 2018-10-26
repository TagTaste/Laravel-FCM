require('newrelic');
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var requester = require("http");
var queryString = require('querystring');
var Port = 3030;

var logErr = function(err,count){
    if(err !== null) console.log(err);
};

var chatNamespace = io.of('/chat');

var chatEmit = function(pattern,channel,message){
            chatNamespace.to(channel).emit("message",message);
        };

        var chat = new Redis();
        chat.psubscribe("chat.*",logErr);
        chat.on('pmessage',chatEmit);

chatNamespace.on('connection', function(socket){
     //on connection with a new user it has been connected to all his previous chat rooms.
	var token = socket.handshake.query['token'];
	var pId = socket.handshake.query['profileId'];
	var newChat = new Redis();
	newChat.subscribe("new-chat-"+ pId, logErr);
	newChat.on('message',function(channel, chatId){
        console.log("new room created for profile Id: "+pId);
		socket.join("chat."+chatId);
	});
	console.log('profile id '+pId+' connected');
	var options = {
                host: 'webapp.test',
                path : '/api/v1/chats',
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
                    socket.emit('chat-room',body);
                     var rooms = Object.keys(body).map(function(k) { return "chat." + body[k].id });
                     for(var i in rooms){
                         socket.join(rooms[i]);
                         console.log(rooms[i]);
                     }
                })
            }).end();


    		//we will recieve messages on this channel. 
           socket.on('message', function(message, chatId){
    		console.log('message: ' + message);
    		var data = queryString.stringify({
                    "message" : message
                });
    			var optionsChat = {
                    host: 'webapp.test',
                    path : '/api/v1/chats/' + chatId + '/messages',
                    method: 'post',
                    headers: {
                         'Content-Type': 'application/x-www-form-urlencoded',
                        'Authorization' : "Bearer " + token
                    },
                };
                var req = requester.request(optionsChat, function(response){
                	if(response.statusCode !== 200){
                        socket.disconnect(true);
                    }
                    response.setEncoding('utf8');
                    response.on('data',function(body){
                        //do nothing.
                        //console.log(body);
                    })
                });
                req.write(data);
                req.end();
                //chatEmit('chat.'+chatId,message);

			});

			socket.on('message-read',function(messageId,chatId){
				console.log('message read socket');
				var data = queryString.stringify({
					"messageId":messageId
				});
				console.log('here');
				var optionsChat = {
					host: 'webapp.test',
					path: '/api/v1/chats/'+ chatId +'/markAsRead',
					method: 'post',
					headers: {
                         'Content-Type': 'application/x-www-form-urlencoded',
                        'Authorization' : "Bearer " + token
					}
				}
				console.log('here');
				var msgReadReq = requester.request(optionsChat, function(response){
                	if(response.statusCode !== 200){
                        socket.disconnect(true);
                    }
                    response.setEncoding('utf8');
                    response.on('data',function(body){
                        //do nothing.
                        console.log(body);
                    })
                });
                msgReadReq.write(data);
                msgReadReq.end();
			
			});
			 //create new chat
            socket.on("createNewChat",function(data){
				data = JSON.parse(data);
                var newChat = {
                    host: 'webapp.test',
                    path : '/api/v1/chats',
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization' : "Bearer " + token,
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
	                            console.log(body);
	                            var chatId = body.data.id;
	                            socket.join("chat." + chatId);
	                            console.log(body);
	                            body.data.profiles.forEach(function(profile){
	                            	var chatchat = new Redis();
	                            	chatchat.publish("new-chat-"+ profile.id, chatId);
	                            });
	                        } catch (e){
	                            console.log(e);
	                        }
	                    })
                	
                })
                req.write(JSON.stringify(data));
                req.end();
            });   
    		
});
http.listen(Port, function(){
    console.log('Listening on Port '+Port);
})