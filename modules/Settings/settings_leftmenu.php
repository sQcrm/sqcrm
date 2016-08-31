<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Setting left side menu
* @author Abhik Chakraborty
*/  
        
?>
<div class="col-md-3">
	<div class="row">
		<div class="col-md-12">
			<div class="box_content">
				<ul class="list-group">
				<?php
				/**
				* loading the core settings
				* @see modules/Settings/settings.conf.inc.php
				* @see includes/extraconfig.inc.php  (loading the module specific config files)
				*/
				foreach ($core_settings as $header=>$settings) {
					echo '<h2><small>'.$header.'</small></h2>';
					foreach ($settings as $name=>$files) {
						$li_class = '';
						if (in_array($current_file,$files['files_list'])) {
							//$current_file name is parsed in module.php
							$li_clas = 'class="list-group-item custom_active"';
						} else { $li_clas = 'class="list-group-item"';}
						echo '<li '.$li_clas.'><a href="'.NavigationControl::getNavigationLink($files["module"],$files['default_file']).'">'.$name.'</a></li>';
					}
				}
				?>
				</ul>
			</div><!--/.well -->
		</div>
	</div>
</div><!--/span-->
   