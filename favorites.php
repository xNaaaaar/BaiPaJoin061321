<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	// REDIRECT IF NOT LOGGED IN
  if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

	// ADD ADVENTURE TO FAVORITES
	if(isset($_GET['addFav'])) addToFavorites($_GET['addFav'], 'fave');

	// REMOVE ADVENTURE TO FAVORITES
	if(isset($_GET['removeFav'])) removeFavorite($_GET['removeFav'], 'fave');

	// IF ADVENTURE IS ADDED SUCCESSFULLY
	if(isset($_GET['added']) && $_GET['added'] == 1){
		echo "<script>alert('Adventure successfully added to favorites!')</script>";
	}

	// IF ADVENTURE IS REMOVED SUCCESSFULLY
	if(isset($_GET['removed']) && $_GET['removed'] == 1){
		echo "<script>
		alert('Adventure successfully removed to favorites!')

		let fave = document.getElementById('saved')
		fave.addEventListener('click', () => {
			fave.classList.remove('added')
		})
		</script>";
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

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;position:relative;}
		main input{display:inline-block;width:99%;height:60px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:50px;padding:0 110px 0 30px;margin:15px auto;border:1px solid #cfcfcf;}
		main form{position:relative;margin-bottom:10px;}
		main button:first-of-type{right:67px;}
		main button{display:block;width:45px;height:45px;border:none;background:#bf127a;border-radius:50px;color:#fff;position:absolute;top:50%;right:15px;transform:translateY(-50%);z-index:5;font-size:20px;}
		main button:hover{background:#8c0047;}

		/* .card-link{text-decoration:none !important;}
		.card{width:100%;min-height:200px;position:relative;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:20px;padding:30px 125px 30px 215px;line-height:35px;text-align:left;margin:25px auto;border:1px solid #cfcfcf;}
		.card:hover{border:1px solid #bf127a;}
		.card figure{width:165px;height:165px;position:absolute;top:30px;left:30px;border:1px solid #cfcfcf;}
		.card figure img{width:100%;height:100%;}
		.card ul{position:absolute;top:20px;right:20px;font-size:30px;}
		.card ul li{display:inline-block;margin:0 0 0 8px;}
		.card ul li .added{color:#bf127a;}
		.card ul li a{color:#313131;}
		.card ul li a:hover{color:#bf127a;}
		.card h2{font:600 35px/100% Montserrat,sans-serif;color:#313131;margin-bottom:15px;}
		.card h2 span{display:block;font-size:18px;color:gray;}
		.card h2 span i{color:#ffac33;}
		.card p{font-size:23px;color:#989898;width:100% !important;margin:0 0 10px 2px;}
		.card p:last-of-type{color:#111;font-size:30px;font-weight:500;margin:0 0 0 2px;} */
		.card{width:100%;min-height:227px;padding:30px 125px 30px 215px;margin:25px auto;}
		.card figure{width:165px;height:165px;position:absolute;top:30px;left:30px;border:1px solid #cfcfcf;}
		.card h2 span i{color:#ffac33;}
		.card p{font-size:23px;color:#989898;width:100% !important;margin:0 0 10px 2px;}
		.card p:last-of-type{color:#111;font-size:30px;font-weight:500;margin:0 0 0 2px;}

		/* PAGINATION COLORS */
		a.paging:visited {background-color: none;   color:none;}
		a.paging:active {background-color: #FFCC00; text-decoration: none;  color:#FFFFFF}
		a.paging:hover {background-color: wheat; font-weight:bold; color:#bf127a;}

		a.pagingCurrent:visited {color:#bf127a;}
		a.pagingCurrent:active {background: #FF0000; color:#FFFFFF;}
		a.pagingCurrent:hover {background: wheat; font-weight:bold; color: none;}

		@media only screen and (max-width:1000px){
			main{padding:50px 0 0 25px;}

			.card{width:47.5%;min-height:auto;line-height:30px;padding:45px 20px 20px 20px !important;margin:25px 5px;display:inline-block;vertical-align:top;height:auto;background:#fff;}
      .card ul{position:absolute;top:10px;right:20px;font-size:20px;}
      .card h2{font-size:30px !important;}
      .card figure{width:100%;position:static;}
      .card p:last-of-type{font-size:25px;margin:0;}
		}
		@media only screen and (max-width:800px){
			.card{width:100%;margin:25px auto;display:block;}
		}
		@media only screen and (max-width:600px){
			main{padding:30px 0 30px 30px;}
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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; Favorites
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'favorites';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->

			<main>
				<h2>My Favorite Adventures</h2>
				<form method="post" >
					<input type="text" name="txtSearch" placeholder="Search any...">

					<!-- DIRECT LINK FOR UNDO|RESET BUTTON -->
					<button type="submit" formaction="favorites.php" name="btnSearch"><i class="fas fa-search" data-toggle='tooltip' title='Search'></i></button>
					<button formaction="favorites.php" ><i class="fas fa-undo-alt" data-toggle='tooltip' title='Reset View'></i></button>
			  </form>


					<?php
						// DISPLAY ALL ADVENTURE
						if(isset($_POST['btnSearch']))
						{
							$txtSearch = trim(ucwords($_POST['txtSearch']));

//=============================== UPDATES =====================================//

								if(isset($_GET['page']))
									{
										$page = $_GET['page'];
									}
									else
										{
											$page = 1;
										}

										$num_per_page = 5; //LIMIT NUMBER per PAGE
										$start_from = ($page-1) * $num_per_page; // Page STARTED

									    $card = DB::query("SELECT * FROM adventure INNER JOIN favorite ON favorite.adv_id = adventure.adv_id WHERE joiner_id = ? AND adv_kind LIKE '%{$txtSearch}%' || adv_name LIKE '%{$txtSearch}%' || adv_type LIKE '%{$txtSearch}%' || adv_address LIKE '%{$txtSearch}%' || adv_totalcostprice LIKE '%{$txtSearch}%' || adv_date LIKE '%{$txtSearch}%' || adv_details LIKE '%{$txtSearch}%' || adv_postedDate LIKE '%{$txtSearch}%' || adv_maxguests LIKE '%{$txtSearch}%' LIMIT $start_from,$num_per_page", array($_SESSION['joiner']), "READ"); // using LIMIT to limit SEARCH query display

									    displayAll(4, $card);

									    $card1 = DB::query("SELECT * FROM adventure INNER JOIN favorite ON favorite.adv_id = adventure.adv_id WHERE joiner_id = ? AND adv_kind LIKE '%{$txtSearch}%' || adv_name LIKE '%{$txtSearch}%' || adv_type LIKE '%{$txtSearch}%' || adv_address LIKE '%{$txtSearch}%' || adv_totalcostprice LIKE '%{$txtSearch}%' || adv_date LIKE '%{$txtSearch}%' || adv_details LIKE '%{$txtSearch}%' || adv_postedDate LIKE '%{$txtSearch}%' || adv_maxguests LIKE '%{$txtSearch}%'", array($_SESSION['joiner']), "READ");

									    //PAGINATION Kirk

										$total_record = count($card1); // COUNTS DATA IN Search query
						                $total_page = ceil($total_record/$num_per_page); // DIVIDES TOTAL RECORD SEARCH USING ceil

						                if($page > 1) // PAGINATION STARTS|PREVIOUS
						                {
						                    echo "<a href='favorites.php?page=" .($page-1). "' class='fas fa-angle-double-left pull-left' > Previous</a>";
						                }

							                    //LIMIT VISIBLE NUMBER PAGE
												$numpage = 1;
												$startPage = max(1, $page - $numpage);
												$endPage = min( $total_page, $page + $numpage);


												for($i=$startPage;$i<=$endPage;$i++) // PAGINATION COUNTS|LOOPS
												{


													if($i == $page) {
										            $class = 'pagingCurrent'; // PAGINATION CURRENT Page COLOR

										            }else
														{
														$class = 'paging'; // PAGINATION COLOR
														}


														if($page > $i && $page > 2) { //CONTROL VISIBLE START NUMBER PAGE

														 echo "<a href='favorites.php' class='".$class."'> 1 ... </a>";
														}


														echo "<a href='favorites.php?page=" .$i. "' class='".$class."'>  $i   </a>";

														if($page < $i && $page < ($total_page-1)) { //CONTROL VISIBLE END NUMBER PAGE

															echo "<a href='favorites.php?page=" .($total_page). "' class='".$class."'> ... $total_page </a>";

														}


												 }

						                if(($i-1) > $page) // PAGINATION NEXT|ENDS
						                {
						                    echo "<a href='favorites.php?page=" .($page+1). "' class='fas fa-angle-double-right pull-right' > Next </a >";
						                }
						}
						else
						{
//=============================== UPDATES =====================================//

								if(isset($_GET['page']))
									{
										$page = $_GET['page'];
									}
									else
										{
											$page = 1;
										}

										$num_per_page = 5; //LIMIT NUMBER per PAGE
										$start_from = ($page-1) * $num_per_page; // Page STARTED

								        $card1 = DB::query("SELECT * FROM adventure INNER JOIN favorite ON favorite.adv_id = adventure.adv_id WHERE joiner_id = ? LIMIT $start_from,$num_per_page", array($_SESSION['joiner']), "READ"); // USING LIMIT to limit query display

				    					displayAll(4, $card1);


						                $card2 = DB::query("SELECT * FROM adventure INNER JOIN favorite ON favorite.adv_id = adventure.adv_id WHERE joiner_id = ?", array($_SESSION['joiner']), "READ");

		 								//PAGINATION Kirk

										$total_record = count($card2); // COUNTS DATA IN query
						                $total_page = ceil($total_record/$num_per_page); // DIVIDES TOTAL RECORD USING ceil

						                if($page > 1) // PAGINATION STARTS|PREVIOUS
						                {
						                    echo "<a href='favorites.php?page=" .($page-1). "' class='fas fa-angle-double-left pull-left' > Previous</a>";
						                }


							                    //LIMIT VISIBLE NUMBER PAGE
												$numpage = 1;
												$startPage = max(1, $page - $numpage);
												$endPage = min( $total_page, $page + $numpage);


												for($i=$startPage;$i<=$endPage;$i++) // PAGINATION COUNTS|LOOPS
												{


													if($i == $page) {
										            $class = 'pagingCurrent'; // PAGINATION CURRENT Page COLOR

										            }else
														{
														$class = 'paging'; // PAGINATION COLOR
														}


														if($page > $i && $page > 2) { //CONTROL VISIBLE START NUMBER PAGE

														 echo "<a href='favorites.php' class='".$class."'> 1 ... </a>";
														}


														echo "<a href='favorites.php?page=" .$i. "' class='".$class."'>  $i   </a>";

														if($page < $i && $page < ($total_page-1)) { //CONTROL VISIBLE END NUMBER PAGE

															echo "<a href='favorites.php?page=" .($total_page). "' class='".$class."'> ... $total_page </a>";

														}


												 }


						                if(($i-1) > $page) // PAGINATION NEXT|ENDS
						                {
						                    echo "<a href='favorites.php?page=" .($page+1). "' class='fas fa-angle-double-right pull-right' > Next </a >";
						                }
				        }

					?>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
