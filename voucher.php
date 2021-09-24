<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	// REDIRECT IF NOT LOGGED IN
  if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

	// IF ADVENTURE IS UPDATED SUCCESSFULLY
	if(isset($_GET['updated']) && $_GET['updated'] == 1){
		echo "<script>alert('Voucher successfully updated!')</script>";
	}
	// IF ADVENTURE IS ADDED SUCCESSFULLY
	if(isset($_GET['added']) && $_GET['added'] == 1){
		echo "<script>alert('Voucher successfully added!')</script>";
	}
	// IF ADVENTURE IS DELETED SUCCESSFULLY
	if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
		echo "<script>alert('Voucher successfully deleted!')</script>";
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
		main h2, main h3{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
		main h2 span{font-size:30px;}
		main h2 span a:hover{color:#313131;text-decoration:none;}
		main h3{font-size:20px;color:red;}
		main input{display:inline-block;width:99%;height:60px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 20px/20px Montserrat,sans-serif;padding:0 110px 0 30px;margin:15px auto;border:1px solid #cfcfcf;}
		main form{position:relative;}
		main button:first-of-type{right:67px;}
		main button{display:block;width:45px;height:45px;border:none;background:#bf127a;border-radius:50px;color:#fff;position:absolute;top:50%;right:15px;transform:translateY(-50%);z-index:5;font-size:20px;}
		main button:hover{background:#8c0047;}

		.card-div{display:flex;justify-content:center;flex-wrap:wrap;}
		.card{width:48%;min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:20px;padding:30px 100px 30px 175px;line-height:35px;text-align:left;margin:25px auto 0;border:1px solid #cfcfcf;overflow:hidden;}
		.card:before{content:'';width:1px;height:85%;border:none;border-left:5px dotted #cfcfcf;position:absolute;top:50%;left:30%;transform:translateY(-50%);}
		.card:hover{border:1px solid #bf127a;margin:20px auto 0;}
		.card:hover:before{border-left:5px dotted #bf127a;}
		.card .expired{width:65%;height:100%;top:-5px;left:50%;transform:translateX(-50%);z-index:5;}
		.card figure{width:115px;height:115px;position:absolute;top:50%;left:20px;transform:translateY(-50%);}
		.card figure img{width:100%;height:100%;}
		.card ul{position:absolute;top:20px;right:20px;font-size:30px;}
		.card ul li{display:inline-block;margin:0;}
		.card ul li a{color:#313131;}
		.card ul li a:hover{color:#bf127a;cursor:pointer;}
		.card h2{font:600 35px/100% Montserrat,sans-serif;color:#313131;margin-bottom:15px;}
		.card h2 span{display:block;font-size:18px;color:gray;line-height:100%;}
		.card h2 span i{color:#ffac33;}
		.card p{font-size:23px;color:#989898;width:100% !important;margin:0 0 10px 2px;}
		.card p:last-of-type{color:#111;font-size:25px;font-weight:500;margin:0 0 0 2px;}
		.card p q{display:block;}

		/* main .btn{display:inline-block;width:249px;height:60px;background:#bf127a;border-radius:50px;color:#fff;margin:40px 5px;text-align:center;font:normal 20px/59px Montserrat,sans-serif;}
		main .btn:hover{background:#8c0047;text-decoration:none;color:#fff;} */

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
				<h2>Vouchers <span>
					<?php
						// CHECK IF ORGANIZER IS VERIFIED TO ADD VOUCHER
						if(isset($_SESSION['organizer'])){
							if($_SESSION['verified'] == 1)
								echo "<a href='add_voucher.php'><i class='fas fa-plus-circle'></i></a>";
							else
								echo "<a class='disable'><i class='fas fa-plus-circle'></i></a>";
						}
					?>
				</span> </h2>
				<form method="post">
					<input type="text" name="txtSearch" placeholder="Search any...">
					<button type="submit" name="btnSearch"><i class="fas fa-search"></i></button>
					<button type="submit" name="btnRestart"><i class="fas fa-undo-alt"></i></button>
				</form>
					<!-- FOR ORGANIZER -->
					<?php if(isset($_SESSION['organizer'])){ ?>
					<div class="card-div">
						<?php
						// DISPLAY ALL VOUCHER ADDED BY SPECIFIC ORGANIZER
						if(isset($_POST['btnSearch'])){
							$txtSearch = trim(ucwords($_POST['txtSearch']));

							$card = DB::query("SELECT * FROM voucher WHERE vouch_discount LIKE '%{$txtSearch}%' || vouch_name LIKE '%{$txtSearch}%' || vouch_startdate LIKE '%{$txtSearch}%' || vouch_enddate LIKE '%{$txtSearch}%' || vouch_minspent LIKE '%{$txtSearch}%' AND orga_id = ?", array($_SESSION['organizer']), "READ");

							displayAll(2, $card);
						}
						else if(isset($_POST['btnRestart'])){
							displayAll(2);
						}
						else
							displayAll(2);
						?>
					</div>
					<?php } else { ?>

					<!-- FOR JOINER -->
					<h3>Note: Copy voucher code by clicking the copy icon.</h3>
					<div class="card-div">
						<?php
						// DISPLAY ALL VOUCHER
						if(!empty(isset($_GET['adv_id'])) && !empty(isset($_GET['adv_org'])) && !empty(isset($_GET['booking_total']))) {
							$orga_id = $_GET['adv_org'];
							$adv_id = $_GET['adv_id'];
							$price = $_GET['booking_total'];

							$card = DB::query("SELECT * FROM voucher WHERE orga_id = '$orga_id' AND adv_id = '$adv_id' AND vouch_minspent < ($price+1)", array(), "READ");
							if(!empty($card))
								displayAll(3, $card);
							else{
								echo "<script>alert('Sorry! No available voucher for this adventure but these are the vouchers you may avail for other adventures.')</script>";
								displayAll(3, $card);
							}
						}
						else if(isset($_POST['btnSearch'])){
							$txtSearch = trim(ucwords($_POST['txtSearch']));

							$card = DB::query("SELECT * FROM voucher WHERE vouch_discount LIKE '%{$txtSearch}%' || vouch_name LIKE '%{$txtSearch}%' || vouch_startdate LIKE '%{$txtSearch}%' || vouch_enddate LIKE '%{$txtSearch}%' || vouch_minspent LIKE '%{$txtSearch}%'", array(), "READ");

							displayAll(3, $card);
						}
						else if(isset($_POST['btnRestart'])){
							displayAll(3);
						}
						else
							displayAll(3);
						?>
					</div>
					<?php } ?>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
