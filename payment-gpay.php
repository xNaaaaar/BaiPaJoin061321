<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
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
					<li class=""><a href="payment-card.php">Card</a> </li>
					<li class=""><a href="payment-gcash.php">Gcash</a> </li>
					<li class="current_sidebar"><a href="payment-gpay.php">Grab Pay</a> </li>
				</ul>
			</aside>
			<!-- End of Sub Navigation -->

			<main>
				<form method="post">
					<h2>Payment</h2>

					<div class="booking_details">
						<h2>Melnar Ancit <em>Booking for someone else.</em> </h2>
						<ul>
							<li>Book ID: 123123</li>
							<li>Total guest/s: 3</li>
							<li>on Monday, Sept. 23, 2021</li>
						</ul>
					</div>

					<div class="price_details">
						<h2>Price Details</h2>
						<section>
							<table>
								<tr>
									<td>Adventure Sample Name 1 - Island Hopping (Packaged)</td>
									<td>₱ 780.00</td>
								</tr>
								<tr>
									<td>Taxes and Fees</td>
									<td>₱ 120.00</td>
								</tr>
								<tr>
									<td>Total Price</td>
									<td>₱ 900.00</td>
								</tr>
							</table>
						</section>
					</div>

					<button class="edit" type="button" name="button">Pay with Gcash</button>
				</form>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
