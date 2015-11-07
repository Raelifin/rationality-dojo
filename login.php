<?php

include('core.php');

head('Login', 'no-sidebar');

?>
		<!-- Main -->
			<article id="main">

				<header class="special container">
					<span class="icon fa-key"></span>
					<h2><strong>Login</strong></h2>
				</header>
					
				<!-- One -->
					<section class="wrapper style4 container">
					
						<!-- Content -->
							<div class="content">
								<section id="loginSection">
									<!--<a href="#" class="image featured"><img src="images/pic04.jpg" alt="" /></a>-->
									<p>Our only option thus far: <br/>
<div id="signinButton">
  <span class="g-signin"
    data-scope="profile"
    data-clientid="804607250145-arr5d1d67pslbkk07do8vu7a4pv7ghs2.apps.googleusercontent.com"
    data-redirecturi="postmessage"
    data-accesstype="offline"
    data-cookiepolicy="single_host_origin"
    data-callback="signinCallback"
	
	data-approvalprompt="force"
	
	data-height="tall"
	data-width="wide">
  </span>
</div>
<div id="result"></div>
									</p>
								</section>
							</div>

					</section>
					
			</article>
<?php

foot();

?>