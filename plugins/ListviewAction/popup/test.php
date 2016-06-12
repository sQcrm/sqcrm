<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/
include_once("config.php");
$e_test = new Event("ListviewAction->eventTest");
$e_test->addParam("ids",$_REQUEST["chk"]);
echo '<form class="form-horizontal" id="ListviewAction__eventTest" name="ListviewAction__eventTest" action="/eventcontroler.php" method="post">';
echo $e_test->getFormEvent();
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
	<span class="badge badge-warning"><?php echo _('WARNING!');?></span>
</div>
<div class="modal-body">
	<?php echo _('Are you sure you want to submit ?');?>
</div>
<div class="modal-footer">
	<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
	<input type="submit" class="btn btn-primary" value="<?php echo _('Submit')?>"/>
</div>
</form>
