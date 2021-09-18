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
	//CHECK IF ACCOUNT USER TYPE
	if($cboType === "Joiner") {
		//CHECK IF EMAIL ALREADY EXIST FOR JOINER
		$checkEmail = DB::query("SELECT * FROM joiner WHERE joiner_email=?", array($emEmail), "READ");
	} else {
		//CHECK IF EMAIL ALREADY EXIST FOR ORGANIZER
		$checkEmail = DB::query("SELECT * FROM organizer WHERE orga_email=?", array($emEmail), "READ");
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
			} else {
				//ADD NEW ORGANIZER ACCOUNT
				DB::query("INSERT INTO organizer(orga_fname, orga_lname, orga_mi, orga_email, orga_password) VALUES(?,?,?,?,?)", array($txtFirstname, $txtLastname, $txtMi, $emEmail, $pwPassword), "CREATE");

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

			//SUCCESSFUL MESSAGE
			echo "<script>alert('Account created successfully!')</script>";
		}
	}
}

##### CODE START HERE @LOGIN ACCOUNT (JOINER or ORGANIZER) #####
function loginAccount(){
	$emEmail = trim($_POST['emEmail']);
	$pwPassword = trim(md5($_POST['pwPassword']));

	$joinerAccount = DB::query('SELECT * FROM joiner WHERE joiner_email=? AND joiner_password=?', array($emEmail, $pwPassword), "READ");
	$organizerAccount = DB::query('SELECT * FROM organizer WHERE orga_email=? AND orga_password=?', array($emEmail, $pwPassword), "READ");

	if(count($joinerAccount)>0){
		$joinerAccount = $joinerAccount[0];
		$_SESSION['joiner'] = $joinerAccount['joiner_id'];

		header('Location: index.php');
		exit;
	}
	else if(count($organizerAccount)>0){
		$organizerAccount = $organizerAccount[0];
		$_SESSION['organizer'] = $organizerAccount['orga_id'];

		header('Location: index.php');
		exit;
	}
	else {
		echo "<script>alert('Email address or password is incorrect!')</script>";
	}
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
			$_SESSION['verified'] = $user['orga_verified'];
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
function displayAll($num, $query = NULL){
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
							<li><a href='edit_docu.php?image=".$result['docu_image']."'><i class='fas fa-edit'></i></a></li>
							<li><a href='delete.php?table=legal_document&image=".$result['docu_image']."' onclick='return confirm(\"Are you sure you want to delete this document?\");'><i class='far fa-trash-alt'></i></a></li>
						</ul>
				</div>
				";
			}
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

				echo "
				<div class='card'>
					<figure>
						<img src='images/organizers/".$_SESSION['organizer']."/$image[$displayImage]' alt='image'>
					</figure>
					<h2>".$result['adv_name']." - ".$result['adv_kind']." <span>5 <i class='fas fa-star'></i> (25 reviews) ".$remainingGuestsText."</span> </h2>
					<p>".$result['adv_address']."</p>
					<p>₱ ".number_format((float)$price, 2, '.', '')." / guest</p>
					<ul class='icons'>
						<li><a href='edit_adv.php?id=".$result['adv_id']."'><i class='fas fa-edit'></i></a></li>
						<li><a href='delete.php?table=adventure&id=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to delete this adventure?\");'><i class='far fa-trash-alt'></i></a></li>
					</ul>
				</div>
				";
			}
		}
		else {
			echo "<h3>Click + to post an adventure!!</h3>";
		}
	}
	// FOR VOUCHER ADDED DISPLAYING CARDS
	else if($num === 2){
		$card = DB::query("SELECT * FROM voucher WHERE orga_id = ?", array($_SESSION['organizer']), "READ");
		if($query != NULL) $card = $query;

		if(count($card)>0){
			foreach($card as $result){
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
					<h2>".$result['vouch_discount']."% OFF <span>₱".$result['vouch_minspent']." min. spend</span> </h2>
					<p>Valid Until: <q>".date('M. j, Y', strtotime($result['vouch_enddate']))."</q></p>

					<ul class='icons'>
						<li><a href='edit_voucher.php?id=".$result['vouch_code']."'><i class='fas fa-edit'></i></a></li>
						<li><a href='delete.php?table=voucher&id=".$result['vouch_code']."' onclick='return confirm(\"Are you sure you want to delete this voucher?\");'><i class='far fa-trash-alt'></i></a></li>
					</ul>
				</div>
				";
			}
		}
		else {
			echo "<h3>Click + to add a voucher!</h3>";
		}
	}
	// FOR VOUCHER DISPLAYING ALL CARDS FOR JOINER
	else if($num === 3){
		$card = DB::query("SELECT * FROM voucher", array(), "READ");
		if($query != NULL) $card = $query;

		if(count($card)>0){
			foreach($card as $result){
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
					<h2>".$result['vouch_discount']."% OFF <span>₱".$result['vouch_minspent']." min. spend</span> </h2>
					<p>Valid Until: <q>".date('M. j, Y', strtotime($result['vouch_enddate']))."</q></p>

					<ul class='icons'>
						<li><a onclick='copy_voucher_code(\"".$result['vouch_code']."\")'><i class='far fa-copy'></i></a></li>
					</ul>
				</div>
				";
			}
		}
		else {
			echo "<h3>No added voucher yet...</h3>";
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
					<h2>".$result['adv_name']." - ".$result['adv_kind']." <span>5 <i class='fas fa-star'></i> (25 reviews) ".$remainingGuestsText."</span> </h2>
					<p>".$result['adv_address']."</p>
					<p>₱ ".number_format((float)$price, 2, '.', '')." / guest</p>
					<ul class='icons'>";

			  if(isset($_SESSION['joiner'])){
					$favAdv = DB::query("SELECT * FROM favorite WHERE joiner_id = ? AND adv_id = ?", array($_SESSION['joiner'], $result['adv_id']), "READ");

					if(count($favAdv) > 0)
						echo "<li><a id='saved' class='added' href='favorites.php?removeFav=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to remove this adventure to your favorites?\");'><i class='fas fa-bookmark'></i></a></li>";
					else
						echo "<li><a href='favorites.php?addFav=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to add this adventure to your favorites?\");'><i class='fas fa-bookmark'></i></a></li>";

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
			echo "<h3>No adventure added to favorites...</h3>";
		}
	}
	// ELSE TO IFS AND DISPLAY ALL ADVENTURES
	else {
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
					$remainingGuestsText = "- ".$numRemainingGuests." guests remaining";

				echo "
				<a class='card-link' href='place.php?id=".$result['adv_id']."'>
				<div class='card'>
					<figure>
						<img src='images/organizers/".$result['orga_id']."/$image[$displayImage]' alt=''>
					</figure>
					<h2>".$result['adv_name']." - ".$result['adv_kind']." (".$result['adv_type'].") <span>5 <i class='fas fa-star'></i> (25 reviews) ".$remainingGuestsText."</span> </h2>
					<p>".$result['adv_address']."</p>
					<p>₱ ".number_format((float)$price, 2, '.', '')." / guest</p>
					<ul class='icons'>";

			  if(isset($_SESSION['joiner'])){
					$favAdv = DB::query("SELECT * FROM favorite WHERE joiner_id = ? AND adv_id = ?", array($_SESSION['joiner'], $result['adv_id']), "READ");

					//echo "<li><a href='book.php?id=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to book this adventure?\");'><i class='fas fa-book'></i></a></li>";

					if(count($favAdv) > 0)
						echo "<li><a id='saved' class='added' href='adventures.php?removeFav=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to remove this adventure to your favorites?\");'><i class='fas fa-bookmark'></i></a></li>";
					else
						echo "<li><a href='adventures.php?addFav=".$result['adv_id']."' onclick='return confirm(\"Are you sure you want to add this adventure to your favorites?\");'><i class='fas fa-bookmark'></i></a></li>";

				} else {
					//echo "<li><a href='login.php' onclick='return confirm(\"Are you sure you want to login to book this adventures?\");'><i class='fas fa-book'></i></a></li>";
					echo "<li><a href='login.php' onclick='return confirm(\"Are you sure you want to login to add adventures to favorites?\");'><i class='fas fa-bookmark'></i></a></li>";
				}


				echo "
					</ul>
				</div>
				</a>
				";
			}
		}
		else {
			echo "<h3>Adventure does not exist...</h3>";
		}
	}

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
		$fileItineraryImg = uploadImage('fileItineraryImg', "images/organizers/".$_SESSION['organizer']."/");
		$fileAdvImgs = uploadMultipleImages('fileAdvImgs', "images/organizers/".$_SESSION['organizer']."/");

		if($numMaxGuests == "") $numMaxGuests = 0;
		else if($fileAdvImgs === 0){
			echo "<script>alert('Must upload a maximum of four images!')</script>";
		}
		else if($fileAdvImgs === 1 || $fileItineraryImg === 1){
			echo "<script>alert('An error occurred in uploading your image!')</script>";
		}
		else if($fileAdvImgs === 2 || $fileItineraryImg === 2){
			echo "<script>alert('File type is not allowed!')</script>";
		}
		else {
			if($cboLoc == "Bantayan Island") $town = "Bantayan";
			elseif($cboLoc == "Malapascua Island") $town = "Daanbantayan";
			elseif($cboLoc == "Camotes Island") $town = "Camotes";
			else $town = $cboLoc;

			DB::query('INSERT INTO adventure(adv_images, adv_name, adv_kind, adv_type, adv_address, adv_town, adv_totalcostprice, adv_date, adv_details, adv_postedDate, adv_maxguests, adv_currentGuest, adv_itineraryImg, adv_status, orga_id) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($fileAdvImgs, $txtName, $cboKind, $cboType, $cboLoc, $town, $numPrice, $dateDate, $txtDetails, date('Y-m-d'), $numMaxGuests, 0, $fileItineraryImg, 'not full', $_SESSION['organizer']), "CREATE");

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

			//
			$fileItineraryImg = uploadImage('fileItineraryImg', "images/organizers/".$_SESSION['organizer']."/");
			$fileAdvImgs = uploadMultipleImages('fileAdvImgs', "images/organizers/".$_SESSION['organizer']."/");

			if($numMaxGuests == "") $numMaxGuests = 0;
			else if($fileAdvImgs === 0){
				echo "<script>alert('Must upload a maximum of four images!')</script>";
			}
			else if($fileAdvImgs === 1 || $fileItineraryImg === 1){
				echo "<script>alert('An error occurred in uploading your image!')</script>";
			}
			else if($fileAdvImgs === 2 || $fileItineraryImg === 2){
				echo "<script>alert('File type is not allowed!')</script>";
			}
			else {
				if($cboLoc == "Bantayan Island") $town = "Bantayan";
				elseif($cboLoc == "Malapascua Island") $town = "Daanbantayan";
				elseif($cboLoc == "Camotes Island") $town = "Camotes";
				else $town = $cboLoc;

				// UPDATE
				DB::query('UPDATE adventure SET adv_images = ?, adv_name = ?, adv_kind = ?, adv_type = ?, adv_address = ?, adv_town = ?, adv_totalcostprice = ?, adv_date = ?, adv_details = ?, adv_postedDate = ?, adv_maxguests = ?, adv_itineraryImg = ? WHERE adv_id = ?', array($fileAdvImgs, $txtName, $cboKind, $cboType, $cboLoc, $town, $numPrice, $dateDate, $txtDetails, date('Y-m-d'), $numMaxGuests, $fileItineraryImg, $_GET['id']), "UPDATE");

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
	$numDiscount = trim($_POST['numDiscount']);
	$numMinSpent = trim($_POST['numMinSpent']);
	$vouchCode = uniqid('', true);

	// ERROR TRAPPINGS
	if($dateStartDate > $dateEndDate){
		echo "<script>alert('Start date cannot be greater than end date!')</script>";
	}
	else {
		DB::query("INSERT INTO voucher(vouch_code, vouch_discount, vouch_name, vouch_minspent, vouch_startdate, vouch_enddate, orga_id, adv_id) VALUES(?,?,?,?,?,?,?,?)", array($vouchCode, $numDiscount, $txtName, $numMinSpent, $dateStartDate, $dateEndDate, $_SESSION['organizer'], $cboAdv), "CREATE");

		header("Location: voucher.php?added=1");
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

	// CURRENT LOGIN USER IS JOINER
	}
	else {
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
		foreach($_SESSION['places'] as $result){
			if($result == $place) echo "checked";
		}
	}
}

##### CODE START HERE @SPECIFIC CHECKBOX CHECK IN ACTIVITIES #####
function checkActivities($activity){
	if(isset($_POST['activities'])){
		foreach($_POST['activities'] as $result){
			if($result == $activity) echo "checked";
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

##### CODE START HERE @RATING ADVENTURE #####
function rateAdventure(){
	$star = $_POST['star'];
	$txtFeedback = trim(ucwords($_POST['txtFeedback']));

	if($txtFeedback == "")
		$txtFeedback = "No comment";

	DB::query("INSERT INTO rating(rating_stars, rating_message, joiner_id, adv_id) VALUES(?,?,?,?)", array($star, $txtFeedback, $_SESSION['joiner'], $_GET['id']), "CREATE");

	header("Location: place.php?id={$_GET['id']}&rated=1");
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
		$_SESSION['numTotal'] = $_POST['numTotal'];
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

		// BOOK AS A GUEST: 1 JOINER (KUWANG)
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

	$method_body_params = '{
	    "data": {
	        "attributes": {
	            "details": {
	                "card_number": "'.$card_num.'",
	                "exp_month": '.substr($card_expiry, 4).',
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

	curl_close($curl);

	return $response;
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

	curl_close($curl);

	return $response;
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
function send_email($to, $subject, $message) {

	require 'PHPMailerAutoload.php';

	$mail = new PHPMailer;

	//$mail->SMTPDebug = 4;                               	// Enable verbose debug output
	$mail->isSMTP();                                      	// Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               	// Enable SMTP authentication
	$mail->Username = 'teambaipajoincebu@gmail.com';  // SMTP username
	$mail->Password = 'capstone42';                          	// SMTP password
	$mail->SMTPSecure = 'tls';                             	// Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    	// TCP port to connect to
	$mail->setFrom('inflatedimpressionscebu@gmail.com', 'BAIPAJOIN');
	$mail->addAddress($to);     							// Add a recipient
	$mail->addReplyTo('inflatedimpressionscebu@gmail.com');
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Add attachment + name (optional)
	//$mail->isHTML(true);                                  // Set email format to HTML

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
	if($adv_booked['adv_maxguests'] <= $adv_booked['adv_currentGuest'])
		DB::query("UPDATE adventure SET adv_status=? WHERE adv_id=?", array("full", $adv_booked['adv_id']), "UPDATE");

	# INSERT DATA TO PAYMENT TABLE
	DB::query("INSERT INTO payment(payment_id, payment_method, payment_total, payment_datetime, book_id) VALUES(?,?,?,?,?)", array($intent_id, $method, $total, date("Y-m-d H:i:s"), $book_id), "CREATE");

	# IF PAYMENT METHOD IS THRU CARD
	if($method == "card"){
		# SUCCESSFUL MESSAGE
		echo "<i class='far fa-check-circle success'></i><p class='success'>Successfully paid thru ".$method."!</p>";

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



##### END OF CODES #####
