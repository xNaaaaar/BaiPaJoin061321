<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

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

		.sidebar{flex:1;height:500px;padding:50px 30px 30px 0;position:relative;}
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

		.booking_details{min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:10px;padding:30px;line-height:35px;margin:25px auto;border:1px solid #cfcfcf;text-align:left;}
		.booking_details h2{margin:0 0 20px;font:500 35px/100% Montserrat,sans-serif;}
		.booking_details h2 em{display:block;font-size:20px;color:gray;}

		.payment_method{min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:10px;padding:30px;line-height:35px;margin:25px auto;border:1px solid #cfcfcf;text-align:left;}
		.payment_method h2{margin:0 0 20px;font:500 35px/100% Montserrat,sans-serif;}
		.payment_method h2 span{margin:0 0 0 20px;}
		.payment_method input{display:inline-block;width:99%;height:60px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 18px/20px Montserrat,sans-serif;padding:0 30px;margin:0 auto 15px;border:1px solid #cfcfcf;}
		.payment_method label span{color:red;}

		.price_details{min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:10px;padding:30px;line-height:35px;margin:25px auto;border:1px solid #cfcfcf;text-align:left;}
		.price_details h2{margin:0 0 20px;font:500 35px/100% Montserrat,sans-serif;}
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
					$price_fee = $booked['book_totalcosts'] * 0.035 + 15;
					$final_price = $booked['book_totalcosts'] + $price_fee;
			?>
			<main>
				<ul class="sub-breadcrumbs">
					<li class="ongoing success"><i class="far fa-check-circle"></i> Add Guest <span>&#187;</span></li>
					<li class="ongoing success"><i class="far fa-check-circle"></i> Fill in Guest Information <span>&#187;</span></li>
					<li class="ongoing"><i class="far fa-check-circle"></i> Review & Payment</li>
				</ul>
				<form method="post">
					<h2>Payment</h2>

					<div class="booking_details">
						<h2>
							<?php
							echo $joiner['joiner_fname']." ".$joiner['joiner_mi'].". ".$joiner['joiner_lname'];
							if($_SESSION['bookOption'] == "someone"){
								echo "<em>Booking for someone else.</em>";
							} else {
								echo "<em>Booking as a guest.</em>";
							}
							?>
						</h2>
						<ul>
							<li>Book ID: <?php echo $booked['book_id']; ?></li>
							<li>Total guest/s: <?php echo $booked['book_guests']; ?></li>
							<li>on <?php echo date('l - M. j, Y', strtotime($adv['adv_date'])); ?></li>
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
									<td>₱ <?php echo number_format($price_fee, 2, '.', ''); ?></td>
								</tr>
								<tr>
									<td>Total Price</td>
									<td>₱ <?php echo number_format($final_price, 2, '.', ''); ?></td>
								</tr>
							</table>
						</section>
					</div>

					<button class="edit" type="button" name="button">Pay with Gcash</button>
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
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
