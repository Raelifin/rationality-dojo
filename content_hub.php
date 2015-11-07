<?php

if ( ! defined('LOGGED_IN')) { return; }

$dojoMembership = [];

?>
		<!-- Main -->
			<article id="main">

				<header class="special container">
					<span class="icon fa-cogs"></span>
					<h2>Ohio Rationality Dojo<h2>
					<select id="dojoSelect" onchange="changeDojo()"><?php
					$dojos = $mysqli->query('SELECT * FROM dojos');
					while($dojo = $dojos->fetch_assoc()){
						$dId = $dojo['id'];
						$dojoMembership[$dId] = true;
						$member = $mysqli->query("SELECT * FROM user_dojo_membership WHERE user_id='".USERID."' AND dojo_id='".$dId."'")->fetch_assoc();
						echo '<option value="'.$dId.'"';
						if ( ! $dojoMembership[$dId]) {
							$dojoMembership[$dId] = false;
							echo 'disabled="disabled"';
						}
						echo '>';
						echo $dojo['name'];
						echo '</option>';
					}
					?></select>
				</header>

<script>
var currentDojo=$('#dojoSelect').val();
var allBids = {};
var newMemberAdmitBids = {};
var newMemberDenyBids = {};
var newMemberBanBids = {};
var newMemberNames = {};
var newMemberStatus = {};
var newMemberBlurbs = {};
$('#dojoSelect option').each(function() {
	allBids['dojo'+$(this).val()] = {};
	newMemberAdmitBids['dojo'+$(this).val()] = {};
	newMemberDenyBids['dojo'+$(this).val()] = {};
	newMemberBanBids['dojo'+$(this).val()] = {};
	newMemberNames['dojo'+$(this).val()] = {};
	newMemberStatus['dojo'+$(this).val()] = {};
	newMemberBlurbs['dojo'+$(this).val()] = {};
});

function saveAll() {
	var sendData = {userId:<?php echo USERID; ?>, dojoId:currentDojo, prefs:{}, newbieAdmitBids:{}, newbieDenyBids:{}, newbieBanBids:{}};
	$('.bid').each(function() {
		sendData['prefs'][$(this).attr('id')] = $(this).val();
	});
	$('.personAdmitBid').each(function() {
		sendData['newbieAdmitBids'][$(this).attr('id')] = $(this).val();
	});
	$('.personDenyBid').each(function() {
		sendData['newbieDenyBids'][$(this).attr('id')] = $(this).val();
	});
	$('.personBanBid').each(function() {
		sendData['newbieBanBids'][$(this).attr('id')] = $(this).val();
	});
	$('.personProblemCheck').each(function() {
		sendData['newbieAdmitBids'][$(this).attr('id')] = $(this).prop('checked')?-3:0;
	});
	var timeout = setInterval(function () {alert('Unable to connect to server! Make sure you have Internet. X_X'); $('.bid').attr('disabled','disabled'); clearInterval(timeout);}, 1000);
	$.ajax({
		type: 'POST',
		url: 'api.php',
		success: function(result) {
			clearInterval(timeout);
			//alert(JSON.stringify(result));
		},
		data: sendData
	});
}

function changeDojo() {
	currentDojo = $('#dojoSelect').val();
	
	$('.bid').val('0');
	$('#possibleNewMembersList li').remove();
	
	for (var code in allBids['dojo'+currentDojo]) {
		$('#'+code).val(allBids['dojo'+currentDojo][code]);
	}
	for (var code in newMemberAdmitBids['dojo'+currentDojo]) {
		var possibleBlurb = '';
		if (newMemberBlurbs['dojo'+currentDojo][code]) {
			possibleBlurb = '<p class="description">'+newMemberBlurbs['dojo'+currentDojo][code]+'</p>';
		}
		
		var bidString = '';
		if (newMemberStatus['dojo'+currentDojo][code] == 'start') {
			bidString += '<input onchange="changePossibleNewbieBid(\''+code+'\',$(this).prop(\'checked\')?-3:0,\'admit\');" class="personProblemCheck" type="checkbox"';
			if (newMemberAdmitBids['dojo'+currentDojo][code] < 0) {
				bidString += ' checked';
			}
			bidString += ' id="possible'+code+'AdmitBid" /><label for="possible'+code+'AdmitBid">Please do *not* make this person a member: </label>';
		} else {
			bidString += '<input onchange="changePossibleNewbieBid(\''+code+'\',$(this).val(),\'admit\');" class="personAdmitBid personBid" type="number" min="-10" max="20" value="'+newMemberAdmitBids['dojo'+currentDojo][code]+'" id="possible'+code+'AdmitBid" /><label for="possible'+code+'AdmitBid">Make this person a member: </label>';
			bidString += '<br/><input onchange="changePossibleNewbieBid(\''+code+'\',$(this).val()),\'deny\';" class="personDenyBid personBid" type="number" min="-10" max="20" value="'+newMemberDenyBids['dojo'+currentDojo][code]+'" id="possible'+code+'DenyBid" /><label for="possible'+code+'DenyBid">Deny membership, allow as guest: </label>';
			bidString += '<br/><input onchange="changePossibleNewbieBid(\''+code+'\',$(this).val(),\'ban\');" class="personBanBid personBid" type="number" min="-10" max="20" value="'+newMemberBanBids['dojo'+currentDojo][code]+'" id="possible'+code+'BanBid" /><label for="possible'+code+'BanBid">Ban this person: </label>';
		}
		
		$('#possibleNewMembersList').append('<li class="expanded"><strong>'+newMemberNames['dojo'+currentDojo][code]+'</strong> '+bidString+possibleBlurb+'</li>');
	}
}

function changePracticeBid(practice, newBid) {
	allBids['dojo'+currentDojo][practice] = newBid;
	saveAll();
}

function changePossibleNewbieBid(newbie, newBid, outcome) {
	if (outcome == 'admit') {
		newMemberAdmitBids['dojo'+currentDojo][newbie] = newBid;
	} else if (outcome == 'deny') {
		newMemberDenyBids['dojo'+currentDojo][newbie] = newBid;
	} else if (outcome == 'ban') {
		newMemberBanBids['dojo'+currentDojo][newbie] = newBid;
	}
	saveAll();
}
</script>
				
				<!-- One -->
					<section class="wrapper style4 container">
					
					<h2>Welcome, <?php echo USERNAME; ?></h2>
					
					<p style="max-width: 800px;">To input your preferences for your local dojo go down the list below and enter a "bid" next to each possible practice topic. The size of your bid should represent how much you want that practice. 0 means you're ambivalent. 1 is the smallest amount of caring. 2 is twice the smallest amount. Etc. 5 is a typical "I want this!" level.</p>
					
					<p style="max-width: 800px;">A few days before the dojo the computer will pick the preference maximum. Then it will transfer an imaginary currency called "whuffie" from those that get the outcome they want to those who did not. Whuffie has a scaling effect for your preferences, meaning if you don't get what you want, each successive time you get more likely to get what you want.</p>
					
					<p style="max-width: 800px;">The first half-hour of dojo involves getting settled, talking about the material and reading, and answering questions. Then we'll do practice for about an hour (maybe a bit more). Then we'll have about fifteen minutes of discussion of metrics, homework, and meta discussion about dojo itself.</p>
					
					<p style="max-width: 800px;"><strong>NOTE:</strong> If there were guests at the last dojo you should see them show up with check-boxes next to their name, below. Check the box if you have a problem with this person becoming a member of dojo. (All data you submit is anonymous.) If anyone has a problem with guest, we'll talk about them at the next practice and then have an auction. Auction outcomes are: admitting the person as a member, banning them from future practices at that dojo, or letting them re-attend as a guest, but not have membership rights.</p>
					
<?php

$noobAuctions = $mysqli->query("SELECT * FROM noob_auctions WHERE status<>'done'");
$noobsToBidOn = false;
while($na = $noobAuctions->fetch_assoc()){
	if ( ! $dojoMembership[$na['dojo_id']]) { continue; }
	$noobsToBidOn = true;
	$admitBid = $mysqli->query("SELECT * FROM noob_bids WHERE user_id='".USERID."' AND noob_auction_id='".$na['id']."' AND outcome='admit'")->fetch_assoc();
	if ($admitBid) { $admitBid = $admitBid['bid']; } else { $admitBid = 0; }
	$denyBid = $mysqli->query("SELECT * FROM noob_bids WHERE user_id='".USERID."' AND noob_auction_id='".$na['id']."' AND outcome='deny'")->fetch_assoc();
	if ($denyBid) { $denyBid = $denyBid['bid']; } else { $denyBid = 0; }
	$banBid = $mysqli->query("SELECT * FROM noob_bids WHERE user_id='".USERID."' AND noob_auction_id='".$na['id']."' AND outcome='ban'")->fetch_assoc();
	if ($banBid) { $banBid = $banBid['bid']; } else { $banBid = 0; }
	echo '<script>';
	echo 'newMemberAdmitBids["dojo'.$na['dojo_id'].'"]["Newbie'.$na['id'].'"] = '.$admitBid.";\n";
	echo 'newMemberDenyBids["dojo'.$na['dojo_id'].'"]["Newbie'.$na['id'].'"] = '.$denyBid.";\n";
	echo 'newMemberBanBids["dojo'.$na['dojo_id'].'"]["Newbie'.$na['id'].'"] = '.$banBid.";\n";
	echo 'newMemberNames["dojo'.$na['dojo_id'].'"]["Newbie'.$na['id'].'"] = "'.$na['noob_name']."\";\n";
	echo 'newMemberStatus["dojo'.$na['dojo_id'].'"]["Newbie'.$na['id'].'"] = "'.$na['status']."\";\n";
	echo 'newMemberBlurbs["dojo'.$na['dojo_id'].'"]["Newbie'.$na['id'].'"] = "'.$na['blurb']."\";\n";
	echo '</script>'."\n";
}
if ($noobsToBidOn) {
	echo '<ul class="practiceList" id="possibleNewMembersList"><p class="listName">Possible New Members:</p></ul>';
}

$rawPractices = $mysqli->query("SELECT * FROM practices WHERE status='normal'");
$practices = [];
while($p = $rawPractices->fetch_assoc()){
	array_push($practices,$p);
}
function practCmp($a,$b) {
	if ($a['level'] == $b['level']) {
		return strcmp($a['name'], $b['name']);
	}
	return ($a['level'] < $b['level']) ? -1 : 1;
}
usort($practices, "practCmp");
$level = -2;
foreach($practices as $i=>$p) {
	if ($p['level'] > $level) {
		$level += 1;
		if ($level == -1) {
			echo '<ul class="practiceList"><p class="listName">Nonstandard Meetings:</p>';
		} elseif ($level == 0) {
			echo '</ul><ul class="practiceList"><p class="listName">Foundational Practices:</p>';
		} elseif ($level == 1) {
			echo '</ul><ul class="practiceList"><p class="listName">Beginner+ Practices:</p>';
		} elseif ($level == 2) {
			echo '</ul><ul class="practiceList"><p class="listName">Advanced Practices:</p>';
		}
	}
	$preferences = $mysqli->query("SELECT * FROM preferences WHERE practice_id='".$p['id']."' AND user_id='".USERID."';");
	while ($pref = $preferences->fetch_assoc()) {
		echo '<script>allBids["dojo'.$pref['dojo_id'].'"]["'.$p['code'].'Bid"] = '.$pref['preference'].';</script>'."\n";
	}
	//TODO: Change expand button from being text to being an image
	echo '<li><span class="expandButton">&#9654;</span> '.$p['name'].' <input onchange="changePracticeBid(\''.$p['code'].'Bid\',$(this).val());" class="bid" type="number" min="-5" max="20" value="0" id="'.$p['code'].'Bid"/><label for="'.$p['code'].'Bid">Bid: </label><p class="description">'.$p['desc'].'</p></li>'."\n";
}
echo '<script>changeDojo(currentDojo);</script>'."\n";
?>
</ul>

					</section>
					
			</article>