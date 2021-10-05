<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
	##
	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");
	##
	if(isset($_GET['success'])){
		echo "<script>alert('Successfully rated!')</script>";
	}
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
		main h3{font-weight:500;font-size:20px;color:red;margin:20px 0 0;text-align:center;}
		main table{width:100%;text-align:center;font-size:16px;}
		main table thead{background:#7fdcd3;color:#fff;}
		main table thead tr:hover{background:#7fdcd3;}
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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; <a href="reports_booking.php">Reports </a> &#187; Ratings
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'reports';
				$currentSubMenu = 'ratings';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->
			<main>
				<h2>Ratings</h2>

				<?php ##
				// DISPLAY RATING REPORTS FOR ORGANIZER
				if(isset($_SESSION['organizer'])){


				// DISPLAY RATING REPORTS FOR JOINER
				} else {
					echo "
					<table>
						<thead>
							<tr>
								<th>Book ID</th>
								<th>Adventure Name</th>
								<th>Adventure Date</th>
								<th>Price</th>
								<th>Ratings</th>
							</tr>
						</thead>
					";

					$to_rate = DB::query("SELECT * FROM adventure a INNER JOIN booking b ON a.adv_id=b.adv_id WHERE joiner_id=? AND book_status=? AND adv_date<? ORDER BY book_datetime DESC", array($_SESSION['joiner'], "paid", date("Y-m-d")), "READ");

					if(count($to_rate)>0){
						foreach ($to_rate as $result) {
							echo "
							<tr>
								<td>".$result['book_id']."</td>
								<td>".$result['adv_name']."</td>
								<td>".date("M j, Y", strtotime($result['adv_date']))."</td>
								<td>".number_format($result['book_totalcosts'],2,".",",")."</td>";

							## CHECK IF ALREADY RATED
							$rated = DB::query("SELECT * FROM rating WHERE joiner_id=? AND book_id=?", array($_SESSION['joiner'], $result['book_id']), "READ");
							## RATED
							if(count($rated)>0){
								$rated = $rated[0];
								echo "<td>".$rated['rating_stars']." <i class='fas fa-star'></i></td>";
							# NOT RATED
							} else {
								echo "<td><a href='reports_rating-rate.php?book_id=".$result['book_id']."' onclick='return confirm(\"Are you sure you want to rate now?\");'>rate now</a></td>";
							}
							echo "</tr>";
						}
						echo "</table>";

					// NO RECORDS FOUND
					} else {
						echo "</table>";
						echo "<h3>No adventures to rate found!</h3>";
					}
				}
				?>

			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
