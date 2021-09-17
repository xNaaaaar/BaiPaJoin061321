<?php

	require_once 'vendor/autoload.php';

	$google_client = new Google_Client();

	$google_client -> setClientId('1031903496080-kk99fgt2bf3tgc68eva47cao4lnnm4rg.apps.googleusercontent.com');
	$google_client -> setClientSecret('cDLDjuBFoaAzJ3yTR5Ej7MUR');
	$google_client -> setRedirectUri('http://localhost/Melnar%20Ancit/BaiPaJoin061321/index.php');
	$google_client -> addScope('email');
	$google_client -> addScope('profile');
	$google_client -> addScope('openid');

?>
