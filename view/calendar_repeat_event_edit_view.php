<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* repeat event edit view for calendar
* @author Abhik Chakraborty
*/
?>
<div class="box_content">
	<div class="form-group">
		<input type="checkbox" id="event_repeat" name="event_repeat" checked>
		<b><?php echo _('Repeat'); ?></b>
    </div>
    <div id="recurrent_options_section" style="display:block;">
    <br />
		<?php
		$do_recurrent_events = new RecurrentEvents();
		$recurrent_options = $do_recurrent_events->get_recurrent_options();
		echo _('Frequencey');
		echo '<br />';
		echo '<select name="recurrent_options" id="recurrent_options">';
		foreach ($recurrent_options as $key=>$val) {
			$selected = '';
			if ($recurrent_events_pattern["recurrent_options"] == $key) $selected = "selected";
			echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>'."\n";
		}
		echo '</select>';
		?>
		<?php
		if ($recurrent_events_pattern["recurrent_options"] == 1 || $recurrent_events_pattern["recurrent_options"] == 5
        || $recurrent_events_pattern["recurrent_options"] == 6 || $recurrent_events_pattern["recurrent_options"] == 7
		) {
		?>
		<div id="repeat_freq_section" style="display:block;">
			<?php
			echo _('Repeat every');
			echo '<br />';
			echo '<select id="repeat_freq_opts" name= "repeat_freq_opts" class="form-control input-sm">';
			for ($i=1;$i<=30;$i++) {
				$selected = '';
				if ($recurrent_events_pattern["repeat_freq_opts"] == $i) $selected = "selected";
				echo '<option value ="'.$i.'" '.$selected.'>'.$i.'</option>'."\n";
			}
			echo '</select>';
			?>
			<span id="repeat_freq_section_text">
				<?php echo "&nbsp;".$text_options[$recurrent_events_pattern["recurrent_options"]];?>
			</span>
		</div>
	<?php } ?>   
		<?php
		if ($recurrent_events_pattern["recurrent_options"] == 5) {
		?>
			<div id="weekly_opts_section" style="display:block;">
				<?php
				echo _('Repeat on');
				echo '&nbsp;';
				$weekly_opts = false ;
				if (is_array($recurrent_events_pattern["weekly_opts"]) && count($recurrent_events_pattern["weekly_opts"]) > 0) {
					$weekly_opts = true ;
				}
				foreach ($days_in_week as $key=>$val) {
					$checked = "";
					if ($weekly_opts === true && in_array($key,$recurrent_events_pattern["weekly_opts"])) {
						$checked = "CHECKED";
					}
					echo '<input type = "checkbox" id="weekly_opts" name="weekly_opts[]" value="'.$key.'" '.$checked.'>&nbsp;'.$val."&nbsp;";
				}
				?> 
			</div>
		<?php } ?>  
		<?php 
			if ($recurrent_events_pattern["recurrent_options"] == 6) {
		?>
			<div id="monthly_opts_section" style="display:blobk;">
				<?php
				$repeat_monthly_opts_1_checked = "";
				$repeat_monthly_opts_2_checked = "";
				$repeat_monthly_opts_week_section = 'display:none;';        
				if ($recurrent_events_pattern["repeat_monthly_opts"] == 1) {
					$repeat_monthly_opts_1 = "CHECKED";
				} elseif ($recurrent_events_pattern["repeat_monthly_opts"] == 2) {
					$repeat_monthly_opts_2_checked = "CHECKED";
					$repeat_monthly_opts_week_section = 'display:block;';
				}
				echo '<input type="radio" class= "repeat_monthly_opts" name="repeat_monthly_opts" id="repeat_monthly_opts" value="1" '.$repeat_monthly_opts_1_checked.'>'.'&nbsp;'._('on');
				echo '&nbsp;';
				echo '<div id="repeat_monthly_opts_days_section">';
				echo '<input type="text" name="repeat_monthly_opts_days" class="form-control input-sm" id="repeat_monthly_opts_days" value="'.$recurrent_events_pattern["repeat_monthly_opts_days"].'">';
				echo '&nbsp;';
				echo _('day of the month');
				echo '</div>';
				echo '<input type="radio" class= "repeat_monthly_opts" name="repeat_monthly_opts" id="repeat_monthly_opts" value="2" '.$repeat_monthly_opts_2_checked.'>'.'&nbsp;'._('or');
				echo '&nbsp;';
				echo '<div id="repeat_monthly_opts_week_section" style="'.$repeat_monthly_opts_week_section.'">';
				echo '<select name="repeat_monthly_opts_week_freq" id="repeat_monthly_opts_week_freq" class="form-control input-sm">';
				echo '<option value="first" '.($recurrent_events_pattern["repeat_monthly_opts_week_freq"] == "first" ? "SELECTED": "").'>'._('first').'</option>';
				echo '<option value="second" '.($recurrent_events_pattern["repeat_monthly_opts_week_freq"] == "second" ? "SELECTED": "").'>'._('second').'</option>';
				echo '<option value="third" '.($recurrent_events_pattern["repeat_monthly_opts_week_freq"] == "third" ? "SELECTED": "").'>'._('third').'</option>';
				echo '<option value="fourth" '.($recurrent_events_pattern["repeat_monthly_opts_week_freq"] == "fourth" ? "SELECTED": "").'>'._('fourth').'</option>';
				echo '<option value="last" '.($recurrent_events_pattern["repeat_monthly_opts_week_freq"] == "last" ? "SELECTED": "").'>'._('fifth').'</option>';
				echo '</select>';
				echo '&nbsp;';
				echo '<select name="repeat_monthly_opts_week_weekdays" id="repeat_monthly_opts_week" class="form-control input-sm">';
				echo '<option value="sunday" '.($recurrent_events_pattern["repeat_monthly_opts_week_weekdays"] == "sunday" ? "SELECTED": "").'>'._('sun').'</option>';
				echo '<option value="monday" '.($recurrent_events_pattern["repeat_monthly_opts_week_weekdays"] == "monday" ? "SELECTED": "").'>'._('mon').'</option>';
				echo '<option value="tuesday" '.($recurrent_events_pattern["repeat_monthly_opts_week_weekdays"] == "tuesday" ? "SELECTED": "").'>'._('tue').'</option>';
				echo '<option value="wednesday" '.($recurrent_events_pattern["repeat_monthly_opts_week_weekdays"] == "wednesday" ? "SELECTED": "").'>'._('wed').'</option>';
				echo '<option value="thursday" '.($recurrent_events_pattern["repeat_monthly_opts_week_weekdays"] == "thursday" ? "SELECTED": "").'>'._('thu').'</option>';
				echo '<option value="friday" '.($recurrent_events_pattern["repeat_monthly_opts_week_weekdays"] == "friday" ? "SELECTED": "").'>'._('fri').'</option>';
				echo '<option value="saturday" '.($recurrent_events_pattern["repeat_monthly_opts_week_weekdays"] == "saturday" ? "SELECTED": "").'>'._('sat').'</option>';
				echo '</select>';
				echo '</div>';
				?>
			</div>
		<?php } ?>  
		<div id="yearly_opts_section" style="display:none;"></div>
		<div id="repeat_end_opts_section">
			<?php
			echo _('Ends');
			echo '<br />';
			$repeat_end_opts_1_checked = "";
			$repeat_end_opts_2_checked = "";
			$repeat_end_date_section = 'display:none;';
			$repeat_end_num_occurence_section = 'display:none;';
			
			$repeat_end_num_occurence = 10 ;
			$repeat_end_date = '';
        
			if ($recurrent_events_pattern["repeat_end_opts"] == 1) {
				$repeat_end_opts_1_checked = "CHECKED";
				$repeat_end_num_occurence = $recurrent_events_pattern["repeat_end_num_occurence"] ;
				$repeat_end_num_occurence_section = 'display:block;';
			} elseif ($recurrent_events_pattern["repeat_end_opts"] == 2) {
				$repeat_end_opts_2_checked = "CHECKED";
				$repeat_end_date_section = 'display:block;';
				$repeat_end_date = $recurrent_events_pattern["repeat_end_date"] ;
			}
			echo '<input type="radio" class= "repeat_end_opts" name="repeat_end_opts" id="repeat_end_opts" value="1" '.$repeat_end_opts_1_checked.'>'.'&nbsp;'._('after').'&nbsp;';
			echo '<div id="repeat_end_num_occurence_section" style="'.$repeat_end_num_occurence_section.'">';
			echo '<input type="text" name="repeat_end_num_occurence" id="repeat_end_num_occurence" value = "'.$repeat_end_num_occurence.'" class="form-control input-sm">';
			echo '&nbsp;'._('occurrences');
			echo '</div>';
			echo '<input type="radio" class = "repeat_end_opts" name="repeat_end_opts" id="repeat_end_opts" value="2" '.$repeat_end_opts_2_checked.'>'.'&nbsp;'._('on').'&nbsp;';
			echo '<div id="repeat_end_date_section" style="'.$repeat_end_date_section.'">';
			FieldType9::display_field('repeat_end_date',$repeat_end_date,'input-medium');
			echo '</div>';
			?>
		</div>
	</div>        
</div>

<script>
$(document).ready(function() {
	$("#event_repeat").click(function() {
		if ($("#event_repeat").is(':checked')) {
			$("#recurrent_options_section").show();
			$("#repeat_freq_section").show();
			$("#repeat_freq_section").append($("#daily_opts_section").html());
		} else { 
			$("#recurrent_options_section").hide();
		}
	});
  
	$(".repeat_end_opts").click(function() { 
		var end_opt_val = $("input:radio[name=repeat_end_opts]:checked").val();
		if (end_opt_val == 1) {
			$("#repeat_end_num_occurence_section").show();
			$("#repeat_end_date_section").hide();
		} else if (end_opt_val == 2) {
			$("#repeat_end_num_occurence_section").hide();
			$("#repeat_end_date_section").show();
		}
	});
  
	$("#recurrent_options").change(function() {
		var recurrent_options = $("#recurrent_options").val() ;
		if (recurrent_options == 1) {
			$("#repeat_freq_section").show();
			var repeat_freq_section_text = '<?php echo "&nbsp;"._("days");?>' ;
			$("#repeat_freq_section_text").html(repeat_freq_section_text);
			$("#weekly_opts_section").hide();
			$("#monthly_opts_section").hide();
		} else if (recurrent_options == 2) {
			$("#repeat_freq_section").hide();
			$("#weekly_opts_section").hide();
			$("#monthly_opts_section").hide();
		} else if (recurrent_options == 3) {
			$("#repeat_freq_section").hide();
			$("#weekly_opts_section").hide();
			$("#monthly_opts_section").hide();
		} else if (recurrent_options == 4) {
			$("#repeat_freq_section").hide();
			$("#weekly_opts_section").hide();
			$("#monthly_opts_section").hide();
		} else if (recurrent_options == 5) {
			$("#repeat_freq_section").show();
			var repeat_freq_section_text = '<?php echo "&nbsp;"._("weeks");?>' ;
			$("#repeat_freq_section_text").html(repeat_freq_section_text);
			$("#weekly_opts_section").show();
			$("#monthly_opts_section").hide();
		} else if (recurrent_options == 6) {
			$("#repeat_freq_section").show();
			var repeat_freq_section_text = '<?php echo "&nbsp;"._("months");?>' ;
			$("#repeat_freq_section_text").html(repeat_freq_section_text);
			$("#weekly_opts_section").hide();
			$("#monthly_opts_section").show();
		} else if (recurrent_options == 7) {
			$("#repeat_freq_section").show();
			var repeat_freq_section_text = '<?php echo "&nbsp;"._("years");?>' ;
			$("#repeat_freq_section_text").html(repeat_freq_section_text);
			$("#weekly_opts_section").hide();
			$("#monthly_opts_section").hide();
		}
	});
  
	$(".repeat_monthly_opts").click(function() {
		var repeat_monthly_opts = $("input:radio[name=repeat_monthly_opts]:checked").val();
		if (repeat_monthly_opts == 1) {
			$("#repeat_monthly_opts_days_section").show();
			$("#repeat_monthly_opts_week_section").hide();
		} else if (repeat_monthly_opts == 2) {
			$("#repeat_monthly_opts_days_section").hide();
			$("#repeat_monthly_opts_week_section").show();
		}
	});
});
</script>