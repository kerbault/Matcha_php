<?php
/**
 * Created by PhpStorm.
 * User: kerbault
 * Date: 2019-04-02
 * Time: 14:48
 */

$title = 'Account';

ob_start(); ?>

    <nav class="nav nav-pills nav-justified">
        <a class="nav-item nav-link active" href="/account">Profile</a>
        <a class="nav-item nav-link" href="/account/pictures">Pictures</a>
        <a class="nav-item nav-link" href="/account/tags">Tags</a>
        <a class="nav-item nav-link" href="/account/likes">Likes</a>
        <a class="nav-item nav-link" href="/account/viewed">Viewed</a>
        <a class="nav-item nav-link" href="/account/history">History</a>
		<a class="nav-item nav-link" href="/account/blocked">Blocked</a>
        <a class="nav-item nav-link" href="/account/security">Security</a>
        <?php if (verifyStatus() == 5) { ?><a class="nav-item nav-link" href="/account/admin">Admin</a><?php } ?>
    </nav>
    <br>
    <form class="container" method="post" action="">
        <div class="form-group row">
            <label for="firstName" class="col-sm-2 col-form-label">First name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="firstName" name="firstName"
                       pattern="<?= RGX_REAL_NAME ?>"
                       title="Invalid first name"
                       placeholder="<?= $info->fname ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="lastName" class="col-sm-2 col-form-label">Last name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="lastName" name="lastName"
                       pattern="<?= RGX_REAL_NAME ?>"
                       title="Invalid last name"
                       placeholder="<?= $info->lname ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" name="email" title="Invalid email"
                       placeholder="<?= $info->email ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="city" class="col-sm-2 col-form-label">City</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="city" name="city"
                       title="Invalid city"
                       placeholder="<?= $info->city ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="bio" class="col-sm-2 col-form-label">Short Bio</label>
            <div class="col-sm-10">
                <textarea class="form-control" rows="5" id="bio" name="bio"
                          maxlength="255"
                          minlength="4"
                          title="The short bio must be between 4 and 255 characters"
                          placeholder="<?= $info->bio ?>"></textarea>
            </div>
        </div>
        <div class="form-group row">

            <label for="date" class="col-sm-2 col-form-label">Date of Birth</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" name="date" id="inputDate" value="<?= $info->birthDate ?>"
                       placeholder="Date of Birth">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputGender" class="col-sm-2 col-form-label">Gender</label>
            <select class="col-sm-3 ml-3 mr-3 form-control" id="inputGender" name="inputGender">
                <option value="2" <?php if ($info->gender == 2) echo 'selected' ?>>
                    Male
                </option>
                <option value="3" <?php if ($info->gender == 3) echo 'selected' ?>>
                    Female
                </option>
            </select>
        </div>
        <div class="form-group row">
            <label for="inputOrientation" class="col-sm-2 col-form-label">Orientation</label>
            <select class="col-sm-3 ml-3 mr-3 form-control" id="inputOrientation" name="inputOrientation">
                <option value="1" <?php if ($info->sexualOrientation == 1) echo 'selected' ?>>
                    Bisexual
                </option>
                <option value="2" <?php if ($info->sexualOrientation == 2) echo 'selected' ?>>
                    Heterosexual
                </option>
                <option value="3" <?php if ($info->sexualOrientation == 3) echo 'selected' ?>>
                    Homosexual
                </option>
            </select>
        </div>
        <div class="form-group row">
            <div class="text-right col">
                <input type="submit" class="btn btn-outline-secondary" value="Update" name="saveChange">
            </div>
        </div>
    </form>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
