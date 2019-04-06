<?php
/**
 * Created by PhpStorm.
 * User: kerbault
 * Date: 2019-04-02
 * Time: 15:47
 */

$title = 'Account';

ob_start();
if (isset($error)) {
    if ($error != 'nope') {
        echo '<div class="container alert alert-danger text-center" role="alert">' . $error . '</div>';
    }
} ?>

<form method="post" enctype="multipart/form-data" action="" onchange="preview_image(event);">
    <nav class="nav nav-pills nav-justified">
        <a class="nav-item nav-link " href="/account">Profile</a>
        <a class="nav-item nav-link active" href="/account/pictures">Pictures</a>
        <a class="nav-item nav-link" href="/account/tags">Tags</a>
        <a class="nav-item nav-link" href="/account/likes">Likes</a>
        <a class="nav-item nav-link" href="/account/viewed">Viewed</a>
        <a class="nav-item nav-link" href="/account/history">History</a>
        <a class="nav-item nav-link" href="/account/blocked">Blocked</a>
        <a class="nav-item nav-link" href="/account/security">Security</a>
        <?php if (verifyStatus() == 5) { ?><a class="nav-item nav-link" href="/account/admin">Admin</a><?php } ?>
    </nav>
    <br>
    <div class="input-group mb-3">
        <div class="custom-file">
            <input class="custom-file-input" type="file" name="fileToUpload" id="fileToUpload" accept="image/*">
            <label class="custom-file-label" for="fileToUpload" aria-describedby="inputGroupFileAddon02">Choose
                file</label>
        </div>
        <div class="input-group-append">
            <button class="input-group-text" id="inputGroupFileAddon02">Upload</button>
        </div>
    </div>
</form>
<div class="row">
    <?php
    foreach ($pictureList as $picture) {
        ?>
        <div class="col">
            <div class="card center" style="width: 18rem;">
                <img src="<?= $picture['path'] ?>" class="card-img-top" alt="...">
                <?php if ($profilePicID != $picture['ID']) {
                    ?>
                    <div class="card-body text-center">
                        <a href="/account/pictures/setProfile/<?= $picture['ID'] ?>" class="card-link">Set as
                            profile</a>
                        <a href="/account/pictures/remove/<?= $picture['ID'] ?>" class="card-link">Remove</a>
                    </div>
                <?php } else { ?>
                    <div class="card-body text-center">
                        <a>Active</a>
                    </div><?php } ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<script src="/public/js/uploadPreview.js"></script>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
