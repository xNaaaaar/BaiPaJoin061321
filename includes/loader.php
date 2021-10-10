<div class="loader-wrapper">
	<figure class="loader">
		<img src="images/loader.gif" alt="">
	</figure>
</div>

<?php
	## DONE CHECKER FOR ADV
	$adv = DB::query("SELECT * FROM adventure", array(), "READ");
	if(count($adv)>0){
		foreach ($adv as $result) {
			if($result['adv_status'] == "canceled" || $result['adv_status'] == "done") continue;
			if($result['adv_date'] < date("Y-m-d"))
				DB::query("UPDATE adventure SET adv_status=? WHERE adv_id=?", array("done", $result['adv_id']), "UPDATE");
		}
	}
