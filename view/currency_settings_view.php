<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Custom field page
* @author Abhik Chakraborty
*/  
$currency_iso_with_symbol = $currency_data["currency_iso_code"]."-".$currency_data["currency_sysmbol"];
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"currency")?>"><?php echo _('Currency')?></a></h3>
				<p><?php echo _('Manage currency settings')?></p> 
			</div>
			<div class="datadisplay-outer">
				<?php
				echo '<div id="message"></div>';
				$e_edit = new Event("CRMGlobalSettings->eventAjaxUpdateCurrencySettings");
				echo '<form class="form-horizontal" id="CRMGlobalSettings__eventAjaxUpdateCurrencySettings" name="CRMGlobalSettings__eventAjaxUpdateCurrencySettings"  method="post" enctype="multipart/form-data">';
				echo $e_edit->getFormEvent();
				?>
				<label class="control-label" for="currency"><?php echo _('Select a currency');?></label>
				<div class="controls">
					<select name="currency" id="currency" class="currency_settings">
						<option value = "AUD-$" <?php if($currency_iso_with_symbol== 'AUD-$') echo "SELECTED"; ?>><?php echo _('Australian dollar');?> (AUD-$)</option>
						<option value = "BRL-R$" <?php if($currency_iso_with_symbol== 'BRL-R$') echo "SELECTED"; ?>><?php echo _('Brazilian real');?> (BRL-R$)</option>
						<option value = "GBP-&amp;pound;" <?php if($currency_iso_with_symbol== 'GBP-&pound;') echo "SELECTED"; ?>><?php echo _('British pound');?> (GBP-&pound;)</option>
						<option value = "CAD-$" <?php if($currency_iso_with_symbol== 'CAD-$') echo "SELECTED"; ?>><?php echo _('Canadian dollar');?> (CAD-$)</option>
						<option value = "Euro-&amp;euro;" <?php if($currency_iso_with_symbol== 'Euro-&euro;') echo "SELECTED"; ?>><?php echo _('Euro')?> -&euro;</option>
						<option value = "HKD-$" <?php if($currency_iso_with_symbol== 'HKD-$') echo "SELECTED"; ?>><?php echo _('Hong Kong dollar');?> (HKD-$)</option>
						<option value = "INR-&amp;#8377;" <?php if($currency_iso_with_symbol== 'INR-&#8377;') echo "SELECTED"; ?>><?php echo _('Indian rupee');?> (INR-&#8377;)</option>
						<option value = "JPY-&amp;yen;" <?php if($currency_iso_with_symbol== 'JPY-&yen;') echo "SELECTED"; ?>><?php echo _('Japanese yen');?> (JPY-&yen;)</option>
						<option value = "MUR-Rs" <?php if($currency_iso_with_symbol== 'MUR-Rs') echo "SELECTED"; ?>><?php echo _('Mauritian Rupees');?> (MUR-Rs)</option>
						<option value = "NZD-$" <?php if($currency_iso_with_symbol== 'NZD-$') echo "SELECTED"; ?>><?php echo _('New Zealand dollar');?> (NZD-$)</option>
						<option value = "ZAR-R" <?php if($currency_iso_with_symbol== 'ZAR-R') echo "SELECTED"; ?>><?php echo _('South African rand');?> (ZAR-R)</option>
						<option value = "CHF-Fr" <?php if($currency_iso_with_symbol== 'CHF-Fr') echo "SELECTED"; ?>><?php echo _('Swiss franc');?> (CHF-Fr)</option>
						<option value = "USD-$" <?php if($currency_iso_with_symbol== 'USD-$') echo "SELECTED"; ?>><?php echo _('United States dollar');?> (USD-$)</option>
					</select>  
				</div>
				<br />
				<label class="control-label" for="currency_symbol_position"><?php echo _('Select currency position');?></label>
				<div class="controls">
					<select name="currency_symbol_position" id="currency_symbol_position" class="currency_settings">
						<option value = "left" <?php if($currency_data["currency_symbol_position"] == 'left') echo "SELECTED";?>>Left</option>
						<option value = "right" <?php if($currency_data["currency_symbol_position"] == 'right') echo "SELECTED";?>>Right</option>
					</select>  
				</div>
				<br />
				<label class="control-label" for="decimal_point"><?php echo _('Select number of decimal point to show');?></label>
				<div class="controls">
					<select name="decimal_point" id="decimal_point" class="currency_settings">
						<option value = "0" <?php if($currency_data["decimal_point"] == '0'|| $currency_data["decimal_point"] =="") echo "SELECTED";?>>No decimal</option>
						<option value = "1" <?php if($currency_data["decimal_point"] == '1') echo "SELECTED";?>>One</option>
						<option value = "2" <?php if($currency_data["decimal_point"] == '2') echo "SELECTED";?>>Two</option>
						<option value = "3" <?php if($currency_data["decimal_point"] == '3') echo "SELECTED";?>>Three</option>
					</select>  
				</div>
				<br />
				<label class="control-label" for="decimal_symbol"><?php echo _('Select decimal symbol');?></label>
				<div class="controls">
					<select name="decimal_symbol" id="decimal_symbol" class="currency_settings">
						<option value = "." <?php if($currency_data["decimal_symbol"] == '.') echo "SELECTED";?>>Period (" . ")</option>
						<option value = "," <?php if($currency_data["decimal_symbol"] == ',') echo "SELECTED";?>>Comma (" , ")</option>
					</select>  
				</div>
				<br />
				<label class="control-label" for="thousand_seperator"><?php echo _('Select seperator for every thousand');?></label>
				<div class="controls">
					<select name="thousand_seperator" id="thousand_seperator" class="currency_settings">
						<option value = "," <?php if($currency_data["thousand_seperator"] == ',') echo "SELECTED";?>>Comma (" , ")</option>
						<option value = " " <?php if($currency_data["thousand_seperator"] == " ") echo "SELECTED";?>>Space (" ")</option>
						<option value = "." <?php if($currency_data["thousand_seperator"] == ".") echo "SELECTED";?>>Period (" . ")</option>
					</select>  
				</div>
				<br /><br />
				<div id="example_amount">
					<b>
					<?php
					if ($currency_data["currency_symbol_position"] == 'left') {
						echo $currency_data["currency_sysmbol"];
						echo " ";
					}
					echo number_format($amount_example,$currency_data["decimal_point"], $currency_data["decimal_symbol"],$currency_data["thousand_seperator"]);
					if ($currency_data["currency_symbol_position"] == 'right') {
						echo " ";
						echo $currency_data["currency_sysmbol"];
					}
					?>
					</b>
				</div>
				<hr class="form_hr">
				<div id="settings_currency">
					<input type="submit" class="btn btn-primary" id="" value="<?php echo _('Save');?>"/>
				</div>
				</form>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>
<script type="text/javascript" src="/js/jquery/plugins/accounting.js"></script>
<script type="text/javascript" src="/js/jquery/plugins/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var amount_example = '<?php echo $amount_example ;?>';
	$(".currency_settings").change( function() {
		var currency = $("#currency").val();
		var currency_split = currency.split("-");
		var currency_symbol = currency_split[1];
		var currency_symbol_position = $("#currency_symbol_position").val();
		var decimal_point = $("#decimal_point").val();
		var decimal_symbol = $("#decimal_symbol").val();
		var thousand_seperator = $("#thousand_seperator").val();
		var formatted_amt = accounting.formatMoney(amount_example, "", decimal_point, thousand_seperator, decimal_symbol); 
		if (currency_symbol_position == 'left') {
			formatted_amt = currency_symbol+' '+formatted_amt;
		} else if (currency_symbol_position == 'right') {
			formatted_amt = formatted_amt+' '+currency_symbol;
		}
		$("#example_amount").html('<b>'+formatted_amt+'</b>');
	});
  
	var options = {
		target: '#message', //Div tag where content info will be loaded in
		url:'/ajax_evctl.php', //The php file that handles the file that is uploaded
		beforeSubmit: function() {
			$('#settings_currency').html('<img src="/themes/images/ajax-loader1.gif" border="0" />'); //Including a preloader, it loads into the div tag with id uploader
		},
		success:  function(data) {
			//Here code can be included that needs to be performed if Ajax request was successful
			var succ_element = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
			var succ_msg = succ_element+'<strong>'+data+'</strong></div>';
			$("#message").html(succ_msg);
			var submit_btn = '<input type="submit" class="btn btn-primary" id="" value="<?php echo _('Save');?>"/>';
			$("#settings_currency").html(submit_btn);
		}
    };
    
    $('#CRMGlobalSettings__eventAjaxUpdateCurrencySettings').submit(function() {
		$(this).ajaxSubmit(options);
        return false;
    });
    // Ajax submit ends here
});
</script>