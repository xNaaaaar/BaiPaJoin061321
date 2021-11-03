<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	// REDIRECT IF NOT LOGGED IN
  if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

	if(isset($_POST['btnAdd'])){
		addVoucher();
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
					<h2>Add Voucher</h2>
					<h3>Note: Voucher will only apply to a specific adventure you selected.</h3>
					<div class="form form2">
						<input type="text" name="txtName" value="" placeholder="Name" required>
						<select name="cboAdv" required>
							<option value="">-- SELECT ADVENTURE --</option>
							<?php
							$currentDate = date('Y-m-d');
							// DISPLAY ALL ADVENTURES CREATED BY CURRENT ORGANIZER
							$adv = DB::query("SELECT * FROM adventure WHERE orga_id=? AND adv_date>? AND adv_status!='canceled' AND adv_status !='done' ORDER BY adv_date", array($_SESSION['organizer'], $currentDate), "READ");

							if(count($adv)>0){
								foreach ($adv as $result) {
									echo "<option value='".$result['adv_id']."'>(".date("M j, Y", strtotime($result['adv_date'])).") ".$result['adv_name']." - ".$result['adv_kind']."</option>";
								}
							}
							?>
						</select>
						<input type="text" name="dateStartDate" placeholder="Start Date" onfocus="(this.type='date')" required>
						<input type="text" name="dateEndDate" placeholder="End Date" onfocus="(this.type='date')" required>
						<input type="number" name="numDiscount" placeholder="Discount" max="100" required>
						<input type="number" name="numMinSpent" placeholder="Minimum Spent" required>
					</div>

					<button class="edit" type="submit" name="btnAdd">Add</button>
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
