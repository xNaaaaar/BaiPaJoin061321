<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

  // REDIRECT IF ADMIN NOT LOGGED IN
  if(!isset($_SESSION['admin'])) header("Location: login.php");

  // CHANGING ADMIN PASS
  if(isset($_POST['btnChange'])) changePassword();

  // CREATING ADMIN ACCOUNT
  if(isset($_POST['btnAdd'])) create_admin_account();

  // DELETING ADMIN
  if(isset($_GET['delete'])) delete_admin_account($_GET['delete']);

  // SUCCESSFUL MESSAGE FOR DELETION
  if(isset($_GET['deleted'])) echo "<script>alert('Admin deleted successfully!')</script>";

  // SUCCESSFUL MESSAGE FOR ADDED
  if(isset($_GET['added'])) echo "<script>alert('Admin account created successfully!')</script>";

  // SUCCESSFUL MESSAGE FOR UPDATED
  if(isset($_GET['updated'])) echo "<script>alert('Admin account updated successfully!')</script>";

  // ERROR MESSAGE FOR EXISTING EMAIL
  if(isset($_GET['exists'])) echo "<script>alert('Email already exists!')</script>";

  // SUCCESSFUL MESSAGE FOR CHANGING PASS
  if(isset($_GET['changed'])) echo "<script>alert('Password changed successfully!')</script>";

  // ERROR MESSAGE FOR CHANGING PASS
  if(isset($_GET['wrong'])) echo "<script>alert('Wrong password!')</script>";

  // ERROR MESSAGE FOR CHANGING PASS
  if(isset($_GET['not_match'])) echo "<script>alert('New and retype password must match!')</script>";

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

.sidebar{padding:30px;background:#7fdcd3;}
.sidebar:before{display:none;}
.sidebar figure{width:150px;margin:0 auto!important;}
.sidebar h2{text-align:center;}
.sidebar h2 span{font-size:15px;}
.sidebar ul{margin:35px 0 0 25px;height:auto;}

main{flex:4;float:none;height:100%;background:none;margin:0;padding:50px 50px 0;border-radius:0;text-align:center;}
main h1{text-align:right;font-size:20px;}
main h2{margin:15px 0;}
main h3{color:#000;}
main input{margin:5px auto;}

main .contents{display:flex;justify-content:space-between;margin:30px 0 0;}

main .admins{height:auto;width:70%;}
main .admins table tr td:nth-child(4) i{color:#5cb85c;}
main .admins table tr td:last-child i{color:red;}

main .forms{width:28%;}
main .forms form{height:auto;padding:40px 25px 25px;box-shadow:10px 10px 10px -5px #cfcfcf;border:1px solid #cfcfcf;margin:0 0 20px;}

/* Responsive Design */
@media only screen and (max-width: 1800px) {
	main{height:100%;}
}

@media only screen and (max-width: 1400px) {
	.main_con{padding:0;}
	main .contents{display:block;}
	main .admins{width:100%;}
	main .forms{width:100%;display:flex;justify-content:space-between;margin:30px 0 0;}
	main .forms form{width:48%;}

	.sidebar ul{margin:35px 0 0;padding-left:10px;}
}

@media only screen and (max-width: 1000px){
	main{padding:0 0 0 30px;}
	main .forms form{background:#fff;}
}

@media only screen and (max-width: 800px){
	main h1{font-size:20px !important;}
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
					$currentSidebarPage = 'admin';
					include("includes/sidebar-admin.php");
				?>

        <!-- MAIN -->
        <main>
          <h1><i class="fas fa-user-circle"></i> Admin: <?php echo $current_admin['admin_name']; ?></h1>
          <h2>Admins</h2>
          <div class="contents">
            <div class="admins">
              <table class="table">
                <thead class="table-dark">
                  <tr>
                    <th>ID#</th>
    								<th>Name</th>
    								<th>Email Address</th>
    								<th></th>
    								<th></th>
                  </tr>
                </thead>
                <?php
									## DISPLAYS ALL ADMIN
									display_admin();
                ?>
            </div>
            <div class="forms">
              <form method="post">
                <h3>Add Admin User</h3>
                <input type="text" name="txtName" placeholder="Name" required>
                <input type="email" name="emEmail" placeholder="Email" required>
                <input type="password" name="pwPass" placeholder="Password" minlength="8" required>
                <button class="edit" type="submit" name="btnAdd">Add</button>
              </form>
              <form method="post">
                <h3>Change Password</h3>
                <input type="password" name="pass" placeholder="Current Password" required>
    						<input type="password" name="newPass" placeholder="New Password" required>
    						<input type="password" name="retypeNewPass" placeholder="Retype New Password" required>
                <button class="edit" type="submit" name="btnChange">Change</button>
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
