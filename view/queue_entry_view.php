<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Queue entry view o queue listing page
* @author Abhik Chakraborty
*/  
?>
<?php
if (count($queue_data)> 0 && array_key_exists('today',$queue_data)) {
?>
<div class="datadisplay-outer" id="content_today">
	<h4><?php echo _('Queue for the day');?></h4>
	<hr class="form_hr">
	<table class="datadisplay" id="today">
		<tbody>
			<?php
			foreach($queue_data['today'] as $key=>$val) {
			?>
			<tr id="<?php echo $val["idqueue"]?>">
				<td width="30%"><?php echo $modules_info[$val["idmodule"]]['label']?></td>
				<td width="50%"><a href="#" id="<?php echo $modules_info[$val["idmodule"]]['name']?>_<?php echo $val["sqcrm_record_id"]?>" class="entity-detail"><?php echo $val["entity_identifier"];?></a></td>
				<td width="20%">
					<?php 
					if ($_SESSION["do_crm_action_permission"]->action_permitted('edit',$module_id) === true) {
					?>
					<a href="#" class="btn btn-primary btn-mini edit-queue" id="<?php echo $val["idqueue"]?>">
						<i class="icon-white icon-edit"></i>
					</a>
					<?php 
					}
					if ($_SESSION["do_crm_action_permission"]->action_permitted('delete',$module_id) === true) {
					?>
					<a href="#" class="btn btn-primary btn-mini bs-prompt delete-queue" id="<?php echo $val["idqueue"]?>">
						<i class="icon-white icon-trash"></i>
					</a>
					<?php
					}
					?>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>	
</div>
<?php
} else { ?>
<div class="datadisplay-outer" id="content_today">
	<h4><?php echo _('Queue for the day');?></h4>
	<hr class="form_hr">
	<strong id="msg"><?php echo _('No queue data found !');?></strong>
</div>
<?php
}
?>

<?php
if (count($queue_data)> 0 && array_key_exists('tomorrow',$queue_data)) {
?>
<div class="datadisplay-outer" id="content_tomorrow">
	<h4><?php echo _('Queue for tomorrow');?></h4>
	<hr class="form_hr">
	<table class="datadisplay" id="tomorrow">
		<tbody>
			<?php
			foreach($queue_data['tomorrow'] as $key=>$val) {
			?>
			<tr id="<?php echo $val["idqueue"]?>">
				<td width="30%"><?php echo $modules_info[$val["idmodule"]]['label']?></td>
				<td width="50%"><a href="#" id="<?php echo $modules_info[$val["idmodule"]]['name']?>_<?php echo $val["sqcrm_record_id"]?>" class="entity-detail"><?php echo $val["entity_identifier"];?></a></td>
				<td width="20%">
					<?php
					if ($_SESSION["do_crm_action_permission"]->action_permitted('edit',$module_id) === true) {
					?>
					<a href="#" class="btn btn-primary btn-mini edit-queue" id="<?php echo $val["idqueue"]?>">
						<i class="icon-white icon-edit"></i>
					</a>
					<?php 
					}
					if ($_SESSION["do_crm_action_permission"]->action_permitted('delete',$module_id) === true) {
					?>
					<a href="#" class="btn btn-primary btn-mini bs-prompt delete-queue" id="<?php echo $val["idqueue"]?>">
						<i class="icon-white icon-trash"></i>
					</a>
					<?php
					}
					?>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>
<?php
} else { ?>
<div class="datadisplay-outer" id="content_tomorrow">
	<h4><?php echo _('Queue for tomorrow');?></h4>
	<hr class="form_hr">
	<strong id="msg"><?php echo _('No queue data found !');?></strong>
</div>
<?php
}
?>


<?php
if (count($queue_data)> 0 && array_key_exists('later',$queue_data)) {
?>
<div class="datadisplay-outer" id="content_later">
	<h4><?php echo _('Queue for later');?></h4>
	<hr class="form_hr">
	<table class="datadisplay" id="later">
		<tbody>
			<?php
			foreach($queue_data['later'] as $key=>$val) {
			?>
			<tr id="<?php echo $val["idqueue"]?>">
				<td width="30%"><?php echo $modules_info[$val["idmodule"]]['label']?></td>
				<td width="50%"><a href="#" id="<?php echo $modules_info[$val["idmodule"]]['name']?>_<?php echo $val["sqcrm_record_id"]?>" class="entity-detail"><?php echo $val["entity_identifier"];?></a></td>
				<td width="20%">
					<?php
					if ($_SESSION["do_crm_action_permission"]->action_permitted('edit',$module_id) === true) {
					?>
					<a href="#" class="btn btn-primary btn-mini edit-queue" id="<?php echo $val["idqueue"]?>">
						<i class="icon-white icon-edit"></i>
					</a>
					<?php
					}
					if ($_SESSION["do_crm_action_permission"]->action_permitted('delete',$module_id) === true) {
					?>
					<a href="#" class="btn btn-primary btn-mini bs-prompt delete-queue" id="<?php echo $val["idqueue"]?>">
						<i class="icon-white icon-trash"></i>
					</a>
					<?php
					}
					?>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>	
</div>
<?php
} else { ?>
<div class="datadisplay-outer" id="content_later">
	<h4><?php echo _('Queue for later');?></h4>
	<hr class="form_hr">
	<strong id="msg"><?php echo _('No queue data found !');?></strong>
</div>
<?php
}
?>