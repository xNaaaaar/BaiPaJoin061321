<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");
?>

<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->

	<style>
		/* Header Area */
		header{background:url(images/header-bg.png) no-repeat center top/cover, #fff;}
		.main_logo{position:static;margin-left:10px;}

		/* Main Area */
		main{width:100%;flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0;border-radius:0;text-align:center;position:relative;}
		main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
		main h3{font:600 20px/100% Montserrat,sans-serif;color:red;margin:0 0 10px;text-align:left;}
		main input[type="checkbox"]{position:absolute;bottom:150px;left:0;}
		main span{position:absolute;bottom:145px;left:25px;}

		input{width:99.6%;}

		.form1 input:nth-of-type(1){width:49%;}
		.form1 input:nth-of-type(2){width:35%;}
		.form1 input:nth-of-type(3){width:15%;}
		.form1 input:nth-of-type(5){width:32.95%;}
		.form1 select{width:32.95%;}

		.form2 input:nth-of-type(2){width:75.6%;}
		.form2 input:nth-of-type(3){width:23.6%;}

		.edit{margin:60px auto 15px !important;}
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
			<a href="index.php">Home</a> &#187; <a href="adventures.php">Adventures</a> &#187; Lending
		</div>
		<div class="main_con">
			<main>
				<form method="post" enctype="multipart/form-data">
					<h2>Applicant</h2>
					<div class="form form1">
						<input type="text" name="txtName" placeholder="Name" required>
						<input type="text" name="dateBirth" placeholder="Date of Birth" onfocus="(this.type='date')" onblur="if(this.value==''){this.type='text'}" required>
						<input type="number" name="numAge" placeholder="Age" required>
						<input type="text" name="txtAddress" placeholder="Address" required>
						<select name="cboMarital" required>
							<option value="">Marital Status</option>
							<option value="single">Single</option>
							<option value="married">Married</option>
							<option value="widowed">Widowed</option>
							<option value="divorced">Divorced</option>
						</select>
						<select name="cboGender" required>
							<option value="">Gender</option>
							<option value="male">Male</option>
							<option value="female">Female</option>
							<option value="bi">Prefer not to say</option>
						</select>
						<input type="number" name="numIncome" placeholder="Monthly Income" required>
						<input type="text" name="txtPhone1" placeholder="Contact No.: 0999XXXXXXX" required>
					</div>

					<h2>Employment Info</h2>
					<div class="form form2">
						<input type="text" name="txtEmployer" placeholder="Name of Employer/Company" required>
						<input type="text" name="txtTitle" placeholder="Position Title" required>
						<input type="number" name="numYears" placeholder="Years on this job" required>
						<input type="text" name="txtPhone2" placeholder="Contact No.: 0999XXXXXXX" required>
					</div>

					<h2>Proof of Income</h2>
					<div class="form form3">
						<input type="file" name="fileIncome" required>
					</div>

					<h2>Valid IDs</h2>
					<h3>Note: Upload two valid IDs</h3>
					<div class="form form4">
						<input type="file" name="fileValidID1" required>
						<input type="file" name="fileValidID2" required>
					</div>

					<input type="checkbox" required>
					<span>By checking this box, all information is correct and is covered in <a href="https://www.privacy.gov.ph/data-privacy-act/" target="_blank">Data Privacy Act of 2012</a>, read and understood <a href="terms.php" target="_blank">Terms & Conditions</a></span>

					<button class="edit" type="submit" name="btnSubmit">Submit</button>
					<a class="edit" href="place.php?id=<?php echo $_GET['adv_id']; ?>">Back</a>
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
