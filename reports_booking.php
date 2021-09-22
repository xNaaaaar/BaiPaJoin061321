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
		.main_con{display:flex;justify-content:space-between;}
		.sidebar ul ul{height:auto;}

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:30px;text-align:left;}
		main table{width:100%;text-align:left;}
		main table thead{background:#7fdcd3;color:#fff;}
		main table thead tr:hover{background:#7fdcd3;}
		main table thead th{padding:15px 10px;font-weight:bold;}
		main table tr{border-bottom:1px solid gray;}
		main table tr:hover{background:#fafafa;}
		main table td{padding:15px 10px;}

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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; Booking
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'reports';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->
			<main>
				<form method="post" >
					<h2>Bookings</h2>

					<table class="">
						<?php
						if(isset($_SESSION['organizer'])){
						?>
						<thead class="">
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

						<?php
							$orga_bookings = DB::query("SELECT * FROM booking b INNER JOIN adventure a ON a.adv_id = b.adv_id WHERE orga_id=?", array($_SESSION['organizer']), "READ");

							if(count($orga_bookings)>0){
								foreach ($orga_bookings as $result) {
									echo "
									<tr>
										<td>".$result['adv_id']."</td>
										<td>".$result['book_id']."</td>
										<td>".$result['book_guests']."</td>
										<td>".date("M. j, Y g:i a", strtotime($result['book_datetime']))."</td>
										<td>".$result['book_totalcosts']."</td>
										<td>".$result['book_status']."</td>
										<td></td>
										<td></td>
									</tr>
									";
								}
							}
						}
						?>
					</table>

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
