<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
	##
	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

	if(isset($_GET['cancel_success'])){
		echo "<script>alert('Cancellation request successfully sent to admin!')</script>";
	}

	if(isset($_GET['update_success'])){
		echo "<script>alert('Successfully updated cancelation reason!')</script>";
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
		.success{color:#5cb85c;}

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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; Request
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSubMenu = '';
				$currentSidebarPage = 'request';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->
			<main>
				<form method="post" >
					<h2>My Pending Requests</h2>
					<div class="scroll-table">
					<?php ##
					// DISPLAY REQUEST FOR ORGANIZER
					if(isset($_SESSION['organizer'])){
						echo "
						<table>
							<thead>
								<tr>
									<th>Adv ID</th>
									<th>Request Type</th>
									<th>Request Date</th>
									<th>Total Amount</th>
									<th>Request Reason</th>
									<th>Request Status</th>
									<th></th>
									<th></th>
								</tr>
							</thead>
						";

						$request = DB::query("SELECT * FROM request r INNER JOIN adventure a ON r.adv_id = a.adv_id WHERE orga_id=? AND req_status=?", array($_SESSION['organizer'], "pending"), "READ");

						if(count($request)>0){
							foreach ($request as $result) {
								echo "
								<tr>
									<td>".$result['adv_id']."</td>
									<td>".$result['req_type']."</td>
									<td>".date("M. j, Y", strtotime($result['req_dateprocess']))."</td>
									<td>???".number_format($result['req_amount'],2,".",",")."</td>
									<td>".$result['req_reason']."</td>
									<td><em class='success'>".$result['req_status']."</em></td>";

								if($result['req_status'] == "pending"){
									echo "<td><a href='request-edit.php?req_id=".$result['req_id']."' onclick='return confirm(\"Are you sure you want to edit reason?\");'>edit</a></td>";
									echo "<td></td>";

								## NO PENDING
								} else {
									echo "<td></td>";
									echo "<td></td>";
								}

								echo "</tr>";
							}
							echo "</table>";

						// NO RECORDS FOUND
						} else {
							echo "</table>";
							echo "<h3>No pending request found!</h3>";
						}

					// DISPLAY REQUEST FOR JOINER
					} else {
						echo "
						<table>
							<thead>
								<tr>
									<th>Book ID</th>
									<th>Request Type</th>
									<th>Request Date</th>
									<th>Amount Paid</th>
									<th>Request Reason</th>
									<th>Request Status</th>
									<th></th>
									<th></th>
								</tr>
							</thead>
						";

						$request = DB::query("SELECT * FROM request r INNER JOIN booking b ON r.book_id = b.book_id WHERE joiner_id=? AND (req_status=? || req_type=?)", array($_SESSION['joiner'], "pending", "canceled"), "READ");

						if(count($request)>0){
							foreach ($request as $result) {
								echo "
								<tr>
									<td>".$result['book_id']."</td>
									<td>".$result['req_type']."</td>
									<td>".date("M. j, Y", strtotime($result['req_dateprocess']))."</td>
									<td>???".number_format($result['req_amount'],2,".",",")."</td>
									<td>".$result['req_reason']."</td>
									<td><em class='success'>".$result['req_status']."</em></td>";

								if($result['req_status'] == "pending"){
									echo "<td><a href='request-edit.php?req_id=".$result['req_id']."' onclick='return confirm(\"Are you sure you want to edit reason?\");'>edit</a></td>";
									echo "<td></td>";

								## CANCELED BY ORGANIZER
								} elseif($result['req_type'] == "canceled") {
									echo "<td>refund</td>";
									echo "<td>reschedule</td>";

								## NO PENDING
								} else {
									echo "<td></td>";
									echo "<td></td>";
								}


								echo "</tr>";
							}
							echo "</table>";

						// NO RECORDS FOUND
						} else {
							echo "</table>";
							echo "<h3>No pending request found!</h3>";
						}
					}
					?>
					</div>
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
