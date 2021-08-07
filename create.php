<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(isset($_POST['btnCreate'])){
		createAccount();
	}
?>

<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->

	<!--?php wp_head(); ?-->
</head>
	<body>
		<div class="protect-me">
		<div class="clearfix">

<!-- Main -->
<div id="main_area" class="user_area create">
	<div class="wrapper">
		<div class="main_con">
			<main>
				<div class="main_logo">
				  <a href="index.php"><figure><img src="images/main-logo.png" alt="<?php //echo get_bloginfo('name');?>"/></figure></a>
				</div>

				<form method="post">
					<input type="text" name="txtLastname" placeholder="Lastname" required>
					<input type="text" name="txtFirstname" placeholder="Firstname" required>
					<input type="text" name="txtMi" placeholder="MI" maxlength="1" required>

					<select name="cboType" required>
						<option value="">Type of User</option>
						<option value="joiner">Joiner/Tourists</option>
						<option value="organizer">Tour Organizer</option>
					</select>

					<input type="email" name="emEmail" placeholder="sample@gmail.com" required>
					<input type="password" name="pwPassword" placeholder="Password" minlength="8" required>
					<button type="submit" name="btnCreate">Create</button>
					<a href="login.php">&#171; Back to Login</a>
				</form>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
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
  <!--?php wp_footer(); ?-->
</body>
</html>
<!-- End Footer -->
