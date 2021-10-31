<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

  // REDIRECT IF ADMIN NOT LOGGED IN
  if(!isset($_SESSION['admin'])) header("Location: login.php");
?>
<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

<style media="screen">
html, body{height:100%;}
.wrapper{max-width:100%;}
/* Main Area */
.main_con{display:flex;justify-content:space-between;min-height:100vh;}

.sidebar{padding:30px;background:#7fdcd3;height:auto;}
.sidebar:before{display:none;}
.sidebar figure{width:150px;margin:0 auto!important;}
.sidebar h2{text-align:center;}
.sidebar h2 span{display:block;font-size:15px;}
.sidebar ul{margin:35px 0 0 25px;height:auto;}
.sidebar ul ul{margin:0 0 0 10px;}

main{flex:4;float:none;height:100%;background:none;margin:0;padding:50px 50px 0;border-radius:0;text-align:center;}
main h1{text-align:right;font-size:20px;}
main h2{margin:15px 0;}

main .contents{display:flex;justify-content:space-between;margin:30px 0 0;width:100%;flex-wrap:wrap;}

main section h3{font-size:25px;margin:0 0 100px;}
main section{width:30%;min-height:500px;border:1px solid #cfcfcf;box-shadow:10px 10px 10px -5px #cfcfcf;position:relative;padding:30px;margin:0 auto;}
main #graph{width:68%;}
#chartContainer svg{width:100%;}

/* Responsive Design */
@media only screen and (max-width: 1800px) {
	main{height:100%;}
}

@media only screen and (max-width: 1400px) {
	.main_con{padding:0;}
	main .contents{display:block;}
	main section{width:100%;margin:0 auto 20px;}
	main #graph{width:100%;}
	main p{width:100%;}

	.sidebar ul{margin:35px 0 0;padding-left:10px;}
}

@media only screen and (max-width: 1200px){
	main #chartContainer{width:100%;clear:both;overflow-x:auto;}
	main #chartContainer svg{min-width: rem-calc(640);}
}

@media only screen and (max-width: 1000px){
	main{padding:0 0 0 30px;}
}

@media only screen and (max-width: 800px){
	main h1{font-size:20px !important;}
}

</style>

</head>
<body>
  <div id="main_area">
    <div class="wrapper">
      <?php
			$current_admin = DB::query("SELECT * FROM admin WHERE admin_id=?", array($_SESSION['admin']), "READ");

      if(count($current_admin)>0){
        $current_admin = $current_admin[0];
      ?>
      <div class="main_con">
        <!-- SIDEBAR -->
				<?php
					$currentSidebarPage = 'dashboard';
					$currentSubMenu = 'sales';
					include("includes/sidebar-admin.php");

					$num_confirm_bookings = $num_pending_bookings = $num_prospect_bookings =
					$adv_inactive = $org_active = $joiner_active = $highest_paid = 0;
					$confirm_php = $pending_php = $prospect_php = $payout_php = $advBest =
					$revenue_php = $refund_php = $adv_active =$biggest_spender = 0;
					$current_date = date('Y-m-d');

					$revenueDB = DB::query("SELECT sum(booking.book_totalcosts),booking.adv_id, adventure.adv_id FROM booking INNER JOIN adventure ON booking.adv_id = adventure.adv_id WHERE book_status ='paid' AND adv_status != 'canceled' ", array(), "READ");
					$revenue = $revenueDB[0];
					$revenue_php = $revenue_php + (int)$revenue[0];

					$payoutDB = DB::query("SELECT sum(req_amount) FROM request WHERE req_rcvd = 1 AND req_user ='organizer' ", array(), "READ");
					$payout = $payoutDB[0];
					$payout_php = $payout_php + (int)$payout[0];

					$refundDB = DB::query("SELECT sum(req_amount) FROM request WHERE req_rcvd = 1 AND req_user ='joiner' ", array(), "READ");
					$refund = $refundDB[0];
					$refund_php = $refund_php + (int)$refund[0];

					$prospect_db = DB::query("SELECT count(adv_id) FROM booking WHERE book_status='pending'", array(), "READ");
					$prospect = $prospect_db[0];
					$num_prospect_bookings = $num_prospect_bookings + (int)$prospect[0];

					$pending_db = DB::query("SELECT count(adv_id) FROM booking WHERE book_status='waiting for payment'", array(), "READ");
					$pending = $pending_db[0];
					$num_pending_bookings = $num_pending_bookings + (int)$pending[0];

					$paid_db = DB::query("SELECT count(adv_id) FROM booking WHERE book_status='paid'", array(), "READ");
					$paid = $paid_db[0];
					$num_confirm_bookings = $num_confirm_bookings + (int)$paid[0];

					$prospect_val_db = DB::query("SELECT sum(book_totalcosts) FROM booking WHERE book_status='pending'", array(), "READ");
					$prospect_val = $prospect_val_db[0];
					$prospect_php = $prospect_php + (int)$prospect_val[0];

					$pending_val_db = DB::query("SELECT sum(book_totalcosts) FROM booking WHERE book_status='waiting for payment'", array(), "READ");
					$pending_val = $pending_val_db[0];
					$pending_php = $pending_php + (int)$pending_val[0];

					$confirm_val_db = DB::query("SELECT sum(book_totalcosts) FROM booking WHERE book_status='paid'", array(), "READ");
					$confirm_val = $confirm_val_db[0];
					$confirm_php = $confirm_php + (int)$confirm_val[0];

					//file_put_contents('debug.log', date('h:i:sa').' => ' .$prospect_php. "\n" . "\n", FILE_APPEND);

					$orgaDB = DB::query("SELECT count(orga_id) FROM organizer WHERE orga_status = 1 ", array(), "READ");
					$org_active = $orgaDB[0];

					$joinerDB = DB::query("SELECT count(joiner_id) FROM joiner ", array(), "READ");
					$joiner_active = $joinerDB[0];

					$adv_active_db = DB::query("SELECT count(adv_id) FROM adventure WHERE adv_status != 'done' AND adv_status !='canceled'", array(), "READ");
					$adv_active = $adv_active_db[0];

					$adv_inactive_db = DB::query("SELECT count(adv_id) FROM adventure WHERE adv_status = 'done' OR adv_status ='canceled'", array(), "READ");
					$adv_inactive = $adv_inactive_db[0];

					$voucher_result = DB::query("SELECT * FROM voucher", array(), "READ");

					if(!empty($voucher_result)) {

						$vouch_active_db = DB::query("SELECT count(adv_id) FROM voucher WHERE vouch_enddate >= '$current_date' ", array(), "READ");
						$vouch_active = $vouch_active_db[0];

						$vouch_applied_db = DB::query("SELECT sum(vouch_user) FROM voucher", array(), "READ");
						$vouch_applied = $vouch_applied_db[0];
				}

				?>

        <!-- MAIN -->
        <main>
          <h1><i class="fas fa-user-circle"></i> Admin: <?php echo $current_admin['admin_name']; ?></h1>
          <h2>Admin Dashboard</h2>
          <div class="contents">
          <section>
						<p><i class='fas fa-money-bill' style='color:#60bd68;'></i>
							<?php

						//======================== REVENUE ============================//

								if($revenue_php != 0)
									echo '<b>₱'.number_format($revenue_php, 2, ".", ",");
								else
									echo 'N/A';
								echo "<b> Total Revenue</b><br>
								 <i class='fas fa-money-check-alt' style='color:#5da5da;'></i> ";

						//======================== PAYOUTS ============================//
								if($payout_php != 0)
									echo '₱'.number_format($payout_php, 2, ".", ",");
								else
									echo 'N/A';

								echo "<a href='admin-request-payout.php'> Organizers Total Payout</a><br>
								<i class='fas fa-money-check' style='color:#faa43a;'></i> ";

						//======================== REFUNDS ============================//

						if($payout_php != 0)
									echo '₱'.number_format($refund_php, 2, ".", ",");
								else
									echo 'N/A';

								echo "<a href='admin-request-refund.php'> Joiners Total Refund</a></b><hr>
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

						//======================== USERS =======================//
								echo "</u><i class='fas fa-user-tie' style='color:#5da5da;'></i> ";
								if($org_active != 0)
									echo $org_active[0];
								else
									echo 'N/A ';
                  echo "<a href='admin-organizer.php'> Total Active Organizers</a><br>
                  <i class='fas fa-user-ninja' style='color:#5da5da;'></i> ";

								if(!empty($joiner_active))
									echo $joiner_active[0];
								else
									echo 'N/A ';
								  echo "<a href='admin-joiner.php'> Total Joiners</a><hr>";

						//======================== ADVENUTURES =======================//
								echo "</u><i class='fas fa-tree' style='color:#60bd68;'></i> ";
								if($adv_active != 0)
									echo $adv_active[0];
								else
									echo 'N/A ';
                  echo "<b> Active Adventures </b><br><i class='fas fa-tree' style='color:#F15854;'></i> ";

								if(!empty($adv_inactive))
									echo $adv_inactive[0];
								else
									echo 'N/A ';
								  echo "<b> Inactive Adventures</b><hr>";

					    //======================== VOUCHERS =======================//
								echo "</u></b> <i class='fas fa-receipt' style='color:#60bd68;'></i> ";
									if(!empty($voucher_result))
									echo $vouch_active[0];
								else
									echo '0';

								echo "<a href='admin-voucher.php'> Active Vouchers<br></a><i class='fas fa-receipt' style='color:#F15854;'></i> ";
								if(!empty($voucher_result))
									echo $vouch_applied[0];
								else
									echo "0";
								  echo "<b> Applied Voucher</b>";

						  //==========================================================//
							?>

						</p>
					</section>

				        <!-- ======================== GRAPH ======================= -->

					<section id='graph'>
					<h3 style="color:black"><i class='fas fa-chart-line' style='color:#60bd68'></i> Daily Sales Chart</h3>

					<?php

					$payChart= '';
          $revenueDB = DB::query("SELECT booking.book_totalcosts, booking.book_datetime, booking.book_guests, booking.adv_id, adventure.adv_id FROM booking INNER JOIN adventure ON booking.adv_id = adventure.adv_id WHERE book_status ='paid' AND adv_status != 'canceled'", array(), "READ");

           if($revenueDB != 0) {

					   foreach($revenueDB as $row ){

								$payChart .=
									" {

									book_datetime:'".$row["book_datetime"]."',

									book_totalcosts:".$row["book_totalcosts"].",

									book_guests:".$row["book_guests"]."},";

						}
					}	else echo "<h3> N/A </h3>";

					?>

					 <div id="chartContainer" ></div>

					</section>
          </div>
        </main>
      </div>
      <div class="clearfix"></div>
      <?php
      }
      ?>
    </div>
  </div>

            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
						<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
						<script src="morris.js/morris.min.js"></script>

  							<script>

								Morris.Area({

									element :'chartContainer',
									data:[<?php echo $payChart; ?>],
									//barColors:['#60bd68'],
									lineColors:['#60bd68','#F15854'],
									xkey:'book_datetime',
									ykeys:['book_totalcosts','book_guests'],
									labels:['Booking Cost','Total Guets'],
									hideHover:'auto',

								}

								);

							</script>



</body>

</html>
