<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* calendar edit view 
* @author Abhik Chakraborty
*/ 
?>
<div class="container-fluid">
	<div class="row-fluid">
    <?php
	$e_edit_entity = new Event($module."->eventEditRecord");
	$e_edit_entity->addParam("idmodule",$module_id);
	$e_edit_entity->addParam("module",$module);
	$e_edit_entity->addParam("sqrecord",$sqcrm_record_id);
	if (isset($_REQUEST["return_page"]) && strlen($_REQUEST["return_page"]) > 2) {
		$e_edit_entity->addParam("return_page",$_REQUEST["return_page"]);
	}
	$e_edit_entity->addParam("error_page",NavigationControl::getNavigationLink($module,"edit",$sqcrm_record_id));
	echo '<form class="" id="'.$module.'__editRecord" name="'.$module.'__editRecord" action="/eventcontroler.php" method="post" enctype="multipart/form-data">';
	echo $e_edit_entity->getFormEvent();
    ?>
		<div class="span7" style="margin-left:3px;">
			<div class="row-fluid">
				<div class="datadisplay-outer">
					<?php 
					require("edit_view_form_fields.php");
					?> 
				</div>
			</div><!--/row-->
		</div><!--/span-->
 
		<div class="span5" style="margin-left:10px;">
			<?php
			if (is_array($recurrent_events_pattern) && count($recurrent_events_pattern) > 0) {
				require("view/calendar_repeat_event_edit_view.php");
			} else {
				require("view/calendar_repeat_event_add_view.php");
			}
			?>
			<div class="box_content">
				<?php
				$reminder_set = false ;
				$reminder_style = "display:none;";
				if (false !== $reminder && is_array($reminder) && count($reminder) > 0) {
					$reminder_set = true;
					$reminder_style = "display:block;";
				}
				?>
				<label class="checkbox">
					<input type="checkbox" id="event_alert" name="event_alert" <?php echo ($reminder_set === true ? "CHECKED":"")?> >
					<b><?php echo _('Send Alert'); ?></b>
				</label>
				<div id="event_alert_opt_section" style="<?php echo $reminder_style ; ?>">
					<?php
					echo '<br />';
					echo _('Send alert before');
					echo '<br />';
					echo '<select name="event_alert_day" id="event_alert_day" class="input-small">';
					for ($i=0;$i<=31;$i++) {
						$select = '';
						if ($reminder_set === true && $reminder["days"] == $i) $select = "SELECTED";
						echo '<option value ="'.$i.'" '.$select.'>'.$i.'</option>';
					}
					echo '</select>';
					echo '&nbsp;'._('days').'&nbsp;';
					echo '<select name="event_alert_hrs" id="event_alert_hrs" class="input-small">' ;
					for ($i=0;$i<24;$i++) {
						$select = '';
						if ($reminder_set === true && $reminder["hours"] == $i) $select = "SELECTED";
						echo '<option value ="'.$i.'" '.$select.'>'.$i.'</option>';
					}
					echo '</select>';
					echo '&nbsp;'._('hours').'&nbsp;';
					echo '<select name="event_alert_mins" id="event_alert_mins" class="input-small">' ;
					for ($i=0;$i<=55;$i++) {
						$select = '';
						if ($reminder_set === true && $reminder["minutes"] == $i) $select = "SELECTED";
						echo '<option value ="'.$i.'" '.$select.'>'.$i.'</option>';
					}
					echo '</select>';
					echo '&nbsp;'._('minutes').'&nbsp;';
					echo '<br />';
					echo _('Add more email ids by comma separation to receive alert.(ex: abhik@sqcrm.com,joe@sqcrm.com)');
					echo '<br />';
					$email_ids = '';
					if ($reminder_set === true) $email_ids = $reminder["email_ids"];
					FieldType20::display_field('event_alert_email_ids',$email_ids,'expand_text_area');
					echo '<br />';
					?>
				</div>
			</div>
		</div>
		</form>
	</div><!--/row-->
</div>

<script>
$(document).ready(function() {  
	$("#event_alert").click(function() {
		if ($("#event_alert").is(':checked')) {
			$("#event_alert_opt_section").show();
		} else {
			$("#event_alert_opt_section").hide();
		}	
	});
});
<?php 
	echo $do_crmfields->get_js_form_validation($module_id,$module."__editRecord","edit");
?>
	$.validator.addMethod("notEqual", function(value,element,param) {
		return this.optional(element) || value != param;
	},"Please select a value "
);
</script>