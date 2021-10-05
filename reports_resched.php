<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
	##
	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

	## CHOOSE WHICH ADVENTURE THEN IF RESCHEDULE IS FINAL
	## $_GET['adv_id'] IS ADVENTURE TO BE RESCHED
	if(isset($_GET['adv_id']) && isset($_GET['book_id'])){
		$booked = DB::query("SELECT * FROM booking WHERE book_id=?", array($_GET['book_id']), "READ");
		$booked = $booked[0];
		$current_adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($booked['adv_id']), "READ");
		$current_adv = $current_adv[0];
		$resched_adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($_GET['adv_id']), "READ");
		$resched_adv = $resched_adv[0];

		## UPDATE ADVENTURES CURRENT GUEST
			## CURRENT ADV
			DB::query("UPDATE adventure SET adv_currentGuest=? WHERE adv_id=?", array($current_adv['adv_currentGuest'] - $booked['book_guests'], $booked['adv_id']), "UPDATE");
			## RESCHED ADV
			DB::query("UPDATE adventure SET adv_currentGuest=? WHERE adv_id=?", array($resched_adv['adv_currentGuest'] + $booked['book_guests'], $_GET['adv_id']), "UPDATE");

		## UPDATE IF FULL OR NOT FULL
		adv_full_checker();

		## UPDATE BOOKING ADV_ID
		DB::query("UPDATE booking SET adv_id=? WHERE book_id=?", array($_GET['adv_id'], $_GET['book_id']), "UPDATE");

		## INSERT RESCHED IN REQUEST
		DB::query("INSERT INTO request(req_user, req_type, req_dateprocess, req_dateresponded, req_amount, req_status, req_rcvd, book_id) VALUES(?,?,?,?,?,?,?,?)", array("joiner", "resched", date("Y-m-d"), date("Y-m-d"), $booked['book_totalcosts'], "rescheduled", 0, $_GET['book_id']), "CREATE");

		## EMAIL + SMS NOTIFICATION
		$joiner_db = DB::query("SELECT * FROM joiner WHERE joiner_id = ?", array($_SESSION['joiner']), "READ");
		$joiner_info = $joiner_db[0];

		$sms_sendto = $joiner_info['joiner_phone'];
		$sms_message = "Hi ".$joiner_info['joiner_fname']."! Your booking id ".$_GET['book_id']." has been successfully rescheduled. Thank you!";

		send_sms($sms_sendto,$sms_message);

		//$email_message = html_reschedule_message($joiner_info['joiner_fname'], $current_adv['adv_date'], $resched_adv['adv_date']);
		//send_email($joiner_info['joiner_email'], "BOOKING RESCHEDULE", $email_message);
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
		.sidebar ul ul{height:auto;}

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:30px;text-align:left;}
		main h3{font-weight:500;font-size:20px;color:red;margin:20px 0 0;text-align:center;}
		main table{width:100%;text-align:center;font-size:16px;}
		main table thead{background:#7fdcd3;color:#fff;}
		main table thead tr:hover{background:#7fdcd3;}
		main table thead th{padding:15px 10px;font-weight:bold;line-height:20px;}
		main table tr{border-bottom:1px solid gray;}
		main table tr:hover{background:#fafafa;}
		main table td{padding:15px 10px;line-height:20px;}
		main table td a:hover{text-decoration:none;color:#000;}

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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; <a href="reports_booking.php">Reports </a> &#187; Reschedule
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'reports';
				$currentSubMenu = 'resched';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->
			<main>
				<h2>Rescheduled</h2>

				<?php ##
				// DISPLAY BOOKING REPORTS FOR ORGANIZER
				if(isset($_SESSION['organizer'])){


				// DISPLAY BOOKING REPORTS FOR JOINER
				} else {
					echo "
					<table>
						<thead>
							<tr>
								<th>Book ID</th>
								<th>Date Process</th>
								<th>Amount</th>
								<th>Status</th>
								<th>Receipt</th>
							</tr>
						</thead>
					";

					$booked_resched = DB::query("SELECT * FROM booking b INNER JOIN request r ON b.book_id=r.book_id WHERE joiner_id=? AND req_type=? ORDER BY book_datetime DESC", array($_SESSION['joiner'], "resched"), "READ");

					if(count($booked_resched)>0){
						foreach ($booked_resched as $result) {
							echo "
							<tr>
								<td>".$result['book_id']."</td>
								<td>".date("M j, Y", strtotime($result['req_dateprocess']))."</td>
								<td>₱".number_format($result['req_amount'],2,'.',',')."</td>
								<td><em style='color:#5cb85c;'>".$result['req_status']."</em></td>
								<td><a href='reports_booking-view.php?book_id=".$result['book_id']."' onclick='return confirm(\"View receipt?\");'>view</a></td>
							</tr>
							";
						}
						echo "</table>";

					// NO RECORDS FOUND
					} else {
						echo "</table>";
						echo "<h3>No rescheduled bookings found!</h3>";
					}
				}
				?>

			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
