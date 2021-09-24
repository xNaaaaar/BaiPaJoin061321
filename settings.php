<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	// REDIRECT IF NOT LOGGED IN
    if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");


	// IF PROFILE IS UPDATED SUCCESSFULLY
	if(isset($_GET['updated']) && $_GET['updated'] == 1){
		echo "<script>alert('Profile successfully updated!')</script>";
	}
	// IF DOCUMENT IS DELETED SUCCESSFULLY
	if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
		echo "<script>alert('Document successfully deleted!')</script>";
	}
	// IF DOCUMENT IS EDITED SUCCESSFULLY
	if(isset($_GET['edited']) && $_GET['edited'] == 1){
		echo "<script>alert('Document successfully edited!')</script>";
	}
	// IF DOCUMENT IS ADDED SUCCESSFULLY
	if(isset($_GET['added']) && $_GET['added'] == 1){
		echo "<script>alert('Document successfully added!')</script>";
	}
	// IF PASSWORD IS CHANGED SUCCESSFULLY
	if(isset($_GET['changepass']) && $_GET['changepass'] == 1){
		echo "<script>alert('Password successfully changed!')</script>";
	}

	//
	if(isset($_GET['sent'])){
		DB::query("UPDATE organizer SET orga_status=? WHERE orga_id=?", array(2, $_SESSION['organizer']), "UPDATE");
		$pending = DB::query("SELECT * FROM organizer WHERE orga_id=?", array($_SESSION['organizer']), "READ");
		$pending = $pending[0];
		$_SESSION['verified'] = $pending['orga_status'];
		echo "<script>alert('Successfully sent. Please wait for admin\'s verification!')</script>";
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
		main h2, main h3{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
		main h2 span{font-size:25px;}
		main h2 .legal{font-size:30px;}
		main h2 span a:hover{color:#313131;text-decoration:none;}
		main h3{font-size:20px;color:red;margin-left:1px;}
		main .form{display:flex;justify-content:center;flex-wrap:wrap;margin-bottom:40px;position:relative;}
		main .form input{display:inline-block;width:99%;height:60px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 20px/20px Montserrat,sans-serif;padding:0 30px;margin:15px auto;border:1px solid #cfcfcf;}
		main .form input:nth-child(2){width:41%;}
		main .form input:nth-child(3){width:41%;}
		main .form input:nth-child(4){width:16%;}
		main .form input:nth-child(6){width:49%;}
		main .form input:nth-child(7){width:49%;}

		.card{width:100%;min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:20px;padding:30px;line-height:35px;text-align:left;margin:15px 0;border:1px solid #cfcfcf;flex-direction:row;}
		.card figure{width:165px;height:165px;border:1px solid #cfcfcf;margin-right:30px !important;display:inline-block;vertical-align:top;}
		.card figure img{width:100%;height:100%;}
		.card div{display:inline-block;vertical-align:top;}
		.card h2{font:600 35px/100% Montserrat,sans-serif;color:#313131;margin-bottom:15px;}
		.card h2 span{font:500 italic 18px/100% Montserrat,sans-serif;color:#989898;display:block;margin:5px 0 0;}
		.card p{font-size:23px;color:#989898;width:100% !important;font-weight:bold;}
		.card ul{position:absolute;top:20px;right:20px;font-size:30px;}
		.card ul li{display:inline-block;margin:0;}
		.card ul li a{color:#313131;}
		.card ul li a:hover{color:#bf127a;}

		main .edit{display:inline-block;width:209px;height:60px;background:#bf127a;border-radius:50px;color:#fff;margin:15px 5px;text-align:center;font:normal 20px/59px Montserrat,sans-serif;}
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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; Profile
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'profile';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->

			<main>
				<form method="post">
					<h2>Profile <span><a href="edit_profile.php"><i class='fas fa-edit'></i></a></span></h2>
					<!-- FOR ORGANIZER -->
					<?php if(isset($_SESSION['organizer'])){ ?>
					<h3>Note: Please complete profile details and add 2 legal documents below, to verify your account.</h3>
					<div class="form form1">
						<input type="text" value="<?php echo "{$_SESSION['company']}"; ?>" placeholder="Company Name" disabled>
						<input type="text" value="<?php echo "{$_SESSION['fname']}"; ?>" placeholder="Firstname" disabled>
						<input type="text" value="<?php echo "{$_SESSION['lname']}"; ?>" placeholder="Lastname" disabled>
						<input type="text" value="<?php echo "{$_SESSION['mi']}"; ?>" placeholder="Mi" disabled>
						<input type="text" value="<?php echo "{$_SESSION['address']}"; ?>" placeholder="Address" disabled>
						<input type="text" value="<?php echo "{$_SESSION['phone']}"; ?>" placeholder="0999XXXXXXX" disabled>
						<input type="email" value="<?php echo "{$_SESSION['email']}"; ?>" placeholder="Email Address" disabled>
					</div>

					<h2>Password <span><a href="edit_password.php"><i class='fas fa-edit'></i></a></span></h2>
					<div class="form form2">
						<input type="password" name="" value="" placeholder="**********" disabled>
					</div>

					<h2>Legal Documents <span class="legal" >
						<a href="add_docu.php"><i class='fas fa-plus-circle'></i></a>
						<?php
						$docu = DB::query("SELECT * FROM legal_document WHERE orga_id=?", array($_SESSION['organizer']), "READ");
						## CHECK IF ORGANIZER STATUS IS NOT VERIFIED AND ADDED LEGAL IS GREATER THAN OR EQUAL TO TWO
						if($_SESSION['verified'] == 0 && count($docu) >= 2){
						?>
						<a href="settings.php?sent" onclick="return confirm('Send legal document to admin for verification?');"><i class="fas fa-paper-plane"></i></a>
						<?php
						} ##
						?>
					</span></h2>

					<!-- DISPLAY ALL LEGAL DOCUMENT ADDED -->
					<?php displayAll(0);

					} else {
					?>
					<!-- FOR JOINER -->
					<div class="form form1">
						<input type="text" value="" style="display:none;" disabled>
						<input type="text" value="<?php echo "{$_SESSION['fname']}"; ?>" placeholder="Firstname" disabled>
						<input type="text" value="<?php echo "{$_SESSION['lname']}"; ?>" placeholder="Lastname" disabled>
						<input type="text" value="<?php echo "{$_SESSION['mi']}"; ?>" placeholder="Mi" disabled>
						<input type="text" value="<?php echo "{$_SESSION['address']}"; ?>" placeholder="Address" disabled>
						<input type="text" value="<?php echo "{$_SESSION['phone']}"; ?>" placeholder="0999XXXXXXX" disabled>
						<input type="email" value="<?php echo "{$_SESSION['email']}"; ?>" placeholder="Email Address" disabled>
					</div>

					<h2>Password <span><a href="edit_password.php"><i class='fas fa-edit'></i></a></span></h2>
					<div class="form form2">
						<input type="password" name="" value="" placeholder="**********" disabled>
					</div>

					<?php } ?>

				</form>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php");?>
<!-- End Footer -->
