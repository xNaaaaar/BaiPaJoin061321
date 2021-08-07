<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

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

		.sidebar{flex:1;height:500px;padding:30px 30px 30px 0;position:relative;}
		.sidebar:before{content:'';width:2px;height:70%;background:#cdcdcd;position:absolute;top:50%;right:0;transform:translateY(-50%);}
		.sidebar h2{font-size:30px;line-height:100%;}
		.sidebar ul{display:flex;height:100%;flex-direction:column;justify-content:flex-start;font:600 30px/100% Montserrat,sans-serif;list-style:none;margin:35px 0 0;}
		.sidebar ul li{line-height:45px;}
		.sidebar ul li i{width:40px;position:relative;}
		.sidebar ul li i:before{position:absolute;top:-25px;left:50%;transform:translateX(-50%);}
		.sidebar ul li:last-child{margin:auto 0;}
		.sidebar ul li a{color:#454545;}
		.sidebar ul li a:hover{color:#bf127a;}

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
		main h2{font:600 59px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
		main h3{font:600 30px/100% Montserrat,sans-serif;color:#ff4444;margin-bottom:10px;text-align:center;}

		.card-div{display:flex;justify-content:center;flex-wrap:wrap;}
		.card{width:48%;min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:20px;padding:30px 100px 30px 200px;line-height:35px;text-align:left;margin:25px auto 0;border:1px solid #cfcfcf;overflow:hidden;}
		.card:before{content:'';width:1px;height:85%;border:none;border-left:5px dotted #cfcfcf;position:absolute;top:50%;left:34%;transform:translateY(-50%);}
		.card:hover{border:1px solid #bf127a;}
		.card:hover:before{border-left:5px dotted #bf127a;}
		.card .expired{width:65%;height:100%;top:-5px;left:50%;transform:translateX(-50%);z-index:5;}
		.card figure{width:135px;height:135px;position:absolute;top:30px;left:20px;}
		.card figure img{width:100%;height:100%;}
		.card ul{position:absolute;top:20px;right:20px;font-size:30px;}
		.card ul li{display:inline-block;margin:0;}
		.card ul li a{color:#313131;}
		.card ul li a:hover{color:#bf127a;}
		.card h2{font:600 35px/100% Montserrat,sans-serif;color:#313131;margin-bottom:15px;}
		.card h2 span{display:block;font-size:18px;color:gray;line-height:100%;}
		.card h2 span i{color:#ffac33;}
		.card p{font-size:23px;color:#989898;width:100% !important;margin:0 0 10px 2px;}
		.card p:last-of-type{color:#111;font-size:25px;font-weight:500;margin:0 0 0 2px;}
		.card p q{display:block;}

		main .btn{display:inline-block;width:249px;height:60px;background:#bf127a;border-radius:50px;color:#fff;margin:40px 5px;text-align:center;font:normal 20px/59px Montserrat,sans-serif;}
		main .btn:hover{background:#8c0047;text-decoration:none;color:#fff;}

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
				<form method="post" >
					<h2>Vouchers</h2>

					<div class="card-div">
						<?php
						// DISPLAY ALL VOUCHER ADDED BY SPECIFIC ORGANIZER
						displayAll(2);
						?>
					</div>


					<?php
						// CHECK IF ORGANIZER IS VERIFIED TO ADD VOUCHER
						if($_SESSION['verified'] == 1) echo "<a class='btn edit' href='add_voucher.php'>Add Voucher</a>";
						else echo "<a class='btn edit disable' style='background:#313131;'>Add Voucher</a>";
					?>

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
