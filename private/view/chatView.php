<?php $title = 'Chat'; ?>

<?php ob_start(); ?>
<div class="container row my-5">
    <div class="card col" style="width: 25rem;">
        <div class="card-header">
            <h5>Your matches</h5>
        </div>
        <ul id="matchList" class="list-group text-left" style="overflow-y: scroll;">
			<?php
			if (isset($matches) && !empty($matches))
			{
				foreach ($matches as $profile) {
					$birth = explode("-", $profile['birthDate']);
					$profile['age'] = (date("md", date("U", mktime(0, 0, 0, $birth[0], $birth[1], $birth[2]))) > date("md")
						? ((date("Y") - $birth[0]) - 1)
						: (date("Y") - $birth[0]));;
					?>
						<li class="list-group-item my-1" id="<?= htmlspecialchars($profile['userName'])?>" style="cursor: pointer;" >
						<div class="row justify-content-around align-items-center">
						<img class="my-1" style="object-fit: cover; width: 90px; height: 90px;" src="<?= htmlspecialchars($profile['img']) ?>">
					<?php
					if ($profile['genders_ID'] == 2) {
						echo '<a class="d-inlineblock"><a class="text-primary">' . htmlspecialchars($profile['userName']) . ',</a><a>' . htmlspecialchars($profile['age']) . ' yo</a>';
					} else {
						echo '<a class="d-inlineblock"><a style="color: #ff47e9;">' . htmlspecialchars($profile['userName']) . ',</a><a>' . htmlspecialchars($profile['age']) . ' yo</a>';
					}
					echo '</div>';
					echo '</li>';
				}
			}
			?>
        </ul>
    </div>
    <div class="container-fluid border">
        <div class="container" id="chatBox" style="height: 700px; overflow-y: scroll;">
            <ul id="messageList">
				<?php
				if (isset($messages) && isset($messages[0])) {
					foreach ($messages as $pm) {
						$time = explode(':', explode(' ',$pm['timestamp'])[1])[0] . " : " . explode(':', explode(' ', $pm['timestamp'])[1])[1];
						if ($pm['users_ID'] == 'me') {
							echo '<li class="me" id="' . $pm['private_id'] . '"><span class="tme">' . $time . '</span><a>' . $pm['message'] . '</a></li>';
						} else {
							echo '<li class="him" id="' . $pm['private_id'] . '"><span class="thim">' . $time . '</span><a>' . $pm['message'] . '</a></li>';
						}
					}
				}
				?>
            </ul>
        </div>
        <div id="messageForm" class="input-group my-1">
            <input id="messageInput" type="text" class="form-control" placeholder="Enter your message">
            <button id="messageButton" class="btn btn-outline-primary">Send</button>
        </div>
    </div>
</div>
<script src="/public/js/chat.js"></script>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
