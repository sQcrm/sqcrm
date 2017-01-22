<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* project email subscription view
* @author Abhik Chakraborty
*/  
?>
<li class="list-group-item" id="email-subscription">
	<strong><?php echo _('Email subscription');?></strong>
	<br />
	<hr class="form_hr">
	<div class="row">
		<div class="col-xs-12">
			<div class="col-xs-8">
				<select name="project-email-subscription-flag" id="project-email-subscription-flag" class="form-control input-sm">
					<option value="1" <?php echo ($email_subscription == 1 ? 'SELECTED' : '');?>><?php echo _('Receive all discussion emails');?></option>
					<option value="2" <?php echo ($email_subscription == 2 ? 'SELECTED' : '');?>><?php echo _('Receive discussion email only if @username is mentioned to me');?></option>
					<option value="3" <?php echo ($email_subscription == 3 ? 'SELECTED' : '');?>><?php echo _('Do not recieve any discussion email');?></option>
				</select>
			</div>
			<div class="col-xs-3" id="change-email-subscription-button">
				<input type="button" class="btn btn-primary" id="change-email-subscription" value="<?php echo _('change')?>"/>
			</div>
		</div>
	</div>
</li>