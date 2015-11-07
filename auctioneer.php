<style>
* {
	border: thin solid;
}
</style>
<?php

include('database.php');

if ( ! LOGGED_IN || USERID != 1) { echo 'You must be the admin to run the auctioneer.'; return; }

$realAuction = false;
if ($_GET['real']) {
	$realAuction = true;
}

$showData = false;
if ($_GET['visible']) {
	$showData = true;
}

if ( ! $showData && ! $realAuction) {
	echo 'You should either show data or have a real auction. This page does nothing by default.';
	return;
}

function argMaxRandomTiebreak($a) {
	$bestKeys = array();
	$bestVal = -999999999999999;
	foreach ($a as $key=>$value) {
		if ($value > $bestVal) {
			$bestKeys = array($key);
			$bestVal = $value;
		} elseif ($value == $bestVal) {
			array_push($bestKeys, $key);
		}
	}
	if (empty($bestKeys)) {
		return -1;
	} else {
		return $bestKeys[rand(0,count($bestKeys)-1)];
	}
}

function allArgMax($a) {
	$bestKeys = array();
	$bestVal = -999999999999999;
	foreach ($a as $key=>$value) {
		if ($value > $bestVal) {
			$bestKeys = array($key);
			$bestVal = $value;
		} elseif ($value == $bestVal) {
			array_push($bestKeys, $key);
		}
	}
	return $bestKeys;
}

$dojo_id = 2;

$practices = $mysqli->query("SELECT * FROM practices");

if ($realAuction) {
	$mysqli->query("INSERT INTO practice_auctions () VALUES ()");
	$auction_id = $mysqli->insert_id;
} else {
	$auction_id = 2;
}
	
$standings = [];

if ($showData) {
	echo '<table>';
	echo '<tr><td></td>';
}
$users = $mysqli->query("SELECT * FROM users");
while ($user = $users->fetch_assoc()) {
	$membership = $mysqli->query("SELECT * FROM user_dojo_membership WHERE user_id='".$user['id']."' AND dojo_id='".$dojo_id."'")->fetch_assoc();
	$active = $mysqli->query("SELECT * FROM preferences WHERE user_id='".$user['id']."' AND dojo_id='".$dojo_id."' AND timestamp>now() - INTERVAL 60 DAY")->fetch_assoc();
	if ($membership && $active) {
		$standing = $membership['whuffie'];
		if ($showData) { echo '<td>'.$user['name'].' ('.$standing.')</td>'; }
		$standings[$user['id']] = $standing;
	}
}
if ($showData) { echo '</tr>'; }

$bidTable = [];
$practiceNames = [];

while ($practice = $practices->fetch_assoc()) {
	if ($showData) { echo '<tr><td>'.$practice['name'].'</td>'; }
	$practiceNames[$practice['id']] = $practice['name'];
	$users = $mysqli->query("SELECT * FROM users");
	while ($user = $users->fetch_assoc()) {
		if (array_key_exists($user['id'], $standings)) {
			$preference = $mysqli->query("SELECT * FROM preferences WHERE user_id='".$user['id']."' AND practice_id='".$practice['id']."' AND dojo_id='".$dojo_id."'")->fetch_assoc()['preference'];
			if ( ! $preference) { $preference = 0; }
			if ( ! array_key_exists($user['id'], $bidTable)) {
				$bidTable[$user['id']] = [];
			}
			$bidTable[$user['id']][$practice['id']] = $preference;
			if ($showData) { echo '<td>'.$preference.'</td>'; }
		}
	}
	if ($showData) { echo '</tr>'; }
}
if ($showData) {
	echo '</table>';
	//var_dump($bidTable);
}

$utilitarianSums = [];

if ($showData) {
	echo '<table>';
	echo '<tr>';
}
foreach ($bidTable as $userId=>$thisUserBids) {
	$minBid = min($thisUserBids);
	if ($showData) { echo '<tr>'; }
	foreach ($thisUserBids as $practiceId=>$bid) {
		$bidTable[$userId][$practiceId] -= $minBid; //Avoid problems with negative numbers and people being dumb.
		$bid = $bidTable[$userId][$practiceId];
		if ($realAuction) {
			$mysqli->query("INSERT INTO bids (user_id, practice_auction_id, practice_id, bid) VALUES ('".$userId."', '".$auction_id."', '".$practiceId."', '".$bid."')");
		}
		$scaledBid = $bid*(1/(0.1+exp(-$standings[$userId]/10))); //Modified sigmoid
		if ($showData) { echo '<td>'.$bid.' ('.$scaledBid.')</td>'; }
		$utilitarianSums[$practiceId] += $scaledBid;
	}
	if ($showData) { echo '</tr>'; }
}
if ($showData) {
	echo '<tr>';
	foreach ($utilitarianSums as $practiceId=>$sum) {
		echo '<td>('.$sum.')</td>';
	}
	echo '</tr>';
	echo '</table>';
}

$bestKey = argMaxRandomTiebreak($utilitarianSums);
if ($showData) { var_dump($bestKey); }

$winners = array();
$earners = array();
$toGive = 0;
$totalDesire = 0;

foreach ($bidTable as $userId=>$thisUserBids) {
	if (in_array($bestKey, allArgMax($thisUserBids))) {
		$winners[$userId] = max($thisUserBids);
		$toGive += $winners[$userId];
	} else {
		if (array_key_exists($bestKey, $thisUserBids)) {
			$earners[$userId] = max($thisUserBids) - $thisUserBids[$bestKey];
		} else {
			$earners[$userId] = max($thisUserBids); //Assume vote of 0 in case of missing vote
		}
		$totalDesire += $earners[$userId];
	}
}
if ($showData) {
	var_dump($winners);
	var_dump($earners);
}

$whuffieDeltas = [];

$flow = min($totalDesire, $toGive);

foreach ($winners as $userId=>$contribution) {
	$whuffieDeltas[$userId] = -(1.0*$flow*$contribution/$toGive);
}
foreach ($earners as $userId=>$desire) {
	$whuffieDeltas[$userId] = 1.0*$flow*$desire/$totalDesire;
}

if ($showData) {
	echo '<p>';
	var_dump($whuffieDeltas);
	echo '</p>';
}

if ($realAuction) {
	foreach ($whuffieDeltas as $userId=>$change) {
		$mysqli->query("UPDATE `user_dojo_membership` SET `whuffie`=`whuffie`+".$change." WHERE `user_id`=".$userId." AND `dojo_id`=".$dojo_id);
	}

	$objDateTime = new DateTime('NOW');

	$to      = 'raelifin@gmail.com';
	$subject = '[DOJO] Auction Decided';
	$message = 'Auction decided at '.$objDateTime->format('c').'! Outcome: '.$practiceNames[$bestKey].'.';
	$headers = 'From: Dojo Robot <noreply@rationality-dojo.com>' . "\r\n" .
		'Reply-To: '.$_POST['name'].' <'.$_POST['email'].'>' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	if(mail($to, $subject, $message, $headers)) {
		$mailInfo = '<p>Email successfully <strong>sent</strong>.</p>';
	} else {
		$mailInfo = '<p style="color: rgb(255, 0, 0);">There was an <strong>error</strong> sending your message!</p>';
	}
		
	echo $mailInfo;
}
?>