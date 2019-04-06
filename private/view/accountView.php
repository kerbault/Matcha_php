<?php $title = "Your account"; ?>

<?php ob_start(); ?>
<div class="jumbotron text-center">
    <h1>We need more information</h1>
    <p>Those infos can be modified later</p>
</div>
<?php
if (isset($error)) {
    switch ($error) {
        case 0:
            echo '<div class="container alert alert-primary text-center" role="alert">Your account is now complete, you may browse to find your soul mate now.</div>';
            break;
        case 1:
            echo '<div class="container alert alert-danger text-center" role="alert">Wrong gender pick</div>';
            break;
        case 2:
            echo '<div class="container alert alert-danger text-center" role="alert">Wrong orientation pick</div>';
            break;
        case 3:
            echo '<div class="container alert alert-danger text-center" role="alert">Something went wrong with the file you tried to upload</div>';
            break;
        case 4:
            echo '<div class="container alert alert-danger text-center" role="alert">You bio is too long or too short</div>';
            break;
        case 5:
            echo '<div class="container alert alert-danger text-center" role="alert">Something went wrong with the tags</div>';
            break;
        default:
            echo '<div class="container alert alert-danger text-center" role="alert">Unknown case</div>';
            break;
    }
} ?>
<div class="container">
    <div class="container">
        <h2>Gender</h2>
        <select class="form-control ml-1" id="inputGender" name="inputGender">
            <option value="1">
                --
            </option>
            <option value="2">
                Male
            </option>
            <option value="3">
                Female
            </option>
        </select>
    </div>
    <div class="container mt-4">
        <h2>Sexual orientation</h2>
        <select class="form-control ml-1" id="inputOrientation" name="inputOrientation">
            <option value="1">
                Bisexual
            </option>
            <option value="2">
                Heterosexual
            </option>
            <option value="3">
                Homosexual
            </option>
        </select>
    </div>
    <div class="container mt-4">
        <h2>Your profile picture</h2>
        <div class="form-group">
            <div id="uploadInput">Upload a picture to continue :
                <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" required>
            </div>
            <br>
            <div class="outerFrame">
                <div class="captureFrame card">
                    <img src="./public/img/you_photo_here.png" id="photo" title="your picture"
                         alt="The screen capture will appear in this box.">
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <h2>Your bio</h2>
        <div class="form-group" id="inputBio">
			<textarea class="form-control" rows="5" id="bio" required
                      name="bio"></textarea>
        </div>
    </div>
    <div class="container mt-4">
        <h2>Your interests</h2>
        <div class="form-group">
            <input id="inputTag" type="text" class="form-control" id="inputTag">
            <div class="row" id="userTags">
            </div>
            <div class="row" id="listTags">
            </div>
        </div>
    </div>
    <div class="container mt-4 text-right">
        <button id="submit_form" class="btn btn-primary mb-4" id="inputSubmit">Submit</button>
    </div>
</div>
<script src="/public/js/account.js"></script>
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>
