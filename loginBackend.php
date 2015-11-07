<?php

include('database.php');

$rawPost = file_get_contents('php://input');

$url = 'https://accounts.google.com/o/oauth2/token';
$data = array('code' => $rawPost, 'grant_type' => 'authorization_code', 'redirect_uri'=>'postmessage', 'client_id'=>'804607250145-arr5d1d67pslbkk07do8vu7a4pv7ghs2.apps.googleusercontent.com', 'client_secret'=>'7w1zoidtjJDbdaVOmKfPtTW_');

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context1  = stream_context_create($options);
$connectionResults = json_decode(file_get_contents($url, false, $context1), true);

$context2 = stream_context_create(array(
    'http' => array(
        'header'  => 'Authorization: Bearer ' . $connectionResults['access_token']
    )
));
$result = json_decode(file_get_contents('https://www.googleapis.com/plus/v1/people/me?key=804607250145-arr5d1d67pslbkk07do8vu7a4pv7ghs2.apps.googleusercontent.com', false, $context2), true);

$users = $mysqli->query('SELECT * FROM users');

while($row = $users->fetch_assoc()){
	if (strcasecmp($row['name'], $result['displayName']) == 0) {
		if (strlen($row['googleId']) > 0) {
			if (strcmp($row['googleId'], $result['id']) == 0) {
				echo '{"response":"<p>Welcome, '.$row['name'].'!<br/>You should be redirected to the full site shortly...</p>", "pass":"'.$row['pass'].'"}';
				return;
			}
		} else {
			$newPass = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
			
			$mysqli->query("UPDATE users SET `pass`='$newPass', `googleId`='".$result['id']."' WHERE `id`=".$row['id']);
			
			echo '{"response":"<p>Welcome, '.$row['name'].'!<br/>You should be redirected to the full site shortly...</p>", "pass":"'.$newPass.'"}';
			return;
		}
	}
}

echo '{"response":"<p>Hello, '.$result['displayName'].'.<br/> You have not been given access to the site. This is probably because you aren\'t a member of the dojo, but if you are a member then you should probably send an email to somebody asking to be white-listed!</p>"}';
return;

?>