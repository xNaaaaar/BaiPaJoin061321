<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(isset($_POST['btnCont1'])){
		booking("pending");
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
		main{width:100%;flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0;border-radius:0;text-align:center;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}

		.sub-breadcrumbs{text-align:right;margin-bottom:30px;}
		.sub-breadcrumbs li{display:inline;margin-left:10px;color:gray;}
		.sub-breadcrumbs li span{margin-left:10px;}
		.ongoing{color:#000 !important;}
		.success{color:#5cb85c !important;}

		.place_info{margin:0;}

		.main_info section{min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:10px;padding:30px;line-height:35px;margin:25px auto;border:1px solid #cfcfcf;}
		.main_info h1{font:600 45px/100% Montserrat,sans-serif;text-align:left;margin:0 0 20px;}
		.main_info h2{margin:0 0 20px;font:500 35px/100% Montserrat,sans-serif;}
		.main_info h2 em{display:block;font-size:20px;color:gray;}
		.main_info h3{margin:0 0 20px;font:500 25px/100% Montserrat,sans-serif;text-align:left;}
		.main_info ul{text-align:left;font-size:20px;margin:0 0 20px;}
		.main_info .form{margin-bottom:40px;position:relative;}
		.main_info .form label{float:left;margin-left:5px;}
		.main_info .form input, .main_info select{display:inline-block;width:99%;height:60px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 18px/20px Montserrat,sans-serif;padding:0 30px;margin:0 auto 15px;border:1px solid #cfcfcf;}
		.main_info .form .radio{display:block;width:25px;height:25px;border:none;box-shadow:none;border-radius:0;padding:0;margin:10px auto 25px 5px;}
		.main_info .form .terms_cond{position:absolute;bottom:97px;left:40px;}
		.main_info .form button, .main_info .form a{margin:15px 5px 0 0;}

		.book_info{text-align:left;height:100%;min-height:0;margin:75px auto 0;}
		.book_info figure img{width:100%;height:200px;border-radius:10px;}
		.book_info h2{text-align:left;margin:20px 0 10px;}
		.book_info .title_info1{list-style:none;margin-bottom:30px;font:600 18px/100% Montserrat,sans-serif;color:gray;}
	  .book_info .title_info1 li{display:inline-block;}
	  .book_info .title_info1 li address{display:inline-block;}
	  .book_info p{margin:5px 0 0;font:600 25px/100% Montserrat,sans-serif;color:gray;display:block;}
	  .book_info section:last-child p{font:400 18px/30px Montserrat,sans-serif;}
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
		<?php
			if(isset($_GET['id'])){
				# GET THE ADVENTURE ID
				$adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($_GET['id']), "READ");
				# GET THE BOOKED ADV WHERE STATUS IS PENDING
				$pendingBooking = DB::query("SELECT * FROM booking WHERE book_guests=? AND book_totalcosts=? AND book_status=? AND joiner_id=? AND adv_id=?", array($_SESSION['cboGuests'], $_SESSION['numTotal'], "pending", $_SESSION['joiner'], $_GET['id']), "READ");
				$joiner = DB::query("SELECT * FROM joiner WHERE joiner_id=?", array($_SESSION['joiner']), "READ");
				//
				if(count($adv)>0 && count($pendingBooking)>0 && count($joiner)>0){
					$adv = $adv[0];
					$pendingBooking = $pendingBooking[0];
					$joiner = $joiner[0];
					# CHOOSING RANDOM IMAGE
					$images = $adv['adv_images'];
					$image = explode(",", $images);
					$totalImagesNum = count($image) - 1;
					$displayImage = rand(1,$totalImagesNum);
		?>
		<div class="breadcrumbs">
			<a href="index.php">Home</a> &#187; <a href="adventures.php">Adventures</a> &#187; Booking
		</div>
		<div class="main_con">
			<main>
				<ul class="sub-breadcrumbs">
					<li class="ongoing success"><i class="far fa-check-circle"></i> Add Guest <span>&#187;</span></li>
					<li class="ongoing"><i class="far fa-check-circle"></i> Fill in Guest Information <span>&#187;</span></li>
					<li><i class="far fa-check-circle"></i> Review & Payment</li>
				</ul>
				<div class="place_info">
					<div class="main_info">
						<form method="post" action="payment-card.php?book_id=<?php echo $pendingBooking['book_id']; ?>&id=<?php echo $_GET['id']; ?>">
						<h1>Guest Information</h1>
						<section>
							<h2>
								<?php
									echo $joiner['joiner_fname']." ".$joiner['joiner_mi'].". ".$joiner['joiner_lname'];
									if($_SESSION['bookOption'] == "someone"){
										$_SESSION['cboGuests'] = $_SESSION['cboGuests'] + 1;
										echo "<em>Booking for someone else.</em>";
									} else {
										echo "<em>Booking as a guest.</em>";
									}
								?>
							</h2>
							<ul>
								<li>Book ID: <?php echo $pendingBooking['book_id']; ?></li>
								<li>Book Date: <?php echo date('M. j, Y H:i a', strtotime($pendingBooking['book_datetime'])); ?></li>
								<li>Total Price: ₱<?php echo $_SESSION['numTotal']; ?></li>
								<li>Guest: <?php echo $pendingBooking['book_guests']; ?></li>
							</ul>
						</section>
						<!--  -->
						<div class="form">
							<?php
								for($i=1; $i<$_SESSION['cboGuests']; $i++){
									echo "
									<section>
										<h3>Guest ".$i."</h3>
										<input type='text' name='txtName[]' placeholder='Name' required>
										<input type='text' name='txtPhone[]' placeholder='Phone' maxlength='11' minlength='11' required>
										<input type='email' name='emEmail[]' placeholder='Email' required>
									</section>
									";
								}
							?>
							<a class="terms_cond" href="terms.php" target="_blank">Accept terms & condition</a>
							<input class="radio" type="radio" name="radioTerms" required>
							<button class="edit" type="submit" name="btnCont2">Continue</button>
							<a href="delete.php?table=booking&id=<?php echo $pendingBooking['book_id']; ?>&adv=<?php echo $_GET['id']; ?>" class="edit">Back</a>
						</div>
						</form>
						<!-- WHEN BUTTON IS CLICKED -->
						<?php

						?>
					</div>
					<!-- BOOKED INFORMATION -->
					<div class="book_info">
						<figure>
							<?php
								# DISPLAY RANDOM IMAGE
								echo "<img src='images/organizers/".$adv['orga_id']."/$image[$displayImage]' alt=''>";
							?>
						</figure>
						<section>
							<h2><?php echo $adv['adv_name']." (".$adv['adv_type'].")"; ?> <span><?php echo $adv['adv_kind']; ?></span> </h2>
							<ul class="title_info1">
								<li>5 <i class="fas fa-star"></i> <q>(25 reviews)</q></li>
								<li><i class="fas fa-map-marker-alt"></i> <address><?php echo $adv['adv_address']; ?></address></li>
							</ul>
							<p>₱<?php echo $_SESSION['price']; ?> / guest</p>
						</section>
						<section>
							<h2>Overview</h2>
							<!-- ADVENTURE DESCRIPTION LIMITED TO 30 WORDS -->
							<p><?php echo limit_text($adv['adv_details'], 30) ?></p>
						</section>
					</div>
				</div>
			</main>
			<?php
					}
				}
			?>
		</div>
	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php");?>
<!-- End Footer -->
