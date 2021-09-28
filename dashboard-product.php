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
				$currentSubMenu = 'prod';
				include("includes/sidebar.php");

				$adv_active = $adv_inactive = 0;
				$num_confirm_bookings = $num_pending_bookings = $num_prospect_bookings = 0;
				$vouch_active = $vouch_inactive = $vouch_applied = 0;
				$current_date = date('Y-m-d');

				$adventure_result = DB::query("SELECT * FROM adventure WHERE orga_id=?", array($_SESSION['organizer']), "READ");

				if(!empty($adventure_result)) {

					## CARD NUMBER 1
					$adv_active_db = DB::query("SELECT count(adv_id) FROM adventure WHERE adv_date > '$current_date' and orga_id=?", array($_SESSION['organizer']), "READ");
					$adv_active = $adv_active_db[0];

					## CARD NUMBER 2
					$adv_inactive_db = DB::query("SELECT count(adv_id) FROM adventure WHERE adv_date <= '$current_date' and orga_id=?", array($_SESSION['organizer']), "READ");
					$adv_inactive = $adv_inactive_db[0];

					## CARD NUMBER 3
					$total_num_adv = (int)$adv_active[0]+(int)$adv_inactive[0];
				}

				$adv_ids = DB::query("SELECT adv_id FROM adventure WHERE orga_id=?", array($_SESSION['organizer']), "READ");

				foreach($adv_ids as $id) {

					## CARD NUMBER 4
					$prospect_db = DB::query("SELECT count(adv_id) FROM booking WHERE book_status='pending' and adv_id=?", array($id[0]), "READ");
					$prospect = $prospect_db[0];
					$num_prospect_bookings = $num_prospect_bookings + (int)$prospect[0];

					## CARD NUMBER 5
					$pending_db = DB::query("SELECT count(adv_id) FROM booking WHERE book_status='waiting for payment' and adv_id=?", array($id[0]), "READ");
					$pending = $pending_db[0];
					$num_pending_bookings = $num_pending_bookings + (int)$pending[0];

					## CARD NUMBER 6
					$paid_db = DB::query("SELECT count(adv_id) FROM booking WHERE book_status='paid' and adv_id=?", array($id[0]), "READ");
					$paid = $paid_db[0];
					$num_confirm_bookings = $num_confirm_bookings + (int)$paid[0];
				}

				$voucher_result = DB::query("SELECT * FROM voucher WHERE orga_id=?", array($_SESSION['organizer']), "READ");

				if(!empty($voucher_result)) {

					##CARD NUMBER 7
					$vouch_active_db = DB::query("SELECT count(adv_id) FROM voucher WHERE vouch_enddate >= '$current_date' and orga_id=?", array($_SESSION['organizer']), "READ");
					$vouch_active = $vouch_active_db[0];

					##CARD NUMBER 8
					$vouch_inactive_db = DB::query("SELECT count(adv_id) FROM voucher WHERE vouch_enddate < '$current_date' and orga_id=?", array($_SESSION['organizer']), "READ");
					$vouch_inactive = $vouch_inactive_db[0];

					##CARD NUMBER 9
					$vouch_applied_db = DB::query("SELECT sum(vouch_user) FROM voucher WHERE orga_id=?", array($_SESSION['organizer']), "READ");
					$vouch_applied = $vouch_applied_db[0];
				}

			?>
			<!-- End of Sub Navigation -->

			<main>
				<h2>Products</h2>
				<div class="contents">
					<section>
						<h3>Number of Active Adventures</h3>
						<p>
							<?php
								if(!empty($adv_active))
									echo $adv_active[0];
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Number of Inactive Adventures</h3>
						<p>
							<?php
								if(!empty($adv_inactive))
									echo $adv_inactive[0];
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Total Number of Listed Adventures</h3>
						<p>
							<?php
								if(!empty($adv_inactive) && !empty($adv_active))
									echo $total_num_adv;
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Average # of Prospect Booking per Adventure</h3>
						<p>
							<?php
								if(!empty($num_prospect_bookings))
									echo ($num_prospect_bookings/$total_num_adv);
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Average # of Pending Booking per Adventure </h3>
						<p>
							<?php
								if(!empty($num_pending_bookings))
									echo number_format(($num_pending_bookings/$total_num_adv),2,'.','');
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Average # of Confirmed Booking per Adventure </h3>
						<p>
							<?php
								if(!empty($num_confirm_bookings))
									echo ($num_confirm_bookings/$total_num_adv);
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Number of Active Vouchers</h3>
						<p>
							<?php
								if(!empty($voucher_result))
									echo $vouch_active[0];
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Number of Inactive Vouchers</h3>
						<p>
							<?php
								if(!empty($voucher_result))
									echo $vouch_inactive[0];
								else
									echo 'N/A';
							?>
						</p>
					</section>
					<!--  -->
					<section>
						<h3>Total Number of Applied Vouchers</h3>
						<p>
							<?php
								if(!empty($voucher_result))
									echo $vouch_applied[0];
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
