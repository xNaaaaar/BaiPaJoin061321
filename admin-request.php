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
.sidebar ul ul{margin:0 0 0 10px;}

main{flex:4;float:none;height:100%;background:none;margin:0;padding:50px 50px 0;border-radius:0;text-align:center;}
main h1{text-align:right;font-size:20px;}
main h2{margin:15px 0;}

main .contents{display:flex;justify-content:space-between;margin:30px 0 0;}

main .admins{height:auto;width:100%;}
main .admins select{float:left;margin:0 0 20px;height:40px;width:205px;max-width:100%;padding:0 10px;}
main .admins button{float:left;margin:0 10px 20px;height:40px;max-width:100%;padding:0 30px;border:1px solid #000;}
main .admins button:hover{background:#000;color:#fff;}
main .admins table tr td:nth-child(8){color:#33b5e5;}
main .admins table tr td:nth-child(9) a{color:#5cb85c;}
main .admins table tr td:last-child a{color:red;}

/* Responsive Design */
@media only screen and (max-width: 1800px) {
	main{height:100%;}
}

@media only screen and (max-width: 1400px) {
	main .contents{display:block;}
	main .admins{width:100%;}

	.sidebar ul{margin:35px 0 0;padding-left:10px;}
}

@media only screen and (max-width: 1200px){
	main .admins{width:100%;clear:both;overflow-x:auto;}
	main .admins table{min-width: rem-calc(640);}
}

@media only screen and (max-width: 1000px){
	main{padding:0 0 0 30px;}
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
				$currentSubMenu = '';
				$currentSidebarPage = 'request';
					include("includes/sidebar-admin.php");
				?>

        <!-- MAIN -->
        <main>
          <h1><i class="fas fa-user-circle"></i> Admin: <?php echo $current_admin['admin_name']; ?></h1>
          <h2>Pending Requests</h2>
          <div class="contents">
            <div class="admins">
							<form method="post">
								<select name="cboOption" required>
									<option value="">-- SELECT USER --</option>
									<option value="joiner">Joiner</option>
									<option value="organizer">Organizer</option>
								</select>
								<button type="submit" name="btnSearch">Search</button>
							</form>
							<table class="table-responsive table">
								<thead class="table-dark">
									<tr>
										<th>ID#</th>
										<th>Book Id</th>
										<th>User</th>
										<th>Type</th>
										<th>Date Processed</th>
										<th>Amount</th>
										<th>Reason</th>
										<th>Status</th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
          <?php
					## BUTTON SEARCH IS CLICKED
					if(isset($_POST['btnSearch'])){
						$cboOption = $_POST['cboOption'];
						## FOR ORGANIZER && JOINER
						$request = DB::query("SELECT * FROM request WHERE req_user=? AND req_status=?", array($cboOption, "pending"), "READ");

					## ALL REQUEST RESULTS
					} else {
						$request = DB::query("SELECT * FROM request WHERE req_status=?", array("pending"), "READ");
					}

					## DISPLAY
					if(count($request)>0){
						foreach ($request as $result) {
							echo "<tr>
								<td>".$result['req_id']."</td>
								<td>".$result['book_id']."</td>
								<td>".$result['req_user']."</td>
								<td>".$result['req_type']."</td>
								<td>".date("M. j, Y", strtotime($result['req_dateprocess']))."</td>
								<td>₱".number_format($result['req_amount'],2,'.',',')."</td>
								<td>".$result['req_reason']."</td>
								<td><em>".$result['req_status']."</em></td>";

								if($result['req_user'] == 'joiner'){
									echo "
									<td><b><a href='admin-request-cancel.php?req_id=".$result['req_id']."&approved' onclick='return confirm(\"Are you sure you want to approved this cancelation request?\");'>✓</a></b></td>";

								## FOR ORGANIZER
								} else {
									echo "
									<td><b><a href='admin-request-cancel.php?req_id=".$result['req_id']."&approved_o' onclick='return confirm(\"Are you sure you want to approved this cancelation request?\");'>✓</a></b></td>";
								}

							echo "
							<td><b><a href='admin-request-cancel.php?req_id=".$result['req_id']."&disapproved' onclick='return confirm(\"Are you sure you want to disapproved this request?\");'>✗</a></b></td>
							</tr>";
						}
						echo "	</tbody>";
						echo "</table>";

					## IF NO EXISTING ORGANIZER
					} else {
						echo "	</tbody>";
						echo "</table>";
						echo "<h3>No pending requests exists!</h3>";
					}
					?>
            </div>
          </div>
        </main>
      </div>
		<?php } ?>
    </div>
  </div>
</body>
</html>
