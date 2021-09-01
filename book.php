<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(isset($_POST['btnRate'])){
		rateAdventure();
	}

	// IF RATED SUCCESSFULLY AN ADVENTURE
	if(isset($_GET['rated']) && $_GET['rated'] == 1){
		echo "<script>alert('This adventure is successfully rated!')</script>";
	}

	$_SESSION['price'] = 900;
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

		.place_info{margin:0;}

		.main_info h1{font:600 50px/100% Montserrat,sans-serif;text-align:left;margin:0 0 20px;}
		.main_info h2{margin:0;font:500 30px/100% Montserrat,sans-serif;}
		.main_info ul{text-align:left;margin:0 0 30px;}
		.main_info form{margin-bottom:40px;position:relative;}
		.main_info form label{float:left;margin-left:5px;}
		.main_info form input{display:inline-block;width:99%;height:60px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 20px/20px Montserrat,sans-serif;padding:0 30px;margin:0 auto 15px;border:1px solid #cfcfcf;}
		.main_info form .radio{display:block;width:25px;height:25px;border:none;box-shadow:none;border-radius:0;padding:0;margin:10px auto 25px 5px;}
		.main_info form .terms_cond{position:absolute;bottom:97px;left:40px;}
		.main_info form button{margin:15px 5px 0 0;}

		.book_info{text-align:left;height:100%;min-height:0;}
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
			<a href="index.php">Home</a> &#187; <a href="adventures.php">Adventures</a> &#187; Book
		</div>
		<div class="main_con">

			<main>
				<div class="place_info">
					<div class="main_info">
						<h1>Your Information</h1>
						<section>
							<h2>Melnar B. Ancit</h2>
							<ul>
								<li>Sitio Granada Quiot Pardo</li>
								<li>Cebu City, Philippines</li>
								<li>09458566652</li>
								<li>melnar.a@gmail.com</li>
							</ul>
						</section>
						<form method="post">
							<label for="">Book date</label>
							<input type="text" name="" value="Book Date" disabled>
							<label for="">Total Price</label>
							<input id="totalPrice" type="text" name="" disabled>
							<label for="">Add guest/s:</label>
							<input id="guest" type="text" name="" value="3" onchange="displayTotalPrice(this.value)">
							<a class="terms_cond" href="terms.php" target="_blank">Accept terms & condition</a>
							<input class="radio" type="radio" name="" value="">
							<button class="edit" type="button" name="button">Continue</button>
							<button class="edit" type="button" name="button">Back</button>
						</form>
					</div>
					<div class="book_info">
						<figure>
							<img src="images/organizers/1/610a6ddb4b7d77.05444535.jpg" alt="">
						</figure>
						<section>
							<h2>Adventure Name <span>Island Hopping</span> </h2>
							<ul class="title_info1">
								<li>5 <i class="fas fa-star"></i> <q>(25 reviews)</q></li>
								<li><i class="fas fa-map-marker-alt"></i> <address>Bantayan Island</address></li>
							</ul>
							<p>₱900 / guest</p>
						</section>
						<section>
							<h2>Overview</h2>
							<p><?php echo limit_text("You are reading dummy text as placeholders for this layout. Dummy text for the reader to review. Words shown on this layout are placeholders. More information about the company will be posted soon. Contents are for display purposes only.", 30) ?></p>
						</section>
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
