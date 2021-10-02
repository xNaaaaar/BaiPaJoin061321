<div id="nav_area">
	<!-- Loader -->
	<?php include("includes/loader.php"); ?>
	
	<div class="nav_toggle_button">
	<div class="logo_wrap"></div>
		<div class="toggle_holder">
			<div class="hamburger hamburger--spin-r">
			  <div class="hamburger-box">
				<div class="hamburger-inner"></div>
			  </div>
			</div>
			<small>Menu</small>
		</div>
	<div class="clearfix"></div>
	</div>

	<div class="toggle_right_nav">
	  <nav class="page_nav">
			<div class="menu_slide_right">
				<a href="<?php //echo get_home_url(); ?>" class="logo_slide_right"><figure><img src="images/main-logo.png" alt="<?php //echo get_bloginfo('name');?>"/></figure></a>
				<div class="toggle_holder">
					<div class="hamburger hamburger--spin-r">
					  <div class="hamburger-box">
						<div class="hamburger-inner"></div>
					  </div>
					</div>
					<small>Close</small>
				</div>
				<div class="clearfix"></div>
			</div>

			<div class="wrapper">
				<ul>
					<li class="<?php if($currentPage == 'index') echo 'current_page_item'; ?>"><a href="index.php">Home</a></li>
					<li class="<?php if($currentPage == 'aboutus') echo 'current_page_item'; ?>"><a href="#main_area">About Us</a></li>
					<!-- SHOW ALL ADVENTURES POSTED IF CURRENT USER IS JOINER -->
					<?php
						if(!isset($_SESSION['organizer'])){
					?>
						<li class="<?php if($currentPage == 'adventures') echo 'current_page_item'; ?>"><a href="adventures.php">Adventures</a></li>
					<?php
						}
					?>
					<li class="<?php if($currentPage == 'contactus') echo 'current_page_item'; ?>"><a href="#contact-us">Contact Us</a></li>
					<!-- IF USER LOGIN OR NOT -->
					<?php
						$_SESSION['current_user'] = "";

						if(isset($_SESSION['joiner'])){
							$_SESSION['current_user'] = 'Joiner';

							echo "<li class='";
								if($currentPage == 'settings') echo 'current_page_item';
							echo "'><a href='settings.php' style='color:#bf127a;'>Settings</a></li>";
						} else if(isset($_SESSION['organizer'])) {
							$_SESSION['current_user'] = 'Organizer';

							echo "<li class='";
								if($currentPage == 'settings') echo 'current_page_item';
							echo "'><a href='settings.php' style='color:#bf127a;'>Settings</a></li>";
						} else {
					?>
						<li><a href="login.php" style="color:#bf127a;">Login</a></li>
						<li><a href="create.php">Create</a></li>

					<?php } ?>
				</ul>
			</div>
	  </nav>
		<div class="toggle_nav_close"></div>
	</div>
</div>
