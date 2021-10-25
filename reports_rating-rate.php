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

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
		main h3{font-weight:500;font-size:25px;margin:20px 0 10px;text-align:left;display:block;}
		main h4{font-weight:500;font-size:20px;display:block;color:red;text-align:left;margin-bottom:20px;}

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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; <a href="reports_booking.php">Reports </a> &#187; Ratings
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'reports';
				$currentSubMenu = 'ratings';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->
			<main>
				<div class="place_ratings">
					<h2>Rate</h2>
					<h4>Your ratings will be seen as Anonymous in adventure's reviews.</h4>
					<form method="post" enctype="multipart/form-data">
						<h3>Joiner Engagement & Communication Rating</h3>
						<section>
							<div class="rating">
								<input type="radio" id="star5" name="engage_rating" value="5" required><label for="star5" title='Excellent'></label>
								<input type="radio" id="star4" name="engage_rating" value="4" required><label for="star4" title='Very Good'></label>
								<input type="radio" id="star3" name="engage_rating" value="3" required><label for="star3" title='Good'></label>
								<input type="radio" id="star2" name="engage_rating" value="2" required><label for="star2" title='Bad'></label>
								<input type="radio" id="star1" name="engage_rating" value="1" required><label for="star1" title='Very Bad'></label>
							</div>
						</section>
						<h3>Tour Safety, Mastery & Expertise Rating</h3>
						<section>
							<div class="rating">
								<input type="radio" id="star10" name="quality_rating" value="5" required><label for="star10" title='Excellent'></label>
								<input type="radio" id="star9" name="quality_rating" value="4" required><label for="star9" title='Very Good'></label>
								<input type="radio" id="star8" name="quality_rating" value="3" required><label for="star8" title='Good'></label>
								<input type="radio" id="star7" name="quality_rating" value="2" required><label for="star7" title='Bad'></label>
								<input type="radio" id="star6" name="quality_rating" value="1" required><label for="star6" title='Very Bad'></label>
							</div>
						</section>
						<h3>Tour Expectation VS Reality Rating</h3>
						<section>
							<div class="rating">
								<input type="radio" id="star15" name="expect_rating" value="5" required><label for="star15" title='Excellent'></label>
								<input type="radio" id="star14" name="expect_rating" value="4" required><label for="star14" title='Very Good'></label>
								<input type="radio" id="star13" name="expect_rating" value="3" required><label for="star13" title='Good'></label>
								<input type="radio" id="star12" name="expect_rating" value="2" required><label for="star12" title='Bad'></label>
								<input type="radio" id="star11" name="expect_rating" value="1" required><label for="star11" title='Very Bad'></label>
							</div>
						</section>
						<h3>Overall Experience Rating</h3>
						<section>
							<div class="rating">
								<input type="radio" id="star20" name="overall_rating" value="5" required><label for="star20" title='Excellent'></label>
								<input type="radio" id="star19" name="overall_rating" value="4" required><label for="star19" title='Very Good'></label>
								<input type="radio" id="star18" name="overall_rating" value="3" required><label for="star18" title='Good'></label>
								<input type="radio" id="star17" name="overall_rating" value="2" required><label for="star17" title='Bad'></label>
								<input type="radio" id="star16" name="overall_rating" value="1" required><label for="star16" title='Very Bad'></label>
							</div>
						</section>
						<div class="feedback">
							<label>Upload pictures of adventure</label>
							<input type="file" name="fileAdvImg" required>
							<textarea name="txtFeedback" placeholder="Feedback (Required)" required maxlength="100"></textarea>
							<button class="edit" type="submit" name="btnRate">Rate</button>
							<a class="edit" href="reports_rating.php">Back</a>
						</div>
					</form>
				</div>

				<?php
				if(isset($_POST['btnRate'])){
					$engage = $_POST['engage_rating'];
					$quality = $_POST['quality_rating'];
					$expect = $_POST['expect_rating'];
					$overall = $_POST['overall_rating'];
					$imageName = uploadImage('fileAdvImg', "images/joiners/".$_SESSION['joiner']."/");
					$txtFeedback = trim($_POST['txtFeedback']);

					// ERROR TRAPPINGS
					if($imageName === 1){
						echo "<script>alert('An error occurred in uploading your image!')</script>";

					} else if($imageName === 2){
						echo "<script>alert('File type is not allowed!')</script>";

					} else {
						## FOR OVERALL RATING
						DB::query("INSERT INTO rating(rating_img, rating_type, rating_stars, rating_message, joiner_id, book_id) VALUES(?,?,?,?,?,?)", array($imageName, 4, $overall, $txtFeedback, $_SESSION['joiner'], $_GET['book_id']), "CREATE");
						## FOR ENGAGEMENT RATING
						DB::query("INSERT INTO rating(rating_img, rating_type, rating_stars, joiner_id, book_id) VALUES(?,?,?,?,?)", array($imageName, 1, $engage, $_SESSION['joiner'], $_GET['book_id']), "CREATE");
						## FOR QUALITY RATING
						DB::query("INSERT INTO rating(rating_img, rating_type, rating_stars, joiner_id, book_id) VALUES(?,?,?,?,?)", array($imageName, 2, $quality, $_SESSION['joiner'], $_GET['book_id']), "CREATE");
						## FOR EXPECTATION RATING
						DB::query("INSERT INTO rating(rating_img, rating_type, rating_stars, joiner_id, book_id) VALUES(?,?,?,?,?)", array($imageName, 3, $expect, $_SESSION['joiner'], $_GET['book_id']), "CREATE");

						header("Location: reports_rating.php?success");
					}
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
