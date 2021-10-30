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
		main h3{font-size:30px;color:#313131;margin-bottom:10px;}

		.body{display:flex;justify-content:center;}
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
	$response = facebook_graph_api('tagged');
    $fb_data = json_decode($response,true);
    $media_src = array();
    $media_height = array();
    $media_width = array();
    $media_desc = array();
		$message = "";
		if(isset($fb_data['data'])) {
			foreach($fb_data['data'] as $item) {
	    	$response = get_facebook_media_id($item['id']);
	    	$fb_media = json_decode($response,true);
	    	if($fb_media['data'][0]['type'] == 'photo') {
	     		array_push($media_src,$fb_media['data'][0]['media']['image']['src']);
	     		array_push($media_height,$fb_media['data'][0]['media']['image']['height']);
	     		array_push($media_width,$fb_media['data'][0]['media']['image']['width']);
	     		//array_push($media_desc,$fb_media['data'][0]['description']);
	     	}
	     	else if($fb_media['data'][0]['type'] == 'album') {
	     		for ($i=0; $i < count($fb_media['data'][0]['subattachments']['data']) ; $i++) {
	     			if($fb_media['data'][0]['subattachments']['data'][$i]['type'] == 'photo') {
	     				array_push($media_src,$fb_media['data'][0]['subattachments']['data'][$i]['media']['image']['src']);
	     				array_push($media_height,$fb_media['data'][0]['subattachments']['data'][$i]['media']['image']['height']);
	     				array_push($media_width,$fb_media['data'][0]['subattachments']['data'][$i]['media']['image']['width']);
	     				//array_push($media_desc,$fb_media['data'][0]['description']);
	     			}
	     		}
	     	}
			}
		## TOKEN EXPIRED
		} else $message = "Token has expired!";
?>

<!-- Main -->
<div id="main_area">
	<div class="wrapper">
		<div class="breadcrumbs">
			<a href="gallery.php">Videos</a> |
			<a href="gallery-imgs.php" style="color:#bf127a;">Images</a> |
			<a href="gallery-live.php">Live Virtual Tour</a>
		</div>
		<div class="main_con">
			<main>
				<?php
				if($message == "")
					echo "<h3>Be inspire! Be Bold! Go out! Relive the moments together with our Joiners! Let these images speak for itself!</h3>";
				else
					echo "<h3>".$message."</h3>";
				?>
				<br>
				<div class="carousel" data-flickity>
						<?php
							if(!empty($media_src)){
								for ($i=0; $i <count($media_src) ; $i++) {
									echo "<div class='carousel-cell images'>";
									// width='".$media_width[$i]."'
									echo "<iframe src=".$media_src[$i]." height='".$media_height[$i]."' frameborder='0' id='images'></iframe>";
									//echo "<iframe src=".$media_src[$i]." width='".$media_width[$i]."' height='".$media_height[$i]."' frameborder='0' title='".$media_desc[$i]."'></iframe>";
									//echo "<p title=''>".$media_desc[$i]."</p>";
									echo "</div>";
								}
							} else echo "Sorry, there are no available images as of this moment.";
						?>
					</div>
				</div>
			</main>
		</div>

		<script>
			let frame = document.getElementById('images')
			let this_body = frame.getElementsByTagName('html')
			console.log(frame);
			this_body[0].style.display = "flex";
			this_body[0].style.justifyContent = "center";
		</script>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
