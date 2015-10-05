<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* datahistory fields page
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"datahistory_settings")?>"><?php echo _('Data History')?></a></h3>
				<p><?php echo _('Manage data history for the modules')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div id="message"></div>
				<div class="left_300"><h4><?php echo _('Choose fields for history tracking');?></h4></div>
				<div class="right_300">
					<select name="dh_module_selector" id="dh_module_selector">
						<?php
						foreach ($datahistory_modules as $key=>$val) {
							$select = '';
							if ($val["idmodule"] == $dh_module) $select = "SELECTED";
								echo '<option value="'.$val["idmodule"].'" '.$select.'>'.$val["module_label"].'</option>';
							}
						?>
					</select>
				</div>
				<div class="clear_float"></div>
				<div id="dh_entry">
					<?php 
					require("datahistory_fields_entry_view.php");
					?>
				</div>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>

<script>

</script>