<?php $title = '404 Not Found'; ?>

<?php ob_start(); ?>

<div class="jumbotron text-center">
    <h1>404 NOT FOUND</h1>
</div>
<div class="container text-center">
    <img class="img-fluid" src="http://i.imgur.com/qhMbkGi.jpg">
    <p class="display-4">The page you requested does not exists or has been deleted</p>
</div>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
