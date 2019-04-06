<?php $title = 'Matcha'; ?>

<?php ob_start(); ?>

<link type="text/css" rel="stylesheet" href="/public/libraries/ntslider/dist/jquery.nstSlider.css">

<div class="jumbotron text-center">
    <?php
    if (!isset($profiles)) {
        echo '<h1 class="text-primary">You need to review your info before having any suggestions</h1>';
    } else if (isset($profiles) && $profiles == "empty") {
        echo '<h1 class="text-primary">We were not able to find any profiles compatible with you</h1>';
    } else {
        echo '<h1 class="text-primary">We found ' . sizeof($profiles) . ' users compatible with you</h1>';
    }
    ?>
</div>
<?php
if (isset($profiles) && $profiles != "empty") {
    ?>
    <form class="container form-group d-flex">
        <div class="d-inline">
            <h3>Order by</h3>
        </div>
        <div class="d-inline ml-2">
			<?php
				if (isset($_POST['sort']) && isset($_POST['aleft']) && isset($_POST['aright']) && isset($_POST['locleft']) && isset($_POST['locright']) && isset($_POST['popleft']) && isset($_POST['popright']) && isset($_POST['tags_array'])) {
					$option = true;
				}
			?>
            <select id="select_option" class="form-control ml-1" name="order">
				<?php if (isset($option) && $_POST['sort'] != "false") {
					if ($_POST['sort'] == "ageA") {
						?>
						<option>Choose ..</option>
						<option selected >Age ↗</option>
						<option>Age ↘</option>
						<option>Distance ↗</option>
						<option>Distance ↘</option>
						<option>Popularity ↗</option>
						<option>Popularity ↘</option>
						<?php
					}
					else if ($_POST['sort'] == "ageD") {
						?>
						<option>Choose ..</option>
						<option>Age ↗</option>
						<option selected >Age ↘</option>
						<option>Distance ↗</option>
						<option>Distance ↘</option>
						<option>Popularity ↗</option>
						<option>Popularity ↘</option>
						<?php
					}
					else if ($_POST['sort'] == "locA") {
						?>
						<option>Choose ..</option>
						<option>Age ↗</option>
						<option>Age ↘</option>
						<option selected >Distance ↗</option>
						<option>Distance ↘</option>
						<option>Popularity ↗</option>
						<option>Popularity ↘</option>
						<?php
					}
					else if ($_POST['sort'] == "locD") {
						?>
						<option>Choose ..</option>
						<option>Age ↗</option>
						<option>Age ↘</option>
						<option>Distance ↗</option>
						<option selected >Distance ↘</option>
						<option>Popularity ↗</option>
						<option>Popularity ↘</option>
						<?php
					}
					else if ($_POST['sort'] == "popA") {
						?>
						<option>Choose ..</option>
						<option>Age ↗</option>
						<option>Age ↘</option>
						<option>Distance ↗</option>
						<option>Distance ↘</option>
						<option selected >Popularity ↗</option>
						<option>Popularity ↘</option>
						<?php
					}
					else if ($_POST['sort'] == "popD") {
						?>
						<option>Choose ..</option>
						<option>Age ↗</option>
						<option>Age ↘</option>
						<option>Distance ↗</option>
						<option>Distance ↘</option>
						<option>Popularity ↗</option>
						<option selected >Popularity ↘</option>
						<?php
					}
				}
				else {
					?>
					<option>Choose ..</option>
					<option>Age ↗</option>
					<option>Age ↘</option>
					<option>Distance ↗</option>
					<option>Distance ↘</option>
					<option>Popularity ↗</option>
					<option>Popularity ↘</option>
					<?php
				}
				?>
            </select>
        </div>
    </form>
    <div class="ml-3 mt-3">
        <h3>Filter by</h3>
    </div>
    <div class="container">
		<?php
			if (isset($option)) {
		?>
        <div class="row">
            <div class="col mx-1">
                <h5 class="text-center">Age</h5>
                <div class="d-inline-flex mt-2">
                    <div class="leftLabel"></div>
                    <div id="slider-1" class="nstSlider mx-2 bg-primary" data-range_min="18" data-range_max="80"
                         data-cur_min="<?= $_POST['aleft'] ?>" data-cur_max="<?= $_POST['aright'] ?>" style="width: 8vw; min-width: 200px">
                        <div class="bar"></div>
                        <div class="leftGrip"></div>
                        <div class="rightGrip"></div>
                    </div>
                    <div class="rightLabel"></div>
                </div>
            </div>
            <div class="col mx-1">
                <h5 class="text-center">Location</h5>
                <div class="d-inline-flex mt-2" >
                    <div class="leftLabel"></div>
                    <div id="slider-2" class="nstSlider mx-2 bg-primary"  data-range_min="0"
                         data-range_max="500"
                         data-cur_min="<?= $_POST['locleft'] ?>" data-cur_max="<?= $_POST['locright'] ?>" style="width: 8vw; min-width: 200px">
                        <div class="bar"></div>
                        <div class="leftGrip"></div>
                        <div class="rightGrip"></div>
                    </div>
                    <div class="rightLabel"></div>
                </div>
            </div>
            <div class="col mx-1">
                <h5 class="text-center">Popularity</h5>
                <div class="d-inline-flex mt-2">
                    <div class="leftLabel"></div>
                    <div id="slider-3" class="nstSlider mx-2 bg-primary" data-range_min="0" data-range_max="500"
                         data-cur_min="<?= $_POST['popleft'] ?>" data-cur_max="<?= $_POST['popright'] ?>" style="width: 8vw; min-width: 200px">
                        <div class="bar"></div>
                        <div class="leftGrip"></div>
                        <div class="rightGrip"></div>
                    </div>
                    <div class="rightLabel"></div>
                </div>
            </div>
            <div class="col mx-1">
                <h5 class="text-center">Tags</h5>
                <input id="inputTag" type="text" class="form-control" id="inputTag">
                <div class="row mt-2 ml-2" id="userTags">
                </div>
                <div class="row mt-2 ml-2" id="listTags">
                </div>
            </div>
            <div class="col mx-1 mt-3">
                <button id="filter_button" class="d-inline btn btn-primary mt-3 ml-3">Submit</button>
            </div>
        </div>
    </div>
	<?php
		} else {
	?>
	<div class="row">
		<div class="col mx-1">
			<h5 class="text-center">Age</h5>
			<div class="d-inline-flex mt-2">
				<div class="leftLabel"></div>
				<div id="slider-1" class="nstSlider mx-2 bg-primary" data-range_min="18" data-range_max="80"
					 data-cur_min="18" data-cur_max="30" style="width: 8vw; min-width: 200px">
					<div class="bar"></div>
					<div class="leftGrip"></div>
					<div class="rightGrip"></div>
				</div>
				<div class="rightLabel"></div>
			</div>
		</div>
		<div class="col mx-1">
			<h5 class="text-center">Location</h5>
			<div class="d-inline-flex mt-2" >
				<div class="leftLabel"></div>
				<div id="slider-2" class="nstSlider mx-2 bg-primary"  data-range_min="0"
					 data-range_max="500"
					 data-cur_min="0" data-cur_max="100" style="width: 8vw; min-width: 200px">
					<div class="bar"></div>
					<div class="leftGrip"></div>
					<div class="rightGrip"></div>
				</div>
				<div class="rightLabel"></div>
			</div>
		</div>
		<div class="col mx-1">
			<h5 class="text-center">Popularity</h5>
			<div class="d-inline-flex mt-2">
				<div class="leftLabel"></div>
				<div id="slider-3" class="nstSlider mx-2 bg-primary" data-range_min="0" data-range_max="500"
					 data-cur_min="0" data-cur_max="300" style="width: 8vw; min-width: 200px">
					<div class="bar"></div>
					<div class="leftGrip"></div>
					<div class="rightGrip"></div>
				</div>
				<div class="rightLabel"></div>
			</div>
		</div>
		<div class="col mx-1">
			<h5 class="text-center">Tags</h5>
			<input id="inputTag" type="text" class="form-control" id="inputTag">
			<div class="row mt-2 ml-2" id="userTags">
			</div>
			<div class="row mt-2 ml-2" id="listTags">
			</div>
		</div>
		<div class="col mx-1 mt-3">
			<button id="filter_button" class="d-inline btn btn-primary mt-3 ml-3">Submit</button>
		</div>
	</div>
</div>
    <?php
}
?>
<div class="container">
    <div class="row">
        <?php
            foreach ($profiles as $user) {
				echo "<div class='col'>";
                if ($user['username'] != $_SESSION['username']) {
                    ?>
                    <div class="card mt-4" style="width: 15rem;">
                        <img class="card-img-top ml-4 mt-1" src="<?= htmlspecialchars($user['img']) ?>"
                             alt="Card image cap" style="object-fit: cover; width: 200px; height: 200px">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($user['username']) ?>
                                - <?= htmlspecialchars($user['birthDate']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($user['shortBio'], 0, 50)) ?></p>
                            <p>At <?= strrev(chunk_split(strrev(htmlspecialchars($user['distance'])), 4, ' ')) ?>
                                km</p>
                            <a href="user/<?= $user['username'] ?>" class="btn btn-primary">Check user's profile</a>
                        </div>
                    </div>
                    <?php
                }
				echo "</div>";
            }
        }
        ?>
    </div>
</div>
<?php
$content = ob_get_clean();
require('template.php');
?>

<script src="/public/js/suggest.js"></script>
<script src="/public/libraries/ntslider/dist/jquery.nstSlider.min.js"></script>
