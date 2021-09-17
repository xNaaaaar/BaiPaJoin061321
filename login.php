<?php
	
	include("google_login/config.php");
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(isset($_POST['btnLogin'])){
		loginAccount();
	}

	$login_url = '<a href = "'.$google_client -> createAuthUrl().'"><img src = "google_login/images/1x/btn_google_signin_dark_normal_web.png"/></a>';

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
<div id="main_area" class="user_area login">
	<div class="wrapper">
		<div class="main_con">
			<main>
				<div class="main_logo">
				  <a href="index.php"><figure><img src="images/main-logo.png" alt="<?php //echo get_bloginfo('name');?>"/></figure></a>
				</div>

				<form method="post">
					<input type="email" name="emEmail" placeholder="sample@gmail.com" required>
					<input type="password" name="pwPassword" placeholder="Password" required>
					<a href="forgotpass.php">Forgot Password &#187;</a>
					<button type="submit" name="btnLogin">Login</button>
					<a href="create.php">Create</a>
				</form>
				<?php echo $login_url; ?>
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
