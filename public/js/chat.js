function send_message() {
	var message = document.getElementById("messageInput").value;
	var lastId = document.getElementById("messageList").children;
	if (lastId[lastId.length - 1]) {
		lastId = parseInt(lastId[lastId.length - 1].id) + 1;
	}
	else {
		lastId = 1;
	}

	if (message) {
		const username = window.location.href.split("/")[4];
		$.ajax ({
			url: "/ajaxSendMessage",
			type: "POST",
			data: {msg : message, user : username},
			success: function (response) {
				const time = new Date;

				const node = document.createElement("LI");
				const spanNode = document.createElement("SPAN");
				const text = document.createElement("A")
				const timenode = document.createTextNode(time.getHours() + " : " + time.getMinutes());
				const textnode = document.createTextNode(message);

				node.className = "me";
				node.id = lastId;
				spanNode.className = "tme";

				spanNode.appendChild(timenode);
				text.appendChild(textnode);

				node.appendChild(spanNode);
				node.appendChild(text);
				document.getElementById("messageList").appendChild(node);
				document.getElementById("messageInput").value = "";
				var objDiv = document.getElementById("chatBox");
				objDiv.scrollTop = objDiv.scrollHeight;
			},
			error: function(error) {
				console.log(error);
			},
		});
	}
}

function check_new_messages(username) {
	const msg_list = document.getElementById("messageList").children;

	let id = msg_list[msg_list.length - 1];
	if (!id)
		id = 0;
	else
		id = id.id;
	$.ajax ({
		url: "/ajaxCheckNewMessages",
		type: "POST",
		data: {last_id : id, user : username},
		success: function (response) {
			if (!response)
				return ;
			var obj = JSON.parse(response);

			const list = document.getElementById("messageList").children;
			const time = new Date;
			Object.keys(obj).forEach(key => {
				const node = document.createElement("LI");
				const spanNode = document.createElement("SPAN");
				const text = document.createElement("A")
				const timenode = document.createTextNode(time.getHours() + " : " + time.getMinutes());
				const textnode = document.createTextNode(obj[key].message);

				node.className = "him";
				node.id = obj[key].private_ID;
				spanNode.className = "thim";

				spanNode.appendChild(timenode);
				text.appendChild(textnode);

				node.appendChild(spanNode);
				node.appendChild(text);
				document.getElementById("messageList").appendChild(node);
				var objDiv = document.getElementById("chatBox");
				objDiv.scrollTop = objDiv.scrollHeight;
			});
		},
		error: function(error) {
			console.log(error);
		},
	});
}

function check_chat_start() {
	const chattingWith = window.location.href.split('/')[4];
	const elem = document.getElementById(chattingWith);

	if (elem)
	elem.className = "list-group-item my-1 border border-primary";

	var matches = document.getElementById("matchList").childNodes;

	for (var i = 0; i < matches.length; i++) {
		matches[i].onclick = function() {
			window.location.replace("/chat/" + this.id);
		}
	}
}

function send_my_message() {
	const button = document.getElementById("messageButton");
	const input = document.getElementById("messageInput");

	button.onclick = function() {
		send_message();
	}
	input.onkeyup = function() {
		if (event.keyCode == 13) {
			send_message();
		}
	}
}

window.onload = function() {
	var objDiv = document.getElementById("chatBox");
	var url = window.location.href;
	objDiv.scrollTop = objDiv.scrollHeight;
	check_chat_start();

	if (url.split("/")[4]) {
		send_my_message();
		window.setInterval(function(){
			check_new_messages(url.split("/")[4]);
		}, 3000);
	}
}
