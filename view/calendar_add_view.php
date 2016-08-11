<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Entity add view 
* @author Abhik Chakraborty
*/ 
?>
<div class="container-fluid">
	<div class="row">
		<?php
		$e_add_entity = new Event($module."->eventAddRecord");
		$e_add_entity->addParam("idmodule",$module_id);
		$e_add_entity->addParam("module",$module);
		$e_add_entity->addParam("error_page",NavigationControl::getNavigationLink($module,"add"));
		echo '<form class="" id="'.$module.'__addRecord" name="'.$module.'__addRecord" action="/eventcontroler.php" method="post" enctype="multipart/form-data">';
		echo $e_add_entity->getFormEvent();
		?>
		<div class="col-md-7">
			<div class="row">
				<div class="col-md-12"> 
					<div class="datadisplay-outer">
					<?php
					require("add_view_form_fields.php");
					?>
					</div>
				</div>
			</div><!--/row-->
		</div><!--/span-->
    
		<div class="col-md-5">
			<div class="row">
				<div class="col-md-12"> 
					<?php
					require("view/calendar_repeat_event_add_view.php");
					?>
					<div class="box_content">
						<div class="form-group">
							<input type="checkbox" id="event_alert" name="event_alert">
							<b><?php echo _('Send Alert'); ?></b>
						</div>
						<div id="event_alert_opt_section" style="display:none;">
						<?php
						echo '<br />';
						echo _('Send alert before');
						echo '<br />';
						echo '<select name="event_alert_day" id="event_alert_day" class="form-control input-sm">';
						for ($i=0;$i<=31;$i++) {
							echo '<option value ="'.$i.'">'.$i.'</option>';
						}
						echo '</select>';
						echo '&nbsp;'._('days').'&nbsp;';
						echo '<select name="event_alert_hrs" id="event_alert_hrs" class="form-control input-sm">' ;
						for ($i=0;$i<24;$i++) {
							echo '<option value ="'.$i.'">'.$i.'</option>';
						}
						echo '</select>';
						echo '&nbsp;'._('hours').'&nbsp;';
						echo '<select name="event_alert_mins" id="event_alert_mins" class="form-control input-sm">' ;
						for ($i=0;$i<=55;$i++) {
							echo '<option value ="'.$i.'">'.$i.'</option>';
						}
						echo '</select>';
						echo '&nbsp;'._('minutes').'&nbsp;';
						echo '<br />';
						echo _('Add more email ids by comma separation to receive alert.(ex: abhik@sqcrm.com,joe@sqcrm.com)');
						echo '<br />';
						FieldType20::display_field('event_alert_email_ids','','expand_text_area');
						echo '<br /><br />';
						?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	</div><!--/row-->
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
		var recurrent_options = $(this).attr('value');
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
  
	$("#event_alert").click(function() {
		if ($("#event_alert").is(':checked')) {
			$("#event_alert_opt_section").show();
		} else {
			$("#event_alert_opt_section").hide();
		}
	});
});
<?php 
	echo $do_crmfields->get_js_form_validation($module_id,$module."__addRecord","add");
?>
	$.validator.addMethod("notEqual", function(value,element,param) {
		return this.optional(element) || value != param;
	},"Please select a value "
);
</script>