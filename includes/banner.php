<div id="banner">
	<div class="wrapper">
		<div class="bnr_con">
			<div class="slider">
				<ul class="rslides">
					<li><figure><img src="images/slider/1.jpg" alt=""/></figure></li>
					<li><figure><img src="images/slider/2.jpg" alt=""/></figure></li>
					<li><figure><img src="images/slider/3.jpg" alt=""/></figure></li>
					<li><figure><img src="images/slider/4.jpg" alt=""/></figure></li>
					<li><figure><img src="images/slider/5.jpg" alt=""/></figure></li>
					<li><figure><img src="images/slider/6.jpg" alt=""/></figure></li>
					<li><figure><img src="images/slider/7.jpg" alt=""/></figure></li>
				</ul>
			</div>

			<div class="bnr_info">
				<h2 class="wow fadeInLeft" data-wow-duration="1s">Search an Adventures <span>in CEBU</span></h2>
				<p class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">We are using these temporary contents on the website  These dummy texts are for display purposes only.</p>
				<?php
					if(isset($_SESSION['organizer']))
						echo "<a href='adventures_posted.php' class='wow fadeInLeft' data-wow-duration='1s' data-wow-delay='.6s'>Search Now &#187;</a>";
					else
						echo "<a href='islands.php' class='wow fadeInLeft' data-wow-duration='1s' data-wow-delay='.6s'>Search Now &#187;</a>";
				?>
			</div>

		</div>
	</div>
</div>
