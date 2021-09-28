<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
	ob_start();
	##
	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");
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
		.sidebar ul ul{height:auto;}

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:30px;text-align:left;}
		main textarea{display:inline-block;width:99%;height:150px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:10px;font:normal 18px/20px Montserrat,sans-serif;padding:20px;margin:0 auto 15px;border:1px solid #cfcfcf;resize:none;}

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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; Booking
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'reports';
				$currentSubMenu = 'request';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->
			<main>
				<?php
				if(isset($_GET['req_id'])){
					$request = DB::query("SELECT * FROM request WHERE req_id=?", array($_GET['req_id']), "READ");
					$request = $request[0];
				?>
				<form method="post">
					<h2>Edit Reason</h2>

					<textarea name="txtReason" placeholder="Input valid reason" maxlength="100" required><?php echo $request['req_reason']; ?></textarea>
					<button class="edit" type="submit" name="btnSave">Save</button>
					<a class="edit" href="request.php">Back</a>
				</form>
				<?php
				}

				if(isset($_POST['btnSave'])){
					$txtReason = ucfirst(trim($_POST['txtReason']));

					DB::query("UPDATE request SET req_reason=? WHERE req_id=?", array($txtReason, $_GET['req_id']), "UPDATE");

					header("Location: request.php?update_success");
				}
				?>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ob_end_flush();?>
<!-- End Footer -->
