<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
	ob_start();

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

		.sidebar{flex:1;height:500px;padding:100px 30px 30px 0;position:relative;}
		.sidebar:before{content:'';width:2px;height:70%;background:#cdcdcd;position:absolute;top:50%;right:0;transform:translateY(-50%);}
		.sidebar h2{font-size:25px;line-height:100%;}
		.sidebar ul{display:flex;height:100%;flex-direction:column;justify-content:flex-start;font:600 25px/100% Montserrat,sans-serif;list-style:none;margin:35px 0 0;}
		.sidebar ul li{line-height:45px;}
		.sidebar ul li i{width:40px;position:relative;}
		.sidebar ul li i:before{position:absolute;top:-25px;left:50%;transform:translateX(-50%);}
		.sidebar ul li a{color:#454545;position:relative;}
		.sidebar ul li a small{color:#fff;font-size:15px;position:absolute;top:0;right:-20px;background:#bf127a;height:25px;width:25px;text-align:center;border-radius:50px;line-height:25px;}
		.sidebar ul li a:hover{color:#bf127a;}

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}

		.sub-breadcrumbs{text-align:right;margin-bottom:30px;}
		.sub-breadcrumbs li{display:inline;margin-left:10px;color:gray;}
		.sub-breadcrumbs li span{margin-left:10px;}
		.ongoing{color:#000 !important;}
		.success{color:#5cb85c !important;}
		.error{color:red;}

		.booking_details{min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:10px;padding:30px;line-height:35px;margin:25px auto;border:1px solid #cfcfcf;text-align:left;}
		.booking_details h2{margin:0 0 20px;font:500 35px/100% Montserrat,sans-serif;}
		.booking_details h2 em{display:block;font-size:20px;color:gray;}
		.booking_details ul li span{color:red;}

		.payment_method{min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:10px;padding:30px;line-height:35px;margin:25px auto;border:1px solid #cfcfcf;text-align:left;}
		.payment_method h2{margin:0 0 20px;font:500 35px/100% Montserrat,sans-serif;}
		.payment_method h2 span{margin:0 0 0 20px;}
		.payment_method input{margin:0 auto 10px;}
		.payment_method label span{color:red;}

		.voucher{min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:10px;padding:30px;line-height:35px;margin:25px auto;border:1px solid #cfcfcf;text-align:left;}
		.voucher h2{margin:0 0 20px;font:500 35px/100% Montserrat,sans-serif;}
		.voucher h2 span{color:gray;font-size:25px;}
		.voucher p{margin:0 0 5px 5px;width:100%;}
		.voucher .error{color:red;}
		.voucher section{display:flex;justify-content:space-between;}
		.voucher input{display:inline-block;width:80%;margin:0 auto;}
		.voucher .edit{width:18% !important;margin:0 auto;}

		.price_details{min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:10px;padding:30px;line-height:35px;margin:25px auto;border:1px solid #cfcfcf;text-align:left;}
		.price_details h2{margin:0 0 20px;font:500 35px/100% Montserrat,sans-serif;}
		.price_details ul li{list-style:circle;margin:0 0 10px 22px;}
		.price_details section{position:relative;}
		.price_details section:before{content:"";width:100%;height:2px;background:#cfcfcf;position:absolute;bottom:50px;right:0;}
		.price_details section table{width:100%;}
		.price_details section table tr td{width:70%;}
		.price_details section table tr:last-child td{padding:40px 0 0;}
		.price_details section table tr td:last-child{text-align:right;width:30%;}

		main .edit{width:350px;}

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
			<a href="index.php">Home</a> &#187; <a href="adventures.php">Adventures</a> &#187; Payment
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<aside class="sidebar">
				<h2>Payment Method</h2>
				<ul>
					<li class=""><a href="payment-card.php?book_id=<?php echo $_GET['book_id']; ?>&id=<?php echo $_GET['id']; ?>">Card</a> </li>
					<li class="current_sidebar"><a href="payment-gcash.php?book_id=<?php echo $_GET['book_id']; ?>&id=<?php echo $_GET['id']; ?>">Gcash</a> </li>
					<li class=""><a href="payment-gpay.php?book_id=<?php echo $_GET['book_id']; ?>&id=<?php echo $_GET['id']; ?>">Grab Pay</a> </li>
					<li class=""><a href="payment-paymaya.php?book_id=<?php echo $_GET['book_id']; ?>&id=<?php echo $_GET['id']; ?>">Paymaya</a> </li>
					<li class=""><a href="payment-seven_connect.php?book_id=<?php echo $_GET['book_id']; ?>&id=<?php echo $_GET['id']; ?>">7/11</a> </li>
				</ul>
			</aside>
			<!-- End of Sub Navigation -->

			<?php
				$joiner = DB::query("SELECT * FROM joiner WHERE joiner_id=?", array($_SESSION['joiner']), "READ");
				$adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($_GET['id']), "READ");
				$booked = DB::query("SELECT * FROM booking WHERE book_id=?", array($_GET['book_id']), "READ");

				if(count($joiner)>0 && count($adv)>0 && count($booked)>0){
					$joiner = $joiner[0];
					$adv = $adv[0];
					$booked = $booked[0];
					// JOINER FEES CALCULATION
					$price_fee = $booked['book_totalcosts'] * 0.025;
					$final_price = $booked['book_totalcosts'] + $price_fee;
					//
					$verified = false;
					$discount = 0;
					//
			?>
			<main>
				<ul class="sub-breadcrumbs">
					<li class="ongoing success"><i class="far fa-check-circle"></i> Add Guest <span>&#187;</span></li>
					<li class="ongoing success"><i class="far fa-check-circle"></i> Fill in Guest Information <span>&#187;</span></li>
					<li class="ongoing"><i class="far fa-check-circle"></i> Review & Payment</li>
				</ul>
				<?php //echo $_SESSION['book_id']; ?>
				<h2>Payment</h2>
				<!-- BOOKING DETAILS SECTION -->
				<div class="booking_details">
					<h2>
						<?php
						echo $joiner['joiner_fname']." ".$joiner['joiner_mi'].". ".$joiner['joiner_lname'];
						if(isset($_SESSION['bookOption']) && $_SESSION['bookOption'] == "someone"){
							echo "<em>Booking for someone else.</em>";
						} else {
							echo "<em>Booking as a guest.</em>";
						}
						?>
					</h2>
					<ul>
						<li>Book ID: <b><?php echo $booked['book_id']; ?></b></li>
						<li>Total guest/s:
							<?php
							echo $booked['book_guests'];
							$info = (isset($_SESSION['bookOption']) && $_SESSION['bookOption'] == "someone") ? "(excluding you)" : "(including you)";
							echo " <span>".$info."</span>";
							?>
						</li>
						<li><?php echo date('l, M. j, Y', strtotime($adv['adv_date'])); ?></li>
					</ul>
				</div>
				<!-- VOUCHER SECTION -->
				<div class="voucher">
					<h2>Add Voucher <span><a href="voucher.php?adv_id=<?php echo $adv[0]; ?>&adv_org=<?php echo $adv[15]; ?>&booking_total=<?php echo $final_price; ?>" target="_blank" >look for voucher</a></span> </h2>

					<?php
					if(isset($_POST['btnVerify'])){
						$voucher_code = trim($_POST['txtCode']);
						# CHECK IF VOUCHER EXIST
						$voucher_exist = DB::query("SELECT * FROM voucher WHERE vouch_code=?", array($voucher_code), "READ");

						if(count($voucher_exist)>0){
							$voucher_exist = $voucher_exist[0];
							# CHECK IF VOUCHER EXPIRED
							if($voucher_exist['vouch_enddate'] < date('Y-m-d')){
								echo "<p class='error'>Voucher expired!</p>";

							# CHECK IF VOUCHER MATCH THE SPECIFIC ADVENTURE
							} elseif($voucher_exist['adv_id'] != $_GET['id']){
								echo "<p class='error'>Cannot use voucher in this adventure!</p>";

							# CHECK IF VOUCHER MIN. SPEND ATTAINED BY SPECIFIC ADVENTURE PRICE
							} elseif($voucher_exist['vouch_minspent'] > $booked['book_totalcosts']){
								echo "<p class='error'>Not enough price to use this voucher!</p>";

							# SUCCESS
							} else {
								$discount = $booked['book_totalcosts'] * ($voucher_exist['vouch_discount']/100);
								$final_price -= $discount;

								# KEEP TRACK OF USED VOUCHER
								$_SESSION['used_voucher_code'] = $voucher_exist['vouch_code'];

								echo "<p class='success'>Voucher added successfully!</p>";
							}
						} else {
							echo "<p class='error'>Voucher does not exist!</p>";
						}
					}
					?>
					<form method="post">
						<section>
							<input type="text" name="txtCode" placeholder="Input voucher code">
							<button class="edit" type="submit" name="btnVerify">Verify</button>
						</section>
					</form>
				</div>

				<form method="post">
					<div class="payment_method">
						<h2>Gcash</h2>
						<label>Gcash name <span>*</span> </label>
						<input type="text" name="gcash_name" placeholder="Account name" required>
						<label>Gcash number <span>*</span> </label>
						<input type="text" name="gcash_num" placeholder="11 digit number" maxlength="11" minlength="11" required>
						<label>Email address <span>*</span> </label>
						<input type="email" name="emEmail" placeholder="sample@gmail.com" required>
					</div>
					<div class="price_details">
						<h2 style='color:red;'>Important Notes:</h2>
						<ul>
							<li>Cards payments are processed and verified by our payment gateway (Paymongo Philippines). All payments are subject to fees and other service related charges like any other forms of online payment.</li>
							<li>Fees and other charges includes foreign fee, transaction fee, service fee, inconvience fee and government sanction charges imposed by the law implemented by the payment gateway (Paymongo Philippines) or BaiPaJoin </li>
							<li>All successful bookings can be reschedule for FREE without any approval as long as there is an IDENTICAL ADVENTURE, the NUMBER OF GUEST/S booked can be accomodated by the ADVENTURE SELECTED and it is 5 DAYS PRIOR to the date of adventure. If current date is LESS THAN 5 DAYS before the adventure happens then you cannot reschedule the booking. </li>
							<li>All successful bookings can be cancel subject to 3% inconvience fee and other charges. If a joiner wishes to cancel a booking, he/she may do so as long as it is 10 DAYS PRIOR to the date of adventure. If the adventure is LESS THAN 10 DAYS then he/she cannot cancel the booking. Once a request is submitted BaiPaJoin will approve the request with 3-5 business days.</li>
							<li>You may read the <a href="terms.php" target="_blank">terms and conditions</a> of this website.</li>
						</ul>

					</div>

					<div class="price_details">
						<h2>Price Details</h2>
						<section>
							<table>
								<tr>
									<td><?php echo $adv['adv_name']." - ".$adv['adv_kind']." (".$adv['adv_type'].")"; ?></td>
									<td>₱ <?php echo $booked['book_totalcosts']; ?></td>
								</tr>
								<tr>
									<td>Fees</td>
									<td class="success">+ ₱ <?php echo number_format($price_fee, 2, '.', ''); ?></td>
								</tr>
								<tr>
									<td>Voucher Discount (
										<?php
											if(isset($voucher_exist) && count($voucher_exist)>0)
												echo $voucher_exist['vouch_discount']."%";
											else
												echo "0%";
										?>
										)
									</td>
									<td class="error">- ₱ <?php echo number_format($discount, 2, '.', ''); ?></td>
								</tr>
								<tr>
									<td>Total Price</td>
									<td>₱ <?php echo number_format($final_price, 2, '.', ''); ?></td>
								</tr>
							</table>
						</section>
					</div>

					<button class="edit" type="submit" name="btnGCashEWallet">Pay with Gcash</button>

					<?php
						if(isset($_POST['btnGCashEWallet'])) {
							/*$payment_desc = "This payment is for Booking ID ".$booked['book_id']." under Mr/Ms. " . $_POST['card_name'];*/
							$final_price = number_format($final_price, 2, '', '');
							process_paymongo_ewallet_source('gcash', $final_price, $joiner, $_GET['book_id']);
						}
					?>


				</form>




			</main>
			<?php
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
