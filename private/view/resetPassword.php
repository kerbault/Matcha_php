<?php
/**
 * Created by PhpStorm.
 * User: kerbault
 * Date: 2019-04-05
 * Time: 22:33
 */

$title = 'Recovery';

ob_start();
if (isset($valid)) {
    switch ($valid) {
        case "OK":
            echo '<div class="container alert alert-primary text-center" role="alert">Your password has been changed with success you may now log in.</div>';
            break;
        case "KO":
            echo '<div class="container alert alert-danger text-center" role="alert">Something went wrong, please verify the passwords</div>';
            break;
    }
}
?>
    <form class="container" method="post" action="">
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