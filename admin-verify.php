<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

  // REDIRECT IF ADMIN NOT LOGGED IN
  if(!isset($_SESSION['admin'])) header("Location: login.php");

	## BUTTON VERIFY IS CLICKED
	if(isset($_POST['btnVerify'])){
		## UPDATE ORGANIZER STATUS
		DB::query("UPDATE organizer SET orga_status=? WHERE orga_id=?", array(1, $_GET['orga_id']), "UPDATE");
		##
		header("Location: admin-organizer.php?success");
	}

	## BUTTON RETURN IS CLICKED
	if(isset($_POST['btnReturn'])){
		## UPDATE ORGANIZER STATUS
		DB::query("UPDATE organizer SET orga_status=? WHERE orga_id=?", array(0, $_GET['orga_id']), "UPDATE");
		##
		header("Location: admin-organizer.php?return");
	}

?>
<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">


<style media="screen">
html, body{height:100%;}
.wrapper{max-width:100%;}
/* Main Area */
.main_con{display:flex;justify-content:space-between;min-height:100vh;}

.sidebar{padding:30px;background:#7fdcd3;height:auto;}
.sidebar:before{display:none;}
.sidebar figure{width:150px;margin:0 auto!important;}
.sidebar h2{text-align:center;}
.sidebar h2 span{display:block;font-size:15px;}
.sidebar ul{margin:35px 0 0 25px;height:auto;}

main{flex:4;float:none;height:100%;background:none;margin:0;padding:50px 50px 0;border-radius:0;text-align:center;}
main h1{text-align:right;font-size:20px;}
main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin:15px 0;text-align:left;}
main h2 span{font-size:30px;}
main h2 span a:hover, main a:hover{color:#313131;text-decoration:none;}
main h3{font:600 30px/100% Montserrat,sans-serif;;margin-bottom:10px;text-align:left;}

main .contents{display:flex;justify-content:space-between;margin:30px 0 0;}

main .admins{height:auto;width:100%;}
main .edit{width:150px;height:45px;font:normal 18px/45px Montserrat,sans-serif;border-radius:0;vertical-align:top;margin:30px 5px;}

/* Responsive Design */
@media only screen and (max-width: 1800px) {
	.main_con{min-height:0;}
	main{height:100%;}
}

@media only screen and (max-width: 1400px) {
	.main_con{padding:0;}
	main .contents{display:block;}
	main .admins{width:100%;}
	main .forms{width:100%;display:flex;justify-content:space-between;margin:30px 0 0;}
	main .forms form{width:48%;}
}

@media only screen and (max-width: 1200px){
	.sidebar ul{margin:35px 0 0;}
}

@media only screen and (max-width: 1000px){
	main{padding:0 0 0 30px;}
}

@media only screen and (max-width: 800px){
	main .forms{display:block;}
	main .forms form{width:100%;}
}

@media only screen and (max-width: 600px){
	main input{font-size:15px;}
	main .admins{width:100%;clear:both;overflow-x:auto;}
	main .admins table{min-width: rem-calc(640);}
}

</style>

</head>
<body>
  <div id="main_area">
    <div class="wrapper">
      <?php
			$current_admin = DB::query("SELECT * FROM admin WHERE admin_id=?", array($_SESSION['admin']), "READ");

      if(count($current_admin)>0){
        $current_admin = $current_admin[0];
      ?>
      <div class="main_con">
        <!-- SIDEBAR -->
				<?php
					$currentSidebarPage = 'organizer';
					include("includes/sidebar-admin.php");
				?>

        <!-- MAIN -->
        <main>
          <h1><i class="fas fa-user-circle"></i> Admin: <?php echo $current_admin['admin_name']; ?></h1>
          <h2>Organizers</h2>
          <div class="contents">
            <div class="admins">
							<?php
							$orga = DB::query("SELECT * FROM organizer WHERE orga_id=?", array($_GET['orga_id']), "READ");
							$orga = $orga[0];
							echo "<h3>".$orga['orga_fname']." ".$orga['orga_lname']."</h3>";
							?>
							<table class="table-responsive table">
                <thead class="table-dark">
                  <tr>
    								<th>Document Type</th>
    								<th>Description</th>
    								<th>Date Added</th>
    								<th>Downloadable Resources</th>
                  </tr>
                </thead>
								<tbody>
	              <?php
								$counter = 1;
								$docu = DB::query("SELECT * FROM organizer o JOIN legal_document l ON o.orga_id = l.orga_id WHERE o.orga_id=?", array($_GET['orga_id']), "READ");

								if(count($docu)>0){
									foreach ($docu as $result) {
										echo "
										<tr>
											<td>".$result['docu_type']."</td>
											<td>".$result['docu_description']."</td>
											<td>".date("F j, Y", strtotime($result['docu_dateadded']))."</td>
											<td><a href='legal_docu/".$_GET['orga_id']."/".$result['docu_image']."' download='".$result['orga_fname']."-Legal-Documents".$counter."'>Download the file</a></td>
										</tr>
										";
										$counter++;
									}
								}
								?>
								</tbody>
							</table>
							<form method="post">
								<button class="edit" type="submit" name="btnVerify" onclick="return confirm('Are you sure you want to verify this organizer?');">Verify</button>
								<button class="edit" type="submit" name="btnReturn" onclick="return confirm('Are you sure you want to return this documents to organizer?');">Return</button>
								<a class="edit" href="admin-organizer.php">Back</a>
							</form>
            </div>
          </div>
        </main>
      </div>
      <?php
      }
      ?>
    </div>
  </div>
</body>
</html>
