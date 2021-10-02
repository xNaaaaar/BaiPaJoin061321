<div id="banner">
	<div class="wrapper">
		<div class="bnr_con">
			<!-- Images Slider -->
			<?php include("slider.php"); ?>

			<div class="bnr_info">
				<h2 class="wow fadeInLeft" data-wow-duration="1s">Search an Adventures <span>in Cebu</span></h2>
				<p class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">Rejoice and stop daydreaming! You'll be surprised to discover that you don't have to travel long distances to reap the benefits of breathtaking attractions and exciting outdoor adventures, because Cebu may just have it all.</p>
				<?php
					if(isset($_SESSION['organizer']))
						echo "<a href='adventures_posted.php' class='wow fadeInLeft' data-wow-duration='1s' data-wow-delay='.6s'>Search Now &#187;</a>";
					else if(!isset($_SESSION['organizer'])) {
						$current_date = date('Y-m-d');
						echo "<a href='islands.php' class='wow fadeInLeft' data-wow-duration='1s' data-wow-delay='.6s'>Search Now &#187;</a>";
						$adv_db = DB::query("SELECT adv_id FROM adventure WHERE adv_date > '$current_date' AND adv_status != 'full'", array(), "READ");
						$adv_list = array();
						foreach($adv_db as $item) {
							array_push($adv_list, $item[0]);
							file_put_contents('debug.log', date('h:i:sa').' => '. $item[0] . "\n" . "\n", FILE_APPEND);
						}
						$random_adv = array_rand($adv_list,1);						
						echo "<a href='place.php?id=".$adv_list[$random_adv]."' class='wow fadeInLeft' data-wow-duration='1.5s' data-wow-delay='1s'>Surprise Me &#187;</a>";
					}						
				?>

			</div>

		</div>
	</div>
</div>
