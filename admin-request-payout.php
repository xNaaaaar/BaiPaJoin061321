<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

  // REDIRECT IF ADMIN NOT LOGGED IN
  if(!isset($_SESSION['admin'])) header("Location: login.php");

	## UPLOADING PROOF OF PAYMENT SUCCESS MESSAGE
	if(isset($_GET['success'])) echo "<script>alert('Proof of payment successfully uploaded!')</script>";

	## FOR JOINER
	// if(isset($_GET['req_id']) && isset($_GET['refunded'])){
	// 	## UPDATE this.req_id
	// 	DB::query("UPDATE request SET req_rcvd=? WHERE req_id=?", array(1, $_GET['req_id']), "READ");
	//
	// 	## DISPLAY this.req_id
	// 	$refund = DB::query("SELECT * FROM request WHERE req_id=?", array($_GET['req_id']), "READ");
	// 	$refund = $refund[0];
	//
	// 	## CREATE REQUEST FOR REFUNDED REQUEST
	// 	$payout = DB::query("INSERT INTO request(req_user, req_type, req_dateprocess, req_dateresponded, req_amount, req_status, req_rcvd, book_id) VALUES(?,?,?,?,?,?,?,?)", array($refund['req_user'], "payout", date("Y-m-d"), date("Y-m-d"), $refund['req_amount'], "refunded", 0, $refund['book_id']), "CREATE");
	//
	// 	## EMAIL + SMS NOTIFICATION
	// 	if($refund['req_user'] == 'joiner') {
	// 		$request_joiner_id_db = DB::query("SELECT joiner_id FROM booking WHERE book_id = ?", array($refund['book_id']), "READ");
	// 		$request_joiner_id = $request_joiner_id_db[0];
	//
	// 		$joiner_db = DB::query("SELECT * FROM joiner WHERE joiner_id = ?", array($request_joiner_id['joiner_id']), "READ");
	// 		$joiner_info = $joiner_db[0];
	//
	// 		$sms_sendto = $joiner_info['joiner_phone'];
	// 		$sms_message = "Hi ".$joiner_info['joiner_fname']."! The amount ".$refund['req_amount']." has been successfully refunded on ".date('d-M-Y').".";
	//
	// 		send_sms($sms_sendto,$sms_message);
	//
	// 		$email_message = html_payout_message($joiner_info['joiner_fname'], 'Joiner', $refund['req_amount']);
	//
	// 		$img_address = array();
	// 		$img_name = array();
	//
	// 		array_push($img_address,'images/payout-bg.png','images/main-logo-green.png','images/payout-img.png');
	// 		array_push($img_name,'background','logo','main');
	//
	// 		send_email($joiner_info['joiner_email'], "REFUND PAYOUT SUCCESSFUL", $email_message, $img_address, $img_name);
	// 	}
	//
	// 	echo "<script>alert('Successfully sent refund!')</script>";
	// }

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
main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin:15px 0;text-align:left;}
main h2 span{font-size:30px;}
main h2 span a:hover, main a:hover{color:#313131;text-decoration:none;}
main h3{font:600 30px/100% Montserrat,sans-serif;;margin-bottom:10px;text-align:center;}
main p:last-of-type{width:100%;color:red;font-size:20px;}

main .contents{display:flex;justify-content:space-between;margin:30px 0 0;}

main .admins{height:auto;width:100%;}
main .admins select{float:left;margin:0 0 20px;height:40px;max-width:100%;padding:0 10px;}
main .admins button{float:left;margin:0 10px 20px;height:40px;max-width:100%;padding:0 30px;}

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
					$currentSubMenu = 'payout';
					include("includes/sidebar-admin.php");
				?>

        <!-- MAIN -->
        <main>
          <h1><i class="fas fa-user-circle"></i> Admin: <?php echo $current_admin['admin_name']; ?></h1>
          <h2>Payout (for organizer)</h2>
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
										<th>Adv Id</th>
										<th>User</th>
										<th>Type</th>
										<th>Date Processed</th>
										<th>Date Responded</th>
										<th>Amount</th>
										<th>Status</th>
										<th>Proof of Payment</th>
									</tr>
								</thead>
								<tbody>
          <?php
					## BUTTON SEARCH IS CLICKED
					if(isset($_POST['btnSearch'])){
						$cboOption = $_POST['cboOption'];
						## FOR ORGANIZER && JOINER
						$request = DB::query("SELECT * FROM request WHERE req_user=? AND req_type=? AND (req_status=? || req_status=? || req_status=?)", array($cboOption, "payout", "paid", "approved", "refunded"), "READ");

					## ALL REFUND APPROVED RESULTS
					} else {
						$request = DB::query("SELECT * FROM request WHERE req_type=? AND (req_status=? || req_status=? || req_status=?)", array("payout", "paid", "approved", "refunded"), "READ");
					}

					## DISPLAY
					if(count($request)>0){
						foreach ($request as $result) {
							echo "
							<tr>
								<td>".$result['req_id']."</td>
								<td>".$result['adv_id']."</td>
								<td>".$result['req_user']."</td>
								<td>".$result['req_type']."</td>
								<td>".date("M. j, Y", strtotime($result['req_dateprocess']))."</td>
								<td>".date("M. j, Y", strtotime($result['req_dateresponded']))."</td>
								<td>₱".number_format($result['req_amount'],2,'.',',')."</td>";

							## CHECK IF RECEIVED BY USER
							if($result['req_rcvd'] == 0) ## 0 MEANS PENDING REQUEST / RECEIPT PROOF NOT UPLOADED
								echo "<td style='color:#33b5e5;'><em>pending<em></td>";
							elseif($result['req_rcvd'] == 2) ## 2 MEANS SENT MONEY TO ORGA INFO
								echo "<td style='color:#5cb85c;'><em>sent<em></td>";
							else ## 1 MEANS MONEY RECEIVED BY ORGA AND RECEIVED BUTTON IS CLICKED
								echo "<td style='color:#5cb85c;'><em>received<em></td>";

							## CHECK IF STATUS IS NOT PAID
							if($result['req_status'] == "approved"){
								echo "<td><a href='admin-request-payout-proof.php?req_id=".$result['req_id']."' onclick='return confirm(\"Are you sure you want to upload proof of payment?\");'>upload</a></td>";
							} else {
								echo "<td><a href='images/admin/".$_SESSION['admin']."/".$result['req_img']."' download='proof-of-payment-".$result['req_id']."'>Download Proof</a></td>";
							}

							echo "</tr>";

						}
						echo "	</tbody>";
						echo "</table>";

					## IF NO EXISTING ORGANIZER
					} else {
						echo "	</tbody>";
						echo "</table>";
						echo "<p>No payout exists!</p>";
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
