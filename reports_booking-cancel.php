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
			<main>
				<form method="post">
					<h2>Reason for Cancelling</h2>

					<textarea name="txtReason" placeholder="Type here.." maxlength="100" required></textarea>
					<button class="edit" type="submit" name="btnRequest">Request</button>
					<a class="edit" href="reports_booking.php">Back</a>
				</form>
			</main>

			<?php
			if(isset($_POST['btnRequest'])){
				$txtReason = ucfirst(trim($_POST['txtReason']));
				##
				$booked_db = DB::query("SELECT * FROM booking WHERE book_id=?", array($_GET['book_id']), "READ");
				$booked = $booked_db[0];
				##
				DB::query("INSERT INTO request (req_user, req_type, req_dateprocess, req_amount, req_status, req_reason, req_rcvd, book_id) VALUES(?,?,?,?,?,?,?,?)", array('joiner', 'cancel', date("Y-m-d"), $booked['book_totalcosts'], 'pending', $txtReason, 0, $_GET['book_id']), "CREATE");

				## EMAIL + SMS NOTIFICATION
				$joiner_db = DB::query("SELECT * FROM joiner WHERE joiner_id = ?", array($_SESSION['joiner']), "READ");
				$joiner_info = $joiner_db[0];

				$sms_sendto = $joiner_info['joiner_phone'];
				$sms_message = "Hi ".$joiner_info['joiner_fname']."! Your request for cancellation has been submitted on ".date('d-M-Y').".";

				send_sms($sms_sendto,$sms_message);

				//$email_message = html_cancellation_message($joiner_info['joiner_fname'], $current_adv['adv_date'], $resched_adv['adv_date']);
				//send_email($joiner_info['joiner_email'], "BOOKING CANCELLATION", $email_message);

				header("Location: request.php?cancel_success");
			}
			?>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ob_end_flush();?>
<!-- End Footer -->
