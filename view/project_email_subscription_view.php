<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* project email subscription view
* @author Abhik Chakraborty
*/  
?>
<ul class="list-group" id="email_subscription_block">
	
</ul>

<script>
$(document).ready(function() {
	// load the email subscription section 
	$.ajax({
		type: "GET",
		url: "/modules/Project/project_email_subscription",
		data : "ajaxreq="+true+"&rand="+generateRandonString(10)+"&sqrecord=<?php echo $sqcrm_record_id;?>",
		success: function(result) { 
			$('#email_subscription_block').html(result) ;
		},
		beforeSend: function() {
			$('#email_subscription_block').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		}
	});
	
	// change the email subscription 
	$('#email_subscription_block').on('click', '#change-email-subscription', function() {
		$('#email_subscription_block #change-email-subscription-button').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		var subscriptionFlag = $('#email_subscription_block #project-email-subscription-flag').val();
		$.ajax({
			type: "POST",
			data: {subscriptionFlag: subscriptionFlag, idproject:<?php echo $sqcrm_record_id;?>},
			<?php
			$e_change = new Event("Project->eventChangeProjectEmailSubscriptionChoice");
			$e_change->setEventControler("/ajax_evctl.php");
			$e_change->setSecure(false);
			?>
			url: "<?php echo $e_change->getUrl(); ?>",
			success:  function(data) {
				if (data.trim() === '1') {
					$('#email_subscription_block #change-email-subscription-button').html('<input type="button" class="btn btn-primary" id="change-email-subscription" value="'+CHANGE_LW+'"/>');
					display_js_success(UPDATED_SUCCESSFULLY,'js_errors');
				} else {
					display_js_error(data,'js_errors');
				}
			}
		});
	});
});
</script>