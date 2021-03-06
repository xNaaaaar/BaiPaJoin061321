<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

	if(isset($_GET['same_day']) && $_GET['same_day'] == 1) {
		echo "<script>alert('Oh uh! We noticed that you have PREVIOUSLY book for an adventure having the same ADVENTURE DATE as the adventure you CURRENTLY selected. Are you sure, you want to continue?')</script>";
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

		.main_info section, .weather{min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:10px;padding:30px;line-height:35px;margin:25px auto;border:1px solid #cfcfcf;}
		.main_info h1{font:600 45px/100% Montserrat,sans-serif;text-align:left;margin:0 0 20px;}
		.main_info h2{margin:0 0 20px;font:500 30px/100% Montserrat,sans-serif;}
		.main_info ul{text-align:left;font-size:20px;}
		.main_info form{margin-bottom:40px;position:relative;}
		.main_info form label{float:left;margin-left:5px;}
		.main_info form label span{color:red;}
		.main_info form button, .main_info form a{margin:15px 5px 0 0;}

		.side_info{width:35%;position:absolute;right:0;}

		.weather{margin:70px auto 25px;border:none;max-width:100%;min-height:100px;padding:20px 20px 30px;position:relative;display:flex;justify-content:space-between;text-align:left;color:#fff;font:600 30px/100% Montserrat,sans-serif;}
		.weather figure{position:absolute;top:50%;right:10px;transform:translateY(-50%);}
		.weather h2:before{content:"";height:80%;width:2px;background:#fff;position:absolute;top:50%;right:-20px;transform:translateY(-50%);}
		.weather h2{display:inline-block;margin:0 40px 0 0;color:#fff;position:relative;}
		.weather h2 span{display:block;font-size:70px;margin:10px 0 0;}
		.weather p{display:inline-block;font-size:18px;margin:15px 0 0;}
		.weather p span{display:block;}

		.book_info{text-align:left;height:auto;min-height:0;margin:0;width:100%;}
		.book_info figure img{width:100%;height:200px;border-radius:10px;}
		.book_info h2{text-align:left;margin:20px 0 10px;}
		.book_info h2 span{color:#86cbec;}
		.book_info .title_info1{list-style:none;margin-bottom:30px;font:600 18px/100% Montserrat,sans-serif;color:gray;}
	  .book_info .title_info1 li{display:inline-block;}
	  .book_info .title_info1 li address{display:inline-block;}
	  .book_info p{margin:5px 0 0;font:600 25px/100% Montserrat,sans-serif;color:gray;display:block;}
	  .book_info section:last-child p{font:400 18px/30px Montserrat,sans-serif;}

		/* RESPONSIVE DESIGN */
		@media only screen and (max-width:1400px){
			.place_info{min-height:940px;}
			.main_info{padding-right:30px;}
			.side_info{right:10px;}
		}
		@media only screen and (max-width:1000px){
			main{padding:50px 0 0;}

			.side_info{width:99%;position:static;}
			.book_info{margin:0 auto 40px;}
			.book_info div{background:#fff;}

			.place_info{min-height:0;}
			.main_info{padding:0;}
			.main_info section{background:#fff;}
		}
		@media only screen and (max-width:500px){
			.main_info ul{font-size:18px;}
			.edit{width:48% !important;}
			.main_info form button, .main_info form a{margin:0 auto;}
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
						$_SESSION['price'] = number_format($price, 2, '.', '');
						$price_with_comma = number_format($price, 2, '.', ',');

						# SLOTS REMAINING
						$slots = $adv['adv_maxguests'] - $adv['adv_currentGuest'];

						# CHOOSING RANDOM IMAGE
						$images = $adv['adv_images'];
						$image = explode(",", $images);
						$totalImagesNum = count($image) - 1;
						$displayImage = rand(1,$totalImagesNum);

						# CURRENT WEATHER OF A CERTAIN LOCATION
						$result = get_current_weather_location($adv[6]);
						$weather = json_decode($result, true);
						$style = weather_bg($weather['weather'][0]['main']);

			?>
			<main>
				<ul class="sub-breadcrumbs">
					<li class="ongoing"><i class="far fa-check-circle"></i> Add Guest <span>&#187;</span></li>
					<li><i class="far fa-check-circle"></i> Fill in Guest Information <span>&#187;</span></li>
					<li><i class="far fa-check-circle"></i> Review & Payment</li>
				</ul>
				<div class="place_info">
					<div class="side_info">
						<!-- WEATHER INFORMATION -->
						<div class="weather" style="<?php echo $style; ?>">
							<?php
								echo "
								<figure>
									<img src='http://openweathermap.org/img/wn/{$weather['weather'][0]['icon']}@2x.png' alt='".$weather['weather'][0]['main']."'>
								</figure>
								<h2>".$weather['weather'][0]['main']."<span>".ceil($weather['main']['temp'])."??</span></h2>
								<p>".date("F j")." <span><i class='fas fa-map-marker-alt'></i> ".$adv[6]."</span></p>
								";
							?>
						</div>
						<!-- BOOKED INFORMATION -->
						<div class="book_info">
							<div>
								<figure>
									<?php
										# DISPLAY RANDOM IMAGE
										echo "<img src='images/organizers/".$adv['orga_id']."/$image[$displayImage]' alt=''>";
									?>
								</figure>
								<section>
									<h2><?php echo $adv['adv_name']; ?> <span><?php echo $adv['adv_kind']; ?></span> </h2>
									<ul class="title_info1">
										<li>
											<?php
											## RATINGS
											$rate = adv_ratings($_GET['id'], true);
											if($rate == 0) echo $rate;
											else echo number_format($rate,1,".","");
											?>
											<i class="fas fa-star"></i> <q>(<?php echo adv_ratings($_GET['id'], true, "count ratings"); ?> reviews)</q></li>
										<li><i class="fas fa-map-marker-alt"></i> <address><?php echo $adv['adv_address']; ?></address></li>
									</ul>
									<p>???<?php echo $_SESSION['price']; ?> / person</p>
								</section>
								<section>
									<h2>Overview</h2>
									<!-- ADVENTURE DESCRIPTION LIMITED TO 30 WORDS -->
									<p><?php echo limit_text($adv['adv_details'], 30) ?></p>
								</section>
							</div>
						</div>
					</div>

					<div class="main_info">
						<h1>Your Information</h1>
						<section>
							<?php
							echo "
							<h2>".$user['joiner_fname']." ".$user['joiner_mi'].". ".$user['joiner_lname']."</h2>
							<ul>
								<li>".$user['joiner_address']."</li>
								<li>Cebu City, Philippines</li>
								<li>".$user['joiner_phone']."</li>
								<li>".$user['joiner_email']."</li>
							</ul>
							";
							?>

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
								<label>Slots Available: <span id="label_slot"></span> </label>
								<!--  -->
								<select name="cboGuests" id="cboGuests" onclick="displayTotalPrice(this.value, <?php echo $slots; ?>)" required>
									<?php
										# SHOW ALL MAXIMUM NUMBER OF GUEST IN OPTIONS
										for($i=1; $i<=$slots; $i++){
											echo "<option value='".$i."'>".$i."</option>";
										}
									?>
								</select>
								<label>Total Price</label>
								<input type="text" name="numTotal" id="totalPrice" value="<?php echo $price_with_comma; ?>" readonly required>
							</section>
							<button class="edit" type="submit" name="btnCont1">Continue</button>
							<a href="place.php?id=<?php echo $_GET['id']; ?>" class="edit">Back</a>
						</form>
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
