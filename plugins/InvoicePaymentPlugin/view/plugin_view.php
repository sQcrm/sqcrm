<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/  
?>
<div id="existing_payments">
	<div class="alert alert-info">
	<?php
		echo _('Payment details');
		echo '&nbsp;&nbsp;&nbsp;';
		echo _('Due Amount : ').'<span id="due_amount">'.$due_amount.'<span>';
	?>
	</div>
	<table class="datadisplay">  
		<thead>  
			<tr>  
				<th width="10%"><?php echo _('Date');?></th>  
				<th width="18%"><?php echo _('Amount');?></th>  
				<th width="17%"><?php echo _('Ref No.')?></th> 
				<th width="10%"><?php echo _('Mode')?></th>
				<th width="15%"><?php echo _('Transaction Type')?></th> 
				<th width="30%"><?php echo _('Note')?></th>
			</tr> 
		</thead>
		<tbody id="payment_details_tbody">
		<?php
		if (count($payments) > 0) {
			foreach ($payments as $key=>$val) {
				echo '<tr>';
				echo '<td>'.FieldType9::display_value($val['date_added']).'</td>';
				echo '<td>'.FieldType30::display_value($val['amount']).'</td>';
				echo '<td>'.FieldType1::display_value($val['ref_num']).'</td>';
				echo '<td>'.FieldType1::display_value($val['mode_name']).'</td>';
				echo '<td>'.FieldType1::display_value($val['transaction_type']).'</td>';
				echo '<td>'.nl2br($val['additional_note']).'</td>';
				echo '</tr>';
			}
		}
		?>
		</tbody>
	</table>
</div>
<hr class="form_hr">
<div id="add_payment">
	<div class="alert alert-info">
	<?php
		echo _('Add Payment');
	?>
	</div>
	<div id="message"></div>
	<?php
	$e_add = new Event("InvoicePaymentPlugin->eventAjaxAddInvoicePayment");
	echo '<form class="form-horizontal" id="InvoicePaymentPlugin__eventAjaxAddInvoicePayment" name="InvoicePaymentPlugin__eventAjaxAddInvoicePayment"  method="post">';
	$e_add->addParam("idinvoice",$sqcrm_record_id);
	echo $e_add->getFormEvent();
	?>
	
	<label class="control-label" for="payment_date"><?php echo _('Payment Date');?></label>
	<div class="controls">
	<?php 
	echo FieldType9::display_field('payment_date');
	?>
	</div>
	<br />
	
	<label class="control-label" for="payment_mode"><?php echo _('Payment Mode');?></label>
	<div class="controls">
	<select name="payment_mode" id="payment_mode">
	<?php
	foreach($payment_modes as $key=>$val) {
		echo '<option value="'.$val['id'].'">'.$val['mode_name'].'</option>';
	}
	?>
	</select>
	</div>
	<br />
	
	<label class="control-label" for="ref_num"><?php echo _('Amount');?></label>
	<div class="controls">
	<?php
	echo FieldType30::display_field('amount');
	?>
	</div>
	<br />
	
	<label class="control-label" for="ref_num"><?php echo _('Ref Number');?></label>
	<div class="controls">
	<?php
	echo FieldType1::display_field('ref_num');
	?>
	</div>
	<br />
	
	<label class="control-label" for="additional_note"><?php echo _('Additional Note');?></label>
	<div class="controls">
	<?php
	$default_note = _('Payment details added by ');
	$default_note .= $_SESSION['do_user']->user_name.' ('.$_SESSION['do_user']->firstname.' '.$_SESSION['do_user']->lastname.')';
	echo FieldType20::display_field('additional_note',$default_note,'expand_text_area');
	?>
	</div>
	<br />
	
	<hr class="form_hr">
	<div id="add_payment_submit">
		<input type="submit" class="btn btn-primary" id="" value="<?php echo _('Save');?>"/>
	</div>
	</form>
</div>
<script type="text/javascript" src="/js/jquery/plugins/jquery.form.js"></script>
<script type="text/javascript" src="/plugins/InvoicePaymentPlugin/asset/i18n_message.js"></script>
<script>
$(document).ready(function() {
	var options = {
		target: '#message', //Div tag where content info will be loaded in
		url:'/ajax_evctl.php', //The php file that handles the file that is uploaded
		beforeSubmit: function() {
			$('#add_payment_submit').html('<img src="/themes/images/ajax-loader1.gif" border="0" />'); //Including a preloader, it loads into the div tag with id uploader
		},
		success:  function(data) {
			//Here code can be included that needs to be performed if Ajax request was successful
			if (data.trim() == '1') {
				display_js_error(PLUGIN_IP_ADD_PAYMENT_DATE,'message') ;
			} else if(data.trim() == '2') {
				display_js_error(PLUGIN_IP_ADD_REF_NUM,'message') ;
			} else if (data.trim() == '3') { 
				display_js_error(PLUGIN_IP_ADD_AMOUNT,'message') ;
			} else if (data.trim() == '4') {
				display_js_error(PLUGIN_IP_AMOUNT_MORE_THAN_DUE,'message') ;
			} else {
				var jsonParsedData = JSON.parse(data);
				$('#payment_details_tbody').prepend(jsonParsedData.html);
				$('#due_amount').html(jsonParsedData.due_amount);
				$('#ref_num').val('');
				$('#payment_date').val('');
				$('#amount').val('');
				display_js_success(PLUGIN_IP_PAYMENT_ADDED,'message') ;
			}
			var submit_btn = '<input type="submit" class="btn btn-primary" id="" value="<?php echo _('Save');?>"/>';
			$("#add_payment_submit").html(submit_btn);
		}
    };
    
    $('#InvoicePaymentPlugin__eventAjaxAddInvoicePayment').submit(function() {
		$(this).ajaxSubmit(options);
        return false;
    });
});
</script>