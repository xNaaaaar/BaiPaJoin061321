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
.sidebar ul ul{margin:0 0 0 10px;}

main{flex:4;float:none;height:100%;background:none;margin:0;padding:50px 50px 0;border-radius:0;text-align:center;}
main h1{text-align:right;font-size:20px;}
main h2{font:600 45px/100% Montserrat,sans-serif;color:#313131;margin:15px 0;text-align:left;}
main h2 span{font-size:30px;}
main h2 span a:hover{color:#313131;text-decoration:none;}
main h3{font:600 30px/100% Montserrat,sans-serif;;margin-bottom:10px;text-align:center;}
main input{display:inline-block;width:99%;height:50px;border:none;box-shadow:10px 10px 10px -5px #cfcfcf;outline:none;border-radius:50px;font:normal 18px/20px Montserrat,sans-serif;padding:0 20px;margin:5px auto;border:1px solid #cfcfcf;}

main .contents{display:flex;justify-content:space-between;margin:30px 0 0;width:100%;flex-wrap:wrap;}
main section{width:31%;border:1px solid #cfcfcf;box-shadow:10px 10px 10px -5px #cfcfcf;min-height:200px;position:relative;padding:30px;margin:0 auto 30px;}
main section h3{font-size:25px;}
main section h3 span{display:block;}
main section p{margin:20px 0 0;font-size:60px;color:gray;line-height:60px;}

main .admins{height:auto;width:100%;}
main .edit{width:150px;height:45px;font:normal 18px/45px Montserrat,sans-serif;border-radius:0;vertical-align:top;margin:30px 5px;}

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
					$currentSidebarPage = 'dashboard';
					$currentSubMenu = 'orga';
					include("includes/sidebar-admin.php");
				?>

        <!-- MAIN -->
        <main>
          <h1><i class="fas fa-user-circle"></i> Admin: <?php echo $current_admin['admin_name']; ?></h1>
          <h2>Organizer</h2>
          <div class="contents">
            <section>
							<h3>Total <span>Joiners</span></h3>
							<p>
								<?php
								$joiner = DB::query("SELECT * FROM joiner", array(), "READ");
								echo count($joiner);
								?>
							</p>
            </section>
						<!--  -->
						<section>
							<h3>Total <span>Organizers</span></h3>
							<p>
								<?php
								$orga = DB::query("SELECT * FROM organizer", array(), "READ");
								echo count($orga);
								?>
							</p>
            </section>
						<!--  -->
						<section>
							<h3>Total <span>Paid</span></h3>
							<p>
								<?php
								$total = 0;
								$payment = DB::query("SELECT * FROM payment", array(), "READ");

								if(count($payment)>0){
									foreach ($payment as $result) {
										$total = $total + $result['payment_total'];
									}
								}

								echo "₱".number_format($total, 2, ".", ",");
								?>
							</p>
            </section>
						<!--  -->
						<section>
							<h3>Total <span>Bookings</span> </h3>
							<p>
								<?php
								$book = DB::query("SELECT * FROM booking", array(), "READ");
								echo count($book);
								?>
							</p>
						</section>
						<!--  -->
						<section>
							<h3>Bookings <span>Paid</span>	</h3>
							<p>
								<?php
								$book_paid = DB::query("SELECT * FROM booking WHERE book_status=?", array("paid"), "READ");
								echo count($book_paid);
								?>
							</p>
						</section>
						<!--  -->
						<section>

						</section>
						<!--  -->
						<section>

						</section>
						<!--  -->
						<section>

						</section>
						<!--  -->
						<section>

						</section>
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
