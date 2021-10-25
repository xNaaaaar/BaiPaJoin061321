<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
	ob_start();

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
		main{width:100%;flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0;border-radius:0;text-align:center;}
		main h1{font:600 50px/100% Montserrat,sans-serif;}

		.card-link{text-decoration:none !important;}
		.card{width:60%;min-height:227px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:20px;padding:30px 125px 30px 215px;line-height:35px;text-align:left;margin:25px auto;border:1px solid #cfcfcf;}
		.card:hover{border:1px solid #bf127a;}
		.card figure{width:165px;height:165px;position:absolute;top:30px;left:30px;border:1px solid #cfcfcf;}
		.card figure img{width:100%;height:100%;}
		.card h2{font:600 35px/100% Montserrat,sans-serif;color:#313131;margin-bottom:15px;}
		.card h2 span{display:block;font-size:18px;color:gray;}
		.card h2 span i{color:#ffac33;}
		.card input{margin:0 auto;}
		.card p{font-size:23px;color:#989898;width:100% !important;margin:0 0 10px 2px;}
		.card p:last-of-type{color:#111;font-size:30px;font-weight:500;margin:0 0 20px 2px;}
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
	$currentPage = 'adventures';
	include("includes/nav.php");
?>
<!-- End Navigation -->

<!-- Main -->
<div id="main_area">
	<div class="wrapper">
		<div class="breadcrumbs">
			<a href="index.php">Home</a> &#187; <a href="adventures.php">Adventures</a> &#187; Request Adventure Date
		</div>
		<div class="main_con">
			<main>
				<h1>Request Adventure Date</h1>
				<?php
				$card = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($_GET['adv_id']), "READ");
				$card = $card[0];
				$joiner = DB::query("SELECT * FROM joiner WHERE joiner_id = ?", array($_SESSION['joiner']), "READ");
				$joiner = $joiner[0];
				##
				$remainingGuestsText = "";
				$images = $card['adv_images'];
				$image = explode(",", $images);

				// RANDOM IMAGE DISPLAY
				$totalImagesNum = count($image) - 1;
				$displayImage = rand(1,$totalImagesNum);

				// PRICE PER PERSON
				$price = $card['adv_totalcostprice'] / $card['adv_maxguests'];

				// REMAINING GUEST
				$numRemainingGuests = $card['adv_maxguests'] - $card['adv_currentGuest'];

				// REMAINING GUEST IN TEXT
				if($card['adv_type'] == 'Packaged')
					$remainingGuestsText = " - ".$card['adv_currentGuest']."/".$card['adv_maxguests']." slots occupied";

				echo "
				<form method='post'>
				<div class='card'>
					<figure>
						<img src='images/organizers/".$card['orga_id']."/$image[$displayImage]' alt=''>
					</figure>
					<h2>".$card['adv_name']." <span>".$card['adv_kind']." (".$card['adv_type'].")</span></h2>";

					$distance = 0;
					##
					if(!empty($joiner['joiner_citymuni']))
						$distance = get_distance_from_location($joiner['joiner_citymuni'],$card['adv_town']);
					if($distance > 0)
						echo "<p>".$card['adv_address']." - <b>".$distance."</b> KMs away from ".$joiner['joiner_citymuni']."</p>";
					else
						echo "<p>".$card['adv_address']."</p>";

					echo "
					<p>₱".number_format((float)$price, 2, '.', ',')." / person</p>
					<span class='date'>Request a date: </span>
					<input type='text' name='dateDate' placeholder='Adventure Date' onfocus=\"(this.type='date')\" onblur=\"if(this.value==''){this.type='text'}\" required />
				</div>
				<button type='submit' name='btnRequest' class='edit'>Request</button>
				<a class='edit' href='adventures.php'>Back</a>
				</form>
				";
				## BUTTON IS CLICKED
				if(isset($_POST['btnRequest'])){
					$dateDate = $_POST['dateDate'];

					header("Location: adventures.php?request_success");
				}
				?>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ob_end_flush();?>
<!-- End Footer -->
