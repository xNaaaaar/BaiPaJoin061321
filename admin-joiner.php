<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

  // REDIRECT IF ADMIN NOT LOGGED IN
  if(!isset($_SESSION['admin'])) header("Location: login.php");

	if(isset($_GET['success'])){
		echo "<script>alert('Successfully verified!')</script>";
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
main h2{margin:15px 0;}

main .contents{display:flex;justify-content:space-between;margin:30px 0 0;}

main .admins{height:auto;width:100%;}

/* Responsive Design */
@media only screen and (max-width: 1800px) {
	main{height:100%;}
}

@media only screen and (max-width: 1400px) {
	.main_con{padding:0;}
	main .contents{display:block;}
	main .admins{width:100%;}

	.sidebar ul{margin:35px 0 0;padding-left:10px;}
}

@media only screen and (max-width: 1000px){
	main{padding:0 0 0 30px;}
}

@media only screen and (max-width: 800px){
	main h1{font-size:20px !important;}
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
					$currentSidebarPage = 'joiner';
					include("includes/sidebar-admin.php");
				?>

        <!-- MAIN -->
        <main>
          <h1><i class="fas fa-user-circle"></i> Admin: <?php echo $current_admin['admin_name']; ?></h1>
          <h2>Joiners</h2>
          <div class="contents">
            <div class="admins">
              <table class="table-responsive table">
                <thead class="table-dark">
                  <tr>
                    <th>ID#</th>
    								<th>Name</th>
    								<th>Address</th>
    								<th>Phone</th>
    								<th>Email Address</th>
    								<th>Total Bookings</th>
    								<th>Total Paid</th>
    								<th></th>
    								<th></th>
                  </tr>
                </thead>
								<tbody>
                <?php
									// ALL ORGANIZERS RESULTS
                  $joiner = DB::query("SELECT * FROM joiner", array(), "READ");

                  if(count($joiner)>0){
                    foreach ($joiner as $result) {
											echo "
                      <tr>
                        <td>".$result['joiner_id']."</td>
                        <td>".$result['joiner_fname']." ".$result['joiner_mi'].". ".$result['joiner_lname']." </td>
                        <td>".$result['joiner_address']."</td>
                        <td>".$result['joiner_phone']."</td>
                        <td>".$result['joiner_email']."</td>
											";

											$booked = DB::query("SELECT * FROM booking WHERE joiner_id=? AND book_status=?", array($result['joiner_id'], "paid"), "READ");

											if(count($booked)>0){
												$total = 0;
												foreach ($booked as $key) {
													$total += $key['book_totalcosts'];
												}
												echo "
												<td>".count($booked)."</td>
												<td>???".number_format($total,2,".",",")."</td>
												";
											} else {
												echo "
												<td>0</td>
												<td>0</td>
												";
											}

											echo "</tr>";
                    }
										echo "	</tbody>";
										echo "</table>";

									## IF NO EXISTING ORGANIZER
									} else {
										echo "	</tbody>";
										echo "</table>";
										echo "<p>No joiner exists!</p>";
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
