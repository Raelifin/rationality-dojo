<?php

include('core.php');

head('', 'index');

if ( ! LOGGED_IN) {
	include('content_splash.php');
} else {
	include('content_hub.php');
}

foot();

?>