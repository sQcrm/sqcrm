<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/
include_once("config.php");
$e_test = new Event("ListviewAction->eventTest");
$e_test->addParam("ids",$_REQUEST["chk"]);
echo '<form class="" id="ListviewAction__eventTest" name="ListviewAction__eventTest" action="/eventcontroler.php" method="post">';
echo $e_test->getFormEvent();
?>
<div class="modal-dialog" role="document">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<span class="label label-warning"><?php echo _('WARNING!');?></span>
			</div>
			<div class="modal-body">
				<?php echo _('Are you sure you want to submit ?');?>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default active" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Close');?></a>
				<input type="submit" class="btn btn-primary" value="<?php echo _('Submit')?>"/>
			</div>
			</form>
		</div>
	</div>
</div>