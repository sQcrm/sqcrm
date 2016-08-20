<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Profile listing page
* @author Abhik Chakraborty
*/  

$do_roles = new Roles();
    
?>
<div class="container-fluid">
	<div class="row">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="col-md-9">
			<div class="box_content">
				<ol class="breadcrumb">
					<li class="active"><?php echo _('Settings')?></li>
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"roles_list")?>"><?php echo _('Roles'); ?></a></li>
				</ol>
				<p class="lead"><?php echo _('Manage Roles hierarchy for users')?></p> 
			</div>
			<div class="datadisplay-outer">
				<h2><small class="text-muted"><?php echo _('Tree view of roles and hierarchy');?></small></h2>
				<div class="clear_float"></div>
				<div class="css-treeview">
					<?php
					$depth_zero_role = $do_roles->get_depth_zero_role();
					$do_roles->render_role_hierarchy(0,$depth_zero_role);
					?>
				</div>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>