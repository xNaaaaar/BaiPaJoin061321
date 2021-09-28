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
		.success{color:#5cb85c;}
		.error{color:red;}

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
		main table td button{padding:5px 10px;}

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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; <a href="request.php">Request</a> &#187; Payout
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'request';
				$currentSubMenu = "payout";
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->
			<main>
				<form method="post" >
					<h2>Payout</h2>

					<?php ##
					// DISPLAY PAYOUT REQUEST FOR ORGANIZER
					if(isset($_SESSION['organizer'])){


					// DISPLAY PAYOUT REQUEST FOR JOINER
					} else {
						echo "
						<table>
							<thead>
								<tr>
									<th>Book ID</th>
									<th>Date Processed</th>
									<th>Date Responded</th>
									<th>Amount to Received</th>
									<th></th>
								</tr>
							</thead>
						";

						$request = DB::query("SELECT * FROM request r INNER JOIN booking b ON r.book_id = b.book_id WHERE joiner_id=? AND req_type=?", array($_SESSION['joiner'], "payout"), "READ");

						if(count($request)>0){
							foreach ($request as $result) {
								echo "
								<input type='hidden' name='hidReqID' value='".$result['req_id']."'>
								<tr>
									<td>".$result['book_id']."</td>
									<td>".date("M. j, Y", strtotime($result['req_dateprocess']))."</td>
									<td>".date("M. j, Y", strtotime($result['req_dateresponded']))."</td>
									<td>₱".number_format($result['req_amount'],2,".",",")."</td>";

								if($result['req_rcvd'] == 0)
									echo "<td><button type='submit' name='btnRcvd' onclick='return confirm(\"Are you sure you received the refund money?\");'>Received</button></td>";
								else
									echo "<td class='success'><em>received</em></td>";

								echo "</tr>";
							}
							echo "</table>";

						// NO RECORDS FOUND
						} else {
							echo "</table>";
							echo "<h3>No payout request found!</h3>";
						}
					}

					## WHEN BUTTON RECEIVED IS CLICKED
					if(isset($_POST['btnRcvd'])){
						$hidReqID = $_POST['hidReqID'];

						DB::query("UPDATE request SET req_rcvd=? WHERE req_id=?", array(1, $hidReqID), "UPDATE");

						header("Location: request-payout.php");
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
<?php include("includes/footer.php"); ob_end_flush();?>
<!-- End Footer -->
