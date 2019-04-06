<?php $title = "Search"; ?>
<?php ob_start(); ?>
<link type="text/css" rel="stylesheet" href="/public/libraries/ntslider/dist/jquery.nstSlider.css">

<div class="jumbotron text-center">
    <?php
    if (!isset($profiles)) {
        echo '<h2 class="text-primary">We were not able to find any profiles</h2>';
    } else {
        echo '<h2 class="text-primary">We found ' . ($pages * 15) . ' users</h2>';
    }
    ?>
</div>
<?php
if (isset($profiles) && $profiles != "empty") {
    ?>
    <div class="d-inline">
        <h3>Order by</h3>
    </div>
    <div class="d-flex">
        <div class="d-inline ml-2">
			<?php
			if (isset($url[1]) && $url[1] == 'filter') {
				if (isset($url[2]) && isset($url[3]) && is_numeric($url[3]) && isset($url[4]) && is_numeric($url[4])
					&& isset($url[5]) && is_numeric($url[5]) && isset($url[6]) && is_numeric($url[6]) && isset($url[7]) && is_numeric($url[7]) && isset($url[8]) && is_numeric($url[8]) && isset($url[9])) {
						$option = true;
					}
				}
			?>
            <select id="select_sort" class="form-control ml-1" name="order">
				<?php if (isset($option) && $url[2] != "false") {
					if ($url[2] == "ageA") {
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
					else if ($url[2] == "ageD") {
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
					else if ($url[2] == "locA") {
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
					else if ($url[2] == "locD") {
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
					else if ($url[2] == "popA") {
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
					else if ($url[2] == "popD") {
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
    </div>
    <div class="mt-3">
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
					<div id="slider-1" class="nstSlider mx-2 bg-primary" data-range_min="18" data-range_max="99"
						 data-cur_min="<?= $url[3] ?>" data-cur_max="<?= $url[4] ?>" style="width: 8vw; min-width: 200px">
						<div class="bar"></div>
						<div class="leftGrip"></div>
						<div class="rightGrip"></div>
					</div>
					<div class="rightLabel"></div>
				</div>
			</div>
			<div class="col mx-1">
				<h5 class="text-center">Location</h5>
				<div class="d-inline-flex mt-2">
					<div class="leftLabel"></div>
					<div id="slider-2" class="nstSlider mx-2 bg-primary" data-range_min="0" data-range_max="500"
						 data-cur_min="<?= $url[5] ?>" data-cur_max="<?= $url[6] ?>" style="width: 8vw; min-width: 200px">
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
					<div id="slider-3" class="nstSlider mx-2 bg-primary" data-range_min="0" data-range_max="1000"
						 data-cur_min="<?= $url[7] ?>" data-cur_max="<?= $url[8] ?>" style="width: 8vw;min-width: 200px">
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
				<button id="search_button" class="d-inline btn btn-primary mt-3 ml-3">Submit</button>
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
                    <div id="slider-1" class="nstSlider mx-2 bg-primary" data-range_min="18" data-range_max="99"
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
                <div class="d-inline-flex mt-2">
                    <div class="leftLabel"></div>
                    <div id="slider-2" class="nstSlider mx-2 bg-primary" data-range_min="0" data-range_max="1000"
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
                         data-cur_min="0" data-cur_max="300" style="width: 8vw;min-width: 200px">
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
                <button id="search_button" class="d-inline btn btn-primary mt-3 ml-3">Submit</button>
            </div>
        </div>
		<?php
		}
		?>
    </div>
    <div class="container mt-4">
		<div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th scope="col"><h4>#</h4></th>
                <th scope="col"><h4>Image</h4></th>
                <th scope="col"><h4>Pseudonyme</h4></th>
                <th scope="col"><h4>Name</h4></th>
                <th scope="col"><h4>Age</h4></th>
                <th scope="col"><h4>Distance</h4></th>
                <th scope="col"><h4>Popularity</h4></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($profiles as $key => $profile) {
                ?>
                <tr>
                    <th scope="row"><p class="mt-4"><?= (($page * 15) + ($key + 1)) ?></p></th>
                    <td><img class="card-img-top ml-4 mt-1" src="<?= $profile['img'] ?>" alt="Card image cap"
                             style="object-fit: cover; width: 70px; height: 70px"></td>
                    <td><a class="mt-4" href="/user/<?= $profile['username'] ?>"><?= $profile['username'] ?></a></td>
                    <td><p class="mt-4"><?= $profile['firstName'] ?></p></td>
                    <td><p class="mt-4"><?= $profile['birthDate'] ?></p></td>
                    <td><p class="mt-4"><?= $profile['distance'] ?> km</p></td>
                    <td><p class="mt-4"><?= $profile['popularity'] ?></p></td>
                    <td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
	</div>
    </div>
    <nav aria-label="..." class="mt-3">
        <ul class="pagination" id="pagination">
            <?php
            for ($i = 0; $i < $pages; $i++) {
                if ($i == $page) {
                    echo '<li class="page-item active" aria-current="page">';
                    echo '<a class="page-link">' . ($i + 1) . '</a><span class="sr-only">(current)</span>';
                    echo '</li>';
                } else {
                    echo '<li class="page-item"><a class="page-link">' . ($i + 1) . '</a></li>';
                }
            }
            ?>
        </ul>
    </nav>
    <?php
}
?>

<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>

<script src="/public/js/search.js"></script>
<script src="/public/libraries/ntslider/dist/jquery.nstSlider.min.js"></script>
