<?php

	include("facebook_login/config.php");
	include("google_login/config.php");
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(isset($_GET['login']) && isset($_GET['login']) == 1){
		echo "<script>alert('Login successfully!')</script>";
	}

	if(isset($_GET['code'])) {

		$google_token = $google_client -> fetchAccessTokenWithAuthCode($_GET['code']);

		if(!isset($google_token['error'])) {
			//This is for Google OAuth
			$google_client -> setAccessToken($google_token['access_token']);
			$google_service = new Google_Service_Oauth2($google_client);
			$data = $google_service -> userinfo -> get();

			unset($_SESSION['helper']); //This will unset FB session variable to solve error on settings.php

			$_SESSION['access_token'] = $google_token['access_token'];

			if(!empty($data)) {
			    if(!file_exists('logs\google_login'))
			      mkdir('logs\google_login', 0777, true);
			    $log_file_data = 'logs\\google_login\\log_' . date('d-M-Y') . '.log';
			    file_put_contents($log_file_data, date('h:i:sa').' => '. $data['name'] . ' ' . $data['email'] . ' ' . $data['given_name'] .' ' . $data['family_name'] . ' ' . $data['picture'] . ' ' . $data['locale'] . "\n" . "\n", FILE_APPEND);
			}

			$joiner_db = DB::query("SELECT * FROM joiner WHERE joiner_email=?", array($data['email']), "READ");
			if(!empty($joiner_db)) {
				echo "<script>alert('Login successfully!')</script>";
				loginCreateAccountSocial($data['given_name'], $data['family_name'], $data['email']);
			}
			else
				echo "<script>alert('Your email address is already in our system! Please user another Google Account')</script>";
		}
		/* elseif($google_token['error']) {
			//This is for Facebook OAuth'
			$facebook_token = $_SESSION['helper']->getAccessToken();
			$facebook->setDefaultAccessToken($facebook_token);
			$graph_response = $facebook->get("/me?fields=first_name,last_name,email", $facebook_token);
			$user_data = $graph_response->getGraphUser();

			unset($_SESSION['helper']); //This will unset FB session variable to solve error on settings.php

			if(!empty($user_data)) {
			    if(!file_exists('logs\facebook_login'))
			      mkdir('logs\facebook_login', 0777, true);
			    $log_file_data = 'logs\\facebook_login\\log_' . date('d-M-Y') . '.log';
			    file_put_contents($log_file_data, date('h:i:sa').' => '. $user_data['first_name'] . ' ' . $user_data['last_name'] . ' ' . $user_data['email'] . "\n" . "\n", FILE_APPEND);
			}
			$joiner_db = DB::query("SELECT * FROM joiner WHERE joiner_email=?", array($user_data['email']), "READ");
			if(!empty($joiner_db)) {
				echo "<script>alert('Login successfully!')</script>";
				loginCreateAccountSocial($user_data['first_name'], $user_data['last_name'], $user_data['email']);
			}
			else
				echo "<script>alert('Your email address is already in our system! Please user another Facebook Account')</script>";

		} */
	}
?>

<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->

</head>
	<body>
		<div class="protect-me">
		<div class="clearfix">

<!-- Header -->
<?php include("includes/header.php"); ?>
<!-- End of Header -->

<!-- Navigation -->
<?php
	$currentPage = 'index';
	include("includes/nav.php");
?>
<!-- End of Navigation -->

<!-- Banner -->
<?php include("includes/banner.php"); ?>
<!-- End Banner -->

<!-- Middle -->
<?php include("includes/middle.php"); ?>
<!-- End Middle -->

<!-- Main -->
<div id="main_area">
	<div class="wrapper">
		<div class="main_con">
			<div class="main_images">
				<figure class="main_img1 wow fadeInLeft" data-wow-duration="1s">
					<img src="images/main_img1.png" alt="caretaker helping patient">
				</figure>
				<figure class="main_img2 wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".5s">
					<img src="images/main_img2.png" alt="caretaker talking to patient">
				</figure>
			</div>

			<main>
				<h1 class="h1_title">BaiPaJoin: <span>An Online Joiner Platform for Tourists</span></h1>
				<p>We are using these temporary contents on the website.  These dummy texts are for display purposes only to show the volume of content that will be placed on this particular page. We have reserved this space to display more information about the company.</p>
				<p>In the near future, these text will be replaced with more details and different ways to contact the business.</p>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!-- Bottom -->
<?php include("includes/bottom.php"); ?>
<!-- End Bottom -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
