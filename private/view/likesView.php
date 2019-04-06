<?php
/**
 * Created by PhpStorm.
 * User: kerbault
 * Date: 2019-04-05
 * Time: 18:20
 */

$title = 'Account';

ob_start(); ?>
<nav class="nav nav-pills nav-justified">
    <a class="nav-item nav-link" href="/account">Profile</a>
    <a class="nav-item nav-link" href="/account/pictures">Pictures</a>
    <a class="nav-item nav-link" href="/account/tags">Tags</a>
    <a class="nav-item nav-link active" href="/account/likes">Likes</a>
    <a class="nav-item nav-link" href="/account/viewed">Viewed</a>
    <a class="nav-item nav-link" href="/account/history">History</a>
    <a class="nav-item nav-link" href="/account/blocked">Blocked</a>
    <a class="nav-item nav-link" href="/account/security">Security</a>
    <?php if (verifyStatus() == 5) { ?><a class="nav-item nav-link" href="/account/admin">Admin</a><?php } ?>
</nav>
<br>
<div class="row">
    <ul class="list-group col">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <h1>You like </h1>
            <span class="badge badge-primary badge-pill"></span>
        </li>
        <?php
        foreach ($listLiked as $liked) {
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="/user/<?= $liked['userName'] ?>"><?= $liked['userName'] ?></a>
                <span class="badge badge-primary badge-pill"><?= $liked['date'] ?></span>
            </li>
            <?php
        }
        ?>
    </ul>
    <ul class="list-group col">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <h1>Match</h1>
            <span class="badge badge-primary badge-pill"></span>
        </li>
        <?php
        if (is_array($matches)) {
            foreach ($matches as $match) {
                ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="/user/<?= $match['userName'] ?>"><?= $match['userName'] ?></a>
                    <span class="badge badge-primary badge-pill"></span>
                </li>
                <?php
            }
        }
        ?>
    </ul>
    <ul class="list-group col">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <h1>Like you</h1>
            <span class="badge badge-primary badge-pill"></span>
        </li>
        <?php
        foreach ($listLikedBy as $likedBy) {
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="/user/<?= $likedBy['userName'] ?>"><?= $likedBy['userName'] ?></a>
                <span class="badge badge-primary badge-pill"><?= $likedBy['date'] ?></span>
            </li>
            <?php
        }
        ?>
    </ul>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
