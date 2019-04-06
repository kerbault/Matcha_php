<?php $title = 'Sign up'; ?>

<?php ob_start(); ?>
<div class="jumbotron text-center">
    <h1>Sign up</h1>
</div>
<?php
if (isset($error)) {
    switch ($error) {
        case 0:
            echo '<div class="container alert alert-primary text-center" role="alert">A confirmation email have been sent, if you cannot find it, please check your spams</div>';
            break;
        case 1:
            echo '<div class="container alert alert-danger text-center" role="alert">Wrong name size</div>';
            break;
        case 2:
            echo '<div class="container alert alert-danger text-center" role="alert">Wrong birthdate</div>';
            break;
        case 3:
            echo '<div class="container alert alert-danger text-center" role="alert">Username or mail already exist</div>';
            break;
        case 4:
            echo '<div class="container alert alert-danger text-center" role="alert">Wrong email</div>';
            break;
        case 5:
            echo '<div class="container alert alert-danger text-center" role="alert">Confirm password different than original</div>';
            break;
        default:
            echo '<div class="container alert alert-danger text-center" role="alert">Unknown case</div>';
            break;
    }
}
?>
<form onsubmit="return validateForm()" class="container" method="post" action="">
    <div class="form-group">
        <label>First name</label>
        <input type="text" class="form-control" name="fname" id="inputFname" placeholder="Enter your first name"
               required>
        <div class="invalid-feedback">
            Your first name need to be between 3 and 20 characters!
        </div>
    </div>
    <div class="form-group">
        <label>Last name</label>
        <input type="text" class="form-control" name="lname" id="inputLname" placeholder="Enter your last name"
               required>
        <div class="invalid-feedback">
            Your last name need to be between 4 and 20 characters!
        </div>
    </div>
    <div class="form-group">
        <label>Date of Birth</label>
        <input type="date" class="form-control" name="date" id="inputDate" placeholder="Date of Birth" required>
        <div class="invalid-feedback">
            Your age is not valid, you need to be at least 18!
        </div>
    </div>
    <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" name="username" id="inputUsername" placeholder="Enter username"
               required>
        <div class="invalid-feedback">
            Your username need to be between 4 and 20 characters!!
        </div>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" class="form-control" name="email" id="inputEmail" placeholder="Enter email" required>
        <div class="invalid-feedback">
            Your email is not valid!
        </div>
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" class="form-control" name="pass1" id="inputPassword1" placeholder="Password" required>
        <div class="invalid-feedback">
            Your password need to be between 8 and 20 characters, contain at least one numeric digit, one uppercase and
            one lowercase letter!
        </div>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Repeat password</label>
        <input type="password" class="form-control" name="pass2" id="inputPassword2" placeholder="Password" required>
        <div class="invalid-feedback">
            Your passwords doesn't match!
        </div>
    </div>
    <input type="submit" class="btn btn-primary mt-2" id="inputSubmit" value="Submit">
</form>
<script src="../../public/js/signup.js"></script>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
