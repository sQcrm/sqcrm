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
	<div class="row">
		<div class="col-md-12">
			<div class="datadisplay-outer">
				<div class="left_600">
					<a href="<?php echo NavigationControl::getNavigationLink($_SESSION["do_module"]->modules_full_details[$target_module_id]["name"],"list");?>" class="btn btn-default active">
					<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>  
					<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
				</div>
				<div class="clear_float"></div>
				<hr class="form_hr">
				<div class="box_content_header"><strong><?php echo _('Custom view information');?></strong>
					<hr class="form_hr">
					<div class="row">
						<label class="col-sm-2 control-label" for="cvname">* <?php echo _('Custom view name');?></label>
						<div class="col-xs-6">
							<input type = "text" name="cvname" id="cvname" value="" class="form-control input-sm">
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 control-label" for="is_default"><?php echo _('Is default');?></label>
						<div class="col-xs-4">
							<input type = "checkbox" name="is_default" id="is_default">
						</div>
					</div>
					<?php
					if ($_SESSION["do_user"]->is_admin == 1) {
					?>
					<div class="row">
						<label class="col-sm-2 control-label" for="is_public"><?php echo _('Is public');?></label>
						<div class="col-xs-4">
							<input type = "checkbox" name="is_public" id="is_public">
						</div>
					</div>
					<?php
					}
					?>
				</div>
				
				<div class="box_content_header"><strong><?php echo _('Custom view fields information');?></strong>
					<hr class="form_hr">
					<div class="control-group">
						<div class="controls">
							<table align="">
								<tr>
									<td>
										<label class="control-label" for=""><?php echo _('Available Fields')?></label><br />
										<select name="select_module_fields" id="select_module_fields" multiple size = "20" class="form-control input-sm">
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
										<a href="#" class="btn btn-success" id="module_fields_add_select"><i class="glyphicon glyphicon-arrow-right"></i></a>
									</td>
									<td>
										<label class="control-label" for=""><?php echo _('Selected Fields')?></label><br />
										<select name="cv_fields[]" id="cv_fields" multiple size = "19" class="form-control input-sm"></select>
									</td>
									<td width="50px;" align="center"><br />
										<a href="#" class="btn btn-success" id="cv_fields_up"><i class="glyphicon glyphicon-arrow-up"></i></a>
										<br /><br />
										<a href="#" class="btn btn-success" id="cv_fields_down"><i class="glyphicon glyphicon-arrow-down"></i></a>
										<br /><br />
										<a href="#" class="btn btn-default active" id="cv_fields_delete"><i class="glyphicon glyphicon-remove-sign"></i></a>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				
				<div class="box_content_header"><strong><?php echo _('Custom view date filter');?></strong>
					<hr class="form_hr">
					<label class="control-label" for=""><?php echo _('Select Date Filter');?></label>
					<div class="row">
						<div class="col-xs-6">
							<select name="cv_date_field" id="cv_date_field" class="form-control input-sm">
								<option value="0"><?php echo _('Select date filter');?></option>
								<?php
								foreach ($date_filters as $key=>$val) {
									echo '<option value="'.$val["idfields"].'">'.$val["field_label"].'</option>';
								}
								?>
							</select>
						</div>
						<div class="col-xs-6">
							<select name="cv_date_field_type" id="cv_date_field_type" class="form-control input-sm">
							<?php
							foreach ($date_filter_options as $key=>$val) {
								echo '<option value="'.$key.'">'.$val.'</option>';
							}
							?>
							</select>
						</div>
					</div>
					<br />
					<div class="row" id="cv_date_filter">
						<div class="col-xs-8">
							<div class="row">
								<div class="col-xs-3">
									<label class="control-label" for=""><?php echo _('Start date');?></label>
								</div>
								<div class="col-xs-5">
									<?php echo FieldType9::display_field('cv_date_start');?>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-3">
									<label class="control-label" for=""><?php echo _('End date');?></label>
								</div>
								<div class="col-xs-5">
									<?php echo FieldType9::display_field('cv_date_end');?>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="box_content_header"><strong><?php echo _('Advanced filter');?></strong>
				<hr class="form_hr">
				<?php
					$cnt = 0;
					for ($i=0;$i<=4;$i++) {
						$cnt++;
					?>
					<div class="row">
						<div class="col-xs-4">
							<select name="cv_adv_fields_<?php echo $i+1;?>" id="cv_adv_fields_<?php echo $i+1;?>" class="form-control input-sm">
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
						</div>
						<div class="col-xs-4">
							<select name="cv_adv_fields_type_<?php echo $i+1;?>" id = "cv_adv_fields_type_<?php echo $i+1;?>" class="form-control input-sm">	
							<?php
							foreach ($advanced_filter_options as $key=>$val) {
								echo '<option value="'.$key.'">'.$val.'</option>';
							}
							?>
							</select>
						</div>
						<div class="col-xs-3">
							<input type="text" name="cv_adv_fields_val_<?php echo $i+1;?>" id="cv_adv_fields_val_<?php echo $i+1;?>" value="" class="form-control input-sm">
						</div>
						<div class="col-xs-1">
							<?php if($i<4) echo '<span style="font-size: 11px;">'._('and').'</span>';?>
						</div>
					</div>
					<?php
					}
					?>
				</div>
				<hr class="form_hr">
				<div class="left_600">
					<a href="<?php echo NavigationControl::getNavigationLink($_SESSION["do_module"]->modules_full_details[$target_module_id]["name"],"list");?>" class="btn btn-default active">
					<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>  
					<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {   
	
});
</script>