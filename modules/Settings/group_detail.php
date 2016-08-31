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
	<div class="row">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="col-md-9">
			<div class="box_content">
				<ol class="breadcrumb">
					<li class="active"><?php echo _('Settings')?></li>
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"group_list")?>"><?php echo _('Group');?></a></li>
				</ol>
				<p class="lead"><?php echo _('Manage group and users related to the group')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div class="row"> 
					<div class="col-md-12">
						<?php
						echo '<h2><small>'.$do_group->group_name.'</small></h2>' ;
						echo '<p class="lead">'.nl2br($do_group->description).'</p>';
						?>
						<a href="<?php echo NavigationControl::getNavigationLink($module,"group_edit",$do_group->idgroup)?>" class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i> <?php echo _('Update');?></a>
					
						<div class="clear_float"></div>
						<h2><small><?php echo _('Members associated to this group');?></small></h2>
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