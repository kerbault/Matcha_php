let usrTags = false;

function sendForm() {
    tags = JSON.stringify(usrTags);
	$.ajax({
		url: "/account/tags",
		data: {'tags' : tags},
		type: 'post',
		error: function(response) {
			console.log(response);
		},
	});
	window.location.replace('/account/tags');
}

function inArray(needle,haystack) {
	const count=haystack.length;
	for (let i=0;i<count;i++) {
		if (haystack[i]===needle) {return true;}
	}
	return false;
}

function delete_tag() {
	let myNodeList = document.getElementsByClassName("ml-2 fas fa-times");
	for (let i = 0; i < myNodeList.length; i++) {
		myNodeList[i].onclick = function () {
			const tag = $(this).parent().find('span').text();

			let index = usrTags.indexOf(tag);
			if (index > -1) {
				usrTags.splice(index, 1);
			}
			this.parentNode.parentNode.parentNode.remove();
		}
	}
}

function check_suggestions() {
	let suggestions = document.getElementById("listTags").children;
	const input = document.getElementById("inputTag").value.toUpperCase();

	let arr = [].slice.call(suggestions);
	for (let i = 0; i < arr.length; i++) {
		if ($(arr[i]).find('span').text().toUpperCase() === input) {
			return false;
		}
	}
	return true;
}

function add_new_tag() {
	if (check_suggestions() == false) {
		return ;
	}
	else {
		const input = document.getElementById("inputTag").value.toLowerCase();
		const tag = input.charAt(0).toUpperCase() + input.slice(1);

		if (tag.length < 1 || tag.length > 15)
			return ;
		if (inArray(tag, usrTags)) {
			return ;
		}
		$.ajax({
			url: "/ajaxNewTag/" + tag,
			success: function (res) {
				let node = document.createElement("DIV");
				let size = document.createElement("H3");
				let button = document.createElement("SPAN");
				let spannode = document.createElement("SPAN");
				let textnode = document.createTextNode(tag);
				let inode = document.createElement("I");

				button.className = 'badge badge-primary mx-1';
				inode.style.cursor = 'pointer';
				inode.className = 'ml-2 fas fa-times';

				spannode.appendChild(textnode);
				button.appendChild(spannode);
				button.appendChild(inode);
				size.appendChild(button);
				node.appendChild(size);
				document.getElementById("userTags").appendChild(node);
				if (usrTags)
				usrTags.push(tag);
				else {
					usrTags = [tag];
				}
				delete_tag();
			},
			error: function(response) {
				console.log(response);
			},
		});
	}
}

function select_suggestion() {
	let suggestions = document.getElementById("listTags").children;

	for (let i = 0; i < suggestions.length; i++) {
		suggestions[i].onclick = function() {
			let tag = $(this).find('span').text();

			if (inArray(tag, usrTags) == false) {
				let node = document.createElement("DIV");
				let size = document.createElement("H3");
				let button = document.createElement("SPAN");
				let spannode = document.createElement("SPAN");
				let textnode = document.createTextNode(tag);
				let inode = document.createElement("I");

				button.className = 'badge badge-primary mx-1';
				inode.style.cursor = 'pointer';
				inode.className = 'ml-2 fas fa-times';

				spannode.appendChild(textnode);
				button.appendChild(spannode);
				button.appendChild(inode);
				size.appendChild(button);
				node.appendChild(size);
				document.getElementById("userTags").appendChild(node);
				if (usrTags) {
					usrTags.push(tag);
				}
				else {
					usrTags = [tag];
				}
				document.getElementById("listTags").innerHTML = '';
				document.getElementById("inputTag").value = '';
				delete_tag();
			}
		}
	}
}
// if (addTags)
// 	addTags.push(obj[key]);
// else
// 	addTags = [obj[key]];

function load_user_tags() {
	$.ajax({
		url: "/ajaxGetUserTags",
		success: function (response) {
			if (!response)
			return;
			let obj = JSON.parse(response);

			for (let key in obj) {
				let node = document.createElement("DIV");
				let size = document.createElement("H3");
				let button = document.createElement("SPAN");
				let spannode = document.createElement("SPAN");
				let textnode = document.createTextNode(obj[key]);
				let inode = document.createElement("I");

				button.className = 'badge badge-primary mx-1';
				inode.style.cursor = 'pointer';
				inode.className = 'ml-2 fas fa-times';

				spannode.appendChild(textnode);
				button.appendChild(spannode);
				button.appendChild(inode);
				size.appendChild(button);
				node.appendChild(size);
				document.getElementById("userTags").appendChild(node);
				if (usrTags)
					usrTags.push(obj[key]);
				else
					usrTags = [obj[key]];
			}
			delete_tag();
		},
		error: function (error) {
			console.log(error);
		},
	});
}

window.onload = function () {
	const tags = document.getElementById('inputTag');
	const button = document.getElementById('myButton');

	let eventKey;
	let typingTimer;                //timer identifier
	const doneTypingInterval = 200;  //time in ms, 2 second for example

	load_user_tags();

	tags.onkeyup = function (event) {
		clearTimeout(typingTimer);
		eventKey = event
		typingTimer = setTimeout(doneTyping, doneTypingInterval);
	};

	//on keydown, clear the countdown
	tags.onkeydown = function (event) {
		clearTimeout(typingTimer);
		eventKey = event
	};

	function doneTyping() {
		if (eventKey.keyCode == 13) {
			add_new_tag();
		}
		let myNode = document.getElementById("listTags");
		myNode.innerHTML = '';
		if (tags.value.length > 0) {
			$.ajax({
				url: "/ajaxTags/" + tags.value,
				success: function (response) {
					if (!response)
					return;
					let obj = JSON.parse(response);

					for (let key in obj) {
						let node = document.createElement("DIV");
						let size = document.createElement("H3");
						let button = document.createElement("SPAN");
						let textnode = document.createTextNode(obj[key]);

						button.className = 'badge badge-secondary mx-1';
						node.style.cursor = "pointer";

						button.appendChild(textnode);
						size.appendChild(button);
						node.appendChild(size);
						document.getElementById("listTags").appendChild(node);
						select_suggestion();
					}
				},
				error: function (error) {
					console.log(error);
				},
			});
		}
	}

	button.onclick = function() {
		if (usrTags && usrTags.length > 0) {
			sendForm();
		}
	}
}
