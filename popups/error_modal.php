<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Popup Error modal
* @author Abhik Chakraborty
* @see popup_modal.php
*/
include_once("config.php");
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
	<span class="badge badge-warning"><?php echo $e_popup_header;?></span>
</div>
<div class="modal-body alert-error">
	<?php echo $e_popup_message ; ?>
</div>
<div class="modal-footer">
	<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
</div>