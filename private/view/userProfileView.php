<?php
$title = $username . "'s profile";
ob_start();
?>
<script type="text/javascript" src="/public/js/profile.js"></script>

<div class="jumbotron text-center">
    <h1 id="username" class="text-primary"><?= htmlspecialchars($username); ?></h1>
</div>
<div class="container border mb-2">
    <div class="row">
        <div class="col-sm-2">
            <img class="img-fluid mt-3" src="<?= htmlspecialchars($info->img); ?>">
        </div>
        <div class="col">
            <i class="fas fa-flag mt-2" id="report"
               style="font-size: 1.8em; color: #ff0000; position: absolute; right: 20px; cursor: pointer;"></i>
            <i class="fas fa-lock fa-lg" id="block"
               style="font-size: 1.8em; position: absolute; right: 20px; bottom: 1vw; cursor: pointer;"></i>
            <div class="my-2">
                <h2 class="d-inline"><?= htmlspecialchars($info->fname) . " " . htmlspecialchars($info->lname); ?></h2>
				<?php if (isset($info->online) && $info->online == '1') { ?>
					<div class="d-inline ml-2"><i class="far fa-circle" style="font-size: 1.3em; color: #11d600;"></i></div>
				<?php
				}
				else { ?>
                	<div class="d-inline ml-2"><i class="far fa-circle" style="font-size: 1.3em; color: #939393;"></i></div>
				<?php
					if (isset($info->lastlog) && $info->lastlog) {
						?>
							<span class="text-muted">Last log <?= htmlspecialchars($info->lastlog) ?></span>
						<?php
					}
				}
				?>
				<h5 class="mt-2">Member since <span><?= htmlspecialchars(explode(" ", $info->creationDate)[0]); ?></span></h5>
				<?php
					if (isset($info->city) && $info->city) {
					?>
						<h4>Based in <?= $info->city ?></h4>
					<?php
				}
				?>
				<h4><?= htmlspecialchars($info->age); ?> yo</h4>
                <h4><?php if ($info->gender == 2) echo 'Male'; else echo 'Female'; ?></h4>
                <h4><?php if ($info->sexualOrientation == 1) echo 'Bisexual'; else if ($info->sexualOrientation == 2) echo 'Heterosexual'; else echo 'Homosexual'; ?></h4>
                <p style="font-size: 1em;"><?= htmlspecialchars($info->bio); ?></p>
                <h4>Tags</h4>
                <div class="row mt-2 ml-2" id="userTags">
                    <?php
                    if (is_array($userTags)) {
                        foreach ($userTags as $tag) {
                            echo '<div><h3><span class="badge badge-primary mx-1">' . htmlspecialchars($tag) . '</span></h3></div>';
                        }
                    }
                    ?>
                </div>
                <img id="like" class="my-2 mt-3" src="../../public/img/not_liked.png" style="width: 80px; height:80px;">
                <div>
                    <h3 class="d-inline mt-5"><?= htmlspecialchars($info->popularity); ?></h3><h4 class="d-inline mt-5">
                        popularity points</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>
<script src="/public/js/profile.js"></script>
