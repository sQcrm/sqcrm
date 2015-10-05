<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Export list data modal 
* Enables the export functionality for list data
* @author Abhik Chakraborty
*/
include_once("config.php");
$module = $_REQUEST["m"];
$custom_view_id = (int)$_REQUEST["custom_view_id"] ;
$e_export = new Event("ExportListData->eventExportListData");
$e_export->addParam("m", $module);
$e_export->addParam("mid", $module_id);
$e_export->addParam("vid", $custom_view_id);
echo '<form class="form-horizontal" id="ExportListData__eventExportListData" name="ExportListData__eventExportListData" action="/eventcontroler.php" method="post">';
echo $e_export->getFormEvent();
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
	<h3><?php echo _('Select the export option');?></h3>
</div>
<div class="modal-body">
	<div class="box_content" id="export_list_options">
		<div style="margin-left:30px;margin-top:5px;" id="">
			<input type="radio" name="export_list_opt" id="export_list_xls" value="excel" class="">
			&nbsp;&nbsp;<?php echo _('Excel');?>
		</div>
		<div style="margin-left:30px;margin-top:5px;" id="">
			<input type="radio" name="export_list_opt" id="export_list_csv" value="csv" class="">
			&nbsp;&nbsp;<?php echo _('CSV');?>
		</div>
		<!--<div style="margin-left:30px;margin-top:5px;" id="">
				<input type="radio" name="export_list_opt" id="export_list_pdf" value="pdf" class="">
				&nbsp;&nbsp;<?php echo _('PDF');?>
		</div>-->
	</div>
</div>
<div class="modal-footer">
	<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> <?php echo _('Close');?></a>
	<input type="submit" class="btn btn-primary" id="export_list" value="<?php echo _('Export')?>"/>
</div>
</form>
<script>
$(document).ready(function() {   
	$('#ExportListData__eventExportListData').submit( function() {
		var export_options = 0 ;
		var etype = ''
		
		$('#export_list_options input:radio').each( function() {
			if ($(this).is(':checked') == true) { 
				export_options++;
				etype = $(this).attr('value') ;
			}
		});
		
		if (export_options == 0) {
			display_js_error(SELECT_EXPORT_OPTION,'js_errors');
			return false ;
		}
	});
});
</script>