<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* User detail top nav
* @author Abhik Chakraborty
*/  
?>
<div class="tabtable">
	<ul class="nav nav-tabs">
		<?php
		if ($current_file == 'detail') { 
			$detail_css = "active"; 
			$detail_nav = "#" ;
		} else { 
			$detail_css =""; 
			$detail_nav = NavigationControl::getNavigationLink($module,"detail",$sqcrm_record_id);
		}
		
		if ($current_file == 'history') { 
			$history_css = "active"; 
			$history_nav = "#";
		} else { 
			$history_css =""; 
			$history_nav = NavigationControl::getNavigationLink($module,"history",$sqcrm_record_id);
		}
		
		if ($current_file == 'loginaudit') {
			$loginaudit_css = "active"; 
			$loginaudit_nav = "#" ;
		} else { 
			$loginaudit_css =""; 
			$loginaudit_nav = NavigationControl::getNavigationLink($module,"loginaudit",$sqcrm_record_id);
		}
		
		if ($current_file == 'homepage_components') {
			$homepage_components_css = "active"; 
			$homepage_components_nav = "#" ;
		} else { 
			$homepage_components_css =""; 
			$homepage_components_nav = NavigationControl::getNavigationLink($module,"homepage_components",$sqcrm_record_id);
		}
		?>
		<li id = "topbar_detail" class="<?php echo $detail_css ; ?>">
			<a href="#" onclick = "load_deail_view_data('<?php echo $module?>','<?php echo $sqcrm_record_id?>','detail')" data-toggle ="tab" >
			<?php echo _('Detail Information ');?>
			</a>
		</li>
		<li id = "topbar_history" class="<?php echo $history_css ; ?>">
			<a href="#" onclick = "load_deail_view_data('<?php echo $module?>','<?php echo $sqcrm_record_id?>','history')" data-toggle ="tab" >
			<?php echo _('Data Audit');?>
			</a>
		</li>
		<li id = "topbar_loginaudit" class="<?php echo $loginaudit_css ; ?>">
			<a href="#" onclick = "load_deail_view_data('<?php echo $module?>','<?php echo $sqcrm_record_id?>','loginaudit')" data-toggle ="tab" >
			<?php echo _('Login Audit');?>
			</a>
		</li>
		<li id = "topbar_homepage_components" class="<?php echo $homepage_components_css ; ?>">
			<a href="#" onclick = "load_deail_view_data('<?php echo $module?>','<?php echo $sqcrm_record_id?>','homepage_components')" data-toggle ="tab" >
			<?php echo _('Home page components');?>
			</a>
		</li>
	</ul>
</div>