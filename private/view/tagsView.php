<?php
/**
 * Created by PhpStorm.
 * User: kerbault
 * Date: 2019-04-02
 * Time: 15:48
 */

$title = 'Account';

ob_start(); ?>
    <nav class="nav nav-pills nav-justified">
        <a class="nav-item nav-link " href="/account">Profile</a>
        <a class="nav-item nav-link" href="/account/pictures">Pictures</a>
        <a class="nav-item nav-link active" href="/account/tags">Tags</a>
        <a class="nav-item nav-link" href="/account/likes">Likes</a>
        <a class="nav-item nav-link" href="/account/viewed">Viewed</a>
        <a class="nav-item nav-link" href="/account/history">History</a>
		<a class="nav-item nav-link" href="/account/blocked">Blocked</a>
        <a class="nav-item nav-link" href="/account/security">Security</a>
        <?php if (verifyStatus() == 5) { ?><a class="nav-item nav-link" href="/account/admin">Admin</a><?php } ?>
    </nav>
    <div class="container">
		<div class="mt-4 form-group row">
            <label class="col-sm-2 col-form-label">Tags</label>
            <div class="col-sm-10">
				<input id="inputTag" type="text" class="form-control" id="inputTag">
				<div class="row mt-2 ml-2" id="userTags">
				</div>
				<div class="row mt-2 ml-2" id="listTags">
				</div>
            </div>
        </div>
		<div class="form-group row">
			<div class="text-right col">
				<button id="myButton" type="submit" class="btn btn-outline-secondary">Update</button>
			</div>
		</div>
	</div>
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>
<script src="/public/js/accountTags.js"></script>
