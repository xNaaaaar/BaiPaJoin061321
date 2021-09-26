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
main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin:15px 0;text-align:left;}
main h2 span{font-size:30px;}
main h2 span a:hover{color:#313131;text-decoration:none;}
main h3{font:600 30px/100% Montserrat,sans-serif;;margin-bottom:10px;text-align:center;}
main input{display:inline-block;width:99%;height:50px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 18px/20px Montserrat,sans-serif;padding:0 20px;margin:5px auto;border:1px solid #cfcfcf;}
main p:last-of-type{width:100%;color:red;font-size:20px;}

main .contents{display:flex;justify-content:space-between;margin:30px 0 0;}

main .admins{height:auto;width:100%;}
main .admins select{float:left;margin:0 0 20px;width:170px;height:40px;max-width:100%;padding:0 10px;}
main .admins button{float:left;margin:0 10px 20px;width:170px;height:40px;max-width:100%;padding:0 10px;}

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
					$currentSidebarPage = 'request';
					include("includes/sidebar-admin.php");
				?>

        <!-- MAIN -->
        <main>
          <h1><i class="fas fa-user-circle"></i> Admin: <?php echo $current_admin['admin_name']; ?></h1>
          <h2>Requests</h2>
          <div class="contents">
            <div class="admins">
							<form method="post">
								<select name="">
									<option value="">-- SELECT OPTION --</option>
									<option value="">Joiner</option>
									<option value="">Organizer</option>
								</select>
								<button type="button" name="button">Search</button>
							</form>
              <table class="table-responsive table">
                <thead class="table-dark">
                  <tr>
                    <th>ID#</th>
    								<th>Book Id</th>
    								<th>Request User</th>
    								<th>Request Type</th>
    								<th>Request Date Processed</th>
    								<th>Request Amount</th>
    								<th>Request Status</th>
    								<th>Request Reason</th>
    								<th></th>
    								<th></th>
                  </tr>
                </thead>
								<tbody>
                <?php
									// ALL ORGANIZERS RESULTS
                  $request = DB::query("SELECT * FROM request ORDER BY req_dateprocess DESC", array(), "READ");

                  if(count($request)>0){
										foreach ($request as $result) {
											echo "
											<tr>
		                    <td>".$result['req_id']."</td>
		    								<td>".$result['book_id']."</td>
		    								<td>".$result['req_user']."</td>
		    								<td>".$result['req_type']."</td>
		    								<td>".date("M. j, Y", strtotime($result['req_dateprocess']))."</td>
		    								<td>₱".number_format($result['req_amount'],2,'.',',')."</td>
		    								<td>".$result['req_status']."</td>
		    								<td>".$result['req_reason']."</td>
		    								<td>approved</td>
		    								<td></td>
		                  </tr>
											";
										}

									## IF NO EXISTING ORGANIZER
									} else {
										echo "	</tbody>";
										echo "</table>";
										echo "<p>No requests exists!</p>";
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
