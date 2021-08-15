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
		.main_con form{display:flex;justify-content:space-between;}

		.sidebar{flex:1;min-height:529px;padding:30px 30px 30px 0;position:relative;}
		.sidebar:before{content:'';width:2px;height:50%;background:#cdcdcd;position:absolute;top:50%;right:0;transform:translateY(-50%);}
		.sidebar h2{font-size:30px;line-height:100%;}
		.sidebar h2 i{color:#00c851;}
		/* .sidebar h2 i:nth-child(2){color:#33b5e5;}
		.sidebar h2 i:last-child{color:#ff4444;} */
		.sidebar p{margin-bottom:35px;}
		.sidebar p q{color:#00c851;}
		.sidebar ul{display:flex;height:100%;flex-direction:column;justify-content:space-between;font:600 20px/100% Montserrat,sans-serif;list-style:none;}
		.sidebar .places li input, .sidebar .activites li input{width:20px;height:20px;}
		.sidebar .prices li input{width:90%;height:30px;font-size:18px;}
		.sidebar ul h3{font-size:25px;margin-top:15px;}
		.sidebar ul li{line-height:35px;}
		.sidebar ul li label{font-weight:400;}
		.sidebar button{display:inline-block;width:200px;height:50px;background:#bf127a;border-radius:50px;color:#fff;margin:15px 5px;text-align:center;font:normal 20px/45px Montserrat,sans-serif;border:none;}
		.sidebar button:hover{background:#8c0047;}

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
		main h2{font:600 59px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}

		.card{width:100%;min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:20px;padding:30px 125px 30px 215px;line-height:35px;text-align:left;margin:25px auto;border:1px solid #cfcfcf;}
		.card:hover{border:1px solid #bf127a;}
		.card a{color:#313131}
		.card a:hover{color:#313131;text-decoration:none;}
		.card figure{width:165px;height:165px;position:absolute;top:30px;left:30px;border:1px solid #1a1a1a;}
		.card figure img{width:100%;}
		.card small{position:absolute;top:20px;right:20px;font-size:35px;}
		.card small:hover{cursor:pointer;color:#bf127a;}
		.card h2{font:600 35px/100% Montserrat,sans-serif;color:#313131;margin-bottom:15px;}
		.card h2 span{display:block;font-size:18px;color:gray;}
		.card h2 span i{color:#ffac33;}
		.card p{font-size:23px;color:#989898;width:100% !important;margin:0 0 10px 2px;}
		.card p:last-of-type{color:#111;font-size:30px;font-weight:500;margin:0 0 0 2px;}
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
			<a href="index.php">Home</a> &#187; Adventures
		</div>
		<div class="main_con">
			<form method="post">
			<aside class="sidebar">
				<?php
				if($_SESSION['current_user'] == 'Joiner') {
					currentJoiner($_SESSION['joiner']);
					//
					echo "<h2> {$_SESSION['fname']} {$_SESSION['lname']} </h2>
					<p>User Type: Joiner</p>";
				}
				else {
					echo "<h2>Welcome guests!</h2>";
				}
				?>

				<div class="filters">
					<ul class="places">
						<li><h3>Places</h3></li>
						<li><input class="checkbox1" type="checkbox" name="places[]" required value="Bantayan Island" <?php checkPlaces("Bantayan Island"); ?>> <label for="">Bantayan Island</label></li>
						<li><input class="checkbox1" type="checkbox" name="places[]" required value="Malapascua Island" <?php checkPlaces("Malapascua Island"); ?>> <label for="">Malapascua Island</label></li>
						<li><input class="checkbox1" type="checkbox" name="places[]" required value="Camotes Island" <?php checkPlaces("Camotes Island"); ?>> <label for="">Camotes Island</label></li>
						<li><input class="checkbox1" type="checkbox" name="places[]" required value="Moalboal" <?php checkPlaces("Moalboal"); ?>> <label for="">Moalboal</label></li>
						<li><input class="checkbox1" type="checkbox" name="places[]" required value="Badian" <?php checkPlaces("Badian"); ?>> <label for="">Badian</label></li>
						<li><input class="checkbox1" type="checkbox" name="places[]" required value="Oslob" <?php checkPlaces("Oslob"); ?>> <label for="">Oslob</label></li>
						<li><input class="checkbox1" type="checkbox" name="places[]" required value="Alcoy" <?php checkPlaces("Alcoy"); ?>> <label for="">Alcoy</label></li>
						<li><input class="checkbox1" type="checkbox" name="places[]" required value="Aloginsan" <?php checkPlaces("Aloginsan"); ?>> <label for="">Aloginsan</label></li>
						<li><input class="checkbox1" type="checkbox" name="places[]" required value="Santander" <?php checkPlaces("Santander"); ?>> <label for="">Santander</label></li>
						<li><input class="checkbox1" type="checkbox" name="places[]" required value="Alegria" <?php checkPlaces("Alegria"); ?>> <label for="">Alegria</label></li>
						<li><input class="checkbox1" type="checkbox" name="places[]" required value="Dalaguete" <?php checkPlaces("Dalaguete"); ?>> <label for="">Dalaguete</label></li>
					</ul>
					<ul class="activites">
						<li><h3>Activities</h3></li>
						<li><input class="checkboxes" type="checkbox" name="activities[]" required value="Packaged" <?php checkActivities("Packaged") ?>> <label for="">Packaged</label></li>
						<li><input class="checkboxes" type="checkbox" name="activities[]" required value="Swimming" <?php checkActivities("Swimming") ?>> <label for="">Swimming</label></li>
						<li><input class="checkboxes" type="checkbox" name="activities[]" required value="Camping" <?php checkActivities("Camping") ?>> <label for="">Camping</label></li>
						<li><input class="checkboxes" type="checkbox" name="activities[]" required value="Island Hopping" <?php checkActivities("Island Hopping") ?>> <label for="">Island Hopping</label></li>
						<li><input class="checkboxes" type="checkbox" name="activities[]" required value="Mountain Hiking" <?php checkActivities("Mountain Hiking") ?>> <label for="">Mountain Hiking</label></li>
						<li><input class="checkboxes" type="checkbox" name="activities[]" required value="Snorkeling" <?php checkActivities("Snorkeling") ?>> <label for="">Snorkeling</label></li>
						<li><input class="checkboxes" type="checkbox" name="activities[]" required value="Canyoneering" <?php checkActivities("Canyoneering") ?>> <label for="">Canyoneering</label></li>
						<li><input class="checkboxes" type="checkbox" name="activities[]" required value="Biking" <?php checkActivities("Biking") ?>> <label for="">Biking</label></li>
						<li><input class="checkboxes" type="checkbox" name="activities[]" required value="Diving" <?php checkActivities("Diving") ?>> <label for="">Diving</label></li>
						<li><input class="checkboxes" type="checkbox" name="activities[]" required value="Jetski" <?php checkActivities("Jetski") ?>> <label for="">Jetski</label></li>
						<li><input class="checkboxes" type="checkbox" name="activities[]" required value="Banana Boat" <?php checkActivities("Banana Boat") ?>> <label for="">Banana Boat</label></li>
					</ul>
					<ul class="prices">
						<li><h3>Prices</h3></li>
						<li><input type="number" name="" placeholder="Minimum price"></li>
						<li><input type="number" name="" placeholder="Maximum price"></li>
					</ul>
					<button type="button" name="button">Save Changes</button>
				</div>

			</aside>

			<main>
					<h2>Adventures</h2>

					<div class="card">
						<a href="#">
							<figure>
								<img src="" alt="image">
							</figure>
							<small><i class="fas fa-bookmark"></i></small>
							<h2>Coastal Rock Resort - Swimming <span>5 <i class="fas fa-star"></i> (25 reviews) - 10 guests remaining</span> </h2>
							<p>Alcoy, Cebu</p>
							<p>₱ 1380 / guest</p>
						</a>
					</div>

					<div class="card">
						<a href="#">
							<figure>
								<img src="" alt="image">
							</figure>
							<small><i class="fas fa-bookmark"></i></small>
							<h2>Coastal Rock Resort - Swimming <span>5 <i class="fas fa-star"></i> (25 reviews) - 10 guests remaining</span> </h2>
							<p>Alcoy, Cebu</p>
							<p>₱ 1380 / guest</p>
						</a>
					</div>

					<div class="card">
						<a href="#">
							<figure>
								<img src="" alt="image">
							</figure>
							<small><i class="fas fa-bookmark"></i></small>
							<h2>Coastal Rock Resort - Swimming <span>5 <i class="fas fa-star"></i> (25 reviews) - 10 guests remaining</span> </h2>
							<p>Alcoy, Cebu</p>
							<p>₱ 1380 / guest</p>
						</a>
					</div>
			</main>
			</form>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
