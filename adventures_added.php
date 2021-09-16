<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(isset($_POST['btnPost'])){
		postAdventure();
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
		.main_con{display:flex;justify-content:space-between;}

		.sidebar{flex:1;height:500px;padding:30px 30px 30px 0;position:relative;}
		.sidebar:before{content:'';width:2px;height:70%;background:#cdcdcd;position:absolute;top:50%;right:0;transform:translateY(-50%);}
		.sidebar h2{font-size:30px;line-height:100%;}
		.sidebar p{margin-bottom:35px;}
		.sidebar ul{display:flex;height:100%;flex-direction:column;justify-content:flex-start;font:600 30px/100% Montserrat,sans-serif;list-style:none;}
		.sidebar ul li{line-height:45px;}
		.sidebar ul li i{width:40px;position:relative;}
		.sidebar ul li i:before{position:absolute;top:-25px;left:50%;transform:translateX(-50%);}
		.sidebar ul li:last-child{margin:auto 0;}
		.sidebar ul li a{color:#454545;position:relative;}
		.sidebar ul li a small{color:#fff;font-size:15px;position:absolute;top:0;right:-20px;background:#bf127a;height:25px;width:25px;text-align:center;border-radius:50px;line-height:25px;}
		.sidebar ul li a:hover{color:#bf127a;}

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
		main .form{display:flex;justify-content:center;flex-wrap:wrap;margin-bottom:40px;position:relative;}
		main .form input, main .form select, main .form textarea{display:inline-block;width:99%;height:60px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 20px/20px Montserrat,sans-serif;padding:0 30px;margin:15px auto;border:1px solid #cfcfcf;}
		main .form select{width:73%;}
		main .form select:nth-of-type(2){width:49%;}
		main .form select:nth-of-type(3){width:73%;}
		main .form textarea{resize:none;height:180px;border-radius:25px;padding-top:25px;}
		main .form input:first-of-type{width:25%;opacity:0;}
		main .form input:nth-of-type(2){width:49%;}
		main .form input:nth-of-type(3){width:25%;}
		main .form input[type=file]{padding-top:13px;color:#b1b1b1;margin-top:0;}
		main .form .label{font-size:15px;display:inline-block;text-align:left;width:99%;margin:5px 0 0;}

		.card{width:100%;min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:20px;padding:30px 30px 30px 200px;line-height:35px;text-align:left;margin:15px 0;border:1px solid #cfcfcf;}
		.card figure{width:140px;height:140px;position:absolute;top:30px;left:30px;border:1px solid #1a1a1a;}
		.card figure img{width:100%;}
		.card h2{font:600 35px/100% Montserrat,sans-serif;color:#313131;margin-bottom:15px;}
		.card p{font-size:23px;color:#989898;width:100% !important;}

		main .edit{display:inline-block;width:209px;height:60px;background:#bf127a;border-radius:50px;color:#fff;margin:15px 5px;text-align:center;font:normal 20px/59px Montserrat,sans-serif;border:none;}
		main .edit:hover{background:#8c0047;text-decoration:none;color:#fff;}

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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; Adventures
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'adventures';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->

			<main>
				<form method="post" enctype="multipart/form-data">
					<h2>Post an Adventure</h2>

					<div class="form form1">
						<select name="cboType" onchange="displayMaxGuests(this);" required>
							<option value="">Type of Adventure</option>
							<option value="packaged">Packaged</option>
							<option value="not packaged">Not Packaged</option>
						</select>
						<input type="number" name="numMaxGuests" id="display" placeholder="No. Max Guests" value="1">
						<input type="text" name="txtName" placeholder="Adventure Name" required>
						<select name="cboKind" required>
							<option value="">Activities</option>
							<option value="Packaged">Packaged</option>
							<option value="Swimming">Swimming</option>
							<option value="Camping">Camping</option>
							<option value="Island Hopping">Island Hopping</option>
							<option value="Mountain Hiking">Mountain Hiking</option>
							<option value="Snorkeling">Snorkeling</option>
							<option value="Canyoneering">Canyoneering</option>
							<option value="Biking">Biking</option>
							<option value="Diving">Diving</option>
							<option value="Jetski">Jetski</option>
							<option value="Banana Boat">Banana Boat</option>
						</select>
						<!-- <input type="text" name="txtAddress" placeholder="Address" required> -->
						<select name="cboLoc" required>
							<option value="">Location</option>
							<option value="Bantayan Island">Bantayan Island</option>
							<option value="Malapascua Island">Malapascua Island</option>
							<option value="Camotes Island">Camotes Island</option>
							<option value="Moalboal">Moalboal</option>
							<option value="Badian">Badian</option>
							<option value="Oslob">Oslob</option>
							<option value="Alcoy">Alcoy</option>
							<option value="Aloguinsan">Aloguinsan</option>
							<option value="Santander">Santander</option>
							<option value="Alegria">Alegria</option>
							<option value="Dalaguete">Dalaguete</option>
						</select>
						<input type="date" name="dateDate" placeholder="Date" required>
						<div class="label">
							<label for="f01">Add a maximum of 4 adventure images</label>
						</div>
						<input id="f01" type="file" name="fileAdvImgs[]" placeholder="Add Adventure Images" multiple required/>
						<textarea name="txtDetails" placeholder="Details" required></textarea>
						<div class="label">
							<label for="f02">Add itinerary image</label>
						</div>
						<input id="f02" type="file" name="fileItineraryImg" placeholder="Add Itinerary Image" required/>
						<input type="num" name="numPrice" placeholder="Total Price" required>
					</div>

					<button class="edit" type="submit" name="btnPost">Post</button>
					<a class="edit" href="adventures_posted.php">Back</a>
				</form>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
