<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

  // REDIRECT IF ADMIN NOT LOGGED IN
  if(!isset($_SESSION['admin'])) header("Location: login.php");

	## IF REQUEST CANCELATION IS APPROVED (FOR JOINER)
	if(isset($_GET['approved']) && isset($_GET['req_id'])){
		## UPDATE REQUEST DATE APPROVED && STATUS
		DB::query("UPDATE request SET req_dateresponded=?, req_status=? WHERE req_id=?", array(date("Y-m-d"), "approved", $_GET['req_id']), "UPDATE");

		## QUERY THE SPECIFIC REQ_ID
		$req = DB::query("SELECT * FROM request WHERE req_id=?", array($_GET['req_id']), "READ");
		$req = $req[0];

		## GET THE ADVENTURE TO CALCULATE REFUNDED AMOUNT
		$adv = DB::query("SELECT * FROM booking b INNER JOIN adventure a ON b.adv_id = a.adv_id WHERE book_id=?", array($req['book_id']), "READ");
		$adv = $adv[0];

		## UPDATE ADVENTURE CURRENT GUESTS
		DB::query("UPDATE adventure SET adv_currentGuest=? WHERE adv_id=?", array($adv['adv_currentGuest'] - $adv['book_guests'], $adv['adv_id']), "UPDATE");

		## UPDATE BOOKING PAID TO REFUNDED
		## DB::query("UPDATE booking SET book_status=? WHERE book_id=?", array("refunded", $req['book_id']), "UPDATE");

		## TO BE REFUNDED AMOUNT
		$adv_price = ($adv['adv_totalcostprice'] / $adv['adv_maxguests']) * $adv['book_guests'];
		$cancel_fee = $adv_price * 0.3;
		$final_price = $adv_price - $cancel_fee;

		## ADD NEW REQUEST AS REFUND
		DB::query("INSERT INTO request(req_user, req_type, req_dateprocess, req_dateresponded, req_amount, req_status, req_rcvd, book_id) VALUES(?,?,?,?,?,?,?,?)", array($req['req_user'], "refund", date("Y-m-d"), date("Y-m-d"), $final_price, "approved", 0, $req['book_id']), "CREATE");

		## EMAIL + SMS NOTIFICATION
		$booking_joiner_id_db = DB::query("SELECT joiner_id FROM booking WHERE book_id = ?", array($req['book_id']), "READ");
		$booking_joiner_id = $booking_joiner_id_db[0];

		if($req['req_user'] == 'joiner') {
			$joiner_db = DB::query("SELECT * FROM joiner WHERE joiner_id = ?", array($booking_joiner_id['joiner_id']), "READ");
			$joiner_info = $joiner_db[0];

			$sms_sendto = $joiner_info['joiner_phone'];
			$sms_message = "Hi ".$joiner_info['joiner_fname']."! Your request to cancel & refund has been approved!";

			send_sms($sms_sendto,$sms_message);

			$email_message = html_cancellation_message($joiner_info['joiner_fname'], 'Joiner',$req['book_id']);

			$img_address = array();
			$img_name = array();

			array_push($img_address,'images/cancel-bg.png','images/main-logo-green.png','images/cancel-img.png');
			array_push($img_name,'background','logo','main');

			send_email($joiner_info['joiner_email'], "BOOKING CANCELATION APPROVED", $email_message, $img_address, $img_name);
		}

		echo "<script>alert('Successfully approved cancelation!')</script>";
	}

	## IF REQUEST CANCELATION IS APPROVED (FOR ORGANIZER)
	if(isset($_GET['approved_o']) && isset($_GET['req_id'])){
		## QUERY THE SPECIFIC REQ_ID
		$req = DB::query("SELECT * FROM request WHERE req_id=?", array($_GET['req_id']), "READ");
		$req = $req[0];

		## ADV STATUS WILL BE CANCELED
		DB::query("UPDATE adventure SET adv_status=? WHERE adv_id=?", array("canceled", $req['adv_id']), "UPDATE");

		## CHECK IF THIS TO CANCEL ADVENTURE BY ORGANIZER HAVE SOMEONE WHO RESCHEDULE
		## GET THE ADVENTURE TO BE CANCEL IN BOOKING
		## CHECK IF THE BOOKING ID IS IN REQUEST WITH REQ_STATUS = RESCHEDULE
		$adv_canceled = DB::query("SELECT * FROM booking b INNER JOIN request r ON b.book_id=r.book_id WHERE req_status=? AND b.adv_id=?", array("rescheduled", $req['adv_id']), "READ");

		## DELETE ALL RESCHEDULED IN REQUEST TO THIS CANCELED ADVENTURE
		if(count($adv_canceled)>0){
			foreach ($adv_canceled as $result) {
				DB::query("DELETE FROM request WHERE req_id=?", array($result['req_id']), "DELETE");
			}
		}

		## UPDATE REQUEST PENDING TO APPROVED STATUS, DATE PROCESSED
		DB::query("UPDATE request SET req_dateresponded=?, req_status=? WHERE req_id=?", array(date("Y-m-d"), "approved", $_GET['req_id']), "UPDATE");

		$orga_id_db = DB::query("SELECT orga_id FROM adventure WHERE adv_id = ?", array($req['adv_id']), "READ");
		$orga_db1 = $orga_id_db[0];

		## EMAIL + SMS NOTIFICATION FOR ORGANIZER
		if($req['req_user'] == 'organizer') {
			$orga_db = DB::query("SELECT * FROM organizer WHERE orga_id = ?", array($orga_db1[0]), "READ");
			$orga_info = $orga_db[0];

			$sms_sendto = $orga_info['orga_phone'];
			$sms_message = "Hi ".$orga_info['orga_fname']."! Your request to cancel an adventure has been approved!";

			send_sms($sms_sendto,$sms_message);

			$email_message = html_cancellation_message($orga_info['orga_fname'], 'Organizer',null);

			$img_address = array();
			$img_name = array();

			array_push($img_address,'images/cancel-bg.png','images/main-logo-green.png','images/cancel-img.png');
			array_push($img_name,'background','logo','main');

			send_email($orga_info['orga_email'], "BOOKING CANCELATION APPROVED", $email_message, $img_address, $img_name);

			## EMAIL NOTIFICATION FOR JOINER DUE TO ORGANIZER CANCELLATION
			$book_db = DB::query("SELECT * FROM booking WHERE adv_id = ?", array($req['adv_id']), "READ");

			if(!empty($book_db) && count($book_db) > 0) {
				foreach($book_db as $book) {
					$joiner_info = DB::query("SELECT * FROM joiner WHERE joiner_id = ?", array($book['joiner_id']), "READ");

					if(!empty($joiner_info)) {
						$joiner_info = $joiner_info[0];

						$email_message = html_cancellation_message($joiner_info['joiner_fname'], 'JoinerOrganizer', $book['book_id']);

						$img_address = array();
						$img_name = array();

						array_push($img_address,'images/cancel-bg.png','images/main-logo-green.png','images/cancel-img.png');
						array_push($img_name,'background','logo','main');

						send_email($joiner_info['joiner_email'], "ORGANIZER ADVENTURE CANCELATION", $email_message, $img_address, $img_name);
					}
				}
			}

			echo "<script>alert('Successfully approved cancelation!')</script>";
		}
	}

	## IF REQUEST CANCELATION IS DISAPPROVED (FOR JOINER && ORGANIZER)
	if(isset($_GET['disapproved']) && isset($_GET['req_id'])) {
		## UPDATE REQUEST STATUS
		DB::query("UPDATE request SET req_dateresponded=?, req_status=? WHERE req_id=?", array(date("Y-m-d"), "disapproved", $_GET['req_id']), "UPDATE");

		$req = DB::query("SELECT * FROM request WHERE req_id=?", array($_GET['req_id']), "READ");
		$req = $req[0];

		if($req['req_user'] == 'joiner') {
			$booking_joiner_id_db = DB::query("SELECT joiner_id FROM booking WHERE book_id = ?", array($req['book_id']), "READ");
			$booking_joiner_id = $booking_joiner_id_db[0];

			$joiner_db = DB::query("SELECT * FROM joiner WHERE joiner_id = ?", array($booking_joiner_id['joiner_id']), "READ");
			$joiner_info = $joiner_db[0];

			$email_message = html_cancellation_message($joiner_info['joiner_fname'], 'denied',null);

			$img_address = array();
			$img_name = array();

			array_push($img_address,'images/cancel-bg.png','images/main-logo-green.png','images/cancel-img.png');
			array_push($img_name,'background','logo','main');

			send_email($joiner_info['joiner_email'], "BOOKING CANCELATION DENIED", $email_message, $img_address, $img_name);
		}

		else if($req['req_user'] == 'organizer') {
			$orga_id_db = DB::query("SELECT orga_id FROM adventure WHERE adv_id = ?", array($req['adv_id']), "READ");
			$orga_db1 = $orga_id_db[0];

			$orga_db = DB::query("SELECT * FROM organizer WHERE orga_id = ?", array($orga_db1[0]), "READ");
			$orga_info = $orga_db[0];

			$email_message = html_cancellation_message($orga_info['orga_fname'], 'denied');

			$img_address = array();
			$img_name = array();

			array_push($img_address,'images/cancel-bg.png','images/main-logo-green.png','images/cancel-img.png');
			array_push($img_name,'background','logo','main');

			send_email($orga_info['orga_email'], "BOOKING CANCELATION DENIED", $email_message, $img_address, $img_name);
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
main h3{font:600 30px/100% Montserrat,sans-serif;;margin-bottom:10px;text-align:center;}
main input{display:inline-block;width:99%;height:50px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 18px/20px Montserrat,sans-serif;padding:0 20px;margin:5px auto;border:1px solid #cfcfcf;}
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
					$currentSubMenu = 'cancel';
					include("includes/sidebar-admin.php");
				?>

        <!-- MAIN -->
        <main>
          <h1><i class="fas fa-user-circle"></i> Admin: <?php echo $current_admin['admin_name']; ?></h1>
          <h2>Cancelation</h2>
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
										<th>Date Responded</th>
										<th>Amount</th>
										<th>Reason</th>
										<th>Status</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
          <?php
					## BUTTON SEARCH IS CLICKED
					if(isset($_POST['btnSearch'])){
						$cboOption = $_POST['cboOption'];
						## FOR ORGANIZER && JOINER
						$request = DB::query("SELECT * FROM request WHERE req_user=? AND req_type=? AND (req_status=? || req_status=?)", array($cboOption, "cancel", "approved", "disapproved"), "READ");

					## ALL CANCELLATION APPROVED RESULTS
					} else {
						$request = DB::query("SELECT * FROM request WHERE req_type=? AND (req_status=? || req_status=?)", array("cancel", "approved", "disapproved"), "READ");
					}

					## DISPLAY
					if(count($request)>0){
						foreach ($request as $result) {
							echo "
							<tr>
								<td>".$result['req_id']."</td>
								<td>".$result['book_id']."</td>
								<td>".$result['req_user']."</td>
								<td>".$result['req_type']."</td>
								<td>".date("M. j, Y", strtotime($result['req_dateprocess']))."</td>
								<td>".date("M. j, Y", strtotime($result['req_dateresponded']))."</td>
								<td>₱".number_format($result['req_amount'],2,'.',',')."</td>
								<td>".$result['req_reason']."</td>";

							if($result['req_status'] == "approved")
								echo "<td style='color:#5cb85c;'><em>approved</em></td>";
							else
								echo "<td style='color:red;'><em>disapproved</em></td>";

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
						echo "<p>No cancelation exists!</p>";
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
