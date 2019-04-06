function sleep(time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}

function getColor() {
    const username = document.getElementById("username").innerText;

    $.ajax({
        url: '/ajaxLikeStatus/' + username,
        success: function (response) {
            if (response == 'liked') {
                document.getElementById("like").src = 'http://localhost/public/img/liked.png';
            }
        },
        error: function (e) {
            console.log(e);
        },
    });
}

function like_or_not(like) {
    const username = document.getElementById("username").innerText;

    if (!like) {
        like = '/ajaxLikeUser/' + username + '/dislike'
    } else {
        like = '/ajaxLikeUser/' + username + '/like';
    }
    $.ajax({
        url: like,
        error: function (error) {
            console.log(error);
        },
    });
}

window.onload = function () {
    const like = document.getElementById("like");
    const report = document.getElementById("report");
    const block = document.getElementById("block");
    const url = window.location.href;

    getColor();
    like.onclick = function () {
        like.style.transform = "scale(1.2)";
        sleep(100).then(() => {
            like.style.transform = "scale(1)";
        });
        if (like.src.split("/")[5] == 'not_liked.png') {
            like.src = 'http://localhost/public/img/liked.png'
            like_or_not(true);
        } else {
            like.src = 'http://localhost/public/img/not_liked.png';
            like_or_not(false);
        }
    };

    report.onclick = function () {
        if (confirm("Report this user ?")) {
            const array = url.split('/');
            const user = array[array.length - 1];

            $.ajax({
                url: '/ajaxReportUser/' + user,
                error: function (error) {
                    console.log(error);
                },
            });
        }
    };

    block.onclick = function () {
        if (confirm("Block this user ?")) {
            const array = url.split('/');
            const user = array[array.length - 1];

            $.ajax({
                url: '/ajaxBlockUser/' + user,
                success: function () {
                    window.location.replace('/');
                },
                error: function (error) {
                    console.log(error);
                },
            });
        }
    }
};
