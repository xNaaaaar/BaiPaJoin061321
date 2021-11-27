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
			<main>
				<form method="post">
					<h2>Reason for Cancelling</h2>
					<input list="valid_reason" name="txtReason" placeholder="Input reason if not stated below.." required/>
					<datalist id="valid_reason">
						<option value="Valid Reason 1">Valid Reason 1</option>
						<option value="Valid Reason 2">Valid Reason 2</option>
						<option value="Valid Reason 3">Valid Reason 3</option>
						<option value="Valid Reason 4">Valid Reason 4</option>
						<option value="Valid Reason 5">Valid Reason 5</option>
						<option value="Valid Reason 6">Valid Reason 6</option>
					</datalist>
					<?php
					if(isset($_SESSION['joiner'])) {
						echo "<button class='edit' type='submit' name='btnRequest-Joiner'>Request</button>";
						echo "<a class='edit' href='reports_booking.php'>Back</a>";
					} else {
						echo "<button class='edit' type='submit' name='btnRequest-Organizer'>Request</button>";
						echo "<a class='edit' href='adventures_posted.php'>Back</a>";
					}
					?>
				</form>
			</main>

			<?php
			## REQUEST CANCEL FOR JOINER
			if(isset($_POST['btnRequest-Joiner'])){
				$txtReason = ucfirst(trim($_POST['txtReason']));
				##
				$booked_db = DB::query("SELECT * FROM booking WHERE book_id=?", array($_GET['book_id']), "READ");
				$booked = $booked_db[0];
				##
				DB::query("INSERT INTO request (req_user, req_type, req_dateprocess, req_amount, req_status, req_reason, req_rcvd, book_id, adv_id) VALUES(?,?,?,?,?,?,?,?,?)", array('joiner', 'cancel', date("Y-m-d"), $booked['book_totalcosts'], 'pending', $txtReason, 0, $_GET['book_id'], NULL), "CREATE");

				## EMAIL JOINER NOTIFICATION
				$joiner_db = DB::query("SELECT * FROM joiner WHERE joiner_id = ?", array($_SESSION['joiner']), "READ");
				$joiner_info = $joiner_db[0];

				$email_message = html_request_message($joiner_info['joiner_fname'], 1, 'joiner');

				$img_address = array();
				$img_name = array();

				array_push($img_address,'images/request-bg.png','images/main-logo-green.png','images/request-img.png');
				array_push($img_name,'background','logo','main');

				send_email($joiner_info['joiner_email'], "REQUEST ACKNOWLEDGED", $email_message, $img_address, $img_name);

				header("Location: request.php?cancel_success");
			}

			## REQUEST CANCEL FOR ORGANIZER
			if(isset($_POST['btnRequest-Organizer'])){
				$txtReason = ucfirst(trim($_POST['txtReason']));
				##
				$adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($_GET['adv_id']), "READ");
				$adv = $adv[0];
				##
				DB::query("INSERT INTO request(req_user, req_type, req_dateprocess, req_amount, req_status, req_reason, req_rcvd, book_id, adv_id) VALUES(?,?,?,?,?,?,?,?,?)", array('organizer', 'cancel', date("Y-m-d"), $adv['adv_totalcostprice'], "pending", $txtReason, 0, NULL, $_GET['adv_id']), "CREATE");

				## EMAIL ORGANIZER NOTIFICATION
				$organizer_db = DB::query("SELECT * FROM organizer WHERE orga_id = ?", array($_SESSION['organizer']), "READ");
				$organizer_info = $organizer_db[0];

				$email_message = html_request_message($organizer_info['orga_fname'], 1, 'organizer');

				$img_address = array();
				$img_name = array();

				array_push($img_address,'images/request-bg.png','images/main-logo-green.png','images/request-img.png');
				array_push($img_name,'background','logo','main');

				send_email($organizer_info['orga_email'], "REQUEST ACKNOWLEDGED", $email_message, $img_address, $img_name);

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
