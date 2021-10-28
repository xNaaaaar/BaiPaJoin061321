<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

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

		.reuse_data select{display:inline-block;height:50px;border:1px solid #cfcfcf;outline:none;border-radius:10px;padding:0 30px;font:normal 15px/50px Montserrat,sans-serif;}
		.reuse_data button{display:inline-block;height:50px;border:1px solid #cfcfcf;outline:none;border-radius:10px;padding:0 30px;font:normal 15px/50px Montserrat,sans-serif;}
		.reuse_data button:hover{color:#fff;background:#d1375d;border:1px solid #d1375d;}

		.card{width:100%;min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:20px;padding:30px 30px 30px 200px;line-height:35px;text-align:left;margin:15px 0;border:1px solid #cfcfcf;}
		.card figure{width:140px;height:140px;position:absolute;top:30px;left:30px;border:1px solid #1a1a1a;}
		.card figure img{width:100%;}
		.card h2{font:600 35px/100% Montserrat,sans-serif;color:#313131;margin-bottom:15px;}
		.card p{font-size:23px;color:#989898;width:100% !important;}

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
				<h2>Post an Adventure</h2>
				<form method="post" class='reuse_data'>
					<select name="cboAdv" required>
						<option value="">-- REUSE ADVENTURE DATA --</option>
					<?php
					## SELECT ALL ORGANIZER'S ADVENTURE POSTED
					$advs = DB::query("SELECT * FROM adventure WHERE orga_id=?", array($_SESSION['organizer']), "READ");
					if(count($advs)>0){
						foreach ($advs as $result) {
							echo "<option value='".$result['adv_id']."'>".$result['adv_name']."</option>";
						}
					}
					?>
					</select>
					<button type="submit" name="btnReuse">Reuse</button>
				</form>
				<?php
				## REUSE EXISTING ADVENTURE DATA
				if(isset($_POST['btnReuse'])){
					$cboAdv = $_POST['cboAdv'];
					if(!empty($cboAdv)){
						$this_adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($cboAdv), "READ");
						$this_adv = $this_adv[0];
						##
						$_SESSION['this_type'] = $this_adv['adv_type'];
						$_SESSION['this_guest'] = $this_adv['adv_maxguests'];
						$_SESSION['this_name'] = $this_adv['adv_name'];
						$_SESSION['this_kind'] = $this_adv['adv_kind'];
						$_SESSION['this_address'] = $this_adv['adv_address'];
						$_SESSION['this_date'] = $this_adv['adv_date'];
						$_SESSION['this_details'] = $this_adv['adv_details'];
						$_SESSION['this_price'] = $this_adv['adv_totalcostprice'];
					}
				}
				?>
				<form method="post" enctype="multipart/form-data">
					<div class="form form1">
						<select name="cboType" onchange="displayMaxGuests(this);" required>
							<option value="">Type of Adventure</option>
							<option value="packaged" <?php echo (isset($_SESSION['this_type']) && $_SESSION['this_type'] == "Packaged") ? "selected='selected'":""; ?>>Packaged</option>
							<option value="not packaged" <?php echo (isset($_SESSION['this_type']) && $_SESSION['this_type'] == "Not Packaged") ? "selected='selected'":""; ?>>Not Packaged</option>
						</select>
						<input type="number" name="numMaxGuests" id="display" placeholder="No. Max Guests" value="<?php echo (isset($_SESSION['this_guest'])) ? $_SESSION['this_guest']:1; ?>">
						<input type="text" name="txtName" placeholder="Adventure Name" value="<?php echo (isset($_SESSION['this_name'])) ? $_SESSION['this_name']:""; ?>" required>
						<select name="cboKind" required>
							<option value="">Activities</option>
							<option value="Swimming" <?php echo (isset($_SESSION['this_kind']) && $_SESSION['this_kind'] == "Swimming") ? "selected='selected'":""; ?>>Swimming</option>
							<option value="Camping" <?php echo (isset($_SESSION['this_kind']) && $_SESSION['this_kind'] == "Camping") ? "selected='selected'":""; ?>>Camping</option>
							<option value="Island Hopping" <?php echo (isset($_SESSION['this_kind']) && $_SESSION['this_kind'] == "Island Hopping") ? "selected='selected'":""; ?>>Island Hopping</option>
							<option value="Mountain Hiking" <?php echo (isset($_SESSION['this_kind']) && $_SESSION['this_kind'] == "Mountain Hiking") ? "selected='selected'":""; ?>>Mountain Hiking</option>
							<option value="Snorkeling" <?php echo (isset($_SESSION['this_kind']) && $_SESSION['this_kind'] == "Snorkeling") ? "selected='selected'":""; ?>>Snorkeling</option>
							<option value="Canyoneering" <?php echo (isset($_SESSION['this_kind']) && $_SESSION['this_kind'] == "Canyoneering") ? "selected='selected'":""; ?>>Canyoneering</option>
							<option value="Biking" <?php echo (isset($_SESSION['this_kind']) && $_SESSION['this_kind'] == "Biking") ? "selected='selected'":""; ?>>Biking</option>
							<option value="Diving" <?php echo (isset($_SESSION['this_kind']) && $_SESSION['this_kind'] == "Diving") ? "selected='selected'":""; ?>>Diving</option>
							<option value="Jetski" <?php echo (isset($_SESSION['this_kind']) && $_SESSION['this_kind'] == "Jetski") ? "selected='selected'":""; ?>>Jetski</option>
							<option value="Banana Boat" <?php echo (isset($_SESSION['this_kind']) && $_SESSION['this_kind'] == "Banana Boat") ? "selected='selected'":""; ?>>Banana Boat</option>
						</select>
						<!-- <input type="text" name="txtAddress" placeholder="Address" required> -->
						<select name="cboLoc" required>
							<option value="">Location</option>
							<option value="Bantayan Island" <?php echo (isset($_SESSION['this_address']) && $_SESSION['this_address'] == "Bantayan Island") ? "selected='selected'":""; ?>>Bantayan Island</option>
							<option value="Malapascua Island" <?php echo (isset($_SESSION['this_address']) && $_SESSION['this_address'] == "Malapascua Island") ? "selected='selected'":""; ?>>Malapascua Island</option>
							<option value="Camotes Island" <?php echo (isset($_SESSION['this_address']) && $_SESSION['this_address'] == "Camotes Island") ? "selected='selected'":""; ?>>Camotes Island</option>
							<option value="Moalboal" <?php echo (isset($_SESSION['this_address']) && $_SESSION['this_address'] == "Moalboal") ? "selected='selected'":""; ?>>Moalboal</option>
							<option value="Badian" <?php echo (isset($_SESSION['this_address']) && $_SESSION['this_address'] == "Badian") ? "selected='selected'":""; ?>>Badian</option>
							<option value="Oslob" <?php echo (isset($_SESSION['this_address']) && $_SESSION['this_address'] == "Oslob") ? "selected='selected'":""; ?>>Oslob</option>
							<option value="Alcoy" <?php echo (isset($_SESSION['this_address']) && $_SESSION['this_address'] == "Alcoy") ? "selected='selected'":""; ?>>Alcoy</option>
							<option value="Aloguinsan" <?php echo (isset($_SESSION['this_address']) && $_SESSION['this_address'] == "Aloguinsan") ? "selected='selected'":""; ?>>Aloguinsan</option>
							<option value="Santander" <?php echo (isset($_SESSION['this_address']) && $_SESSION['this_address'] == "Santander") ? "selected='selected'":""; ?>>Santander</option>
							<option value="Alegria" <?php echo (isset($_SESSION['this_address']) && $_SESSION['this_address'] == "Alegria") ? "selected='selected'":""; ?>>Alegria</option>
							<option value="Dalaguete" <?php echo (isset($_SESSION['this_address']) && $_SESSION['this_address'] == "Dalaguete") ? "selected='selected'":""; ?>>Dalaguete</option>
						</select>
						<input type="date" name="dateDate" placeholder="Date" value="<?php echo (isset($_SESSION['this_date'])) ? $_SESSION['this_date']:""; ?>" required>
						<div class="label">
							<label for="f01">Add a maximum of 4 adventure images</label>
						</div>
						<input id="f01" type="file" name="fileAdvImgs[]" placeholder="Add Adventure Images" multiple required/>
						<textarea name="txtDetails" placeholder="Details" required><?php echo (isset($_SESSION['this_details'])) ? $_SESSION['this_details']:""; ?></textarea>
						<div class="label">
							<label for="f02">Add Itinerary Image</label>
						</div>
						<input id="f02" type="file" name="fileItineraryImg" required/>
						<div class="label">
							<label for="f03">Add Do's & Dont's Image</label>
						</div>
						<input id="f03" type="file" name="fileDosDontsImg" required/>
						<input type="num" name="numPrice" placeholder="Total Price" value="<?php echo (isset($_SESSION['this_price'])) ? $_SESSION['this_price']:""; ?>" required>
					</div>
					<div class="price_details">
						<h2 style='color:red;'>Important Notes:</h2>
						<ul>
							<li>When posting an adventure, the organizer agrees to provide a tour or service to joiner/s whose booking is under the posted adventure. The organizer is responsible for the joiner/s by the time the adventure is underway or if the joiner is under the jurisdiction of the organizers premises.</li>
							<li>Fees and other charges includes transaction fee, service fee, environmental fee and government sanction charges imposed by the LGU or a National Government should not & is not include on invidual posted in the site or app. </li>
							<li>All successful posted adventure can be deleted for FREE without any approval as long as there is/are no bookings made for the adventure. The organizer can edit and update the adventure to a different date or alter the price for FREE without any approval as long as there is/are no bookings made for the adventure.</li>
							<li>All successful posted adventure can be canceled subject to approval if there is/are bookings made for the adventure. The organizer can file for a cancelation request provided clear documentation as to why it needs to be cancelled. If the organizer wanted to cancel and reschedule the adventure at the same time, then the current booking made to the adventure is given an option for refund or book for an identical adventure on a different date.</li>
							<li>All successful adventures will tag as done, once the adventure is considered as done then the organizer may file for a payout request within the site or app. Once a payment request is submitted then the organizer will submit the preferred payout method thru email. All payout request is subject to 5% service and transaction fee including other charges as it deems fit. </li>
							<li>Upon posting an adventure you've read and agree to the important notes mentioned above and to the terms and condition set by BaiPaJoin. You may read the <a href="terms.php" target="_blank">terms and conditions</a> of this website.</li>
						</ul>
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
