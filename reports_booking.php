<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
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
		main h3{font-weight:500;font-size:20px;color:red;margin:0 0 10px;text-align:left;}
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
				<h2>Bookings</h2>
				<!-- <h3>Note: Pay anytime by clicking the <i class='fas fa-hand-holding-usd'></i> icon. Request cancel by clicking <i class='fas fa-ban'></i> icon. View booking details by clicking <i class='far fa-eye'></i> icon.</h3> -->

				<?php ##
				// DISPLAY BOOKING REPORTS FOR ORGANIZER
				if(isset($_SESSION['organizer'])){
					echo "
					<table>
						<thead>
							<tr>
								<th>Adventure ID</th>
								<th>Book ID</th>
								<th>Book Guests</th>
								<th>Book Date & Time</th>
								<th>Book Price</th>
								<th>Book Status</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
					";

					$orga_bookings = DB::query("SELECT * FROM booking b INNER JOIN adventure a ON a.adv_id = b.adv_id WHERE orga_id=?", array($_SESSION['organizer']), "READ");

					if(count($orga_bookings)>0){
						foreach ($orga_bookings as $result) {
							echo "
							<tr>
								<td>".$result['adv_id']."</td>
								<td>".$result['book_id']."</td>
								<td>".$result['book_guests']."</td>
								<td>".date("M. j, Y g:i a", strtotime($result['book_datetime']))."</td>
								<td>₱".number_format($result['book_totalcosts'], 2, ".", ",")."</td>
								<td>".$result['book_status']."</td>
								<td></td>
								<td></td>
							</tr>
							";
						}
						echo "</table>";

					// NO RECORDS FOUND
					} else {
						echo "</table>";
						echo "<h3>No bookings found!</h3>";
					}

				// DISPLAY BOOKING REPORTS FOR JOINER
				} else {
					echo "
					<table>
						<thead>
							<tr>
								<th>Book ID</th>
								<th>Book Guests</th>
								<th>Book Date & Time</th>
								<th>Book Price</th>
								<th>Book Status</th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
					";

					$joiner_bookings = DB::query("SELECT * FROM booking WHERE joiner_id=? ORDER BY book_datetime DESC", array($_SESSION['joiner']), "READ");

					if(count($joiner_bookings)>0){
						foreach ($joiner_bookings as $result) {
							## CHECK IF BOOKING IS IN REQUEST
							$req = DB::query("SELECT * FROM request WHERE book_id=? AND req_status!=?", array($result['book_id'], "disapproved"), "READ");
							## GET THE SPECIFIC ADVENTURE
							$adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($result['adv_id']), "READ");
							$adv = $adv[0];
							## PRICE PER PERSON
							$adv_price = number_format(($adv['adv_totalcostprice'] / $adv['adv_maxguests']),2,'.',',');
							## CANNOT CANCEL IN THIS DATE
							$no_cancel_date = date("Y-m-d", strtotime("-10 days", strtotime($adv['adv_date'])));
							## DAYS REMAINING FOR THE ADVENTURE
							$now = time();
							$adv_date = strtotime($adv['adv_date']);
							$days = $adv_date - $now;
							##
							if(count($req) == 0){
								echo "
								<tr>
									<td>".$result['book_id']."</td>
									<td>".$result['book_guests']."</td>
									<td>".date("M. j, Y g:i a", strtotime($result['book_datetime']))."</td>
									<td>₱".number_format($result['book_totalcosts'], 2, ".", ",")."</td>
									<td>".$result['book_status']."</td>
								";

								if($result['book_status'] == "paid"){
									$_SESSION['resched'] = [];
									## DISPLAY ALL ADVENTURES FOR COMPARISON PURPOSES
									$adv_all = DB::query("SELECT * FROM adventure WHERE adv_id!=?", array($result['adv_id']), "READ");
									if(count($adv_all)>0){
										foreach ($adv_all as $this_adv) {
											## PRICE PER PERSON
											$this_adv_price = number_format(($this_adv['adv_totalcostprice'] / $this_adv['adv_maxguests']),2,'.',',');
											## REMAINING GUESTS
											$this_guest = $this_adv['adv_maxguests'] - $this_adv['adv_currentGuest'];
											## CHECK IF THERE ARE THE SAME adv_name, adv_kind, adv_address, adv_totalcostprice/person
											## && CHECK IF THERE ARE ENOUGH GUESTS
											if($this_adv['adv_name'] == $adv['adv_name'] && $this_adv['adv_kind'] == $adv['adv_kind'] && $this_adv['adv_address'] == $this_adv['adv_address'] && $this_adv_price == $adv_price && $this_guest >= $result['book_guests']){
												$_SESSION['resched'][] = $this_adv['adv_id'];
											}
										}

										## CHECK IF RESCHEDULED ONCE
										$resched_once = DB::query("SELECT * FROM request WHERE book_id=? AND req_status=?", array($result['book_id'], "rescheduled"), "READ");
										if(count($resched_once)>0){
											echo "<td><em style='color:#5cb85c;'>rescheduled</em></td>";
										} else {
											if(empty($_SESSION['resched']))
												echo "<td><a href='' onclick='return confirm(\"Sorry! There is no available adventure for you to reschedule!\");'>resched</a></td>";
											else {
												echo "<td><a href='reports_booking-resched.php?book_id=".$result['book_id']."&available=".implode(",",$_SESSION['resched'])."' onclick='return confirm(\"You can only resched this adventure once. Are you sure you want to reschedule?\");'>resched</a></td>";
												## DESTROY THIS SESSION AFTER SENDING
												unset($_SESSION['resched']);
											}
										}
									}

									## CURRENT DATE IS GREATER THAN ADVENTURE DATE (done)
									if($adv['adv_date'] < date("Y-m-d")) {
										echo "<td><em>done</em></td>";

									## CURRENT DATE IS EQUAL ADVENTURE DATE (happening)
									} elseif($adv['adv_date'] == date("Y-m-d")) {
										echo "<td><em>happening</em></td>";

									## CANNOT CANCEL 10days BEFORE THE ADVENTURE DATE (ongoing)
									} elseif(date("Y-m-d") > $no_cancel_date){
										echo "<td><em>".round($days / (60 * 60 * 24))." days to go</em></td>";

									## CANCELABLE DATE
									} else {
										echo "<td><a href='reports_booking-cancel.php?book_id=".$result['book_id']."' onclick='return confirm(\"Are you sure you want to request cancelation for this adventure? BaiPaJoin deducts 30% cancelation fee for the total price you paid (excludes the fee)\");'>cancel</a></td>";
									}
									echo "<td><a href='reports_booking-view.php?book_id=".$result['book_id']."' onclick='return confirm(\"View receipt?\");'>view</a></td>";
								} else {
									echo "<td></td>";
									echo "<td></td>";
									echo "<td><a href='payment-card.php?book_id=".$result['book_id']."&id=".$result['adv_id']."' onclick='return confirm(\"Ready to pay now?\");'>pay</a></td>";
								}
								echo "</tr>";
							}
						}
						echo "</table>";

					// NO RECORDS FOUND
					} else {
						echo "</table>";
						echo "<h3>No bookings found!</h3>";
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
