<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Create report step1 view 
* @author Abhik Chakraborty
*/ 
$report_type = 1;
if (isset($_SESSION["report_type"])) {
	$report_type = $_SESSION["report_type"] ;
} elseif (isset($edit) && $edit == 1) {
	$report_type = $do_report->report_type ;
}

$e_set_report_data = new Event("Report->eventSetReportData");
$e_set_report_data->addParam("step",1);
if (isset($edit) && $edit == 1) {
	$edit_msg = _('Update Report');
	$e_set_report_data->addParam("mode","edit");
	$e_set_report_data->addParam("sqrecord",$sqcrm_record_id);
} else {
	$edit_msg = _('Create Report');
	$e_set_report_data->addParam("mode","add");
}
echo '<form class="form-horizontal" id="Report__eventSetReportData" name="Report__eventSetReportData" action="/eventcontroler.php" method="post">';
echo $e_set_report_data->getFormEvent();
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="box_content">
				<p><strong><?php echo $edit_msg;?> > <?php echo _('Step 1');?></strong></p>
				<p class="lead"><?php echo _('Select the type of the report')?></p> 
			</div>
			<div class="box_content">
				<input type="radio" name="report_type" id="report_type" value="1" <?php echo ($report_type == 1 ? "CHECKED" : "") ;?>>&nbsp;&nbsp;<?php echo _('Tabular');?>
				<br />
				<hr class="form_hr">
				<a href="<?php echo NavigationControl::getNavigationLink($module,"index");?>" class="btn btn-default active">
				<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>  
				<input type="submit" class="btn btn-primary" value="<?php echo _('Next');?>"/>
			</div>
			</form>
		</div>
	</div>
</div>