<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(isset($_POST['btnLogin'])){

	}

?>

<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->
	<style>
		.main_logo{}

		.user_area{background:#88dfd8 !important;}
		.user_area main{width:100%;height:100vh;background:none;color:#2f2f2f;border-radius:0;padding:0;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);}
		.user_area main .main_logo{left:-4px;transform:none;}
		.user_area main form{width:95%;display:block;position:absolute;top:55%;left:50%;transform:translate(-50%,-50%);height:60vh;text-align:left;}
		.user_area main form h2{font:600 59px/100% Montserrat,sans-serif;color:#fff;}
		.user_area main form h2 span{display:block;color:#fff;font-size:25px;}
		.user_area main form ul{margin:25px 0 0;}
		.user_area main form ul li{line-height:50px;}
	  .user_area main form input{display:inline-block;width:25px;height:25px;margin:0;border:none;padding:0;border-radius:5px;}
	  .user_area main form label{display:inline-block;font:normal 30px/20px Montserrat,sans-serif;color:#fff;}
	  .user_area main form button{width:170px;height:50px;margin:0;background:none;border-radius:0;position:absolute;bottom:0;right:0;font-size:30px;}
	  .user_area main form button:hover{background:none;text-decoration:underline;}
	</style>
</head>
	<body>
		<div class="protect-me">
		<div class="clearfix">


<!-- Main -->
<div id="main_area" class="user_area">
	<div class="wrapper">
		<div class="main_con">
			<main>
				<div class="main_logo">
				  <a href="index.php"><figure><img src="images/main-logo.png" alt="<?php //echo get_bloginfo('name');?>"/></figure></a>
				</div>

				<form method="post">
					<h2>Places in Cebu you want to visit? <span>Check all that applies:</span> </h2>
					<ul>
						<li><input type="checkbox" name="places[]"> <label for="">Bantayan Island</label></li>
						<li><input type="checkbox" name="places[]"> <label for="">Malapascua Island</label></li>
						<li><input type="checkbox" name="places[]"> <label for="">Camotes Island</label></li>
						<li><input type="checkbox" name="places[]"> <label for="">Moalboal</label></li>
						<li><input type="checkbox" name="places[]"> <label for="">Badian</label></li>
						<li><input type="checkbox" name="places[]"> <label for="">Oslob</label></li>
					</ul>
					<button type="submit" name="btnNext">Next &#187;</button>
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
