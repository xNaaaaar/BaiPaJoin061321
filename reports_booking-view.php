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
		main h1{font:600 45px/100% Montserrat,sans-serif;color:#313131;text-align:center;margin:-70px 0 0;}
		main h2{font:600 30px/100% Montserrat,sans-serif;margin:15px 0;text-align:left;}
		main figure{width:60%;margin:-40px auto 0;}
		main section{margin:50px 0 0;position:relative;}
		main section:before{content:"";width:100%;height:3px;background:#7fdcd3;position:absolute;top:-25px;left:0;}
		main ol{text-align:left;}
		main table{width:100%;margin:0 auto 0;text-align:left;}
		main table tr td{width:50%;line-height:25px;}

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
			<?php
			## JOINER BOOKING WANT TO VIEW
			$booked = DB::query("SELECT * FROM booking WHERE book_id=?", array($_GET['book_id']), "READ");
			$booked = $booked[0];

			## 	RETRIEVE PAYMENT DETAILS
			$payment_db = DB::query("SELECT * FROM payment WHERE book_id=?", array($_GET['book_id']), "READ");
			$payment = $payment_db[0];
			if($payment['payment_method'] == 'card') {
				$payment_details = retrieve_paymongo_card_payment($payment['payment_id']);

				$pay_id = $payment_details['data']['id'];
				$pay_desc = $payment_details['data']['attributes']['description'];
				$pay_type = $payment_details['data']['attributes']['payments'][0]['attributes']['source']['brand'];
				$pay_lastdigit = $payment_details['data']['attributes']['payments'][0]['attributes']['source']['last4'];

				## PER GUESTS COST
				$per_guest = $payment_details['data']['attributes']['payments'][0]['attributes']['net_amount'];
				$fee = $payment_details['data']['attributes']['payments'][0]['attributes']['fee'];
				$total = $payment_details['data']['attributes']['payments'][0]['attributes']['amount'];

				## NUMBER FORMAT
				$per_guest = number_format(($per_guest/100),2,".",",");
				$fee = number_format(($fee/100),2,".",",");
				$total = number_format(($total/100),2,".",",");

				## BILLING INFO
				$bill_name = $payment_details['data']['attributes']['payments'][0]['attributes']['billing']['name'];
				$bill_emailadd = $payment_details['data']['attributes']['payments'][0]['attributes']['billing']['email'];
				$bill_phone = $payment_details['data']['attributes']['payments'][0]['attributes']['billing']['phone'];
				$bill_address = $payment_details['data']['attributes']['payments'][0]['attributes']['billing']['address']['line1'];
			}
			else {
				$payment_details = retrieve_paymongo_ewallet_payment($payment['payment_id']);

				$pay_id = $payment_details['data']['id'];
				$pay_desc = 'N/A';
				$pay_type = $payment_details['data']['attributes']['source']['type'];
				$pay_lastdigit = 'N/A';

				## PER GUESTS COST
				$per_guest = $payment_details['data']['attributes']['net_amount'];
				$fee = $payment_details['data']['attributes']['fee'];
				$total = $payment_details['data']['attributes']['amount'];

				## NUMBER FORMAT
				$per_guest = number_format(($per_guest/100),2,".",",");
				$fee = number_format(($fee/100),2,".",",");
				$total = number_format(($total/100),2,".",",");

				## BILLING INFO
				$bill_name = $payment_details['data']['attributes']['billing']['name'];
				$bill_emailadd = $payment_details['data']['attributes']['billing']['email'];
				$bill_phone = $payment_details['data']['attributes']['billing']['phone'];
				$bill_address = 'N/A';
			}

			## THE ADV JOINER BOOKED
			$adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($booked['adv_id']), "READ");
			$adv = $adv[0];

			## JOINER DETAILS
			$joiner = DB::query("SELECT * FROM joiner WHERE joiner_id=?", array($_SESSION['joiner']), "READ");
			$joiner = $joiner[0];
			?>
			<main>
				<figure>
					<img src="images/receipt.jpg" alt="">
				</figure>
				<h1>Itinerary Receipt</h1>
				<section>
					<h2>Booking Details</h2>
					<table>
						<tr>
							<td>Adventure Name</td>
							<td><b><?php echo $adv['adv_name']; ?></b></td>
						</tr>
						<tr>
							<td>Booking ID</td>
							<td><b><?php echo $booked['book_id']; ?></b></td>
						</tr>
						<tr>
							<td>Booking Date</td>
							<td><b><?php echo date("M. j, Y", strtotime($adv['adv_date'])); ?></b></td>
						</tr>
						<tr>
							<td>Status</td>
							<td><b><?php echo strtoupper($booked['book_status']); ?></b></td>
						</tr>
					</table>
				</section>
				<section>
					<h2>Guest Details</h2>
					<ol>
						<?php

							$num_guests_db = DB::query("SELECT book_guests FROM booking WHERE book_id=?", array($booked['book_id']), "READ");
								$num_guests = $num_guests_db[0];

							$guests = DB::query("SELECT * FROM guest WHERE book_id=?", array($booked['book_id']), "READ");

							## BOOK FOR SOMEONE ELSE
							if(count($guests) == $num_guests[0]) {
								foreach ($guests as $result) {
									echo "<li>".$result['guest_name']."</li>";
								}
							}

							## BOOK AS A GUEST
							else if(count($guests) < $num_guests[0]) {

								$joiner_db = DB::query("SELECT joiner_fname, joiner_lname FROM joiner WHERE joiner_id =?", array($_SESSION['joiner']), "READ");
								$joiner = $joiner_db[0];

								echo "<li>".$joiner[0]." ".$joiner[1]." (you)</li>";

								foreach ($guests as $result) {
									echo "<li>".$result['guest_name']."</li>";
								}
							}
						?>

					</ol>
				</section>
				<section>
					<h2>Payment Details</h2>
					<table>
						<tr>
							<td>Payment ID</td>
							<td><b><?php echo $pay_id; ?></b></td>
						</tr>
						<tr>
							<td>Payment Description</td>
							<td><b><?php echo $pay_desc; ?></b></td>
						</tr>
						<tr><td><br></td></tr>
						<tr>
							<td>Paid Thru</td>
							<td><b><?php echo strtoupper($pay_type); ?></b></td>
						</tr>
						<tr>
							<td>Last 4 Digits</td>
							<td><b><?php echo $pay_lastdigit; ?></b></td>
						</tr>
						<tr>
							<td>Payment Time</td>
							<td><b><?php echo date("M. j, Y @H:i a", strtotime($payment['payment_datetime'])); ?></b></td>
						</tr>
						<tr><td><br></td></tr>
						<tr>
							<td>Adventure Cost</td>
							<td><b><?php echo "₱".$per_guest; ?></b></td>
						</tr>
						<tr>
							<td>Fee and Other Charges</td>
							<td><b><?php echo "₱".$fee; ?></b></td>
						</tr>
						<tr>
							<td>Total Amount Paid</td>
							<td><b><?php echo "₱".$total; ?></b></td>
						</tr>
					</table>
				</section>
				<section>
					<h2>Billing Details</h2>
					<table>
						<tr>
							<td>Name</td>
							<td><b><?php echo $bill_name ?></b></td>
						</tr>
						<tr>
							<td>Email Address</td>
							<td><b><?php echo $bill_emailadd ?></b></td>
						</tr>
						<tr>
							<td>Phone Number</td>
							<td><b><?php echo $bill_phone ?></b></td>
						</tr>
						<tr>
							<td>Address</td>
							<td><b><?php echo $bill_address ?></b></td>
						</tr>
					</table>
				</section>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ob_end_flush();?>
<!-- End Footer -->
