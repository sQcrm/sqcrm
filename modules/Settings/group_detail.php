<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Group add page
* @author Abhik Chakraborty
*/  

$do_group = new Group();
$idgroup = $do_group->getId($sqcrm_record_id) ;    
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings');?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"group_list")?>"><?php echo _('Group');?></a></h3>
				<p><?php echo _('Manage group and users related to the group')?></p> 
			</div>
			<div class="row-fluid"> 
				<div class="datadisplay-outer">
					<div class="left_600">
						<?php
						echo '<h3>'.$do_group->group_name.'</h3>' ;
						echo '<p>'.nl2br($do_group->description).'</p>';
						?>
					</div>
					<div class="right_200">
						<a href="<?php echo NavigationControl::getNavigationLink($module,"group_edit",$do_group->idgroup)?>" class="btn btn-primary"><i class="icon-white icon-edit"></i> <?php echo _('Update');?></a>
					</div>
					<div class="clear_float"></div>
					<h3><?php echo _('Members associated to this group');?></h3>
					<?php
					$do_group_user_rel = new GroupUserRelation();
					$do_group_user_rel->get_users_related_to_group($do_group->idgroup);
					if ($do_group_user_rel->getNumRows() > 0) {
						while ($do_group_user_rel->next()) {
							echo '<p><a href="'.NavigationControl::getNavigationLink("User","detail",$do_group_user_rel->iduser).'">'.$do_group_user_rel->firstname.' '.$do_group_user_rel->lastname.'('.$do_group_user_rel->user_name.')</a></p>';
						}
					}
					?>
				</div>
			</div>
		</div><!--/row-->
	</div><!--/span-->
</div><!--/row-->