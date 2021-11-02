<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	// REDIRECT IF NOT LOGGED IN
    if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

	if(isset($_POST['btnSave'])){
		addLegalDocuments();
		header("Location: settings.php?added=1");
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

		main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0 50px 50px;border-radius:0;text-align:center;}
		main .form{display:flex;justify-content:center;flex-wrap:wrap;}
		main .form textarea{display:inline-block;width:99%;height:180px;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:8px;font:normal 20px/20px Montserrat,sans-serif;padding:15px;margin:15px auto;border:1px solid #cfcfcf;resize:none;}
		main .form input[type=radio]{vertical-align:bottom;width:20px;height:20px;margin:0 0 3px 8px;border:none;box-shadow:none;}
		main .form input[type=file]{padding-top:13px;color:#b1b1b1;margin-top:0;}
		main .form .label{font-size:15px;display:inline-block;text-align:left;width:99%;margin:5px 0 0;}
		main .form .label-view{text-align:left;}

		/*RESPONSIVE*/
		@media only screen and (max-width:1000px) {
			main{padding:50px 0 0 25px;}
		}
		@media only screen and (max-width:600px) {
			main .form input[type=radio]{width:20px !important;margin:0 0 3px 8px !important;}
			main .form .label-view{width:100%;margin:0 0 10px;}
			main .form .label-view label:first-child{display:block;}
		}
		@media only screen and (max-width:500px) {
			main .edit{width:48%;}
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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; Legal Documents
		</div>
		<div class="main_con">
			<!-- Sub Navigation -->
			<?php
				$currentSidebarPage = 'profile';
				include("includes/sidebar.php");
			?>
			<!-- End of Sub Navigation -->

			<main>
				<form method="post" enctype="multipart/form-data">
					<h2>Add Legal Documents</h2>

					<div class="form form2">
						<div class="label-view">
							<label>Document viewable by joiners: </label>
							<input type="radio" name="radioView" value="1" required>
							<label>public</label>
							<input type="radio" name="radioView" value="0" required>
							<label>private</label>
						</div>
						<div class="label">
							<label for="f01">Add Legal Document Image</label>
						</div>
						<input id="f01" type="file" name="fileDocuImage" required>
						<select name="cboType" required>
							<option value="">Choose Legal Docu Type</option>
							<option value="Docu Type1">Docu Type1</option>
							<option value="Docu Type2">Docu Type2</option>
							<option value="Docu Type3">Docu Type3</option>
							<option value="Docu Type4">Docu Type4</option>
						</select>
						<textarea name="txtDescription" placeholder="Document description (optional)"></textarea>
					</div>

					<!-- SHOW ALL THE ORGANIZERS LEGAL DOCUMENT -->

					<button class="edit" type="submit" name="btnSave">Add</button>
					<a class="edit" href="settings.php">Back</a>
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
