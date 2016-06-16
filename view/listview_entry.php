<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* View modal for the list view of crm module data
* Included in the module/list.php file
* Get the filed information of module for the list view and generate the header for the datatable display
* Sets the fields information in the object member list_view_field_information and sets the object in the persistent session
* @author Abhik Chakraborty
*/

if (!isset($list_special)) {
	// if not special list
	$do_crm_list_view = new CRMListView();
	$custom_view_id = 0 ;
	if ($_SESSION["do_crm_action_permission"]->action_permitted('view',17)) {
		if (isset($_REQUEST["custom_view_id"]) && (int)$_REQUEST["custom_view_id"] > 0) {
			$custom_view_id = (int)$_REQUEST["custom_view_id"] ;
		} else {
			if (isset($_SESSION[$module]["pinned_list_view"]) && (int)$_SESSION[$module]["pinned_list_view"] > 0 ) {
				$custom_view_id = $_SESSION[$module]["pinned_list_view"] ;
			} else {
				$custom_view_id = $default_custom_view ; // check list.php for the module ex. modules/Leads/list.php
			}
		}
	}
	$_SESSION[$module]["pinned_list_view"] = $custom_view_id ;
	
	$fields_info = $do_crm_list_view->get_listview_field_info($module,$module_id,"list",$custom_view_id);
	$lp = 'n';
	$lp_object = '';
	$method = '';
	$method_param = '';
	$lp_mid = '';
} else {
	// if special list
	$lp = 'y';
	$lp_object = $list_special_object;
}

?>
<link href="/js/plugins/DataTables/datatables.min.css" rel="stylesheet">
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	//Select all records
	$('#sel_all_list_data').click(function() {
		if ($(this).is(":checked")) {
			$('.sel_record').prop("checked",true);
		} else {
			$('.sel_record').prop("checked",false);
		}
	});
	// Select all ends here

	//Setting a nosort columns
	var dontSort = [];
	$('#sqcrmlist thead th').each( function() {
		if ($(this).hasClass('no_sort')) {
			dontSort.push({"bSortable": false});
		} else {
			dontSort.push(null);
		}
	});
	// no sort columns setting ends here

    oTable = $('#sqcrmlist').dataTable({
		responsive: true,
		stateSave: true,
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
		"aoColumns":dontSort,
		"bProcessing": true,
		"bServerSide": true,
		"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span'6'p>>'",
		"sPaginationType": "full_numbers",
		"sAjaxSource": "/listdata.php?m=<?php echo $module;?>&lp=<?php echo $lp;?>&lp_mid=<?php echo $lp_mid ;?>&lp_object=<?php echo $lp_object;?>&method=<?php echo $method;?>&method_param=<?php echo $method_param;?>&custom_view_id=<?php echo $custom_view_id;?>",
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "more_data", "value": "my_value" } );
		}
	});        
});
</script>

<div class="datadisplay-outer">
<table cellpadding="0" cellspacing="0" border="0" class="datadisplay nowrap dt-responsive" id="sqcrmlist">
	<thead>
		<tr>
		<?php
			// for the check box
			if ($lp == 'y') {
				echo '<th width="2%" class="no_sort"><input type="hidden" name="sel_all_list_data" id="sel_all_list_data"></th>'; 
			} else {
				echo '<th width="2%" class="no_sort"><input type="checkbox" name="sel_all_list_data" id="sel_all_list_data"></th>'; 
			}
			foreach ($fields_info as $field=>$info) {
				echo '<th width="10%">'.$info["field_label"].'</th>';
			}
			if ($lp == 'y') {
				echo '<th width="1%" class="no_sort">&nbsp;</td>';
			} else {
				echo '<th width="10%" class="no_sort">&nbsp;</td>';
			}
		?>
		</tr>
	</thead>
</table>
</div>