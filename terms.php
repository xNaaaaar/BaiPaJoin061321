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
		main h2{}

		.place_info{margin:0;}
		.main_info{width:100%;padding:0;}
		.main_info h1{font:600 50px/100% Montserrat,sans-serif;margin:0 0 25px;}
		.main_info h2{font:500 40px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
		.main_info p{font:400 18px/30px Montserrat,sans-serif;}
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
			<a href="index.php">Home</a> &#187; <a href="adventures.php">Adventures</a> &#187; Terms and Conditions
		</div>
		<div class="main_con">

			<main>
				<div class="place_info">
					<div class="main_info">
						<h1>Terms and Conditions</h1>
						<section>
							<h2>Lorem Ipsum</h2>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec porttitor velit eget turpis varius aliquet. Aenean in sem in ex finibus tempus. Vestibulum ornare posuere metus, vehicula dapibus velit vehicula non. Sed pellentesque est at velit lobortis, in consequat lectus laoreet. Aenean tristique elit vitae turpis ornare luctus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nulla dignissim ullamcorper metus, sit amet ultricies dolor viverra sit amet. Sed enim mi, porttitor quis malesuada ornare, elementum et lorem. Integer sem orci, hendrerit ac nisi quis, eleifend tristique mauris. Ut tempus varius massa, nec varius nisl congue in. Etiam sagittis nisl quis tellus vehicula pretium. Etiam ultricies mi vel orci dignissim, non hendrerit augue aliquam. Quisque eu ultricies libero. Nulla facilisi. </p>
						</section>
						<section>
							<h2>Lorem Ipsum</h2>
							<p>Sed consequat felis eget ante elementum, lacinia vehicula diam lobortis. Etiam in tellus nec lacus lobortis maximus. In hac habitasse platea dictumst. Donec et fermentum tellus, ac auctor felis. Proin mollis efficitur purus nec elementum. Vestibulum venenatis, ex eget faucibus interdum, magna velit viverra felis, sed ultrices nunc ligula at dui. Curabitur fringilla purus gravida velit congue sagittis. In viverra felis nec nulla commodo, in dictum felis tempus. </p>
						</section>
						<section>
							<h2>Lorem Ipsum</h2>
							<p>Fusce dapibus a sem nec maximus. Ut condimentum sapien id libero sollicitudin suscipit. Morbi sapien neque, tristique nec pharetra ut, sollicitudin quis massa. Phasellus sit amet tempus neque, vestibulum porttitor tellus. Nullam ac magna augue. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Suspendisse venenatis consequat dolor ac faucibus. Maecenas tristique molestie arcu, sit amet cursus lacus bibendum sed. Donec sit amet orci id lacus tincidunt efficitur. Nulla facilisi. Sed non risus porttitor, tempor lectus vitae, ornare eros. </p>
						</section>
						<section>
							<h2>Lorem Ipsum</h2>
							<p>In in lacinia est. Pellentesque pulvinar libero vel metus tempus, at suscipit arcu suscipit. Quisque rutrum elementum eros, ut luctus orci sodales et. Nunc mattis pulvinar aliquam. Vestibulum bibendum cursus ante, sed varius neque suscipit ac. Aliquam quis vulputate neque, eu posuere nibh. Nunc facilisis nunc sed est euismod placerat. Nam lacinia, est ac blandit rutrum, lacus sem commodo lorem, vitae sodales nisi ipsum id dui. Quisque mollis, libero vitae semper scelerisque, sapien nunc aliquet ante, semper fermentum lacus risus sed purus. Fusce commodo, purus ac suscipit fermentum, mi mi auctor turpis, at volutpat augue sem eget felis. Aenean pulvinar ante quis ex luctus, eu elementum risus aliquet. Nulla nisl orci, ultricies sit amet velit ac, elementum vestibulum odio. Curabitur et ipsum urna. Suspendisse fermentum dui neque, sit amet elementum purus fermentum vel. </p>
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
