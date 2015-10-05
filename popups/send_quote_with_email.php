<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Export list data modal 
* Enables the export functionality for list data
* @author Abhik Chakraborty
*/
include_once("config.php");
$idquotes = $_REQUEST["idquotes"];
$idorganization = $_REQUEST["idorganization"];
$contact_emails = array();
  
if ((int)$idorganization > 0) {
	$do_org = new Organization();
	$contact_emails = $do_org->get_organization_contacts_email((int)$idorganization);
}
  
$e_export = new Event("Quotes->sendQuoteWithEmail");
$e_export->addParam("idquotes", $idquotes);
$e_export->addParam("module", "Quotes");
echo '<form class="form-horizontal" id="Quotes__sendQuoteWithEmail" name="Quotes__sendQuoteWithEmail" action="/eventcontroler.php" method="post">';
echo $e_export->getFormEvent();
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
	<h3><?php echo _('Choose emails to send the quote');?></h3>
</div>
<div class="modal-body">
	<div class="box_content" id="">
		<?php
		if (count($contact_emails) > 0) {
			foreach ($contact_emails as $key=>$val) {
				if (count($val["email"]) > 0) {
					$email_found = false ;
					foreach ($val["email"] as $k=>$v) {
						if (strlen($v) > 3) {
							$email_found = true;
							break;
						}
					}
					if (false === $email_found) continue ;
					echo '<div style="font-size:14px;">';
					echo $val["firstname"].' '.$val["lastname"];
					echo '</div><br>';
					foreach ($val["email"] as $email) {
						if (strlen($email) > 4) {
							echo '<div style="font-size:12px;margin-left:5px;">';
							echo '<input type="checkbox" name="quote_email[]" value="'.$email.':::'.$val["firstname"].'::'.$val["lastname"].'" class="">';
							echo '&nbsp;&nbsp;'.$email ;
							echo '</div>';
						}
					}
					echo '<hr class="form_hr">';
				} else { 
					echo '<strong>'._('No email ids found with the associated contacts for organization selected for this quote, or email opt-out option is on for attached contacts.').'</strong>'; 
				}
			}
		} else {
			echo '<strong>'._('No email ids found with the associated contacts for organization selected for this quote, or email opt-out option is on for attached contacts.').'</strong>';
		}
		?>
	</div>
</div>
<div class="modal-footer">
	<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> <?php echo _('Close');?></a>
	<input type="submit" class="btn btn-primary" id="export_list" value="<?php echo _('Send')?>"/>
</div>
</form>
<script>
$(document).ready(function() {  
	
});
</script>