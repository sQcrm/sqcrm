<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Create report step7 view 
* @author Abhik Chakraborty
*/ 
$do_report_fields = new ReportFields();
$do_report_folder = new ReportFolder();
$report_folders = $do_report_folder->get_report_folders();

$report_name = '';
$idreport_folder = 0 ;
$description = '';

$e_set_report_data = new Event("Report->eventSaveReport");
$e_set_report_data->addParam("step","7");
	
if (isset($edit) && $edit == 1) {
	$edit_msg = _('Update Report');
	$e_set_report_data->addParam("mode","edit");
	$e_set_report_data->addParam("sqrecord",$sqcrm_record_id);
	$do_report = new Report();
	$do_report->getId($sqcrm_record_id);
	$report_name = $do_report->name;
	$idreport_folder = $do_report->idreport_folder;
	$description = $do_report->description;
} else {
	$edit_msg = _('Create Report');
	$e_set_report_data->addParam("mode","add");
}
echo '<form class="form-horizontal" id="Report__eventSaveReport" name="Report__eventSaveReport" action="/eventcontroler.php" method="post">';
echo $e_set_report_data->getFormEvent();
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo $edit_msg;?> > <?php echo _('Step 7');?></h3>
				<p><?php echo _('Add report informations')?></p> 
			</div>
			<div class="box_content">
				<label class="control-label" for="name">* <?php echo _('Report Name');?></label>
				<div class="controls">
					<input type = "text" name="name" id="name" value="<?php echo $report_name ; ?>">
				</div><br />
				<label class="control-label" for="idreport_folder"><?php echo _('Report Folder');?></label>
				<div class="controls">
					<select name="idreport_folder" id="idreport_folder">
					<?php
					foreach ($report_folders as $key=>$val) {
						$selected = '';
						if ($idreport_folder == $val["idreport_folder"]) $selected = "SELECTED";
						echo '<option value="'.$val["idreport_folder"].'" '.$selected.'>'.$val["name"].'</option>';
					}
					?>
					</select>	
				</div><br />
				<label class="control-label" for="description"><?php echo _('Report Description');?></label>
				<div class="controls">
					<textarea name="description" id="description"><?php echo $description; ?></textarea>
				</div><br />
			</div>
			<div class="form-actions">  
				<?php
				if (isset($edit) && $edit == 1) { ?>
				<a href="<?php echo NavigationControl::getNavigationLink($module,"edit?step=6&sqrecord=".$sqcrm_record_id);?>" class="btn btn-inverse">
				<?php } else { ?>
				<a href="<?php echo NavigationControl::getNavigationLink($module,"add?step=6");?>" class="btn btn-inverse">
				<?php } ?>
				<i class="icon-white icon-remove-sign"></i> <?php echo _('Back');?></a>  
				<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
			</div>
			</form>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {  
	$('#Report__eventSaveReport').submit( function() { 
		if ($("#name").val() == '') {
			display_js_error(REPORT_ADD_REPORT_NAME,'js_errors');
			$("#name").focus();
			return false ;
		}
	});
});
</script>