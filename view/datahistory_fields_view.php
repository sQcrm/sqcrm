<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* datahistory fields page
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="col-md-9">
			<div class="box_content">
				<ol class="breadcrumb">
					<li class="active"><?php echo _('Settings')?></li>
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"datahistory_settings")?>"><?php echo _('Data History')?></a></li>
				</ol>
				<p class="lead"><?php echo _('Manage data history for the modules')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div id="message"></div>
				<div class="row">
					<div class="col-md-12">
						<h2><small><?php echo _('Choose fields for history tracking');?></small></h2>
						<div class="row">
							<div class="col-xs-6">
								<select name="dh_module_selector" id="dh_module_selector" class="form-control input-sm">
									<?php
									foreach ($datahistory_modules as $key=>$val) {
										$select = '';
										if ($val["idmodule"] == $dh_module) $select = "SELECTED";
											echo '<option value="'.$val["idmodule"].'" '.$select.'>'.$val["module_label"].'</option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="clear_float"></div>
						<div id="dh_entry">
							<?php 
							require("datahistory_fields_entry_view.php");
							?>
						</div>
					</div>
				</div>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>

<script>

</script>