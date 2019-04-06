<?php
/**
 * Created by PhpStorm.
 * User: kerbault
 * Date: 2019-04-03
 * Time: 17:46
 */

$title = 'Account';
ob_start(); ?>

<nav class="nav nav-pills nav-justified">
    <a class="nav-item nav-link" href="/account">Profile</a>
    <a class="nav-item nav-link" href="/account/pictures">Pictures</a>
    <a class="nav-item nav-link" href="/account/tags">Tags</a>
    <a class="nav-item nav-link" href="/account/likes">Likes</a>
    <a class="nav-item nav-link" href="/account/viewed">Viewed</a>
    <a class="nav-item nav-link" href="/account/history">History</a>
    <a class="nav-item nav-link" href="/account/blocked">Blocked</a>
    <a class="nav-item nav-link active" href="/account/security">Security</a>
    <?php if (verifyStatus() == 5) { ?><a class="nav-item nav-link" href="/account/admin">Admin</a><?php } ?>
</nav>
<br>
<?php
if (isset($error)) {
    if ($error === 'nope') {
        echo '<div class="container alert alert-primary text-center" role="alert">Password updated successfully</div>';
    } else {
        echo '<div class="container alert alert-danger text-center" role="alert">' . $error . '</div>';
    }
}
?>
<form class="container" method="post" action="">
    <div class="form-group row">
        <label for="oldPasswd" class="col-sm-2 col-form-label">Old password</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="oldPasswd" name="oldPasswd"
                   pattern="<?= RGX_PASSWD ?>"
                   title="Invalid password"
                   placeholder="Verify your password first">
        </div>
    </div>
    <div class="form-group row">
        <label for="newPasswd" class="col-sm-2 col-form-label">New password</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="newPasswd" name="newPasswd"
                   pattern="<?= RGX_PASSWD ?>"
                   title="Invalid password"
                   placeholder="Must contain between 8 and 20 characters, minuscule, capitalize and numbers">
        </div>
    </div>
    <div class="form-group row">
        <label for="confirmPasswd" class="col-sm-2 col-form-label">Confirm password</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="confirmPasswd" name="confirmPasswd"
                   pattern="<?= RGX_PASSWD ?>"
                   title="Invalid password"
                   placeholder="Must match with the previous one">
        </div>
    </div>
    <div class="form-group row">
        <div class="text-right col">
            <input type="submit" class="btn btn-outline-secondary" value="Update" name="saveChange">
        </div>
    </div>
</form>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
