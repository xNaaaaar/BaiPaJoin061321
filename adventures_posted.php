<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	// IF ADVENTURE IS UPDATED SUCCESSFULLY
	if(isset($_GET['updated']) && $_GET['updated'] == 1){
		echo "<script>alert('Adventure successfully updated!')</script>";
	}
	// IF ADVENTURE IS ADDED SUCCESSFULLY
	if(isset($_GET['added']) && $_GET['added'] == 1){
		echo "<script>alert('Adventure successfully added!')</script>";
	}
	// IF ADVENTURE IS DELETED SUCCESSFULLY
	if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
		echo "<script>alert('Adventure successfully deleted!')</script>";
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

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;position:relative;}
		main h2{font:600 59px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
		main h2 span{font-size:30px;}
		main h2 span a:hover{color:#313131;text-decoration:none;}
		main h3{font:600 30px/100% Montserrat,sans-serif;color:#ff4444;margin-bottom:10px;text-align:center;}
		main input{display:inline-block;width:99%;height:60px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 20px/20px Montserrat,sans-serif;padding:0 110px 0 30px;margin:15px auto;border:1px solid #cfcfcf;}
		main button:first-of-type{right:67px;}
		main button{display:block;width:45px;height:45px;border:none;background:#bf127a;border-radius:50px;color:#fff;position:absolute;top:152px;right:15px;z-index:5;font-size:20px;}

		.card{width:100%;min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:20px;padding:30px 125px 30px 215px;line-height:35px;text-align:left;margin:25px auto;border:1px solid #cfcfcf;}
		.card:hover{border:1px solid #bf127a;}
		.card figure{width:165px;height:165px;position:absolute;top:30px;left:30px;border:1px solid #cfcfcf;}
		.card figure img{width:100%;height:100%;}
		.card ul{position:absolute;top:20px;right:20px;font-size:30px;}
		.card ul li{display:inline-block;margin:0;}
		.card ul li a{color:#313131;}
		.card ul li a:hover{color:#bf127a;}
		.card h2{font:600 35px/100% Montserrat,sans-serif;color:#313131;margin-bottom:15px;}
		.card h2 span{display:block;font-size:18px;color:gray;}
		.card h2 span i{color:#ffac33;}
		.card p{font-size:23px;color:#989898;width:100% !important;margin:0 0 10px 2px;}
		.card p:last-of-type{color:#111;font-size:30px;font-weight:500;margin:0 0 0 2px;}

		/* main .btn{display:inline-block;width:249px;height:60px;background:#bf127a;border-radius:50px;color:#fff;margin:15px 5px;text-align:center;font:normal 20px/59px Montserrat,sans-serif;}
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
				<form method="post" >
					<h2>Posted Adventures <span>
						<?php
						// CHECK IF ORGANIZER IS VERIFIED TO POST ADVENTURE
						if($_SESSION['verified'] == 1)
							echo "<a class='btn' href='adventures_added.php'><i class='fas fa-plus-circle'></i></a>";
						else
							echo "<a class='btn disable' style='background:#313131;'><i class='fas fa-plus-circle'></i></a>";
						?>
					</span></h2>
					<input type="text" name="txtSearch" placeholder="Search any...">
					<button type="submit" name="btnSearch"><i class="fas fa-search"></i></button>
					<button type="submit" name="btnRestart"><i class="fas fa-undo-alt"></i></button>

					<?php
						// DISPLAY ALL ADVENTURE POSTED BY SPECIFIC ORGANIZER
						if(isset($_POST['btnSearch'])){
							$txtSearch = trim(ucwords($_POST['txtSearch']));

							$card = DB::query("SELECT * FROM adventure WHERE adv_kind LIKE '%{$txtSearch}%' || adv_name LIKE '%{$txtSearch}%' || adv_type LIKE '%{$txtSearch}%' || adv_address LIKE '%{$txtSearch}%' || adv_totalcostprice LIKE '%{$txtSearch}%' || adv_date LIKE '%{$txtSearch}%' || adv_details LIKE '%{$txtSearch}%' || adv_postedDate LIKE '%{$txtSearch}%' || adv_maxguests LIKE '%{$txtSearch}%' AND orga_id = ?", array($_SESSION['organizer']), "READ");

							displayAll(1, $card);
						}
						else if(isset($_POST['btnRestart'])){
							displayAll(1);
						}
						else
							displayAll(1);
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
