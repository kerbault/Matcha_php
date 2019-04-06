<?php
$title = 'Account';

ob_start(); ?>
<form method="post" enctype="multipart/form-data" action="">
    <nav class="nav nav-pills nav-justified">
        <a class="nav-item nav-link " href="/account">Profile</a>
        <a class="nav-item nav-link" href="/account/pictures">Pictures</a>
        <a class="nav-item nav-link" href="/account/tags">Tags</a>
        <a class="nav-item nav-link" href="/account/likes">Likes</a>
        <a class="nav-item nav-link" href="/account/viewed">Viewed</a>
        <a class="nav-item nav-link" href="/account/history">History</a>
        <a class="nav-item nav-link active" href="/account/blocked">Blocked</a>
        <a class="nav-item nav-link" href="/account/security">Security</a>
        <?php if (verifyStatus() == 5) { ?><a class="nav-item nav-link" href="/account/admin">Admin</a><?php } ?>
    </nav>
</form>
<div class="container mt-4">
    <?php
    if (is_array($res)) {
        foreach ($res as $block) {
            ?>
            <a>- To unblock </a><a class="text-muted"><?= $block['blocked_name'] ?></a><a> please click </a><a
                    href="/unblock/<?= $block['blocked_name'] ?>">here</a>
            <br>
            <?php
        }
    }
    ?>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
