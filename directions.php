<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");
?>

<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->

	<style>
		/* Header Area */
		header{background:url(images/header-bg.png) no-repeat center top/cover, #fff;}
		.main_logo{position:static;margin-left:10px;}

		/* Main Area */
		main{width:100%;flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0;border-radius:0;text-align:center;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
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
	$currentPage = 'gallery';
	include("includes/nav.php");
?>
<!-- End Navigation -->
<?php

	$dest = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($_GET['id']), "READ");
	$dest = $dest[0];

	$loc = DB::query("SELECT * FROM joiner WHERE joiner_id=?", array($_SESSION['joiner']), "READ");
	$loc = $loc[0];

	if($dest['adv_town'] == 'Poro')
		$to = "Camotes"; 
	else if($dest['adv_town'] == 'Bantayan')
		$to = "Santa%Fe";
	else
		$to = $dest['adv_town'];

	if($loc['joiner_citymuni'] == 'Cebu' || $loc['joiner_citymuni'] == 'Mandaue' || $loc['joiner_citymuni'] == 'Talisay' || $loc['joiner_citymuni'] == 'LapuLapu')
		$from = $loc['joiner_address'].",".$loc['joiner_citymuni']."%20City";
	else
		$from = $loc['joiner_address'].",".$loc['joiner_citymuni'];


	$response = get_directions_from_to_location($from,$to);
    $directions_data = json_decode($response,true);
    $directions = array();
    $directions_snips = array();

    if(count($directions_data['route']['legs'][0]['maneuvers']) != 0) {
    	for ($i=0; $i < count($directions_data['route']['legs'][0]['maneuvers']); $i++) { 
    		array_push($directions,$directions_data['route']['legs'][0]['maneuvers'][$i]['narrative']);
    		if($i == (count($directions_data['route']['legs'][0]['maneuvers'])-1)) {
    			$url = $directions_data['route']['legs'][0]['maneuvers'][$i]['iconUrl'];
    		}
    		else {
    			$url = substr_replace($directions_data['route']['legs'][0]['maneuvers'][$i]['mapUrl'], '620', 86, 3);
				$url = substr_replace($url,'620', 90, 3);
			}
    		array_push($directions_snips,$url);
    	}
    }
?>
<!-- Main -->
<div id="main_area">
	<div class="wrapper">
		<div class="breadcrumbs">
			<!-- <a href="gallery.php">Videos</a> | 
			<a href="gallery-imgs.php">Images</a> | 
			<a href="gallery-live.php">Live Virtual Tour</a> |
			<a href="directions.php" style="color:#bf127a;">Live Virtual Tour</a>  -->
		</div>
		<div class="main_con">
			<main>
				<h3>Here's a detailed navigation towards your destination</h3>
				<br>
				<div class="carousel">
					<?php
						for ($i=0; $i < count($directions) ; $i++) {
							echo "<h4><b>".$directions[$i]."</b></h4><br>";							
							echo "<div class='carousel-cell'>";
							if($i == (count($directions)-1))
								break;
							else
								echo "<iframe src='".$directions_snips[$i]."' width='620' height='620' frameborder='0'></iframe>";
							echo "</div><br>";
						}			
					?>
				</div>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
