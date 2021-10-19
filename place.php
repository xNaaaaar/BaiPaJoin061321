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

		.main_info ul{text-align:left;margin:-20px 0 30px 30px;list-style:circle;}

		.error{font-size:20px;color:red;margin:50px auto;}
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
						<li>
							<?php
							## RATINGS
							$rate = adv_ratings($_GET['id'], true);
							if($rate == 0) echo $rate;
							else echo number_format($rate,1,".","");
							?>
							<i class="fas fa-star"></i> <q>(<?php echo adv_ratings($_GET['id'], true, "count ratings"); ?> reviews)</q></li>
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
							<div class='carousel-cell images'>
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
						<?php
						## LEGAL DOCU OF SPECIFIC ORGANIZER
						$docu = DB::query("SELECT * FROM legal_document WHERE orga_id=? AND docu_viewable=?", array($place['orga_id'],1), "READ");
						if(count($docu)>0){
							echo "
							<p>Here are some legal documents of organizer you can download: </p>
							<ul>";
							foreach ($docu as $result) {
								echo "<li><a href='legal_docu/".$place['orga_id']."/".$result['docu_image']."' download='Legal-Documents'>".$result['docu_type']."</a></li>";
							}
							echo "
							</ul>";
						}
						?>

						<p>You can also download <a href="<?php echo "images/organizers/".$place['orga_id']."/".$place['adv_itineraryImg']; ?>" download="Adventure-Itinerary">this</a> adventure itinerary and <a href="<?php echo "images/organizers/".$place['orga_id']."/".$place['adv_dosdont_image']; ?>" download="Dos-And-Donts">do's and dont's</a>.</p>
					</div>
					<div class="book_info">
						<div>
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
								$adv_checker = DB::query('SELECT * FROM adventure WHERE adv_id=?', array($_GET['id']), "READ");
								$adv_checker = $adv_checker[0];

								//This will only excute if a joiner will login.
								if(!empty($_SESSION['joiner'])) {
									// IF CHECK JOINER HAS OTHER BOOKING SET ON THE SAME DATE
									$adv_ids = DB::query('SELECT adv_id FROM booking WHERE joiner_id=? AND book_status=?', array($_SESSION['joiner'], 'paid'), "READ");
									if(!empty($adv_ids)) {
										$adv_ids = $adv_ids[0];
										$sameday_booking = -1;
										if(!empty($adv_ids)) {
											foreach ($adv_ids as $adv_id) {
												$adv = DB::query('SELECT adv_date FROM adventure WHERE adv_id=?', array($adv_id), "READ");
												$adv = $adv[0];

												if($adv['adv_date'] == $adv_checker['adv_date']) {
													$sameday_booking = 1;
													break;
												}
											}
										}
									}
								}

								// WHEN BUTTON IS CLICKED
								if(isset($_POST['btnBook'])){
									if(count($adv_checker)>0){
										// CHECK ADVENTURE IF FULL
										if($adv_checker['adv_status'] == 'not full') {
											//file_put_contents('debug.log', date('h:i:sa').' => '. $sameday_booking . "\n" . "\n", FILE_APPEND);
											header("Location: book.php?id=".$_GET['id']."&same_day=".$sameday_booking."");
										}
										else header("Location: place.php?id=".$_GET['id']."&full");
									}
								}
							?>

							<a class="edit" href="#">Lend</a>
						</div>
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
					<div class="carousel" data-flickity>
						<?php
						$message = "There are no reviews to this adventure!";
						## THIS ADVENTURE
						if(isset($_GET['id'])){
							$this_adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($_GET['id']), "READ");
							$this_adv = $this_adv[0];
							## ADVENTURE THAT HAS BEEN RATED WITH SPECIFIC ORGANIZER
							$adv = DB::query("SELECT * FROM rating r JOIN booking b ON r.book_id=b.book_id JOIN adventure a ON b.adv_id=a.adv_id WHERE orga_id=?", array($this_adv['orga_id']), "READ");
							##
							if(count($adv)>0){
								foreach ($adv as $result) {
									if($result['adv_name'] == $this_adv['adv_name'] && $result['adv_kind'] == $this_adv['adv_kind'] && $result['adv_type'] == $this_adv['adv_type'] && $result['adv_address'] == $this_adv['adv_address']){
										echo "
										<div class='card carousel-cell'>
											<figure>
												<img src='images/joiners/".$result['joiner_id']."/".$result['rating_img']."'>
											</figure>
											<h3>Anonymous <span>rated ".$result['rating_stars']." <i class='fas fa-star'></i></span></h3>
											<i class='fas fa-quote-left'></i>
											<p>".$result['rating_message']."</p>
										</div>
										";
										$message = "";
									}
								}
							}
						}
						## IF THERE ARE NO RATINGS
						echo ($message == "")? "" : "<h3 class='error'>".$message."</h3>";
						?>
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
