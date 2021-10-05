<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
	ob_start();

	// IF RATED SUCCESSFULLY AN ADVENTURE
	if(isset($_GET['rated']) && $_GET['rated'] == 1){
		echo "<script>alert('This adventure is successfully rated!')</script>";
	}

	// IF ADVENTURE IS FULL
	if(isset($_GET['full']))
		echo "<script>alert('This adventure is full!')</script>";
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
		main h1 span{display:block;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
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
			<?php
				// DISPLAY DETAILS CODE STARTS HERE
				if(isset($_GET['id'])){
					$place = DB::query("SELECT * FROM adventure WHERE adv_id = ?", array($_GET['id']), "READ");

					if(count($place)>0){
						$place = $place[0];
						$price = $place['adv_totalcostprice'] / $place['adv_maxguests'];

						$advImages = explode(",", $place['adv_images']);
			?>
			<main>
				<div class="place_title">
					<h1><?php echo $place['adv_name']; ?> <span>(<?php echo $place['adv_kind']." - ".$place['adv_type']; ?>)</span> </h1>
					<ul class="title_info1">
						<li>5 <i class="fas fa-star"></i> <q>(25 reviews)</q></li>
						<li><i class="fas fa-map-marker-alt"></i> <address><?php echo $place['adv_address']; ?></address></li>
					</ul>
					<!-- <ul class="title_info2">
						<li><i class="fas fa-share-square"></i> Share</li>
						<li><i class="fas fa-heart"></i> Save</li>
					</ul> -->
				</div>
				<!--  -->
				<div class="carousel" data-flickity>
					<?php
						// DISPLAYING IMAGES
						for($i = 1; $i < count($advImages); $i++){
							echo "
							<div class='carousel-cell'>
								<img src='images/organizers/".$place['orga_id']."/".$advImages[$i]."'>
							</div>
							";
						}
					?>
				</div>
				<!--  -->
				<div class="place_info">
					<div class="main_info">
						<h2>Overview</h2>
						<p><?php echo $place['adv_details']; ?></p>
					</div>
					<div class="book_info">
						<h2>On <?php echo date('M. j, Y', strtotime($place['adv_date'])); ?> <span><?php echo "₱ ".number_format((float)$price, 2, '.', ',')." / person"; ?></span> </h2>

						<form method="post">
							<?php
							## IF JOINER LOGIN
							if(isset($_SESSION['joiner'])) {
								$isempty = false;
								$details_empty = DB::query("SELECT * FROM joiner WHERE joiner_id=?", array($_SESSION['joiner']), "READ");
								if(count($details_empty)>0){
									foreach ($details_empty as $result) {
										$isempty = ($result[4] == "" || $result[5] == "") ? true : false;
									}
								}

								if($isempty)
									echo "<a class='edit' href='settings.php' onclick='return confirm(\"You need to fill out all profile details!\");'>Book</a>";
								else
									echo "<button class='edit' type='submit' name='btnBook'>Book</button>";

							## FOR GUESTS
							} else {
							?>
								<a class="edit" href="login.php" onclick='return confirm("Login to book adventure!");'>Book</a>
							<?php
							}
							?>
						</form>

						<?php
							// WHEN BUTTON IS CLICKED
							if(isset($_POST['btnBook'])){
								// CHECK ADVENTURE IF FULL
								$adv_checker = DB::query('SELECT * FROM adventure WHERE adv_id=?', array($_GET['id']), "READ");
								if(count($adv_checker)>0){
									$adv_checker = $adv_checker[0];
									//
									if($adv_checker['adv_status'] == 'not full')
										header("Location: book.php?id=".$_GET['id']."");
									else
										header("Location: place.php?id=".$_GET['id']."&full");
								}
							}
						?>

						<a class="edit" href="#">Lend</a>
					</div>
				</div>
				<?php
						}
					}
					// DISPLAY DETAILS CODE ENDS HERE
				?>
				<!--  -->
				<div class="place_reviews">
					<h2>Reviews</h2>
					<div class="review_cards">
						<div class="card">
							<i class="fas fa-quote-left"></i>
							<p>I was looking for a place to spend time with myself. I found this place in BaiPaJoin and thought the price was affordable. When I arrived, they already had the room ready and cleaned with all the toiletries. My accommodation was of great value and will definitely return to this place when I visit Camotes Island again!</p>
							<h3>Melnar Ancit <span>rated 4 <i class="fas fa-star"></i></span></h3>
						</div>
						<div class="card">
							<i class="fas fa-quote-left"></i>
							<p>I have stayed in many many hotels over the years, but never one quite like this at the price. Great staff, a really comfortable bed, unbelievable food and a very friendly owner. After reading so many incredible reviews about this hotel, I had to experience it for myself, and if was a good decision. There is quite a lot to see and do from having a base here, and the hotel will arrange day tours, or scooter hire if requested. I did two day tours and enjoyed them both very much. As for the food, it didn't reach on what I had expected, some food was bland, and some was good, it was really 50/50.</p>
							<h3>Joy Blanco <span>rated 3 <i class="fas fa-star"></i></span></h3>
						</div>
						<div class="card">
							<i class="fas fa-quote-left"></i>
							<p>This resort is amongst the very few existing facilities on Camotes Island that has a degree of scale and the ability to satisfy the expectations of the travellers / holiday makers. The food on offer is good quality and very reasonably priced. The hotel offers fabulous swimming and is not too far from the Ferry Terminal. Surprisingly the breakfast menu does not include much in the way of fresh fruit. It’s easily either the best or at worst the second best place to stay.</p>
							<h3>John Doe <span>rated 4 <i class="fas fa-star"></i></span></h3>
						</div>
					</div>
				</div>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ob_end_flush();?>
<!-- End Footer -->
