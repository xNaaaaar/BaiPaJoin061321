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

	/* main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
	main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}

	main .contents{display:flex;justify-content:space-between;margin:30px 0 0;width:100%;flex-wrap:wrap;}

	main section{width:30%;border:1px solid #cfcfcf;box-shadow:10px 10px 10px -5px #cfcfcf;min-height:200px;position:relative;padding:30px 30px 110px;margin:0 auto 30px;}

	main #graph{width:68%; border:1px solid #cfcfcf;box-shadow:10px 10px 10px -5px #cfcfcf;min-height:200px;position:relative;padding:30px 30px 110px;margin:0 auto 30px;}

	main section h3{font:600 25px/100% Montserrat,sans-serif;}

	main section #chartContainer{min-height:200px;position:relative;padding:30px 30px 110px;margin:0 auto 30px;}

	main section p{margin:10px 0 0; font: 25px/100% Montserrat,sans-serif; color:gray;line-height:50px;position:absolute; bottom:50px;left:0;right:0;} */

	/********************************/
	main{flex:4;float:none;height:100%;background:none;margin:0;padding:50px 50px 0;border-radius:0;text-align:center;}

	main .contents{display:flex;justify-content:space-between;margin:30px 0 50px;width:100%;flex-wrap:wrap;}

	main section h3{font-size:25px;margin:0 0 10px;color:#313131;}
	main section{width:30%;height:auto;border:1px solid #cfcfcf;box-shadow:10px 10px 10px -5px #cfcfcf;position:relative;padding:30px;margin:0 auto;background:#fff;}
	main #graph{width:68%;}
	#chartContainer svg{width:100%;}

	/* RESPONSIVE DESIGN */
	@media only screen and (max-width: 1090px){
		main .contents{display:block;}
		main section{width:100%;margin:0 auto 20px;}
		main #graph{width:100%;}
		main p{width:100%;}
	}

	@media only screen and (max-width: 1000px){
		main{padding:50px 0 0 25px;}

		main .contents{display:flex;}
		main section{width:30%;}
		main #graph{width:68%;}
	}

	@media only screen and (max-width: 800px){
		main .contents{display:block;}
		main section{width:100%;}
		main #graph{width:100%;}

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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; Dashboard
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'dashboard';
				$currentSubMenu = 'sales';
				include("includes/sidebar.php");

				$num_confirm_bookings = $num_pending_bookings = $num_prospect_bookings = $adv_active = $vouch_applied  = $best_seller  = 0;
				$confirm_php = $pending_php = $prospect_php = $payDB =
				$revenue_php = $payout_php = $voucher_result = $vouch_active = $high_count= 0;
				$current_date = date('Y-m-d');

				$adv_ids = DB::query("SELECT adv_id FROM adventure WHERE orga_id=?", array($_SESSION['organizer']), "READ");

				foreach($adv_ids as $id) {

					$payoutDB = DB::query("SELECT sum(req_amount) FROM request WHERE req_rcvd = 1 AND req_user ='organizer' AND adv_id=? ", array($id[0]), "READ");
					$payout = $payoutDB[0];
					$payout_php = $payout_php + (int)$payout[0];

					$revenueDB = DB::query("SELECT sum(booking.book_totalcosts),booking.adv_id, adventure.adv_id FROM booking INNER JOIN adventure ON booking.adv_id = adventure.adv_id WHERE book_status ='paid' AND adv_status != 'canceled' AND adventure.adv_id=?", array($id[0]), "READ");
					$revenue = $revenueDB[0];
					$revenue_php = $revenue_php + (int)$revenue[0];

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


					$count_db = DB::query("SELECT count(adv_id) FROM booking WHERE book_status='paid' AND adv_id =?", array($id[0]), "READ");
					$count = $count_db[0];
						if($count[0] > $high_count) {
							$high_count = $count[0];
							$best_seller_db = DB::query("SELECT adv_name FROM adventure WHERE adv_id = ?", array($id[0]), "READ");
							$best_seller = $best_seller_db[0];
						}


					//file_put_contents('debug.log', date('h:i:sa').' => ' .$prospect_php. "\n" . "\n", FILE_APPEND);
				}


					$payDB = DB::query("SELECT * FROM booking INNER JOIN adventure ON booking.adv_id = adventure.adv_id WHERE adventure.orga_id=?", array($_SESSION['organizer']), "READ");

				    $adventure_result = DB::query("SELECT * FROM adventure WHERE orga_id=?", array($_SESSION['organizer']), "READ");

				    if(!empty($adventure_result)) {

						$adv_active_db = DB::query("SELECT count(adv_id) FROM adventure WHERE (adv_status != 'done' AND adv_status !='canceled') AND orga_id=?", array($_SESSION['organizer']), "READ");
						$adv_active = $adv_active_db[0];

						$adv_inactive_db = DB::query("SELECT count(adv_id) FROM adventure WHERE adv_date <= '$current_date' and orga_id=?", array($_SESSION['organizer']), "READ");
					    $adv_inactive = $adv_inactive_db[0];

						$total_num_adv = (int)$adv_active[0]+(int)$adv_inactive[0];
				}

					$voucher_result = DB::query("SELECT * FROM voucher WHERE orga_id=?", array($_SESSION['organizer']), "READ");

					if(!empty($voucher_result)) {

						$vouch_active_db = DB::query("SELECT count(adv_id) FROM voucher WHERE vouch_enddate >= '$current_date' and orga_id=?", array($_SESSION['organizer']), "READ");
						$vouch_active = $vouch_active_db[0];

						$vouch_applied_db = DB::query("SELECT sum(vouch_user) FROM voucher WHERE orga_id=?", array($_SESSION['organizer']), "READ");
						$vouch_applied = $vouch_applied_db[0];
				}


			?>
			<!-- End of Sub Navigation -->

				<main >
				<h2>Organizer Dashboard</h2>
				<div class="contents">
					<section >
						<i class='fas fa-money-check-alt' style='color:#5da5da;'></i>
							<?php
						//======================== PAYOUTS ============================//
								if($payout_php != 0)
									echo '<b>₱'.number_format($payout_php, 2, ".", ",");
								else
									echo 'N/A';
								echo "<a href='request-payout.php'> Payouts</a><br>
								<i class='fas fa-money-check' style='color:#60bd68;'></i> ";

						//======================== REVENUE ============================//

								if($revenue_php != 0)
									echo '₱'.number_format($revenue_php, 2, ".", ",");
								else
									echo 'N/A';
								echo "</b><a href='reports_booking.php'> Revenue</a><hr>
								<i class='fas fa-cart-plus' style='color:#5da5da;'></i> ";

						//======================== PROSPECT ============================//
								if($num_prospect_bookings != 0)
									echo $num_prospect_bookings. '(₱'.number_format($prospect_php, 2, ".", ",").')';
								else
									echo 'N/A ';
                                echo "<b>Prospects</b><br><b style='font-size: 12px'> Prospect → Confirm Ratio <u>";

								if($num_prospect_bookings != 0)
									echo round((($num_confirm_bookings/($num_prospect_bookings+$num_confirm_bookings))*100)).'% </b><br>';
								else
									echo 'N/A </b><br>';

						//======================== PENDINGS =======================//

								echo "</u><i class='fas fa-cart-arrow-down' style='color:#faa43a;'></i> ";
								if($num_pending_bookings != 0)
									echo $num_pending_bookings. " (₱".number_format($pending_php, 2, ".", ",").")";
								else
									echo 'N/A';
                                echo " <b>Pendings</b><br><b style='font-size: 12px'> Prospect → Pending Ratio <u>";

									if($num_pending_bookings != 0)
									echo round((($num_pending_bookings/($num_prospect_bookings+$num_pending_bookings))*100)).'% </b><br>';
								else
									echo 'N/A </b><br>';

						//======================== CONFIRMS =======================//

								echo "</u><i class='fas fa-briefcase' style='color:#60bd68;'></i> ";
								if($num_confirm_bookings != 0)
									echo $num_confirm_bookings. " (₱".number_format($confirm_php, 2, ".", ",").")";
								else
									echo 'N/A';

								echo " <b>Confirms</b><br><b style='font-size: 12px'> Pending → Confirm Ratio <u>";
								if($num_confirm_bookings != 0)
									echo round((($num_confirm_bookings/($num_pending_bookings+$num_confirm_bookings))*100)).'% </b><hr>';
								else
									echo 'N/A </b><hr>';

						//======================== ADVENTURES =======================//
								echo "</u><i class='fas fa-tree' style='color:#60bd68;'></i> ";
								if($adv_active != 0)
									echo $adv_active[0];
								else
									echo 'N/A ';
                                    echo "<a href='adventures_posted.php'> Active Adventures
                                    </a><br><b style='font-size: 12px'> Avg Pending <u>";

								if(!empty($num_pending_bookings))
									echo number_format(($num_pending_bookings/$total_num_adv),2,'.','');
								else
									echo 'N/A';
								    echo "</u> | Avg Confirm <u>";

								if(!empty($num_confirm_bookings))
									echo number_format(($num_confirm_bookings/$total_num_adv),2,'.',',')."<br>";
								else
									echo 'N/A <br>';
								    echo "</u></b><i class='fas fa-check-circle' style='color:#F15854;'></i><u><b> ";
								if($best_seller  != 0)
									echo $best_seller[0];
								else
									echo 'N/A';
								    echo "</u></b><br> Best Seller<hr>";

					    //======================== VOUCHERS =======================//
								echo "</u></b> <i class='fas fa-receipt' style='color:#60bd68;'></i> ";
									if(!empty($voucher_result))
									echo $vouch_active[0] ;
								else
									echo 'N/A';

								echo "<a href='voucher.php'> Vouchers<br></a><i class='fas fa-receipt' style='color:#F15854;'></i> ";
								if(!empty($voucher_result))
									echo $vouch_applied[0]." <b>Applied Voucher</b>";
								else
									echo "N/A<b> Applied Voucher</b>";

						//==========================================================//
							?>


					</section>

				        <!-- ======================== GRAPH ======================= -->

					<section id='graph'>
					<h3>Daily Booking Chart</h3>

					 <?php

						$payChart= '';

                   if(!empty($payDB)) {

						foreach($payDB as $row ){


							if($row['book_status'] =='paid'){

							$payChart .=
								" {

								book_datetime:'".$row["book_datetime"]."',

								book_totalcosts:".$row["book_totalcosts"].",

								book_guests:".$row["book_guests"]."},";



							}

     						 if($row['book_status'] =='waiting for payment'){

							$payChart .=
								"{

								book_datetime:'".$row["book_datetime"]."',

								book_totalcosts2:".$row["book_totalcosts"].",

								book_guests:".$row["book_guests"]."}, ";

							}

							if($row['book_status'] =='pending'){

							$payChart .=
								"{

								book_datetime:'".$row["book_datetime"]."',

								book_totalcosts3:".$row["book_totalcosts"].",

								book_guests:".$row["book_guests"]."}, ";

							}

						}
					}

					else echo "<br><h3> N/A </h3>";

					?>
							<br>
							<script>

								Morris.Line({

									element :'chartContainer',
									data:[<?php echo $payChart; ?>],
									//barColors:['#60bd68','black','#faa43a','#5da5da'],
									lineColors:['#60bd68','black','#faa43a','#5da5da'],
									xkey:'book_datetime',
									ykeys:['book_totalcosts','book_guests','book_totalcosts2','book_totalcosts3'],
									labels:['Booking Cost','Guest', 'Pending Cost','Prospect Booking'],
									hideHover:'auto',

								}

								);

							</script>

					<i class='fas fa-chart-line' style='color:#5da5da'><b style="color:black"> Prospect Bookings</b></i> |
					<i class='fas fa-chart-line' style='color:#faa43a'><b style="color:black"> Pending Bookings</b></i> |
					 <i class='fas fa-chart-line' style='color:#60bd68'><b style="color:black"> Confirmed Bookings</b></i>


					  <div id="chartContainer" ></div>
					    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
						<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
						<script src="morris.js/morris.min.js"></script>
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
