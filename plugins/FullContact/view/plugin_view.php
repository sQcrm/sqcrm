<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/  
?>
<link href="/plugins/FullContact/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<div id="message"></div>
<strong><?php echo $msg;?></strong>
<hr class="form_hr">
<?php
if ($idmodule == 4) {
	if (count($contact_emails) > 0) {
		echo '<br><div style="font-size:14px;">';
		echo _('Select an email for getting social information');
		echo '</div><br>';
		$cnt = 1 ;
		foreach($contact_emails as $k=>$v) {
			if (strlen($v) > 3) {
				echo '<div style="font-size:12px;margin-left:5px;">';
				echo '<input type="radio" '.($cnt == 1 ? 'CHECKED':'').' name="fullcontact_email" value="'.$v.'" class="">';
				echo '&nbsp;&nbsp;'.$v ;
				echo '</div>';
				$cnt++;
			}
		}
		echo '<hr class="form_hr">';
		echo '
			<div id="fullcontact_submit_area">
				<input type="button" class="btn btn-primary" id="fullcontact_submit_button_contact" value="'._('get information').'"/>
			</div>';
		echo '<div id="fullcontact_loading" style="display:none;"><img src="/themes/images/ajax-loader1.gif" border="0" /></div>' ;
		echo '<hr class="form_hr">';
	} else {
		echo '<br><div style="font-size:12px;">';
		echo _('No email available for getting social information');
		echo '</div><br>';
	}
	echo '<div id="fullcontact_info_section" style="display:none;"></div>' ;
} elseif ($idmodule == 6)  {
	if (count($org_websites) > 0) {
		echo '<br><div style="font-size:14px;">';
		echo _('Select a website for getting social information');
		echo '</div><br>';
		$cnt = 1 ;
		foreach($org_websites as $k=>$v) {
			if (strlen($v) > 3) {
				echo '<div style="font-size:12px;margin-left:5px;">';
				echo '<input type="radio" '.($cnt == 1 ? 'CHECKED':'').' name="fullcontact_website" value="'.$v.'" class="">';
				echo '&nbsp;&nbsp;'.$v ;
				echo '</div>';
				$cnt++;
			}
		}
		echo '<hr class="form_hr">';
		echo '
			<div id="fullcontact_submit_area">
				<input type="button" class="btn btn-primary" id="fullcontact_submit_button_org" value="'._('get information').'"/>
			</div>';
		echo '<div id="fullcontact_loading" style="display:none;"><img src="/themes/images/ajax-loader1.gif" border="0" /></div>' ;
		echo '<hr class="form_hr">';
	} else {
		echo '<br><div style="font-size:12px;">';
		echo _('No website available for getting social information');
		echo '</div><br>';
	}
	echo '<div id="fullcontact_info_section" style="display:none;"></div>' ;
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$('.row-fluid #fullcontact_submit_button_contact').click(function() {
		var email = $('input[name=fullcontact_email]:checked').val() ;
		$("#fullcontact_submit_area").hide();
		$("#fullcontact_loading").show();
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("FullContact->eventProcessFullContactAPI");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>&type=person",
			data:"email="+email,
			beforeSubmit: function() {
				$("#fullcontact_submit_area").html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
			},
			success:  function(html) {
				$('#fullcontact_info_section').html(html) ;
				$('#fullcontact_info_section').show('slow') ;
				$("#fullcontact_loading").hide();
				$("#fullcontact_submit_area").show();
			}
		});
        return false;
    });
    
    $('.row-fluid #fullcontact_submit_button_org').click(function() {
		var website = $('input[name=fullcontact_website]:checked').val() ;
		$("#fullcontact_submit_area").hide();
		$("#fullcontact_loading").show();
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("FullContact->eventProcessFullContactAPI");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>&type=company",
			data:"website="+website,
			beforeSubmit: function() {
				$("#fullcontact_submit_area").html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
			},
			success:  function(html) {
				$('#fullcontact_info_section').html(html) ;
				$('#fullcontact_info_section').show('slow') ;
				$("#fullcontact_loading").hide();
				$("#fullcontact_submit_area").show();
			}
		});
        return false;
    });
});
</script>