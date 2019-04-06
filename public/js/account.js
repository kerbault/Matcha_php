//╔═════════════════════════════════════════════════════════════════╗
//║ 					    Form verifications						║
//╚═════════════════════════════════════════════════════════════════╝

let validGender = false;
let validOrientation = 1;
let validFileToUpload = false;
let validBio = false;
let validTags = false;

function inArray(needle, haystack) {
    const count = haystack.length;
    for (let i = 0; i < count; i++) {
        if (haystack[i] === needle) {
            return true;
        }
    }
    return false;
}

function validateForm() {
    if (validGender && validOrientation && validFileToUpload && validBio && validTags && validTags.length > 0) {
        const tags = JSON.stringify(validTags);
        const data = new FormData();
        data.append('file', validFileToUpload)
        data.append('gender', validGender);
        data.append('orientation', validOrientation);
        data.append('bio', validBio);
        data.append('tags', tags);
        $.ajax({
            url: "/ajaxExtendedRegistration",
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: 'post',
            success: function (res) {
                window.location.replace('/account');
            },
            error: function (response) {
                console.log(response);
            },
        });
    } else {
        alert("Please review your info");
    }
}

//╔═════════════════════════════════════════════════════════════════╗
//║ 							Tags								║
//╚═════════════════════════════════════════════════════════════════╝

function delete_tag() {
    let myNodeList = document.getElementsByClassName("ml-2 fas fa-times");
    for (let i = 0; i < myNodeList.length; i++) {
        myNodeList[i].onclick = function () {
            const tag = $(this).parent().find('span').text();

            let index = validTags.indexOf(tag);
            if (index > -1) {
                validTags.splice(index, 1);
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
        return;
    } else {
        const input = document.getElementById("inputTag").value.toLowerCase();
        const tag = input.charAt(0).toUpperCase() + input.slice(1);
        if (tag.length < 1 || tag.length > 15)
            return;
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
                if (validTags)
                    validTags.push(tag);
                else {
                    validTags = [tag];
                }
                delete_tag();
            },
            error: function (response) {
                console.log(response);
            },
        });
    }
}

function select_suggestion() {
    let suggestions = document.getElementById("listTags").children;

    for (let i = 0; i < suggestions.length; i++) {
        suggestions[i].onclick = function () {
            let tag = $(this).find('span').text();

            if (inArray(tag, validTags) == false) {
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
                if (validTags) {
                    validTags.push(tag);
                } else {
                    validTags = [tag];
                }
                document.getElementById("listTags").innerHTML = '';
                document.getElementById("inputTag").value = '';
                delete_tag();
            }
        }
    }
}

//╔═════════════════════════════════════════════════════════════════╗
//║ 							page Load							║
//╚═════════════════════════════════════════════════════════════════╝

window.onload = function () {
    const tags = document.getElementById("inputTag");
    const button = document.getElementById("submit_form");

    let eventKey;
    let typingTimer;                //timer identifier
    const doneTypingInterval = 200;  //time in ms, 2 second for example

    const gender = document.getElementById("inputGender");
    const orientation = document.getElementById("inputOrientation");
    const fileToUpload = document.getElementById("fileToUpload");
    const bio = document.getElementById("bio");

    button.onclick = function () {
        validateForm();
    }
    //gender input
    gender.onchange = function () {
        if (gender.value != 2 && gender.value != 3) {
            validGender = false;
            gender.className = "form-control is-invalid";
        } else {
            validGender = gender.value;
            gender.className = "form-control is-valid";
        }
    };

    //orientation input
    orientation.onchange = function () {
        if (orientation.value > 3 || orientation.value < 1) {
            validOrientation = false;
            orientation.className = "form-control is-invalid";
        } else {
            validOrientation = orientation.value;
            orientation.className = "form-control is-valid";
        }
    };

    //fileToUpload input
    if (fileToUpload) {
        fileToUpload.onchange = function (event) {
            const output = document.getElementById('photo');

            if (event.target.files[0]) {
                const FileSize = event.target.files[0].size / 1024 / 1024; // in MB}
                if (FileSize > 2) {
                    alert('File size exceeds 2 MB');
                    document.getElementById('fileToUpload').value = '';
                    output.setAttribute('src', 'public/img/you_photo_here.png');
                    fileToUpload.className = "form-control is-invalid";
                } else {
                    if (event.target.files && event.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function () {
                            output.src = reader.result;
                        };
                        validFileToUpload = document.getElementById('fileToUpload').files[0];
                        reader.readAsDataURL(event.target.files[0]);
                        fileToUpload.className = "form-control is-valid";
                    } else {
                        output.setAttribute('src', 'public/img/you_photo_here.png');
                        fileToUpload.className = "form-control is-invalid";
                    }
                }
            } else {
                output.setAttribute('src', 'public/img/you_photo_here.png');
                fileToUpload.className = "form-control is-invalid";
            }
        };
    }

    //bio input
    bio.onchange = function () {
        if (bio.value.length < 4 || bio.value.length > 250) {
            validBio = false;
            bio.className = 'form-control is-invalid';
        } else {
            validBio = bio.value;
            bio.className = 'form-control is-valid';
        }
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
}
