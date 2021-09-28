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
	main section{width:31%;border:1px solid #cfcfcf;box-shadow:10px 10px 10px -5px #cfcfcf;min-height:200px;position:relative;padding:30px 30px 110px;margin:0 auto 30px;}
	main section h3{font:600 25px/100% Montserrat,sans-serif;}
	main section p{margin:20px 0 0;font-size:50px;color:gray;line-height:50px;position:absolute;bottom:30px;left:0;right:0;}

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
				$currentSubMenu = 'sales';
				include("includes/sidebar.php");

				$num_confirm_bookings = $num_pending_bookings = $num_prospect_bookings = 0;
				$confirm_php = $pending_php = $prospect_php = 0;

				$adv_ids = DB::query("SELECT adv_id FROM adventure WHERE orga_id=?", array($_SESSION['organizer']), "READ");

				foreach($adv_ids as $id) {

					$prospect_db = DB::query("SELECT count(adv_id) FROM booking WHERE book_status='pending' and adv_id=?", array($id[0]), "READ");
					$prospect = $prospect_db[0];
					$num_prospect_bookings = $num_prospect_bookings + (int)$prospect[0];

					$pending_db = DB::query("SELECT count(adv_id) FROM booking WHERE book_status='waiting for payment' and adv_id=?", array($id[0]), "READ");
					$pending = $pending_db[0];
					$num_pending_bookings = $num_pending_bookings + (int)$pending[0];

					$paid_db = DB::query("SELECT count(adv_id) FROM booking WHERE book_status='paid' and adv_id=?", array($id[0]), "READ");
					$paid = $paid_db[0];
					$num_confirm_bookings = $num_confirm_bookings + (int)$paid[0];

					$prospect_val_db = DB::query("SELECT sum(book_totalcosts) FROM booking WHERE book_status='pending' and adv_id=?", array($id[0]), "READ");
					$prospect_val = $prospect_val_db[0];
					$prospect_php = $prospect_php + (int)$prospect_val[0];

					$pending_val_db = DB::query("SELECT sum(book_totalcosts) FROM booking WHERE book_status='waiting for payment' and adv_id=?", array($id[0]), "READ");
					$pending_val = $pending_val_db[0];
					$pending_php = $pending_php + (int)$pending_val[0];

					$confirm_val_db = DB::query("SELECT sum(book_totalcosts) FROM booking WHERE book_status='paid' and adv_id=?", array($id[0]), "READ");
					$confirm_val = $confirm_val_db[0];
					$confirm_php = $confirm_php + (int)$confirm_val[0];

					//file_put_contents('debug.log', date('h:i:sa').' => ' .$prospect_php. "\n" . "\n", FILE_APPEND);
				}

			?>
			<!-- End of Sub Navigation -->

			<main>
				<h2>Sales</h2>
				<div class="contents">
					<section>
						<h3>Number of Prospect Bookings</h3>
						<p>
							<?php
								if($num_prospect_bookings != 0)
									echo $num_prospect_bookings;
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Number of Pending Bookings</h3>
						<p>
							<?php
								if($num_pending_bookings != 0)
									echo $num_pending_bookings;
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Number of Confirmed Bookings</h3>
						<p>
							<?php
								if($num_confirm_bookings != 0)
									echo $num_confirm_bookings;
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Value (PHP) of Prospect Bookings</h3>
						<p>
							<?php
								echo "₱".number_format($prospect_php, 2, ".", ",");
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Value (PHP) of Pending Bookings</h3>
						<p>
							<?php
								echo "₱".number_format($pending_php, 2, ".", ",");
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Value (PHP) of Confirm Bookings</h3>
						<p>
							<?php
								echo "₱".number_format($confirm_php, 2, ".", ",");
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Prospect to Confirm Booking Conversion Ratio</h3>
						<p>
							<?php
								if($num_prospect_bookings != 0)
									echo round((($num_confirm_bookings/($num_prospect_bookings+$num_confirm_bookings))*100)).'%';
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Prospect to Pending Booking Conversion Ratio</h3>
						<p>
							<?php
								if($num_pending_bookings != 0)
									echo round((($num_pending_bookings/($num_prospect_bookings+$num_pending_bookings))*100)).'%';
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Pending to Confirm Booking Conversion Ratio</h3>
						<p>
							<?php
								if($num_confirm_bookings != 0)
									echo round((($num_confirm_bookings/($num_pending_bookings+$num_confirm_bookings))*100)).'%';
								else
									echo 'N/A';
							?>
						</p>
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
