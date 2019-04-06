window.addEventListener('load', start_notif);
window.onbeforeunload = function() {
	$.ajax({
		url: '/offlineUser',
	});
}


function start_notif() {
	const button = document.getElementById('notifButton');
	const close = document.getElementById('notifClose');
	const closeBis = document.getElementById('notifCloseBis');

	if (!button || !close || !closeBis)
		return -1;

	check_new_notifs();
	check_click();
	window.setInterval(function () {
		check_new_notifs();
	}, 8000);
}

function check_new_notifs() {
	$.ajax({
		url: "/ajaxGetNotificationsNumber",
		success: function (response) {
			document.getElementById('notifNumber').innerHTML = response;
		},
		error: function (error) {
			console.log(error);
		},
	});
}

function check_click() {
	const button = document.getElementById('notifButton');
	const close = document.getElementById('notifClose');
	const closeBis = document.getElementById('notifCloseBis');
	// <div class="card">
	// 	<div class="card-body">
	// 		<a>goberlui</a><span class="h6 ml-5 font-italic text-muted">29/03/2019 14:13</span>
	// 	</div>
	// </div>

	if (!button || !close || !closeBis)
		return -1;
	button.onclick = function () {
		$.ajax({
			url: "/ajaxGetNotifications",
			success: function (response) {
//				console.log(response);
				document.getElementById('notifNumber').innerHTML = '';

				const obj = JSON.parse(response);
				const likes = document.getElementById('likes_notif');
				const messages = document.getElementById('messages_notif');
				const visits = document.getElementById('visits_notif');
				let status = " unliked your profile";

				Object.keys(obj.likes).forEach(key => {
					const date = obj.likes[key].date.split(':');

					if (obj.likes[key].liked == 1) {
						if (obj.likes[key].match == 1)
							status = " liked your profile, it's a Match !";
						else
							status = " liked your profile";
					}
					const node = document.createElement("DIV");
					const body = document.createElement("DIV");
					const text = document.createElement("A");
					const infoNode = document.createElement("SPAN");
					const spanNode = document.createElement("SPAN");
					const infotext = document.createTextNode(status);
					const textnode = document.createTextNode(obj.likes[key].users_ID);
					const timenode = document.createTextNode(date[0] + ':' + date[1]);

					node.className = "card";
					body.className = "card-body";
					spanNode.className = "h6 ml-5 font-italic text-muted";
					text.href = "/user/" + obj.likes[key].users_ID;

					infoNode.appendChild(infotext);
					text.appendChild(textnode);
					spanNode.appendChild(timenode);

					body.appendChild(text);
					body.appendChild(infoNode);
					body.appendChild(spanNode);

					node.appendChild(body);
					likes.appendChild(node);
				});
				Object.keys(obj.views).forEach(key => {
					const date = obj.views[key].timestamp.split(':');

					const node = document.createElement("DIV");
					const body = document.createElement("DIV");
					const text = document.createElement("A");
					const infoNode = document.createElement("SPAN");
					const spanNode = document.createElement("SPAN");
					const infotext = document.createTextNode(" visited your profile");
					const textnode = document.createTextNode(obj.views[key].users_ID);
					const timenode = document.createTextNode(date[0] + ':' + date[1]);

					node.className = "card";
					body.className = "card-body";
					spanNode.className = "h6 ml-5 font-italic text-muted";
					text.href = "/user/" + obj.views[key].users_ID;

					infoNode.appendChild(infotext);
					text.appendChild(textnode);
					spanNode.appendChild(timenode);

					body.appendChild(text);
					body.appendChild(infoNode);
					body.appendChild(spanNode);

					node.appendChild(body);
					visits.appendChild(node);
				});
				Object.keys(obj.messages).forEach(key => {
					const date = obj.messages[key].timestamp.split(':');

					const node = document.createElement("DIV");
					const body = document.createElement("DIV");
					const text = document.createElement("A");
					const infoNode = document.createElement("SPAN");
					const spanNode = document.createElement("SPAN");
					const infotext = document.createTextNode(" sent you a new message");
					const textnode = document.createTextNode(obj.messages[key].users_ID);
					const timenode = document.createTextNode(date[0] + ':' + date[1]);

					node.className = "card";
					body.className = "card-body";
					spanNode.className = "h6 ml-5 font-italic text-muted";
					text.href = "/user/" + obj.messages[key].users_ID;

					infoNode.appendChild(infotext);
					text.appendChild(textnode);
					spanNode.appendChild(timenode);

					body.appendChild(text);
					body.appendChild(infoNode);
					body.appendChild(spanNode);

					node.appendChild(body);
					messages.appendChild(node);
				});
			},
			error: function (error) {
				console.log(error);
			},
		});

	}
	close.onclick = function () {
		const likes = document.getElementById('likes_notif');
		const messages = document.getElementById('messages_notif');
		const visits = document.getElementById('visits_notif');

		likes.innerHTML = '';
		messages.innerHTML = '';
		visits.innerHTML = '';
	}
	closeBis.onclick = function () {
		const likes = document.getElementById('likes_notif');
		const messages = document.getElementById('messages_notif');
		const visits = document.getElementById('visits_notif');

		likes.innerHTML = '';
		messages.innerHTML = '';
		visits.innerHTML = '';
	}
}
