<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
	ob_start();
	##
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
		.sidebar ul ul{height:auto;}

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
		main h1{font:600 45px/100% Montserrat,sans-serif;color:#313131;text-align:center;margin:-70px 0 0;}
		main h2{font:600 30px/100% Montserrat,sans-serif;margin:15px 0;text-align:left;}
		main figure{width:60%;margin:-40px auto 0;}
		main section{margin:50px 0 0;position:relative;}
		main section:before{content:"";width:100%;height:3px;background:#7fdcd3;position:absolute;top:-25px;left:0;}
		main ol{text-align:left;}
		main table{width:100%;margin:0 auto 0;text-align:left;}
		main table tr td{width:50%;}

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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; <a href="reports_booking.php">Reports </a> &#187; Booking
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'reports';
				$currentSubMenu = 'reports';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->
			<?php
			## JOINER BOOKING WANT TO VIEW
			$booked = DB::query("SELECT * FROM booking WHERE book_id=?", array($_GET['book_id']), "READ");
			$booked = $booked[0];
			## THE ADV JOINER BOOKED
			$adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($booked['adv_id']), "READ");
			$adv = $adv[0];

			## PER GUESTS COST
			$per_guest = ($adv['adv_totalcostprice'] / $adv['adv_maxguests']) * $booked['book_guests'];
			$fee = $per_guest * 0.035 + 15;
			$total = $per_guest + $fee;

			## NUMBER FORMAT
			$per_guest = number_format($per_guest,2,".",",");
			$fee = number_format($fee,2,".",",");
			$total = number_format($total,2,".",",");

			## JOINER DETAILS
			$joiner = DB::query("SELECT * FROM joiner WHERE joiner_id=?", array($_SESSION['joiner']), "READ");
			$joiner = $joiner[0];
			?>
			<main>
				<figure>
					<img src="images/receipt.jpg" alt="">
				</figure>
				<h1>Transaction Receipt</h1>
				<section>
					<h2>Booking Details</h2>
					<table>
						<tr>
							<td>Booking ID : </td>
							<td><b><?php echo $booked['book_id']; ?></b></td>
						</tr>
						<tr>
							<td>Booking Date : </td>
							<td><b><?php echo date("M. j, Y", strtotime($adv['adv_date'])); ?></b></td>
						</tr>
						<tr>
							<td>Status : </td>
							<td><b><?php echo strtoupper($booked['book_status']); ?></b></td>
						</tr>
					</table>
				</section>
				<section>
					<h2>Guest Details</h2>
					<ol>
						<?php
						$guests = DB::query("SELECT * FROM guest WHERE book_id=?", array($booked['book_id']), "READ");
						## BOOKED FOR AS A GUEST
						if(isset($_SESSION['bookOption']) && $_SESSION['bookOption'] == "guest"){
						?>
							<li><?php echo $joiner['joiner_fname']." ".$joiner['joiner_lname']; ?> (you)</li>
							<?php
							## IF MANY GUESTS EXISTS
							$counter = 2;
							if(count($guests)>0){
								foreach ($guests as $result) {
									echo "<li start='".$counter."'>".$result['guest_name']."</li>";
									$counter++;
								}
							}
						## BOOKED FOR SOMEONE
						} else {
							if(count($guests)>0){
								foreach ($guests as $result) {
									echo "<li>".$result['guest_name']."</li>";
								}
							}
						}
						?>

					</ol>
				</section>
				<section>
					<h2>Payment Details</h2>
					<table>
						<tr>
							<td>Paid Thru</td>
							<td><b>Card</b></td>
						</tr>
						<tr>
							<td>Adventure Name</td>
							<td><b><?php echo $adv['adv_name']; ?></b></td>
						</tr>
						<tr>
							<td>Adventure Cost</td>
							<td><b><?php echo "₱".$per_guest; ?></b></td>
						</tr>
						<tr>
							<td>Fee</td>
							<td><b><?php echo "₱".$fee; ?></b></td>
						</tr>
						<tr>
							<td>Total Amount Paid</td>
							<td><b><?php echo "₱".$total; ?></b></td>
						</tr>
					</table>
				</section>
				<section>
					<h2>Bank Details</h2>
					<table>
						<tr>
							<td>Account Name</td>
							<td>Card</td>
						</tr>
						<tr>
							<td>Account Number</td>
							<td>Card</td>
						</tr>
						<tr>
							<td>Expiry Date</td>
							<td>Card</td>
						</tr>
					</table>
				</section>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ob_end_flush();?>
<!-- End Footer -->
