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
    <a class="nav-item nav-link" href="/account/tags">Tags</a>
    <a class="nav-item nav-link" href="/account/likes">Likes</a>
    <a class="nav-item nav-link" href="/account/viewed">Viewed</a>
    <a class="nav-item nav-link" href="/account/history">History</a>
    <a class="nav-item nav-link" href="/account/blocked">Blocked</a>
    <a class="nav-item nav-link" href="/account/security">Security</a>
    <?php if (verifyStatus() == 5) { ?><a class="nav-item nav-link active" href="/account/admin">Admin</a><?php } ?>
</nav>
<br>
<form class="container" method="post" action="">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="userID">User</label>
        </div>
        <select class="custom-select" id="userID" name="userID">
            <option selected>Choose...</option>
            <?php
            $users->execute();
            Foreach ($users as $user) { ?>
                <option
                value="<?= $user['userName'] ?>"><?= $user['ID'] . " - " . $user['userName'] . " - " . $user['status'] ?></option><?php
            }
            $users->closeCursor();
            ?>
        </select>
    </div>
    <div class="input-group">
        <select class="custom-select" id="newRole" name="newRole">
            <option selected>Choose...</option>
            <?php
            $roles->execute();
            Foreach ($roles as $role) { ?>
                <option value="<?= $role['ID'] ?>"><?= $role['ID'] . " - " . $role['userStatus'] ?></option><?php
            }
            $users->closeCursor();
            ?>
        </select>
        <div class="input-group-append">
            <input type="submit" class="btn btn-outline-secondary" value="Update role" name="saveChange">
        </div>
    </div>
</form>
<br>
<table class="table table-striped">
    <thead>
    <tr class="bg-danger">
        <th scope="col">ID</th>
        <th scope="col">Banned users</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $bans->execute();

    Foreach ($bans as $banned) { ?>
        <tr>
        <th scope="row"><?= $banned['ID'] ?></th>
        <td><?= $banned['user'] ?></td>
        </tr><?php
    }
    $users->closeCursor();
    ?>
    </tbody>
</table>
<table class="table table-striped">
    <thead>
    <tr class="bg-warning">
        <th scope="col">ID</th>
        <th scope="col">Reported users</th>
        <th scope="col">reports</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $reports->execute();
    Foreach ($reports as $reported) { ?>
        <tr>
        <th scope="row"><?= $reported['ID'] ?></th>
        <td><?= $reported['user'] ?></td>
        <td><?= $reported['reports'] ?></td>
        </tr><?php
    }
    $users->closeCursor();
    ?>
    </tbody>
</table>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
