<nav class="navbar navbar-expand-md navbar-light bg-light">
    <a class="navbar-brand abs" href="/">Matcha</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="navbarNav">
        <ul class="navbar-nav">
            <?php if (verifyStatus() > 3) { ?>
                <li class="nav-item <?php if ($_GET['url'] == '') {
                    echo 'active';
                } ?>">

                    <a class="nav-link <?php if ($_GET['url'] != '') {
                        echo 'text-primary';
                    } ?>" href="/">Suggestions</a>
                </li>
                <li class="nav-item <?php if ($_GET['url'] == 'search') {
                    echo 'active';
                } ?>">
                    <a class="nav-link <?php if ($_GET['url'] != 'search') {
                        echo 'text-primary';
                    } ?>" href="/search">Search</a>
                </li>
                <li class="nav-item <?php if ($_GET['url'] == 'chat') {
                    echo 'active';
                } ?>">
                    <a class="nav-link <?php if ($_GET['url'] != 'chat') {
                        echo 'text-primary';
                    } ?>" href="/chat">Chat</a>
                </li>
            <?php } ?>

        </ul>
        <ul class="navbar-nav ml-auto">
            <?php
            if (verifyStatus() > 3) {
                ?>
                <button id="notifButton" type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#exampleModal">
                    <i class="fas fa-lg fa-bell"></i><span id="notifNumber" class="badge badge-light ml-2"></span>
                </button>
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="exampleModalLabel">Notifications</h3>
                                <button id="notifCloseBis" type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h5>Likes</h5>
                                <div id="likes_notif">
                                </div>
                                <hr>
                                <h5>Messages</h5>
                                <div id="messages_notif">
                                </div>
                                <hr>
                                <h5>Visits</h5>
                                <div id="visits_notif">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="notifClose" type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            if (verifyStatus() > 2) { ?>
                <li class="nav-item <?php if ($_GET['url'] == 'account') {
                    echo 'active';
                } ?>">
                    <a class="nav-link <?php if ($_GET['url'] != 'account') {
                        echo 'text-primary';
                    } ?>" href="/account">Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-primary" href="/logout">Logout</a></li>
                <?php
            } else {
                ?>
                <li class="nav-item <?php if ($_GET['url'] == 'signup') {
                    echo 'active';
                } ?>">
                    <a class="nav-link <?php if ($_GET['url'] != 'signup') {
                        echo 'text-primary';
                    } ?>" href="/signup">Sign up</a></li>

                <li class="nav-item <?php if ($_GET['url'] == 'login') {
                    echo 'active';
                } ?>">
                    <a class="nav-link <?php if ($_GET['url'] != 'login') {
                        echo 'text-primary';
                    } ?>" href="/login">Login</a></li>
                <?php
            }
            ?>
        </ul>
    </div>
</nav>
