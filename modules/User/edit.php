<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* User edit 
* @author Abhik Chakraborty
*/  
    
$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);

$module_obj = new User();
$module_obj->getId($sqcrm_record_id);
if (isset($_GET["return_page"]) && $_GET["return_page"] != '') {
	$return = $_GET["return_page"] ;
	$cancel_return = NavigationControl::getNavigationLink($module,$return,$sqcrm_record_id);
} else {
	$cancel_return = NavigationControl::getNavigationLink($module,"users");
}
?>
<div class="container-fluid">
	<div class="row">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="col-md-9">
			<div class="row">
				<div class="datadisplay-outer">
					<?php
					$e_add_entity = new Event($module."->eventEditRecord");
					$e_add_entity->addParam("idmodule",$module_id);
					$e_add_entity->addParam("sqrecord",$sqcrm_record_id);
					$e_add_entity->addParam("error_page",NavigationControl::getNavigationLink($module,"edit",$sqcrm_record_id));
					echo '<form class="form-horizontal" id="'.$module.'__editRecord" name="'.$module.'__editRecord" action="/eventcontroler.php" method="post" enctype="multipart/form-data">';
					echo $e_add_entity->getFormEvent();
					?>
					<div class="left_large">
						<a href="<?php echo NavigationControl::getNavigationLink($module,"users");?>" class="btn btn-default active">
						<i class="glyphicon glyphicon-remove"></i> <?php echo _('Cancel');?></a>  
						<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
					</div>
					<div class="clear_float"></div>
					<hr class="form_hr">
					<?php
						while ($do_block->next()) { ?>
							<div class="box_content_header"><strong><?php echo $do_block->block_label;?></strong>
							<hr class="form_hr">
							<?php 
							$do_crmfields->get_form_fields_information($do_block->idblock,$module_id) ;
							$num_fields = $do_crmfields->getNumRows() ;
							$tot_count = 0 ;
							while ($do_crmfields->next()) {
								$fieldobject = 'FieldType'.$do_crmfields->field_type;
								if ($do_crmfields->field_type == 11) continue; // do not show the password update on edit
								$tot_count++;
								if ($tot_count == 1 || $tot_count%2 != 0 ) { ?>
								<div class="row">
								<?php
								}
								?>
									<div class="col-md-6">
										<div class="col-md-12">
											<div class="form-group">  
												<label class="control-label" for="<?php echo $do_crmfields->field_name; ?>"><?php echo $do_crmfields->field_label;?></label>  
												<div class="controls">  
													<?php
													$fld_name =  $do_crmfields->field_name;
													if ($do_crmfields->field_type == 5 || $do_crmfields->field_type == 6) {
														$fieldobject::display_field($do_crmfields->field_name,$do_crmfields->idfields,$module_obj->$fld_name);
													} elseif ($do_crmfields->field_type == 104) {
														$fieldobject::display_field($do_crmfields->field_name,$module_obj->$fld_name);
													} elseif ($do_crmfields->field_type == 12) {
														$fieldobject::display_field($do_crmfields->field_name,$module_obj->$fld_name,'m');
													} else {
														$fieldobject::display_field($do_crmfields->field_name,$module_obj->$fld_name,'form-control input-sm');
													}
													?>
												</div>
											</div>
										</div>
									</div>
								<?php
								if ($tot_count != 1 && $tot_count%2 == 0 ) {  ?> 
								</div>
								<?php 
								} 
							}
							if ($tot_count%2 != 0) echo '</div>';
							?>
						</div>
						<?php 
						} 
						?>
					<hr class="form_hr">
					<div class="left_large">
						<a href="<?php echo NavigationControl::getNavigationLink($module,"users");?>" class="btn btn-default active">
						<i class="glyphicon glyphicon-remove"></i> <?php echo _('Cancel');?></a>  
						<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
					</div>
					<div class="clear_float"></div>
					</form>
				</div>
			</div><!--/row-->
		</div><!--/span-->
	</div><!--/row-->
</div>
<script>
<?php 
  echo $do_crmfields->get_js_form_validation($module_id,$module."__editRecord","edit",$sqcrm_record_id);
?>
$.validator.addMethod("notEqual", function(value,element,param) {
	return this.optional(element) || value != param;
},"Please select a value ");

$.validator.addMethod("alphaNumericUnderscore", function(value,element,param) {
	return this.optional(element) || /^[a-zA-Z0-9_]+$/i.test(value);
},"Only letter numbers and underscore is allowed for username "); 
</script>