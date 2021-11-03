<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

	if(isset($_POST['btnSave'])){
		updateVoucher();
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
		main .form{display:flex;justify-content:center;flex-wrap:wrap;}
		main .form input:nth-of-type(1){width:67%;}
		main .form input:nth-of-type(2){width:49%;}
		main .form input:nth-of-type(3){width:49%;}
		main .form input:nth-of-type(4){width:49%;}
		main .form input:nth-of-type(5){width:49%;}
		main .form select{width:31%;}

		/*RESPONSIVE*/
		@media only screen and (max-width:1000px) {
			main{padding:50px 0 0 25px;}
		}
		@media only screen and (max-width:500px) {
			main .edit{width:48%;}
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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; Voucher
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'voucher';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->

			<main>
				<form method="post">
					<h2>Edit Voucher</h2>
					<?php
					if(isset($_GET['id'])){
						$voucher = DB::query("SELECT * FROM voucher WHERE vouch_code=?", array($_GET['id']), "READ");

						if(count($voucher)>0){
							$voucher = $voucher[0];

					?>

					<div class="form form2">
						<input type="text" name="txtName" value="<?php echo $voucher['vouch_name']; ?>" placeholder="Name" required>
						<select name="cboAdv" required>
							<option value="">-- SELECT ADVENTURE --</option>
							<?php
							$currentDate = date('Y-m-d');
							// DISPLAY ALL ADVENTURES CREATED BY CURRENT ORGANIZER
							$adv = DB::query("SELECT * FROM adventure WHERE orga_id=? AND adv_date>? AND adv_status!='canceled' AND adv_status !='done' ORDER BY adv_date", array($_SESSION['organizer'], $currentDate), "READ");

							if(count($adv)>0){
								foreach ($adv as $result) {
									$selected = "";
									// SELECTED ADVENTURE
									if($voucher['adv_id'] == $result['adv_id'])
										$selected = "selected";
									else
										$selected = "";
									//
									echo "<option value='".$result['adv_id']."' ".$selected.">(".date("M j, Y", strtotime($result['adv_date'])).") ".$result['adv_name']." - ".$result['adv_kind']."</option>";
								}
							}
							?>
						</select>
						<input type="date" name="dateStartDate" value="<?php echo $voucher['vouch_startdate']; ?>" placeholder="Start Date" required>
						<input type="date" name="dateEndDate" value="<?php echo $voucher['vouch_enddate']; ?>" placeholder="End Date" required>
						<input type="number" name="numDiscount" value="<?php echo $voucher['vouch_discount']; ?>" placeholder="Discount" required>
						<input type="number" name="numMinSpent" value="<?php echo $voucher['vouch_minspent']; ?>" placeholder="Minimum Spent" required>
					</div>

					<?php
						}
						else {
							echo "<h3>Please select a voucher to be edited!</h3>";
						}
					}
					?>

					<!-- SHOW ALL THE ORGANIZERS LEGAL DOCUMENT -->

					<button class="edit" type="submit" name="btnSave">Save</button>
					<a class="edit" href="voucher.php">Back</a>
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
