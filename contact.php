<?php

include('core.php');

head('Contact', 'contact');

?>
		<!-- Main -->
			<article id="main">

				<header class="special container">
					<span class="icon fa-envelope"></span>
					<h2>Get In Touch</h2>
<?php
$mailInfo = '<p>Use the form below to contact us. (It may take a moment to send.)</p>';
if ($_POST['message']) {
	$to      = 'raelifin@gmail.com';
	$subject = '[DOJO] '.$_POST['subject'];
	$message = 'Message from "'.$_POST['name'].'": '."\r\n".$_POST['message'];
	$headers = 'From: Dojo Webform <noreply@rationality-dojo.com>' . "\r\n" .
		'Reply-To: '.$_POST['name'].' <'.$_POST['email'].'>' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	if(mail($to, $subject, $message, $headers)) {
		$mailInfo = '<p>Email successfully <strong>sent</strong>.</p>';
	} else {
		$mailInfo = '<p style="color: rgb(255, 0, 0);">There was an <strong>error</strong> sending your message!</p>';
	}
}
echo $mailInfo;
?>
				</header>
					
				<!-- One -->
					<section class="wrapper style4 special container 75%">
					
						<!-- Content -->
							<div class="content">
								<form action="#" method="POST">
									<div class="row 50%">
										<div class="6u 12u(3)">
											<input type="text" name="name" placeholder="Name" />
										</div>
										<div class="6u 12u(3)">
											<input type="email" name="email" placeholder="Email" />
										</div>
									</div>
									<div class="row 50%">
										<div class="12u">
											<input type="text" name="subject" placeholder="Subject" />
										</div>
									</div>
									<div class="row 50%">
										<div class="12u">
											<textarea name="message" placeholder="Message" rows="7"></textarea>
										</div>
									</div>
									<div class="row">
										<div class="12u">
											<ul class="buttons">
												<li><input type="submit" class="special" value="Send Message" /></li>
											</ul>
										</div>
									</div>
								</form>
							</div>
							
					</section>
				
			</article>
<?php

foot();

?>