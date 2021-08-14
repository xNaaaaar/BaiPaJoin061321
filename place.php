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
						<li>5 <i class="fas fa-star"></i> <q>(25 reviews)</q></li>
						<li><i class="fas fa-map-marker-alt"></i> <address>Santa Fe, Cebu</address></li>
					</ul>
					<!-- <ul class="title_info2">
						<li><i class="fas fa-share-square"></i> Share</li>
						<li><i class="fas fa-heart"></i> Save</li>
					</ul> -->
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
						<h2>Overview</h2>
						<p>You are reading dummy text as placeholders for this layout. Dummy text for the reader to review. Words shown on this layout are placeholders. More information about the company will be posted soon. Contents are for display purposes only. This space is reserved for more details.You are reading dummy text as placeholders for this layout. Dummy text for the reader to review. Words shown on this layout are placeholders. More information about the company will be posted soon. Contents are for display purposes only.  This space is reserved for more details.</p>
					</div>
					<div class="book_info">
						<h2>On Aug. 25, 2021 <span>₱3,000/guest</span> </h2>
						<a class="edit" href="#">Book</a>
						<a class="edit" href="#">Lend</a>
					</div>
				</div>
				<!--  -->
				<div class="place_reviews">
					<h2>Reviews</h2>
					<div class="review_cards">
						<div class="card">
							<i class="fas fa-quote-left"></i>
							<p>You are reading dummy text as placeholders for this layout. Dummy text for the reader to review. Words shown on this layout are placeholders. More information about the company will be posted soon. Contents are for display purposes only.</p>
							<h3>Melnar Ancit <span>rated 4 <i class="fas fa-star"></i></span></h3>
						</div>
						<div class="card">
							<i class="fas fa-quote-left"></i>
							<p>You are reading dummy text as placeholders for this layout. Dummy text for the reader to review. Words shown on this layout are placeholders. </p>
							<h3>Joy Blanco <span>rated 3 <i class="fas fa-star"></i></span></h3>
						</div>
						<div class="card">
							<i class="fas fa-quote-left"></i>
							<p>You are reading dummy text as placeholders for this layout. Dummy text for the reader to review. Words shown on this layout are placeholders. More information about the company will be posted soon. Contents are for display purposes only. This space is reserved for more details.</p>
							<h3>John Doe <span>rated 4 <i class="fas fa-star"></i></span></h3>
						</div>
					</div>
				</div>
				<!--  -->
				<div class="place_ratings">
					<h2>Ratings</h2>
					<form method="post">
						<div class="rating">
							<input type="radio" id="star5" name="star" value="5" required><label for="star5"></label>
							<input type="radio" id="star4" name="star" value="4" required><label for="star4"></label>
							<input type="radio" id="star3" name="star" value="3" required><label for="star3"></label>
							<input type="radio" id="star2" name="star" value="2" required><label for="star2"></label>
							<input type="radio" id="star1" name="star" value="1" required><label for="star1"></label>
						</div>
						<div class="feedback">
							<textarea name="txtFeedback" placeholder="Feedback (Optional)"></textarea>
							<button class="edit" type="submit" name="btnRate">Rate</button>
							<a class="edit" href="adventures.php">Back</a>
						</div>
					</form>
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
