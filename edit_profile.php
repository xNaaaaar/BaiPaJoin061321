<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

	if(isset($_GET['error']))
		echo "<script>alert('ERROR! Please ensure that all fields are not blank or filled with spaces ONLY.')</script>";

	if(isset($_POST['btnSaveOrganizer'])){
		if(!empty(trim(ucwords($_POST['txtFirstname']))) && !empty(trim(ucwords($_POST['txtLastname']))) && !empty(trim(ucwords($_POST['txtMi']))) && !empty(trim(ucwords($_POST['txtAddress']))) && !empty(trim($_POST['txtPhone'])) && !empty(trim($_POST['emEmail'])) && !empty(trim(ucwords($_POST['txtCompName']))))
			organizerSaveProfileChanges();
		else
			header("Location: edit_profile.php?error");
	}
	else if(isset($_POST['btnSaveJoiner'])){
		if(!empty(trim(ucwords($_POST['txtFirstname']))) && !empty(trim(ucwords($_POST['txtLastname']))) && !empty(trim(ucwords($_POST['txtMi']))) && !empty(trim(ucwords($_POST['txtAddress']))) && !empty(trim($_POST['txtPhone'])) && !empty(trim($_POST['emEmail'])))
			joinerSaveProfileChanges();
		else
			header("Location: edit_profile.php?error");
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
		main .form input:nth-of-type(2){width:41%;}
		main .form input:nth-of-type(3){width:41%;}
		main .form input:nth-of-type(4){width:16%;}
		main .form input:nth-of-type(6){width:49%;}
		main .form input:nth-of-type(7){width:49%;}

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
				<form method="post" >
					<h2>Update Profile</h2>

					<?php if(isset($_SESSION['organizer'])){ ?>
					<div class="form form1">
						<input type="text" name="txtCompName" value="<?php echo "{$_SESSION['company']}"; ?>" placeholder="Company Name" required>
						<input type="text" name="txtFirstname" value="<?php echo "{$_SESSION['fname']}"; ?>" placeholder="Firstname" required>
						<input type="text" name="txtLastname" value="<?php echo "{$_SESSION['lname']}"; ?>" placeholder="Lastname" required>
						<input type="text" name="txtMi" value="<?php echo "{$_SESSION['mi']}"; ?>" placeholder="Mi" maxlength="1" required>
						<input type="text" name="txtAddress" value="<?php echo "{$_SESSION['address']}"; ?>" placeholder="Address" required>
						<input type="text" name="txtPhone" value="<?php echo "{$_SESSION['phone']}"; ?>" placeholder="0999XXXXXXX" maxlength="11" required>
						<input type="email" name="emEmail" value="<?php echo "{$_SESSION['email']}"; ?>" placeholder="Email Address" required>
					</div>
					<button class="edit" type="submit" name="btnSaveOrganizer">Save</button>

				<?php } else { ?>
					<div class="form form1">
						<input type="text" style="display:none;">
						<input type="text" name="txtFirstname" value="<?php echo "{$_SESSION['fname']}"; ?>" placeholder="Firstname" required>
						<input type="text" name="txtLastname" value="<?php echo "{$_SESSION['lname']}"; ?>" placeholder="Lastname" required>
						<input type="text" name="txtMi" value="<?php echo "{$_SESSION['mi']}"; ?>" placeholder="Mi" maxlength="1" required>
						<input type="text" name="txtAddress" value="<?php echo "{$_SESSION['address']}"; ?>" placeholder="Address" required>
						<input type="text" name="txtPhone" value="<?php echo "{$_SESSION['phone']}"; ?>" placeholder="0999XXXXXXX" maxlength="11" required>
						<input type="email" name="emEmail" value="<?php echo "{$_SESSION['email']}"; ?>" placeholder="Email Address" required>
					</div>
					<button class="edit" type="submit" name="btnSaveJoiner">Save</button>
					<?php } ?>

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
