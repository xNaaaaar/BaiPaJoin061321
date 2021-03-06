<?php
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
date_default_timezone_set('Asia/Manila');

##### CODE START HERE @CREATING ACCOUNT (JOINER or ORGANIZER) #####
function createAccount(){
	$txtFirstname = trim(ucwords($_POST['txtFirstname']));
	$txtLastname = trim(ucwords($_POST['txtLastname']));
	$txtMi = trim(ucwords($_POST['txtMi']));
	$cboType = trim(ucwords($_POST['cboType']));
	$emEmail = trim($_POST['emEmail']);
	$pwPassword = trim(md5($_POST['pwPassword']));

	//CHECK IF EMAIL ALREADY EXIST FOR JOINER
	$check_joiner_email = DB::query("SELECT * FROM joiner WHERE joiner_email=?", array($emEmail), "READ");
	//CHECK IF EMAIL ALREADY EXIST FOR ORGANIZER
	$check_orga_email = DB::query("SELECT * FROM organizer WHERE orga_email=?", array($emEmail), "READ");
	//CHECK IF EMAIL ALREADY EXIST FOR ADMIN
	$check_admin_email = DB::query("SELECT * FROM admin WHERE admin_email=?", array($emEmail), "READ");
	//
	if(count($check_joiner_email)>0 || count($check_orga_email)>0 || count($check_admin_email)>0){
		echo "<script>alert('Email address already exists!')</script>";
	}
	//ERROR HANDLING
	else {
		if(preg_match('/\d/', $txtFirstname)){
			echo "<script>alert('Firstname cannot have a number!')</script>";
		}
		else if(preg_match('/\d/', $txtLastname)){
			echo "<script>alert('Lastname cannot have a number!')</script>";
		}
		else if(preg_match('/\d/', $txtMi)){
			echo "<script>alert('MI cannot have a number!')</script>";
		}
		else {
			if($cboType == "Joiner") {
				//ADD NEW JOINER ACCOUNT
				DB::query("INSERT INTO joiner(joiner_fname, joiner_lname, joiner_mi, joiner_email, joiner_password) VALUES(?,?,?,?,?)", array($txtFirstname, $txtLastname, $txtMi, $emEmail, $pwPassword), "CREATE");

				//GET THE JOINER ID
				$userid = DB::query("SELECT * FROM joiner WHERE joiner_email = ?", array($emEmail), "READ");

				//CHECK EXISTING EMAIL
				if(count($userid)>0){
					$userid = $userid[0];
					$ratePathImages = 'images/joiners/'.$userid['joiner_id'];
					//CREATE A FOLDER FOR THEIR RATINGS IMAGES
					if(!file_exists($ratePathImages)) mkdir($ratePathImages,0777,true);
				}
			} else {
				//ADD NEW ORGANIZER ACCOUNT
				DB::query("INSERT INTO organizer(orga_fname, orga_lname, orga_mi, orga_email, orga_password, orga_status) VALUES(?,?,?,?,?,?)", array($txtFirstname, $txtLastname, $txtMi, $emEmail, $pwPassword, 0), "CREATE");

				//GET THE ORGANIZER ID
				$userid = DB::query("SELECT * FROM organizer WHERE orga_email = ?", array($emEmail), "READ");

				//CHECK EXISTING EMAIL
				if(count($userid)>0){
					$userid = $userid[0];
					$documentPathImages = 'legal_docu/'.$userid['orga_id'];
					$adventurePathImages = 'images/organizers/'.$userid['orga_id'];
					//CREATE A FOLDER FOR THEIR LEGAL DOCU IMAGES
					if(!file_exists($documentPathImages)) mkdir($documentPathImages,0777,true);
					//CREATE A FOLDER FOR THEIR ADVENTURES IMAGES
					if(!file_exists($adventurePathImages)) mkdir($adventurePathImages,0777,true);
				}
			}

			$img_address = array();
		  	$img_name = array();

		 	array_push($img_address,'images/welcome-bg.jpg',
		 		'images/main-logo-green.png','images/welcome-img.jpg');

		  	array_push($img_name,'background','logo','main');

			$email_subject = 'WELCOME TO BAIPAJOIN';
			$email_message = html_welcome_message($txtFirstname, $cboType);

			send_email($emEmail, $email_subject, $email_message, $img_address, $img_name);

			//SUCCESSFUL MESSAGE
			echo "<script>alert('Account created successfully!')</script>";
		}
	}
}

##### CODE START HERE @CREATING ACCOUNT FOR ADMIN #####
function create_admin_account(){
	$txtName = trim(ucwords($_POST['txtName']));
	$emEmail = trim($_POST['emEmail']);
	$pwPass = trim(md5($_POST['pwPass']));

	$check_admin = DB::query("SELECT * FROM admin WHERE admin_email=?", array($emEmail), "READ");
	$check_joiner = DB::query("SELECT * FROM joiner WHERE joiner_email=?", array($emEmail), "READ");
	$check_orga = DB::query("SELECT * FROM organizer WHERE orga_email=?", array($emEmail), "READ");

	if(count($check_admin)>0 || count($check_joiner)>0 || count($check_orga)>0)
		header("Location: admin.php?exists");
	else {
		DB::query("INSERT INTO admin(admin_name, admin_email, admin_pass) VALUES(?,?,?)", array($txtName, $emEmail, $pwPass), "CREATE");

		//GET THE ORGANIZER ID
		$admin = DB::query("SELECT * FROM admin WHERE admin_email = ?", array($emEmail), "READ");
		$admin = $admin[0];
		// CREATE FOLDER TO ADMIN
		$payment_proof = 'images/admin/'.$admin['admin_id'];
		//CREATE A FOLDER FOR THEIR ADVENTURES IMAGES
		if(!file_exists($payment_proof)) mkdir($payment_proof,0777,true);

		header("Location: admin.php?added");
	}
}

##### CODE START HERE @LOGIN ACCOUNT (JOINER or ORGANIZER) #####
function loginAccount(){
	$emEmail = trim($_POST['emEmail']);
	$pwPassword = trim(md5($_POST['pwPassword']));

	$joinerAccount = DB::query('SELECT * FROM joiner WHERE joiner_email=? AND joiner_password=?', array($emEmail, $pwPassword), "READ");
	$organizerAccount = DB::query('SELECT * FROM organizer WHERE orga_email=? AND orga_password=?', array($emEmail, $pwPassword), "READ");
	$adminAccount = DB::query('SELECT * FROM admin WHERE admin_email=? AND admin_pass=?', array($emEmail, $pwPassword), "READ");

	// JOINER ACCOUNT
	if(count($joinerAccount)>0){
		$joinerAccount = $joinerAccount[0];
		$_SESSION['joiner'] = $joinerAccount['joiner_id'];

		header('Location: index.php');
		exit;

	// ORGANIZER ACCOUNT
	} else if(count($organizerAccount)>0){
		$organizerAccount = $organizerAccount[0];
		$_SESSION['organizer'] = $organizerAccount['orga_id'];

		header('Location: index.php');
		exit;

	// ADMIN ACCOUNT
	} else if(count($adminAccount)>0){
		$adminAccount = $adminAccount[0];
		$_SESSION['admin'] = $adminAccount['admin_id'];

		header('Location: admin.php');
		exit;

	} else
		echo "<script>alert('Email address or password is incorrect!')</script>";
}

##### CODE START HERE @LOGIN ACCOUNT USING GMAIL FOR JOINER #####
function loginCreateAccountSocial($first_name, $last_name, $email){

	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charactersLength = strlen($characters);
    	$randomString = '';
   		for ($i = 0; $i < 8; $i++)
        	$randomString = $randomString . $characters[rand(0, $charactersLength - 1)];

	$pwPassword = md5($randomString);

	$img_address = array();
  	$img_name = array();

 	array_push($img_address,'images/welcome-bg.jpg',
 		'images/main-logo-green.png','images/welcome-img.jpg');

  	array_push($img_name,'background','logo','main');

	$email_subject = 'WELCOME TO BAIPAJOIN';
	$email_message = html_welcome_message_social($first_name, $pwPassword);

	send_email($email, $email_subject, $email_message, $img_address, $img_name);

	DB::query("INSERT INTO joiner(joiner_fname, joiner_lname, joiner_email, joiner_password) VALUES(?,?,?,?)", array($first_name, $last_name, $email, $pwPassword), "CREATE");

	$joinerAccount = DB::query('SELECT * FROM joiner WHERE joiner_email=? AND joiner_password=?', array($email, $pwPassword), "READ");

	if(count($joinerAccount)>0){
		$joinerAccount = $joinerAccount[0];
		$_SESSION['joiner'] = $joinerAccount['joiner_id'];

		header('Location: index.php');
		exit;
	}
}

##### CODE START HERE @LOGOUT ACCOUNT (JOINER or ORGANIZER) #####
function checkIfThereAreUsers(){
	if(!isset($_SESSION['joiner']) || !isset($_SESSION['organizer'])){
		header('Location: login.php');
		exit;
	}
}

##### CODE START HERE @CURRENT LOGIN USER (JOINER) #####
function currentJoiner($id){
	$user = DB::query("SELECT * FROM joiner WHERE joiner_id = ?", array($id), "READ");
	if(count($user) > 0) {
		$user = $user[0];

		$_SESSION['fname'] = $user['joiner_fname'];
		$_SESSION['lname'] = $user['joiner_lname'];
		$_SESSION['mi'] = $user['joiner_mi'];
		$_SESSION['address'] = $user['joiner_address'];
		$_SESSION['phone'] = $user['joiner_phone'];
		$_SESSION['email'] = $user['joiner_email'];
		$_SESSION['password'] = $user['joiner_password'];
		$_SESSION['citymuni'] = $user['joiner_citymuni'];

	}
}

##### CODE START HERE @CURRENT LOGIN USER (ORGANIZER) #####
function currentOrganizer($id){
	$user = DB::query("SELECT * FROM organizer WHERE orga_id = ?", array($id), "READ");
	if(count($user) > 0) {
		$user = $user[0];

		$_SESSION['fname'] = $user['orga_fname'];
		$_SESSION['lname'] = $user['orga_lname'];
		$_SESSION['mi'] = $user['orga_mi'];
		$_SESSION['address'] = $user['orga_address'];
		$_SESSION['phone'] = $user['orga_phone'];
		$_SESSION['email'] = $user['orga_email'];
		$_SESSION['password'] = $user['orga_password'];

		if(isset($_SESSION['organizer'])) {
			$_SESSION['company'] = $user['orga_company'];
			$_SESSION['verified'] = $user['orga_status'];
		}
	}
}

##### CODE START HERE @EDIT USER PROFILE (ORGANIZER) #####
function organizerSaveProfileChanges(){
	$txtCompName = trim(ucwords($_POST['txtCompName']));
	$txtFirstname = trim(ucwords($_POST['txtFirstname']));
	$txtLastname = trim(ucwords($_POST['txtLastname']));
	$txtMi = trim(ucwords($_POST['txtMi']));
	$txtAddress = trim(ucwords($_POST['txtAddress']));
	$txtPhone = trim($_POST['txtPhone']);
	$emEmail = trim($_POST['emEmail']);
	//ERROR HANDLING
	if(preg_match('/\d/', $txtFirstname)){
		echo "<script>alert('Firstname cannot have a number!')</script>";
	}
	else if(preg_match('/\d/', $txtLastname)){
		echo "<script>alert('Lastname cannot have a number!')</script>";
	}
	else if(preg_match('/\d/', $txtMi)){
		echo "<script>alert('Mi cannot have a number!')</script>";
	}
	else if(!preg_match('/\d/', $txtPhone)){
		echo "<script>alert('Phone number cannot have a letter!')</script>";
	}
	else {
		//UPDATE CHANGES
		DB::query("UPDATE organizer SET orga_company=?, orga_fname=?, orga_lname=?, orga_mi=?, orga_address=?, orga_phone=?, orga_email=? WHERE orga_id=?", array($txtCompName, $txtFirstname, $txtLastname, $txtMi, $txtAddress, $txtPhone, $emEmail, $_SESSION['organizer']), "UPDATE");
		//DISPLAY UPDATED CHANGES
		currentOrganizer($_SESSION['organizer']);
		//
		header('Location: settings.php?updated=1');
		exit;
	}
}

##### CODE START HERE @EDIT USER PROFILE (JOINER) #####
function joinerSaveProfileChanges(){
	$txtFirstname = trim(ucwords($_POST['txtFirstname']));
	$txtLastname = trim(ucwords($_POST['txtLastname']));
	$txtMi = trim(ucwords($_POST['txtMi']));
	$txtAddress = trim(ucwords($_POST['txtAddress']));
	$txtPhone = trim($_POST['txtPhone']);
	$emEmail = trim($_POST['emEmail']);
	$txtCityMuni = trim($_POST['txtCityMuni']);
	//ERROR HANDLING
	if(preg_match('/\d/', $txtFirstname)){
		echo "<script>alert('Firstname cannot have a number!')</script>";
	}
	else if(preg_match('/\d/', $txtLastname)){
		echo "<script>alert('Lastname cannot have a number!')</script>";
	}
	else if(preg_match('/\d/', $txtMi)){
		echo "<script>alert('Mi cannot have a number!')</script>";
	}
	else if(!preg_match('/\d/', $txtPhone)){
		echo "<script>alert('Phone number cannot have a letter!')</script>";
	}
	else {
		//UPDATE CHANGES
		DB::query("UPDATE joiner SET joiner_fname=?, joiner_lname=?, joiner_mi=?, joiner_address=?, joiner_phone=?, joiner_email=?, joiner_citymuni=? WHERE joiner_id=?", array($txtFirstname, $txtLastname, $txtMi, $txtAddress, $txtPhone, $emEmail, $txtCityMuni, $_SESSION['joiner']), "UPDATE");
		//DISPLAY UPDATED CHANGES
		currentJoiner($_SESSION['joiner']);
		//
		header('Location: settings.php?updated=1');
		exit;
	}
}

##### CODE START HERE @ADD USER LEGAL DOCUMENTS (ORGANIZER) #####
function addLegalDocuments(){
	$radioView = $_POST['radioView'];
	$cboType = $_POST['cboType'];
	$txtDescription = trim($_POST['txtDescription']);
	$imageName = uploadImage('fileDocuImage', "legal_docu/".$_SESSION['organizer']."/");
	// CHECK IF LEGAL DOCU DESCRIPTION IS EMPTY
	if($txtDescription == ""){
		$txtDescription = "No description being added!";
	}
	// ERROR TRAPPINGS
	if($imageName === 1){
		echo "<script>alert('An error occurred in uploading your image!')</script>";
	}
	else if($imageName === 2){
		echo "<script>alert('File type is not allowed!')</script>";
	}
	else {
		DB::query('INSERT INTO legal_document (orga_id, docu_type, docu_description, docu_image, docu_dateadded, docu_viewable) VALUES (?,?,?,?,?,?)', array($_SESSION['organizer'], $cboType, $txtDescription, $imageName, date('Y-m-d'), $radioView), "CREATE");

		header("Location: settings.php?edited=1");
	}
}

##### CODE START HERE @UPLOAD IMAGE #####
function uploadImage($name, $target){
	$allowedType = array('jpg','jpeg','png','pdf');
	$file = $_FILES[$name];
	//
	$fileName = $file['name'];
	$fileTmpName = $file['tmp_name'];
	$fileError = $file['error'];
	//
	$fileType = pathinfo($fileName, PATHINFO_EXTENSION);
	// RETURN 2 MEANS FILE TYPE IS NOT ALLOWED!
	if(!in_array($fileType, $allowedType)) return 2;
	// RETURN 1 MEANS THERE IS AN ERROR IN UPLOADING IMAGE!
	if(!$fileError === 0) return 1;
	// ASSIGN UNIQUE NAME AND FILE LOCATION
	$fileNewName = uniqid('', true).".".$fileType;
	$fileLocation = $target.$fileNewName;
	// UPLOADS
	@move_uploaded_file($fileTmpName, $fileLocation);

	return $fileNewName;
}

##### CODE START HERE @UPLOAD MULTIPLE IMAGES #####
function uploadMultipleImages($name, $target){
	$result = "";
	$file = $_FILES[$name];
	$allowedType = array('jpg','jpeg','png');
	//
	if(count($file['name']) < 5 && count($file['name']) > 0){
		foreach($file['name'] as $key => $value){
			$fileName = $file['name'][$key];
			$fileTmpName = $file['tmp_name'][$key];
			$fileError = $file['error'][$key];
			//
			$fileType = pathinfo($fileName, PATHINFO_EXTENSION);
			// RETURN 2 MEANS FILE TYPE IS NOT ALLOWED!
			if(!in_array($fileType, $allowedType)) return 2;
			// RETURN 1 MEANS THERE IS AN ERROR IN UPLOADING IMAGE!
			if(!$fileError === 0) return 1;
			// ASSIGN UNIQUE NAME AND FILE LOCATION
			$fileNewName = uniqid('', true).".".$fileType;
			$fileLocation = $target.$fileNewName;
			// UPLOADS
			@move_uploaded_file($fileTmpName, $fileLocation);
			//
			$result = $result.",".$fileNewName;
		}
	} else return 0;
	//
	return $result;
}

##### CODE START HERE @DISPLAY ALL CARDS #####
function displayAll($num, $query = NULL, $book_id = NULL){
	$card = "";

	// FOR LEGAL DOCUMENT DISPLAYING CARDS
	if($num === 0){
		$card = DB::query("SELECT * FROM legal_document WHERE orga_id = ?", array($_SESSION['organizer']), "READ");

		if(count($card)>0){
			foreach($card as $result){
				echo "
				<div class='card'>
					<figure>
						<img src='legal_docu/".$_SESSION['organizer']."/".$result['docu_image']."' alt='".$result['docu_type']."'>
					</figure>
					<div>
						";
						if($result['docu_viewable'] == 1)
							echo "<em>Viewable by Joiners: Public</em>";
						else echo "<em>Viewable by Joiners: Private</em>";
						echo "
						<h2>".$result['docu_type']." <span>Added on: ".date('M. j, Y', strtotime($result['docu_dateadded']))."</span></h2>
						<p>".$result['docu_description']."</p>
					</div>
					<ul class='icons'>
				";
					if(!$_SESSION['verified'] == 1 || $_SESSION['verified'] == 2){
						echo "<li><a href='edit_docu.php?image=".$result['docu_image']."'><i class='fas fa-edit' data-toggle='tooltip' title='Update Document'></i></a></li>";
					}

					if(!$_SESSION['verified'] == 2){
						echo "
						<li><a href='delete.php?table=legal_document&image=".$result['docu_image']."' onclick='return confirm(\"Are you sure you want to delete this document?\");'><i class='far fa-trash-alt' data-toggle='tooltip' title='Delete Document'></i></a></li>
						";
					}
				echo "
						</ul>
				</div>
				";
			}
		} else {
			echo "<h3>Click + to add legal documents!</h3>";
		}
	}
	// FOR ADVENTURE POSTED DISPLAYING CARDS
	else if($num === 1){
		$card = DB::query("SELECT * FROM adventure WHERE orga_id = ?", array($_SESSION['organizer']), "READ");
		if($query != NULL) $card = $query;

		if(count($card)>0){
			foreach($card as $result){
				$remainingGuestsText = "";
				$images = $result['adv_images'];
				$image = explode(",", $images);

				// RANDOM IMAGE DISPLAY
				$totalImagesNum = count($image) - 1;
				$displayImage = rand(1,$totalImagesNum);

				// PRICE PER PERSON
				$price = $result['adv_totalcostprice'] / $result['adv_maxguests'];

				// REMAINING GUEST
				$numRemainingGuests = $result['adv_maxguests'] - $result['adv_currentGuest'];

				// REMAINING GUEST IN TEXT
				if($result['adv_type'] == 'Packaged')
					$remainingGuestsText = " - ".$result['adv_currentGuest']."/".$result['adv_maxguests'];

				$no_cancel_starting_date = date("Y-m-d", strtotime("-5 days", strtotime($result['adv_date'])));

				## SKIP DISPLAY IF ADVENTURE IS PENDING FOR CANCELATION
				$pending_adv = DB::query("SELECT * FROM request WHERE adv_id=? AND (req_status=? || req_status=? || req_status=?)", array($result['adv_id'], "pending", "approved", "paid"), "READ");
				if(count($pending_adv)>0) continue;

				echo "
				<div class='card'>
					<figure>
						<img src='images/organizers/".$_SESSION['organizer']."/$image[$displayImage]' alt='image'>
					</figure>
					<em> on ".date("F j, Y", strtotime($result['adv_date']))."</em>
					<h2>".$result['adv_name']." - ".$result['adv_kind']." (".$result['adv_type'].")
						<span>";
						## RATINGS
						$rate = adv_ratings($result['adv_id'], true);
						if($rate == 0) echo $rate;
						else echo number_format($rate,1,".","");
						echo " <i class='fas fa-star'></i> ";

						## RATINGS COUNT
						echo "(".adv_ratings($result['adv_id'], true, "count ratings")." reviews)";

						## ADV STATUS
						if($result['adv_status'] == "done") echo $remainingGuestsText." slots (DONE)";
						elseif($numRemainingGuests == 0) echo $remainingGuestsText." slots (FULL)";
						else echo $remainingGuestsText." slots occupied";
					echo "
						</span>
					</h2>
					<p>".$result['adv_address']."</p>
					<p>???".number_format((float)$price, 2, '.', ',')." / person</p>";
					##
					if($result['adv_currentGuest'] == 0){
						echo "<a href='edit_adv.php?id=".$result['adv_id']."' class='edit'>Edit</a>";
						echo "<a href='delete.php?table=adventure&id=".$result['adv_id']."' class='edit' onclick='return confirm(\"Are you sure you want to delete this adventure?\");'>Delete</a>";
					} elseif((date("Y-m-d") < $no_cancel_starting_date) && ($result['adv_currentGuest'] > 0)){
						echo "<a href='reports_booking-cancel.php?adv_id=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to cancel this adventure? Joiner who are booked can either request refund or reschedule!\");' class='edit'>Cancel</a>";
					} elseif((date("Y-m-d") >= $no_cancel_starting_date) && (date("Y-m-d") <= $result['adv_date'])){
						echo "<a class='edit disable'>Cancel</a>";
					} else {
						echo "<a href='request-payout.php?adv_id=".$result['adv_id']."' onclick='return confirm(\"Confirm request payout?\");' class='edit'>Request Payout</a>";

						## EMAIL ORGANIZER NOTIFICATION
						$organizer_db = DB::query("SELECT * FROM organizer WHERE orga_id = ?", array($_SESSION['organizer']), "READ");
						$organizer_info = $organizer_db[0];

						$email_message = html_request_message($organizer_info['orga_fname'], 2, 'organizer');

						$img_address = array();
						$img_name = array();

						array_push($img_address,'images/request-bg.png','images/main-logo-green.png','images/request-img.png');
						array_push($img_name,'background','logo','main');

						send_email($organizer_info['orga_email'], "PAYOUT REQUEST ACKNOWLEDGED", $email_message, $img_address, $img_name);
					}
				##
				echo "</div>";
			}
		## IF NO ADVENTURE POSTED EXISTS
		} else {
			if($_SESSION['verified'] == 1)
				echo "<h3>Click + to post an adventure!</h3>";
			else
				echo "<h3>Must be verified to post an adventure!</h3>";
		}
	}
	// FOR VOUCHER ADDED DISPLAYING CARDS
	else if($num === 2){
		$card = DB::query("SELECT * FROM voucher WHERE orga_id = ?", array($_SESSION['organizer']), "READ");
		if($query != NULL) $card = $query;

		if(count($card)>0){
			foreach($card as $result){
				## GET THE ADVENTURE OF SPECIFIC VOUCHER
				$adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($result['adv_id']), "READ");
				$adv = $adv[0];
				if($result['vouch_enddate'] < date('Y-m-d')){
					echo "
					<div class='card'>
						<figure class='expired'>
							<img src='images/expired.png' alt='image'>
						</figure>";
				}
				else {
					echo "
					<div class='card'>";
				}

				echo "
					<figure>
						<img src='images/voucher.jpg' alt='image'>
					</figure>
					<em>for ".$adv['adv_name']."</em>
					<h2>".$result['vouch_discount']."% OFF <span>???".$result['vouch_minspent']." min. spend</span> </h2>
					<p>Valid Until: <q>".date('M. j, Y', strtotime($result['vouch_enddate']))."</q></p>

					<ul class='icons'>
						<li><a href='edit_voucher.php?id=".$result['vouch_code']."'><i class='fas fa-edit' data-toggle='tooltip' title='Update Voucher'></i></a></li>
						<li><a href='delete.php?table=voucher&id=".$result['vouch_code']."' onclick='return confirm(\"Are you sure you want to delete this voucher?\");'><i class='far fa-trash-alt' data-toggle='tooltip' title='Remove Voucher'></i></a></li>
					</ul>
				</div>
				";
			}
		## IF NO VOUCHER POSTED EXISTS
		} else {
			if($_SESSION['verified'] == 1)
				echo "<h3>Click + to add a voucher!</h3>";
			else
				echo "<h3>Must be verified to add a voucher!</h3>";
		}
	}
	// FOR VOUCHER DISPLAYING ALL CARDS FOR JOINER
	else if($num === 3){
		$card = DB::query("SELECT * FROM voucher", array(), "READ");
		if($query != NULL) $card = $query;

		if(count($card)>0){
			foreach($card as $result){
				## GET THE ADVENTURE OF SPECIFIC VOUCHER
				$adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($result['adv_id']), "READ");
				$adv = $adv[0];
				if($result['vouch_enddate'] < date('Y-m-d')){
					echo "
					<div class='card'>
						<figure class='expired'>
							<img src='images/expired.png' alt='image'>
						</figure>";
				}
				else {
					echo "
					<div class='card'>";
				}

				echo "
					<figure>
						<img src='images/voucher.jpg' alt='image'>
					</figure>
					<em>for ".$adv['adv_name']."</em>
					<h2>".$result['vouch_discount']."% OFF <span>???".$result['vouch_minspent']." min. spend</span> </h2>
					<p>Valid Until: <q>".date('M. j, Y', strtotime($result['vouch_enddate']))."</q></p>

					<ul class='icons'>
						<li><a onclick='copy_voucher_code(\"".$result['vouch_code']."\")'><i class='far fa-copy' data-toggle='tooltip' title='Copy Code'></i></a></li>
					</ul>
				</div>
				";
			}
		## IF NO VOUCHER POSTED
		} else {
			echo "<h3>No available voucher!</h3>";
		}
	}
	// FOR FAVORITES DISPLAYING CARDS
	else if($num === 4){
		$card = DB::query("SELECT * FROM adventure, favorite WHERE joiner_id = ? AND adventure.adv_id = favorite.adv_id", array($_SESSION['joiner']), "READ");
		if($query != NULL) $card = $query;

		if(count($card)>0){
			foreach($card as $result){
				$remainingGuestsText = "";
				$images = $result['adv_images'];
				$image = explode(",", $images);

				// RANDOM IMAGE DISPLAY
				$totalImagesNum = count($image) - 1;
				$displayImage = rand(1,$totalImagesNum);

				// PRICE PER PERSON
				$price = $result['adv_totalcostprice'] / $result['adv_maxguests'];

				// REMAINING GUEST
				$numRemainingGuests = $result['adv_maxguests'] - $result['adv_currentGuest'];

				// REMAINING GUEST IN TEXT
				if($result['adv_type'] == 'Packaged')
					$remainingGuestsText = " - ".$result['adv_currentGuest']."/".$result['adv_maxguests']." slots occupied";

				echo "
				<a class='card-link' href='place.php?id=".$result['adv_id']."'>
				<div class='card'>
					<figure>
						<img src='images/organizers/".$result['orga_id']."/$image[$displayImage]' alt='image'>
					</figure>
					<em> on ".date("F j, Y", strtotime($result['adv_date']))."</em>
					<h2>".$result['adv_name']." - ".$result['adv_kind']." <span>";
						## RATINGS
						$rate = adv_ratings($result['adv_id'], true);
						if($rate == 0) echo $rate;
						else echo number_format($rate,1,".","");
						echo " <i class='fas fa-star'></i> ";

						## RATINGS COUNT
						echo "(".adv_ratings($result['adv_id'], true, "count ratings")." reviews)";

						## ADV STATUS
						if($result['adv_status'] == "done") echo " - done";
						elseif($numRemainingGuests == 0) echo " - full";
						else echo $remainingGuestsText;
					echo "
						</span>
					</h2>
					<p>".$result['adv_address']."</p>
					<p>??? ".number_format((float)$price, 2, '.', ',')." / person</p>
					<ul class='icons'>";

			  if(isset($_SESSION['joiner'])){
					$favAdv = DB::query("SELECT * FROM favorite WHERE joiner_id = ? AND adv_id = ?", array($_SESSION['joiner'], $result['adv_id']), "READ");

					if(count($favAdv) > 0)
						echo "<li><a id='saved' class='added' href='favorites.php?removeFav=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to remove this adventure to your favorites?\");'><i class='fas fa-heart' data-toggle='tooltip' title='Remove from Favorite'></i></a></li>";
					else
						echo "<li><a href='favorites.php?addFav=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to add this adventure to your favorites?\");'><i class='far fa-heart' data-toggle='tooltip' title='Add to Favorite'></i></a></li>";

				} else
					echo "<li><a href='login.php' onclick='return confirm(\"Are you sure you want to login to add adventures to favorites?\");'><i class='far fa-heart' data-toggle='tooltip' data-placement='top' title='Add to Favorite'></i></a></li>";

				echo "
					</ul>
				</div>
				</a>
				";
			}
		}
		else {
			echo "<h3>You haven't added adventures to your favorites!</h3>";
		}
	}
	// DISPLAY ALL ADVENTURES AVAILABLE FOR RESCHEDULE
	else if($num === 5){
		// $card = DB::query("SELECT * FROM adventure", array(), "READ");
		if($query != NULL) $card = $query;
		$booked = DB::query("SELECT * FROM booking WHERE book_id=?", array($book_id), "READ");
		$booked = $booked[0];

		if(count($card)>0){
			foreach($card as $result){
				$remainingGuestsText = "";
				$images = $result['adv_images'];
				$image = explode(",", $images);

				// RANDOM IMAGE DISPLAY
				$totalImagesNum = count($image) - 1;
				$displayImage = rand(1,$totalImagesNum);

				// PRICE PER PERSON
				$price = $result['adv_totalcostprice'] / $result['adv_maxguests'];

				// REMAINING GUEST
				$numRemainingGuests = $result['adv_maxguests'] - $result['adv_currentGuest'];

				// REMAINING GUEST IN TEXT
				if($result['adv_type'] == 'Packaged')
					$remainingGuestsText = " - ".$result['adv_currentGuest']."/".$result['adv_maxguests']." slots occupied";

				// DISPLAY ALL ADVENTURE WITH FUTURE DATES
				if($result["adv_date"] > date("Y-m-d")){
					echo "
					<div class='card'>
						<figure>
							<img src='images/organizers/".$result['orga_id']."/$image[$displayImage]' alt=''>
						</figure>
						<em> on ".date("F j, Y", strtotime($result['adv_date']))."</em>
						<h2>".$result['adv_name']." - ".$result['adv_kind']." (".$result['adv_type'].") <span>";
							## RATINGS
							$rate = adv_ratings($result['adv_id'], true);
							if($rate == 0) echo $rate;
							else echo number_format($rate,1,".","");
							echo " <i class='fas fa-star'></i> ";

							## RATINGS COUNT
							echo "(".adv_ratings($result['adv_id'], true, "count ratings")." reviews)";

							## ADV STATUS
							if($result['adv_status'] == "done") echo " - done";
							elseif($numRemainingGuests == 0) echo " - full";
							else echo $remainingGuestsText;
						echo "
							</span>
						</h2>
						<p>".$result['adv_address']."</p>
						<p>???".number_format((float)$price, 2, '.', ',')." / person</p>";

					if(isset($_SESSION['joiner'])){
						$no_cancel_date = date("Y-m-d", strtotime("-10 days", strtotime($result['adv_date'])));
						## CHECK IF CURRENT DAY IS NOT 10DAYS BVEFORE ADV
						if(date("Y-m-d") > $no_cancel_date) {
							echo "<a class='edit' href='' onclick='return confirm(\"You cannot resched this adventure because it is happening in a few days!\");'>Reschedule</a>";

						## ADV AVAILABLE TO RESCHED
						} else {
							echo "<a class='edit' href='reports_resched.php?adv_id=".$result['adv_id']."&book_id=".$book_id."' onclick='return confirm(\"You can only resched this adventure once. Are you sure you want to reschedule?\");'>Reschedule</a>";
						}
					}
					##
					echo "</div>";
				}
			}
		}
		else {
			echo "<h3>No adventure exists!</h3>";
		}

	// ELSE TO IFS AND DISPLAY ALL ADVENTURES FOR JOINER
	} else {
		$card = DB::query("SELECT * FROM adventure", array(), "READ");
		if($query != NULL) $card = $query;

		if(isset($_SESSION['joiner'])){
			$joiner = DB::query("SELECT * FROM joiner WHERE joiner_id = ?", array($_SESSION['joiner']), "READ");
			$joiner = $joiner[0];
		}

		if(count($card)>0){

			$favorite_adv = get_most_favorite_adventure();
			$best_seller_adv = get_best_seller_adventure();
			$popular_adv = get_most_popular_adventure();
			$highest_rating_adv = 0;

			## GETTING THE HIGHEST RATINGS
			foreach($card as $result){
				$highest_rate = adv_ratings($result['adv_id'], true);
				($highest_rating_adv < $highest_rate) ? $highest_rating_adv = $highest_rate: $highest_rating_adv = $highest_rating_adv;
			}

			foreach($card as $result){
				$remainingGuestsText = "";
				$images = $result['adv_images'];
				$image = explode(",", $images);

				// RANDOM IMAGE DISPLAY
				$totalImagesNum = count($image) - 1;
				$displayImage = rand(1,$totalImagesNum);

				// PRICE PER PERSON
				$price = $result['adv_totalcostprice'] / $result['adv_maxguests'];

				// REMAINING GUEST
				$numRemainingGuests = $result['adv_maxguests'] - $result['adv_currentGuest'];

				// REMAINING GUEST IN TEXT
				if($result['adv_type'] == 'Packaged')
					$remainingGuestsText = " - ".$result['adv_currentGuest']."/".$result['adv_maxguests']." slots occupied";

				// DISPLAY ALL ADVENTURE WITH FUTURE DATES
				if($result["adv_date"] > date("Y-m-d")){
					echo "
					<a class='card-link' href='place.php?id=".$result['adv_id']."'>
					<div class='card'>
						<div class='label'>";
							## EACH ADV RATINGS
							$rate = adv_ratings($result['adv_id'], true);
							// background:#4a934a; GREEN
							$discount = get_voucher_discount($result['adv_id']);
							if($highest_rating_adv > 0 && $highest_rating_adv == $rate){
								echo "<span style='min-width:170px;background:#4a934a;'>HIGHEST RATINGS</span>";
							}
							if($discount != -1){
								echo "<span style='min-width:90px;'>".$discount."% OFF</span>";
							}
							if($favorite_adv == $result['adv_id']) {
								echo "<span style='min-width:155px;'>MOST FAVORITE!</span>";
							}
							if($best_seller_adv == $result['adv_id']) {
								echo "<span style='min-width:155px;'>BEST SELLER!</span>";
							}
							if($popular_adv == $result['adv_id']) {
								echo "<span style='min-width:155px;'>MOST POPULAR!</span>";
							}

						echo "
						</div>
						<figure>
							<img src='images/organizers/".$result['orga_id']."/$image[$displayImage]' alt=''>
						</figure>
						<em> on ".date("F j, Y", strtotime($result['adv_date']))."</em>
						<h2>".$result['adv_name']." - ".$result['adv_kind']." (".$result['adv_type'].") <span>";
							## RATINGS
							if($rate == 0) echo $rate;
							else echo number_format($rate,1,".","");
							echo " <i class='fas fa-star'></i> ";

							## RATINGS COUNT
							echo "(".adv_ratings($result['adv_id'], true, "count ratings")." reviews)";

							## ADV STATUS
							if($result['adv_status'] == "done") echo " - done";
							elseif($numRemainingGuests == 0) echo " - full";
							else echo $remainingGuestsText;
						echo "
							</span>
						</h2>";

						$distance = 0;
						##
						if(!empty($joiner['joiner_citymuni']))
							$distance = get_distance_from_location($joiner['joiner_citymuni'],$result['adv_town']);
						if($distance > 0)
							echo "<p>".$result['adv_address']." - <b>".$distance."</b> KMs away from ".$joiner['joiner_citymuni']."</p>";
						else
							echo "<p>".$result['adv_address']."</p>";

						echo "
						<p>???".number_format((float)$price, 2, '.', ',')." / person</p>
						<ul class='icons'>";

					if(isset($_SESSION['joiner'])){
						$favAdv = DB::query("SELECT * FROM favorite WHERE joiner_id = ? AND adv_id = ?", array($_SESSION['joiner'], $result['adv_id']), "READ");

						if(count($favAdv) > 0)
							echo "<li><a id='saved' class='added' href='adventures.php?removeFav=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to remove this adventure to your favorites?\");'><i class='fas fa-heart' data-toggle='tooltip' data-placement='top' title='Remove from Favorite'></i></a></li>";
						else
							echo "<li><a href='adventures.php?addFav=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to add this adventure to your favorites?\");'><i class='far fa-heart' data-toggle='tooltip' data-placement='top' title='Add to Favorite'></i></a></li>";

					} else {
						echo "<li><a href='login.php' onclick='return confirm(\"Are you sure you want to login to add adventures to favorites?\");'><i class='far fa-heart' data-toggle='tooltip' data-placement='top' title='Add to Favorite'></i></a></li>";
					}
					##
					echo "
						</ul>
						<a href='place.php?id=".$result['adv_id']."' class='edit'>View Adventure</a>
						<a href='adventures-request.php?adv_id=".$result['adv_id']."' class='edit' onclick='return confirm(\"Confirm request a date for this adventure?\")'>Request Adventure Date</a>
					</div>
					</a>
					";
				}
			}
		}
		else {
			echo "<h3>No adventure exists!</h3>";
		}
	}
}

##### CODE START HERE @PAGINATION #####
function pagination($page, $card, $num_per_page){

	$total_record = count($card);
    $total_page = ceil($total_record/$num_per_page);

    if($page > 1)
        echo "<a href='adventures.php?page=" .($page-1). "' class='fas fa-angle-double-left pull-left' > Previous</a>";

				//LIMIT VISIBLE NUMBER PAGE
				$numpage = 1;
				$startPage = max(1, $page - $numpage);
				$endPage = min( $total_page, $page + $numpage);


    for($i=$startPage;$i<=$endPage;$i++) {
		if ($i == $page)
    		$class = 'pagingCurrent';
    	else
			$class = 'paging';

		if($page > $i && $page > 2)
			echo "<a href='adventures.php' class='".$class."'> 1 ... </a>";


		echo "<a href='adventures.php?page=" .$i. "' class='".$class."'>  $i   </a>";

		if($page < $i && $page < ($total_page-1))
			echo "<a href='adventures.php?page=" .($total_page). "' class='".$class."'> ... $total_page </a>";
    }

    if(($i-1) > $page)
        echo "<a href='adventures.php?page=" .($page+1). "' class='fas fa-angle-double-right pull-right' > Next </a >";
}

##### CODE START HERE @FILTER SQL / ALGORITHM #####
function create_filter_sql($places, $activities, $min, $max) {

	$sqlquery = '';

	if(!empty($places)) {

		//This will trigger if PLACES is PRESENT

		$sqlquery = "SELECT * FROM adventure WHERE (adv_address";

		$arrlength = count($places);

		foreach($places as $index => $place) {
			if($index != $arrlength-1)
				$sqlquery = $sqlquery . " = '$place' OR adv_address";
			else
				$sqlquery = $sqlquery . " = '$place'";
		}

		if(!empty($activities)) {

			// This will trigger if ACTIVITIES is PRESENT including PLACES

			$sqlquery = $sqlquery . " OR adv_kind";

			$arrlength = count($activities);

			foreach($activities as $index => $activity) {
				if($index != $arrlength-1)
					$sqlquery = $sqlquery . " = '$activity' OR adv_kind";
				else
					$sqlquery = $sqlquery . " = '$activity'";
			}

			if(!empty($min) && !empty($max)) {
				//BOTH MIN, MAX is present including ACTIVITIES and PLACES
				$sqlquery = $sqlquery . " OR (adv_totalcostprice /adv_maxguests) > ($min+1)) AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' AND (adv_totalcostprice /adv_maxguests) < ($max+1) ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";

			}
			else if(!empty($min) && empty($max)) {
				//ONLY MIN is present including ACTIVITIES and PLACES but MAX is absent
				$sqlquery = $sqlquery . " OR (adv_totalcostprice /adv_maxguests) > ($min+1)) AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
			}
			else if(empty($min) && !empty($max)) {
				//ONLY MAX is present including ACTIVITIES and PLACES but MIN is absent
				$sqlquery = $sqlquery . " OR (adv_totalcostprice /adv_maxguests) > ($max+1)) AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
			}
			else {
				//BOTH ACTIVITIES and PLACES is present but BOTH MAX & MIN is absent
				$sqlquery = $sqlquery . ") AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
			}
		}

		else {

			// This will trigger if ACTIVITES is ABSENT

			if(!empty($min) && !empty($max)) {
				//BOTH MIN and MAX is present including PLACES but ACTIVITIES is absent
				$sqlquery = $sqlquery . " OR (adv_totalcostprice /adv_maxguests) > ($min+1) AND (adv_totalcostprice /adv_maxguests) < ($max+1)) AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
			}
			else if(!empty($min) && empty($max)) {
				//ONLY MIN is present including PLACES but ACTIVITIES and MAX is absent
				$sqlquery = $sqlquery . " OR (adv_totalcostprice /adv_maxguests) > ($min+1)) AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
			}
			else if(empty($min) && !empty($max)) {
				//ONLY MAX is present including PLACES but ACTIVITIES and MIN is absent
				$sqlquery = $sqlquery . " OR (adv_totalcostprice /adv_maxguests) < ($max+1)) AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
			}
			else{
				//ONLY PLACES is present but ACTIVITIES, MIN and MAX is absent
				$sqlquery = $sqlquery . ") AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
			}
		}
	}

	else if(!empty($activities)) {

		//This will trigger only if ACTIVITIES is PRESENT but PLACES is ABSENT

		if(empty($places))
			$sqlquery = "SELECT * FROM adventure WHERE (adv_kind";

		$arrlength = count($activities);

		foreach($activities as $index => $activity) {
			if($index != $arrlength-1)
				$sqlquery = $sqlquery . " = '$activity' OR adv_kind";
			else
				$sqlquery = $sqlquery . " = '$activity'";
		}

		if(!empty($min) && !empty($max)) {
			//BOTH MIN, MAX is present including ACTIVITIES but PLACES is absent
			$sqlquery = $sqlquery . " OR (adv_totalcostprice /adv_maxguests) > ($min+1) AND (adv_totalcostprice /adv_maxguests) < ($max+1)) AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
		}
		else if(!empty($min) && empty($max)) {
			//ONLY MIN is present including ACTIVITIES but PLACES is absent
			$sqlquery = $sqlquery . " OR (adv_totalcostprice /adv_maxguests) > ($min+1)) AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
		}
		else if(empty($min) && !empty($max)) {
			//ONLY MAX is present including ACTIVITIES but PLACES is absent
			$sqlquery = $sqlquery . " OR (adv_totalcostprice /adv_maxguests) < ($max+1)) AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
		}
		else  {
			//ONLY ACTIVITIES is present PLACES, MAX & MIN is absent
			$sqlquery = $sqlquery . ") AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
		}
	}

	else if(!empty($min) && !empty($max)) {
			//BOTH MIN & MAX is present but PLACES and ACTIVITIES is absent
			$sqlquery = "SELECT * from adventure WHERE ((adv_totalcostprice /adv_maxguests) > ($min+1) AND (adv_totalcostprice /adv_maxguests) < ($max+1)) AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
	}

	else if(!empty($min) && empty($max)) {
		//ONLY MIN is present but PLACES and ACTIVITIES is absent
		$sqlquery = "SELECT * from adventure WHERE ((adv_totalcostprice /adv_maxguests) > ($min-1)) AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
	}

	else if(empty($min) && !empty($max)) {
		//ONLY MAX is present but PLACES and ACTIVITIES is absent
		$sqlquery = "SELECT * from adventure WHERE ((adv_totalcostprice /adv_maxguests) < ($max+1)) AND adv_status != 'full' AND adv_status !='canceled' AND adv_status !='done' ORDER BY adv_type DESC, (adv_currentGuest / adv_maxguests) DESC, adv_date";
	}

	file_put_contents('debug.log', date('h:i:sa').' => ' .$sqlquery. "\n" . "\n", FILE_APPEND);

	return $sqlquery;
}

##### CODE START HERE @DELETE SQL DATA IN SPECIFIED TABLE #####
function deleteSQLDataTable($table, $id, $status = NULL){
	// DELETE LEGAL DOCUMENT
	if($table == 'legal_document'){
		// DELETE THE IMAGE FILE
		$path = "legal_docu/".$_SESSION['organizer']."/".$id;
		if(!unlink($path)){
			echo "<script>alert('An error occurred in deleting image!')</script>";
		}
		// DELETE IN TABLE
		DB::query("DELETE FROM {$table} WHERE docu_image=?", array($id), 'DELETE');
	}

	// DELETE POSTED ADVENTURE
	else if($table == 'adventure'){
		$adv = DB::query("SELECT * FROM {$table} WHERE adv_id = ?", array($id), "READ");

		if(count($adv) > 0){
			$adv = $adv[0];
			// EXPLODE ADVENTURE IMAGES
			$advImages = explode(",", $adv['adv_images']);

			// DELETE THE IMAGE FILE
			for($i = 1; $i < count($advImages); $i++){
				$path1 = "images/organizers/".$_SESSION['organizer']."/".$advImages[$i];
				if(!unlink($path1)) echo "<script>alert('An error occurred in deleting image!')</script>";
			}

			$path2 = "images/organizers/".$_SESSION['organizer']."/".$adv['adv_itineraryImg'];
			if(!unlink($path2)) echo "<script>alert('An error occurred in deleting image!')</script>";

			$path3 = "images/organizers/".$_SESSION['organizer']."/".$adv['adv_dosdont_image'];
			if(!unlink($path3)) echo "<script>alert('An error occurred in deleting image!')</script>";
		}
		// DELETE SPECIFIC ID
		DB::query("DELETE FROM {$table} WHERE adv_id=?", array($id), 'DELETE');
	}

	// DELETE ADDED VOUCHER
	else if($table == 'voucher'){
		// DELETE SPECIFIC ID
		DB::query("DELETE FROM {$table} WHERE vouch_code=?", array($id), 'DELETE');
	}

	// DELETE ADDED BOOKING
	else if($table == 'booking'){
		## DELETE WHEN WAITING FOR PAYMENT STATUS IS EXPIRED
		if($status == "waiting for payment") {
			DB::query("DELETE FROM {$table} WHERE book_id=? AND book_status=?", array($id, $status), 'DELETE');

		## DELETE WHEN JOINER USES BACK BUTTON FROM book-guest.php TO book.php
		} elseif($status == "pending") {
			DB::query("DELETE FROM {$table} WHERE book_id=? AND book_status=?", array($id, $status), 'DELETE');

		## TBD
		} else {

		}
	}
	## TBD
	else {

	}
}

##### CODE START HERE @POST AN ADVENTURE #####
function postAdventure(){
	// DECLARING
	$cboType = ucwords($_POST['cboType']);
	$cboKind = ucwords($_POST['cboKind']);
	$numMaxGuests = trim($_POST['numMaxGuests']);
	$txtName = trim(ucwords($_POST['txtName']));
	$cboLoc = $_POST['cboLoc'];
	$dateDate = date("Y-m-d", strtotime($_POST['dateDate']));
	$txtDetails = trim(ucwords($_POST['txtDetails']));
	$numPrice = trim($_POST['numPrice']);
	$town = "";

	// ERROR TRAPPINGS
	if(!$numMaxGuests == "" && !is_numeric($numMaxGuests)){
		echo "<script>alert('Max guests must be number!')</script>";
	}
	else if($numMaxGuests < 1){
		echo "<script>alert('Max guests must atleast have one guest!')</script>";
	}
	else if(!is_numeric($numPrice)){
		echo "<script>alert('Price must be number!')</script>";
	}
	else if($numPrice < 1){
		echo "<script>alert('Price cannot be zero or negative!')</script>";
	}
	else if($dateDate < date("Y-m-d")){
		echo "<script>alert('The adventure date should be greater than the current date.')</script>";
	}
	else {
		if($cboType == "Not Packaged") $numMaxGuests = 1;

		$fileDosDontsImg = uploadImage('fileDosDontsImg', "images/organizers/".$_SESSION['organizer']."/");
		$fileItineraryImg = uploadImage('fileItineraryImg', "images/organizers/".$_SESSION['organizer']."/");
		$fileAdvImgs = uploadMultipleImages('fileAdvImgs', "images/organizers/".$_SESSION['organizer']."/");

		if($numMaxGuests == "") $numMaxGuests = 0;
		else if($fileAdvImgs === 0){
			echo "<script>alert('Must upload a maximum of four images!')</script>";
		}
		else if($fileAdvImgs === 1 || $fileItineraryImg === 1 || $fileDosDontsImg === 1){
			echo "<script>alert('An error occurred in uploading your image!')</script>";
		}
		else if($fileAdvImgs === 2 || $fileItineraryImg === 2 || $fileDosDontsImg === 2){
			echo "<script>alert('File type is not allowed!')</script>";
		}
		else {
			if($cboLoc == "Bantayan Island") $town = "Bantayan";
			elseif($cboLoc == "Malapascua Island") $town = "Daanbantayan";
			elseif($cboLoc == "Camotes Island") $town = "Poro";
			else $town = $cboLoc;

			DB::query('INSERT INTO adventure(adv_images, adv_name, adv_kind, adv_type, adv_address, adv_town, adv_totalcostprice, adv_date, adv_details, adv_postedDate, adv_maxguests, adv_currentGuest, adv_itineraryImg, adv_dosdont_image, adv_status, orga_id) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($fileAdvImgs, $txtName, $cboKind, $cboType, $cboLoc, $town, $numPrice, $dateDate, $txtDetails, date('Y-m-d'), $numMaxGuests, 0, $fileItineraryImg, $fileDosDontsImg, "not full", $_SESSION['organizer']), "CREATE");

			header('Location: adventures_posted.php?added=1');
		}
	}
}

##### CODE START HERE @UPDATE AN ADVENTURE #####
function updateAdventure(){
	// DECLARING
	$cboType = ucwords($_POST['cboType']);
	$cboKind = ucwords($_POST['cboKind']);
	$numMaxGuests = trim($_POST['numMaxGuests']);
	$txtName = trim(ucwords($_POST['txtName']));
	$cboLoc = $_POST['cboLoc'];
	$dateDate = date("Y-m-d", strtotime($_POST['dateDate']));
	$txtDetails = trim(ucwords($_POST['txtDetails']));
	$numPrice = trim($_POST['numPrice']);
	$town = "";

	// ERROR TRAPPINGS
	if(!$numMaxGuests == "" && !is_numeric($numMaxGuests)){
		echo "<script>alert('Max guests must be number!')</script>";
	}
	else if($numMaxGuests < 1){
		echo "<script>alert('Max guests must atleast have one guest!')</script>";
	}
	else if(!is_numeric($numPrice)){
		echo "<script>alert('Price must be number!')</script>";
	}
	else if($numPrice < 1){
		echo "<script>alert('Price cannot be zero or negative!')</script>";
	}
	else {
		if($cboType == "Not Packaged") $numMaxGuests = 1;
		//
		$adv = DB::query("SELECT * FROM adventure WHERE adv_id = ?", array($_GET['id']), "READ");

		if(count($adv) > 0){
			$adv = $adv[0];
			// EXPLODE ADVENTURE IMAGES
			$advImages = explode(",", $adv['adv_images']);

			// DELETE THE IMAGE FILE
			for($i = 1; $i < count($advImages); $i++){
				$path1 = "images/organizers/".$_SESSION['organizer']."/".$advImages[$i];
				if(!unlink($path1)) echo "<script>alert('An error occurred in deleting image!')</script>";
			}

			$path2 = "images/organizers/".$_SESSION['organizer']."/".$adv['adv_itineraryImg'];
			if(!unlink($path2)) echo "<script>alert('An error occurred in deleting image!')</script>";

			$path3 = "images/organizers/".$_SESSION['organizer']."/".$adv['adv_dosdont_image'];
			if(!unlink($path3)) echo "<script>alert('An error occurred in deleting image!')</script>";

			//
			$fileAdvImgs = uploadMultipleImages('fileAdvImgs', "images/organizers/".$_SESSION['organizer']."/");
			$fileItineraryImg = uploadImage('fileItineraryImg', "images/organizers/".$_SESSION['organizer']."/");
			$fileDosDontsImg = uploadImage('fileDosDontsImg', "images/organizers/".$_SESSION['organizer']."/");

			if($numMaxGuests == "") $numMaxGuests = 0;
			else if($fileAdvImgs === 0){
				echo "<script>alert('Must upload a maximum of four images!')</script>";
			}
			else if($fileAdvImgs === 1 || $fileItineraryImg === 1 || $fileDosDontsImg === 1){
				echo "<script>alert('An error occurred in uploading your image!')</script>";
			}
			else if($fileAdvImgs === 2 || $fileItineraryImg === 2 || $fileDosDontsImg === 2){
				echo "<script>alert('File type is not allowed!')</script>";
			}
			else {
				if($cboLoc == "Bantayan Island") $town = "Bantayan";
				elseif($cboLoc == "Malapascua Island") $town = "Daanbantayan";
				elseif($cboLoc == "Camotes Island") $town = "Poro";
				else $town = $cboLoc;

				// UPDATE
				DB::query('UPDATE adventure SET adv_images = ?, adv_name = ?, adv_kind = ?, adv_type = ?, adv_address = ?, adv_town = ?, adv_totalcostprice = ?, adv_date = ?, adv_details = ?, adv_postedDate = ?, adv_maxguests = ?, adv_itineraryImg = ?, adv_dosdont_image = ? WHERE adv_id = ?', array($fileAdvImgs, $txtName, $cboKind, $cboType, $cboLoc, $town, $numPrice, $dateDate, $txtDetails, date('Y-m-d'), $numMaxGuests, $fileItineraryImg, $fileDosDontsImg, $_GET['id']), "UPDATE");

				header('Location: adventures_posted.php?updated=1');
			}
		}
	}
}

##### CODE START HERE @ADD USER VOUCHER (ORGANIZER) #####
function addVoucher(){
	$txtName = trim(ucwords($_POST['txtName']));
	$cboAdv = $_POST['cboAdv'];
	$dateStartDate = date("Y-m-d", strtotime($_POST['dateStartDate']));
	$dateEndDate = date("Y-m-d", strtotime($_POST['dateEndDate']));
	$numDiscount = $_POST['numDiscount'];
	$numMinSpent = $_POST['numMinSpent'];
	$vouchCode = uniqid('', true);

	// QUERY ADVENTURE FOR COMPARING DATE PURPOSES
	if($cboAdv >= 0) {
		$this_adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($cboAdv), "READ");
		if(count($this_adv)>0){
			$this_adv = $this_adv[0];
			$adv_date = date("Y-m-d", strtotime($this_adv['adv_date']));
			## CHECK IF VOUCHER STARTING DATE IS GREATER THAN ADV DATE
			if($dateStartDate > $adv_date){
				echo "<script>alert('Voucher start date cannot be greater than adventure date!')</script>";
			## CHECK IF VOUCHER ENDING DATE IS GREATER THAN ADV DATE
			} else if($dateEndDate > $adv_date){
				echo "<script>alert('Voucher end date cannot be greater than adventure date!')</script>";
			## CHECK IF VOUCHER STARTING DATE IS GREATER THAN VOUCHER ENDING DATE
			} else if($dateStartDate > $dateEndDate){
				echo "<script>alert('Start date cannot be greater than end date!')</script>";
			}
			## CHECK WHETHER END AND/OR START DATE IS NOT BEYOND CURRENT DATE
			else if($dateStartDate < date("Y-m-d") || $dateEndDate < date("Y-m-d")) {
				file_put_contents('debug.log', date('h:i:sa').' => '. 'beyond' . "\n" . "\n", FILE_APPEND);
				echo "<script>alert('Please ensure that start date and/or end date is not beyond the current date.')</script>";
			} // NO ERROR FOUND
			else {
				DB::query("INSERT INTO voucher(vouch_code, vouch_discount, vouch_name, vouch_minspent, vouch_startdate, vouch_enddate, orga_id, adv_id, vouch_user) VALUES(?,?,?,?,?,?,?,?,?)", array($vouchCode, $numDiscount, $txtName, $numMinSpent, $dateStartDate, $dateEndDate, $_SESSION['organizer'], $cboAdv, 0), "CREATE");

				header("Location: voucher.php?added=1");
			}
		}
	}
	## CHECK WHETHER ORGANIZER FAILED TO SELECT AN ADVENTURE OR NO ACTIVE ADVENTURE
	else if($cboAdv == -1) {
		echo "<script>alert('Please select an adventure in order to create a voucher OR there is no active adventures in your account.')</script>";
	}
}

##### CODE START HERE @UPDATE A VOUCHER #####
function updateVoucher(){
	// DECLARING
	$txtName = trim(ucwords($_POST['txtName']));
	$cboAdv = $_POST['cboAdv'];
	$dateStartDate = date("Y-m-d", strtotime($_POST['dateStartDate']));
	$dateEndDate = date("Y-m-d", strtotime($_POST['dateEndDate']));
	$numDiscount = trim($_POST['numDiscount']);
	$numMinSpent = trim($_POST['numMinSpent']);

	// ERROR TRAPPINGS
	if($dateStartDate > $dateEndDate){
		echo "<script>alert('Start date cannot be greater than end date!')</script>";
	}
	else {
		DB::query("UPDATE voucher SET vouch_name=?, vouch_startdate=?, vouch_enddate=?, vouch_discount=?, vouch_minspent=?, adv_id=? WHERE vouch_code=?", array($txtName, $dateStartDate, $dateEndDate, $numDiscount, $numMinSpent, $cboAdv, $_GET['id']), "UPDATE");

		header("Location: voucher.php?updated=1");
	}
}

##### CODE START HERE @CHANGE A PASSWORD (JOINER OR ORGANIZER) #####
function changePassword(){
	// DECLARING
	$user = "";
	$pass = trim(md5($_POST['pass']));
	$newPass = trim($_POST['newPass']);
	$retypeNewPass = trim($_POST['retypeNewPass']);

	// CURRENT LOGIN USER IS ORGANIZER
	if(isset($_SESSION['organizer'])){
		$user = DB::query("SELECT * FROM organizer WHERE orga_id=?", array($_SESSION['organizer']), "READ");

		if(count($user)>0){
			$user = $user[0];
			// ERROR TRAPPINGS
			if($pass != $user['orga_password']){
				echo "<script>alert('Current password do not match!')</script>";
			}
			else if($newPass != $retypeNewPass){
				echo "<script>alert('New password must match retype password!')</script>";
			}
			else {
				DB::query('UPDATE organizer SET orga_password=? WHERE orga_id=?', array(md5($newPass), $_SESSION['organizer']), 'UPDATE');
				//
				header("Location: settings.php?changepass=1");
				exit;
			}
		}
		else
			echo "<script>alert('Organizer does not exist!')</script>";

	// CURRENT LOGIN USER IS ADMIN
	} elseif(isset($_SESSION['admin'])) {
		$user = DB::query("SELECT * FROM admin WHERE admin_id=?", array($_SESSION['admin']), "READ");

		if(count($user)>0){
			$user = $user[0];
			// ERROR TRAPPINGS
			if($pass != $user['admin_pass']){
				header("Location: admin.php?wrong");
			}
			else if($newPass != $retypeNewPass){
				header("Location: admin.php?not_match");
			}
			else {
				DB::query('UPDATE admin SET admin_pass=? WHERE admin_id=?', array(md5($newPass), $_SESSION['admin']), 'UPDATE');
				//
				header("Location: admin.php?changed");
			}
		}

	// CURRENT LOGIN USER IS JOINER
	} else {
		$user = DB::query("SELECT * FROM joiner WHERE joiner_id=?", array($_SESSION['joiner']), "READ");

		if(count($user)>0){
			$user = $user[0];
			// ERROR TRAPPINGS
			if($pass != $user['joiner_password']){
				echo "<script>alert('Current password do not match!')</script>";
			}
			else if($newPass != $retypeNewPass){
				echo "<script>alert('New password must match retype password!')</script>";
			}
			else {
				DB::query('UPDATE joiner SET joiner_password=? WHERE joiner_id=?', array(md5($newPass), $_SESSION['joiner']), 'UPDATE');
				//
				header("Location: settings.php?changepass=1");
				exit;
			}
		}
		else
			echo "<script>alert('Joiner does not exist!')</script>";
	}
}

##### CODE START HERE @SPECIFIC CHECKBOX CHECK IN PLACES #####
function checkPlaces($place){
	if(isset($_SESSION['places'])){
		if(!empty($_SESSION['places'])) {
			foreach($_SESSION['places'] as $result){
				if($result == $place) {
					return "checked";
					break;
				}
			}
		}
	}
	elseif(isset($_POST['places'])) {
		foreach($_POST['places'] as $result){
			if($result == $place) {
				return "checked";
				break;
			}
		}
	}
}

##### CODE START HERE @SPECIFIC CHECKBOX CHECK IN ACTIVITIES #####
function checkActivities($activity){
	if(isset($_POST['activities'])){
		foreach($_POST['activities'] as $result){
			if($result == $activity) {
				return "checked";
				break;
			}
		}
	}
}

##### CODE START HERE @ADDING ADVENTURE TO FAVORITES #####
function addToFavorites($advId, $page = NULL){
	DB::query("INSERT INTO favorite(joiner_id, adv_id, fav_date) VALUES(?,?,?)", array($_SESSION['joiner'], $advId, date('Y-m-d')), "CREATE");

	if($page != NULL && $page == 'fave')
		header('Location: favorites.php?added=1');
	else
		header('Location: adventures.php?added=1');
}

##### CODE START HERE @REMOVING ADVENTURE TO FAVORITES #####
function removeFavorite($advId, $page = NULL){
	DB::query("DELETE FROM favorite WHERE adv_id = ?", array($advId), "DELETE");

	if($page != NULL && $page == 'fave')
		header('Location: favorites.php?removed=1');
	else
		header('Location: adventures.php?removed=1');
}

##### CODE START HERE @LIMITING TEXT CONTENTS #####
function limit_text($text, $limit) {
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos   = array_keys($words);
        $text  = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}

##### CODE START HERE @BOOKING ADVENTURE #####
function booking($status, $book_id=null) {
	// IF STATUS PENDING
	if($status === "pending"){
		$_SESSION['bookOption'] = $_POST['cboOption'];
		$_SESSION['numTotal'] = str_replace(",","",$_POST['numTotal']);
		$_SESSION['cboGuests'] = $_POST['cboGuests'];
		$currentDateTime = date("Y-m-d H:i:s");
		// INSERT BOOKED ADVENTURE
		DB::query("INSERT INTO booking(book_guests, book_datetime, book_totalcosts, book_status, joiner_id, adv_id) VALUES(?,?,?,?,?,?)", array($_SESSION['cboGuests'], $currentDateTime, $_SESSION['numTotal'], $status, $_SESSION['joiner'], $_GET['id']), "CREATE");

	// IF STATUS IS WAITING FOR PAYMENT
	} else if($status === "waiting for payment") {
		// BOOK AS A GUEST: 1 JOINER 1:M GUESTS
		if($_SESSION['cboGuests'] > 1 && $_SESSION['bookOption'] == "guest"){
			for($i=0; $i<$_SESSION['cboGuests']-1; $i++){
				$txtName = trim(ucwords($_POST['txtName'][$i]));
				$txtPhone = $_POST['txtPhone'][$i];
				$emEmail = trim($_POST['emEmail'][$i]);
				//
				booking_with_guest($txtName, $txtPhone, $emEmail, $status, $book_id);
			}

		// BOOK FOR SOMEONE: 1:M GUESTS
		} else if($_SESSION['cboGuests'] > 1 && $_SESSION['bookOption'] == "someone"){
			for($i=0; $i<$_SESSION['cboGuests']; $i++){
				$txtName = trim(ucwords($_POST['txtName'][$i]));
				$txtPhone = $_POST['txtPhone'][$i];
				$emEmail = trim($_POST['emEmail'][$i]);
				//
				booking_with_guest($txtName, $txtPhone, $emEmail, $status, $book_id);
			}

		// BOOK FOR SOMEONE: 1 GUEST
		} else if($_SESSION['cboGuests'] == 1 && $_SESSION['bookOption'] == "someone"){
			$txtName = trim(ucwords($_POST['txtName'][0]));
			$txtPhone = $_POST['txtPhone'][0];
			$emEmail = trim($_POST['emEmail'][0]);
			//
			booking_with_guest($txtName, $txtPhone, $emEmail, $status, $book_id);

		// BOOK AS A GUEST: 1 JOINER
		} else {
			// UPDATE JOINER BOOKING STATUS
			DB::query("UPDATE booking SET book_status=? WHERE book_id=?", array($status, $book_id), "UPDATE");
		}

	// IF STATUS (TBD)
	} else {

	}
}

##### CODE START HERE @BOOKING PROCESS OF ADVENTURE #####
function booking_with_guest($name, $phone, $email, $status, $book_id) {
	// INSERT GUEST INFO TO TABLE
	DB::query("INSERT INTO guest(book_id, guest_name, guest_phone, guest_email) VALUES(?,?,?,?)", array($book_id, $name, $phone, $email), "CREATE");

	// UPDATE JOINER BOOKING STATUS
	DB::query("UPDATE booking SET book_status=? WHERE book_id=?", array($status, $book_id), "UPDATE");
}

##### CODE START HERE @PAYMONGO API #####
function process_paymongo_card_payment($card_name, $card_num, $card_expiry, $card_cvv, $amount, $description, $joiner) {

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

	//Paymongo create card PAYMENT INTENT code STARTS here

	$intent_body_params = '{
	    "data": {
	        "attributes": {
	            "amount": '.$amount.',
	            "payment_method_allowed": [
	                "card"
	            ],
	            "payment_method_options": {
	                "card": {
	                    "request_three_d_secure": "any"
	                }
	            },
	            "currency": "PHP",
	            "description": "'.$description.'"
	        }
	    }
	}'; //This is to setup the query for paymongo create payment intent

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.paymongo.com/v1/payment_intents',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $intent_body_params,
	  CURLOPT_HTTPHEADER => array(
	    'Authorization: Basic c2tfdGVzdF9ITFJ0NHRmYUZlMjNGUE5iZVppcmtyZXA6',
	    'Content-Type: application/json'
	  ),
	));

	$payment_intent = curl_exec($curl);
	$err = curl_error($curl);

	if(!empty($err))
		echo $err;

	if(!empty($payment_intent)) {
		$log_filename = "logs\payment_intent";
		if(!file_exists($log_filename)) {
			mkdir($log_filename, 0777, true);
		}
		$log_file_data = 'logs\\payment_intent\\log_' . date('d-M-Y') . '.log';
    	file_put_contents($log_file_data, date('h:i:sa').' => '.$payment_intent . "\n" . "\n", FILE_APPEND);
	} //This code will a log.txt file to get the response of the cURL command

	//Paymongo create card PAYMENT INTENT code ENDS here

	//Paymongo add card PAYMENT DETAILS code STARTS here

	if(substr($card_expiry,4,1) == 0){
		$expiry_month = substr($card_expiry,5,1);
	}
	else
		$expiry_month = substr($card_expiry,4,2);

	$method_body_params = '{
	    "data": {
	        "attributes": {
	            "details": {
	                "card_number": "'.$card_num.'",
	                "exp_month": '.$expiry_month.',
	                "exp_year": '.substr($card_expiry, 2, 2).',
	                "cvc": "'.$card_cvv.'"
	            },
	            "billing": {
	                "address": {
	                    "line1": "'.$joiner[4].'"
	                },
	                "name": "'.$joiner[1]." ".$joiner[2].'",
	                "email": "'.$joiner[6].'",
	                "phone": "'.$joiner[5].'"
	           		},
	        	"type": "card"
	        }
	    }
	}'; //This is to setup the query for paymongo add payment method

	file_put_contents('debug.log', date('h:i:sa').' => '.$method_body_params . "\n" . "\n", FILE_APPEND);

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.paymongo.com/v1/payment_methods',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $method_body_params,
	  CURLOPT_HTTPHEADER => array(
	    'Authorization: Basic cGtfdGVzdF82RDZ4TGk3OHRORGtOWU1WR3RlZEtkcWg6',
	    'Content-Type: application/json'
	  ),
	));

	$payment_method = curl_exec($curl);
	$err = curl_error($curl);

	if(!empty($err))
		echo $err;

	if(!empty($payment_method)) {
		$log_filename = "logs\payment_method";
		if(!file_exists($log_filename)) {
			mkdir($log_filename, 0777, true);
		}
		$log_file_data = 'logs\\payment_method\\log_' . date('d-M-Y') . '.log';
    	file_put_contents($log_file_data, date('h:i:sa').' => '.$payment_method . "\n" . "\n", FILE_APPEND);
	} //This code will a log.txt file to get the response of the cURL command

	//Paymongo add card PAYMENT DETAILS code ENDS here

	//Paymongo attach card PAYMENT DETAILS + PAYMENT METHOD code STARTS here

	$intent = json_decode($payment_intent,true);
	$method = json_decode($payment_method,true);

	$attach_body_params = '{
    "data": {
        "attributes": {
            "payment_method": "'.$method['data']['id'].'",
            "client_key": "'.$intent['data']['attributes']['client_key'].'",
            "return_url": "https://localhost/BaiPaJoin/index.php"
        	}
    	}
	}';
	//This is to setup the query for paymongo attach payment method + payment intent

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.paymongo.com/v1/payment_intents/'.$intent['data']['id'].'/attach',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $attach_body_params,
	  CURLOPT_HTTPHEADER => array(
	    'Authorization: Basic c2tfdGVzdF9ITFJ0NHRmYUZlMjNGUE5iZVppcmtyZXA6',
	    'Content-Type: application/json'
	  ),
	));

	$payment_attach = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if(!empty($err))
		echo $err;

	if(!empty($payment_attach)) {
		$log_filename = "logs\payment_attach";
		if(!file_exists($log_filename)) {
			mkdir($log_filename, 0777, true);
		}
		$log_file_data = 'logs\\payment_attach\\log_' . date('d-M-Y') . '.log';
		file_put_contents($log_file_data, date('h:i:sa').' => '.$payment_attach . "\n" . "\n", FILE_APPEND);
	} 	//This code will a log.txt file to get the response of the cURL command

	//Paymongo attach card PAYMENT DETAILS + PAYMENT METHOD code ENDS here

	$attach = json_decode($payment_attach,true);

	foreach($attach as $key => $value) {
		if($key == 'data') {
			if($attach['data']['attributes']['status'] == 'succeeded') {

				$mobile = $attach['data']['attributes']['payments'][0]['attributes']['billing']['phone'];
				$email = $attach['data']['attributes']['payments'][0]['attributes']['billing']['email'];
				$amount = ($attach['data']['attributes']['amount'] / 100);
				$currency = $attach['data']['attributes']['currency'];
				$card_last_num = $attach['data']['attributes']['payments'][0]['attributes']['source']['last4'];

				$sms_message = "Hooray! Thank you! Your payment for " . $amount . " " . $currency . " thru card number ending in " . $card_last_num . " was SUCCESSFUL!";

				send_sms($mobile, $sms_message);

				$img_address = array();
			  	$img_name = array();
			  	array_push($img_address,'images/receipt-bg.png','images/main-logo-green.png','images/receipt-img.png');
			  	array_push($img_name,'background','logo','main');

    			$email_message = html_transreceipt_message($attach, 'card');

				send_email($email, 'BOOKING TRANSACTION RECEIPT', $email_message, $img_address, $img_name);

			}
			$intent_id_status = [$intent['data']['id'], $attach['data']['attributes']['status']];
			return $intent_id_status;
		}
		elseif($key == 'errors') {
			return [0, $attach['errors'][0]['detail']];
		}
	}
}

function process_paymongo_ewallet_source($ewallet_type, $final_price, $joiner, $book_id) {
	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

	$ewallet_body_params = '{
	    "data": {
	        "attributes": {
	            "amount": '.$final_price.',
	            "redirect": {
	                "success": "https://6fa3-2001-4455-329-f600-883d-d473-4803-5735.ngrok.io/BaiPaJoin42/thankyou.php?gcash=1",
	                "failed": "https://6fa3-2001-4455-329-f600-883d-d473-4803-5735.ngrok.io/BaiPaJoin42/thankyou.php?gcash=0"
	            },
	            "billing": {
	                "name": "'.$joiner[1].' '.$joiner[2].'",
	                "phone": "'.$joiner[5].'",
	                "email": "'.$joiner[6].'"
	            },
	            "type": "'.$ewallet_type.'",
	            "currency": "PHP",
	            "description" : "'.$book_id.'"
	        }
	    }
	}';

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.paymongo.com/v1/sources',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $ewallet_body_params,
	  CURLOPT_HTTPHEADER => array(
	    'Authorization: Basic cGtfdGVzdF82RDZ4TGk3OHRORGtOWU1WR3RlZEtkcWg6',
	    'Content-Type: application/json'
	  ),
	));

	$ewallet_source = curl_exec($curl);
	$err = curl_error($curl);
	$ewallet_data = json_decode($ewallet_source, true);

	if(!empty($err))
		echo $err;

	if(!empty($ewallet_source)) {
		if(!file_exists('logs\ewallet_source')) {
			mkdir('logs\ewallet_source', 0777, true);
		}
		$log_file_data = 'logs\\ewallet_source\\log_' . date('d-M-Y') . '.log';
    	file_put_contents($log_file_data, date('h:i:sa').' => '. $ewallet_source . "\n" . "\n", FILE_APPEND);

			$redirect = "Location: " . $ewallet_data['data']['attributes']['redirect']['checkout_url']."";
			file_put_contents('debug.log', date('h:i:sa').' => '. $redirect . "\n" . "\n", FILE_APPEND);

	    	header($redirect);
	} //This code will a log.txt file to get the response of the cURL command

	curl_close($curl);
}

function process_paymongo_ewallet_payment($amount, $source_id,$book_id) {

	$curl = curl_init();

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);


    $fields = array("data" => array ("attributes" => array ("amount" => $amount, "source" => array ("id" => $source_id, "type" => "source"), "currency" => "PHP")));

    $jsonFields = json_encode($fields);

    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.paymongo.com/v1/payments",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $jsonFields,
      CURLOPT_HTTPHEADER => array(
          'Authorization: Basic c2tfdGVzdF9ITFJ0NHRmYUZlMjNGUE5iZVppcmtyZXA6',
          'Content-Type: application/json'
        ),
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
	$ewallet_payment = json_decode($response, true);

    if(!empty($response)) {
    	if(!file_exists('logs\ewallet_payment')) {
        	mkdir('logs\ewallet_payment', 0777, true);
      	}
      	$log_file_data = 'logs\\ewallet_payment\\log_' . date('d-M-Y') . '.log';
      	file_put_contents($log_file_data, date('h:i:sa').' => '. $response . "\n" . "\n", FILE_APPEND);
		$payment_id = $ewallet_payment['data']['id'];
		$ewallet_type = $ewallet_payment['data']['attributes']['source']['type'];
		booking_paid_updates($ewallet_type, $book_id, $payment_id, $amount);
	} //This code will a log.txt file to get the response of the cURL command

    curl_close($curl);

}

function retrieve_paymongo_card_payment($payment_intent_id) {

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    $query = 'https://api.paymongo.com/v1/payment_intents/'.$payment_intent_id.'';

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $query,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => array(
	    'Authorization: Basic c2tfdGVzdF9ITFJ0NHRmYUZlMjNGUE5iZVppcmtyZXA6'
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

    if(!empty($response)) {
      if(!file_exists('logs\payment_retrieve')) {
        mkdir('logs\payment_retrieve', 0777, true);
      }
      $log_file_data = 'logs\\payment_retrieve\\log_' . date('d-M-Y') . '.log';
      file_put_contents($log_file_data, date('h:i:sa').' => '. $response . "\n" . "\n", FILE_APPEND);
	} //This code will a log.txt file to get the response of the cURL command

	curl_close($curl);

	return json_decode($response, true);
}

function retrieve_paymongo_ewallet_payment($payment_id) {

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    $query = 'https://api.paymongo.com/v1/payments/'.$payment_id.'';

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $query,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => array(
	    'Authorization: Basic c2tfdGVzdF9ITFJ0NHRmYUZlMjNGUE5iZVppcmtyZXA6'
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

    if(!empty($response)) {
      if(!file_exists('logs\ewallet_retrieve')) {
        mkdir('logs\ewallet_retrieve', 0777, true);
      }
      $log_file_data = 'logs\\ewallet_retrieve\\log_' . date('d-M-Y') . '.log';
      file_put_contents($log_file_data, date('h:i:sa').' => '. $response . "\n" . "\n", FILE_APPEND);
	} //This code will a log.txt file to get the response of the cURL command

	curl_close($curl);

	return json_decode($response, true);
}

##### CODE START HERE PAYMAYA API #####
function process_paymaya_payment($amount,$book_id) {

	$_SESSION['book_id'] = $book_id;

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://pg-sandbox.paymaya.com/payby/v2/paymaya/payments/',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS =>'{
	  "totalAmount": {
	    "currency": "PHP",
	    "value": "'.$amount.'"
	  },
	  "redirectUrl": {
	    "success": "http://localhost/BaiPaJoin42/thankyou.php?paymaya",
	    "failure": "http://facebook.com",
	    "cancel": "http://shop.someserver.com/cancel?id=6319921"
	  },
	  "requestReferenceNumber": "202259",
	  "metadata": {
	    "subMerchantRequestReferenceNumber": "65899",
	    "pf": {
	       "smi": "SUB034221",
	       "smn": "BAIPAJOINCEBU",
	       "mci": "Cebu",
	       "mpc": "608",
	       "mco": "PHL",
	       "mcc": "3415",
	       "postalCode": "6000",
	       "contactNo": "+63923969932",
	       "state": "Cebu",
	       "addressLine1": "Lower Purok 8,Kamputhaw"
	    }
	  }
	}',
	  CURLOPT_HTTPHEADER => array(
	    'Authorization: Basic cGstcnB3YjVZUjZFZm5LaU1zbGRacVk0aGdwdkpqdXk4aGh4VzJiVkFBaXoyTjo=',
	    'Content-Type: application/json'
	  ),
	));

	$response = curl_exec($curl);
	$response = json_decode($response, true);

	curl_close($curl);

	$redirect = "Location: " . $response['redirectUrl']."";
	header($redirect);
}

##### CODE START HERE 7-CONNECT API #####
function process_sevenconnect_payment($amount,$book_id) {

	$address = "https://testpay.cliqq.net/transact?merchantID=ATI&merchantRef=6419705015&expDate=20210910235900&amount=".$amount."&successURL=http://localhost/BaiPaJoin42/thankyou.php?seven-eleven&failURL=https://github.com/philseven/cliqq-pay/blob/master/docs/index.md%23request-parameters-1&token=235a23122139152ff830aa7fa1a876a95d4e365b&transactionDescription=Booking Payment for booking id ".$book_id." thru Team BaiPaJoin Cebu&receiptRemarks=Team BaiPaJoin|5th floor, UC-Main Building, Sangciangko St., Cebu City.|Call 0923-968-8932 for questions&email=salvador.alexis01@gmail.com&phone=09239688932&payLoad=https://54e8-49-145-165-0.ngrok.io/api_test/7Connect_payload.ph&returnPaymentDetails=Y";

	$redirect = "Location: " . $address."";
	header($redirect);
}

##### CODE START HERE @ITEXMO API #####
function send_sms($mobile, $message) {

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

	curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://www.itexmo.com/php_api/api.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('1' => $mobile ,'2' => $message,'3' => 'TR-ALEXI688932_MPXBC','passwd' => '&9in[7}wh3'),
  ));

    $response = curl_exec($curl);

	$log_filename = "logs\sms";
	if(!file_exists($log_filename)) {
		mkdir($log_filename, 0777, true);
	}
	$log_file_data = 'logs\\sms\\log_' . date('d-M-Y') . '.log';
	file_put_contents($log_file_data, date('h:i:sa').' => Response Code: '. json_decode($response) . "\n" . "              Sent To: ". $mobile . "\n" . "              Message Sent: ". $message . "\n" . "\n", FILE_APPEND);
 	//This code will a log.txt file to get the response of the cURL command

  curl_close($curl);
}

##### CODE START HERE @PHPMAILER API #####
function send_email($to, $subject, $message, $img_address = null, $img_name = null ) {

	require_once 'PHPMailerAutoload.php';

	# EMAIL EMBEDDED WITH IMAGE
	if(!empty($img_address) && !empty($img_name)) {
		$mail = new PHPMailer;

		//$mail->SMTPDebug = 4;                               	// Enable verbose debug output
		$mail->isSMTP();                                      	// Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               	// Enable SMTP authentication
		$mail->Username = 'teambaipajoincebu@gmail.com';  // SMTP username
		$mail->Password = 'capstone42';                          	// SMTP password
		$mail->SMTPSecure = 'tls';                             	// Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    	// TCP port to connect to
		$mail->setFrom('teambaipajoincebu@gmail.com', 'BAIPAJOIN');
		$mail->addAddress($to);     							// Add a recipient
		$mail->addReplyTo('teambaipajoincebu@gmail.com');
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Add attachment + name (optional)
		$mail->AddEmbeddedImage($img_address[0],$img_name[0]);
		$mail->AddEmbeddedImage($img_address[1],$img_name[1]);
		$mail->AddEmbeddedImage($img_address[2],$img_name[2]);
		$mail->isHTML(true); 									// Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body = $message;

		if(!$mail->send()) {
	    	if(!file_exists('logs\phpmailer')) {
	        	mkdir('logs\phpmailer', 0777, true);
	      }
	      $log_file_data = 'logs\\phpmailer\\error_log_' . date('d-M-Y') . '.log';
	      file_put_contents($log_file_data, date('h:i:sa').' => Error: '. $mail->ErrorInfo . "\n" . "\n", FILE_APPEND);
	    }
	    else {
	    	if(!file_exists('logs\phpmailer')) {
	        	mkdir('logs\phpmailer', 0777, true);
	      }
	      $log_file_data = 'logs\\phpmailer\\success_log_' . date('d-M-Y') . '.log';
	      file_put_contents($log_file_data, date('h:i:sa').' =>  Sent to: ' . $to . "\n" . '               Subject: ' . $subject . "\n" . '               Message: ' . $message . "\n" . "\n", FILE_APPEND);

	    } //This code will a log.txt file to get the response of the PHPMailers
	}

	# EMAIL WITHOUT IMAGE EMBEDDED
 	else {
		$mail = new PHPMailer;

		//$mail->SMTPDebug = 4;                               	// Enable verbose debug output
		$mail->isSMTP();                                      	// Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               	// Enable SMTP authentication
		$mail->Username = 'teambaipajoincebu@gmail.com';  // SMTP username
		$mail->Password = 'capstone42';                          	// SMTP password
		$mail->SMTPSecure = 'tls';                             	// Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    	// TCP port to connect to
		$mail->setFrom('teambaipajoincebu@gmail.com', 'BAIPAJOIN');
		$mail->addAddress($to);     							// Add a recipient
		$mail->addReplyTo('teambaipajoincebu@gmail.com');
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Add attachment + name (optional)
		$mail->isHTML(true); 									// Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body = $message;

		if(!$mail->send()) {
	    	if(!file_exists('logs\phpmailer')) {
	        	mkdir('logs\phpmailer', 0777, true);
	      }
	      $log_file_data = 'logs\\phpmailer\\error_log_' . date('d-M-Y') . '.log';
	      file_put_contents($log_file_data, date('h:i:sa').' => Error: '. $mail->ErrorInfo . "\n" . "\n", FILE_APPEND);
	    }
	    else {
	    	if(!file_exists('logs\phpmailer')) {
	        	mkdir('logs\phpmailer', 0777, true);
	      }
	      $log_file_data = 'logs\\phpmailer\\success_log_' . date('d-M-Y') . '.log';
	      file_put_contents($log_file_data, date('h:i:sa').' =>  Sent to: ' . $to . "\n" . '               Subject: ' . $subject . "\n" . '               Message: ' . $message . "\n" . "\n", FILE_APPEND);

	    } //This code will a log.txt file to get the response of the PHPMailers
	}
}

##### CODE START HERE @OPENWEATHERMAP API #####
function get_current_weather_location($location) {

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    $query = 'api.openweathermap.org/data/2.5/weather?q='.$location.'&units=metric&appid=162cd8759db84c387321be37f9a939d4';

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $query,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if(!empty($response)) {
      if(!file_exists('logs\openweathermap')) {
        mkdir('logs\openweathermap', 0777, true);
      }
      $log_file_data = 'logs\\openweathermap\\log_' . date('d-M-Y') . '.log';
      file_put_contents($log_file_data, date('h:i:sa').' => '. $response . "\n" . "\n", FILE_APPEND);
    } //This code will a log.txt file to get the response of the cURL command

	return $response;
}

##### CODE START HERE @DISTANCE24.ORG API #####
function get_distance_from_location($from, $to) {

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    $query = 'https://www.distance24.org/route.json?stops='.$from.'%20Cebu|'.$to.'%20Cebu';

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $query,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if(!empty($response)) {
      if(!file_exists('logs\distance24')) {
        mkdir('logs\distance24', 0777, true);
      }
      $log_file_data = 'logs\\distance24\\log_' . date('d-M-Y') . '.log';
      file_put_contents($log_file_data, date('h:i:sa').' => '. $response . "\n" . "\n", FILE_APPEND);
    } //This code will a log.txt file to get the response of the cURL command

	$distance = json_decode($response,true);

	return $distance['distance'];
}

##### CODE START HERE @MAPQUEST API #####
function get_directions_from_to_location($from, $to) {

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://www.mapquestapi.com/directions/v2/route?key=iPNZaAasLzp7dSD4VtnZGKmuz3Wy6SXA&from='.$from.',Cebu,Philippines&to='.$to.',Cebu,Philippines',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => array(
	  	'Content-Type: application/json'
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);

	if(!empty($response)) {
      if(!file_exists('logs\mapsquestapi')) {
        mkdir('logs\mapsquestapi', 0777, true);
      }
      $log_file_data = 'logs\\mapsquestapi\\log_' . date('d-M-Y') . '.log';
      file_put_contents($log_file_data, date('h:i:sa').' => '. $response . "\n" . "\n", FILE_APPEND);
    } //This code will a log.txt file to get the response of the cURL command

	return $response;
}

##### CODE START HERE FB GRAPH API #####
function facebook_graph_api($type) {

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    $fb_access_token = 'EAA8IHfsXftcBAN7nTwNZBZA2DRRw7gIygEzgdhzQpZCd2yOBWWGhiG6B0ryZCoZAhzixT5g4q3R2I0khX49jJ69GbkD1k6xZChUOsxUz22zTqXU3Wm3ZCzTnogCOVc2T8Ow0lMMStirvTUheQKvqyYEEy4fZBcfT81og1ZBtINDkBKrW7US6DTYwwdkjWFO7YDyi5GJLWx6GvLg0Qq1wXyXZAkPyqyxaxO0qcZD';

	if($type == 'videos')
    	$query = 'https://graph.facebook.com/v12.0/100306372435763/videos?access_token='.$fb_access_token.'';
  	elseif($type == 'live_videos')
    	$query = 'https://graph.facebook.com/v12.0/100306372435763/live_videos?access_token='.$fb_access_token.'';
    elseif($type == 'visitor_post')
    	$query = 'https://graph.facebook.com/v12.0/100306372435763/visitor_posts?access_token='.$fb_access_token.'';
   	elseif($type == 'tagged')
   		$query = 'https://graph.facebook.com/v12.0/100306372435763/tagged?access_token='.$fb_access_token.'';

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $query,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if(!empty($response)) {
      if(!file_exists('logs\FBGraphAPI')) {
        mkdir('logs\FBGraphAPI', 0777, true);
      }
      $log_file_data = 'logs\\FBGraphAPI\\log_' . date('d-M-Y') . '.log';
      file_put_contents($log_file_data, date('h:i:sa').' => tagged'. $response . "\n" . "\n", FILE_APPEND);
    } //This code will a log.txt file to get the response of the cURL command

	return $response;
}

function get_facebook_media_id($id) {

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    $fb_access_token = 'EAA8IHfsXftcBAN7nTwNZBZA2DRRw7gIygEzgdhzQpZCd2yOBWWGhiG6B0ryZCoZAhzixT5g4q3R2I0khX49jJ69GbkD1k6xZChUOsxUz22zTqXU3Wm3ZCzTnogCOVc2T8Ow0lMMStirvTUheQKvqyYEEy4fZBcfT81og1ZBtINDkBKrW7US6DTYwwdkjWFO7YDyi5GJLWx6GvLg0Qq1wXyXZAkPyqyxaxO0qcZD';

	$query = 'https://graph.facebook.com/v12.0/'.$id.'/attachments?access_token='.$fb_access_token.'';

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $query,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if(!empty($response)) {
      if(!file_exists('logs\FBGraphAPI')) {
        mkdir('logs\FBGraphAPI', 0777, true);
      }
      $log_file_data = 'logs\\FBGraphAPI\\log_' . date('d-M-Y') . '.log';
      file_put_contents($log_file_data, date('h:i:sa').' => '. $response . "\n" . "\n", FILE_APPEND);
    } //This code will a log.txt file to get the response of the cURL command

	return $response;
}

### CODE START HERE MAKCORPS HOTEL API
function get_local_hotels($town) {
	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.makcorps.com/auth',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS =>'{
	    "username":"salvadoralexis01",
	    "password":"W@lf0721"
	}',
	  CURLOPT_HTTPHEADER => array(
	    'Content-Type: application/json'
	  ),
	));

	$response = curl_exec($curl);

	if(!empty($response)) {
      if(!file_exists('logs\HotelsAPI')) {
        mkdir('logs\HotelsAPI', 0777, true);
      }
      $log_file_data = 'logs\\HotelsAPI\\log_' . date('d-M-Y') . '.log';
      file_put_contents($log_file_data, date('h:i:sa').' => access_token : '. $response . "\n" . "\n", FILE_APPEND);
    } //This code will a log.txt file to get the response of the cURL command

    $response = json_decode($response,true);

    curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.makcorps.com/free/'.$town.'%20cebu',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => array(
	    'Authorization: JWT '.$response['access_token'].''
	  ),
	));

	$response = curl_exec($curl);

	if(!empty($response)) {
      if(!file_exists('logs\HotelsAPI')) {
        mkdir('logs\HotelsAPI', 0777, true);
      }
      $log_file_data = 'logs\\HotelsAPI\\log_' . date('d-M-Y') . '.log';
      file_put_contents($log_file_data, date('h:i:sa').' => hotels : '. $response . "\n" . "\n", FILE_APPEND);
    } //This code will a log.txt file to get the response of the cURL command

	curl_close($curl);

	$response = json_decode($response,true);
	$hotel_list = array();

	file_put_contents('debug.log', date('h:i:sa').' => hotels : '. count($response['Comparison']) . "\n" . "\n", FILE_APPEND);

	if(count($response['Comparison']) > 9) {
		for ($i=0; $i < 9; $i++)
			array_push($hotel_list, trim($response['Comparison'][$i][0]['hotelName'],""));
	}
	else if(count($response['Comparison']) < 9)
		for ($i=0; $i < count($response['Comparison']); $i++) {
			array_push($hotel_list, trim($response['Comparison'][$i][0]['hotelName'],""));
	}

	return $hotel_list;
}

##### CODE START HERE @NECESSARY UPDATES WHEN BOOKING IS PAID #####
function booking_paid_updates($method, $book_id, $intent_id, $total=null){
	file_put_contents("debug.log", date('h:i:sa').' => '. $method . " " . $book_id . " " . $intent_id . " " . $total . "\n" . "\n", FILE_APPEND);
	# UPDATE VOUCHER USERS
	if(isset($_SESSION['used_voucher_code'])){
		$voucher = DB::query("SELECT * FROM voucher WHERE vouch_code=?", array($_SESSION['used_voucher_code']), "READ");
		$voucher = $voucher[0];
		DB::query("UPDATE voucher SET vouch_user=? WHERE vouch_code=?", array($voucher['vouch_user'] + 1, $voucher['vouch_code']), "UPDATE");
	}

	# UPDATE BOOKING STATUS
	$booked = DB::query("SELECT * FROM booking WHERE book_id=?", array($book_id), "READ");
	$booked = $booked[0];
	DB::query("UPDATE booking SET book_status=?, book_totalcosts=? WHERE book_id=?", array("paid", ($total), $booked['book_id']), "UPDATE");

	# UPDATE ADVENTURE STATUS
	$adv_booked = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($booked['adv_id']), "READ");
	$adv_booked = $adv_booked[0];
	DB::query("UPDATE adventure SET adv_currentGuest=? WHERE adv_id=?", array($adv_booked['adv_currentGuest'] + $booked['book_guests'], $adv_booked['adv_id']), "UPDATE");
	$adv_booked = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($booked['adv_id']), "READ");
	$adv_booked = $adv_booked[0];
	if($adv_booked['adv_maxguests'] <= $adv_booked['adv_currentGuest'])
		DB::query("UPDATE adventure SET adv_status=? WHERE adv_id=?", array("full", $adv_booked['adv_id']), "UPDATE");

	# INSERT DATA TO PAYMENT TABLE
	DB::query("INSERT INTO payment(payment_id, payment_method, payment_total, payment_datetime, book_id) VALUES(?,?,?,?,?)", array($intent_id, $method, ($total), date("Y-m-d H:i:s"), $book_id), "CREATE");

	# IF PAYMENT METHOD IS THRU CARD
	if($method == "card"){
		# SUCCESSFUL MESSAGE
		echo "<h1><span class='success'>Thank you! Successfully paid thru ".$method.".</span></h1>";

	# IF PAYMENT METHOD IS THRU GCASH
	} elseif($method == "gcash") {
		# SUCCESSFUL MESSAGE
		echo "<h1><span class='success'>Thank you! Successfully paid thru ".$method.".</span></h1>";
	# IF PAYMENT METHOD IS THRU GRAB PAY
	} elseif($method == "grabpay") {

	}

	# THIS FOR BOOKING ITINERARY EMAIL
	$guests = DB::query("SELECT guest_name FROM guest WHERE book_id=?", array($booked['book_id']), "READ");

	$joiner = DB::query("SELECT * FROM joiner WHERE joiner_id=?", array($booked['joiner_id']), "READ");
	$joiner = $joiner[0];

	$img_address = array();
  	$img_name = array();
  	array_push($img_address,'images/header-bg.png','images/main-logo-green.png','images/receipt-img.png');
  	array_push($img_name,'background','logo','main');

	$email = $joiner['joiner_email'];
	$email_message = html_bookitinerary_message($booked, $adv_booked, $guests, $joiner);

	send_email($email, 'BOOKING CONFIRMATION ITINERARY', $email_message, $img_address, $img_name);
}

##### CODE START HERE @WEATHER BACKGROUND #####
function weather_bg($weather){
	$bg_color = "";

	if($weather == "Clear")
		$bg_color = "background:linear-gradient(to right, rgba(246,161,32,1), rgba(249,218,46,1));";
	elseif($weather == "Clouds")
		$bg_color = "background:linear-gradient(to right, rgba(64,178,205,1), rgba(205,223,87,1));";
	elseif($weather == "Snow")
		$bg_color = "background:linear-gradient(to right, rgba(64,178,205,1), rgba(205,223,87,1));";
	elseif($weather == "Rain")
		$bg_color = "background:linear-gradient(to right, rgba(41,62,157,1), rgba(10,148,219,1));";
	elseif($weather == "Drizzle")
		$bg_color = "background:linear-gradient(to right, rgba(41,62,157,1), rgba(10,148,219,1));";
	elseif($weather == "Thunderstorm")
		$bg_color = "background:linear-gradient(to right, rgba(36,60,74,1), rgba(38,153,200,1));";
	else
		$bg_color = "background:linear-gradient(to right, rgba(36,60,74,1), rgba(38,153,200,1));";

	return $bg_color;
}

##### CODE START HERE @DELETING ADMIN #####
function delete_admin_account($admin_id){
	// DELETE ADMIN ACCOUNT
	DB::query("DELETE FROM admin WHERE admin_id=?", array($admin_id), "DELETE");

	header("Location: admin.php?deleted");
}

##### CODE START HERE @UPDATING ADMIN #####
function update_admin_account(){
	$txtName = trim(ucwords($_POST['txtName']));
	$emEmail = trim($_POST['emEmail']);

	// UPDATE ADMIN ACCOUNT
	DB::query("UPDATE admin SET admin_name=?, admin_email=? WHERE admin_id=?", array($txtName, $emEmail, $_GET['admin_id']), "UPDATE");

	header("Location: admin.php?updated");
}

##### CODE START HERE @DISPLAY ALL ADMINS #####
function display_admin(){
	// ALL ADMIN RESULTS
	$admins = DB::query("SELECT * FROM admin", array(), "READ");

	if(count($admins)>0){
		foreach ($admins as $result) {
			echo "
			<tr>
				<td>".$result['admin_id']."</td>
				<td>".$result['admin_name']."</td>
				<td>".$result['admin_email']."</td>";

			if($result['admin_id'] == $_SESSION['admin']){
				echo "<td><a href='admin_edit.php?admin_id=".$result['admin_id']."' onclick='return confirm(\"Are you sure you want to edit this admin?\");'><i class='fas fa-edit'></i></a></td>";
				echo "<td><a href='admin.php' onclick='return confirm(\"Admin logged in cannot be deleted!\");'><i class='far fa-trash-alt'></i></a></td>";
			} else {
				echo "<td></td>";
				echo "<td><a href='admin.php?delete=".$result['admin_id']."' onclick='return confirm(\"Are you sure you want to delete this admin?\");'><i class='far fa-trash-alt'></i></a></td>";
			}
			echo "</tr>";
		}
		echo "</table>";

	## IF NO ADMIN EXISTS
	} else {
		echo "</table>";
		echo "<p>No admin exists!</p>";
	}
}

##### CODE START HERE @UPDATE ADVENTURE IF FULL OR NOT #####
function adv_full_checker(){
	/*$adv = DB::query("SELECT * FROM adventure WHERE adv_status=?", array("not full"), "READ");
	if(count($adv)>0){
		foreach ($adv as $result) {
			if($result['adv_maxguests'] == $result['adv_currentGuest']){
				DB::query("UPDATE adventure SET adv_status=? WHERE adv_id=?", array("full", $result['adv_id']), "UPDATE");
			}
		}
	}*/

	$adv = DB::query("SELECT * FROM adventure", array(), "READ");
	if(count($adv)>0){
		foreach ($adv as $result) {
			if($result['adv_status'] == "canceled" || $result['adv_status'] == "done") continue;
			if($result['adv_maxguests'] == $result['adv_currentGuest'])
				DB::query("UPDATE adventure SET adv_status=? WHERE adv_id=?", array("full", $result['adv_id']), "UPDATE");
			else if($result['adv_maxguests'] != $result['adv_currentGuest'])
				DB::query("UPDATE adventure SET adv_status=? WHERE adv_id=?", array("not full", $result['adv_id']), "UPDATE");
		}
	}
}

##### EMAIL TEMPLATE FOR NEW ACCOUNTS
function html_welcome_message($name, $type) {

	if($type == 'Joiner') {
		$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Welcome</title>
		      </head>
		      <body style='background:url(\"cid:background\");font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:200px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Dear ".$name.",</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Welcome to BaiPaJoin! We have come bearing great news: your account has been activated and now you can enjoy convenient and hassle-free adventures in a click. Be sure, to apply or select a voucher when you book your 1st adventure to get a discount! Have a look at our Terms and Conditions to know what is in the store for you. We are incredibly excited to have you here. Want to start your adventure? Click the button below to login.</p>
		            <a href='localhost/BaiPaJoin42/login.php' style='display:block;width:140px;height:35px;background:#7fdcd3;margin:0 auto;border-radius:10px;line-height:35px;color:#fff;text-decoration:none;'>Login Here</a>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions, please send an email to <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]</a> </p>
		          	<p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Regards, </p>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Team BaiPaJoin</p>
		          </div>
		        </div>
		      </body>
		    </html>
		";
	}

	else if($type == 'Organizer') {
		$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Welcome</title>
		      </head>
		      <body style='background:url(\"cid:background\");font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:200px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Dear ".$name.",</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Welcome to BaiPaJoin! We have come bearing great news: your account has been activated and you can now start posting your adventures! Be sure, to create a voucher when you upload your 1st adventure to get a increase your adventure bookings! Have a look at our Terms and Conditions to know what is in the store for you. Your adventure awaits! Welcome aboard! We are incredibly excited to have you here. Want to start your adventure? Click the button below to login.</p>
		            <a href='localhost/BaiPaJoin42/login.php' style='display:block;width:140px;height:35px;background:#7fdcd3;margin:0 auto;border-radius:10px;line-height:35px;color:#fff;text-decoration:none;'>Login Here</a>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions, please send an email to <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]</a> </p>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Regards, </p>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Team BaiPaJoin</p>
		          </div>
		        </div>
		      </body>
		    </html>
		";
	}

	return $message;
}

##### EMAIL TEMPLATE FOR NEW ACCOUNTS
function html_welcome_message_social($name, $password) {

	$message = "
	    <!DOCTYPE html>
	    <html>
	      <head>
	        <meta charset='utf-8'>
	        <title>BaiPaJoin | Welcome</title>
	      </head>
	      <body style='background:url(\"cid:background\");font:normal 15px/20px Verdana,sans-serif;'>
	        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
	          <div class='contents' style='text-align:center;color:#1a1a1a;'>
	            <figure class='main-logo'>
	              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
	            </figure>
	            <figure >
	              <img src='cid:main' style='max-width:100%;width:300px;height:200px;margin:0 auto;'>
	            </figure>
	            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Dear ".$name.",</h1>
	            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Welcome to BaiPaJoin! We have come bearing great news: your account has been activated and now you can enjoy convenient and hassle-free adventures in a click. Your account <b>USERAME</b> is your email address and your <b>TEMPORARY PASSWORD</b> is ".$password.".Be sure, to apply or select a voucher when you book your 1st adventure to get a discount! Have a look at our Terms and Conditions to know what is in the store for you. We are incredibly excited to have you here. Want to start your adventure? Click the button below to login.</p>
	            <a href='localhost/BaiPaJoin42/login.php' style='display:block;width:140px;height:35px;background:#7fdcd3;margin:0 auto;border-radius:10px;line-height:35px;color:#fff;text-decoration:none;'>Login Here</a>
	            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions, please send an email to <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]</a> </p>
	          	<p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Regards, </p>
	            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Team BaiPaJoin</p>
	          </div>
	        </div>
	      </body>
	    </html>
	";

	return $message;
}

function html_resetpassword_message($name, $temppass) {

		$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Reset Password</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 100%;background-size:250px 250px;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:240px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Temporary Password</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! Don't worry! We've got you covered. Here's your temporary password <b>".$temppass."</b>. Please make sure to change your password as soon as you've login to protect your self from hacking. </p>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions or didn't make this change, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com].  </a> </p>
		          </div>
		        </div>
		      </body>
		    </html>
		";

	return $message;
}

function html_reschedule_message($name, $current, $resched) {

		$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Reschedule</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 360%;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:200px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Hooray! Adventure Rescheduled!</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! Your adventure has been successfully rescheduled from <b>".$current."</b> to <b>".$resched."</b>. We're happy to serve you again! Enjoy your BaiPaJoin Adventure!</p>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you did not make this changes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]. Thank you!</a> </p>
		          </div>
		        </div>
		      </body>
		    </html>
		";

	return $message;
}

function html_requestdate_joiner_message($name, $date) {

		$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Request Date</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 360%;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:200px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Hooray! Requested Date Sent!</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! We've recieved your request to open an adventure on <b>".$date."</b>. We're happy to forward this request to the organizer for action. We hope serve and see you soon! Enjoy your BaiPaJoin Adventure!</p>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you did not make this changes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]. Thank you!</a> </p>
		          </div>
		        </div>
		      </body>
		    </html>
		";

	return $message;
}


function html_request_message($name, $req_type, $type) {

	if($type == 'joiner') {
		# CANCELATION REQUEST
		if($req_type==1) {
			$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Request</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 360%;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:200px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Request Acknowledge</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! We've recieved your request to <b>CANCEL</b> an adventure booking. Rest assured that we will do our best to review and provide a feedback as soon as possible. Due to the large volume of requests on review, it may take us between 12-48 hours to get back to you. In the meanwhile, please check your email or sms from time to time as we will provide an update thru these channels.</p>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you did not make this changes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]. Thank you!</a> </p>
		          </div>
		        </div>
		      </body>
		    </html>
			";
		}
		else if($req_type==2) {
		}
		else if($req_type==3) {
		}
	}

	else if($type == 'organizer') {
		# CANCELATION REQUEST
		if($req_type==1) {
			$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Request</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 360%;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:200px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Request Acknowledge</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! We've recieved your request to <b>CANCEL</b> an adventure. Rest assured that we will do our best to review and provide a feedback as soon as possible. Due to the large volume of requests on review, it may take us between 12-48 hours to get back to you. In the meanwhile, please check your email or sms from time to time as we will provide an update thru these channels.</p>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you did not make this changes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]. Thank you!</a> </p>
		          </div>
		        </div>
		      </body>
		    </html>
			";
		}
		else if($req_type==2) {
			$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Request</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 360%;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:200px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Request Acknowledge</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! We've recieved your request for adventure <b>WITHDRAWAL OR PAYOUT</b>. . Please provide us with your bank details by responding to this email. In order for us to transfer the payout amount as soon as possible, please refer to the sample below. In the meanwhile, please check your email or sms from time to time as we will provide an update thru these channels.</p>
		            <table style='width:500px;max-width:100%;margin:0 auto; line-height:25px; text-align:left;'>
		              <h2>SAMPLE BANK DETAILS</h2>
		              <tr>
		                <td>Bank Name :</td>
		                <td>BDO UniBank Ltd.</td>
		              </tr>
		              <tr>
		                <td>Bank Code :</td>
		                <td>BDO Colon 1</td>
		              </tr>
		              <tr>
		                <td>Bank Address :</td>
		                <td>Sanciangko St, Cebu City</td>
		              </tr>
		              <br>
		              <tr>
		                <td>Account Name :</td>
		                <td>Juan Dela Cruz</td>
		              </tr>
		               <tr>
		                <td>Account Number : </td>
		                <td>123-456-7890</td>
		              </tr>
		              <tr>
		                <td>Account Type :</td>
		                <td>(Savings/Checking)</td>
		              </tr>
		              <tr>
		                <td>Routing Number : </td>
		                <td>123-456-7890-123-456789</td>
		              </tr>
		            </table>
		            <br>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Once we receive your banking details we will process the refund within 24-48 hours upon receipt of the email. Once, we send it you will recieve a notification thru SMS and EMail about the it. Also, you may view the refund transaction receipt in payout section of the request tab. Thank you!</p>
		            <br><br>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you did not make this changes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]. Thank you!</a> </p>
		          </div>
		        </div>
		      </body>
		    </html>
			";
		}
		else if($req_type==3) {
		}
	}

	return $message;
}

function html_cancellation_message($name, $type, $book_id) {

	if($type == 'Joiner') {
		$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Cancelation</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 110%;background-size:350px 300px;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:270px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Oooh No! Adventure Cancelled!</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! I'm sorry to hear that you have cancel your adventure. We've recieved your request to cancel booking id <b>".$book_id."</b> and it's already <b>APPROVED</b>. Please provide us with your bank details by responding to this email. In order for us to transfer the refund amount as soon as possible, please refer to the sample below. Stay safe and thank you for using BaiPaJoin!</p>
	            	<table style='width:500px;max-width:100%;margin:0 auto; line-height:25px; text-align:left;'>
		              <h2>SAMPLE BANK DETAILS</h2>
		              <tr>
		                <td>Bank Name :</td>
		                <td>BDO UniBank Ltd.</td>
		              </tr>
		              <tr>
		                <td>Bank Code :</td>
		                <td>BDO Colon 1</td>
		              </tr>
		              <tr>
		                <td>Bank Address :</td>
		                <td>Sanciangko St, Cebu City</td>
		              </tr>
		              <br>
		              <tr>
		                <td>Account Name :</td>
		                <td>Juan Dela Cruz</td>
		              </tr>
		               <tr>
		                <td>Account Number : </td>
		                <td>123-456-7890</td>
		              </tr>
		              <tr>
		                <td>Account Type :</td>
		                <td>(Savings/Checking)</td>
		              </tr>
		              <tr>
		                <td>Routing Number : </td>
		                <td>123-456-7890-123-456789</td>
		              </tr>
		            </table>
		            <br>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Once we receive your banking details we will process the refund within 24-48 hours upon receipt of the email. Once, we send it you will recieve a notification thru SMS and EMail about the it. Also, you may view the refund transaction receipt in payout section of the request tab. Thank you!</p>
		            <br><br>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions or did not make this changes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com].  </a> </p>
		          </div>
		        </div>
		      </body>
    		</html>
		";
	}

	if($type == 'JoinerOrganizer') {
		$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Cancelation</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 110%;background-size:350px 300px;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:270px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Oooh No! Adventure Cancelled!</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! We're sorry to inform you that the organizer cancel your booked adventure id <b>".$book_id."</> due to unforeseen circumtances. We've reviewed the case and we concur with the organizer to cancel the adventure. Please provide us with your bank details by responding to this email. In order for us to transfer the refund amount as soon as possible, please refer to the sample below. Stay safe and thank you for using BaiPaJoin!</p>
	            	<table style='width:500px;max-width:100%;margin:0 auto; line-height:25px; text-align:left;'>
		              <h2>SAMPLE BANK DETAILS</h2>
		              <tr>
		                <td>Bank Name :</td>
		                <td>BDO UniBank Ltd.</td>
		              </tr>
		              <tr>
		                <td>Bank Code :</td>
		                <td>BDO Colon 1</td>
		              </tr>
		              <tr>
		                <td>Bank Address :</td>
		                <td>Sanciangko St, Cebu City</td>
		              </tr>
		              <br>
		              <tr>
		                <td>Account Name :</td>
		                <td>Juan Dela Cruz</td>
		              </tr>
		               <tr>
		                <td>Account Number : </td>
		                <td>123-456-7890</td>
		              </tr>
		              <tr>
		                <td>Account Type :</td>
		                <td>(Savings/Checking)</td>
		              </tr>
		              <tr>
		                <td>Routing Number : </td>
		                <td>123-456-7890-123-456789</td>
		              </tr>
		            </table>
		            <br>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Once we receive your banking details we will process the refund within 24-48 hours upon receipt of the email. Once, we send it you will recieve a notification thru SMS and EMail about the it. Also, you may view the refund transaction receipt in payout section of the request tab. Thank you!</p>
		            <br><br>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions or did not make this changes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com].  </a> </p>
		          </div>
		        </div>
		      </body>
    		</html>
		";
	}

	if($type == 'Organizer') {
		$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Cancelation</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 110%;background-size:350px 300px;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:270px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Oooh No! Adventure Cancelled!</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! I'm sorry to hear that you have cancel the adventure. We've recieved your request and it's already <b>APPROVED</b>. Stay safe and thank you for using BaiPaJoin!</p>
	            	<br>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions or did not make this changes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com].  </a> </p>
		          </div>
		        </div>
		      </body>
    		</html>
		";
	}

	if($type == 'denied') {
		$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Cancelation</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 110%;background-size:350px 300px;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:270px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Oooh No! Adventure Cancelled!</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! I'm sorry to hear that you have cancel the adventure. We've recieved your request and upon further review, the request is <b>DISAPPROVED</b>. Due to the fact that you have not met the criteria and standard set on the Terms and Conditions of this site. Stay safe and thank you for using BaiPaJoin!</p>
	            	<br>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions or would like to appeal the request, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com].  </a> </p>
		          </div>
		        </div>
		      </body>
    		</html>
		";
	}

	return $message;
}

function html_payout_message($name, $type, $amount) {

	if($type == 'Joiner') {
		$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Payout</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 110%;background-size:350px 300px;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:270px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Yaaaaay! Payout Successful!</h1>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! We've <b>SUCCESSFULLY</b> sent out your refund amounting to ".$amount." PHP. A copy of the transaction reciept has been uploaded in the BaiPaJoin site. You may view the receipt in the payout section of the requests tab. Stay safe and thank you for using BaiPaJoin!</p>
		            <br>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions or any disputes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com].  </a> </p>
		          </div>
		        </div>
		      </body>
    		</html>
		";
	}

	if($type == 'Organizer') {
		$message = "
		    <!DOCTYPE html>
		    <html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | Payout</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 110%;background-size:350px 300px;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:300px;height:270px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Yaaaaay! Payout Successful!</h1>
		             <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! We've <b>SUCCESSFULLY</b> sent out your refund amounting to ".$amount." PHP. A copy of the transaction reciept has been uploaded in the BaiPaJoin site. You may view the receipt in the payout section of the requests tab. Stay safe and thank you for using BaiPaJoin!</p>
		            <br>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions or any disputes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com].  </a> </p>
		          </div>
		        </div>
		      </body>
    		</html>
		";
	}

	return $message;
}

function html_transreceipt_message($attach, $type) {

	if($type == 'card') {
		$name = $attach['data']['attributes']['payments'][0]['attributes']['billing']['name'];
		$address = $attach['data']['attributes']['payments'][0]['attributes']['billing']['address']['line1'];
		$mobile = $attach['data']['attributes']['payments'][0]['attributes']['billing']['phone'];
		$email = $attach['data']['attributes']['payments'][0]['attributes']['billing']['email'];
		$id = $attach['data']['id'];
		$description = $attach['data']['attributes']['description'];
		$amount = ($attach['data']['attributes']['amount'] / 100);
		$fee = ($attach['data']['attributes']['payments'][0]['attributes']['fee'] / 100);
		$foreign_fee = ($attach['data']['attributes']['payments'][0]['attributes']['foreign_fee'] / 100);
		$net_amount = ($attach['data']['attributes']['payments'][0]['attributes']['net_amount'] / 100);
		$currency = $attach['data']['attributes']['currency'];
		$pay_type = $attach['data']['attributes']['payments'][0]['attributes']['source']['type'];
		$card_brand = $attach['data']['attributes']['payments'][0]['attributes']['source']['brand'];
		$card_last_num = $attach['data']['attributes']['payments'][0]['attributes']['source']['last4'];
		$available_at = $attach['data']['attributes']['payments'][0]['attributes']['available_at'];
		$paid_at = $attach['data']['attributes']['payments'][0]['attributes']['updated_at'];

		$message = "
			<html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | TRANSACTION RECEIPT</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 100%;background-size:350px 350px;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:350px;height:225px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-40px 0 60px;color:#000;font-size:30px;'>Payment Successful</h1>
		            <table style='width:500px;max-width:100%;margin:0 auto; line-height:25px; text-align:left;'>
		              <h2>Transaction Receipt</h2>
		              <tr>
		                <td>Name :</td>
		                <td>".$name."</td>
		              </tr>
		              <tr>
		                <td>Address :</td>
		                <td>".$address."</td>
		              </tr>
		              <tr>
		                <td>Phone Number :</td>
		                <td>".$mobile."</td>
		              </tr>
		              <tr>
		                <td>Email :</td>
		                <td>".$email."</td>
		              </tr>
		               <tr>
		                <td>Payment ID : </td>
		                <td>".$id."</td>
		              </tr>
		              <tr>
		                <td>Description :</td>
		                <td>".$description."</td>
		              </tr>
		              <tr>
		                <td>Total Amount : </td>
		                <td>".$amount."</td>
		              </tr>
		              <tr>
		                <td>Fee :</td>
		                <td>".$fee."</td>
		              </tr>
		              <tr>
		                <td>Foreign Fee :</td>
		                <td>".$foreign_fee."</td>
		              </tr>
		              <tr>
		                <td>Net Amount : </td>
		                <td>".$net_amount."</td>
		              </tr>
		              <tr>
		                <td>Currency : </td>
		                <td>".$currency."</td>
		              </tr>
		              <tr>
		                <td>Payment Type :</td>
		                <td>".$pay_type."</td>
		              </tr>
		              <tr>
		                <td>Brand :</td>
		                <td>".$card_brand."</td>
		              </tr>
		              <tr>
		                <td>Card Last  4 Digits :</td>
		                <td>".$card_last_num."</td>
		              </tr>
		              <tr>
		                <td>Created at:</td>
		                <td>".date('m/d/Y H:i:s', $available_at)."</td>
		              </tr>
		              <tr>
		                <td>Paid at:</td>
		                <td>".date('m/d/Y H:i:s', $paid_at)."</td>
		              </tr>
		            </table>
		            <p style='margin:50px 0 0;'>Powered by: Paymongo <span style='display:block;'></span> </p>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:0 auto;'>If you have any questions or dispute, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]</a> </p>
		          </div>
		        </div>
		      </body>
		    </html>
	    ";
	}

	if($type == 'gcash') {

		$name = $attach['data']['attributes']['data']['attributes']['billing']['name'];
    	$mobile = $attach['data']['attributes']['data']['attributes']['billing']['phone'];
    	$email = $attach['data']['attributes']['data']['attributes']['billing']['email'];
    	$amount = ($attach['data']['attributes']['data']['attributes']['amount'] / 100);
    	$currency = $attach['data']['attributes']['data']['attributes']['currency'];
    	$method = $attach['data']['attributes']['data']['attributes']['source']['type'];
    	$transaction_id = $attach['data']['attributes']['data']['id'];
    	$fee = ($attach['data']['attributes']['data']['attributes']['fee'] / 100);
    	$available_at = $attach['data']['attributes']['data']['attributes']['available_at'];
    	$paid_at = $attach['data']['attributes']['data']['attributes']['paid_at'];

		$message = "
			<html>
		      <head>
		        <meta charset='utf-8'>
		        <title>BaiPaJoin | TRANSACTION RECEIPT</title>
		      </head>
		      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 100%;background-size:350px 350px;font:normal 15px/20px Verdana,sans-serif;'>
		        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
		          <div class='contents' style='text-align:center;color:#1a1a1a;'>
		            <figure class='main-logo'>
		              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
		            </figure>
		            <figure >
		              <img src='cid:main' style='max-width:100%;width:350px;height:225px;margin:0 auto;'>
		            </figure>
		            <h1 style='margin:-40px 0 60px;color:#000;font-size:30px;'>Payment Successful</h1>
		            <table style='width:500px;max-width:100%;margin:0 auto; line-height:25px; text-align:left;'>
		              <h2>Transaction Receipt</h2>
		              <tr>
		                <td>Name :</td>
		                <td>".$name."</td>
		              </tr>
		              <tr>
		                <td>Phone Number :</td>
		                <td>".$mobile."</td>
		              </tr>
		              <tr>
		                <td>Email :</td>
		                <td>".$email."</td>
		              </tr>
		               <tr>
		                <td>Payment ID : </td>
		                <td>".$transaction_id."</td>
		              </tr>
		              <tr>
		                <td>Total Amount : </td>
		                <td>".$amount."</td>
		              </tr>
		              <tr>
		                <td>Fee :</td>
		                <td>".$fee."</td>
		              </tr>
		              <tr>
		                <td>Net Amount : </td>
		                <td>".($amount-$fee)."</td>
		              </tr>
		              <tr>
		                <td>Currency : </td>
		                <td>".$currency."</td>
		              </tr>
		              <tr>
		                <td>Payment Type :</td>
		                <td>".$type."</td>
		              </tr>
		              <tr>
		                <td>Created at:</td>
		                <td>".date('m/d/Y H:i:s', $available_at)."</td>
		              </tr>
		              <tr>
		                <td>Paid at:</td>
		                <td>".date('m/d/Y H:i:s', $paid_at)."</td>
		              </tr>
		            </table>
		            <p style='margin:50px 0 0;'>Powered by: Paymongo <span style='display:block;'></span> </p>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:0 auto;'>If you have any questions or dispute, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]</a> </p>
		          </div>
		        </div>
		      </body>
		    </html>
	    ";
	}

	return $message;
}

function html_bookitinerary_message($book, $adventure, $guests , $joiner) {

	if(!empty($guests))
		$guest_data = $guests[0];

	$message = "
		<html>
	      <head>
	        <meta charset='utf-8'>
	        <title>BaiPaJoin | BOOKING ITINERARY</title>
	      </head>
	      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url(\"cid:background\") no-repeat center;background-position:50% 100%;background-size:550px 700px;font:normal 15px/20px Verdana,sans-serif;'>
	        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
	          <div class='contents' style='text-align:center;color:#1a1a1a;'>
	            <figure class='main-logo'>
	              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
	            </figure>
	            <figure >
	              <img src='cid:main' style='max-width:100%;width:350px;height:225px;margin:0 auto;'>
	            </figure>
	            <h1 style='margin:-40px 0 60px;color:#000;font-size:30px;'>Booking Itinerary</h1>
	            <table style='width:500px;max-width:100%;margin:0 auto; line-height:25px; text-align:left;'>
	              <h2>Adventure Details</h2>
	              <tr>
	                <td>Adventure Name :</td>
	                <td>".$adventure['adv_name']."</td>
	              </tr>
	              <tr>
	                <td>Adventure Activity : </td>
	                <td>".$adventure['adv_kind']."</td>
	              </tr>
	              <tr>
	                <td>Adventure Type :</td>
	                <td>".$adventure['adv_type']."</td>
	              </tr>
	              <tr>
	                <td>Adventure Town :</td>
	                <td>".$adventure['adv_town']."</td>
	              </tr>
	              <tr>
	                <td>Adventure Date :</td>
	                <td>".$adventure['adv_date']."</td>
	              </tr>
	            </table>
	            <br>
	            <table style='width:500px;max-width:100%;margin:0 auto; line-height:25px; text-align:left;'>
	              <h2>Guest Details</h2>
	";

	if($book['book_guests'] != count($guests)) {
		$message .= "
				<tr>
   					<td>Guest 1 Full Name:</td>
    				<td>".$joiner['joiner_fname']." ".$joiner['joiner_lname']."</td>
  				</tr>
		";

		for ($i=0; $i < count($guests) ; $i++) {
			$j = $i+1;
			$message .= "
				<tr>
	                <td>Guest ".($j+1)." Full Name: </td>
	                <td>".$guest_data[0]."</td>
	            </tr>
			";
		}
	}
	else {
		for ($i=0; $i < count($guests) ; $i++) {
			$j = $i;
			$message .= "
				<tr>
	                <td>Guest ".($j+1)." Full Name: </td>
	                <td>".$guest_data[0]."</td>
	            </tr>
			";
		}
	}

	$message .= "
	            </table>
	            <br>
	            <table style='width:500px;max-width:100%;margin:0 auto; line-height:25px; text-align:left;'>
	              <h2 >Booking Details</h2>
	              <tr>
	                <td>Booking ID :</td>
	                <td>".$book['book_id']."</td>
	              </tr>
	              <tr>
	                <td>Booking Date & Time :</td>
	                <td>".$book['book_datetime']."</td>
	              </tr>
	              <tr>
	                <td>Booking Cost :</td>
	                <td>".$book['book_totalcosts']." PHP</td>
	              </tr>
	               <tr>
	                <td>Booking Status : </td>
	                <td>PAID</td>
	              </tr>
	            </table>
	            <br><br>
	            <p style='line-height:20px;width:1000px;max-width:100%;margin:0 auto;'>If you have any questions or dispute, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]</a> </p>
	          </div>
	        </div>
	      </body>
	    </html>
	";

	return $message;
}

## EACH ADVENTURE RATINGS
function adv_ratings($adv_id, $specific_advs = false, $counts = null){
	$total_stars = 0;
	$counter = 0;
	$this_adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($adv_id), "READ");
	$this_adv = $this_adv[0];
	## ADVENTURE THAT HAS BEEN RATED WITH SPECIFIC ORGANIZER
	$adv = DB::query("SELECT * FROM rating r JOIN booking b ON r.book_id=b.book_id JOIN adventure a ON b.adv_id=a.adv_id WHERE orga_id=? AND rating_message != ''", array($this_adv['orga_id']), "READ");
	##
	if(count($adv)>0){
		foreach ($adv as $result) {
			## IF $specific_advs IS TRUE && DISPLAY SPECIFIC RATINGS
			if($specific_advs) {
				if($result['adv_name'] == $this_adv['adv_name'] && $result['adv_kind'] == $this_adv['adv_kind'] && $result['adv_type'] == $this_adv['adv_type'] && $result['adv_address'] == $this_adv['adv_address']){
					$total_stars += $result['rating_stars'];
					$counter++;
				}

			## FALSE && DISPLAY ALL RATINGS
			} else {
				$total_stars += $result['rating_stars'];
				$counter++;
			}
		}
	}

	if($counts == null){
		if($total_stars == 0) return $total_stars;
		else return $total_stars = $total_stars / $counter;
	} else return $counter;
}

## ORGANIZER RATINGS
function orga_ratings($orga_id){
	$ratings = DB::query("SELECT * FROM rating r JOIN booking b ON r.book_id=b.book_id JOIN adventure a ON b.adv_id=a.adv_id WHERE orga_id=?", array($orga_id), "READ");
	$count_ratings = 0;
	$total_ratings = 0;
	## ALL RATINGS
	if(count($ratings)>0){
		foreach ($ratings as $result) {
			##
			if(isset($per_rate) && $per_rate == $result['rating_img']) continue;
			$sub_rate = 0;
			$rate = DB::query("SELECT * FROM rating WHERE rating_img=?", array($result['rating_img']), "READ");
			## EACH RATING
			if(count($rate)>0){
				foreach ($rate as $key) {
					$sub_rate += $key['rating_stars'];
				}
				$per_rate = $result['rating_img'];
				## SUB TOTAL RATING
				$sub_rate /= count($rate);
			}
			$total_ratings += $sub_rate;
			$count_ratings++;
		}
		$total_ratings /= $count_ratings;
		return $total_ratings;
	## NO RATINGS YET
	} else {
		return 0;
	}
}

## CHECK ADVENTURES AVAILABILITY IN TERMS OF CANCELING, BOOKING, RESCHEDULING, PAYING FOR JOINER
function adv_is_available($adv_id, $type){
	$adv = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($adv_id), "READ");
	## CHECK IF ADV EXISTS
	if(count($adv)>0){
		$adv = $adv[0];
		if($type == "cancel"){
			## CHECK IF CURRENT DATE IS NOT THE 10TH DAY BEFORE ADV
			$not_avail_date = date("Y-m-d", strtotime("-10 days", strtotime($adv['adv_date'])));
			if(date("Y-m-d") >= $not_avail_date)
				return false;
			else
				return true;

		} elseif ($type == "pay"){
			## CHECK IF CURRENT DATE IS NOT THE DAY ADV HAPPENS
			$not_avail_date = date("Y-m-d", strtotime("-1 days", strtotime($adv['adv_date'])));
			if(date("Y-m-d") > $not_avail_date)
				return false;
			else
				return true;

		} elseif ($type == "resched" || $type == "book") {
			## CHECK IF CURRENT DATE IS NOT THE 5TH DAY BEFORE ADV
			$not_avail_date = date("Y-m-d", strtotime("-5 days", strtotime($adv['adv_date'])));
			if(date("Y-m-d") >= $not_avail_date)
				return false;
			else
				return true;

		} else {
			return false;
		}
	}

	return false;
}

## ADV IS REVERTED AFTER RESCHEDULED
function adv_is_reverted($book_id){
	$req = DB::query("SELECT * FROM request WHERE book_id=? AND req_status=?", array($book_id, "reverted"), "READ");
	if(count($req)>0)
		return true;
	return false;
}

## GOOGLE MAP OF PLACES
function google_map($place){
	if($place == "Bantayan Island"){
		echo "
		<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125234.42046805266!2d123.67849425725015!3d11.219000667005867!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a886e327a8c905%3A0x9420bd2b1f535656!2sBantayan%20Island!5e0!3m2!1sen!2sph!4v1635172828242!5m2!1sen!2sph' style='width:100%;height:100%;' allowfullscreen='' loading='lazy'></iframe>";

	} elseif($place == "Malapascua Island"){
		echo "
		<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15647.867641202327!2d124.10535138248265!3d11.337127560148321!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a7de60aa76852f%3A0xe9237836540d3363!2sMalapascua%20Island!5e0!3m2!1sen!2sph!4v1635173564650!5m2!1sen!2sph' style='width:100%;height:100%;' allowfullscreen='' loading='lazy'></iframe>";

	} elseif($place == "Camotes Island"){
		echo "
		<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125456.72281399614!2d124.34960560479635!3d10.694116229752517!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a82954c1a4cdfb%3A0x3cad770124ea1811!2sCamotes%20Islands!5e0!3m2!1sen!2sph!4v1635173842282!5m2!1sen!2sph' style='width:100%;height:100%;' allowfullscreen='' loading='lazy'></iframe>";

	} elseif($place == "Moalboal"){
		echo "
		<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62879.92029489318!2d123.39621602946062!3d9.934371776563639!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33abe8630166d475%3A0x20ac43b5c4d02c78!2sMoalboal%2C%20Cebu!5e0!3m2!1sen!2sph!4v1635173878794!5m2!1sen!2sph' style='width:100%;height:100%;' allowfullscreen='' loading='lazy'></iframe>";

	} elseif($place == "Badian"){
		echo "
		<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62898.13117864832!2d123.38717292937235!3d9.839178567781673!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33abea4c617080f3%3A0xb806741a9d418b34!2sBadian%2C%20Cebu!5e0!3m2!1sen!2sph!4v1635173913371!5m2!1sen!2sph' style='width:100%;height:100%;' allowfullscreen='' loading='lazy'></iframe>";

	} elseif($place == "Oslob"){
		echo "
		<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125915.7915430259!2d123.34229640006367!3d9.520153970082804!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33ab9fc3ba1584b3%3A0xcb54005bbcc85855!2sOslob%2C%20Cebu!5e0!3m2!1sen!2sph!4v1635173945529!5m2!1sen!2sph' style='width:100%;height:100%;' allowfullscreen='' loading='lazy'></iframe>";

	} elseif($place == "Alcoy"){
		echo "
		<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62920.18334603922!2d123.43448572926658!3d9.722670767938139!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33abbddafece6bfb%3A0xf15a0cc4c94d0d76!2sAlcoy%2C%20Cebu!5e0!3m2!1sen!2sph!4v1635173973794!5m2!1sen!2sph' style='width:100%;height:100%;' allowfullscreen='' loading='lazy'></iframe>";

	} elseif($place == "Aloguinsan"){
		echo "
		<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62829.04332036611!2d123.5277837797116!3d10.195657312333257!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a96449565c1269%3A0xf13a9e485cd4f776!2sAloguinsan%2C%20Cebu!5e0!3m2!1sen!2sph!4v1635174002890!5m2!1sen!2sph' style='width:100%;height:100%;' allowfullscreen='' loading='lazy'></iframe>";

	} elseif($place == "Santander"){
		echo "
		<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62970.56148864604!2d123.2926935290299!3d9.451174483573107!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33ab714241461127%3A0x77a9e6bcbdd5b4ad!2sSantander%2C%20Cebu!5e0!3m2!1sen!2sph!4v1635174036626!5m2!1sen!2sph' style='width:100%;height:100%;' allowfullscreen='' loading='lazy'></iframe>";

	} elseif($place == "Alegria"){
		echo "
		<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62914.44433296058!2d123.34766537929396!3d9.753124254858855!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33ab96b199f31027%3A0x1aae490622a879d7!2zQWxlZ3LDrWEsIENlYnU!5e0!3m2!1sen!2sph!4v1635174091531!5m2!1sen!2sph' style='width:100%;height:100%;' allowfullscreen='' loading='lazy'></iframe>";

	} elseif($place == "Dalaguete"){
		echo "
		<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62904.91529812463!2d123.46117642933969!3d9.803482183182943!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33abb92a0116db4f%3A0x1e4b0b1385537b3b!2sDalaguete%2C%20Cebu!5e0!3m2!1sen!2sph!4v1635174119147!5m2!1sen!2sph' style='width:100%;height:100%;' allowfullscreen='' loading='lazy'></iframe>";

	} else {}
}

function get_most_favorite_adventure() {

	$current_date = date('Y-m-d');

	$adv_db = DB::query("SELECT adv_id FROM adventure WHERE adv_date > '$current_date'", array(), "READ");

	$high_count = 0;

	foreach($adv_db as $adv) {
		$count_db = DB::query("SELECT count(adv_id) FROM favorite WHERE adv_id =?", array($adv[0]), "READ");
		$count = $count_db[0];
		if($count[0] > $high_count) {
			$high_count = $count[0];
			$favorite_db = DB::query("SELECT adv_id FROM adventure WHERE adv_id = ?", array($adv[0]), "READ");
			$favorite = $favorite_db[0];
		}
	}

	if(!empty($favorite))
		return $favorite[0];
	else
		return -1;
}

function get_best_seller_adventure() {

	$current_date = date('Y-m-d');

	$adv_db = DB::query("SELECT adv_id FROM adventure WHERE adv_date > '$current_date'", array(), "READ");

	$high_count = 0;

	foreach($adv_db as $adv) {
		$count_db = DB::query("SELECT count(adv_id) FROM booking WHERE book_status='paid' AND adv_id =?", array($adv[0]), "READ");
		$count = $count_db[0];
		if($count[0] > $high_count) {
			$high_count = $count[0];
			$best_seller_db = DB::query("SELECT adv_id FROM adventure WHERE adv_id = ?", array($adv[0]), "READ");
			$best_seller = $best_seller_db[0];
		}
	}

	if(!empty($best_seller))
		return $best_seller[0];
	else
		return -1;
}

function get_most_popular_adventure() {

	$current_date = date('Y-m-d');

	$adv_db = DB::query("SELECT adv_id FROM adventure WHERE adv_date > '$current_date'", array(), "READ");

	$high_count = 0;

	foreach($adv_db as $adv) {
		$count_db = DB::query("SELECT count(adv_id) FROM booking WHERE adv_id =?", array($adv[0]), "READ");
		$count = $count_db[0];
		if($count[0] > $high_count) {
			$high_count = $count[0];
			$popular_db = DB::query("SELECT adv_id FROM adventure WHERE adv_id = ?", array($adv[0]), "READ");
			$popular = $popular_db[0];
			//file_put_contents('debug.log', date('h:i:sa').' => '. $high_count.' : '. $best_seller[0] . "\n" . "\n", FILE_APPEND);
		}
	}

	if(!empty($popular))
		return $popular[0];
	else
		return -1;
}

function get_voucher_discount($adv_id) {

	$current_date = date('Y-m-d');

	$discount = DB::query("SELECT vouch_discount FROM voucher WHERE vouch_enddate >= '$current_date' AND  vouch_startdate <= '$current_date' AND adv_id=?", array($adv_id), "READ");
	if(!empty($discount)) {
		$discount = $discount[0];
		if(count($discount)>1) {
			$high_count = 0;
			foreach($discount as $item) {
				if($item > $high_count)
					$high_count = $item;
			}
			return $high_count;
		}
		else if(count($discount)==1)
			return $discount[0];
	}
	else
		return -1;
}

function filter_ratings($rate){
	$rate_txt = "";
	for($i=0; $i<$rate; $i++){
		$rate_txt = $rate_txt."<i class='fas fa-star'></i> ";
	}
	return $rate_txt;
}

##### END OF CODES #####
