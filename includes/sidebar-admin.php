<aside class="sidebar">
	<figure>
		<img src="images/baipajoin.ico" alt="">
	</figure>
	<h2>BaiPaJoin <span>an online joiner platform for tourists</span> </h2>
	<ul>
		<li class="<?php if($currentSidebarPage == 'dashboard') echo 'current_sidebar'; ?>"><a href="admin-dashboard.php">Dashboard</a> </li>
		<li class="<?php if($currentSidebarPage == 'admin') echo 'current_sidebar'; ?>"><a href="admin.php">Admins</a> </li>
		<li class="<?php if($currentSidebarPage == 'organizer') echo 'current_sidebar'; ?>"><a href="admin-organizer.php">Organizers</a> </li>
		<li class="<?php if($currentSidebarPage == 'joiner') echo 'current_sidebar'; ?>"><a href="admin-joiner.php">Joiners</a> </li>
		<li class="<?php if($currentSidebarPage == 'request') echo 'current_sidebar'; ?>"><a href="admin-request.php">Requests</a> </li>
			<?php
			if($currentSidebarPage == 'request'){
			?>
			<ul>
				<li class="<?php if($currentSubMenu == 'resched') echo 'current_sidebar'; ?>"><a href="admin_request-resched.php">Reschedule</a></li>
				<li class="<?php if($currentSubMenu == 'cancel') echo 'current_sidebar'; ?>"><a href="admin_request-cancel.php">Cancelation</a></li>
				<li class="<?php if($currentSubMenu == 'refund') echo 'current_sidebar'; ?>"><a href="admin_request-refund.php">Refund</a></li>
				<li class="<?php if($currentSubMenu == 'payout') echo 'current_sidebar'; ?>"><a href="admin_request-payout.php">Payout</a></li>
			</ul>
			<?php
			}
			?>
		<li class="<?php if($currentSidebarPage == 'ratings') echo 'current_sidebar'; ?>"><a href="admin-joiner.php">Ratings</a> </li>
		<li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><q>Logout</q></a></li>
	</ul>
</aside>
