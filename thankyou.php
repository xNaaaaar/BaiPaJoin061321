<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");
?>

<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->

	<style>
		/* Header Area */
		header{background:url(images/header-bg.png) no-repeat center top/cover, #fff;}
		.main_logo{position:static;margin-left:10px;}
		.comp{color:#000;}

		/* Main Area */
		main{width:100%;flex:4;float:none;height:auto;background:none;margin:0;padding:0 0 50px;border-radius:0;text-align:center;}

		.success{color:#5cb85c;}
		.error{color:red;}

		.place_info{margin:0;}
		.main_info{width:100%;padding:0;}
		.main_info h1{margin:0 0 50px;}
		.main_info h1 span{display:block;font-size:40px;margin:20px 0 0;}
		.main_info figure{width:70%;margin:0 auto;}
		.main_info section{text-align:center;}
		.main_info section i{font-size:110px;}
		.main_info section p{font:400 20px/30px Montserrat,sans-serif;text-align:center;margin:10px auto 0;max-width:100%;width:50% !important;}
		.main_info section ul{margin:70px 0 0;}
		.main_info section ul li{display:inline-block;}
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
	$currentPage = 'adventures';
	include("includes/nav.php");
?>
<!-- End Navigation -->

<!-- Main -->
<div id="main_area">
	<div class="wrapper">
		<div class="main_con">

			<main>
				<div class="place_info">
					<div class="main_info">
						<figure>
							<img src="images/thankyou.jpg" alt="">
						</figure>
						<section>
							<?php
								if(isset($_GET['card']) && isset($_GET['book_id']) && isset($_GET['intentid']) && isset($_GET['total'])) {
									# NECESSARY UPDATES
									booking_paid_updates("card", $_GET['book_id'], $_GET['intentid'], $_GET['total']/100);

								} else if(isset($_GET['gcash']) && $_GET['gcash'] == 1) {
									echo "<i class='far fa-check-circle success'></i><p class='success'>Successfully paid thru gcash!</p>";

								} else if(isset($_GET['grabpay']) && $_GET['grabpay'] == 1) {
									echo "<i class='far fa-check-circle success'></i><p class='success'>Successfully paid thru grabpay!</p>";

								## FAILED PAYING GCASH
								} else if(isset($_GET['gcash']) && $_GET['gcash'] == 0) {
									echo "<i class='far fa-times-circle error'></i><p class='error'>Error paying thru gcash!</p>";

								## FAILED PAYING GRAB PAY
								} else if(isset($_GET['grabpay']) && $_GET['grabpay'] == 0) {
									echo "<i class='far fa-times-circle error'></i><p class='error'>Error paying thru grabpay!</p>";

								} else if(isset($_GET['paymaya'])){
									echo "<i class='far fa-check-circle success'></i><p class='success'>Successfully paid thru paymaya!</p>";

								} else if(isset($_GET['seven-eleven'])){
									echo "<i class='far fa-check-circle success'></i><p class='success'>Successfully paid thru 7/11!</p>";
								}
							?>

							<p>Hooray! We appreciate your recent booking, please check email for your receipt. We hope you'll enjoy your adventure and meet new wonders. Stay safe and thank you for choosing BaiPaJoin.</p>
							<ul>
								<li><a href="index.php">Back to Home</a> l </li>
								<li><a href="reports_booking.php">View Reports</a></li>
							</ul>
						</section>
					</div>
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
