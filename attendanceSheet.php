<style>
</style>
<?php

include('database.php');

if ( ! LOGGED_IN || USERID != 1) { echo 'You must be the admin to mark attendance.'; return; }

$dojo_id = 2;

if ($_POST['practice']) {
	$practiceId = $mysqli->real_escape_string($_POST['practice']);
	foreach ($_POST as $key => $value) {
		if (substr($key,0,4) == "user") {
			$userId = $mysqli->real_escape_string($value);
			$mysqli->query("INSERT INTO attendance_markers (`user_id`, `practice_id`, `dojo_id`) VALUES ('$userId', '$practiceId', '$dojo_id')");
		}
	}
	
	echo '<div>';
	var_dump($_POST);
	echo '</div>';
}

?><form method="post"><div style="line-height: 150%;"><?php

$practices = $mysqli->query("SELECT * FROM practices");
$users = $mysqli->query("SELECT * FROM users");

while ($practice = $practices->fetch_assoc()) {
	echo '<div style="margin: 5px; background: #EEF; display: inline-block;"><input type="radio" name="practice" id="practice'.$practice['id'].'" value="'.$practice['id'].'"/><label for="practice'.$practice['id'].'">'.$practice['name'].'</label></div>';
}

echo '</div>';

while ($user = $users->fetch_assoc()) {
	$membership = $mysqli->query("SELECT * FROM user_dojo_membership WHERE user_id='".$user['id']."' AND dojo_id='".$dojo_id."'")->fetch_assoc();
	//$active = $mysqli->query("SELECT * FROM preferences WHERE user_id='".$user['id']."' AND dojo_id='".$dojo_id."' AND timestamp>now() - INTERVAL 60 DAY")->fetch_assoc();
	if ($membership) {
		echo '<div><input type="checkbox" id="user'.$user['id'].'" name="user'.$user['id'].'" value="'.$user['id'].'"/><label for="user'.$user['id'].'">'.$user['name'].'</label></div>';
	}
}
?>

<button type="submit">Save</button>
</form>