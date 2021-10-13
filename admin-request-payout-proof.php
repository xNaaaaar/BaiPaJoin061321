<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

  // REDIRECT IF ADMIN NOT LOGGED IN
  if(!isset($_SESSION['admin'])) header("Location: login.php");

	## WHEN BUTTON UPLOAD IS CLICKED
	if(isset($_POST['btnUpload'])){
		$imageName = uploadImage('fileProofImage', "images/admin/".$_SESSION['admin']."/");

		## ERROR TRAPPINGS
		if($imageName === 1){
			echo "<script>alert('An error occurred in uploading your image!')</script>";

		} else if($imageName === 2){
			echo "<script>alert('File type is not allowed!')</script>";

		} else {
			DB::query('UPDATE request SET req_img=?, req_status=?, req_rcvd=? WHERE req_id=?', array($imageName, "paid", 2, $_GET['req_id']), "CREATE");

			## EMAIL + SMS NOTIFICATION
			$refund = DB::query("SELECT * FROM request WHERE req_id = ?", array($_GET['req_id']), "READ");
			$refund = $refund[0];

			$orga_id = DB::query("SELECT orga_id FROM adventure WHERE adv_id = ?", array($refund['adv_id']), "READ");
			$orga_id = $orga_id[0];

			$organizer_db = DB::query("SELECT * FROM organizer WHERE orga_id = ?", array($orga_id['orga_id']), "READ");
			$organizer_info = $organizer_db[0];

			$sms_sendto = $organizer_info['orga_phone'];
			$sms_message = "Hi ".$organizer_info['orga_fname']."! The amount ".$refund['req_amount']." has been successfully refunded on ".date('d-M-Y').".";

			send_sms($sms_sendto,$sms_message);

			$email_message = html_payout_message($organizer_info['orga_fname'], 'Organizer', $refund['req_amount']);

			$img_address = array();
			$img_name = array();

			array_push($img_address,'images/payout-bg.png','images/main-logo-green.png','images/payout-img.png');
			array_push($img_name,'background','logo','main');

			send_email($organizer_info['orga_email'], "REFUND PAYOUT SUCCESSFUL", $email_message, $img_address, $img_name);

			header("Location: admin-request-payout.php?success");
		}
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
.sidebar ul ul{margin:0 0 0 10px;}

main{flex:4;float:none;height:100%;background:none;margin:0;padding:50px 50px 0;border-radius:0;text-align:center;}
main h1{text-align:right;font-size:20px;}
main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin:15px 0;text-align:left;}
main h2 span{font-size:30px;}
main h2 span a:hover, main a:hover{color:#313131;text-decoration:none;}
main h3{font:600 30px/100% Montserrat,sans-serif;;margin-bottom:15px;text-align:left;}

main .contents{display:flex;justify-content:space-between;margin:30px 0 0;}

main .admins{height:auto;width:100%;}
main section{height:auto;width:700px;max-width:100%;box-shadow:10px 10px 10px -5px #cfcfcf;border-radius:10px;padding:30px;line-height:35px;margin:0;border:1px solid #cfcfcf;}
main section table{height:auto;width:100%;text-align:left;}

main form input{margin:30px 5px 15px;display:block;}

main .edit{float:left;display:inline-block;width:150px;height:45px;font:normal 18px/45px Montserrat,sans-serif;border-radius:0;vertical-align:top;margin:0 5px;}

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
          <h2>Uploading Proof of Payment</h2>
          <div class="contents">
            <div class="admins">
							<?php
							$details = DB::query("SELECT * FROM request r JOIN adventure a ON r.adv_id=a.adv_id JOIN organizer o ON a.orga_id=o.orga_id WHERE req_id=?", array($_GET['req_id']), "READ");
							$details = $details[0];
							?>
							<section>
								<h3><?php echo $details['orga_fname']; ?>'s Adventure Details</h3>
								<table>
									<tr>
										<td>Adventure ID</td>
										<td>: <b><?php echo $details['adv_id']; ?></b></td>
									</tr>
									<tr>
										<td>Adventure Name</td>
										<td>: <b><?php echo $details['adv_name']; ?></b></td>
									</tr>
									<tr>
										<td>Adventure Activity</td>
										<td>: <b><?php echo $details['adv_kind']; ?></b></td>
									</tr>
									<tr>
										<td>Adventure Type</td>
										<td>: <b><?php echo $details['adv_type']; ?></b></td>
									</tr>
									<tr>
										<td>Expected Guests</td>
										<td>: <b><?php echo $details['adv_maxguests']; ?></b></td>
									</tr>
									<tr>
										<td>Booked Guests</td>
										<td>: <b><?php echo $details['adv_currentGuest']; ?></b></td>
									</tr>
									<tr>
										<td>Total Amount</td>
										<td>: <b><?php echo "₱".number_format($details['req_amount'],2,".",","); ?></b></td>
									</tr>
								</table>
							</section>

							<form method="post" enctype="multipart/form-data">
								<input type='file' name='fileProofImage' required></input>
								<button class='edit' type='submit' name='btnUpload' onclick='return confirm(\"Confirm upload proof of payment?\");'>Upload Proof</button>
								<a class='edit' href="admin-request-payout.php">Back</a>
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
