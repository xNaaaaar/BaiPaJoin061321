<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

  // REDIRECT IF ADMIN NOT LOGGED IN
  if(!isset($_SESSION['admin'])) header("Location: login.php");

  // CHANGING ADMIN PASS
  if(isset($_POST['btnChange'])) changePassword();

  // UPDATING ADMIN ACCOUNT
  if(isset($_POST['btnUpdate'])) update_admin_account();

  // DELETING ADMIN
  if(isset($_GET['delete'])) delete_admin_account($_GET['delete']);

?>
<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->

<style media="screen">
html, body{height:100%;}
.wrapper{max-width:100%;}
/* Main Area */
.main_con{display:flex;justify-content:space-between;height:100vh;}

.sidebar{padding:30px;}
.sidebar figure{width:150px;margin:0!important;}
.sidebar h2{position:absolute;top:68px;left:180px;font-size:35px;}
.sidebar h2 span{display:block;}
.sidebar ul{margin:35px 0 0 20px;}

main{flex:4;float:none;height:auto;background:none;margin:0;padding:50px;border-radius:0;text-align:center;}
main h1{text-align:right;}
main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin:15px 0;text-align:left;}
main h2 span{font-size:30px;}
main h2 span a:hover{color:#313131;text-decoration:none;}
main h3{font:600 30px/100% Montserrat,sans-serif;;margin-bottom:10px;text-align:center;}
main input{display:inline-block;width:99%;height:50px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 18px/20px Montserrat,sans-serif;padding:0 0 0 20px;margin:5px auto;border:1px solid #cfcfcf;}

main .contents{display:flex;justify-content:space-between;margin:30px 0 0;}

main .admins{height:auto;width:70%;}
main .admins table{width:100%;text-align:left;}
main .admins table thead{background:#7fdcd3;color:#fff;}
main .admins table thead tr:hover{background:#7fdcd3;}
main .admins table thead th{padding:15px 10px;font-weight:bold;}
main .admins table tr{border-bottom:1px solid gray;}
main .admins table tr:hover{background:#fafafa;}
main .admins table td{padding:15px 10px;}
main .admins table tr td:nth-child(4){padding:15px 0;text-align:center;}
main .admins table tr td:nth-child(5){padding:15px 0;text-align:center;}

main .adding{width:28%;}
main .adding form{height:auto;padding:40px 25px;box-shadow:10px 10px 10px -5px #cfcfcf;border:1px solid #cfcfcf;border-radius:20px;margin:0 0 20px;}
main .edit{width:150px;height:45px;font:normal 18px/45px Montserrat,sans-serif;}
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
        <aside class="sidebar">
          <figure>
            <img src="images/baipajoin.ico" alt="">
          </figure>
          <h2>BaiPaJoin <span>Admin</span> </h2>
          <ul>
            <li><a href="admin-dashboard.php">Dashboard</a> </li>
            <li class="current_sidebar"><a href="admin.php">Admin</a> </li>
            <li><a href="admin-organizer.php">Organizer</a> </li>
            <li><a href="admin-joiner.php">Joiner</a> </li>
            <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><q>Logout</q></a></li>
          </ul>
        </aside>

        <!-- MAIN -->
        <main>
          <h1><i class="fas fa-user-circle"></i> Admin: <?php echo $current_admin['admin_name']; ?></h1>
          <h2>Admins</h2>
          <div class="contents">
            <div class="admins">
              <table>
                <thead>
                  <tr>
                    <th>ID#</th>
    								<th>Name</th>
    								<th>Email Address</th>
    								<th></th>
    								<th></th>
                  </tr>
                </thead>
                <?php
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
                        echo "<td><a href='admin.php?delete=".$result['admin_id']."' onclick='return confirm(\"Are you sure you want to delete this admin?\");'><i class='far fa-trash-alt'></i></a></td>
                        </tr>";
                      }
                    }
                  }
                ?>

              </table>
            </div>
            <div class="adding">
							<?php
							if(isset($_GET['admin_id'])){
								$edit_admin = DB::query("SELECT * FROM admin WHERE admin_id=?", array($_GET['admin_id']), "READ");
								$edit_admin = $edit_admin[0];
							?>
              <form method="post">
                <h3>Update Admin User</h3>
                <input type="text" name="txtName" value="<?php echo $edit_admin['admin_name']; ?>" placeholder="Name" required>
                <input type="email" name="emEmail" value="<?php echo $edit_admin['admin_email']; ?>" placeholder="Email" required>
                <button class="edit" type="submit" name="btnUpdate">Update</button>
								<a class="edit" href="admin.php">Back</a>
              </form>
							<?php
							}
							?>
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
