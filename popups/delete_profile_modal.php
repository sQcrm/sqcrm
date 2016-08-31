<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Delete modal for the profile
* Checks if the delete is permitted
* @author Abhik Chakraborty
*/
include_once("config.php");
$sqrecord = (int)$_GET["sqrecord"];
$obj =  $_GET["classname"];
$module = $_GET["m"];
$return_page = $_GET["referrar"];
$allow_del = false;
  
if ($sqrecord == 1) {
	$msg = _('You are trying to delete a profile which is not allowed !');
} else {
	$do_profile = new Profile();
	$do_profile->getId($sqrecord);
	if ($do_profile->getNumRows() > 0) {
		$allow_del = true ;
		$associated_roles = $do_profile->get_roles_attached_to_profile($sqrecord);
	} else {
		$msg = _('The profile you are trying to delete does not exist!');
	}
}
    
if ($allow_del === true) {
	$e_del = new Event($obj."->eventDeleteRecord");
	$e_del->addParam("id",$sqrecord);
	if ($associated_roles === false) {
		$e_del->addParam("profile_transfer","no");
	} else {
		$e_del->addParam("profile_transfer","yes");
	}
	$e_del->addParam("next_page",NavigationControl::getNavigationLink($module,$return_page));
	echo '<form class="form-horizontal" id="'.$obj.'__eventDeleteRecord" name="'.$obj.'__eventDeleteRecord" action="/eventcontroler.php" method="post">';
	echo $e_del->getFormEvent();
?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-info"><?php echo _('Delete Profile');?></span></h3>
		</div>
		<div class="modal-body">
		<?php
        if ($associated_roles === false) {
			echo '<div class="alert alert-info">';
			echo _('There is no roles attached with this profile, so it can be deleted without having to transfer the profile.');
			echo '</div>';
        } else {
			echo '<div class="alert alert-info">';
			echo _('Following roles are attached with this profile, please select a profile to transfer data before deleting it.');
			echo '<br />';
			foreach ($associated_roles as $associated_roles) {
				echo '- '.$associated_roles["rolename"].'<br />';
			}
			echo '</div>';
        }
        if ($associated_roles !== false) {
			$do_profile->getAll();
			echo '<select name="idprofile_transfer" name="idprofile_transfer" class="form-control input-sm">';
			while ($do_profile->next()) {
				if ($do_profile->idprofile == $sqrecord) continue ;
				echo '<option value="'.$do_profile->idprofile.'">'.$do_profile->profilename.'</option>'."\n";
			}
			echo '</select>';
        }
		?>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default active" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Close');?></a>
			<input type="submit" class="btn btn-primary" value="<?php echo _('Delete')?>"/>
		</div>
	</div>
	</form>
</div>
<?php
} else {
?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-warning"><?php echo _('WARNING')?></span></h3>
		</div>
		<div class="modal-body">
			<div class="alert alert-danger">
				<?php echo _('You do not have permission to perform this operation');?>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default active" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Close');?></a>
		</div>
	</div>
</div>
<?php 
} ?>