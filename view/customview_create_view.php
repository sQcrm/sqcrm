<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* custom view edit view
* @author Abhik Chakraborty
*/ 
$e_add_cv = new Event($module."->eventAddRecord");
$e_add_cv->addParam("target_module_id",$target_module_id);
$e_add_cv->addParam("error_page",NavigationControl::getNavigationLink($module,"add"));
echo '<form class="form-horizontal" id="'.$module.'__addEditRecord" name="'.$module.'__addEditRecord" action="/eventcontroler.php" method="post" enctype="multipart/form-data">';
echo $e_add_cv->getFormEvent();
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="datadisplay-outer">
			<div class="left_600">
				<a href="<?php echo NavigationControl::getNavigationLink($_SESSION["do_module"]->modules_full_details[$target_module_id]["name"],"list");?>" class="btn btn-inverse">
				<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
				<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
			</div>
			<div class="clear_float"></div>
			<hr class="form_hr">
			<div class="box_content_header"><h3><?php echo _('Custom view information');?></h3>
				<hr class="form_hr">
				<label class="control-label" for="cvname">* <?php echo _('Custom view name');?></label>
				<div class="controls">
					<input type = "text" name="cvname" id="cvname" value="">
				</div>
				<br />
				<label class="control-label" for="is_default"><?php echo _('Is default');?></label>
				<div class="controls">
					<input type = "checkbox" name="is_default" id="is_default">
				</div>
				<br />
				<?php
				if ($_SESSION["do_user"]->is_admin == 1) {
				?>
				<label class="control-label" for="is_public"><?php echo _('Is public');?></label>
				<div class="controls">
					<input type = "checkbox" name="is_public" id="is_public">
				</div>
				<?php
				}
				?>
			</div>
			
			<div class="box_content_header"><h3><?php echo _('Custom view fields information');?></h3>
				<hr class="form_hr">
				<div class="control-group">
					<div class="controls">
						<table align="">
							<tr>
								<td>
									<label class="control-label" for=""><?php echo _('Available Fields')?></label><br />
									<select name="select_module_fields" id="select_module_fields" multiple size = "20" style = "width:300px;">
									<?php
										echo '<optgroup label="'.$_SESSION["do_module"]->modules_full_details[$target_module_id]["label"].'"></optgroup>';
										if (is_array($cv_fields) && count($cv_fields) > 0) {
											foreach ($cv_fields as $idblock=>$blockdata) { 
												foreach ($blockdata as $blockname=>$fieldinfo) {
													echo '<optgroup label="'.$blockname.'" style="padding-left:15px">';
													foreach ($fieldinfo as $key=>$val) {
														echo '<option value="'.$val["idfields"].'" style="padding-left:30px">'.$val["field_label"].'</option>';
													}
												}
											}
										}
									?>
									</select>
								</td>
								<td width="50px;" align="center"><br />
									<a href="#" class="btn btn-success btn-mini-1" id="module_fields_add_select"><i class="icon-white icon-arrow-right"></i></a>
								</td>
								<td>
									<label class="control-label" for=""><?php echo _('Selected Fields')?></label><br />
									<select name="cv_fields[]" id="cv_fields" multiple size = "19"></select>
								</td>
								<td width="50px;" align="center"><br />
									<a href="#" class="btn btn-success btn-mini-1" id="cv_fields_up"><i class="icon-white icon-arrow-up"></i></a>
									<br /><br />
									<a href="#" class="btn btn-success btn-mini-1" id="cv_fields_down"><i class="icon-white icon-arrow-down"></i></a>
									<br /><br />
									<a href="#" class="btn btn-inverse btn-mini-1" id="cv_fields_delete"><i class="icon-white icon-remove-sign"></i></a>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			
			<div class="box_content_header"><h3><?php echo _('Custom view date filter');?></h3>
				<hr class="form_hr">
				<table>
					<tr>
						<td>
							<label class="control-label" for=""><?php echo _('Select Date Filter');?></label>
							<div class="controls">
								<select name="cv_date_field" id="cv_date_field">
									<option value="0"><?php echo _('Select date filter');?></option>
									<?php
									foreach ($date_filters as $key=>$val) {
										echo '<option value="'.$val["idfields"].'">'.$val["field_label"].'</option>';
									}
									?>
								</select>
								&nbsp;&nbsp;
								<select name="cv_date_field_type" id="cv_date_field_type">
								<?php
								foreach ($date_filter_options as $key=>$val) {
									echo '<option value="'.$key.'">'.$val.'</option>';
								}
								?>
								</select>
							</div>
						</td>
					</tr>
					<tr><td colspan=2></td></tr>
					<tr><td colspan=2></td></tr>
					<tr><td colspan=2></td></tr>
					<tr>
						<td>
							<div id="cv_date_filter">
								<label class="control-label" for=""><?php echo _('Start date');?></label>
								<div class="controls">
									<?php echo FieldType9::display_field('cv_date_start');?>
								</div>
								<label class="control-label" for=""><?php echo _('End date');?></label>
								<div class="controls">
									<?php echo FieldType9::display_field('cv_date_end');?>
								</div>
							</div>
						</td>
					</tr>
				</table>	
			</div>
			
			<div class="box_content_header"><h3><?php echo _('Advanced filter');?></h3>
				<table>
					<?php
					$cnt = 0;
					for ($i=0;$i<=4;$i++) {
						$cnt++;
					?>
					<tr>
						<td>
							<select name="cv_adv_fields_<?php echo $i+1;?>" id="cv_adv_fields_<?php echo $i+1;?>" style = "width:300px;">
								<option value="0"><?php echo _('none');?></option>
								<?php
								echo '<optgroup label="'.$_SESSION["do_module"]->modules_full_details[$target_module_id]["label"].'"></optgroup>';
								if (is_array($cv_fields) && count($cv_fields) >0) {
									foreach ($cv_fields as $idblock=>$blockdata) { 
										foreach ($blockdata as $blockname=>$fieldinfo) {
											echo '<optgroup label="'.$blockname.'" style="padding-left:15px">';
											foreach ($fieldinfo as $key=>$val) {
												if($val["field_type"] == 9 ) continue ;
												echo '<option value="'.$val["idfields"].'" style="padding-left:30px">'.$val["field_label"].'</option>';
											}
										}
									}
								}
								?>
							</select>
							&nbsp;&nbsp;
							<select name="cv_adv_fields_type_<?php echo $i+1;?>" id = "cv_adv_fields_type_<?php echo $i+1;?>">	
							<?php
							foreach ($advanced_filter_options as $key=>$val) {
								echo '<option value="'.$key.'">'.$val.'</option>';
							}
							?>
							</select>
							&nbsp;&nbsp;
							<input type="text" name="cv_adv_fields_val_<?php echo $i+1;?>" id="cv_adv_fields_val_<?php echo $i+1;?>" value="">
							&nbsp;&nbsp;
							<?php if($i<4) echo '<span style="font-size: 11px;">'._('and').'</span>';?>
						</td>
					</tr>
					<tr><td></td></tr>
					<tr><td></td></tr>
					<?php 
					} ?>
				</table>
			</div>
			<hr class="form_hr">
			<div class="left_600">
				<a href="<?php echo NavigationControl::getNavigationLink($_SESSION["do_module"]->modules_full_details[$target_module_id]["name"],"list");?>" class="btn btn-inverse">
				<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
				<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {   
	
});
</script>