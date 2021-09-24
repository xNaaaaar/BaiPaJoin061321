<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(!isset($_SESSION['joiner']) || !isset($_SESSION['organizer'])) header("Location: login.php");

	if(isset($_POST['btnSave'])){
		changePassword();
	}
?>

<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->

	<style>
		/* Header Area */
		header{background:url(images/header-bg.png) no-repeat center top/cover, #fff;}
		.main_logo{position:static;margin-left:10px;}

		/* Main Area */
		.main_con{display:flex;justify-content:space-between;}

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
		main .form{display:flex;justify-content:center;flex-wrap:wrap;margin-bottom:40px;position:relative;}
		main .form input, main .form select, main .form textarea{display:inline-block;width:99%;height:60px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 20px/20px Montserrat,sans-serif;padding:0 30px;margin:15px auto;border:1px solid #cfcfcf;}

		main .edit{display:inline-block;width:209px;height:60px;background:#bf127a;border-radius:50px;color:#fff;margin:15px 5px;text-align:center;font:normal 20px/59px Montserrat,sans-serif;border:none;}
		main .edit:hover{background:#8c0047;text-decoration:none;color:#fff;}

		/*RESPONSIVE*/
		@media only screen and (max-width:1000px) {
			main{padding:50px 0 0 25px;}
		}
	</style>

	<!--?php wp_head(); ?-->
</head>
	<body>
		<div class="protect-me">
		<div class="clearfix">

<!-- Header -->
<?php include("includes/header.php"); ?>
<!-- End Header -->

<!-- Navigation -->
<?php
	$currentPage = 'settings';
	include("includes/nav.php");
?>
<!-- End Navigation -->

<!-- Main -->
<div id="main_area">
	<div class="wrapper">
		<div class="breadcrumbs">
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; Password
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'profile';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->

			<main>
				<form method="post" >
					<h2>Change Password</h2>

					<div class="form form1">
						<input type="password" name="pass" placeholder="Current Password" required>
						<input type="password" name="newPass" placeholder="New Password" required>
						<input type="password" name="retypeNewPass" placeholder="Retype New Password" required>
					</div>

					<button class="edit" type="submit" name="btnSave">Save</button>
					<a class="edit" href="settings.php">Back</a>
				</form>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
