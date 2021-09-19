<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(isset($_POST['btnSave'])){
		updateAdventure();
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
					<h2>Edit an Adventure</h2>
					<?php
						if(isset($_GET['id'])){
							$adv = DB::query("SELECT * FROM adventure WHERE adv_id = ?", array($_GET['id']), "READ");

							if(count($adv) > 0){
								$adv = $adv[0];
					?>

					<div class="form form1">
						<select name="cboType" onchange="displayMaxGuests(this);" required>
							<option value="">Type of Adventure</option>
							<option value="packaged" <?php echo $adv['adv_type'] == "Packaged" ? "selected='selected'":""; ?>>Packaged</option>
							<option value="not packaged" <?php echo $adv['adv_type'] == "Not packaged" ? "selected='selected'":""; ?>>Not Packaged</option>
						</select>
						<input type="number" name="numMaxGuests" id="display" placeholder="No. Max Guests" value="<?php echo $adv['adv_maxguests']; ?>">
						<input type="text" name="txtName" placeholder="Adventure Name" value="<?php echo $adv['adv_name']; ?>" required>
						<select name="cboKind" required>
							<option value="">Activities</option>
							<option value="Packaged" <?php echo $adv['adv_kind'] == "Packaged" ? "selected='selected'":""; ?>>Packaged</option>
							<option value="Swimming" <?php echo $adv['adv_kind'] == "Swimming" ? "selected='selected'":""; ?>>Swimming</option>
							<option value="Camping" <?php echo $adv['adv_kind'] == "Camping" ? "selected='selected'":""; ?>>Camping</option>
							<option value="Island Hopping" <?php echo $adv['adv_kind'] == "Island Hopping" ? "selected='selected'":""; ?>>Island Hopping</option>
							<option value="Mountain Hiking" <?php echo $adv['adv_kind'] == "Mountain Hiking" ? "selected='selected'":""; ?>>Mountain Hiking</option>
							<option value="Snorkeling" <?php echo $adv['adv_kind'] == "Snorkeling" ? "selected='selected'":""; ?>>Snorkeling</option>
							<option value="Canyoneering" <?php echo $adv['adv_kind'] == "Canyoneering" ? "selected='selected'":""; ?>>Canyoneering</option>
							<option value="Biking" <?php echo $adv['adv_kind'] == "Biking" ? "selected='selected'":""; ?>>Biking</option>
							<option value="Scuba Diving" <?php echo $adv['adv_kind'] == "Scuba Diving" ? "selected='selected'":""; ?>>Scuba Diving</option>
							<option value="Free Diving" <?php echo $adv['adv_kind'] == "Free Diving" ? "selected='selected'":""; ?>>Free Diving</option>
							<option value="Jetski" <?php echo $adv['adv_kind'] == "Jetski" ? "selected='selected'":""; ?>>Jetski</option>
							<option value="Banana Boat" <?php echo $adv['adv_kind'] == "Banana Boat" ? "selected='selected'":""; ?>>Banana Boat</option>
						</select>
						<select name="cboLoc" required>
							<option value="">Location</option>
							<option value="Bantayan Island" <?php echo $adv['adv_address'] == "Bantayan Island" ? "selected='selected'":""; ?>>Bantayan Island</option>
							<option value="Malapascua Island" <?php echo $adv['adv_address'] == "Malapascua Island" ? "selected='selected'":""; ?>>Malapascua Island</option>
							<option value="Camotes Island" <?php echo $adv['adv_address'] == "Camotes Island" ? "selected='selected'":""; ?>>Camotes Island</option>
							<option value="Moalboal" <?php echo $adv['adv_address'] == "Moalboal" ? "selected='selected'":""; ?>>Moalboal</option>
							<option value="Badian" <?php echo $adv['adv_address'] == "Badian" ? "selected='selected'":""; ?>>Badian</option>
							<option value="Oslob" <?php echo $adv['adv_address'] == "Oslob" ? "selected='selected'":""; ?>>Oslob</option>
							<option value="Alcoy" <?php echo $adv['adv_address'] == "Alcoy" ? "selected='selected'":""; ?>>Alcoy</option>
							<option value="Aloguinsan" <?php echo $adv['adv_address'] == "Aloguinsan" ? "selected='selected'":""; ?>>Aloguinsan</option>
							<option value="Santander" <?php echo $adv['adv_address'] == "Santander" ? "selected='selected'":""; ?>>Santander</option>
							<option value="Alegria" <?php echo $adv['adv_address'] == "Alegria" ? "selected='selected'":""; ?>>Alegria</option>
							<option value="Dalaguete" <?php echo $adv['adv_address'] == "Dalaguete" ? "selected='selected'":""; ?>>Dalaguete</option>
						</select>
						<input type="date" name="dateDate" placeholder="Date" value="<?php echo $adv['adv_date']; ?>" required>
						<div class="label">
							<label for="f01">Add a maximum of 4 adventure images</label>
						</div>
						<input id="f01" type="file" name="fileAdvImgs[]" placeholder="Add Adventure Images" multiple required/>
						<textarea name="txtDetails" placeholder="Details" required><?php echo $adv['adv_details']; ?></textarea>
						<div class="label">
							<label for="f02">Add itinerary image</label>
						</div>
						<input id="f02" type="file" name="fileItineraryImg" placeholder="Add Itinerary Image" required/>
						<input type="num" name="numPrice" placeholder="Total Price" value="<?php echo $adv['adv_totalcostprice']; ?>" required>
					</div>

					<?php
						}
					}
					?>

					<button class="edit" type="submit" name="btnSave">Save</button>
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
