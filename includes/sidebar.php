<aside class="sidebar">
	<?php

		if($_SESSION['current_user'] == 'Joiner') {
			currentJoiner($_SESSION['joiner']);

			#
			echo "<h2> {$_SESSION['fname']} {$_SESSION['lname']} </h2>
			<p>User Type: Joiner</p>";
		}
		#
		if($_SESSION['current_user'] == 'Organizer') {
			currentOrganizer($_SESSION['organizer']);
			#
			echo "<h2> {$_SESSION['fname']} {$_SESSION['lname']} ";
			#IF ORGANIZER SENDS LEGAL DOCU STATUS: PENDING
			if($_SESSION['verified'] == 2) {
				echo "<i class='fas fa-undo' style='color:#33b5e5;'></i></h2>
				<p>Status: <q style='color:#33b5e5;'>Pending</q></p>
				";
			}
			#IF ORGANIZER IS VERIFIED FOR LEGAL DOCU STATUS: VERIFIED
			else if($_SESSION['verified'] == 1) {
				echo "<i class='fas fa-check-circle' style='color:#00c851;'></i></h2>
				<p>Status: <q style='color:#00c851;'>Verified</q></p>
				";
			}
			#IF ORGANIZER IS NOT VERIFIED FOR LEGAL DOCU OR HAVEN'T SUBMIT STATUS: NOT VERIFIED
			else {
				echo "<i class='fas fa-times-circle' style='color:#ff4444;'></i></h2>
				<p>Status: <q style='color:#ff4444;'>Not Verified</q></p>
				";
			}
		}

	?>
	<!-- SUB NAVIGATION: SETTINGS -->
	<ul>
		<li class="<?php if($currentSidebarPage == 'profile') echo 'current_sidebar'; ?>"><a href="settings.php"><i class="fas fa-user-circle"></i> <q>Profile</q></a></li>
		<?php
			if($_SESSION['current_user'] == 'Joiner') {
				echo "<li class='";
					if($currentSidebarPage == 'favorites') echo 'current_sidebar';
				echo "'><a href='favorites.php'><i class='fas fa-bookmark'></i> <q>Favorites</q></a></li>
				";
			}
			#
			if($_SESSION['current_user'] == 'Organizer') {
				echo "<li class='";
					if($currentSidebarPage == 'adventures') echo 'current_sidebar';
				echo "'><a href='adventures_posted.php' ><i class='fas fa-plus-circle'></i> <q>Adventures</q></a></li>";
			}
		?>
		<li class="<?php if($currentSidebarPage == 'voucher') echo 'current_sidebar'; ?>"><a href="voucher.php"><i class="fas fa-tags"></i> <q>Voucher</q></a></li>
		<li class="<?php if($currentSidebarPage == 'request') echo 'current_sidebar'; ?>"><a href="reports_request.php"><i class="fas fa-file-alt"></i> <q>Request</q></a></li>
			<ul>
				<li>Reschedule</li>
				<li>Cancelation</li>
				<li>Refund</li>
				<li>Payout</li>
			</ul>
		<li class="<?php if($currentSidebarPage == 'reports') echo 'current_sidebar'; ?>"><a href="reports_booking.php"><i class="fas fa-sticky-note"></i> <q>Reports</q></a></li>
			<?php
			if($currentSidebarPage == 'reports'){
			?>
				<ul>
					<li class="<?php if($currentSubMenu == 'reports') echo 'current_sidebar'; ?>"><a href="reports_booking.php">Bookings</a></li>
					<?php
					if($_SESSION['current_user'] == 'Joiner'){
						echo "<li><a href=''>Rebooking</a></li>";
					}
					?>
					<li><a href="reports_rating.php">Ratings</a></li>
				</ul>
			<?php
			}
			?>
		<li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><i class="fas fa-sign-out-alt"></i> <q>Logout</q></a></li>
	</ul>
</aside>
