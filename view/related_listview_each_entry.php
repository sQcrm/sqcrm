<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* View modal for the list view of crm related data
* Included in the module/related.php file
* Get the filed information of module for the list view and generate the header for the datatable display
* Sets the fields information in the object member list_view_field_information and sets the object in the persistent session
* @author Abhik Chakraborty
*/
if (!function_exists("get_field_info_related_view")) {
	function get_field_info_related_view($related_module,$mid) {
		$do_crm_list_view = new CRMListView();
		return $do_crm_list_view->get_listview_field_info($related_module,$mid,"related");
	}
}
?>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	//Select all records
	$('#sel_all_list_data<?php echo $val["id"];?>:checkbox').change( function() {
		if ($(this).attr("checked")) {
			//$('input:checkbox').attr('checked','checked');
            $('#<?php echo $val["id"];?>').find(':checkbox').each(function() {
				$(this).attr('checked','checked');
            });
        } else {
			$('#<?php echo $val["id"];?>').find(':checkbox').each(function() {
				$(this).removeAttr('checked');
            });
        }
	});
      
	oTable = $('#<?php echo $val["id"];?>').dataTable({
		responsive: true,
		"oLanguage":{
			"sProcessing": "<img src=\"/themes/images/ajax-loader1.gif\" border=\"0\" />",
			"sLengthMenu": "<?php echo _('Show _MENU_ records per page');?>",
			"sZeroRecords": "<?php echo _('No record found');?>",
			"sInfo" : "<?php echo _('Showing _START_ to _END_ of _TOTAL_ records');?>",
			"sInfoEmpty": "<?php echo _('Showing 0 to 0 of 0 records');?>",
			"sInfoFiltered": "<?php echo _('(filtered from _MAX_ total records)');?>",
			"sSearch" : "<?php echo _('Search on all columns');?>",
			"oPaginate": {
				"sFirst": "<?php echo _('First');?>",
				"sPrevious": "<?php echo _('Previous');?>",
				"sNext": "<?php echo _('Next');?>",
				"sLast": "<?php echo _('Last');?>"
			}
		},
		"pageLength": <?php echo LIST_VIEW_PAGE_LENGTH ;?>,
		"aoColumns":get_dont_sort('<?php echo $val["id"];?>'),
		"bProcessing": false,
		"bServerSide": true,
		"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span'6'p>>'",
		"sPaginationType": "full_numbers",
		"sAjaxSource": "/listdata_related.php?m=<?php echo $key;?>&sqcrmid=<?php echo $sqcrm_record_id;?>&module=<?php echo $module;?>&related_method=<?php echo $val["method"];?>",
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "more_data", "value": "my_value" } );
		}
	});        
});
</script>
<!--<div class="datadisplay-outer">-->
<div class="clear_float"></div>
<h6><?php echo $val["heading"];?></h6>
<br />
<div class="datadisplay-outer">
<table cellpadding="0" cellspacing="0" border="0" class="datadisplay nowrap dt-responsive" id="<?php echo $val["id"] ;?>">
	<thead>
		<tr>
			<?php
            $mid = $_SESSION["do_module"]->get_idmodule_by_name($key,$_SESSION["do_module"]);    
            $fields_info = get_field_info_related_view($key,$mid);
            // for the check box
            echo '<th width="2%" class="no_sort"><input type="hidden" name="sel_all_list_data'.$val["id"].'" id="sel_all_list_data'.$val["id"].'"></th>'; 
            foreach ($fields_info as $field=>$info) {
				echo '<th width="10%">'.$info["field_label"].'</th>';
            }
            echo '<th width="10%" class="no_sort">&nbsp;</td>';
			?>
        </tr>
	</thead>
</table>
</div>
<hr class="form_hr">
<div class="clear_float"></div>