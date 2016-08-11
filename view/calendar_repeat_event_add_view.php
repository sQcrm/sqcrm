<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* repeat event add view for calendar
* @author Abhik Chakraborty
*/
?>
<div class="box_content">
	<div class="form-group">
		<input type="checkbox" id="event_repeat" name="event_repeat">
		<b><?php echo _('Repeat'); ?></b>
    </div>
    <div id="recurrent_options_section" style="display:none;">
    <br />
		<?php
		$do_recurrent_events = new RecurrentEvents();
		$recurrent_options = $do_recurrent_events->get_recurrent_options();
		echo _('Frequencey');
		echo '<br />';
		echo '<select name="recurrent_options" id="recurrent_options" class="form-control input-sm">';
		foreach ($recurrent_options as $key=>$val) {
			echo '<option value="'.$key.'">'.$val.'</option>'."\n";
		}
		echo '</select>';
		?>
		<div id="repeat_freq_section" style="display:none;">
			<?php
			echo _('Repeat every');
			echo '<br />';
			echo '<select id="repeat_freq_opts" name= "repeat_freq_opts" class="form-control input-sm">';
			for ($i=1;$i<=30;$i++) {
				echo '<option value ="'.$i.'">'.$i.'</option>'."\n";
			}
			echo '</select>';
			?>
			<span id="repeat_freq_section_text"><?php echo "&nbsp;"._('days');?></span>
		</div>
          
		<div id="weekly_opts_section" style="display:none;">
			<?php
			echo _('Repeat on');
			echo '&nbsp;';
			foreach ($days_in_week as $key=>$val) {
				echo '<input type = "checkbox" id="weekly_opts" name="weekly_opts[]" value="'.$key.'">&nbsp;'.$val."&nbsp;";
			}
			?> 
		</div>
          
		<div id="monthly_opts_section" style="display:none;">
			<?php
			echo '<input type="radio" class= "repeat_monthly_opts" name="repeat_monthly_opts" id="repeat_monthly_opts" value="1" checked>'.'&nbsp;'._('on');
			echo '&nbsp;';
			echo '<div id="repeat_monthly_opts_days_section">';
			echo '<input type="text" name="repeat_monthly_opts_days" id="repeat_monthly_opts_days" value="1" class="input-small">';
			echo '&nbsp;';
			echo _('day of the month');
			echo '</div>';
			echo '<input type="radio" class= "repeat_monthly_opts" name="repeat_monthly_opts" id="repeat_monthly_opts" value="2">'.'&nbsp;'._('or');
			echo '&nbsp;';
			echo '<div id="repeat_monthly_opts_week_section" style="display:none;">';
			echo '<select name="repeat_monthly_opts_week_freq" id="repeat_monthly_opts_week_freq" class="form-control input-sm">';
			echo '<option value="first">'._('first').'</option>';
			echo '<option value="second">'._('second').'</option>';
			echo '<option value="third">'._('third').'</option>';
			echo '<option value="fourth">'._('fourth').'</option>';
			echo '<option value="last">'._('last').'</option>';
			echo '</select>';
			echo '&nbsp;';
			echo '<select name="repeat_monthly_opts_week_weekdays" id="repeat_monthly_opts_week" class="form-control input-sm">';
			echo '<option value="sunday">'._('sun').'</option>';
			echo '<option value="monday">'._('mon').'</option>';
			echo '<option value="tuesday">'._('tue').'</option>';
			echo '<option value="wednesday">'._('wed').'</option>';
			echo '<option value="thursday">'._('thu').'</option>';
			echo '<option value="friday">'._('fri').'</option>';
			echo '<option value="saturday">'._('sat').'</option>';
			echo '</select>';
			echo '</div>';
			?>
		</div>
    
		<div id="yearly_opts_section" style="display:none;"></div>
          
		<div id="repeat_end_opts_section">
			<?php
			echo _('Ends');
			echo '<br />';
			echo '<input type="radio" class= "repeat_end_opts" name="repeat_end_opts" id="repeat_end_opts" value="1" checked>'.'&nbsp;'._('after').'&nbsp;';
			echo '<div id="repeat_end_num_occurence_section">';
			echo '<input type="text" name="repeat_end_num_occurence" id="repeat_end_num_occurence" value = "10" class="input-small">';
			echo '&nbsp;'._('occurrences');
			echo '</div>';
			echo '<input type="radio" class = "repeat_end_opts" name="repeat_end_opts" id="repeat_end_opts" value="2" >'.'&nbsp;'._('on').'&nbsp;';
			echo '<div id="repeat_end_date_section" style="display:none;">';
			FieldType9::display_field('repeat_end_date','','form-control');
			echo '<br />';
			echo '</div>';
			?>
		</div>
	</div>        
</div>

<script>
$(document).ready(function() {
	$("#event_repeat").click(function() {
		if($("#event_repeat").is(':checked')) {
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