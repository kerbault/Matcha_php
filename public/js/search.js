let tableTags = false;

function pagination() {
	let action = false;
	let path = window.location.href.split('/');

	if (Number(path[path.length - 1])) {
		action = true;
	}
	$('#pagination').children().each(function() {
		if (action) {
			path[path.length - 1] = $(this).find('a').text();
			$(this).find('a').attr('href', path.join('/'));
		}
		else {
			$(this).find('a').attr('href', path.join('/') + '/' + $(this).find('a').text());
		}
	});
}

function delete_tag() {
	let myNodeList = document.getElementsByClassName("ml-2 fas fa-times");
	for (let i = 0; i < myNodeList.length; i++) {
		myNodeList[i].onclick = function () {
			const tag = $(this).parent().find('span').text();

			let index = tableTags.indexOf(tag);
			if (index > -1) {
				tableTags.splice(index, 1);
			}
			this.parentNode.parentNode.parentNode.remove();
		}
	}
}

function inArray(needle,haystack) {
	const count=haystack.length;
	for (let i=0;i<count;i++) {
		if (haystack[i]===needle) {return true;}
	}
	return false;
}

function select_suggestion() {
	let suggestions = document.getElementById("listTags").children;

	for (let i = 0; i < suggestions.length; i++) {
		suggestions[i].onclick = function() {
			let tag = $(this).find('span').text();

			if (inArray(tag, tableTags) == false) {
				let node = document.createElement("DIV");
				let size = document.createElement("H3");
				let button = document.createElement("SPAN");
				let spannode = document.createElement("SPAN");
				let textnode = document.createTextNode(tag);
				let inode = document.createElement("I");

				button.className = 'badge badge-primary mx-1';
				inode.style.cursor = 'pointer';
				inode.className = 'ml-2 fas fa-times';
				inode.id = tag;

				spannode.appendChild(textnode);
				button.appendChild(spannode);
				button.appendChild(inode);
				size.appendChild(button);
				node.appendChild(size);
				document.getElementById("userTags").appendChild(node);
				if (tableTags) {
					tableTags.push(tag);
				}
				else {
					tableTags = [tag];
				}
				document.getElementById("userTags").appendChild(node);
				document.getElementById("listTags").innerHTML = '';
				document.getElementById("inputTag").value = '';
				delete_tag();
			}
		}
	}
}

function filter_by() {
	let search = "false";
	const option = $('#select_sort').val();
	const age = $('#slider-1').parent();
	const loc = $('#slider-2').parent();
	const pop = $('#slider-3').parent();

	const age0 = age.find('.leftLabel').text();
	const age1 = age.find('.rightLabel').text();

	const loc0 = loc.find('.leftLabel').text();
	const loc1 = loc.find('.rightLabel').text();

	const pop0 = pop.find('.leftLabel').text();
	const pop1 = pop.find('.rightLabel').text();

	switch (option) {
		case "Age ↗":
			search = "ageA";
			break;
		case "Age ↘":
			search = "ageD";
			break;
		case 'Distance ↗':
			search = "locA";
			break;
		case 'Distance ↘':
			search = "locD";
			break;
		case 'Popularity ↗':
			search = "popA";
			break;
		case 'Popularity ↘':
			search = "popD";
			break;
	}

	if (tableTags)
		tableTags = JSON.stringify(tableTags);
	else
		tableTags = "false";
	window.location.replace("/search/filter/" + search + "/" + age0 + "/" + age1 + "/" + loc0 + "/" + loc1 + "/" + pop0 + "/" + pop1 + "/" + tableTags);
}

function slider_group() {
	$('#slider-1').nstSlider({
		"crossable_handles": false,
		"left_grip_selector": ".leftGrip",
		"right_grip_selector": ".rightGrip",
		"value_bar_selector": ".bar",
		"value_changed_callback": function(cause, leftValue, rightValue) {
			$(this).parent().find('.leftLabel').text(leftValue);
			$(this).parent().find('.rightLabel').text(rightValue);
		}
	});

	$('#slider-2').nstSlider({
		"crossable_handles": false,
		"left_grip_selector": ".leftGrip",
		"right_grip_selector": ".rightGrip",
		"value_bar_selector": ".bar",
		"value_changed_callback": function(cause, leftValue, rightValue) {
			$(this).parent().find('.leftLabel').text(leftValue);
			$(this).parent().find('.rightLabel').text(rightValue);
		}
	});

	$('#slider-3').nstSlider({
		"crossable_handles": false,
		"left_grip_selector": ".leftGrip",
		"right_grip_selector": ".rightGrip",
		"value_bar_selector": ".bar",
		"value_changed_callback": function(cause, leftValue, rightValue) {
			$(this).parent().find('.leftLabel').text(leftValue);
			$(this).parent().find('.rightLabel').text(rightValue);
		}
	});
}

window.onload = function () {
    const tags = document.getElementById("inputTag");
	slider_group();
	pagination()

    let eventKey;
    let typingTimer;                //timer identifier
    const doneTypingInterval = 200;  //time in ms, 2 second for example
	const submit = document.getElementById("search_button");

	if (!submit || !tags)
		return ;
	submit.onclick = function () {
		filter_by();
	}

//on keyup, start the countdown
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

//user is "finished typing," do something
    function doneTyping() {
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
                        let size = document.createElement("H5");
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
}
