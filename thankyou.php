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
		main{width:100%;flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0;border-radius:0;text-align:center;}
		main h2{}

		.success{color:#5cb85c;}
		.error{color:red;}

		.place_info{margin:0;}
		.main_info{width:100%;padding:0;}
		.main_info h1{font:600 50px/100% Montserrat,sans-serif;margin:0 0 70px;}
		.main_info section{text-align:center;}
		.main_info section i{font-size:120px;}
		.main_info section p{font:400 35px/30px Montserrat,sans-serif;text-align:center;margin:10px 0 0;}
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
						<h1>Spontaneous. Thank you!</h1>
						<section>
							<?php
								if(isset($_GET['card']) && isset($_GET['book_id']) && isset($_GET['intentid'])) {
									# NECESSARY UPDATES
									booking_paid_updates("card", $_GET['book_id'], $_GET['intentid']);

								} else if(isset($_GET['gcash']) && $_GET['gcash'] == 1) {
									echo "<i class='far fa-check-circle success'></i><p class='success'>Successfully paid thru gcash!</p>";

								} else if(isset($_GET['grabpay']) && $_GET['grabpay'] == 1) {
									echo "<i class='far fa-check-circle success'></i><p class='success'>Successfully paid thru grabpay!</p>";

								} else if(isset($_GET['gcash']) && $_GET['gcash'] == 0) {
									echo "<i class='far fa-times-circle error'></i><p class='error'>Error paying thru gcash!</p>";

								} else if(isset($_GET['grabpay']) && $_GET['grabpay'] == 0) {
									echo "<i class='far fa-times-circle error'></i><p class='error'>Error paying thru grabpay!</p>";
								}
							?>
							<ul>
								<li><a href="#">Back to Home</a> | </li>
								<li><a href="#">View Payment Reports</a> | </li>
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
