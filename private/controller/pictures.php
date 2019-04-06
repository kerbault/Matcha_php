<?php
/**
 * Created by PhpStorm.
 * User: kerbault
 * Date: 2019-03-31
 * Time: 15:48
 */

define("WIDTH", 400);
define("HEIGHT", 400);

function imageResize($imageResourceId, $width, $height)
{
    $src_ratio = $width / $height;

    if (1 > $src_ratio) {
        $new_h = WIDTH / $src_ratio;
        $new_w = WIDTH;
    } else {
        $new_w = HEIGHT * $src_ratio;
        $new_h = HEIGHT;
    }
    $x_mid = $new_w / 2;
    $y_mid = $new_h / 2;
    $resizedPicture = imagecreatetruecolor(WIDTH, HEIGHT);
    imagesavealpha($resizedPicture, true);
    $trans_background = imagecolorallocatealpha($resizedPicture, 0, 0, 0, 127);
    imagefill($resizedPicture, 0, 0, $trans_background);
    $newpic = imagecreatetruecolor(round($new_w), round($new_h));
    imagecopyresampled($newpic, $imageResourceId, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
    $resizedPicture = imagecreatetruecolor(WIDTH, HEIGHT);
    imagecopyresampled($resizedPicture, $newpic, 0, 0, ($x_mid - (WIDTH / 2)),
        ($y_mid - (HEIGHT / 2)), WIDTH, HEIGHT, WIDTH, HEIGHT);
    return $resizedPicture;
}

function uploadPicture($fileNameBase, $fileToUpload, $setProfilePic)
{
    $userInfo = new userInfo();
    $picturesManager = new pictures();

    $userInfo->fetchAll($_SESSION['username']);
    $allowedSize = 2000000;
    $allowed_file_types = array('.jpg', '.png', '.jpeg');
    $targetSubDir = $userInfo->id . "_" . $_SESSION['username'];
    $targetDir = "public/userPictures/" . $targetSubDir . "/";

    if ($picturesManager->countUserPicture($userInfo->id) >= 5) {
        $error = 'You already have 5 pictures, please remove a picture before uploading a new one';
        return $error;
    } else if (isset($fileToUpload['error']) && $fileToUpload['error'] == 0) {
        $fileName = $fileToUpload["name"];
        $fileBasename = substr($fileName, 0, strripos($fileName, '.'));
        $fileExt = substr($fileName, strripos($fileName, '.'));
        $sourceProperties = getimagesize($fileToUpload["tmp_name"]);

        if ($sourceProperties[0] == 0 || $sourceProperties[1] == 0) {
            $error = 'not a picture';
            return $error;
        }

        $fileSize = $fileToUpload["size"];
        $newFileName = $fileNameBase . ".png";

        if ($fileSize > $allowedSize) {
            $error = 'The picture your tried to upload is too big';
            return $error;
        } elseif ($fileBasename === NULL) {
            $error = 'Something went wrong with your picture';
            return $error;
        } elseif (!in_array($fileExt, $allowed_file_types)) {
            unlink($fileToUpload["tmp_name"]);
            $error = 'Something went wrong with your picture';
            return $error;
        } elseif (file_exists($targetDir . $newFileName)) {
            $error = 'Your picture already exist';
            return $error;
        } else {
            if (!file_exists('public/userPictures')) {
                mkdir('public/userPictures');
            }
            if (!file_exists('public/userPictures/' . $targetSubDir)) {
                mkdir('public/userPictures/' . $targetSubDir);
            }
            switch ($fileExt) {
                case ".png":
                    $imageResourceId = imagecreatefrompng($fileToUpload["tmp_name"]);
                    $resizedPicture = imageResize($imageResourceId, $sourceProperties[0], $sourceProperties[1]);
                    imagepng($resizedPicture, $targetDir . $newFileName);
                    break;
                case ".jpeg":
                    $imageResourceId = imagecreatefromjpeg($fileToUpload["tmp_name"]);
                    $resizedPicture = imageResize($imageResourceId, $sourceProperties[0], $sourceProperties[1]);
                    imagepng($resizedPicture, $targetDir . $newFileName);
                    break;
                case ".jpg":
                    $imageResourceId = @imagecreatefromjpeg($fileToUpload["tmp_name"]);
                    $resizedPicture = imageResize($imageResourceId, $sourceProperties[0], $sourceProperties[1]);
                    imagepng($resizedPicture, $targetDir . $newFileName);
                    break;
                default:
                    echo "Invalid Image type.";
                    exit;
                    break;
            }
            $picturesManager->registerPicture('/' . $targetDir . $newFileName, $userInfo->id, $setProfilePic);
            $error = 'nope';
            return $error;
        }
    } else {
        $error = 'Unknown case';
        return $error;
    }
}

function remPicture($pictureID)
{
    $user = new user();
    $picturesManager = new pictures();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $ownerPictureID = $picturesManager->fetchInfo($pictureID, 'users_ID');
    $profilePictureID = $user->fetchInfo($_SESSION['username'], 'profilePicture_ID');

    if ((($userID === $ownerPictureID && verifyStatus() > 3) || verifyStatus() > 4) && $profilePictureID != $pictureID) {
        $picturesManager = new pictures();
        $deleted = $picturesManager->remPicture($pictureID);
        if ($deleted['path'] != "") {
            unlink('.' . $deleted['path']);
            $error = 'nope';
            return $error;

        }
    } else {
        $error = 'You cannot do that';
        return $error;
    }
}

function setProfilePicture($pictureID)
{
    $user = new user();
    $picturesManager = new pictures();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $ownerPictureID = $picturesManager->fetchInfo($pictureID, 'users_ID');
    $profilePictureID = $user->fetchInfo($_SESSION['username'], 'profilePicture_ID');

    if ((($userID === $ownerPictureID && verifyStatus() > 3) || verifyStatus() > 4) && $profilePictureID != $pictureID) {
        $picturesManager = new pictures();
        $picturesManager->registerMainPicture($userID, $pictureID);
        $error = 'nope';
        return $error;
    } else {
        $error = 'You cannot do that';
        return $error;

    }
}
