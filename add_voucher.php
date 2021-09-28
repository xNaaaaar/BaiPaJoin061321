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
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
		main .form{display:flex;justify-content:center;flex-wrap:wrap;margin-bottom:40px;position:relative;}
		main .form input, main .form select{display:inline-block;width:67%;height:60px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 20px/20px Montserrat,sans-serif;padding:0 30px;margin:15px auto;border:1px solid #cfcfcf;}
		main .form input:nth-of-type(2){width:49%;}
		main .form input:nth-of-type(3){width:49%;}
		main .form input:nth-of-type(4){width:49%;}
		main .form input:nth-of-type(5){width:49%;}
		main .form select{width:31.5%;}

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
					<h3>Note: When creating a voucher, it will ONLY apply to a specific adventure you selected. A joiner can apply a voucher if it falls along the start and end dates.</h3>
					<div class="form form2">
						<input type="text" name="txtName" value="" placeholder="Name" required>
						<select name="cboAdv" required>
							<option>-- SELECT ADVENTURE --</option>
							<?php
							// DISPLAY ALL ADVENTURES CREATED BY CURRENT ORGANIZER
							$adv = DB::query("SELECT * FROM adventure WHERE orga_id=?", array($_SESSION['organizer']), "READ");

							if(count($adv)>0){
								foreach ($adv as $result) {
									echo "<option value='".$result['adv_id']."'>".$result['adv_name']."</option>";
								}
							}
							?>
						</select>
						<input type="text" name="dateStartDate" placeholder="Start Date" onfocus="(this.type='date')" required>
						<input type="text" name="dateEndDate" placeholder="End Date" onfocus="(this.type='date')" required>
						<input type="number" name="numDiscount" placeholder="Discount" required>
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
