<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Home page view when no component is set for the user from setting section
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12" style="margin-left:3px;">
			<div class="row-fluid">
				<div class="datadisplay-outer">
					<div class="alert alert-info">
						<h4>
						<?php echo _('No Home page component found !');?>
						</h4>
						<?php 
						echo _('No home page component is set up for your login, please ask admin to setup the component for your login.');
						echo '<br />';
						echo _('WIth home page component you can view the live feed, calendar activity and graphs.');
						echo '<br />';
						echo _('In the mean while you can - ');
						echo '<br />';
						?>
					</div>
					<?php
					if ($_SESSION["do_crm_action_permission"]->action_permitted('add',3) === true) {
					?>
					<a href="<?php echo NavigationControl::getNavigationLink('Leads','add');?>" class="btn btn-primary btn-large">
					<i class="icon-white icon-plus"></i>
					<?php
					echo _('Create a new');
					echo '&nbsp;';
					echo CommonUtils::get_module_name_as_text(3);
					?>
					</a>
					<br /><br />
					<?php 
					} ?>
					<?php
					if ($_SESSION["do_crm_action_permission"]->action_permitted('add',2) === true) {
					?>
					<a href="<?php echo NavigationControl::getNavigationLink('Calendar','add');?>" class="btn btn-primary btn-large">
					<i class="icon-white icon-plus"></i>
					<?php
					echo _('Create a new');
					echo '&nbsp;';
					echo CommonUtils::get_module_name_as_text(2);
					?>
					</a>
					<br /><br />
					<?php 
					} ?>
					<?php
					if ($_SESSION["do_crm_action_permission"]->action_permitted('add',4) === true) {
					?>
					<a href="<?php echo NavigationControl::getNavigationLink('Contacts','add');?>" class="btn btn-primary btn-large">
					<i class="icon-white icon-plus"></i>
					<?php
					echo _('Create a new');
					echo '&nbsp;';
					echo CommonUtils::get_module_name_as_text(4);
					?>
					</a>
					<br /><br />
					<?php 
					} ?>
          
					<?php
					if ($_SESSION["do_crm_action_permission"]->action_permitted('add',5) === true) {
					?>
					<a href="<?php echo NavigationControl::getNavigationLink('Potentials','add');?>" class="btn btn-primary btn-large">
					<i class="icon-white icon-plus"></i>
					<?php
					echo _('Create a new');
					echo '&nbsp;';
					echo CommonUtils::get_module_name_as_text(5);
					?>
					</a>
					<br /><br />
					<?php 
					} ?>
          
					<?php
					if ($_SESSION["do_crm_action_permission"]->action_permitted('add',6) === true) {
					?>
					<a href="<?php echo NavigationControl::getNavigationLink('Organization','add');?>" class="btn btn-primary btn-large">
					<i class="icon-white icon-plus"></i>
					<?php
					echo _('Create a new');
					echo '&nbsp;';
					echo CommonUtils::get_module_name_as_text(6);
					?>
					</a>
					<br /><br />
					<?php 
					} ?>
				</div>
			</div><!--/row-->
		</div><!--/span-->
	</div><!--/row-->
</div>