<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
?>

<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->

<style >
	.slider{width:100%;height:auto;top:0;right:0;left:0;z-index:3;}
	.slider:after{display:none;}
	.rslides{border-radius:0;box-shadow:none;}
</style>
</head>
	<body>
		<div class="protect-me">
		<div class="clearfix">

<!-- Loader -->
<?php include("includes/loader.php"); ?>

<!-- Main -->
<div id="main_area" class="user_area forgotpass">
	<!-- Images Slider -->
	<?php include("includes/slider.php"); ?>
	<div class="wrapper">
		<div class="main_con">
			<main>
				<div class="main_logo">
				  <a href="index.php"><figure><img src="images/main-logo.png" alt="<?php //echo get_bloginfo('name');?>"/></figure></a>
				</div>

				<form method="post">
					<label>Temporary password will be send to email: </label>
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
			$img_address = array();
			$img_name = array();
			array_push($img_address,'images/reset-pass-bg.png','images/main-logo-green.png','images/reset-pass-img.png');
			array_push($img_name,'background','logo','main');

			$email_subject = 'PASSWORD RESET';
    		$email_message = html_resetpassword_message($joiner['joiner_fname'], $randomString);

			send_email($email_add, $email_subject, $email_message, $img_address, $img_name);

			DB::query("UPDATE joiner SET joiner_password=? WHERE joiner_id=?", array($hash_pass, $joiner['joiner_id']), "UPDATE");

			header("Location: login.php?success");
		}
		else if(!empty($organizer)) {
			$img_address = array();
			$img_name = array();
			array_push($img_address,'images/reset-pass-bg.png','images/main-logo-green.png','images/reset-pass-img.png');
			array_push($img_name,'background','logo','main');

			$email_subject = 'PASSWORD RESET';
			$email_message = html_resetpassword_message($organizer['orga_fname'], $randomString);

			send_email($email_add, $email_subject, $email_message);

			DB::query("UPDATE organizer SET orga_password=? WHERE orga_id=?", array($hash_pass, $organizer['orga_id']), "UPDATE");

			header("Location: login.php?success");
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
	<script>
		$(window).on("load",()=>{
			$(".loader-wrapper").fadeOut();
		});
	</script>
</body>
</html>
<!-- End Footer -->
