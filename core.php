<?php

include('database.php');

function head($pageName, $bodyClass) {
?><!DOCTYPE HTML>
<!--
	Twenty by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title><?php echo $pageName; if ($pageName) {echo ' - ';} ?>Ohio Rationality Dojo</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/jquery.scrolly.min.js"></script>
		<script src="js/jquery.scrollgress.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-wide.css" />
			<link rel="stylesheet" href="css/style-noscript.css" />
		</noscript>
		<link rel="stylesheet" href="css/dojo.css" />
		<script src="https://apis.google.com/js/client:platform.js?onload=start" async defer></script>
		<script src="js/dojo.js"></script>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie/v9.css" /><![endif]-->
	</head>
	<body class="<?php echo $bodyClass; ?>">	
	<!-- Header -->
		<header id="header" class="skel-layers-fixed">
			<h1 id="logo"><a href="index.php">Ohio Rationality Dojo</a></h1>
			<nav id="nav">
				<ul>
					<?php if ( ! LOGGED_IN) { ?>
						<li><a href="FAQ.php">About</a></li>
						<li><a href="contact.php">Contact</a></li>
						<li><a href="login.php" class="button special">Log In</a></li>
					<?php } else { ?>
						<li><a href="index.php" onclick="document.cookie='pass=00000';" class="button special">Log Out</a></li>
					<?php } ?>
				</ul>
			</nav>
		</header><?php
}

function foot() {
?>		<!-- Footer -->
			<footer id="footer">
				
				<?php $x = rand(0,3);
				if ($x == 0) {
					echo '<p>Absence of evidence is evidence of absence.</p>';
				} elseif ($x == 1) {
					echo '<p>P(B|A) = P(A|B)P(B)/P(A)</p>';
				} elseif ($x == 2) {
					echo '<p>The Bayesian Conspiracy is watching...</p>';
				} else {
					echo '<p>Curiosity, relinquishment, lightness, evenness, argument, empiricism, simplicity, humility, perfectionism, precision, scholarship, and the void....</p>';
				}
				?>
				<ul class="copyright">
					<li>&copy; Max Harms</li>
					<li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
					<li>Image: <a href="https://www.flickr.com/photos/newdimensionfilms/7108632527/">Venus and The Night Sky Over Mammoth</a></li>
				</ul>
			</footer>

	</body>
</html><?php
}

?>