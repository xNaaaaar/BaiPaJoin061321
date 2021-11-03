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
		main h2{margin-bottom:30px;}
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
				<h2>My Ratings</h2>
				<div class="scroll-table">
				<?php ##
				// DISPLAY RATING REPORTS FOR ORGANIZER
				if(isset($_SESSION['organizer'])){
					echo "
					<table>
						<thead>
							<tr>
								<th>Book ID</th>
								<th>Adv Name</th>
								<th>Adv Kind</th>
								<th>Adv Type</th>
								<th>Rating Type</th>
								<th>Ratings</th>
							</tr>
						</thead>
					";

					$this_rate = DB::query("SELECT * FROM rating WHERE rating_id=?", array($_GET['rate_id']), "READ");
					$this_rate = $this_rate[0];
					##
					$done_adv = DB::query('SELECT * FROM adventure a JOIN booking b ON a.adv_id=b.adv_id JOIN rating r ON b.book_id=r.book_id JOIN joiner j ON r.joiner_id=j.joiner_id WHERE a.orga_id=? AND a.adv_status=? AND rating_type!=? AND rating_img=?', array($_SESSION['organizer'], "done", 4, $this_rate['rating_img']), 'READ');

					if(count($done_adv)>0){
						foreach ($done_adv as $result) {
							echo "
							<tr>
								<td>".$result['book_id']."</td>
								<td>".$result['adv_name']."</td>
								<td>".$result['adv_kind']."</td>
								<td>".$result['adv_type']."</td>
								<td>";
								$rate_type = "";
								if($result['rating_type'] == 1) $rate_type = "Engagement Rating";
								elseif($result['rating_type'] == 2) $rate_type = "Safety & Expertise Rating";
								elseif($result['rating_type'] == 3) $rate_type = "Expectation Rating";
								echo $rate_type."
								</td>
								<td>".$result['rating_stars']." <i class='fas fa-star'></i></td>
							</tr>
							";
						}
						echo "</table>";

					// NO RECORDS FOUND
					} else {
						echo "</table>";
						echo "<h3>No ratings found!</h3>";
					}

				// DISPLAY RATING REPORTS FOR JOINER
				} else
				?>
				</div>
				<a href='reports_rating.php' class='edit'>Back</a>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
