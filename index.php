<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(isset($_GET['login']) && isset($_GET['login']) == 1){
		echo "<script>alert('Login successfully!')</script>";
	}
?>

<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->

</head>
	<body>
		<div class="protect-me">
		<div class="clearfix">

<!-- Header -->
<?php include("includes/header.php"); ?>
<!-- End of Header -->

<!-- Navigation -->
<?php
	$currentPage = 'index';
	include("includes/nav.php");
?>
<!-- End of Navigation -->

<!-- Banner -->
<?php include("includes/banner.php"); ?>
<!-- End Banner -->

<!-- Middle -->
<?php include("includes/middle.php"); ?>
<!-- End Middle -->

<!-- Main -->
<div id="main_area">
	<div class="wrapper">
		<div class="main_con">
			<div class="main_images">
				<figure class="main_img1 wow fadeInLeft" data-wow-duration="1s">
					<img src="images/main_img1.png" alt="caretaker helping patient">
				</figure>
				<figure class="main_img2 wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".5s">
					<img src="images/main_img2.png" alt="caretaker talking to patient">
				</figure>
			</div>

			<main>
				<h1 class="h1_title">BaiPaJoin: <span>An Online Joiner Platform for Tourists</span></h1>
				<p>We are using these temporary contents on the website.  These dummy texts are for display purposes only to show the volume of content that will be placed on this particular page. We have reserved this space to display more information about the company.</p>
				<p>In the near future, these text will be replaced with more details and different ways to contact the business.</p>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!-- Bottom -->
<?php include("includes/bottom.php"); ?>
<!-- End Bottom -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
