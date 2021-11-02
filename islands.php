<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
?>

<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->
	<style>
		.user_area{background:#88dfd8 !important;}
		.user_area main{width:100%;height:100vh;background:none;color:#2f2f2f;border-radius:0;padding:0;position:static;transform:none;}
		.user_area main .main_logo{position:static;transform:none;margin-left:-40px;}
		.user_area main form{width:100%;display:block;text-align:left;}
		.user_area main form h2{font:600 45px/100% Montserrat,sans-serif;color:#fff;}
		.user_area main form h2 span{display:block;color:#fff;font-size:25px;position:static !important;}
		.user_area main form ul{margin:25px 10px 0;position:static;}
		.user_area main form ul li{line-height:40px;display:block;}
	  .user_area main form input{display:inline-block;width:20px;height:20px;margin:0 10px 0 0;border:none;padding:0;border-radius:5px;}
	  .user_area main form label{display:inline-block;font:normal 25px/20px Montserrat,sans-serif;color:#fff;}
	  .user_area main form button{margin:0;border:none;background:none;border-radius:0;position:absolute;bottom:80px;right:0;font-size:30px;color:#fff;letter-spacing:4px;}
	  .user_area main form button:hover{color:#bf127a;}
		/* RESPONSIVE DESIGN */
		@media only screen and (max-width:1400px){
			.user_area main form button{right:20px;}
		}
		@media only screen and (max-width:1000px){
			#main_area{height:100vh;padding:0 25px 40px;}
			.main_con{height:0 !important;}
	    main{height:0 !important;}

			.user_area main .main_logo{margin-left:0 !important;}
			.user_area main form button{bottom:30px;}
		}
		@media only screen and (max-width:500px){
			.user_area main .main_logo{margin:0 auto !important;}
			.user_area main form h2 span{margin:10px 0 0;line-height:100%;}
			.user_area main form ul{margin:25px 0 0;}
			.user_area main form ul li{line-height:35px;}
			.user_area main form label{font-size:23px;}
			.user_area main form button{position:static;display:block;text-align:left;margin:30px 0 0;}
		}
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

				<form method="post" action="activities.php">
					<h2>Places in Cebu you want to visit? <span>Check all that applies:</span> </h2>
					<ul>
						<li><input class="checkboxes" type="checkbox" name="places[]" required value="Bantayan Island"> <label for="">Bantayan Island</label></li>
						<li><input class="checkboxes" type="checkbox" name="places[]" required value="Malapascua Island"> <label for="">Malapascua Island</label></li>
						<li><input class="checkboxes" type="checkbox" name="places[]" required value="Camotes Island"> <label for="">Camotes Island</label></li>
						<li><input class="checkboxes" type="checkbox" name="places[]" required value="Moalboal"> <label for="">Moalboal</label></li>
						<li><input class="checkboxes" type="checkbox" name="places[]" required value="Badian"> <label for="">Badian</label></li>
						<li><input class="checkboxes" type="checkbox" name="places[]" required value="Oslob"> <label for="">Oslob</label></li>
						<li><input class="checkboxes" type="checkbox" name="places[]" required value="Alcoy"> <label for="">Alcoy</label></li>
						<li><input class="checkboxes" type="checkbox" name="places[]" required value="Aloginsan"> <label for="">Aloginsan</label></li>
						<li><input class="checkboxes" type="checkbox" name="places[]" required value="Santander"> <label for="">Santander</label></li>
						<li><input class="checkboxes" type="checkbox" name="places[]" required value="Alegria"> <label for="">Alegria</label></li>
						<li><input class="checkboxes" type="checkbox" name="places[]" required value="Dalaguete"> <label for="">Dalaguete</label></li>
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

	<script type="text/javascript">
		$(document).ready(function(){
			var checkboxes = $('.checkboxes');
			checkboxes.change(function(){
					if($('.checkboxes:checked').length>0) {
							checkboxes.removeAttr('required');
					} else {
							checkboxes.attr('required', 'required');
					}
			});
		});
	</script>
</body>
</html>
<!-- End Footer -->
