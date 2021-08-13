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
		main h2{font:600 59px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
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
			<a href="index.php">Home</a> &#187; <a href="adventures.php">Adventures</a> &#187; Place
		</div>
		<div class="main_con">
			<main>
				<div class="place_title">
					<h1>Santa Fe</h1>
					<ul class="title_info1">
						<li>5 <i class="fas fa-star"></i></li>
						<li><q>(25 reviews)</q></li>
						<li><i class="fas fa-map-marker-alt"></i> <address>Santa Fe, Cebu</address></li>
					</ul>
					<ul class="title_info2">
						<li><i class="fas fa-share-square"></i> Share</li>
						<li><i class="fas fa-heart"></i> Save</li>
					</ul>
				</div>
				<!--  -->
				<div class="carousel" data-flickity>
					<div class="carousel-cell">
						<img src="images/slider/1.jpg" alt="">
					</div>
					<div class="carousel-cell">
						<img src="images/slider/2.jpg" alt="">
					</div>
					<div class="carousel-cell">
						<img src="images/slider/3.jpg" alt="">
					</div>
					<div class="carousel-cell">
						<img src="images/slider/4.jpg" alt="">
					</div>
				</div>
				<!--  -->
				<div class="place_info">
					<div class="main_info">
						<ul class="info">
							<li>
								<h2>Overview</h2>
								<p>You are reading dummy text as placeholders for this layout. Dummy text for the reader to review. Words shown on this layout are placeholders. More information about the company will be posted soon. Contents are for display purposes only. This space is reserved for more details.You are reading dummy text as placeholders for this layout. Dummy text for the reader to review. Words shown on this layout are placeholders. More information about the company will be posted soon. Contents are for display purposes only.  This space is reserved for more details.</p>
							</li>
							<li>
								<h2>Property Amenities</h2>
								<ul>
									<li><i class="fas fa-water"></i> Beaching</li>
									<li><i class="fas fa-wine-bottle"></i> Kayaking</li>
								</ul>
							</li>
							<li>
								<h2>Room Features</h2>
								<ul>
									<li><i class="fas fa-person-booth"></i> Private Balcony</li>
									<li><i class="fas fa-wind"></i> Air Conditioning</li>
									<li><i class="fas fa-tv"></i> Flatscreen TV</li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="book_info">
						<div>
							<ul>
								<li>₱3,000/person</li>
								<li>5 <i class="fas fa-star"></i> <q>(25 reviews)</q></li>
							</ul>
						</div>
						<form>
							<input type="text" name="" placeholder="Check-In" onfocus="(this.type='date')">
							<input type="text" name="" placeholder="Check-Out" onfocus="(this.type='date')">
							<select>
								<option>No. of Guests</option>
								<option>1</option>
								<option>2</option>
								<option>3</option>
								<option>4</option>
								<option>5</option>
							</select>
							<button>Book</button>
							<button>Lend</button>
						</form>
					</div>
				</div>
				<!--  -->
				<div class="place_location">
					<h1>Location</h1>
					<div class="loc_map">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125221.35542696604!2d123.7606403408645!3d11.249096259254607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a87d5fc950c8fb%3A0x9dbab0f8f9885ad1!2sSanta%20Fe%2C%20Cebu!5e0!3m2!1sen!2sph!4v1616413480817!5m2!1sen!2sph" allowfullscreen="" loading="lazy"></iframe>
					</div>
				</div>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
