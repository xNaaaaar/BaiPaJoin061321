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

	main #graph{width:97.8%; height: 450px ; border:1px solid #cfcfcf;box-shadow:10px 10px 10px -5px #cfcfcf;min-height:200px;position:relative;padding:30px 30px 110px;margin:0 auto 30px;}

	main section #chartContainer{height:370px; width: 100%; min-height:200px;position:relative;padding:30px 30px 110px;margin:0 auto 30px;}
	
	main section p{margin:20px 0 0; font: 35px/100% Montserrat,sans-serif; color:gray;line-height:50px;position:absolute; bottom:30px;left:0;right:0;}

	main section h3{font:600 25px/100% Montserrat,sans-serif;}

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:30px;text-align:left;}
		main h3{font-weight:500;font-size:20px;margin:0 0 10px;}
		main table{width:100%;text-align:center;font-size:16px;}
		main table thead{background-color:rgb(191,18,122);color:#fff;}
		main table thead tr:hover{background:rgb(191,18,122);}
		main table thead th{padding:15px 10px;font-weight:bold;line-height:20px;}
		main table tr{border-bottom:1px solid gray;}
		main table tr:hover{background:#fafafa;}
		main table td{padding:15px 10px;line-height:20px;}
		main table td a:hover{text-decoration:none;color:#000;}

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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; <a href="dashboard.php">Dashboard</a> &#187;Payout
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'dashboard';
				$currentSubMenu = 'payout';
				include("includes/sidebar.php");

				$refunded_php = $num_confirm_bookings = $num_pending_bookings = $num_prospect_bookings = 0;
				$confirm_php = $pending_php = $prospect_php= $dateMonth  = $payDB = 0;

				$adv_ids = DB::query("SELECT adv_id FROM adventure WHERE orga_id=?", array($_SESSION['organizer']), "READ");

				foreach($adv_ids as $id) {


					## CARD NUMBER 4
					// KIRK REFUND UPDATE
					$refunded_advDB = DB::query("SELECT sum(book_totalcosts) FROM booking WHERE book_status='refunded' and adv_id=?", array($id[0]), "READ");
					$refunded_val = $refunded_advDB[0];
					$refunded_php = $refunded_php + (int)$refunded_val[0];

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

					$payDB = DB::query("SELECT * FROM booking ", array($id[0]), "READ");

				}

			?>
			<!-- End of Sub Navigation -->

			<main>
				<h2>Payouts</h2>
				<div class="contents">

					<section>
						<h3>Total Revenue</h3>
						<p >
						<i class='fas fa-wallet' style='color:#faa43a;'></i><br>
							<?php 
								echo "₱".number_format($confirm_php, 2, ".", ",");
							?>
						</p>
					</section>

					<section>
						<h3>Refunded Bookings</h3>
						<p>
						<i class="fas fa-money-check" style='color:#60bd68;'></i><br>	
							<?php
								echo "₱-".number_format($refunded_php, 2, ".", ",");
							?>

						</p>
					</section >
					<!--  -->
					<section >
						<h3>Total Payouts</h3>
						<p>
						<i class='fas fa-money-check-alt' style='color:#5da5da'></i><br>	
							<?php
								echo "₱".number_format($prospect_php, 2, ".", ",");
							?>

						</p>

					</section>

				<!-- SALE GRAPH -->
					
					<section id='graph'>
					<h3>Daily Payouts Graph</h3><br>

					 <?php

				   $payChart= ''; 	
                      
                   if($payDB != 0) {

						foreach($payDB as $row ){
							

							if($row['book_status'] =='paid'){

							$payChart .=
								" {

								book_datetime:'".$row["book_datetime"]."', 

								book_totalcosts:".$row["book_totalcosts"]."},";

					
							 
							} 

     						 if($row['book_status'] =='refunded'){ 

							$payChart .=
								"{ 

								book_datetime:'".$row["book_datetime"]."', 

								book_totalcosts2:".$row["book_totalcosts"]."}, ";

							}

							if($row['book_status'] =='pending'){ 

							$payChart .=
								"{ 

								book_datetime:'".$row["book_datetime"]."', 

								book_totalcosts3:".$row["book_totalcosts"]."}, ";

							}

						}
					}	else echo "<h3> N/A </h3>";

					?>
							<br>
							<script>

								Morris.Line({

									element :'chartContainer',
									data:[<?php echo $payChart; ?>],
									barColors:['#faa43a','#60bd68','#5da5da'],
									lineColors:['#faa43a','#60bd68','#5da5da'],
									xkey:'book_datetime',
									ykeys:['book_totalcosts','book_totalcosts2','book_totalcosts3'],
									labels:['Total Revenue','Cancelled Bookings','Total Payouts'],
									hideHover:'auto',
									//ymax: 'auto',
									//ymin: 'auto',
									
									//stacked: true
								}

								);

							</script>

					<i class='fas fa-chart-line' style='color:#faa43a'><b style="color:black"> Total Revenue </b></i> |     
					 <i class='fas fa-chart-line' style='color:#60bd68'><b style="color:black"> Cancelled Bookings</b></i> | 
					 <i class='fas fa-chart-line' style='color:#5da5da'><b style="color:black"> Total Payouts</b></i>			

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
