<?php $title = 'Login'; ?>

<?php ob_start(); ?>
<div class="jumbotron text-center">
    <h1>Login</h1>
</div>
<?php
if (isset($error)) {
    if ($error != 1) {
        echo '<div class="container alert alert-danger text-center" role="alert">' . $error . '</div>';
    }
}

?>
<form class="container" method="post" action="">
    <div class="form-group">
        <label for="inputUsername">Username</label>
        <input type="text" class="form-control" id="inputUsername" name="username" placeholder="Enter your username">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" class="form-control" id="inputPassword" name="password"
               placeholder="Enter your password">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <div class="text-right col">
        <a href="/forgot1st">Forgot information ?</a>
    </div>
</form>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
