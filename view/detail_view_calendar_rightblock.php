<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Detail view right side for calendar 
* @author Abhik Chakraborty
*/  
?>
<div class="box_content">
	<?php
    echo '<b>'._('Repeat').'</b><br />';
    if (false === $recurrent_events_pattern) {
		echo _('Repeat not set');
	} else {
		if (is_array($recurrent_events_pattern) && count($recurrent_events_pattern) > 0) {
			$recurrent_event_option = $recurrent_events->get_recurrent_options();
			$recurrent_opt = $recurrent_events_pattern["recurrent_options"] ;
			$recurrent_opt_txt = $recurrent_event_option[$recurrent_opt] ;
			echo $recurrent_opt_txt ;
			echo '<br />';
			if ($recurrent_events_pattern["repeat_freq_opts"] != '') { 
				echo _('Repeat every');
				echo '&nbsp;'.$recurrent_events_pattern["repeat_freq_opts"].'&nbsp;'.strtolower($text_options[$recurrent_opt]);
				echo '<br />';
			}
			if ($recurrent_opt == 5) {
				if (is_array($recurrent_events_pattern["weekly_opts"]) && count($recurrent_events_pattern["weekly_opts"]) > 0) {
					foreach ($recurrent_events_pattern["weekly_opts"] as $opts) {
						echo $days_in_week[$opts];
						echo '&nbsp';
					}
				}
				echo '<br />';
			} elseif ($recurrent_opt == 6) {
				if ($recurrent_events_pattern["repeat_monthly_opts"] == 1) {
					echo $recurrent_events_pattern["repeat_monthly_opts_days"];
					echo '&nbsp';
					echo _('day of the month');
					echo '<br />';
				} elseif ($recurrent_events_pattern["repeat_monthly_opts"] == 2) {
					echo $recurrent_events_pattern["repeat_monthly_opts_week_freq"].' '.$recurrent_events_pattern["repeat_monthly_opts_week_weekdays"];
					echo '<br />';
				}
			}
			if ($recurrent_events_pattern["repeat_end_opts"] == 1) {
				echo _('Ends after');
				echo '&nbsp;';
				echo $recurrent_events_pattern["repeat_end_num_occurence"];
				echo '&nbsp;';
				echo _('occurrences');
			} elseif ($recurrent_events_pattern["repeat_end_opts"] == 2) {
				echo _('Ends on');
				echo '&nbsp;';
				echo FieldType9::display_value($recurrent_events_pattern["repeat_end_date"]);
			}
		}
	}
	?>
</div>
<div class="box_content">
	<?php
    echo '<b>'._('Alert').'</b><br />';
    if (false === $reminder) {
		echo _('Event alert is not set');
    } else {
		if (is_array($reminder) && count($reminder) > 0) {
			echo _('Send alert ');
			echo '<br />';
			echo $reminder["days"].'&nbsp;'._('day(s)');
			echo '&nbsp;';
			echo $reminder["hours"].'&nbsp;'._('hour(s)');
			echo '&nbsp;';
			echo $reminder["minutes"].'&nbsp;'._('minute(s)');
			echo '&nbsp;';
			echo _('before the event start');
			if (strlen($reminder["email_ids"]) > 3) {
				echo '<br />';
				echo _('Additional email ids for the reminder');
				echo '<br />';
				echo $reminder["email_ids"];
			}        
		}
    }
	?>
</div>