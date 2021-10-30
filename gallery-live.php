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

		.images{height:auto;}
	  .images iframe{min-height:1000px;}
	  .images img{width:100%;}
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
	$response = facebook_graph_api('live_videos');
  $fb_data = json_decode($response,true);
  $media_id = array();
	$message = "";
	if(isset($fb_data['data'])){
		if(!empty($fb_data['data'][0]) && $fb_data['data'][0]['status'] == 'LIVE') {
    	file_put_contents('debug.log', date('h:i:sa').' => '. $fb_data['data'][0]['id'] . "\n" . "\n", FILE_APPEND);
    	array_push($media_id,$fb_data['data'][0]['embed_html']);
    }
    else {
    	$response = facebook_graph_api('videos');
    	$fb_data = json_decode($response,true);
	  	foreach($fb_data['data'] as $item) {
	    	array_push($media_id,$item['id']);
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
			<a href="gallery-imgs.php">Images</a> |
			<a href="gallery-live.php" style="color:#bf127a;">Live Virtual Tour</a>
		</div>
		<div class="main_con">
			<main>
				<?php
				if(empty($message))
					echo "<h3>Experience it Live! Feel the vibe and excitement as we let you join our BaiPaJoin adventure!</h3>";
				else
					echo "<h3>".$message."</h3>";
				?>
				<br>
				<div class="carousel" data-flickity>
					<?php
						if(!empty($media_id)){
							if(count($media_id) == 1) {
								echo "<div class='carousel-cell images'>";
								echo $media_id[0];
								echo "</div>";
							} else {
								for ($i=0; $i <count($media_id) ; $i++) {
									echo "<div class='carousel-cell images'>";
									echo "<iframe src='https://www.facebook.com/video/embed?video_id=".$media_id[$i]."' style='border:none;overflow:hidden' scrolling='no' frameborder='0' allowfullscreen='' allow='autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share' allowFullScreen='true'></iframe>";
									echo "</div>";
								}
							}
						} else echo "Sorry, there are no available videos as of this moment.";
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
