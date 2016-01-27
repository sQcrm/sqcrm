<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Custom field page
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"tax_settings")?>"><?php echo _('Tax')?></a></h3>
				<p><?php echo _('Manage different taxes.')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Product and Service Tax');?></h4></div>
				<div class="right_300" id="ps_add_block">
					<a href="#" class="btn btn-primary" id="" onclick="add_product('ps')">
					<i class="icon-white icon-plus"></i><?php echo _('Add New')?></a>
				</div>
				<div id="ps_add_block_hidden" style="display:none;">
					<table class="datadisplay">  
						<tbody>
							<tr>
								<td><?php echo _('Tax name');?> : <input type="text" id="ps_add_tax_name"></td>
								<td><?php echo _('Tax value');?> : <input type="text" id="ps_add_tax_value"></td>
								<td>
									<a href="#" onclick="cancel_save_tax('ps');" class="btn btn-inverse"><i class="icon-white icon-remove-sign"></i><?php echo _('cancel');?></a>
									<a href="#" class="btn btn-primary" onclick="save_new_tax('ps');"><?php echo _('save');?></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="clear_float"></div>
				<table class="datadisplay" id="ps_ds">  
					<thead>  
						<tr>  
							<th><?php echo _('Tax Name');?></th>  
							<th><?php echo _('Tax Value');?></th>  
							<th><?php echo _('Action')?></th>  
						</tr>  
					</thead>
					<tbody id="ps_tbody">
						<?php
						if (is_array($product_service_tax) && count($product_service_tax) > 0) {
							foreach ($product_service_tax as $key=>$val) { ?>
							<tr id="ps_<?php echo $val["idproduct_service_tax"];?>">
								<td id="ps_tax_name_<?php echo $val["idproduct_service_tax"];?>"><?php echo $val["tax_name"]?></td>
								<td id="ps_tax_value_<?php echo $val["idproduct_service_tax"];?>"><?php echo $val["tax_value"]?> %</td>
								<td id="ps_action_<?php echo $val["idproduct_service_tax"];?>">
									<a href="#" class="btn btn-primary btn-mini" 
									onclick="edit_tax_inline_form(<?php echo $val["idproduct_service_tax"];?>,'ps');">
									<i class="icon-white icon-edit"></i></a>
									<a href="#" class="btn btn-primary btn-mini bs-prompt" 
									onclick="return_delete_tax_confirm(<?php echo $val["idproduct_service_tax"];?>,'ps');">
									<i class="icon-white icon-trash"></i></a>
								</td>  
								<?php 
								}
							}
							?>
					</tbody>
				</table>
			</div>
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Shipping and Handling Tax');?></h4></div>
				<div class="right_300" id="sh_add_block">
					<a href="#" class="btn btn-primary" id="" onclick="add_product('sh')">
					<i class="icon-white icon-plus"></i><?php echo _('Add New')?></a>
				</div>
				<div id="sh_add_block_hidden" style="display:none;">
					<table class="datadisplay">  
						<tbody>
							<tr>
								<td><?php echo _('Tax name');?> : <input type="text" id="sh_add_tax_name"></td>
								<td><?php echo _('Tax value');?> : <input type="text" id="sh_add_tax_value"></td>
								<td>
									<a href="#" onclick="cancel_save_tax('sh');" class="btn btn-inverse"><i class="icon-white icon-remove-sign"></i><?php echo _('cancel');?></a>
									<a href="#" class="btn btn-primary" onclick="save_new_tax('sh');"><?php echo _('save');?></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="clear_float"></div>
				<table class="datadisplay" id="sh_ds">  
					<thead>  
						<tr>  
							<th><?php echo _('Tax Name');?></th>  
							<th><?php echo _('Tax Value');?></th>  
							<th><?php echo _('Action')?></th>  
						</tr>  
					</thead>
					<tbody id="sh_tbody">
						<?php
						if (is_array($shipping_handling_tax) && count($shipping_handling_tax) > 0) {
							foreach ($shipping_handling_tax as $key=>$val) { ?>
							<tr id="sh_<?php echo $val["idshipping_handling_tax"];?>">
								<td id="sh_tax_name_<?php echo $val["idshipping_handling_tax"];?>"><?php echo $val["tax_name"]?></td>
								<td id="sh_tax_value_<?php echo $val["idshipping_handling_tax"];?>"><?php echo $val["tax_value"]?> %</td>
								<td id="sh_action_<?php echo $val["idshipping_handling_tax"];?>">
									<a href="#" class="btn btn-primary btn-mini" 
									onclick="edit_tax_inline_form(<?php echo $val["idshipping_handling_tax"];?>,'sh');">
									<i class="icon-white icon-edit"></i></a>
									<a href="#" class="btn btn-primary btn-mini bs-prompt" 
									onclick="return_delete_tax_confirm(<?php echo $val["idshipping_handling_tax"];?>,'sh');">
									<i class="icon-white icon-trash"></i></a>
								</td>  
							</tr>
							<?php 
							}
						}
						?>
					</tbody>
				</table>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>

<div class="modal hide fade" id="delete_confirm_tax">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge badge-warning"><?php echo _('WARNING!');?></span>
	</div>
	<div class="modal-body">
		<?php echo _('Are you sure you want to delete the record.');?>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> <?php echo _('Close');?></a>
		<input type="submit" class="btn btn-primary" value="<?php echo _('Delete');?>"/>
	</div>
</div>

<script>
function edit_tax_inline_form(id,tax_type) {
	var elm = tax_type+'_'+id ;
	var tax_name_val = $('#'+tax_type+'_tax_name_'+id).html();
	var tax_value_val = $('#'+tax_type+'_tax_value_'+id).html().split('%');
	$("#"+elm).find('td').each(function() {
		var td_id = $(this).attr('id') ;
		if (td_id != tax_type+'_action_'+id) {
			var val = $(this).html();
			var val_split = val.split('%');
			$(this).html('<input type="text" id="'+td_id+'_txt" value="'+val_split[0]+'">');
		} else {
			$(this).html('<a href="#" onclick="cancel_edit_inline('+id+',\''+tax_type+'\',\''+tax_name_val+'\',\''+tax_value_val[0]+'\')" class="btn btn-inverse"><i class="icon-white icon-remove-sign"></i>'+CANCEL_LW+'</a>&nbsp;&nbsp;<a href="#" class="btn btn-primary" onclick="update_tax_data('+id+',\''+tax_type+'\')">'+SAVE_LW+'</a>');
		}
	})
}
  
function cancel_edit_inline(id,tax_type,tax_name_val,tax_value_val) {
	var elm = tax_type+'_'+id ;
	var tax_name = tax_type+'_tax_name_'+id;
	var tax_value = tax_type+'_tax_value_'+id;
	$("#"+elm).find('td').each(function() {
		var td_id = $(this).attr('id') ;
		if (td_id != tax_type+'_action_'+id) {
			var val = $("#"+td_id+"_txt").val();
			if (td_id == tax_value) {
				val = tax_value_val+' %';
			} else {
				val = tax_name_val;
			}
			$(this).html(val);
		} else {
			$(this).html('<a href="#" class="btn btn-primary btn-mini" onclick="edit_tax_inline_form('+id+',\''+tax_type+'\')"><i class="icon-white icon-edit"></i></a>&nbsp;<a href="#" class="btn btn-primary btn-mini bs-prompt" onclick="return_delete_tax_confirm('+id+',\''+tax_type+'\')"><i class="icon-white icon-trash"></i></a>');
		}
	});
}
  
function update_tax_data(id,tax_type) {
	var tax_name = tax_type+'_tax_name_'+id+'_txt';
	var tax_value = tax_type+'_tax_value_'+id+'_txt';
	if ($("#"+tax_name).val() == '') {
		display_js_error(TAX_NAME_NO_EMPTY,'js_errors');
		return false;
	}
	
	if ($("#"+tax_value).val() == '') {
		display_js_error(TAX_VALUE_NO_EMPTY,'js_errors');
		return false;
	}
	var qry_string = "&tax_type="+tax_type+"&id="+id+"&tax_name="+$("#"+tax_name).val()+"&tax_value="+$("#"+tax_value).val();
	$.ajax({
		type: "POST",
		<?php
		$e_save_tax = new Event("TaxSettings->eventEditTaxData");
		$e_save_tax->setEventControler("/ajax_evctl.php");
		$e_save_tax->setSecure(false);
		?>
		url: "<?php echo $e_save_tax->getUrl(); ?>"+qry_string,
		beforeSubmit: function() {
			$("#"+tax_type+"_action_"+id).html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
		},
		success:  function(html) {
			$("#"+tax_type+"_tax_name_"+id).html($("#"+tax_name).val());
			$("#"+tax_type+"_tax_value_"+id).html($("#"+tax_value).val()+' %');
			$("#"+tax_type+"_action_"+id).html('<a href="#" class="btn btn-primary btn-mini" onclick="edit_tax_inline_form('+id+',\''+tax_type+'\')"><i class="icon-white icon-edit"></i></a>&nbsp;<a href="#" class="btn btn-primary btn-mini bs-prompt" onclick="return_delete_tax_confirm('+id+',\''+tax_type+'\')"><i class="icon-white icon-trash"></i></a>');
		}
	});
}
  
function add_product(tax_type) {
	var add_block = tax_type+'_add_block';
	var add_block_hidden = tax_type+'_add_block_hidden';
	$('#'+add_block_hidden).show("slow");
	$('#'+add_block).hide("slow");
}
  
function cancel_save_tax(tax_type) {
	var add_block = tax_type+'_add_block';
	var add_block_hidden = tax_type+'_add_block_hidden';
	$('#'+add_block_hidden).hide("slow");
	$('#'+add_block).show("slow");
}
  
function save_new_tax(tax_type) {
	var tax_name = tax_type+'_add_tax_name';
	var tax_value = tax_type+'_add_tax_value';
	
	if ($("#"+tax_name).val() == '') {
		display_js_error(TAX_NAME_NO_EMPTY,'js_errors');
		return false;
	}
		
	if ($("#"+tax_value).val() == '') {
		display_js_error(TAX_VALUE_NO_EMPTY,'js_errors');
		return false;
	}
	var tbody_id  = tax_type+'_tbody';
	var qry_string = "&tax_type="+tax_type+"&tax_name="+$("#"+tax_name).val()+"&tax_value="+$("#"+tax_value).val();
	
	$.ajax({
		type: "POST",
		<?php
		$e_save_tax_new = new Event("TaxSettings->eventSaveTaxData");
		$e_save_tax_new->setEventControler("/ajax_evctl.php");
		$e_save_tax_new->setSecure(false);
		?>
		url: "<?php echo $e_save_tax_new->getUrl(); ?>"+qry_string,
		beforeSubmit: function() {
			$("#action_"+id).html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
		},
		success:  function(html) {
			obj = JSON.parse(html);
			var pk = obj.id;
			var tax_name_val = obj.tax_name;
			var tax_value_val = obj.tax_value;
			$("#"+tbody_id).append('<tr id="'+tax_type+'_'+pk+'"><td id="'+tax_type+'_tax_name_'+pk+'">'+tax_name_val+'</td><td id="'+tax_type+'_tax_value_'+pk+'">'+tax_value_val+' %</td><td id="'+tax_type+'_action_'+pk+'"><a href="#" class="btn btn-primary btn-mini" onclick="edit_tax_inline_form(\''+pk+'\',\''+tax_type+'\')"><i class="icon-white icon-edit"></i></a>&nbsp;<a href="#" class="btn btn-primary btn-mini bs-prompt" onclick="return_delete_tax_confirm(\''+pk+'\',\''+tax_type+'\')"><i class="icon-white icon-trash"></i></a></td></tr>');
			var add_block = tax_type+'_add_block';
			var add_block_hidden = tax_type+'_add_block_hidden';
			$('#'+add_block_hidden).hide("slow");
			$('#'+add_block).show("slow");
		}
	});
}
  
function return_delete_tax_confirm(id,tax_type) {
	$("#delete_confirm_tax").modal('show');
	$("#delete_confirm_tax .btn-primary").click(function() {
		$("#delete_confirm_tax").modal('hide');
		var qry_string = "&tax_type="+tax_type+"&id="+id;
		$.ajax({
			type: "POST",
			<?php
			$e_del_tax = new Event("TaxSettings->eventDeleteTaxData");
			$e_del_tax->setEventControler("/ajax_evctl.php");
			$e_del_tax->setSecure(false);
			?>
			url: "<?php echo $e_del_tax->getUrl(); ?>"+qry_string,
			success:  function(html) {
				var tr_id = tax_type+'_'+id ;
				$("#"+tr_id).remove();
			}
		});
	});
}
</script>