<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Popup modal for the list data for different modules
* @author Abhik Chakraborty
*/
include_once("config.php");

$m = $_GET["m"];
$field = $_GET["fieldname"];
$fielddisp = $_GET["fielddisp"];
$special_field = '';
$special_field_name = '';
  
if (isset($_GET["special_field"]) && $_GET["special_field"] == 'yes') {
	$special_field = $_GET["special_field"] ;
	$special_field_name = $_GET["special_field_name"] ;
}
  
$module_id = $_SESSION["do_module"]->get_idmodule_by_name($m,$_SESSION["do_module"]);    
$do_crm_list_view = new CRMListView();
$fields_info = $do_crm_list_view->get_listview_field_info($m,$module_id,"popup");

$allow = true;
  
if ($allow === true) { 
	// FIXME ugly heck to fix the issue of not loading the datatable on popup on multiple try 
	$table_div_id = 'sqcrmpopuplist'.time();
    
?>
<!-- Modifying the existing style for the datatable to fit the popup window /-->
<style>
div.dataTables_length label {
	width :250px;
	margin-left:5px;
	text-align: left;
}

div.dataTables_length select {
	width :75px;
	margin :0;
}

div.dataTables_filter label {
	float :right;
	width :200px;  
}

div.dataTables_info {
	padding-top : 8px;
	margin-left:5px;
}

div.dataTables_paginate {
	float :right;
	margin-top : 5px;
}
.dataTables_processing {
	position: absolute;
	top: 50%;
	left: 50%;
}
</style>
<!-- Style modification ends here /-->
<link href="/js/plugins/DataTables/datatables.min.css" rel="stylesheet">
<script>
function return_popup_selected(id) {
	var return_data = $('#'+id).val() ;
	var values = return_data.split("::");
	$("#<?php echo $fielddisp.'_'.$field;?>").attr('value',values[1]);
	$("#<?php echo $field;?>").attr('value',values[0]);
}
  
function return_popup_selected_special(id) {
	var return_data = $('#'+id).val() ;
	var values = return_data.split("::");
	$("#<?php echo $fielddisp.'_'.$field;?>").attr('value',values[1]);
	$("#<?php echo $field;?>").attr('value',values[0]);
	$("#<?php echo $special_field_name;?>").attr('value',values[0]);
}
  
function return_popup_line_item(id,module,line_level) {
	var return_data = $('#'+id).val() ;
	var qry_string = "&id="+return_data;
	if (module == 'Products') {
		$.ajax({
			type: "GET",
			<?php
			$e_products = new Event("Products->eventGetIdLineItem");
			$e_products->setEventControler("/ajax_evctl.php");
			$e_products->setSecure(false);
			?>
			url: "<?php echo $e_products->getUrl(); ?>"+qry_string,
			success:  function(html) {
				obj = JSON.parse(html);
				var pk = obj.id;
				if (pk > 0 ) {
					$("#line_item_name_"+line_level).attr('value',obj.product_name);
					$("#line_item_value_"+line_level).attr('value',return_data);
					$("#line_item_description_"+line_level).html(obj.description);
					$("#line_item_price_"+line_level).attr('value',parseFloat(obj.product_price).toFixed(2));
					$("#line_item_tax_values_"+line_level).attr('value','');
					$("#"+line_level+" .line_item_quantity").focus();
					if (obj.tax_value != '') {
						$("#line_has_tax_"+line_level).attr('value','1');
						var tax_value = obj.tax_value ;
						var tax_value_split = tax_value.split(',');
						var tax_html = '<div class="modal-header">';
						tax_html += '<button type="button" class="close" data-dismiss="modal">x</button>'
						tax_html += '<span class="badge badge-info">Set Tax for - '+obj.product_name ;
						tax_html +=  '<span id="tax_line_name"></span></span></div>';
						tax_html += '<div class="modal-body"><div class="box_content">';
						tax_html += '<table>';
						tax_value_split.forEach(function(val) {
							var val_splited = val.split('::');
							tax_html +='<tr>';
							tax_html +='<td style="width:90px;">';
							tax_html +='<input type="checkbox" name="cb_line_tax_ft_'+line_level+'[]" value="'+val_splited[0]+'">';
							tax_html +='<span style="font-size: 12px;margin-left:4px;">'+val_splited[0]+' ( % )</span>';
							tax_html +='</td>';
							tax_html +='<td style="margin-left:5px;"><input type="text" value="'+val_splited[1]+'" class="input-mini" id="cb_linetax_val_'+val_splited[0]+'_'+line_level+'"></td>';
							tax_html +='</tr>';
						});
						tax_html +='</table>';
						tax_html +='</div></div>';
						tax_html += '<div class="modal-footer" id="set_tax_from_default_footer">'
						tax_html += '<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>';
						tax_html += '<input type="button" id="'+line_level+'" class="btn btn-primary set_tax_available" value="Set Tax"/>';
						tax_html += '</div>';
						tax_html += '</div>';
						$("#line_tax_"+line_level).html(tax_html);
					} else {
						$("#line_has_tax_"+line_level).attr('value','0');
					}
				}
			}
		});
	}
}

function return_popup_copy_org_addr(id,target_module) {
	var return_data = $('#'+id).val() ;
	var values = return_data.split("::");
	$("#<?php echo $fielddisp.'_'.$field;?>").attr('value',values[1]);
	$("#<?php echo $field;?>").attr('value',values[0]);
	if (confirm(COPY_ORGANIZATION_ADDRESS_CONFIRM)) {
		var qry_string = "&id="+return_data;
		$.ajax({
			type: "GET",
			<?php
			$e_org = new Event("Organization->eventGetOrganizationAddress");
			$e_org->setEventControler("/ajax_evctl.php");
			$e_org->setSecure(false);
			?>
			url: "<?php echo $e_org->getUrl(); ?>"+qry_string,
			success:  function(html) {
				obj = JSON.parse(html);
				var pk = obj.id;
				if (pk > 0) {
					var field_prefix = '';
					if (target_module == 'Quotes') {
						field_prefix = 'q_';
					} else if (target_module == 'SalesOrder') {
						field_prefix = 'so_';
					} else if (target_module == 'Invoice') {
						field_prefix = 'inv_';
					}
					
					$("#"+field_prefix+"billing_address").html(obj.org_bill_address);
					$("#"+field_prefix+"billing_po_box").attr('value',obj.org_bill_pobox);
					$("#"+field_prefix+"billing_po_code").attr('value',obj.org_bill_postalcode);
					$("#"+field_prefix+"billing_city").attr('value',obj.org_bill_city);
					$("#"+field_prefix+"billing_state").attr('value',obj.org_bill_state);
					$("#"+field_prefix+"billing_country").attr('value',obj.org_bill_country);
					$("#"+field_prefix+"shipping_address").html(obj.org_ship_address);
					$("#"+field_prefix+"shipping_po_box").attr('value',obj.org_ship_pobox);
					$("#"+field_prefix+"shipping_po_code").attr('value',obj.org_ship_postalcode);
					$("#"+field_prefix+"shipping_city").attr('value',obj.org_ship_city);
					$("#"+field_prefix+"shipping_state").attr('value',obj.org_ship_state);
					$("#"+field_prefix+"shipping_country").attr('value',obj.org_ship_country);
				}
			}
		});
	}
}

function copy_cnt_address(id,target_module) {
	var return_data = $('#'+id).val() ;
	var values = return_data.split("::");
	$("#<?php echo $fielddisp.'_'.$field;?>").attr('value',values[1]);
	$("#<?php echo $field;?>").attr('value',values[0]);
	if (confirm('Do you want to copy the contact address ?')) {
		var qry_string = "&id="+return_data;
		$.ajax({
			type: "GET",
			<?php
			$e_org = new Event("Contacts->eventGetContactsAddress");
			$e_org->setEventControler("/ajax_evctl.php");
			$e_org->setSecure(false);
			?>
			url: "<?php echo $e_org->getUrl(); ?>"+qry_string,
			success:  function(html) {
				obj = JSON.parse(html);
				var pk = obj.id;
				if (pk > 0) {
					var field_prefix = '';
					if (target_module == 'PurchaseOrder') {
						field_prefix = 'po_';
					}
					$("#"+field_prefix+"billing_address").html(obj.cnt_mail_street);
					$("#"+field_prefix+"billing_po_box").attr('value',obj.cnt_mail_pobox);
					$("#"+field_prefix+"billing_po_code").attr('value',obj.cnt_mailing_postalcode);
					$("#"+field_prefix+"billing_city").attr('value',obj.cnt_mailing_city);
					$("#"+field_prefix+"billing_state").attr('value',obj.cnt_mailing_state);
					$("#"+field_prefix+"billing_country").attr('value',obj.cnt_mailing_country);
					$("#"+field_prefix+"shipping_address").html(obj.cnt_other_street);
					$("#"+field_prefix+"shipping_po_box").attr('value',obj.cnt_other_pobox);
					$("#"+field_prefix+"shipping_po_code").attr('value',obj.cnt_other_postalcode);
					$("#"+field_prefix+"shipping_city").attr('value',obj.cnt_other_city);
					$("#"+field_prefix+"shipping_state").attr('value',obj.cnt_other_state);
					$("#"+field_prefix+"shipping_country").attr('value',obj.cnt_other_country);
				}
			}
		});
	}
}

$(document).ready(function() {
	//Setting a nosort columns      
	var dontSort = [];
	$('#<?php echo $table_div_id ;?> thead th').each( function() {
		if ($(this).hasClass('no_sort')) {
			dontSort.push({"bSortable": false});
		} else {
			dontSort.push(null);
		}
	});
	// no sort columns setting ends here
 
	oTable = $('#<?php echo $table_div_id ;?>').dataTable({
		responsive: true,
		"oLanguage":{
			"sProcessing": "<img src=\"/themes/images/ajax-loader1.gif\" border=\"0\" />",
			"sLengthMenu": "<?php echo _('Show _MENU_ records per page');?>",
			"sZeroRecords": "<?php echo _('No record found');?>",
			"sInfo" : "<?php echo _('Showing _START_ ro _END_ of _TOTAL_ records');?>",
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
			"aoColumns":dontSort,
			"bProcessing": true,
			"bServerSide": true,
			"sDom": "<'row'<'span3'l><'span3'f>r>t<'row'<'span3'i><'span'3'p>>'",
			"sPaginationType": "full_numbers",
			<?php 
			if (isset($_REQUEST["line_item"]) && $_REQUEST["line_item"] == 'yes') { ?>
				"sAjaxSource": "/listdata_popup.php?m=<?php echo $m;?>&line_level=<?php echo $_REQUEST["line_level"];?>&line_item=yes",
			<?php } elseif ($_REQUEST["copy_org_address"] == 'yes') { ?>
				"sAjaxSource": "/listdata_popup.php?m=<?php echo $m;?>&copy_org_address=yes&target_module=<?php echo $_REQUEST["target_module"];?>",
			<?php } elseif ($_REQUEST["copy_cnt_address"] == 'yes'){ ?>
				"sAjaxSource": "/listdata_popup.php?m=<?php echo $m;?>&copy_cnt_address=yes&target_module=<?php echo $_REQUEST["target_module"];?>",	
			<?php } elseif ($_REQUEST["org_dependent"] == 'yes') { ?>
				"sAjaxSource": "/listdata_popup.php?m=<?php echo $m;?>&org_dependent=yes&idorganization=<?php echo $_REQUEST["idorganization"];?>",
			<?php } else { ?>
				"sAjaxSource": "/listdata_popup.php?m=<?php echo $m;?>&special_field=<?php echo $special_field;?>&special_field_name=<?php echo $special_field_name;?>",
			<?php } ?>
				"fnServerParams": function ( aoData ) {
					aoData.push( { "name": "more_data", "value": "my_value" } );
				}
		});        
	});
</script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
	<span class=""><?php echo _('Select ').$m;?></span>
</div>
<div class="modal-body">
	<div class="datadisplay-outer">
		<table cellpadding="0" cellspacing="0" border="0" class="datadisplay nowrap dt-responsive" id="<?php echo $table_div_id ;?>">
			<thead>
				<tr>
					<?php
                      // for the check box
                      echo '<th width="2%" class="no_sort">&nbsp;</th>'; 
                      foreach ($fields_info as $field=>$info) {
                          echo '<th width="20%">'.$info["field_label"].'</th>';
                      }
                  ?>
				</tr>
			</thead>
		</table>
	</div>
</div>
<div class="modal-footer">
	<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
</div>    
</form>
<?php } else { ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
	<span class="badge badge-warning"><?php echo _('WARNING');?></span>
</div>
<div class="modal-body alert-error">
	<?php echo _('You do not have permission to perform this operation');?>
</div>
<div class="modal-footer">
	<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
</div>
<?php 
} 
?>