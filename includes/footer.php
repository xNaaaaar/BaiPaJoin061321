<footer id="contact-us">
<div class="footer_top">
	<div class="wrapper">
		<div class="footer_top_con">
			<div class="footer_map">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3925.5728295607373!2d123.89524095089475!3d10.295960970655877!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a99bfd54c152e5%3A0xf9b2f3f4bd996da0!2sUniversity%20of%20Cebu%20-%20Main%20Campus!5e0!3m2!1sen!2sph!4v1623559304291!5m2!1sen!2sph" style="border:0;width:100%;height:100%;" allowfullscreen="" loading="lazy"></iframe>
			</div>

			<div class="contact_info1">
				<p>You can visit our office here!</p>
			</div>

			<div class="contact_info2">
				<h2>Get in Touch</h2>
				<ul>
					<li>Our Location: <address class="">
						Sanciangko Street <q>Cebu City, 6000 Cebu</q>
					</address></li>
					<li>Phone Number: <mark>032-328-8260</mark></li>
					<li>Email: <a href="mailto:team.baipajoin@gmail.com">team.baipajoin@gmail.com</a></li>
				</ul>
			</div>

			<div class="social_media">
				<h2>Follow Us:</h2>
				<ul>
					<li><a href="https://www.facebook.com" target="_blank"><figure><img src="images/fb-icon.png" alt="facebook"/></figure></a></li>
					<li><a href="https://www.twitter.com" target="_blank"><figure><img src="images/twitter-icon.png" alt="twitter"/></figure></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="footer_btm">
  <div class="wrapper">
			<div class="footer_btm_con">
				<div class="footer_nav">
					<h2>Site Navigation</h2>
					<ul>
						<li class="<?php if($currentPage == 'index') echo 'current_page_item'; ?>"><a href="index.php">Home</a></li>
						<li class="<?php if($currentPage == 'aboutus') echo 'current_page_item'; ?>"><a href="aboutus.php">About Us</a></li>
						<?php
							if(!isset($_SESSION['organizer'])){
						?>
							<li class="<?php if($currentPage == 'adventures') echo 'current_page_item'; ?>"><a href="adventures.php">Adventures</a></li>
						<?php
							}
						?>
						<li class="<?php if($currentPage == 'contactus') echo 'current_page_item'; ?>"><a href="contactus.php">Contact Us</a></li>
						<!-- IF USER LOGIN OR NOT -->
						<?php
							$_SESSION['current_user'] = "";

							if(isset($_SESSION['joiner'])){
								$_SESSION['current_user'] = 'Joiner';

								echo "<li class='";
									if($currentPage == 'settings') echo 'current_page_item';
								echo "'><a href='settings.php'>Settings</a></li>";
							} else if(isset($_SESSION['organizer'])) {
								$_SESSION['current_user'] = 'Organizer';

								echo "<li class='";
									if($currentPage == 'settings') echo 'current_page_item';
								echo "'><a href='settings.php'>Settings</a></li>";
							} else {
						?>
							<li><a href="login.php">Login</a></li>
							<li><a href="create.php" style="color:#fff;">Create</a></li>

						<?php } ?>
					</ul>
				</div>

				<div class="copyright">
				  	&copy; Copyright
						<?php
						$start_year = '2021';
						$current_year = date('Y');
						$copyright = ($current_year == $start_year) ? $start_year : $start_year.' - '.$current_year;
						echo $copyright;
						?>
				  	<q>Designed by <a href="https://www.proweaver.com/" target="_blank" rel="nofollow">Team BaiPaJoin</a></q>
				</div>
			</div>
		</div>
</div>
</footer>

	<span class="back_top"></span>

  </div> <!-- End Clearfix -->
  </div> <!-- End Protect Me -->

  <!--?php get_includes('ie');?-->


  <!--
  Solved HTML5 & CSS IE Issues
  -->
  <script src="js/modernizr-custom-v2.7.1.min.js"></script>
  <script src="js/jquery-2.1.1.min.js"></script>
  <script src="js/wow.min.js"></script>

  <!--
  Solved Psuedo Elements IE Issues
  -->
  <script src="js/calcheight.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.skitter.min.js"></script>
  <script src="js/responsiveslides.min.js"></script>
  <script src="js/plugins.js"></script>
	<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
  <!--?php wp_footer(); ?-->

	<script type="text/javascript" defer>
		"use strict"
		// LOADING SCREEN
		$(window).on("load",()=>{
			$(".loader-wrapper").fadeOut();
		});
		// DISPLAY INPUT FIELD FOR MAX GUESTS IN ADDING ADVENTURE
		function displayMaxGuests(that) {
			if (that.value == "packaged")
					document.getElementById("display").style.opacity = 1;
			else
					document.getElementById("display").style.opacity = 0;
		}
		// TOTAL PRICE FOR NUMBER OF GUESTS
		function displayTotalPrice(guest=1, slots = null){
			let price = 0;
			let text = guest.concat("/");
			<?php if(isset($_SESSION['price'])) { ?>
				//document.getElementById("totalPrice").value = (guest * <?php echo $_SESSION['price']; ?>).toFixed(2);
				price = numberWithCommas((guest * <?php echo $_SESSION['price']; ?>).toFixed(2));
				document.getElementById("totalPrice").value = price;
				text = text.concat(slots);
				document.getElementById("label_slot").innerHTML = text;
				console.log(text);

			<?php } ?>
		}
		// COPYING OF VOUCHER CODE
		function copy_voucher_code(code){
		  /* Copy the text inside the text field */
		  navigator.clipboard.writeText(code);

		  /* Alert the copied text */
		  alert("Voucher code " + code + " successfully copied!");
		}
		// NUMBER WITH COMMA
		function numberWithCommas(x) {
		  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		}
		<?php
		// COUNTDOWN TIMER FOR REVERT
		if(isset($_SESSION['date_process'])){ ?>
			function revert_timer(counter){
				// Update the count down every 1 second
				var x = setInterval(function() {
					<?php // echo date("M j, Y", strtotime($_SESSION['date_process'])); ?>
					// Set the date we're counting down to
					var this_date = document.getElementById("countdowndate"+counter).innerHTML;
					var countDownDate = new Date(this_date).getTime();

					// Get today's date and time
					var now = new Date().getTime();

					// Find the distance between now and the count down date
				  var distance = countDownDate - now;

					// Time calculations for days, hours, minutes and seconds
				  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
				  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

					// Display the result in the element with id="demo"
				  document.getElementById("timer"+counter).innerHTML = hours + "H "
				  + minutes + "M " + seconds + "S ";

					// If the count down is finished, write some text
				  if (distance < 0) {
				    clearInterval(x);
				    document.getElementById("timer"+counter).innerHTML = "<span style='color:red;'>EXPIRED</span>";
				  }
				}, 1000);
			}
		<?php
		} ?>

		<?php
		// COUNTDOWN TIMER FOR WAITING FOR PAYMENT
		if(isset($_SESSION['waiting_expry'])){ ?>
			function payment_timer(counter){
				// Update the count down every 1 second
				var new_x = setInterval(function() {
					// Set the date we're counting down to
					var new_this_date = document.getElementById("waiting_expry"+counter).innerHTML;
					var new_countDownDate = new Date(new_this_date).getTime();
					console.log(new_this_date);

					// Get today's date and time
					var new_now = new Date().getTime();

					// Find the distance between now and the count down date
				  var new_distance = new_countDownDate - new_now;

					// Time calculations for days, hours, minutes and seconds
				  var new_days = Math.floor(new_distance / (1000 * 60 * 60 * 24));
				  var new_hours = Math.floor((new_distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				  var new_minutes = Math.floor((new_distance % (1000 * 60 * 60)) / (1000 * 60));
				  var new_seconds = Math.floor((new_distance % (1000 * 60)) / 1000);

					// Display the result in the element with id="demo"
				  document.getElementById("exp_timer"+counter).innerHTML = new_minutes + "M " + new_seconds + "S ";

					// If the count down is finished, write some text
				  if (new_distance < 0) {
				    clearInterval(new_x);
				    document.getElementById("exp_timer"+counter).innerHTML = "<span style='color:red;'>EXPIRED</span>";
				  }
				}, 1000);
			}
		<?php
		} ?>
	</script>
</body>
</html>
