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
	$response = get_directions_from_to_location('AS%20Fortuna%20St.,Mandaue%20City','Dalaguete');
    $directions_data = json_decode($response,true);
    $directions = array();
    $directions_snips = array();
    //file_put_contents('debug.log', date('h:i:sa').' => '. count($directions_data[0]). "\n" . "\n", FILE_APPEND);
    if(count($directions_data['route']['legs'][0]['maneuvers']) != 0) {
    	for ($i=0; $i < count($directions_data['route']['legs'][0]['maneuvers']); $i++) { 
    		array_push($directions,$directions_data['route']['legs'][0]['maneuvers'][$i]['narrative']);
    		if($i == (count($directions_data['route']['legs'][0]['maneuvers'])-1)) {
    			$url = $directions_data['route']['legs'][0]['maneuvers'][$i]['iconUrl'];
    		}
    		else {
    			$url = substr_replace($directions_data['route']['legs'][0]['maneuvers'][$i]['mapUrl'], '300', 86, 3);
				$url = substr_replace($url,'300', 90, 3);
			}
    		array_push($directions_snips,$url);
    		file_put_contents('debug.log', date('h:i:sa').' => '. $directions_data['route']['legs'][0]['maneuvers'][$i]['narrative'] . "\n" . "\n", FILE_APPEND);
    	}
    }
?>
<!-- Main -->
<div id="main_area">
	<div class="wrapper">
		<div class="breadcrumbs">
			<a href="gallery.php">Videos</a> | 
			<a href="gallery-imgs.php">Images</a> | 
			<a href="gallery-live.php">Live Virtual Tour</a> |
			<a href="directions.php" style="color:#bf127a;">Live Virtual Tour</a> 
		</div>
		<div class="main_con">
			<main>
				<h3>Tagged Images</h3>
				<br>
				<div class="carousel">
					<?php
						for ($i=0; $i < count($directions) ; $i++) {
							echo "<h4>".$directions[$i]."</h4><br>";							
							echo "<div class='carousel-cell'>";
							echo "<iframe src='".$directions_snips[$i]."' width='300' height='300' frameborder='0'></iframe>";
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
