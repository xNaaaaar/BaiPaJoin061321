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
		main{width:100%;flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0;border-radius:0;text-align:center;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}

		.sub-breadcrumbs{text-align:right;margin-bottom:30px;}
		.sub-breadcrumbs li{display:inline;margin-left:10px;color:gray;}
		.sub-breadcrumbs li span{margin-left:10px;}
		.ongoing{color:#000 !important;}
		.success{color:#5cb85c !important;}

		.place_info{margin:0;}

		.main_info section, .weather{min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:10px;padding:30px;line-height:35px;margin:25px auto;border:1px solid #cfcfcf;}
		.main_info h1{font:600 50px/100% Montserrat,sans-serif;text-align:left;margin:0 0 20px;}
		.main_info h2{margin:0 0 20px;font:500 35px/100% Montserrat,sans-serif;}
		.main_info ul{text-align:left;font-size:20px;}
		.main_info form{margin-bottom:40px;position:relative;}
		.main_info form label{float:left;margin-left:5px;}
		.main_info form input, .main_info form select{display:inline-block;width:99%;height:60px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 18px/20px Montserrat,sans-serif;padding:0 30px;margin:0 auto 15px;border:1px solid #cfcfcf;}
		.main_info form button, .main_info form a{margin:15px 5px 0 0;}

		.side_info{width:35%;}
		.weather{margin:75px auto 25px;}

		.book_info{text-align:left;height:auto;min-height:0;margin:0;width:100%;}
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
		<div class="breadcrumbs">
			<a href="index.php">Home</a> &#187; <a href="adventures.php">Adventures</a> &#187; Booking
		</div>
		<div class="main_con">
			<?php
				# CHECK IF JOINER LOGGED IN
				if(isset($_SESSION['joiner'])) {
					$user = DB::query("SELECT * FROM joiner WHERE joiner_id = ?", array($_SESSION['joiner']), "READ");
					$adv = DB::query("SELECT * FROM adventure WHERE adv_id = ?", array($_GET['id']), "READ");
					# CHECK IF JOINER EXISTED
					if(count($user)>0 && count($adv)>0){
						$user = $user[0];
						$adv = $adv[0];
						# PRICE FOR EACH GUEST
						$price = $adv['adv_totalcostprice'] / $adv['adv_maxguests'];
						$_SESSION['price'] = number_format((float)$price, 2, '.', '');
						# CHOOSING RANDOM IMAGE
						$images = $adv['adv_images'];
						$image = explode(",", $images);
						$totalImagesNum = count($image) - 1;
						$displayImage = rand(1,$totalImagesNum);
			?>
			<main>
				<ul class="sub-breadcrumbs">
					<li class="ongoing"><i class="far fa-check-circle"></i> Add Guest <span>&#187;</span></li>
					<li><i class="far fa-check-circle"></i> Fill in Guest Information <span>&#187;</span></li>
					<li><i class="far fa-check-circle"></i> Review & Payment</li>
				</ul>
				<div class="place_info">
					<div class="main_info">
						<h1>Your Information</h1>
						<section>
							<h2><?php echo $user['joiner_fname']." ".$user['joiner_mi'].". ".$user['joiner_lname']; ?></h2>
							<ul>
								<li><?php echo $user['joiner_address']; ?></li>
								<li>Cebu City, Philippines</li>
								<li><?php echo $user['joiner_phone']; ?></li>
								<li><?php echo $user['joiner_email']; ?></li>
							</ul>
						</section>
						<!--  -->
						<form method="post" action="book-guest.php?id=<?php echo $_GET['id']; ?>">
							<section>
								<h2><?php echo "Date: ".date('M. j, Y', strtotime($adv['adv_date'])); ?></h2>
								<select name="cboOption" onclick="checkBooking(this.value);" required>
									<option value="">-- BOOKING OPTION --</option>
									<option value="guest">I am booking as a guest</option>
									<option value="someone">I am booking for someone</option>
								</select>
								<label>Add guest/s:</label>
								<!--  -->
								<select name="cboGuests" id="cboGuests" onclick="displayTotalPrice(this.value)" required>
									<?php
										# SHOW ALL MAXIMUM NUMBER OF GUEST IN OPTIONS
										for($i=1; $i<=$adv['adv_maxguests']; $i++){
											echo "<option value='".$i."'>".$i."</option>";
										}
									?>
								</select>
								<label>Total Price</label>
								<input type="number" name="numTotal" id="totalPrice" value="<?php echo $_SESSION['price']; ?>" readonly required>
							</section>
							<button class="edit" type="submit" name="btnCont1">Continue</button>
							<a href="place.php?id=<?php echo $_GET['id']; ?>" class="edit">Back</a>
						</form>
					</div>

					<div class="side_info">
						<!-- WEATHER INFORMATION -->
						<div class="weather" style="background:gray;">
							<?php
								//This method will return the current weather at a certain location
								$result = get_current_weather_location($adv[6]);
								$weather = json_decode($result, true);
								$echo $weather['weather'][0]['main'];
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
				</div>
			</main>
			<?php
				}
			}
			#END OF PHP CONDITION ABOVE
			?>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
