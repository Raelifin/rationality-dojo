<?php
include('database.php');

$userId = USERID;
$dojoId = $mysqli->real_escape_string($_POST['dojoId']);

if ($userId) {
	foreach ($_POST['prefs'] as $code=>$bid) {
		$code = $mysqli->real_escape_string(substr($code, 0, -3));
		$bid = $mysqli->real_escape_string($bid);
		$practiceId = $mysqli->query("SELECT * FROM practices WHERE code='$code'")->fetch_assoc()['id'];
		if ($practiceId) {
			if ($mysqli->query("SELECT * FROM preferences WHERE user_id='$userId' AND practice_id='$practiceId' AND dojo_id='$dojoId'")->num_rows > 0) {
				$mysqli->query("UPDATE preferences SET preference='$bid' WHERE user_id='$userId' AND practice_id='$practiceId' AND dojo_id='$dojoId'");
			} else {
				$mysqli->query("INSERT INTO preferences (user_id, practice_id, dojo_id, preference) VALUES ('$userId','$practiceId','$dojoId','$bid')");
			}
		}
	}
	//Todo: Compress the following code into one that handles admits, denys and bans.
	foreach ($_POST['newbieAdmitBids'] as $noobId=>$bid) {
		$noobId = $mysqli->real_escape_string(substr($noobId, 14, -8)); //Trim "possibleNewbieXAdmitBid" to just X
		$bid = $mysqli->real_escape_string($bid);
		if ($mysqli->query("SELECT * FROM noob_bids WHERE user_id='$userId' AND noob_auction_id='$noobId' AND outcome='admit'")->num_rows > 0) {
			$mysqli->query("UPDATE noob_bids SET bid='$bid' WHERE user_id='$userId' AND noob_auction_id='$noobId' AND outcome='admit'");
		} else {
			$mysqli->query("INSERT INTO noob_bids (user_id, noob_auction_id, bid, outcome) VALUES ('$userId','$noobId','$bid','admit')");
		}
	}
	foreach ($_POST['newbieDenyBids'] as $noobId=>$bid) {
		$noobId = $mysqli->real_escape_string(substr($noobId, 14, -7)); //Trim "possibleNewbieXDenyBid" to just X
		$bid = $mysqli->real_escape_string($bid);
		if ($mysqli->query("SELECT * FROM noob_bids WHERE user_id='$userId' AND noob_auction_id='$noobId' AND outcome='deny'")->num_rows > 0) {
			$mysqli->query("UPDATE noob_bids SET bid='$bid' WHERE user_id='$userId' AND noob_auction_id='$noobId' AND outcome='deny'");
		} else {
			$mysqli->query("INSERT INTO noob_bids (user_id, noob_auction_id, bid, outcome) VALUES ('$userId','$noobId','$bid','deny')");
		}
	}
	foreach ($_POST['newbieBanBids'] as $noobId=>$bid) {
		$noobId = $mysqli->real_escape_string(substr($noobId, 14, -6)); //Trim "possibleNewbieXBanBid" to just X
		$bid = $mysqli->real_escape_string($bid);
		if ($mysqli->query("SELECT * FROM noob_bids WHERE user_id='$userId' AND noob_auction_id='$noobId' AND outcome='ban'")->num_rows > 0) {
			$mysqli->query("UPDATE noob_bids SET bid='$bid' WHERE user_id='$userId' AND noob_auction_id='$noobId' AND outcome='ban'");
		} else {
			$mysqli->query("INSERT INTO noob_bids (user_id, noob_auction_id, bid, outcome) VALUES ('$userId','$noobId','$bid','ban')");
		}
	}
	echo 'All preferences updated!';
}

?>