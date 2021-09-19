<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	// ADD ADVENTURE TO FAVORITES
	if(isset($_GET['addFav'])) addToFavorites($_GET['addFav']);

	// REMOVE ADVENTURE TO FAVORITES
	if(isset($_GET['removeFav'])) removeFavorite($_GET['removeFav']);

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
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
		main h2 span{font-size:30px;}
		main h2 span a:hover{color:#313131;text-decoration:none;}
		main h3{font:600 30px/100% Montserrat,sans-serif;color:#ff4444;margin-bottom:10px;text-align:center;}
		main input{display:inline-block;width:99%;height:60px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 20px/20px Montserrat,sans-serif;padding:0 110px 0 30px;margin:15px auto;border:1px solid #cfcfcf;}
		main button:first-of-type{right:67px;}
		main button{display:block;width:45px;height:45px;border:none;background:#bf127a;border-radius:50%;color:#fff;position:absolute;top:126px;right:15px;z-index:5;font-size:22px;}
		main button:hover{background:#8c0047;}

		.card-link{text-decoration:none !important;}
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
		.card p:last-of-type{color:#111;font-size:30px;font-weight:500;margin:0 0 0 2px;}

		/* PAGINATION COLORS */
		a.paging:visited {background-color: black;   color:black;}
		a.paging:active {background-color: black; color:black}
		a.paging:hover {background-color: wheat; font-weight:bold; color:#bf127a;}

		a.pagingCurrent:visited {color:#bf127a;}
		a.pagingCurrent:hover {background: wheat; font-weight:bold; color: none;}

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
	$currentPage = 'adventures';
	include("includes/nav.php");
?>
<!-- End Navigation -->

<!-- Main -->
<div id="main_area">
	<div class="wrapper">
		<div class="breadcrumbs">
			<a href="index.php">Home</a> &#187; Adventures
		</div>
		<div class="main_con">
			<aside class="sidebar">
				<form method="post">
				<?php
				if($_SESSION['current_user'] == 'Joiner') {
					currentJoiner($_SESSION['joiner']);
					//
					echo "<h2> {$_SESSION['fname']} {$_SESSION['lname']} </h2>
					<p>User Type: Joiner</p>";
				}
				else {
					echo "<h2>Welcome guests!</h2>";
				}
				?>

				<div class="filters">
					<ul class="places">
						<li><h3>Places</h3></li>

						<?php
							// DISPLAY PLACES
							$places = array("Bantayan Island", "Malapascua Island", "Camotes Island", "Moalboal", "Badian", "Oslob", "Alcoy", "Aloguinsan", "Santander", "Alegria", "Dalaguete");

							for($i=0; $i<count($places); $i++){
								echo "
								<li><input class='checkbox-places' type='checkbox' name='places[]' value='".$places[$i]."' ".checkPlaces($places[$i])."><label>".$places[$i]."</label></li>
								";
							}
						?>
					</ul>
					<ul class="activites">
						<li><h3>Activities</h3></li>

						<?php
							// DISPLAY ACTIVITIES
							$activities = array("Packaged", "Swimming", "Camping", "Island Hopping", "Mountain Hiking", "Snorkeling", "Canyoneering", "Biking", "Diving", "Jetski", "Banana Boat");

							for($i=0; $i<count($activities); $i++){
								echo "
								<li><input class='checkbox-activities' type='checkbox' name='activities[]' value='".$activities[$i]."' ".checkActivities($activities[$i])."><label for=''>".$activities[$i]."</label></li>
								";
							}
						?>
					</ul>
					<ul class="prices">
						<li><h3>Prices</h3></li>
						<li><input type="number" name="" placeholder="Minimum price"></li>
						<li><input type="number" name="" placeholder="Maximum price"></li>
					</ul>
					<button type="submit" formaction="adventures.php" name="btnSave">Save Changes</button>
					<button type="submit" name="btnReset" formaction="adventures.php">Reset</button>
				</div>
				</form>
			</aside>


			<main>
				<form method="post" >
					<h2>Adventures</h2>
					<input type="text" name="txtSearch" placeholder="Search any...">
					<!-- DIRECT LINK FOR UNDO|RESET BUTTON -->
					<button type="submit" formaction="adventures.php" name="btnSearch"><i class="fas fa-search"></i></button>
					<button formaction="adventures.php" ><i class="fas fa-undo-alt"></i></button>

					<?php

			      //Pagination code  starts here - Kirk Albano

						if(isset($_POST['btnSearch']))
						{
							$txtSearch = trim(ucwords($_POST['txtSearch']));

							if(isset($_GET['page']))
							{
								$page = $_GET['page'];
							}
							else
							{
								$page = 1;
							}

							$num_per_page = 5; //LIMIT NUMBER per PAGE
							$start_from = ($page-1) * $num_per_page;

							$card = DB::query("SELECT * FROM adventure WHERE adv_kind LIKE '%{$txtSearch}%' || adv_name LIKE '%{$txtSearch}%' || adv_type LIKE '%{$txtSearch}%' || adv_address LIKE '%{$txtSearch}%' || adv_totalcostprice LIKE '%{$txtSearch}%' || adv_date LIKE '%{$txtSearch}%' || adv_details LIKE '%{$txtSearch}%' || adv_postedDate LIKE '%{$txtSearch}%' || adv_maxguests LIKE '%{$txtSearch}%' ORDER BY adv_id DESC LIMIT $start_from,$num_per_page", array(), "READ");

							displayAll(99, $card);

							$card1 = DB::query("SELECT * FROM adventure WHERE adv_kind LIKE '%{$txtSearch}%' || adv_name LIKE '%{$txtSearch}%' || adv_type LIKE '%{$txtSearch}%' || adv_address LIKE '%{$txtSearch}%' || adv_totalcostprice LIKE '%{$txtSearch}%' || adv_date LIKE '%{$txtSearch}%' || adv_details LIKE '%{$txtSearch}%' || adv_postedDate LIKE '%{$txtSearch}%' || adv_maxguests LIKE '%{$txtSearch}%' ", array(), "READ");

                            //PAGINATION Kirk

							$total_record = count($card1);
			                $total_page = ceil($total_record/$num_per_page);

			                if($page > 1)
			                {
			                    echo "<a href='adventures.php?page=" .($page-1). "' class='fas fa-angle-double-left pull-left' > Previous</a>";
			                }

			                		//LIMIT VISIBLE NUMBER PAGE
									$numpage = 1;
									$startPage = max(1, $page - $numpage);
									$endPage = min( $total_page, $page + $numpage);

									for($i=$startPage;$i<=$endPage;$i++) // PAGINATION COUNTS|LOOPS
									{


										if ($i == $page) {
							            $class = 'pagingCurrent'; // PAGINATION CURRENT Page COLOR

							            }else
											{
											$class = 'paging'; // PAGINATION COLOR
											}


										if($page > $i && $page > 2) { //CONTROL VISIBLE START NUMBER PAGE

											echo "<a href='adventures.php' class='".$class."'> 1 ... </a>";
										}


											echo "<a href='adventures.php?page=" .$i. "' class='".$class."'>  $i   </a>";

										if($page < $i && $page < ($total_page-1)) { //CONTROL VISIBLE END NUMBER PAGE

											echo "<a href='adventures.php?page=" .($total_page). "' class='".$class."'> ... $total_page </a>";

										}


									}


			                if(($i-1) > $page)
			                {
			                    echo "<a href='adventures?page=" .($page+1). "' class='fas fa-angle-double-right pull-right' > Next </a >";
			                }
						}

						else if(isset($_POST['btnSave']))
						{ //Aside Filter code  starts here - Alexis Salvador

								unset($_SESSION['places']);

								if(isset($_GET['page']))
								{
									$page = $_GET['page'];
								}
								else
								{
									$page = 1;
								}

									$num_per_page = 5; //LIMIT NUMBER per PAGE
									$start_from = ($page-1) * $num_per_page;

									if(!empty($_POST['places'])) {

										$sqlquery = "SELECT * FROM adventure WHERE adv_address";

										$arrlength = count($_POST['places']);

										foreach($_POST['places'] as $index => $place) {
											if($index != $arrlength-1)
												$sqlquery = $sqlquery . " = '$place' OR adv_address";
											else
												$sqlquery = $sqlquery . " = '$place'";
										}

										if(!empty($_POST['activities'])) {

											//	Concatenates string if 1 or more Activity 																checkbox is selected

											$sqlquery = $sqlquery . " OR adv_kind";

											$arrlength = count($_POST['activities']);

											foreach($_POST['activities'] as $index => $activity) {
												if($index != $arrlength-1)
													$sqlquery = $sqlquery . " = '$activity' OR adv_kind";
												else
													$sqlquery = $sqlquery . " = '$activity'";
											}

												 //$sqlquery = $sqlquery . " ORDER BY adv_id DESC LIMIT $start_from,$num_per_page";
												// Check commented code above
												$sqlquery = $sqlquery . " ORDER BY adv_id";	 //Temporary to show true results
										}
										else
											$sqlquery = $sqlquery . " ORDER BY adv_id";
									}

									else if(!empty($_POST['activities'])) {

										if(empty($_POST['places']))
											$sqlquery = "SELECT * FROM adventure WHERE adv_kind  LIMIT $start_from,$num_per_page";
											// 	New string query is created if no Place checkbox is selected

										$arrlength = count($_POST['activities']);

										foreach($_POST['activities'] as $index => $activity) {
											if($index != $arrlength-1)
												$sqlquery = $sqlquery . " = '$activity' OR adv_kind";
											else
												$sqlquery = $sqlquery . " = '$activity'";
										}

										//$sqlquery = $sqlquery . " ORDER BY adv_id DESC LIMIT $start_from,$num_per_page";
										// Check commented code above
										$sqlquery = $sqlquery . " ORDER BY adv_id";	//Temporary to show true results
									}

									$card = DB::query($sqlquery, array(), "READ");

									if(!empty($card))
										displayAll(99, $card);
									else { //This works if return items from SQL is empty due to search not found
										$card1 = DB::query("SELECT * FROM adventure LIMIT $start_from,$num_per_page", array(), "READ");
										$card = DB::query("SELECT * FROM adventure", array(), "READ");
										echo "Your search parameters are not found!";
										displayAll(99, $card1);
									}

								// PAGINATION Kirk

								$total_record = count($card);
				                $total_page = ceil($total_record/$num_per_page);

				                if($page > 1)
				                {
				                    echo "<a href='adventures.php?page=" .($page-1). "' class='fas fa-angle-double-left pull-left' > Previous</a>";
				                }

								        //LIMIT VISIBLE NUMBER PAGE
										$numpage = 1;
										$startPage = max(1, $page - $numpage);
										$endPage = min( $total_page, $page + $numpage);


									    for($i=$startPage;$i<=$endPage;$i++) // PAGINATION COUNTS|LOOPS
									    {


											if ($i == $page) {
							            		$class = 'pagingCurrent'; // PAGINATION CURRENT Page COLOR

							            	}else
												{
													$class = 'paging'; // PAGINATION COLOR
												}


											if($page > $i && $page > 2) { //CONTROL VISIBLE START NUMBER PAGE

												echo "<a href='adventures.php' class='".$class."'> 1 ... </a>";
											}


												echo "<a href='adventures.php?page=" .$i. "' class='".$class."'>  $i   </a>";

											if($page < $i && $page < ($total_page-1)) { //CONTROL VISIBLE PAGINATION END NUMBER PAGE

												echo "<a href='adventures.php?page=" .($total_page). "' class='".$class."'> ... $total_page </a>";

											}


									    }

				                if(($i-1) > $page)
				                {
				                    echo "<a href='adventures?page=" .($page+1). "' class='fas fa-angle-double-right pull-right' > Next </a >";
				                }

						}

						//Aside Filter code ends here - Alexis Salvador

						else
						{ //Index to Adventure Page Filter Code starts here - Alexis Salvador
							if(isset($_GET['page']))
							{
								$page = $_GET['page'];
							}
							else
							{
								$page = 1;
							}

							$num_per_page = 5; //LIMIT NUMBER per PAGE
							$start_from = ($page-1) * $num_per_page;

					        if(!empty($_SESSION['places'])) {

								$sqlquery = "SELECT * FROM adventure WHERE adv_address";

								$arrlength = count($_SESSION['places']);

								foreach($_SESSION['places'] as $index => $place) {
									if($index != $arrlength-1)
										$sqlquery = $sqlquery . " = '$place' OR adv_address";
									else
										$sqlquery = $sqlquery . " = '$place'";
								}

								if(!empty($_POST['activities'])) {

									//	Concatenates string if 1 or more Activity 																checkbox is selected

									$sqlquery = $sqlquery . " OR adv_kind";

									$arrlength = count($_POST['activities']);

									foreach($_POST['activities'] as $index => $activity) {
										if($index != $arrlength-1)
											$sqlquery = $sqlquery . " = '$activity' OR adv_kind";
										else
											$sqlquery = $sqlquery . " = '$activity'";
									}

										//$sqlquery = $sqlquery . " ORDER BY adv_id DESC LIMIT $start_from,$num_per_page";
										// Check commented code above
										$sqlquery = $sqlquery . " ORDER BY adv_id";	 //Temporary to show true results
								}
							}

							else if(!empty($_POST['activities'])) {

								if(empty($_POST['places']))
									$sqlquery = "SELECT * FROM adventure WHERE adv_kind";
									// 	New string query is created if no Place checkbox is selected

								$arrlength = count($_POST['activities']);

								foreach($_POST['activities'] as $index => $activity) {
									if($index != $arrlength-1)
										$sqlquery = $sqlquery . " = '$activity' OR adv_kind";
									else
										$sqlquery = $sqlquery . " = '$activity'";
								}

								//$sqlquery = $sqlquery . " ORDER BY adv_id DESC LIMIT $start_from,$num_per_page";
								// Check commented code above
								$sqlquery = $sqlquery . " ORDER BY adv_id";	//Temporary to show true results
							}

							if(!empty($sqlquery))
								$card = DB::query($sqlquery, array(), "READ");

							if(!empty($card))
								displayAll(99, $card);
							else { //This works if return items from SQL is empty due to search not found
								$card = DB::query("SELECT * FROM adventure", array(), "READ");
								$card1 = DB::query("SELECT * FROM adventure LIMIT $start_from,$num_per_page", array(), "READ");
								echo "Enter a search parameters OR no search parameters found!";
								displayAll(99, $card1);
							}

							// PAGINATION Kirk

							$total_record = count($card);
			                $total_page = ceil($total_record/$num_per_page);

			                if($page > 1)
			                {
			                    echo "<a href='adventures.php?page=" .($page-1). "' class='fas fa-angle-double-left pull-left' > Previous</a>";
			                }

										//LIMIT VISIBLE NUMBER PAGE
										$numpage = 1;
										$startPage = max(1, $page - $numpage);
										$endPage = min( $total_page, $page + $numpage);


									    for($i=$startPage;$i<=$endPage;$i++) // PAGINATION COUNTS|LOOPS
									    {


											if ($i == $page) {
							            		$class = 'pagingCurrent'; // PAGINATION CURRENT Page COLOR

							            	}else
												{
													$class = 'paging'; // PAGINATION COLOR
												}


											if($page > $i && $page > 2) { //CONTROL VISIBLE START NUMBER PAGE

												echo "<a href='adventures.php' class='".$class."'> 1 ... </a>";
											}


												echo "<a href='adventures.php?page=" .$i. "' class='".$class."'>  $i   </a>";

											if($page < $i && $page < ($total_page-1)) { //CONTROL VISIBLE END NUMBER PAGE

												echo "<a href='adventures.php?page=" .($total_page). "' class='".$class."'> ... $total_page </a>";

											}


									    }

			                if(($i-1) > $page)
			                {
			                    echo "<a href='adventures.php?page=" .($page+1). "' class='fas fa-angle-double-right pull-right' > Next </a >";
			                }

			                unset($_SESSION['places']);

			                //Test

				        } //Index to Adventure Page Filter Code starts here - Alexis Salvador

						//Pagination code ends here - Kirk Albano
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
