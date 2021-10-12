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

	//CHECK IF ACCOUNT USER TYPE EXIST
	if($cboType === "Joiner") {
		//CHECK IF EMAIL ALREADY EXIST FOR JOINER
		$checkEmail = DB::query("SELECT * FROM joiner WHERE joiner_email=?", array($emEmail), "READ");
	} elseif($cboType === "Organizer") {
		//CHECK IF EMAIL ALREADY EXIST FOR ORGANIZER
		$checkEmail = DB::query("SELECT * FROM organizer WHERE orga_email=?", array($emEmail), "READ");
	} else {
		//CHECK IF EMAIL ALREADY EXIST FOR ADMIN
		$checkEmail = DB::query("SELECT * FROM admin WHERE admin_email=?", array($emEmail), "READ");
	}
	//
	if(count($checkEmail)>0){
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
		## CHECK IF ORGANIZER IS NOT BANNED
		if($organizerAccount['orga_status'] <= 2){
			$_SESSION['organizer'] = $organizerAccount['orga_id'];

			header('Location: index.php');
			exit;
		}

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

	$email_subject = 'WELCOME TO BAIPAJOIN';
	$email_message = 'Dear '.$first_name.', Thank you for signing up BaiPaJoin! Your USERNAME is "'.$email.'" and your TEMPORARY PASSWORD is "'.$randomString.'" , you any access your account anytime but you\'re advised to change the password immediately. Thank you! THIS IS A TEST. DO NOT REPLY!';

	send_email($email, $email_subject, $email_message);

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
		DB::query("UPDATE joiner SET joiner_fname=?, joiner_lname=?, joiner_mi=?, joiner_address=?, joiner_phone=?, joiner_email=? WHERE joiner_id=?", array($txtFirstname, $txtLastname, $txtMi, $txtAddress, $txtPhone, $emEmail, $_SESSION['joiner']), "UPDATE");
		//DISPLAY UPDATED CHANGES
		currentJoiner($_SESSION['joiner']);
		//
		header('Location: settings.php?updated=1');
		exit;
	}
}

##### CODE START HERE @ADD USER LEGAL DOCUMENTS (ORGANIZER) #####
function addLegalDocuments(){
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
		DB::query('INSERT INTO legal_document (orga_id, docu_type, docu_description, docu_image, docu_dateadded) VALUES (?,?,?,?,?)', array($_SESSION['organizer'], $cboType, $txtDescription, $imageName, date('Y-m-d')), "CREATE");

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
					$remainingGuestsText = "- ".$numRemainingGuests." guests remaining";

				$no_cancel_starting_date = date("Y-m-d", strtotime("-5 days", strtotime($result['adv_date'])));

				## SKIP DISPLAY IF ADVENTURE IS PENDING FOR CANCELATION
				$pending_adv = DB::query("SELECT * FROM request WHERE adv_id=? AND (req_status=? || req_status=?)", array($result['adv_id'], "pending", "approved"), "READ");
				if(count($pending_adv)>0) continue;

				echo "
				<div class='card'>
					<figure>
						<img src='images/organizers/".$_SESSION['organizer']."/$image[$displayImage]' alt='image'>
					</figure>
					<em> on ".date("F j, Y", strtotime($result['adv_date']))."</em>
					<h2>".$result['adv_name']." - ".$result['adv_kind']." (".$result['adv_type'].")
						<span>5 <i class='fas fa-star'></i> (25 reviews) ";##".."
							if($result['adv_status'] == "done") echo "- done";
							elseif($numRemainingGuests == 0) echo "- full";
							else echo $remainingGuestsText;
					echo "
						</span>
					</h2>
					<p>".$result['adv_address']."</p>
					<p>₱".number_format((float)$price, 2, '.', ',')." / person</p>
					<ul class='icons'>";
				#
				if($result['adv_currentGuest'] == 0){
					echo "
						<li><a href='edit_adv.php?id=".$result['adv_id']."'><i class='fas fa-edit' data-toggle='tooltip' title='Update Adventure'></i></a></li>
						<li><a href='delete.php?table=adventure&id=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to delete this adventure?\");'><i class='far fa-trash-alt' data-toggle='tooltip' title='Remove Adventure'></i></a></li>
					";
				#
				} elseif((date("Y-m-d") < $no_cancel_starting_date) && ($result['adv_currentGuest'] > 0)){
					echo "
						<li><a href='reports_booking-cancel.php?adv_id=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to cancel this adventure? Joiner who are booked can either request refund or reschedule!\");'><i class='fas fa-ban' data-toggle='tooltip' title='Cancel Adventure'></i></a></li>
					";
				#
				} else {
					echo "<li><a href='request-payout.php?adv_id=".$result['adv_id']."' onclick='return confirm(\"Confirm request payout?\");'><i class='fas fa-hand-holding-usd' data-toggle='tooltip' title='Request Payout'></i></a></li>";
				}
				echo "
					</ul>
				</div>";
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
					<h2>".$result['vouch_discount']."% OFF <span>₱".$result['vouch_minspent']." min. spend</span> </h2>
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
					<h2>".$result['vouch_discount']."% OFF <span>₱".$result['vouch_minspent']." min. spend</span> </h2>
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
					$remainingGuestsText = "- ".$numRemainingGuests." guests remaining";

				echo "
				<a class='card-link' href='place.php?id=".$result['adv_id']."'>
				<div class='card'>
					<figure>
						<img src='images/organizers/".$result['orga_id']."/$image[$displayImage]' alt='image'>
					</figure>
					<em> on ".date("F j, Y", strtotime($result['adv_date']))."</em>
					<h2>".$result['adv_name']." - ".$result['adv_kind']." <span>5 <i class='fas fa-star'></i> (25 reviews) ".$remainingGuestsText."</span> </h2>
					<p>".$result['adv_address']."</p>
					<p>₱ ".number_format((float)$price, 2, '.', ',')." / person</p>
					<ul class='icons'>";

			  if(isset($_SESSION['joiner'])){
					$favAdv = DB::query("SELECT * FROM favorite WHERE joiner_id = ? AND adv_id = ?", array($_SESSION['joiner'], $result['adv_id']), "READ");

					if(count($favAdv) > 0)
						echo "<li><a id='saved' class='added' href='favorites.php?removeFav=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to remove this adventure to your favorites?\");'><i class='fas fa-bookmark' data-toggle='tooltip' title='Remove from Favorite'></i></a></li>";
					else
						echo "<li><a href='favorites.php?addFav=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to add this adventure to your favorites?\");'><i class='fas fa-bookmark' data-toggle='tooltip' title='Add to Favorite'></i></a></li>";

				} else
					echo "<li><a href='login.php' onclick='return confirm(\"Are you sure you want to login to add adventures to favorites?\");'><i class='fas fa-bookmark'></i></a></li>";

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
					$remainingGuestsText = "- ".$numRemainingGuests." slots remaining";

				// DISPLAY ALL ADVENTURE WITH FUTURE DATES
				if($result["adv_date"] > date("Y-m-d")){
					echo "
					<div class='card'>
						<span><em>on ".date("M j, Y", strtotime($result['adv_date']))."</em></span>
						<figure>
							<img src='images/organizers/".$result['orga_id']."/$image[$displayImage]' alt=''>
						</figure>
						<em> on ".date("F j, Y", strtotime($result['adv_date']))."</em>
						<h2>".$result['adv_name']." - ".$result['adv_kind']." (".$result['adv_type'].") <span>5 <i class='fas fa-star'></i> (25 reviews) ".$remainingGuestsText."</span> </h2>
						<p>".$result['adv_address']."</p>
						<p>₱".number_format((float)$price, 2, '.', ',')." / person</p>
						<ul class='icons'>";

					if(isset($_SESSION['joiner'])){
						$no_cancel_date = date("Y-m-d", strtotime("-10 days", strtotime($result['adv_date'])));
						## CHECK IF CURRENT DAY IS NOT 10DAYS BVEFORE ADV
						if(date("Y-m-d") > $no_cancel_date) {
							echo "<li><a href='' onclick='return confirm(\"You cannot resched this adventure because it is happening in a few days!\");'><i class='far fa-clock' data-toggle='tooltip' data-placement='top' title='Reschedule to this adventure'></i></a></li>";

						## ADV AVAILABLE TO RESCHED
						} else {
							echo "<li><a href='reports_resched.php?adv_id=".$result['adv_id']."&book_id=".$book_id."' onclick='return confirm(\"You can only resched this adventure once. Are you sure you want to reschedule?\");'><i class='far fa-clock' data-toggle='tooltip' data-placement='top' title='Reschedule to this adventure'></i></a></li>";
						}

					}
					##
					echo "
						</ul>
					</div>
					";
				}
			}
		}
		else {
			echo "<h3>No adventure exists!</h3>";
		}

	// ELSE TO IFS AND DISPLAY ALL ADVENTURES
	} else {
		$card = DB::query("SELECT * FROM adventure", array(), "READ");
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
					$remainingGuestsText = "- ".$numRemainingGuests." slots remaining";

				// DISPLAY ALL ADVENTURE WITH FUTURE DATES
				if($result["adv_date"] > date("Y-m-d")){
					echo "
					<a class='card-link' href='place.php?id=".$result['adv_id']."'>
					<div class='card'>
						<figure>
							<img src='images/organizers/".$result['orga_id']."/$image[$displayImage]' alt=''>
						</figure>
						<em> on ".date("F j, Y", strtotime($result['adv_date']))."</em>
						<h2>".$result['adv_name']." - ".$result['adv_kind']." (".$result['adv_type'].") <span>5 <i class='fas fa-star'></i> (25 reviews) ".$remainingGuestsText."</span> </h2>
						<p>".$result['adv_address']."</p>
						<p>₱".number_format((float)$price, 2, '.', ',')." / person</p>
						<ul class='icons'>";

					if(isset($_SESSION['joiner'])){
						$favAdv = DB::query("SELECT * FROM favorite WHERE joiner_id = ? AND adv_id = ?", array($_SESSION['joiner'], $result['adv_id']), "READ");

						if(count($favAdv) > 0)
							echo "<li><a id='saved' class='added' href='adventures.php?removeFav=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to remove this adventure to your favorites?\");'><i class='fas fa-bookmark' data-toggle='tooltip' data-placement='top' title='Remove from Favorite'></i></a></li>";
						else
							echo "<li><a href='adventures.php?addFav=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to add this adventure to your favorites?\");'><i class='fas fa-bookmark' data-toggle='tooltip' data-placement='top' title='Add to Favorite'></i></a></li>";

					} else {
						echo "<li><a href='login.php' onclick='return confirm(\"Are you sure you want to login to add adventures to favorites?\");'><i class='fas fa-bookmark' data-toggle='tooltip' data-placement='top' title='Add to Favorite'></i></a></li>";
					}
					##
					echo "
						</ul>
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
function deleteSQLDataTable($table, $id){
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
		// DELETE SPECIFIC ID
		DB::query("DELETE FROM {$table} WHERE book_id=? AND book_status=?", array($id, "pending"), 'DELETE');
	}

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
	else {
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
			elseif($cboLoc == "Camotes Island") $town = "Camotes";
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
				elseif($cboLoc == "Camotes Island") $town = "Camotes";
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
		// NO ERROR FOUND
		} else {
			DB::query("INSERT INTO voucher(vouch_code, vouch_discount, vouch_name, vouch_minspent, vouch_startdate, vouch_enddate, orga_id, adv_id, vouch_user) VALUES(?,?,?,?,?,?,?,?,?)", array($vouchCode, $numDiscount, $txtName, $numMinSpent, $dateStartDate, $dateEndDate, $_SESSION['organizer'], $cboAdv, 0), "CREATE");

			header("Location: voucher.php?added=1");
		}
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

				$name = $attach['data']['attributes']['payments'][0]['attributes']['billing']['name'];
				$mobile = $attach['data']['attributes']['payments'][0]['attributes']['billing']['phone'];
				$email = $attach['data']['attributes']['payments'][0]['attributes']['billing']['email'];

				$amount = ($attach['data']['attributes']['amount'] / 100);
				$currency = $attach['data']['attributes']['currency'];
				$card_brand = $attach['data']['attributes']['payments'][0]['attributes']['source']['brand'];
				$card_last_num = $attach['data']['attributes']['payments'][0]['attributes']['source']['last4'];
				$id = $attach['data']['id'];

				$sms_message = "Hooray! Thank you! Your payment for " . $amount . " " . $currency . " thru card number ending in " . $card_last_num . " was SUCCESSFUL!";

				$email_subject = 'BOOKING CONFIRMATION';
    			$email_message = ' Dear '.$name.', Hooray! Your payment for '.$amount.' '.$currency.' thru '.$card_brand.' ending in '. $card_last_num. ' was successful. Your transaction id is '.$id.' . Enjoy your BaiPaJoin Adventure! Thank you! THIS IS A TEST. DO NOT REPLY!';

				send_sms($mobile, $sms_message);
				send_email($email, $email_subject, $email_message);
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
	$_SESSION['book_id'] = $book_id;
	$curl = curl_init();

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

	$ewallet_body_params = '{
	    "data": {
	        "attributes": {
	            "amount": '.$final_price.',
	            "redirect": {
	                "success": "https://6b1c-180-190-172-59.ngrok.io/Melnar%20Ancit/BaiPaJoin061321/thankyou.php?gcash=1",
	                "failed": "https://6b1c-180-190-172-59.ngrok.io/Melnar%20Ancit/BaiPaJoin061321/thankyou.php?gcash=0"
	            },
	            "billing": {
	                "name": "'.$joiner[1].' '.$joiner[2].'",
	                "phone": "'.$joiner[5].'",
	                "email": "'.$joiner[6].'"
	            },
	            "type": "'.$ewallet_type.'",
	            "currency": "PHP"
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

	    	header($redirect);
	} //This code will a log.txt file to get the response of the cURL command

	curl_close($curl);
}

function process_paymongo_ewallet_payment($amount, $source_id) {

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
			//
			$payment_id = $ewallet_payment['data']['id'];
			$ewallet_type = $ewallet_payment['data']['attributes']['source']['type'];
			//
			file_put_contents("test.log", date('h:i:sa').' => '. $payment_id . "\n" .$ewallet_type. "\n ".$_SESSION['book_id']."", FILE_APPEND);
			//
			booking_paid_updates($ewallet_type, $_SESSION['book_id'], $payment_id, $amount);
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

	require 'PHPMailerAutoload.php';

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

##### CODE START HERE @NECESSARY UPDATES WHEN BOOKING IS PAID #####
function booking_paid_updates($method, $book_id, $intent_id, $total=null){
	# UPDATE VOUCHER USERS
	if(isset($_SESSION['used_voucher_code'])){
		$voucher = DB::query("SELECT * FROM voucher WHERE vouch_code=?", array($_SESSION['used_voucher_code']), "READ");
		$voucher = $voucher[0];
		DB::query("UPDATE voucher SET vouch_user=? WHERE vouch_code=?", array($voucher['vouch_user'] + 1, $voucher['vouch_code']), "UPDATE");
	}

	# UPDATE BOOKING STATUS
	$booked = DB::query("SELECT * FROM booking WHERE book_id=?", array($book_id), "READ");
	$booked = $booked[0];
	DB::query("UPDATE booking SET book_status=?, book_totalcosts=? WHERE book_id=?", array("paid", $total, $booked['book_id']), "UPDATE");

	# UPDATE ADVENTURE STATUS
	$adv_booked = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($booked['adv_id']), "READ");
	$adv_booked = $adv_booked[0];
	DB::query("UPDATE adventure SET adv_currentGuest=? WHERE adv_id=?", array($adv_booked['adv_currentGuest'] + $booked['book_guests'], $adv_booked['adv_id']), "UPDATE");
	$adv_booked = DB::query("SELECT * FROM adventure WHERE adv_id=?", array($booked['adv_id']), "READ");
	$adv_booked = $adv_booked[0];
	if($adv_booked['adv_maxguests'] <= $adv_booked['adv_currentGuest'])
		DB::query("UPDATE adventure SET adv_status=? WHERE adv_id=?", array("full", $adv_booked['adv_id']), "UPDATE");

	# INSERT DATA TO PAYMENT TABLE
	DB::query("INSERT INTO payment(payment_id, payment_method, payment_total, payment_datetime, book_id) VALUES(?,?,?,?,?)", array($intent_id, $method, $total, date("Y-m-d H:i:s"), $book_id), "CREATE");

	# IF PAYMENT METHOD IS THRU CARD
	if($method == "card"){
		# SUCCESSFUL MESSAGE
		echo "<h1><span class='success'>Thank you! Successfully paid thru ".$method.".</span></h1>";

	# IF PAYMENT METHOD IS THRU GCASH
	} elseif($method == "gcash") {

	# IF PAYMENT METHOD IS THRU GRAB PAY
	} elseif($method == "grabpay") {

	}
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

function html_cancellation_message($name, $type) {

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
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! I'm sorry to hear that you have cancel your adventure. We've recieved your request and it's being reviewed. In the meanwhile, please check your EMAIL and SMS for the updates. Stay safe and thank you for using BaiPaJoin!</p>
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
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Hi ".$name."! We understand that you have to cancel the posted adventure. There are things that is truely out of your control. We've recieved your request and it's being reviewed. In the meanwhile, please check your EMAIL and SMS for the updates. Stay safe and thank you for using BaiPaJoin!</p>
		            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions or did not make this changes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com].  </a> </p>
		          </div>
		        </div>
		      </body>
    		</html>
		";
	}

	return $message;
}

##### END OF CODES #####
