<aside class="sidebar">
	<figure>
		<img src="images/baipajoin.ico" alt="">
	</figure>
	<h2>BaiPaJoin <span>an online joiner platform for tourists</span> </h2>
	<ul>
		<li class="<?php if($currentSidebarPage == 'dashboard') echo 'current_sidebar'; ?>"><a href="adminDashboard.php">Dashboard</a></li>
		
		<!-- TEMPORARY DISABLED 

			<?php
			## IF CURRENT MENU IS EQUAL TO DASHBOARD
			if($currentSidebarPage == 'dashboard'){
			?>
			<ul>
				<li class="<?php if($currentSubMenu == 'sales') echo 'current_sidebar'; ?>"><a href="admin-dashboard.php">Sales</a></li>
				<li class="<?php if($currentSubMenu == 'prod') echo 'current_sidebar'; ?>"><a href="admin-dashboard-prod.php">Products</a></li>
				<li class="<?php if($currentSubMenu == 'orga') echo 'current_sidebar'; ?>"><a href="admin-dashboard-orga.php">Organizer</a></li>
				<li class="<?php if($currentSubMenu == 'joiner') echo 'current_sidebar'; ?>"><a href="admin-dashboard-joiner.php">Joiner</a></li>
			</ul>
			<?php
			}
			?>
		-->	

		<li class="<?php if($currentSidebarPage == 'admin') echo 'current_sidebar'; ?>"><a href="admin.php">Admins</a></li>
		<li class="<?php if($currentSidebarPage == 'joiner') echo 'current_sidebar'; ?>"><a href="admin-joiner.php">Joiners</a></li>
		<li class="<?php if($currentSidebarPage == 'organizer') echo 'current_sidebar'; ?>"><a href="admin-organizer.php">Organizers</a></li>
		<li class="<?php if($currentSidebarPage == 'voucher') echo 'current_sidebar'; ?>"><a href="admin-voucher.php">Voucher</a></li>
		<li class="<?php if($currentSidebarPage == 'request') echo 'current_sidebar'; ?>"><a href="admin-request.php">Requests</a></li>
			<?php
			if($currentSidebarPage == 'request'){
			?>
			<ul>
				<li class="<?php if($currentSubMenu == 'resched') echo 'current_sidebar'; ?>"><a href="admin-request-resched.php">Reschedule</a></li>
				<li class="<?php if($currentSubMenu == 'cancel') echo 'current_sidebar'; ?>"><a href="admin-request-cancel.php">Cancelation</a></li>
				<li class="<?php if($currentSubMenu == 'refund') echo 'current_sidebar'; ?>"><a href="admin-request-refund.php">Refund</a></li>
				<li class="<?php if($currentSubMenu == 'payout') echo 'current_sidebar'; ?>"><a href="admin-request-payout.php">Payout</a></li>
			</ul>
			<?php
			}
			?>
		<li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><q>Logout</q></a></li>
	</ul>
</aside>
