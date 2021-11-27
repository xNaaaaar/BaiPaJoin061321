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
		main h2{margin-bottom:30px;}
		main input{padding:15px;}

		.edit{margin:15px 5px !important;}

		/*RESPONSIVE*/
		@media only screen and (max-width:1000px) {
			main{padding:50px 0 0 25px;}
		}
		@media only screen and (max-width:500px) {
			.edit{width:100% !important;margin:8px auto !important;}
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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; <a href="reports_booking.php">Reports </a> &#187; Booking
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'reports';
				$currentSubMenu = 'reports';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->
			<?php

			## REQUEST CANCEL FOR JOINER
			if(isset($_POST['btnReport'])){
				$txtReason = ucfirst(trim($_POST['txtReason']));
				## ORGANIZER ID
				$orga_id = DB::query("SELECT orga_id FROM adventure WHERE adv_id=?", array($_GET['adv_id']), "READ");

				$_SESSION['reported'] = true;
				## EMAIL JOINER NOTIFICATION
				// $joiner_db = DB::query("SELECT * FROM joiner WHERE joiner_id = ?", array($_SESSION['joiner']), "READ");
				// $joiner_info = $joiner_db[0];
				//
				// $email_message = html_request_message($joiner_info['joiner_fname'], 1, 'joiner');
				//
				// $img_address = array();
				// $img_name = array();
				//
				// array_push($img_address,'images/request-bg.png','images/main-logo-green.png','images/request-img.png');
				// array_push($img_name,'background','logo','main');
				//
				// send_email($joiner_info['joiner_email'], "REQUEST ACKNOWLEDGED", $email_message, $img_address, $img_name);
				//
				// header("Location: request.php?cancel_success");
			}
			?>

			<main>
				<form method="post">
					<?php if(isset($_SESSION['reported'])) { ?>
						<h2>Thank you for your report. Report sent to admin!</h2>
					<?php } else { ?>
						<h2>Reason for Reporting</h2>
						<input list="valid_reason" name="txtReason" placeholder="Input reason if not stated below.." required/>
						<datalist id="valid_reason">
							<option value="Valid Reason 1">Valid Reason 1</option>
							<option value="Valid Reason 2">Valid Reason 2</option>
							<option value="Valid Reason 3">Valid Reason 3</option>
							<option value="Valid Reason 4">Valid Reason 4</option>
							<option value="Valid Reason 5">Valid Reason 5</option>
							<option value="Valid Reason 6">Valid Reason 6</option>
						</datalist>
						<button class='edit' type='submit' name='btnReport'>Report</button>
					<?php } ?>

					<a class='edit' href='reports_booking.php'>Back</a>
				</form>
			</main>


		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ob_end_flush();?>
<!-- End Footer -->
