<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Profile listing page
* @author Abhik Chakraborty
*/  

$do_roles = new Roles();
    
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"roles_list")?>"><?php echo _('Roles'); ?></a></h3>
				<p><?php echo _('Manage Roles hierarchy for users')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div class="left_300"><h3><?php echo _('Tree view of roles and hierarchy');?></h3></div>
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