<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Setting left side menu
* @author Abhik Chakraborty
*/  
        
?>
<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<?php
			/**
			* loading the core settings
			* @see modules/Settings/settings.conf.inc.php
			* @see includes/extraconfig.inc.php  (loading the module specific config files)
			*/
			foreach ($core_settings as $header=>$settings) {
				echo '<li class="nav-header">'.$header.'</li>';
				foreach ($settings as $name=>$files) {
					$li_class = '';
					if (in_array($current_file,$files['files_list'])) {
						//$current_file name is parsed in module.php
						$li_clas = 'class="active"';
					} else { $li_clas = '';}
					echo '<li '.$li_clas.'><a href="'.NavigationControl::getNavigationLink($files["module"],$files['default_file']).'">'.$name.'</a></li>';
				}
			}
			?>
		</ul>
	</div><!--/.well -->
</div><!--/span-->
   