<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
	unset($_SESSION['discounted']);
	##
	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

	## SUCCESS MSG FOR DELETING EXPIRED BOOKING
	if(isset($_GET['exp_suc'])) echo "<script>alert('Successfully deleted expired booking!')</script>";

	## WHEN JOINER WANT A REFUND TO THE CANCELED ADVENTURE OF ORGANIZER
	if(isset($_GET['book_id'])){
		## SPECIFIC ADVENTURE THAT IS CANCELED
		$adv = DB::query("SELECT * FROM booking b INNER JOIN adventure a ON b.adv_id = a.adv_id WHERE book_id=?", array($_GET['book_id']), "READ");
		$adv = $adv[0];

		## UPDATE ADVENTURE CURRENT GUESTS
		DB::query("UPDATE adventure SET adv_currentGuest=? WHERE adv_id=?", array($adv['adv_currentGuest'] - $adv['book_guests'], $adv['adv_id']), "UPDATE");

		## UPDATE BOOKING PAID TO REFUNDED
		## DB::query("UPDATE booking SET book_status=? WHERE book_id=?", array("refunded", $_GET['book_id']), "UPDATE");

		## REFUND 100% PRICE PAID BY JOINER MINUS THE FEE
		$final_price = ($adv['adv_totalcostprice'] / $adv['adv_maxguests']) * $adv['book_guests'];
		$final_price = number_format($final_price, 2, ".", ",");

		## ADD NEW REQUEST AS REFUND
		DB::query("INSERT INTO request(req_user, req_type, req_dateprocess, req_dateresponded, req_amount, req_status, req_rcvd, book_id) VALUES(?,?,?,?,?,?,?,?)", array("joiner", "refund", date("Y-m-d"), date("Y-m-d"), $final_price, "approved", 0, $_GET['book_id']), "CREATE");

		echo "<script>alert('Refund successfully approved. Please check your email to provide details to where you want to send your money.')</script>";
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
		main h2{margin-bottom:30px;}
		main h3{font-weight:500;font-size:20px;color:red;margin:20px 0 0;}
		main form{margin:0 0 20px;text-align:left;}
		main form select{max-width:100%;width:265px;padding:0 15px;margin:15px auto;}
		main form button{max-width:100%;width:100px;padding:0 15px;margin:15px auto;}
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
		@media only screen and (max-width:600px){
			main form button{margin:8px auto!important;width:99%!important;}
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
				<h2>My Bookings</h2>
				<!-- <h3>Note: Pay anytime by clicking the <i class='fas fa-hand-holding-usd'></i> icon. Request cancel by clicking <i class='fas fa-ban'></i> icon. View booking details by clicking <i class='far fa-eye'></i> icon.</h3> -->

				<?php ##
				// DISPLAY BOOKING REPORTS FOR ORGANIZER
				if(isset($_SESSION['organizer'])){
					echo "
					<form method='post'>
						<select name='cboOption' required>
							<option value=''>-- SELECT ADVENTURE --</option>";
							$adv = DB::query('SELECT * FROM adventure WHERE orga_id=?', array($_SESSION['organizer']), 'READ');
							if(count($adv)>0){
								foreach ($adv as $result) {
									## NOT DISPLAY ADV CANCELED OR DONE
									if($result['adv_status'] == "canceled" || $result['adv_status'] == "done") continue;
									echo "<option value='".$result['adv_id']."'>".$result['adv_name']." - ".$result['adv_kind']." (MAX GUESTS: ".$result['adv_maxguests'].")</option>";
								}
							}
					echo "
						</select>
						<button class='edit' type='submit' name='btnSearch'>Search</button>
					</form>
					<div class='scroll-table'>
					";
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

					if(isset($_POST['btnSearch'])){
						$cboOption = $_POST['cboOption'];

						$orga_bookings = DB::query("SELECT * FROM booking b INNER JOIN adventure a ON b.adv_id = a.adv_id WHERE orga_id=? AND a.adv_id=?", array($_SESSION['organizer'], $cboOption), "READ");
					} else {
						$orga_bookings = DB::query("SELECT * FROM booking b INNER JOIN adventure a ON a.adv_id = b.adv_id WHERE orga_id=? AND (adv_status = ? || adv_status = ?)", array($_SESSION['organizer'], "full", "not full"), "READ");
					}

					if(count($orga_bookings)>0){
						if(isset($_POST['btnSearch'])) $remaining_guest = 0;
						foreach ($orga_bookings as $result) {
							## CHECK IF THIS BOOKING IS REFUNDED IN REQUEST
							$request = DB::query("SELECT * FROM request WHERE book_id=? AND req_status=?", array($result['book_id'], "refunded"), "READ");
							if(count($request)>0) continue;
							##
							echo "
							<tr>
								<td>".$result['adv_id']."</td>
								<td>".$result['book_id']."</td>
								<td>".$result['book_guests']."</td>
								<td>".date("M. j, Y g:i a", strtotime($result['book_datetime']))."</td>
								<td>???".number_format($result['book_totalcosts'], 2, ".", ",")."</td>
								<td>".$result['book_status']."</td>
								<td></td>
								<td></td>
							</tr>
							";

							$remaining_guest = $result['adv_maxguests'] - $result['adv_currentGuest'];
						}
						echo "</table>";
						if(isset($_POST['btnSearch'])) echo "<h3>Remaining guest: ".$remaining_guest."</h3>";

					// NO RECORDS FOUND
					} else {
						echo "</table>";
						echo "<h3>No bookings found!</h3>";
					}

				// DISPLAY BOOKING REPORTS FOR JOINER
				} else {
					echo "
					<div class='scroll-table'>
					<table>
						<thead>
							<tr>
								<th>Book ID</th>
								<th>Book Guests</th>
								<th>Book Date & Time</th>
								<th>Book Price</th>
								<th>Book Status</th>
								<th>Adventure Status</th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
					";

					$joiner_bookings = DB::query("SELECT * FROM booking WHERE joiner_id=?", array($_SESSION['joiner']), "READ");
					$_SESSION['pay_counter'] = 0;

					if(count($joiner_bookings)>0){
						foreach ($joiner_bookings as $result) {
							## CHECK IF BOOKING IS IN REQUEST
							$req = DB::query("SELECT * FROM request WHERE book_id=? AND req_status!=? AND req_status!=?", array($result['book_id'], "disapproved", "reverted"), "READ");
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
									<td>???".number_format($result['book_totalcosts'], 2, ".", ",")."</td>
									<td>".$result['book_status']."</td>
									<td>".$adv['adv_status']."</td>
								";

								if($result['book_status'] == "paid"){
									$_SESSION['resched'] = [];
									## DISPLAY ALL ADVENTURES FOR COMPARISON PURPOSES
									$adv_all = DB::query("SELECT * FROM adventure WHERE adv_id!=?", array($result['adv_id']), "READ");
									if(count($adv_all)>0){
										foreach ($adv_all as $this_adv) {
											## DO NOT INCLUDE THE CANCELED ADV
											if($this_adv['adv_status'] == "canceled") continue;
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
											## IF ADV IS DONE
											if($adv['adv_status'] == "done"){
												echo "<td></td>";

											##
											} elseif(empty($_SESSION['resched'])) {
												## CHECK IF ADV IS AVAILABLE FOR RESCHEDULE
												if(adv_is_available($adv['adv_id'], "resched")){
													echo "<td><a href='' onclick='return confirm(\"Sorry! There is no available adventure for you to reschedule!\");'>resched</a></td>";
												} else echo "<td></td>";

											##
											} else {
												## THIS ADV CAN BE RESCHED IF AVAILABLE && NOT REVERTED
												if(adv_is_available($result['adv_id'], "resched") && !adv_is_reverted($result['book_id'])){
													echo "<td><a href='reports_booking-resched.php?book_id=".$result['book_id']."&available=".implode(",",$_SESSION['resched'])."' onclick='return confirm(\"You can only resched this adventure once. Are you sure you want to reschedule?\");'>resched</a></td>";

												} else echo "<td></td>";
											}
										}
									}

									## CHECK IF THIS SPECIFIC ADVENTURE IS CANCELED BY ORGANIZER
									$canceled = false;
									$approved_canceled = DB::query("SELECT * FROM request WHERE adv_id=? AND req_status=?", array($result['adv_id'], "approved"), "READ");
									if(count($approved_canceled)>0) $canceled = true;

									## CURRENT DATE IS GREATER THAN ADVENTURE DATE (done)
									if($adv['adv_date'] < date("Y-m-d")) {
										echo "<td></td>";

									## CURRENT DATE IS EQUAL ADVENTURE DATE (happening)
									} elseif($adv['adv_date'] == date("Y-m-d")) {
										echo "<td><em>happening</em></td>";

									## CANNOT CANCEL 10days BEFORE THE ADVENTURE DATE (ongoing)
									} elseif(date("Y-m-d") > $no_cancel_date){
										echo "<td><em>".round($days / (60 * 60 * 24))." days to go</em></td>";

									## CHECK IF THIS ADVENTURE IS CANCELED BY ORGANIZER
									} elseif($canceled){
										echo "<td><a href='reports_booking.php?book_id=".$result['book_id']."' onclick='return confirm(\"Refunding canceled adventure is 100% moneyback excluding the fee. Are you sure you want a refund?\");'>refund</a></td>";

									## CANCELABLE DATE
									} else {
										echo "<td><a href='reports_booking-cancel.php?book_id=".$result['book_id']."' onclick='return confirm(\"Are you sure you want to request cancelation for this adventure? BaiPaJoin deducts 3% cancelation fee for the total price you paid (excludes the fee)\");'>cancel</a></td>";
									}
									echo "<td><a href='reports_booking-view.php?book_id=".$result['book_id']."' onclick='return confirm(\"View receipt?\");'>view</a></td>";
								} else {
									$_SESSION['waiting_expry'] = date("Y-m-d G:i:s", strtotime("+1 hours", strtotime($result['book_datetime'])));
									$_SESSION['waiting_expry'] = date("M j, Y G:i:s", strtotime($_SESSION['waiting_expry']));
									$_SESSION['pay_counter'] += 1;
									echo "<span style='display:none;' id='waiting_expry".$_SESSION['pay_counter']."'>".$_SESSION['waiting_expry']."</span>";
									echo "<td>payment expiry:</td>";
									echo "<td id='exp_timer".$_SESSION['pay_counter']."'></td>";
									## PAYMENT COUNTDOWN TIMER
									if(isset($_SESSION['waiting_expry']))
										echo "<script>payment_timer(".$_SESSION['pay_counter'].")</script>";
									## JOINER CANNOT PAY IF 5 DAYS BEFORE ADVENTURE
									if(adv_is_available($result['adv_id'], "pay") && $_SESSION['waiting_expry'] > date("M j, Y G:i:s")) {
										echo "<td><a href='payment-card.php?book_id=".$result['book_id']."&id=".$result['adv_id']."' onclick='return confirm(\"Ready to pay now?\");'>pay</a></td>";
									} else
										echo "<td><a href='delete.php?table=booking&book_id=".$result['book_id']."&triggers' onclick='return confirm(\"Are you sure you want to delete this expired booking?\");'><i class='far fa-trash-alt'></i></a></td>";
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
				</div>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
