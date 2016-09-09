<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Popup Error modal
* @author Abhik Chakraborty
* @see popup_modal.php
*/
include_once("config.php");
?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span class="badge badge-warning"><?php echo $e_popup_header;?></span>
		</div>
		<div class="modal-body">
			<div class="alert alert-danger">
			<?php echo $e_popup_message ; ?>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default active" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Close');?></a>
		</div>
	</div>
</div>