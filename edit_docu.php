<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");

	if(isset($_POST['btnSave'])){
		deleteSQLDataTable('legal_document', $_GET['image']);
		addLegalDocuments();
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
		main .form textarea{height:180px;padding:15px;margin:15px auto 0;}
		main .form input[type=file]{padding-top:13px;color:#b1b1b1;margin-top:0;}
		main .form .label{font-size:15px;display:inline-block;text-align:left;width:99%;margin:5px 0 0;}

		/*RESPONSIVE*/
		@media only screen and (max-width:1000px) {
			main{padding:50px 0 0 25px;}
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
			<a href="index.php">Home</a> &#187; <a href="settings.php">Settings</a> &#187; Profile
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
					<h2>Edit Legal Documents</h2>
					<?php
					// UNIQUE IMAGE NAME TO BE EDITED
					if(isset($_GET['image'])){
						$docu = DB::query("SELECT * FROM legal_document WHERE docu_image = ?", array($_GET['image']), "READ");

						if(count($docu)>0){
							$docu = $docu[0];
					?>

					<div class="form form2">
						<div class="label">
							<label for="f01">Legal Document Image</label>
						</div>
						<input id="f01" type="file" name="fileDocuImage" required>
						<select name="cboType" required>
							<option value="">Choose Legal Docu Type</option>
							<option value="Docu Type1" <?php echo $docu['docu_type'] == "Docu Type1" ? "selected='selected'":""; ?>>Docu Type1</option>
							<option value="Docu Type2" <?php echo $docu['docu_type'] == "Docu Type2" ? "selected='selected'":""; ?>>Docu Type2</option>
							<option value="Docu Type3" <?php echo $docu['docu_type'] == "Docu Type3" ? "selected='selected'":""; ?>>Docu Type3</option>
							<option value="Docu Type4" <?php echo $docu['docu_type'] == "Docu Type4" ? "selected='selected'":""; ?>>Docu Type4</option>
						</select>
						<textarea name="txtDescription" placeholder="Document description (optional)"><?php echo $docu['docu_description']; ?></textarea>
					</div>

					<?php
						}
					}
					?>

					<!-- SHOW ALL THE ORGANIZERS LEGAL DOCUMENT -->

					<button class="edit" type="submit" name="btnSave">Save</button>
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
