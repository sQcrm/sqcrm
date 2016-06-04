<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report view
* @author Abhik Chakraborty
*/
?>
<link href="/js/plugins/DataTables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<script type="text/javascript" src="/js/plugins/DataTables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<div class="container-fluid">
	<?php
		echo $breadcrumb ;
	?>
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
				<form id="filter_run_time">
				<input type="hidden" name="runtime" value="1">
				<input type="hidden" name="path" value="<?php echo $_GET['path']?>">
				<input type="hidden" name="resource" value="<?php echo $_GET['resource']?>">
				<div class="left_250" style="margin-left:3px;">
				<?php echo _('Date Filter Type');?><br />
					<select name="report_date_filter_type_runtime" id="report_date_filter_type_runtime">
					<?php
					foreach ($date_filter_options as $key=>$val) {
						if (!in_array($key,$allowed_date_filter)) continue;
						$selected = ($date_filter_type == $key ? 'SELECTED':'');
						echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
					}
					?>
					</select>
				</div>
				<div class="clear_float"></div>
				<div class="left_100" style="margin-left:3px;">
					<input type="submit" class="btn btn-primary" id="" value="<?php echo _('generate');?>"/>
				</div>
				<br />
			</form>
			</div>
		</div>
	</div>
	<div class="clear_float"></div>
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
				<div class="left_300"  id="">
					<p><strong><?php echo $title.' :: '.$series_label['current']; ?></strong></p>
				</div>
				<table cellpadding="0" cellspacing="0" border="0" class="datadisplay" id="sqcrmlist1">
					<thead>
						<tr>
						<?php
						foreach ($fields_info as $key=>$info) {
							echo '<th width="10%">'.$info["field_label"].'</th>';
						}
						?>
						</tr>
					</thead>
					<?php
					if ($detail_data_current->getNumRows() > 0) {
						while ($detail_data_current->next()) {
							echo '<tr>' ;
							foreach ($fields_info as $fields=>$info) {
								$fieldobject = 'FieldType'.$info["field_type"];
								$val = $do_crm_fields->display_field_value($detail_data_current->$fields,$info["field_type"],$fieldobject,$detail_data_current,15,false) ;
								echo '<td class="">'.$val.'</td>';
							}
							echo '</tr>' ;
						}
					}
					?>
				</table>
			</div>
		</div>
	</div>
	
	<div class="clear_float"></div>
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
				<div class="left_300"  id="">
					<p><strong><?php echo $title.' :: '.$series_label['previous']; ?></strong></p>
				</div>
				<table cellpadding="0" cellspacing="0" border="0" class="datadisplay" id="sqcrmlist2">
					<thead>
						<tr>
						<?php
						foreach ($fields_info as $key=>$info) {
							echo '<th width="10%">'.$info["field_label"].'</th>';
						}
						?>
						</tr>
					</thead>
					<?php
					if ($detail_data_previous->getNumRows() > 0) {
						while ($detail_data_previous->next()) {
							echo '<tr>' ;
							foreach ($fields_info as $fields=>$info) {
								$fieldobject = 'FieldType'.$info["field_type"];
								$val = $do_crm_fields->display_field_value($detail_data_previous->$fields,$info["field_type"],$fieldobject,$detail_data_previous,15,false) ;
								echo '<td class="">'.$val.'</td>';
							}
							echo '</tr>' ;
						}
					}
					?>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {  
    var oTable1 = $('#sqcrmlist1').dataTable({
		"paging":   false,
        "info":     false,
        "bFilter" : false,
        "aaSorting": [],
        dom: 'T<"clear">lfrtip',
        tableTools: {
			"sSwfPath": "/js/plugins/DataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
		}
	});   
	
	 var oTable2 = $('#sqcrmlist2').dataTable({
		"paging":   false,
        "info":     false,
        "bFilter" : false,
        "aaSorting": [],
        dom: 'T<"clear">lfrtip',
        tableTools: {
			"sSwfPath": "/js/plugins/DataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
		}
	});      
});
</script>