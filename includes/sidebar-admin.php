<aside class="sidebar">
	<figure>
		<img src="images/baipajoin.ico" alt="">
	</figure>
	<h2>BaiPaJoin <span>an online joiner platform for tourists</span> </h2>
	<ul>
		<li class="<?php if($currentSidebarPage == 'dashboard') echo 'current_sidebar'; ?>"><a href="admin-dashboard.php">Dashboard</a> </li>
		<li class="<?php if($currentSidebarPage == 'admin') echo 'current_sidebar'; ?>"><a href="admin.php">Admin</a> </li>
		<li class="<?php if($currentSidebarPage == 'organizer') echo 'current_sidebar'; ?>"><a href="admin-organizer.php">Organizer</a> </li>
		<li class="<?php if($currentSidebarPage == 'joiner') echo 'current_sidebar'; ?>"><a href="admin-joiner.php">Joiner</a> </li>
		<li class="<?php if($currentSidebarPage == 'request') echo 'current_sidebar'; ?>"><a href="admin-joiner.php">Request</a> </li>
		<li class="<?php if($currentSidebarPage == 'ratings') echo 'current_sidebar'; ?>"><a href="admin-joiner.php">Ratings</a> </li>
		<li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><q>Logout</q></a></li>
	</ul>
</aside>
