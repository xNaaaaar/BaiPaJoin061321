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
		if(!empty(trim(ucwords($_POST['txtFirstname']))) && !empty(trim(ucwords($_POST['txtLastname']))) && !empty(trim(ucwords($_POST['txtMi']))) && !empty(trim(ucwords($_POST['txtAddress']))) && !empty(trim(ucwords($_POST['txtCityMuni']))) && !empty(trim($_POST['txtPhone'])) && !empty(trim($_POST['emEmail'])))
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
		main .form{display:flex;justify-content:center;flex-wrap:wrap;}
		main .form input:nth-of-type(2){width:41%;}
		main .form input:nth-of-type(3){width:41%;}
		main .form input:nth-of-type(4){width:16%;}
		main .form input:nth-of-type(6){width:49%;}
		main .form input:nth-of-type(7){width:49%;}

		/*RESPONSIVE*/
		@media only screen and (max-width:1000px) {
			main{padding:50px 0 0 25px;}
		}
		@media only screen and (max-width:500px) {
			main .edit{width:48%;}
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

				<?php if(isset($_SESSION['organizer']) && $_SESSION['verified'] == 1){ ?>
					<div class="form form1">
						<input type="text" name="txtCompName" value="<?php echo "{$_SESSION['company']}"; ?>" placeholder="Company Name" readonly required>
						<input type="text" name="txtFirstname" value="<?php echo "{$_SESSION['fname']}"; ?>" placeholder="Firstname" readonly required>
						<input type="text" name="txtLastname" value="<?php echo "{$_SESSION['lname']}"; ?>" placeholder="Lastname" readonly required>
						<input type="text" name="txtMi" value="<?php echo "{$_SESSION['mi']}"; ?>" placeholder="Mi" maxlength="1" readonly required>
						<input type="text" name="txtAddress" value="<?php echo "{$_SESSION['address']}"; ?>" placeholder="Address" required>
						<input type="text" name="txtPhone" value="<?php echo "{$_SESSION['phone']}"; ?>" placeholder="0999XXXXXXX" maxlength="11" required>
						<input type="email" name="emEmail" value="<?php echo "{$_SESSION['email']}"; ?>" placeholder="Email Address" required>
					</div>
					<button class="edit" type="submit" name="btnSaveOrganizer">Save</button>

				<?php } elseif(isset($_SESSION['organizer']) && $_SESSION['verified'] == 0) { ?>
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
						<input type="text" name="txtFirstname" value="<?php echo "{$_SESSION['fname']}"; ?>" placeholder="Firstname" readonly required>
						<input type="text" name="txtLastname" value="<?php echo "{$_SESSION['lname']}"; ?>" placeholder="Lastname" readonly required>
						<input type="text" name="txtMi" value="<?php echo "{$_SESSION['mi']}"; ?>" placeholder="Mi" maxlength="1" required>
						<input type="text" name="txtAddress" value="<?php echo "{$_SESSION['address']}"; ?>" placeholder="Barangay" required>
						<input type="text" name="txtCityMuni" value="<?php echo "{$_SESSION['citymuni']}"; ?>" placeholder="City / Municipality" required>
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
