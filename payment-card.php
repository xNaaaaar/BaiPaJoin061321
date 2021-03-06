<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
	ob_start();

	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

	if(isset($_POST['btnCont2']) && isset($_GET['book_id'])){
		booking("waiting for payment", $_GET['book_id']);
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

		.sidebar{flex:1;height:400px;padding:100px 30px 30px 0;position:relative;}
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
		main .edit{width:300px;}

		.sub-breadcrumbs{text-align:right;margin-bottom:30px;}
		.sub-breadcrumbs li{display:inline;margin-left:10px;color:gray;}
		.sub-breadcrumbs li span{margin-left:10px;}
		.ongoing{color:#000 !important;}
		.success{color:#5cb85c !important;}
		.error{color:red;}

		.card-contents{min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:10px;padding:30px;line-height:35px;margin:25px auto;border:1px solid #cfcfcf;text-align:left;background:#fff;}

		.booking_details h2{margin:0 0 20px;font:500 30px/100% Montserrat,sans-serif;}
		.booking_details h2 em{display:block;font-size:20px;color:gray;}
		.booking_details ul li span{color:red;}

		.payment_method h2{margin:0 0 20px;font:500 30px/100% Montserrat,sans-serif;}
		.payment_method h2 span{margin:0 0 0 20px;}
		.payment_method input{margin:0 auto 10px;}
		.payment_method label span{color:red;}

		.voucher h2{margin:0 0 20px;font:500 30px/100% Montserrat,sans-serif;}
		.voucher h2 span{color:gray;font-size:20px;display:inline-block;}
		.voucher p{margin:0 0 5px 5px;width:100%;}
		.voucher .error{color:red;}
		.voucher section{display:flex;justify-content:space-between;}
		.voucher input{display:inline-block;width:80%;margin:0 auto;}
		.voucher .edit{width:18% !important;margin:0 auto;}

		.price_details h2{margin:0 0 20px;font:500 30px/100% Montserrat,sans-serif;}
		.price_details ul li{list-style:circle;margin:0 0 10px 22px;}
		.price_details p{width:100% !important;}
		.price_details section{position:relative;}
		.price_details section:before{content:"";width:100%;height:2px;background:#cfcfcf;position:absolute;bottom:50px;right:0;}
		.price_details section table{width:100%;}
		.price_details section table tr td{width:70%;}
		.price_details section table tr:last-child td{padding:40px 0 0;}
		.price_details section table tr td:last-child{text-align:right;width:30%;}

		/*RESPONSIVE*/
		@media only screen and (max-width:1000px) {
			main{padding:50px 0 0 25px;}
		}
		/*RESPONSIVE*/
		@media only screen and (max-width:800px) {
			main .edit{width:49%;}
		}
		@media only screen and (max-width:600px) {
			main .edit{width:100%;margin:5px auto;}

			.voucher section{display:block;}
			.voucher input{display:block;width:100%;}
			.voucher .edit{width:99.5% !important;}
		}
		@media only screen and (max-width:500px){
			.card-contents{padding:20px;}
			.payment_method h2 span{margin:0;display:inline-block;}
			.price_details section table tbody{font-size:15px;}
			.price_details section table tr td{width:50%!important;}
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
					<li class="current_sidebar"><a href="payment-card.php?book_id=<?php echo $_GET['book_id']; ?>&id=<?php echo $_GET['id']; ?>">Card</a> </li>
					<li><a href="payment-gcash.php?book_id=<?php echo $_GET['book_id']; ?>&id=<?php echo $_GET['id']; ?>">Gcash</a> </li>
					<li><a href="payment-gpay.php?book_id=<?php echo $_GET['book_id']; ?>&id=<?php echo $_GET['id']; ?>">Grab Pay</a> </li>
					<li class=""><a href="payment-paymaya.php?book_id=<?php echo $_GET['book_id']; ?>&id=<?php echo $_GET['id']; ?>">Paymaya</a> </li>
					<li class=""><a href="payment-seven_connect.php?book_id=<?php echo $_GET['book_id']; ?>&id=<?php echo $_GET['id']; ?>">7/11</a> </li>
				</ul>
			</aside>
			<!-- End of Sub Navigation -->

			<?php
				$joiner = DB::query("SELECT * FROM joiner WHERE joiner_id=?", array($_SESSION['joiner']), "READ");
				$booked = DB::query("SELECT * FROM booking WHERE book_id=?", array($_GET['book_id']), "READ");
				$adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($_GET['id']), "READ");

				if(count($joiner)>0 && count($adv)>0 && count($booked)>0){
					$joiner = $joiner[0];
					$adv = $adv[0];
					//$adv_org = $adv[15];
					$booked = $booked[0];
					// JOINER FEES CALCULATION
					$price_fee = $booked['book_totalcosts'] * 0.035 + 15;
					$final_price = $booked['book_totalcosts'] + $price_fee;
					//
					$verified = false;
					$discount = 0;
			?>
			<main>
				<ul class="sub-breadcrumbs">
					<li class="ongoing success"><i class="far fa-check-circle"></i> Add Guest <span>&#187;</span></li>
					<li class="ongoing success"><i class="far fa-check-circle"></i> Fill in Guest Information <span>&#187;</span></li>
					<li class="ongoing"><i class="far fa-check-circle"></i> Review & Payment</li>
				</ul>
				<h2>Payment</h2>

				<div class="booking_details card-contents">
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

				<div class="voucher card-contents">
					<h2>Add Voucher <span><a href="voucher.php?adv_id=<?php echo $adv[0]; ?>&adv_org=<?php echo $adv[15]; ?>&booking_total=<?php echo $final_price; ?>" target="_blank" >view vouchers &#187;</a></span> </h2>

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
									$_SESSION['discounted'] = $final_price - $discount;

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
					<div class="payment_method card-contents">
						<h2>Card Details <span><i class="far fa-credit-card"></i> <i class="fab fa-cc-visa"></i> <i class="fab fa-cc-mastercard"></i></span> </h2>
						<label>Card name <span>*</span> </label>
						<input type="text" name="card_name" placeholder="Your name (as it appears on your card)" required>
						<label>Card number <span>*</span> </label>
						<input type="text" name="card_num" placeholder="16 digit card number" maxlength="16" minlength="16" required>
						<label>Valid until <span>*</span> </label>
						<!-- INPUT TYPE MONTH FORMAT: 2025-12 -->
						<input type="month" name="card_expiry" min="<?php echo date("Y-m"); ?>" required>
						<label>CVV <span>*</span> </label>
						<input type="text" name="card_cvv" placeholder="3 digit code (at the back of the card)" maxlength="3" minlength="3" required>
					</div>
					<?php
					## IMPORTANT NOTICE
					include("includes/notice.php");
					?>
					<div class="price_details card-contents">
						<h2>Price Details</h2>
						<section>
							<table>
								<tr>
									<td><?php echo $adv['adv_name']." - ".$adv['adv_kind']." (".$adv['adv_type'].")"; ?></td>
									<td>??? <?php echo number_format($booked['book_totalcosts'],2,'.',','); ?></td>
								</tr>
								<tr>
									<td>Fees</td>
									<td class="success">+ ??? <?php echo number_format($price_fee, 2, '.', ','); ?></td>
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
									<td class="error">- ??? <?php echo number_format($discount, 2, '.', ''); ?></td>
								</tr>
								<tr>
									<td>Total Price</td>
									<td>???
										<?php
										// IF NO VOUCHER USED
										// echo number_format($final_price, 2, '.', '');
										if(!isset($_SESSION['discounted'])) $_SESSION['discounted'] = $final_price;

										echo number_format($_SESSION['discounted'], 2, '.', ',');
										?>
									</td>
								</tr>
							</table>
						</section>
					</div>

					<button class="edit" type="submit" name="btnPayCard">Pay with Credit/Debit Card</button>
					<a class="edit" href="reports_booking.php?countdown" onclick='return confirm("You will be given 1hour to pay this booking!");'>Pay Later</a>

					<?php
						if(isset($_POST['btnPayCard'])) {
							// LIST OF VALID TEST CARD
							$test_card_list = array(4343434343434345, 4571736000000075, 4009930000001421, 4404520000001439, 5555444444444457, 5455590000000009, 5339080000000003, 5240050000001440, 5577510000001446);

							// CHECK IF INPUTTED CARD NAME HAS NUMBER
							if(preg_match('~[0-9]+~', $_POST['card_name'])){
								echo "<script>alert('Card name cannot consist a number!')</script>";

							// IF NO ERROR
							} else {
								// DISCOUNTED PRICE
								// $final_price = number_format($final_price, 2, '', '');
								$_SESSION['discounted'] = number_format($_SESSION['discounted'], 2, '', '');

								// PAYMENT FUNCTION
								$payment_desc = "This payment is for Booking ID ".$booked['book_id']." under Mr/Ms. " . $joiner[1] . " " . $joiner[2];
								$result = process_paymongo_card_payment($_POST['card_name'], $_POST['card_num'], str_replace("-","",$_POST['card_expiry']), $_POST['card_cvv'], $_SESSION['discounted'], $payment_desc, $joiner);

								if($result[1] == 'succeeded')
									header("Location: thankyou.php?card&book_id=".$booked['book_id']."&intentid=".$result[0]."&total=".$_SESSION['discounted']);
								else
									echo "<script>alert('".$result[1]."')</script>";

								unset($_SESSION['discounted']);
							}
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
