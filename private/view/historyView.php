<?php
$title = 'Account';

ob_start(); ?>
<nav class="nav nav-pills nav-justified">
    <a class="nav-item nav-link " href="/account">Profile</a>
    <a class="nav-item nav-link" href="/account/pictures">Pictures</a>
    <a class="nav-item nav-link" href="/account/tags">Tags</a>
    <a class="nav-item nav-link" href="/account/likes">Likes</a>
    <a class="nav-item nav-link" href="/account/viewed">Viewed</a>
    <a class="nav-item nav-link active" href="/account/history">History</a>
    <a class="nav-item nav-link" href="/account/blocked">Blocked</a>
    <a class="nav-item nav-link" href="/account/security">Security</a>
    <?php if (verifyStatus() == 5) { ?><a class="nav-item nav-link" href="/account/admin">Admin</a><?php } ?>
</nav>
<div class="modal-body">
    <h5>Likes</h5>
    <hr>
    <div class="row ml-2">
        <?php
        if (is_array($res['likes'])) {
            foreach ($res['likes'] as $like) {
                echo '<div class="col-10">' . $like['users_ID'];
                if ($like['liked'] == 1) {
                    if ($like['match'] == 1)
                        echo " liked your profile, it's a Match !";
                    else
                        echo " liked your profile";
                } else {
                    echo ' disliked your profile';
                }
                echo '<span class="ml-3 font-italic text-muted">' . explode(':', $like['date'])[0] . 'h </span>';
                echo '</div>';
            }
        }
        ?>
    </div>
    <hr>
    <h5>Messages</h5>
    <div class="row ml-2">
        <?php
        if (is_array($res['messages'])) {
            foreach ($res['messages'] as $message) {
                echo '<div class="col-10">' . $message['users_ID'] . ' Sent you a message';
                echo '<span class="ml-3 font-italic text-muted">' . explode(':', $like['date'])[0] . 'h </span>';
                echo '</div>';
            }
        }
        ?>
    </div>
    <hr>
    <h5>Visits</h5>
    <div class="row ml-2">
        <?php
        if (is_array($res['views']) && isset ($like['date'])) {
            foreach ($res['views'] as $view) {
                echo '<div class="col-10">' . $view['users_ID'] . ' Visited your profile';
                echo '<span class="ml-3 font-italic text-muted">' . explode(':', $like['date'])[0] . 'h </span>';
                echo '</div>';
            }
        }
        ?>
    </div>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
