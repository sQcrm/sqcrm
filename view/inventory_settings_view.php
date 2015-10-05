<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Custom field page
* @author Abhik Chakraborty
*/  
?>
<script type="text/javascript" src="/js/jquery/plugins/jquery.form.js"></script>
<div id="message"></div>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"tax_settings")?>"><?php echo _('Tax')?></a></h3>
				<p><?php echo _('Manage Inventory settings. Quote ,Invoice, Sales Order, Purchase Order')?></p> 
			</div>		
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Logo for Quote/Invoice/SO/PO');?></h4></div>
				<div class="clear_float"></div>
				<div id="logo_dis">
					<img src="<?php echo $GLOBALS['FILE_UPLOAD_DISPLAY_PATH'].'/'.$inventory_logo?>" width="200" height="70">
				</div>
				<?php
				$e_logo_up = new Event("CRMGlobalSettings->eventAjaxUpdateInventoryLogo");
				echo '<form class="form-horizontal" id="CRMGlobalSettings__eventAjaxUpdateInventoryLogo" name="CRMGlobalSettings__eventAjaxUpdateInventoryLogo"  method="post" enctype="multipart/form-data">';
				echo $e_logo_up->getFormEvent();
				?>
				<div>
					<div class="left_300">
						<input type="file" class="" id="inventory_logo" name="inventory_logo"/>
					</div>
					&nbsp;&nbsp;
					<input type="submit" id="" class="btn btn-primary" value="<?php echo _('change')?>"/>
					&nbsp;&nbsp;
					<?php echo _('( best dimension is 200x70 )');?>
				</div>
				</form>
			</div>
		
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Company Address');?></h4></div>
				<div class="clear_float"></div>
				<div id="company_address_block_hidden" style="display:none;">
					<br />
					<textarea name="company_address" id="company_address" class="expand_text_area"><?php echo $company_address;?></textarea>
					<br />
					<a href="#" class="btn btn-primary save_company_address"><?php echo _('save');?></a>
					<a href="#" class="btn btn-inverse cancel_company_address">
					<i class="icon-white icon-remove-sign"></i><?php echo _('cancel');?></a>
				</div>
				<div class="clear_float"></div>
				<div id="company_address_block" style="">
					<div class="" id="company_address_val">
						<?php echo nl2br($company_address);?>
						&nbsp;&nbsp;
						<a href="#" class="btn btn-primary btn-mini company_address_edit">
						<i class="icon-white icon-edit"></i></a>
					</div>
				</div>
			</div>
			
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Quote Number Prefix');?></h4></div>
				<div id="quote_prefix_add_block_hidden" style="display:none;">
					<br />
					<input type="text" id="quote_num_prefix" value="<?php echo $inventory_prefixes["quote_num_prefix"];?>">
					<br />
					<a href="#" class="btn btn-primary" onclick="save_inventory_setting('quote_num_prefix');return false;"><?php echo _('save');?></a>
					<a href="#" onclick="cancel_inventory_setting('quote_num_prefix');return false;" class="btn btn-inverse">
					<i class="icon-white icon-remove-sign"></i><?php echo _('cancel');?></a>
				</div>
				<div class="clear_float"></div>
				<div id="quote_prefix_value_block" style="">
					<div class="left_300" id="quote_num_prefix_val">
						<?php echo $inventory_prefixes["quote_num_prefix"];?>
					</div>
					&nbsp;&nbsp;
					<a href="#" class="btn btn-primary btn-mini" onclick="edit_inventory_settings('quote_num_prefix');return false;">
					<i class="icon-white icon-edit"></i></a>
				</div>
			</div>
			
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Invoice Number Prefix');?></h4></div>
				<div id="invoice_prefix_add_block_hidden" style="display:none;">
					<br />
					<input type="text" id="invoice_num_prefix" value="<?php echo $inventory_prefixes["invoice_num_prefix"];?>">
					<br />
					<a href="#" class="btn btn-primary" onclick="save_inventory_setting('invoice_num_prefix');return false;"><?php echo _('save');?></a>
					<a href="#" onclick="cancel_inventory_setting('invoice_num_prefix');return false;" class="btn btn-inverse">
					<i class="icon-white icon-remove-sign"></i><?php echo _('cancel');?></a>
				</div>
				<div class="clear_float"></div>
				<div id="invoice_prefix_value_block" style="">
					<div class="left_300" id="invoice_num_prefix_val">
						<?php echo $inventory_prefixes["invoice_num_prefix"];?>
					</div>
					&nbsp;&nbsp;
					<a href="#" class="btn btn-primary btn-mini" onclick="edit_inventory_settings('invoice_num_prefix');return false;">
					<i class="icon-white icon-edit"></i></a>
				</div>
			</div>
			
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Sales Order Number Prefix');?></h4></div>
				<div id="so_prefix_add_block_hidden" style="display:none;">
					<br />
					<input type="text" id="so_num_prefix" value="<?php echo $inventory_prefixes["salesorder_num_prefix"];?>">
					<br />
					<a href="#" class="btn btn-primary" onclick="save_inventory_setting('so_num_prefix');return false;"><?php echo _('save');?></a>
					<a href="#" onclick="cancel_inventory_setting('so_num_prefix');return false;" class="btn btn-inverse">
					<i class="icon-white icon-remove-sign"></i><?php echo _('cancel');?></a>
				</div>
				<div class="clear_float"></div>
				<div id="so_prefix_value_block" style="">
					<div class="left_300" id="so_num_prefix_val">
						<?php echo $inventory_prefixes["salesorder_num_prefix"];?>
					</div>
					&nbsp;&nbsp;
					<a href="#" class="btn btn-primary btn-mini" onclick="edit_inventory_settings('so_num_prefix');return false;">
					<i class="icon-white icon-edit"></i></a>
				</div>
			</div>
			
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Purchase Order Number Prefix');?></h4></div>
				<div id="po_prefix_add_block_hidden" style="display:none;">
					<br />
					<input type="text" id="po_num_prefix" value="<?php echo $inventory_prefixes["purchaseorder_num_prefix"];?>">
					<br />
					<a href="#" class="btn btn-primary" onclick="save_inventory_setting('po_num_prefix');return false;"><?php echo _('save');?></a>
					<a href="#" onclick="cancel_inventory_setting('po_num_prefix');return false;" class="btn btn-inverse">
					<i class="icon-white icon-remove-sign"></i><?php echo _('cancel');?></a>
				</div>
				<div class="clear_float"></div>
				<div id="po_prefix_value_block" style="">
					<div class="left_300" id="po_num_prefix_val">
						<?php echo $inventory_prefixes["purchaseorder_num_prefix"];?>
					</div>
					&nbsp;&nbsp;
					<a href="#" class="btn btn-primary btn-mini" onclick="edit_inventory_settings('po_num_prefix');return false;">
					<i class="icon-white icon-edit"></i></a>
				</div>
			</div>
			
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Quotes Terms & Condition');?></h4></div>
				<div class="clear_float"></div>
				<div id="terms_cond_block_hidden_q" style="display:none;">
					<br />
					<textarea name="inv_terms_cond_q" id="inv_terms_cond_q" class="expand_text_area"><?php echo $inventory_terms_cond["quote_terms_condition"];?></textarea>
					<br />
					<a href="#" id="q" class="btn btn-primary save_terms_condition"><?php echo _('save');?></a>
					<a href="#" id="q" class="btn btn-inverse cancel_terms_condition">
					<i class="icon-white icon-remove-sign"></i><?php echo _('cancel');?></a>
				</div>
				<div class="clear_float"></div>
				<div id="terms_cond_block_q" style="">
					<div class="" id="terms_cond_val_q">
						<?php echo nl2br($inventory_terms_cond["quote_terms_condition"]);?>
						&nbsp;&nbsp;
						<a href="#" class="btn btn-primary btn-mini inv_terms_cond_edit" id="q">
						<i class="icon-white icon-edit"></i></a>
					</div>
				</div>
			</div>
			
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Invoice Terms & Condition');?></h4></div>
				<div class="clear_float"></div>
				<div id="terms_cond_block_hidden_i" style="display:none;">
					<br />
					<textarea name="inv_terms_cond_i" id="inv_terms_cond_i" class="expand_text_area"><?php echo $inventory_terms_cond["invoice_terms_condition"];?></textarea>
					<br />
					<a href="#" id="i" class="btn btn-primary save_terms_condition"><?php echo _('save');?></a>
					<a href="#" id="i" class="btn btn-inverse cancel_terms_condition">
					<i class="icon-white icon-remove-sign"></i><?php echo _('cancel');?></a>
				</div>
				<div class="clear_float"></div>
				<div id="terms_cond_block_i" style="">
					<div class="" id="terms_cond_val_i">
						<?php echo nl2br($inventory_terms_cond["invoice_terms_condition"]);?>
						&nbsp;&nbsp;
						<a href="#" class="btn btn-primary btn-mini inv_terms_cond_edit" id="i">
						<i class="icon-white icon-edit"></i></a>
					</div>
				</div>
			</div>
			
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Sales Order Terms & Condition');?></h4></div>
				<div class="clear_float"></div>
				<div id="terms_cond_block_hidden_s" style="display:none;">
					<br />
					<textarea name="inv_terms_cond_s" id="inv_terms_cond_s" class="expand_text_area"><?php echo $inventory_terms_cond["salesorder_terms_condition"];?></textarea>
					<br />
					<a href="#" id="s" class="btn btn-primary save_terms_condition"><?php echo _('save');?></a>
					<a href="#" id="s" class="btn btn-inverse cancel_terms_condition">
					<i class="icon-white icon-remove-sign"></i><?php echo _('cancel');?></a>
				</div>
				<div class="clear_float"></div>
				<div id="terms_cond_block_s" style="">
					<div class="" id="terms_cond_val_s">
						<?php echo nl2br($inventory_terms_cond["salesorder_terms_condition"]);?>
						&nbsp;&nbsp;
						<a href="#" class="btn btn-primary btn-mini inv_terms_cond_edit" id="s">
						<i class="icon-white icon-edit"></i></a>
					</div>
				</div>
			</div>
			
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Purchase Order Terms & Condition');?></h4></div>
				<div class="clear_float"></div>
					<div id="terms_cond_block_hidden_p" style="display:none;">
						<br />
						<textarea name="inv_terms_cond_p" id="inv_terms_cond_p" class="expand_text_area"><?php echo $inventory_terms_cond["purchaseorder_terms_condition"];?></textarea>
						<br />
						<a href="#" id="p" class="btn btn-primary save_terms_condition"><?php echo _('save');?></a>
						<a href="#" id="p" class="btn btn-inverse cancel_terms_condition">
						<i class="icon-white icon-remove-sign"></i><?php echo _('cancel');?></a>
					</div>
					<div class="clear_float"></div>
					<div id="terms_cond_block_p" style="">
						<div class="" id="terms_cond_val_p">
							<?php echo nl2br($inventory_terms_cond["purchaseorder_terms_condition"]);?>
							&nbsp;&nbsp;
							<a href="#" class="btn btn-primary btn-mini inv_terms_cond_edit" id="p">
							<i class="icon-white icon-edit"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>
<script>
var FILE_UPLOAD_DISPLAY_PATH = '<?php echo $GLOBALS['FILE_UPLOAD_DISPLAY_PATH']; ?>';
$(document).ready(function() { 
	$("#inv_terms_cond_q").expandingTextarea();
	$("#inv_terms_cond_i").expandingTextarea();
	$("#inv_terms_cond_s").expandingTextarea();
	$("#inv_terms_cond_p").expandingTextarea();
	$("#company_address").expandingTextarea();
	
	var options = {
		target: '#message', //Div tag where content info will be loaded in
		url:'/ajax_evctl.php', //The php file that handles the file that is uploaded
		beforeSubmit: function() {
		},
		success:  function(data) {
			if (data.trim() == '0') {
				var succ_element = '<div class="alert alert-error sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
				var succ_msg = succ_element+'<strong>Error uploading image, please check the image before uploading.</strong></div>';
				$("#message").html(succ_msg);
				$("#message").show();
			} else {
				var succ_element = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
				var succ_msg = succ_element+'<strong>Logo uploaded successfully</strong></div>';
				var new_image = '<img src="'+FILE_UPLOAD_DISPLAY_PATH+'/'+data+'" width="200" height="70">';
				$("#logo_dis").html(new_image);
				$("#message").html(succ_msg);
				$("#message").show();
			}
		}
	};
    
	$('#CRMGlobalSettings__eventAjaxUpdateInventoryLogo').submit(function() {
		$(this).ajaxSubmit(options);
		return false;
	});
});

$(document.body).on('click', '.company_address_edit' ,function(e) {
	e.preventDefault();
	$("#company_address_block_hidden").show('slow');
	$("#company_address_block").hide('slow');
});

$(document.body).on('click', '.cancel_company_address' ,function(e) {
	e.preventDefault();
	$("#company_address_block_hidden").hide('slow');
	$("#company_address_block").show('slow');
});

$(document.body).on('click', '.save_company_address' ,function(e) {
	e.preventDefault();
	var company_address = $("#company_address").val();
	$.ajax({
		type: "POST",
		data:"company_address="+company_address,
		<?php
		$e_save_add = new Event("CRMGlobalSettings->eventAjaxUpdateCompanyAddress");
		$e_save_add->setEventControler("/ajax_evctl.php");
		$e_save_add->setSecure(false);
		?>
		url: "<?php echo $e_save_add->getUrl(); ?>",
		success:  function(html) {
			$("#company_address_val").html(html+'&nbsp;&nbsp;<a href="#" class="btn btn-primary btn-mini company_address_edit"><i class="icon-white icon-edit"></i></a>');
			$("#company_address_block_hidden").hide('slow');
			$("#company_address_block").show('slow');
		}
	}); 
});

$(document.body).on('click', '.inv_terms_cond_edit' ,function(e) {
	e.preventDefault();
	var current_item = this.id;
	$("#terms_cond_block_hidden_"+current_item).show('slow');
	$("#terms_cond_block_"+current_item).hide('slow');
});
	
$(document.body).on('click', '.cancel_terms_condition' ,function(e) {
	e.preventDefault();
	var current_item = this.id;
	$("#terms_cond_block_hidden_"+current_item).hide('slow');
	$("#terms_cond_block_"+current_item).show('slow');
});
	
$(document.body).on('click', '.save_terms_condition' ,function(e) {
	e.preventDefault();
	var current_item = this.id;
	var term_cond = $("#inv_terms_cond_"+current_item).val();
	var qry_string = "&type="+current_item;
	$.ajax({
		type: "POST",
		data:"term_cond="+term_cond,
		<?php
		$e_save_tax = new Event("CRMGlobalSettings->eventAjaxUpdateInventoryTermsCond");
		$e_save_tax->setEventControler("/ajax_evctl.php");
		$e_save_tax->setSecure(false);
		?>
		url: "<?php echo $e_save_tax->getUrl(); ?>"+qry_string,
		success:  function(html) {
			$("#terms_cond_val_"+current_item).html(html+'&nbsp;&nbsp;'+'<a href="#" class="btn btn-primary btn-mini inv_terms_cond_edit" id="'+current_item+'"><i class="icon-white icon-edit"></i></a>');
			$("#terms_cond_block_hidden_"+current_item).hide('slow');
			$("#terms_cond_block_"+current_item).show('slow');
		}
	}); 
});
	
function edit_inventory_settings(type) {
	if (type == 'quote_num_prefix') {
		$("#quote_prefix_add_block_hidden").show('slow');
		$("#quote_prefix_value_block").hide('slow');
	}
	
	if (type == 'invoice_num_prefix') {
		$("#invoice_prefix_add_block_hidden").show('slow');
		$("#invoice_prefix_value_block").hide('slow');
	}
	
	if (type == 'po_num_prefix') {
		$("#po_prefix_add_block_hidden").show('slow');
		$("#po_prefix_value_block").hide('slow');
	}
	
	if (type == 'so_num_prefix') {
		$("#so_prefix_add_block_hidden").show('slow');
		$("#so_prefix_value_block").hide('slow');
	}
}
	
function cancel_inventory_setting(type) {	
	if (type == 'quote_num_prefix') {
		$("#quote_prefix_add_block_hidden").hide('slow');
		$("#quote_prefix_value_block").show('slow');
	}
	
	if (type == 'invoice_num_prefix') {
		$("#invoice_prefix_add_block_hidden").hide('slow');
		$("#invoice_prefix_value_block").show('slow');
	}
	
	if (type == 'po_num_prefix') {
		$("#po_prefix_add_block_hidden").hide('slow');
		$("#po_prefix_value_block").show('slow');
	}
	
	if (type == 'so_num_prefix') {
		$("#so_prefix_add_block_hidden").hide('slow');
		$("#so_prefix_value_block").show('slow');
	}
}
	
function save_inventory_setting(type) {
	var setting_val = $("#"+type).val();
	var qry_string = "&type="+type+"&value="+setting_val;
	$.ajax({
		type: "POST",
		<?php
		$e_save_tax = new Event("CRMGlobalSettings->eventAjaxUpdateInventoryPrefixes");
		$e_save_tax->setEventControler("/ajax_evctl.php");
		$e_save_tax->setSecure(false);
		?>
		url: "<?php echo $e_save_tax->getUrl(); ?>"+qry_string,
		beforeSubmit: function() {
			//$("#"+tax_type+"_action_"+id).html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
		},
		success:  function(html) {
			$("#"+type+"_val").html(setting_val);
			cancel_inventory_setting(type);
		}
	});
}
</script>