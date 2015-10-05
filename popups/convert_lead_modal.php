<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Change password modal
* @author Abhik Chakraborty
*/
include_once("config.php");

$referrar = $_GET["referrar"];
$leads_obj = new Leads();
$leads_obj->getId((int)$sqcrm_record_id);

$e_add = new Event("LeadConversion->eventConvertLeads");
$e_add->addParam("idleads",(int)$sqcrm_record_id);
echo '<form class="form-horizontal" id="LeadConversion__eventConvertLeads" name="LeadConversion__eventConvertLeads" action="/eventcontroler.php" method="post">';
echo $e_add->getFormEvent();
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
	<h3><?php echo _('Convert ').'"'.$leads_obj->firstname.' '.$leads_obj->lastname.'"';?></h3>
</div>
<div class="modal-body">
	<!-- Organization section -->
    <div class="box_content" id="org">
		<input type="checkbox" name="org_convertion" id="org_convertion" CHECKED>
		<?php echo _('Organization');?>
		<div id="org_section">
			<div style="margin-left:30px;margin-top:20px;">
				<input type="radio" name="create_org" id="create_org" CHECKED>&nbsp;&nbsp;<?php echo _('Create a new Organization');?>
			</div>
          
			<div id="org_fields" style="margin-left:30px;margin-top:20px;">
				<label class="control-label" for="organization_name">* <?php echo _('Organization Name');?></label>
                <div class="controls">
					<input type = "text" name="organization_name" id="organization_name" value="<?php echo $leads_obj->organization; ?>">
				</div><br />
				<label class="control-label" for="industry">* <?php echo _('Industry');?></label>
				<div class="controls">
				<?php
					FieldType5::display_field("industry",56,$leads_obj->industry);
				?>
				</div>
			</div>
          
			<div style="margin-left:30px;margin-top:20px;">
				<input type="radio" name="select_org" id="select_org">&nbsp;&nbsp;<?php echo _('Select Organization');?>
			</div>
          
			<div id="org_select" style="margin-left:30px;margin-top:20px;display:none;">
				<?php echo FieldType131::display_field("idorganization"); ?>
			</div>
		</div>
	</div>
    <!--/Organization section ends-->
    <!--Contact section-->
    <div class="box_content" id="cont">
		<input type="checkbox" name="cnt_convertion" id="cnt_convertion">
		<?php echo _('Contact');?>
		<div id="cnt_section" style="display:none;">
			<div style="margin-left:30px;margin-top:20px;">
				<input type="radio" name="create_cnt" id="create_cnt" CHECKED>&nbsp;&nbsp;<?php echo _('Create a new Contact');?>
			</div>
			<div id="cnt_fields" style="margin-left:30px;margin-top:20px;">
				<label class="control-label" for="firstname">* <?php echo _('First Name');?></label>
				<div class="controls">
					<input type = "text" name="firstname" id="firstname" value="<?php echo $leads_obj->firstname;?>">
                </div><br />
				<label class="control-label" for="lastname">* <?php echo _('Last Name');?></label>
					<div class="controls">
						<input type = "text" name="lastname" id="lastname" value="<?php echo $leads_obj->lastname;?>">
					</div><br />
				<label class="control-label" for="email">* <?php echo _('Email');?></label>
					<div class="controls">
						<input type = "text" name="email" id="email" value="<?php echo $leads_obj->email;?>">
					</div><br />
			</div>
			<div style="margin-left:30px;margin-top:20px;">
				<input type="radio" name="select_cnt" id="select_cnt">&nbsp;&nbsp;<?php echo _('Select Contact');?>
			</div>
			<div id="cnt_select" style="margin-left:30px;margin-top:20px;display:none;">
				<?php echo FieldType130::display_field("idcontacts"); ?>
			</div>
		</div>
    </div>
    <!--/Contact section ends-->
    <!--Potential section-->
	<div class="box_content" id="pot">
		<input type="checkbox" name="pot_convertion" id="pot_convertion" CHECKED>
		<?php echo _('Prospect');?>
		<div id="pot_section" style="">
			<div id="pot_fields" style="margin-left:30px;margin-top:20px;">
				<label class="control-label" for="potential_name">* <?php echo _('Prospect Name');?></label>
				<div class="controls">
					<input type = "text" name="potential_name" id="potential_name" value="<?php echo $leads_obj->organization; ?>">
                </div><br />
				<label class="control-label" for="expected_closing_date">* <?php echo _('Expected Closing Date');?></label>
				<div class="controls">
					<?php echo FieldType9::display_field('expected_closing_date');?>
				</div><br />
				<label class="control-label" for="sales_stage">* <?php echo _('Sales Stage');?></label>
				<div class="controls">
					<?php echo FieldType5::display_field('sales_stage',117);?>
                </div><br />
				<label class="control-label" for="amount">* <?php echo _('Amount');?></label>
				<div class="controls">
					<!--<input type = "text" name="amount" id="amount" value="">-->
					<?php echo FieldType30::display_field('amount'); ?>
                </div><br />
				<label class="control-label" for="probability">* <?php echo _('Probability');?></label>
                <div class="controls">
					<input type = "text" name="probability" id="probability" value="">
                </div><br />   
			</div>
		</div>
	</div>
    <!--/Potential section ends-->
    <!-- Assigned to section -->
	<div class="box_content">
		<label class="control-label" for="assigned_to"><?php echo _('Assigned To');?></label>
		<div class="controls">
			<?php echo FieldType15::display_field();?>
		</div><br />
    </div>
    <!-- /Assigned to section Ends-->
	<div class="box_content">
		<?php
		echo _('Transfer related data to ');
		?>
		<div style="margin-left:30px;margin-top:5px;" id="transfer_to_org_section">
			<input type="radio" name="transfer_related_data" id="transfer_related_data" value="1" class="transfer_related_data">&nbsp;&nbsp;<?php echo _('Organization');?>
		</div>
		<div style="margin-left:30px;margin-top:5px;display:none;" id="transfer_to_cnt_section">
			<input type="radio" name="transfer_related_data" id="transfer_related_data" value="2" class="transfer_related_data">&nbsp;&nbsp;<?php echo _('Contact');?>
		</div>
		<div style="margin-left:30px;margin-top:5px;" id="transfer_to_pot_section">
			<input type="radio" name="transfer_related_data" id="transfer_related_data" value="3" class="transfer_related_data">&nbsp;&nbsp;<?php echo _('Prospect');?>
		</div>
    </div>
</div>
<div class="modal-footer">
	<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> <?php echo _('Close');?></a>
    <input type="submit" class="btn btn-primary" value="<?php echo _('Save Changes')?>"/>
</div>
</form>
<script>
$(document).ready(function() {  
	$("#org_convertion").click( function() {
		if ($("#org_convertion").is(':checked') == false) {
			$("#transfer_to_org_section").hide();
			$("#org_section").hide();
		} else {
			$("#org_section").show();
			$("#transfer_to_org_section").show();
		}
	});
  
	$ ("#cnt_convertion").click( function() {
		if ($("#cnt_convertion").is(':checked') == false) {
			$("#cnt_section").hide();
			$("#transfer_to_cnt_section").hide();
		} else {
			$("#cnt_section").show();
			$("#transfer_to_cnt_section").show();
		}
	});
  
	$ ("#pot_convertion").click( function() {
		if ($("#pot_convertion").is(':checked') == false) {
			$("#pot_section").hide();
			$("#transfer_to_pot_section").hide();
		} else {
			$("#pot_section").show();
			$("#transfer_to_pot_section").show();
		}
	});
  
	$("#create_org").click( function() {
		$("#select_org").removeAttr('checked');
		$("#org_select").hide();
		$("#org_fields").show();
	});
  
	$("#select_org").click( function() {
		$("#create_org").removeAttr('checked');
		$("#org_fields").hide();
		$("#org_select").show();
	});
  
	$("#create_cnt").click( function() {
		$("#select_cnt").removeAttr('checked');
		$("#cnt_select").hide();
		$("#cnt_fields").show();
	});
  
	$("#select_cnt").click( function() {
		$("#create_cnt").removeAttr('checked');
		$("#cnt_fields").hide();
		$("#cnt_select").show();
	});
  
	$('#LeadConversion__eventConvertLeads').submit( function() {
		var org_convertion = '';
		var cnt_convertion = '';
		var pot_convertion = '';
		if ($("#org_convertion").is(':checked')) {
			org_convertion = 'on';
		}
		if ($("#cnt_convertion").is(':checked')) {
			cnt_convertion = 'on';
		}
		if ($("#pot_convertion").is(':checked')) {
			pot_convertion = 'on';
		}
    
		if (pot_convertion == 'on') {
			if (org_convertion == '' && cnt_convertion =='') {
				display_js_error(LEAD_CONVERT_POT_CONTACT_ORG_REQUIRE,'js_errors');
				return false;
			}
			
			if (org_convertion == 'on' && cnt_convertion =='on') {
				if ($("#select_cnt").is(':checked') && $("#select_org").is(':checked')) {
					display_js_error(LEAD_CONVERT_POT_ONLY_ORG_CONTACT_SELECT,'js_errors');
					return false;
				}
			}
			
			if ($("#potential_name").val() == '') {
				display_js_error(LEAD_CONVERT_POT_NAME_REQUIRE,'js_errors');
				$("#potential_name").focus();
				return false;
			}
			
			if ($("#expected_closing_date").val() == '') {
				display_js_error(LEAD_CONVERT_POT_EXPECTED_CLOSE_DATE_REQUIRE,'js_errors');
				$("#expected_closing_date").focus();
				return false;
			}
			
			if ($("#sales_stage").val() == 'Pick One') {
				display_js_error(LEAD_CONVERT_POT_SALES_STAGE_REQUIRE,'js_errors');
				$("#sales_stage").focus();
				return false;
			}
			
			if ($("#amount").val() == '') {
				display_js_error(LEAD_CONVERT_POT_AMOUNT_REQUIRE,'js_errors');
				$("#amount").focus();
				return false;
			}
			
			if ($("#probability").val() == '') {
				display_js_error(LEAD_CONVERT_POT_PROBABILITY_REQUIRE,'js_errors');
				$("#probability").focus();
				return false;
			}
		}
    
		if (org_convertion == 'on') {
			if ($("#create_org").is(':checked')) {
				if ($("#organization_name").val() == '') {
					display_js_error(LEAD_CONVERT_ORG_ORGNAME_REQUIRE,'js_errors');
					$("#organization_name").focus();
					return false ;
				}
				
				if ($("#industry").val() == 'Pick One') {
					display_js_error(LEAD_CONVERT_ORG_INDUSTRY_REQUIRE,'js_errors');
					$("#industry").focus();
					return false ;
				}
			}
			
			if ($("#select_org").is(':checked')) {
				if ($("#idorganization").val() == '') {
					display_js_error(LEAD_CONVERT_ORG_SELECT_ORG,'js_errors');
					$("#select_idorganization").focus();
					return false ;
				}
			}
		}
    
		if (cnt_convertion == 'on') {
			if ($("#cnt_convertion").is(':checked')) {
				if ($("#firstname").val() == '') {
					display_js_error(LEAD_CONVERT_CONTACT_FIRSTNAME_REQUIRE,'js_errors');
					$("#firstname").focus();
					return false ;
				}
				
				if ($("#lastname").val() == '') {
					display_js_error(LEAD_CONVERT_CONTACT_LASTNAME_REQUIRE,'js_errors');
					$("#lastname").focus();
					return false ;
				}
				
				if ($("#email").val() == '') {
					display_js_error(LEAD_CONVERT_CONTACT_EMAIL_REQUIRE,'js_errors');
					$("#email").focus();
					return false ;
				}
			}
      
			if ($("#select_cnt").is(':checked')) {
				if ($("#idcontact").val() == '') {
					display_js_error(LEAD_CONVERT_CONTACT_SELECT_CONTACT,'js_errors');
					$("#select_idcontact").focus();
					return false ;
				}
			}
		}
		//$('.transfer_related_data').find('input[type=radio]').each(function(){
		var transfer_related_data_checked = 0 ;
		
		$('input:radio').each( function() {
			if ($(this).attr('id') == 'transfer_related_data') {
				if ($(this).is(':checked') == true) {
					transfer_related_data_checked++;
				}
			}
		});
		
		if (transfer_related_data_checked == 0) {
			display_js_error(LEAD_CONVERT_TRANSFER_RELATED_DATA,'js_errors');
			$("#transfer_related_data").focus();
			return false ;
		}
    });
}); // end document.ready
</script>