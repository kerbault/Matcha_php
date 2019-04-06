<?php
/**
 * Created by PhpStorm.
 * User: kerbault
 * Date: 2019-04-04
 * Time: 19:00
 */

$title = 'Recovery';

ob_start();
if (isset($valid)) {
    if ($valid == "OK") {
        echo '<div class="container alert alert-primary text-center" role="alert">A mail has been sent to the existing mail</div>';
    }
}
?>
    <form method="post" action="">
        <div class="form-group">
            <label for="email13">Email address</label>
            <input type="email" class="form-control" id="email13" aria-describedby="emailHelp"
                   placeholder="Enter email" name="email13">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>