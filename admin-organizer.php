<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

  // REDIRECT IF ADMIN NOT LOGGED IN
  if(!isset($_SESSION['admin'])) header("Location: login.php");

	if(isset($_GET['success'])){
		echo "<script>alert('Successfully verified!')</script>";
	}

	if(isset($_GET['return'])){
		echo "<script>alert('Successfully return organizer's verification request!')</script>";
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
main h3{font:600 30px/100% Montserrat,sans-serif;;margin-bottom:10px;text-align:center;}
main input{display:inline-block;width:99%;height:50px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 18px/20px Montserrat,sans-serif;padding:0 20px;margin:5px auto;border:1px solid #cfcfcf;}
main p:last-of-type{width:100%;color:red;font-size:20px;}

main .contents{display:flex;justify-content:space-between;margin:30px 0 0;}

main .admins{height:auto;width:100%;}

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
              <table class="table-responsive table">
                <thead class="table-dark">
                  <tr>
                    <th>ID#</th>
    								<th>Company Name</th>
    								<th>Name</th>
    								<th>Address</th>
    								<th>Phone</th>
    								<th>Email Address</th>
    								<th>Status</th>
    								<th></th>
    								<th></th>
    								<th></th>
                  </tr>
                </thead>
								<tbody>
                <?php
									// ALL ORGANIZERS RESULTS
                  $organizer = DB::query("SELECT * FROM organizer", array(), "READ");

                  if(count($organizer)>0){
                    foreach ($organizer as $result) {
                      echo "
                      <tr>
                        <td>".$result['orga_id']."</td>
                        <td>".$result['orga_company']."</td>
                        <td>".$result['orga_fname']." ".$result['orga_mi'].". ".$result['orga_lname']." </td>
                        <td>".$result['orga_address']."</td>
                        <td>".$result['orga_phone']."</td>
                        <td>".$result['orga_email']."</td>
											";

											$empty_data = ($result['orga_company'] == "" || $result['orga_address'] == "" || $result['orga_phone'] == "") ? true : false;

											if($result['orga_status'] == 2) {
												echo "<td style='color:#33b5e5;'><em>pending</em></td>";
												## CHECK IF THERE ARE MISSING DATA IN ORGANIZER PROFILE
												if($empty_data)
													echo "<td><a href='' onclick='return confirm(\"Organizer must complete profile data to verify!\");'>view</a></td>";
												else
													echo "<td><a href='admin-verify.php?orga_id=".$result['orga_id']."'>view</a></td>";
											} elseif($result['orga_status'] == 0) {
												echo "<td style='color:#ff4444;'><em>not verified</em></td>";
												echo "<td><em>no ratings</em></td>";
												echo "<td>---</td>";
											} else {
												if($result['orga_status'] == 1)
													echo "<td style='color:#00c851;'><em>verified</em></td>";
												else
													echo "<td style='color:red;'><em>banned</em></td>";
												##
												echo "<td>";
													echo $rate = orga_ratings($result['orga_id']);
												echo " <i class='fas fa-star'></i></td>";
												if($rate > 0)
													echo "<td><a href='admin-organizer-ratings.php?orga_id=".$result['orga_id']."'>view ratings</a></td>";
												else echo "<td></td>";
											}
                      echo "
												<td></td>
											</tr>
											";
                    }
										echo "	</tbody>";
										echo "</table>";

									## IF NO EXISTING ORGANIZER
                  } else {
										echo "	</tbody>";
										echo "</table>";
										echo "<p>No organizer exists!</p>";
									}
                ?>
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
