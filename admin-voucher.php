<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

  // REDIRECT IF ADMIN NOT LOGGED IN
  if(!isset($_SESSION['admin'])) header("Location: login.php");

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
main .admins select{float:left;margin:0 0 20px;height:40px;width:205px;max-width:100%;padding:0 10px;}
main .admins button{float:left;margin:0 10px 20px;height:40px;max-width:100%;padding:0 30px;border:1px solid #000;}
main .admins button:hover{background:#000;color:#fff;}

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

@media only screen and (max-width: 1200px){
	main .admins{width:100%;clear:both;overflow-x:auto;}
	main .admins table{min-width: rem-calc(640);}
}

@media only screen and (max-width: 800px){
	main h1{font-size:20px !important;}
}

@media only screen and (max-width: 500px){
	main .admins select{margin:0 0 10px;width:100%;}
	main .admins button{margin:0 0 20px;display:block;width:100%;}
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
					$currentSidebarPage = 'voucher';
					include("includes/sidebar-admin.php");
				?>

        <!-- MAIN -->
        <main>
          <h1><i class="fas fa-user-circle"></i> Admin: <?php echo $current_admin['admin_name']; ?></h1>
          <h2>Vouchers</h2>
          <div class="contents">
            <div class="admins">
							<form method="post">
								<select name="cboOption" required>
									<option value="">-- SELECT ORGANIZER --</option>
									<?php
									$orga = DB::query("SELECT * FROM organizer", array(), "READ");
									if(count($orga)>0){
										foreach ($orga as $result) {
											echo "
											<option value='".$result['orga_id']."'>".$result['orga_fname']." ".$result['orga_lname']."</option>
											";
										}
									}
									?>
								</select>
								<button type="submit" name="btnSearch">Search</button>
							</form>
              <table class="table-responsive table">
                <thead class="table-dark">
                  <tr>
										<th>Organizer ID</th>
										<th>Adventure Name</th>
                    <th>Code</th>
    								<th>Discount</th>
    								<th>Voucher Name</th>
    								<th>Start Date</th>
    								<th>End Date</th>
    								<th>Min. Spent</th>
    								<th>Used</th>
                  </tr>
                </thead>
								<tbody>
                <?php
								// WHEN BUTTON IS CLICKED
								if(isset($_POST['btnSearch'])){
									$cboOption = $_POST['cboOption'];

									// SELECT VOUCHER OF SPECIFIC ORGANIZER
									$voucher = DB::query("SELECT * FROM voucher WHERE orga_id=?", array($cboOption), "READ");

								// ALL VOUCHER RESULTS
								} else $voucher = DB::query("SELECT * FROM voucher", array(), "READ");

								// DISPLAY VOUCHER
                if(count($voucher)>0){
                  foreach ($voucher as $result) {
										$adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($result['adv_id']), "READ");
										$adv = $adv[0];
										echo "
										<tr>
											<td>".$result['orga_id']."</td>
												<td>".$adv['adv_name']."</td>
											<td>".$result['vouch_code']."</td>
											<td>".$result['vouch_discount']."%</td>
											<td>".$result['vouch_name']."</td>
											<td>".date("M. j, Y", strtotime($result['vouch_startdate']))."</td>
											<td>".date("M. j, Y", strtotime($result['vouch_enddate']))."</td>
											<td>".$result['vouch_minspent']."</td>
											<td>".$result['vouch_user']."</td>
										</tr>
										";
                  }
									echo "	</tbody>";
									echo "</table>";

								## IF NO EXISTING VOUCHER
								} else {
									echo "	</tbody>";
									echo "</table>";
									echo "<h3>No voucher added exists!</h3>";
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
