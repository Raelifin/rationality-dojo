<?php

include('core.php');

$category_id = 1;

head('Caplan Test', 'index');

?>
		<!-- Main -->
			<article id="main">
			<header class="special container">
					<span class="icon fa-comments"></span>
					<h2>The Caplan Test</h2>
			</header>
			
			
		<section class="wrapper style4 container">

			<div style="text-align: center;">
			<header style="font-size: 130%">First: How would you best identify yourself?</header>
			<select style="font-size: 100%; font-weight: 400; margin-left: 20px; margin-top: 10px; margin-bottom: 10px;">
			<option value="-1">- Select an Option -</option>
			<?php
			$labels = $mysqli->query('SELECT * FROM caplan_labels WHERE category_id='.$category_id);
			while($label = $labels->fetch_assoc()){
				echo '<option value="'.$label['id'].'">'.$label['label'].'</option>';
			}
			?>
			<option value="0">None of these options describes me</option></select>
			</div>
			
			<hr style="border: thin solid #CCC; width: 50%"/>
		
			<div style="overflow: hidden;">
				<div style="float:left; padding: 10px; width:120px; text-align: center; border: thin solid grey;">
				<img src="http://www.gravatar.com/avatar/24ea99bb80eea86a38d9ccce53321038?default=identicon&s=100">
				Agnostic
				</div>
				<span style="font-size: 80%; color: #AAA; float: right; padding: 10px;" class="icon fa-flag"></span>
				<div style="padding: 10px; padding-left: 140px;">
				<strong>Prompt:</strong>
				<p>I'm curious when in life (if ever) people on here have deferred to their philosophical or theological systems over their intuitions. I'd love to hear personal stories.</p>
				</div>
			</div>
			
			<hr style="border: thin solid #CCC; width: 50%"/>
			
			<div style="overflow: hidden;">
				<div style="float:left; padding: 10px; width:120px; text-align: center; border: thin solid gray;">
				<img src="http://www.gravatar.com/avatar/e5643147188bac36573e84e57fbe7d5b?default=identicon&s=100">
				Atheist
				</div>
				<span style="font-size: 80%; color: #AAA; float: right; padding: 10px;" class="icon fa-flag"></span>
				<div style="padding: 10px; padding-left: 140px;">
				<strong>Response:</strong>
				<p>I've spent a lot of time in twelve-step circles. And while I don't practice the twelve steps (so the Higher Power question is moot for me) one of the best lessons I've received from that way of thinking is the idea that you should look for people in recovery who "have what you want." It took me a long time to learn how to do this. In the past, if I saw someone who seemed to be leading a great life without drugs or alcohol, I'd assume that this person was just naturally better than me in some way and that their path was obviously not open to me. Over time I began to "take suggestions," to just do what other people in recovery suggested to me, and so much of that stuff actually worked that I began to look around for people who had what I wanted and ask them about their daily habits, beliefs, etc.<br/>
				<br/>
This is a process, and it isn't a hard-and-fast rule--there are people whose programs of recovery I admire a lot whose moral beliefs I wouldn't necessarily defer to on all questions. I mean, I wouldn't vote the way they told me to, for example! (Although part of the reason I trust them is that they wouldn't ask me to.) But I think I've learned to recognize, at least to some extent, when people know more about life than I do, and when we differ on something, I've gotten a lot more patient and a lot more likely to accept their beliefs at least for the moment.<br/>
<br/>
You could argue that since I'm the one picking who to talk to and who to defer to, and since I reserve the right to disagree with everyone, I'm still basically trusting my own intuitions and opinions most. But from the inside it doesn't feel that way. When I talk with someone I respect in this way and they present a claim or an argument I disagree with, it often feels like I'm being surprised and guided in ways I simply couldn't do for myself. It feels more like the philosophy which begins in wonder (as vs. the philosophy which begins in doubt, yes, I was a philosophy major). And on a personal level the ongoing practice of humility I need in order to live well feels totally different from a purely self-guided, self-generated belief system. It feels like the kind of trust Leah's talking about in this question.<br/>
<br/>
I don't know how this is different from trusting somebody's opinion about physics. Maybe just that I care a lot more about it! I would rather ride in an airplane designed by somebody who doesn't really understand physics than entrust any part of my recovery to someone whose moral beliefs and guidance would lead me astray. Because more than my own life is on the line.</p>
				<div style="float: right; width: 350px;"><strong><div style="float: left; padding-top: 0.8em;">The response author is </div><div style="padding-left: 205px;"><input id="authenticRadio" type="radio" name="authenticity" value="authentic"><label for="authenticRadio">Authentic</label><br><input id="impostorRadio" type="radio" name="authenticity" value="impostor"><label for="impostorRadio">An Impostor</label></div></strong></div>
				<div><strong><div style="float: left; padding-top: 0.8em;">This response is </div><div style="padding-left: 150px;"><input id="goodQualityRadio" type="radio" name="quality" value="good"><label for="goodQualityRadio">Good</label><br><input id="badQualityRadio" type="radio" name="quality" value="bad"><label for="badQualityRadio">Bad or Confusing</label></div></strong></div>
				
			<p style="text-align: center;"><button style="font-size: 110%; border-radius: 10px; margin-top: 20px; color: #3c4041;">Check Authenticity and Move On</button>
			
			</div>
			
			<hr style="border: thin solid #CCC; width: 50%"/>
			
			<div><header style="font-size: 130%">(Optional) Or give your own response to the prompt:</header>
			<p style="margin: 5px;">Aim for an honest and genuine response to the prompt at the top of the page.<br/>
			Others who read your response will only see it, and will not see the response in the previous section. Don't mention your name or gender when writing.</p>
			<textarea style="height: 200px; margin-bottom: 30px;" maxlength="2400"></textarea>
			
			<header style="font-size: 130%">Now respond while trying to impersonate a <strong>Christian</strong></header>
			<p style="margin: 5px;">Your post will be judged on both how authentically Christian it seems and on its <strong>quality</strong>.</p>
			<p style="margin: 5px;">You've already identified yourself as a Christian. You can copy and paste your genuine answer from above or you can write something else. You'll get points for seeming to be genuinely Christian, so if your honest answer makes you seem non-Christian you'll want to write something else here.</p>
			<textarea style="height: 200px; margin-bottom: 30px;" maxlength="2400"></textarea>
			
			<p style="text-align: center;"><button style="font-size: 110%; border-radius: 10px; margin-top: 20px; color: #3c4041;">Submit Response</button>
			</div>
			
		</section>

		</article>
<?php

foot();

?>