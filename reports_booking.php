<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
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
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:30px;text-align:left;}
		main h3{font-weight:500;font-size:20px;color:red;margin:20px 0 0;}
		main table{width:100%;text-align:center;font-size:16px;}
		main table thead{background:#7fdcd3;color:#fff;}
		main table thead tr:hover{background:#7fdcd3;}
		main table thead th{padding:15px 10px;font-weight:bold;line-height:20px;}
		main table tr{border-bottom:1px solid gray;}
		main table tr:hover{background:#fafafa;}
		main table td{padding:15px 10px;line-height:20px;}

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
			<main>
				<form method="post" >
					<h2>Bookings</h2>
					<!-- <h3>Note: Pay anytime by clicking the <i class='fas fa-hand-holding-usd'></i> icon. Request cancel by clicking <i class='fas fa-ban'></i> icon. View booking details by clicking <i class='far fa-eye'></i> icon.</h3> -->

					<?php ##
					// DISPLAY BOOKING REPORTS FOR ORGANIZER
					if(isset($_SESSION['organizer'])){
						echo "
						<table>
							<thead>
								<tr>
									<th>Adventure ID</th>
									<th>Book ID</th>
									<th>Book Guests</th>
									<th>Book Date & Time</th>
									<th>Book Price</th>
									<th>Book Status</th>
									<th></th>
									<th></th>
								</tr>
							</thead>
						";

						$orga_bookings = DB::query("SELECT * FROM booking b INNER JOIN adventure a ON a.adv_id = b.adv_id WHERE orga_id=?", array($_SESSION['organizer']), "READ");

						if(count($orga_bookings)>0){
							foreach ($orga_bookings as $result) {
								echo "
								<tr>
									<td>".$result['adv_id']."</td>
									<td>".$result['book_id']."</td>
									<td>".$result['book_guests']."</td>
									<td>".date("M. j, Y g:i a", strtotime($result['book_datetime']))."</td>
									<td>₱".number_format($result['book_totalcosts'], 2, ".", ",")."</td>
									<td>".$result['book_status']."</td>
									<td></td>
									<td></td>
								</tr>
								";
							}
							echo "</table>";

						// NO RECORDS FOUND
						} else {
							echo "</table>";
							echo "<h3>No bookings found!</h3>";
						}

					// DISPLAY BOOKING REPORTS FOR JOINER
					} else {
						echo "
						<table>
							<thead>
								<tr>
									<th>Book ID</th>
									<th>Book Guests</th>
									<th>Book Date & Time</th>
									<th>Book Price</th>
									<th>Book Status</th>
									<th></th>
									<th></th>
								</tr>
							</thead>
						";

						$joiner_bookings = DB::query("SELECT * FROM booking WHERE joiner_id=? ORDER BY book_datetime DESC", array($_SESSION['joiner']), "READ");

						if(count($joiner_bookings)>0){
							foreach ($joiner_bookings as $result) {
								## CHECK IF BOOKING IS IN REQUEST
								$req = DB::query("SELECT * FROM request WHERE book_id=?", array($result['book_id']), "READ");
								if(count($req) == 0){
									echo "
									<tr>
										<td>".$result['book_id']."</td>
										<td>".$result['book_guests']."</td>
										<td>".date("M. j, Y g:i a", strtotime($result['book_datetime']))."</td>
										<td>₱".number_format($result['book_totalcosts'], 2, ".", ",")."</td>
										<td>".$result['book_status']."</td>
									";

									if($result['book_status'] == "paid"){
										echo "<td><a href='request_cancel.php?book_id=".$result['book_id']."' onclick='return confirm(\"Are you sure you want to request cancelation for this adventure? BaiPaJoin deducts 30% cancelation fee for the total price you paid (excludes the fee)\");'>cancel</a></td>";
										echo "<td><a href='reports_booking-view.php?book_id=".$result['book_id']."' onclick='return confirm(\"View receipt?\");'>view</a></td>";
									} else {
										echo "<td><a href='payment-card.php?book_id=".$result['book_id']."&id=".$result['adv_id']."' onclick='return confirm(\"Ready to pay now?\");'>pay</a></td>";
										echo "<td></td>";
									}
									echo "</tr>";
								}
							}
							echo "</table>";

						// NO RECORDS FOUND
						} else {
							echo "</table>";
							echo "<h3>No bookings found!</h3>";
						}
					}
					?>
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
