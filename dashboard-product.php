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

	/* Main Area */
	.main_con{display:flex;justify-content:space-between;}

	main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
	main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}

	main .contents{display:flex;justify-content:space-between;margin:30px 0 0;width:100%;flex-wrap:wrap;}
	main section{width:31%;border:1px solid #cfcfcf;box-shadow:10px 10px 10px -5px #cfcfcf;min-height:200px;position:relative;padding:30px;margin:0 auto 30px;}
	main section h3{font-size:25px;}
	main section h3 span{display:block;}
	main section p{margin:20px 0 0;font-size:60px;color:gray;line-height:60px;}

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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; Password
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'dashboard';
				$currentSubMenu = 'prod';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->

			<main>
				<h2>Products</h2>
				<div class="contents">
					<section>
						<h3>Total <span>Joiners</span></h3>
						<p>
							<?php
							$joiner = DB::query("SELECT * FROM joiner", array(), "READ");
							echo count($joiner);
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Total <span>Organizers</span></h3>
						<p>
							<?php
							$orga = DB::query("SELECT * FROM organizer", array(), "READ");
							echo count($orga);
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Total <span>Paid</span></h3>
						<p>
							<?php
							$total = 0;
							$payment = DB::query("SELECT * FROM payment", array(), "READ");

							if(count($payment)>0){
								foreach ($payment as $result) {
									$total = $total + $result['payment_total'];
								}
							}

							echo "₱".number_format($total, 2, ".", ",");
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Total <span>Bookings</span> </h3>
						<p>
							<?php
							$book = DB::query("SELECT * FROM booking", array(), "READ");
							echo count($book);
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Bookings <span>Paid</span>	</h3>
						<p>
							<?php
							$book_paid = DB::query("SELECT * FROM booking WHERE book_status=?", array("paid"), "READ");
							echo count($book_paid);
							?>
						</p>
					</section>
					<!--  -->
					<section>

					</section>
					<!--  -->
					<section>

					</section>
					<!--  -->
					<section>

					</section>
					<!--  -->
					<section>

					</section>
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
