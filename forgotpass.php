<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
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
<div id="main_area" class="user_area forgotpass">
	<div class="wrapper">
		<div class="main_con">
			<main>
				<div class="main_logo">
				  <a href="index.php"><figure><img src="images/main-logo.png" alt="<?php //echo get_bloginfo('name');?>"/></figure></a>
				</div>

				<form method="post">
					<input type="text" name="txtEmailAdd" placeholder="sample@gmail.com">
					<button type="submit" name="btnReset">Reset</button>
					<a href="login.php">&#171; Back to Login</a>
				</form>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<?php

	if(isset($_POST['btnReset'])) {

		$email_add = trim($_POST['txtEmailAdd']);

		$joiner_db = DB::query("SELECT * FROM joiner WHERE joiner_email=?", array($email_add), "READ");
		$organizer_db = DB::query("SELECT * FROM organizer WHERE orga_email=?", array($email_add), "READ");

		$joiner = $joiner_db[0];
		$organizer = $organizer_db[0];

		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charactersLength = strlen($characters);
    	$randomString = '';
   		for ($i = 0; $i < 8; $i++)
        	$randomString = $randomString . $characters[rand(0, $charactersLength - 1)];
        $hash_pass = md5($randomString);

		if(empty($joiner) && empty($organizer)) 
			echo "<script>alert('EMAIL NOT FOUND! Please make sure email address is CORRECT!')</script>";
		else if(!empty($joiner)) {
			$email_subject = 'PASSWORD RESET';
    		$email_message = 'Dear '.$joiner['joiner_fname'].', We have received a request to reset the password of your BaiPaJoin Account. Here\'s a temporary password: '.$randomString.' , you can use to access your account. You\'re advised to change the immediately. Thank you! THIS IS A TEST. DO NOT REPLY!';

			send_email($email_add, $email_subject, $email_message);
			DB::query("UPDATE joiner SET joiner_password=? WHERE joiner_id=?", array($hash_pass, $joiner['joiner_id']), "UPDATE");

			echo "<script>alert('Password reset successful! We've sent you an email. Please check your inbox')</script>";
		}
		else if(!empty($organizers) > 0) {
			$email_subject = 'PASSWORD RESET';
    		$email_message = 'Dear '.$organizer['orga_fname'].', We have received a request to reset the password of your BaiPaJoin Account. Here\'s a temporary password: '.$randomString.' , you can use to access your account. You\'re advised to change the immediately. Thank you! THIS IS A TEST. DO NOT REPLY!';

			send_email($email_add, $email_subject, $email_message);			
			DB::query("UPDATE organizer SET orga_password=? WHERE orga_id=?", array($hash_pass, $organizer['orga_id']), "UPDATE");

			echo "<script>alert('Password reset successful! We've sent you an email. Please check your inbox')</script>";
		}
	}
?>

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
