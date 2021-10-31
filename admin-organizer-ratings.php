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

main{flex:4;float:none;height:100%;background:none;margin:0;padding:50px 50px 0;border-radius:0;text-align:center;}
main h1{text-align:right;font-size:20px;}
main h2{margin:15px 0;}
main h2 button{font:normal 18px/18px Montserrat,sans-serif;margin-left:20px;border:1px solid #cfcfcf;padding:7px 20px;}
main h2 a{display:inline-block;font:normal 18px/18px Montserrat,sans-serif;border:1px solid #cfcfcf;padding:7px 20px;color:#000;background:#efefef;}
main h2 button:hover, main h2 a:hover{background:#000;color:#fff;}
main a:hover{color:#fff !important;text-decoration:none;}
main h3{font:600 20px/100% Montserrat,sans-serif;;text-align:left;color:red;}

main .contents{display:flex;justify-content:space-between;margin:30px 0 0;}

main .admins{height:auto;width:100%;}

/* Responsive Design */
@media only screen and (max-width: 1800px) {
	main{height:100%;}
}

@media only screen and (max-width: 1400px) {
	.main_con{padding:0;}
	main .contents{display:block;}
	main .admins{width:100%;}

	.sidebar ul{margin:35px 0 0;padding-left:10px;}
}

@media only screen and (max-width: 1000px){
	main{padding:0 0 0 30px;}
}

@media only screen and (max-width: 800px){
	main h1{font-size:20px !important;}
	main h2 span{display:block;}
	main h2 button{margin:0;}
}

@media only screen and (max-width: 500px){
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
					$currentSidebarPage = 'organizer';
					include("includes/sidebar-admin.php");
				?>

        <!-- MAIN -->
        <main>
          <h1><i class="fas fa-user-circle"></i> Admin: <?php echo $current_admin['admin_name']; ?></h1>
					<?php
					$orga = DB::query("SELECT * FROM organizer WHERE orga_id=?", array($_GET['orga_id']), "READ");
					$orga = $orga[0];
					?>
					<form method="post">
	          <h2><?php echo $orga['orga_fname']."'s"; ?> Ratings
							<span>
							<?php
							## CHECK IF ORGA IS BANNED
							if($orga['orga_status'] == 3)
								echo "<button type='submit' name='btnUnban' onclick='return confirm(\"Are you sure you want to unban this organizer?\")'>Unban</button>";
							else
								echo "<button type='submit' name='btnBan' onclick='return confirm(\"Are you sure you want to ban this organizer?\")'>Ban</button>";
							?>
							<a href="admin-organizer.php">Back</a>
							</span>
						</h2>
						<?php
						## IF BANNED
						if($orga['orga_susp_datetime'] != "")
							echo "<h3>Banned on ".date("M. j, Y g:i a", strtotime($orga['orga_susp_datetime']))."</h3>";
						?>
					</form>
					<?php
					## WHEN BAN IS CLICKED
					if(isset($_POST['btnBan'])){
						DB::query("UPDATE organizer SET orga_status=?, orga_susp_datetime=? WHERE orga_id=?", array(3, date("Y-m-d g:i:a"), $_GET['orga_id']), "UPDATE");
					}

					## WHEN UNBAN IS CLICKED
					if(isset($_POST['btnUnban'])){
						DB::query("UPDATE organizer SET orga_status=?, orga_susp_datetime=? WHERE orga_id=?", array(1, NULL, $_GET['orga_id']), "UPDATE");
					}
					?>
          <div class="contents">
            <div class="admins">
              <table class="table-responsive table">
                <thead class="table-dark">
                  <tr>
                    <th>ADV ID#</th>
                    <th>JOINER ID#</th>
    								<th>RATING TYPE</th>
    								<th>STARS</th>
    								<th>FEEDBACK</th>
                  </tr>
                </thead>
								<tbody>
                <?php
									// ALL ORGANIZERS RESULTS
                  $all_ratings = DB::query("SELECT * FROM rating r JOIN booking b ON r.book_id=b.book_id JOIN adventure a ON b.adv_id=a.adv_id WHERE orga_id=?", array($_GET['orga_id']), "READ");

                  if(count($all_ratings)>0){
                    foreach ($all_ratings as $result) {
											echo "
											<tr>
												<td>".$result['adv_id']."</td>
												<td>".$result['joiner_id']."</td>";
											## RATING TYPE IN TEXT
											if($result['rating_type'] == 1)
												echo "<td>Engagement Rating</td>";
											elseif($result['rating_type'] == 2)
												echo "<td>Safety & Expertise Rating</td>";
											elseif($result['rating_type'] == 3)
												echo "<td>Expectation Rating</td>";
											else
												echo "<td>Overall Rating</td>";
											##
											echo "<td>".$result['rating_stars']." <i class='fas fa-star'></i></td>";
											## RATING MESSAGE
											if($result['rating_message'] == '')
												echo "<td>---</td>";
											else
												echo "<td>".$result['rating_message']."</td>";
											##
											echo "</tr>";
										}

									## IF NO EXISTING ORGANIZER
                  } else {
										echo "	</tbody>";
										echo "</table>";
										echo "<p>No ratings exists!</p>";
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
