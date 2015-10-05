<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* history view entry 
* @author Abhik Chakraborty
*/  
if ($data_history === false) {
	echo '<strong>'._('No History Found !').'</strong>';
?>
<?php
} elseif (is_array($data_history["data"]) && count($data_history["data"])>0) {
	$last_year = '';
	$last_month = '';
	$last_postition = '';
	$cnt_last_pos = 0 ;
	echo '<ul class="timeline">' ;
	foreach ($data_history["data"] as $year=>$months_data) {
		$last_year = $year ; 
		if ($data_history["last_details"]["last_year"] != $year) {
			echo '<li><div class="tldate">'.$year.'</div></li>';
		}
		foreach ($months_data as $month=>$history_values) {
			$mon_cnt = 0  ;
			$cnt = 0;
			$last_month = $month;
			foreach ($history_values as $k=>$data) {
				$mon_cnt++;
				if ($cnt_last_pos == 0) {
					if ($data_history["last_details"]["last_postition"] == 'r') {
						$cnt = 2;
					} elseif ($data_history["last_details"]["last_postition"] == 'l') { $cnt = 1; }
				}
				$cnt_last_pos++;
				$cnt++;
				if ($cnt != 1 && $cnt%2 == 0) {
					$last_postition = 'r';
					echo '<li class="timeline-inverted">';
				} else {
					$last_postition = 'l';
					echo '<li>';
				}
				if($mon_cnt == 1) {
					if ($data_history["last_details"]["last_month"] != $month) {
						echo '<div class="tl-circ">'.$month.'</div>';
					}
				}
				echo '<div class="timeline-panel">';
				echo '<div class="tl-heading">';
				if (strlen($data["avatar"]) > 3) {
					$avatar = $data["avatar"] ;
				} else { $avatar =  '/themes/images/blank_avatar.jpg'; }
				echo '<div style="float:left;"><div class="circular_35" style="background-image: url(\''.$avatar.'\')"></div></div>';
				echo '<div style="float:right;left:67;position:absolute;top:28;"><p><small class="text-muted"><i class="glyphicon glyphicon-time"></i>'.$data["row1"].'</small></p></div>';
				echo '<div class="clear_float"></div>';
				echo '<br />';
				echo '<div class="tl-body">
						<p>'.$data["row2"].'</p>
					</div>';
				echo '</div>';
				echo '</li>';
			}
		}
	}
	echo '</ul>';
	echo '<div id="last_details">';
	echo '<input type="hidden" name="last_year" id="last_year" value="'.$last_year.'">';
	echo '<input type="hidden" name="last_month" id="last_month" value="'.$last_month.'">';
	echo '<input type="hidden" name="last_postition" id="last_postition" value="'.$last_postition.'">';
	echo '</div>';
}
?>