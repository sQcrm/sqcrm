<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* User add 
* @author Abhik Chakraborty
*/ 

$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);
?>

<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
			<div class="span9" style="margin-left:3px;">
				<div class="row-fluid">
					<div class="datadisplay-outer">
						<?php
						$e_add_entity = new Event($module."->eventAddRecord");
						$e_add_entity->addParam("idmodule",$module_id);
						$e_add_entity->addParam("error_page",NavigationControl::getNavigationLink($module,"add"));
						echo '<form class="form-horizontal" id="'.$module.'__addRecord" name="'.$module.'__addRecord" action="/eventcontroler.php" method="post" enctype="multipart/form-data">';
						echo $e_add_entity->getFormEvent();
						?>
						<div class="left_600">
							<a href="<?php echo NavigationControl::getNavigationLink($module,"users");?>" class="btn btn-inverse">
							<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
							<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
						</div>
						<div class="clear_float"></div>
						<hr class="form_hr">
						<?php
						while ($do_block->next()) { ?>
							<div class="box_content_header"><?php echo $do_block->block_label;?></div>
								<table width="100%">
								<?php 
								$do_crmfields->get_form_fields_information($do_block->idblock,$module_id) ;
								$num_fields = $do_crmfields->getNumRows() ;
								$tot_count = 0 ;
								while ($do_crmfields->next()) {
									$fieldobject = 'FieldType'.$do_crmfields->field_type;
									$tot_count++;
									if ($tot_count == 1 || $tot_count%2 != 0 ) { ?>
									<tr>
									<?php 
									} ?>
										<td width="40%">
											<div class="control-group">  
												<label class="control-label" for="<?php echo $do_crmfields->field_name; ?>"><?php echo $do_crmfields->field_label;?></label>  
												<div class="controls">  
													<?php
													if ($do_crmfields->field_type == 5 || $do_crmfields->field_type ==6) {
														$fieldobject::display_field($do_crmfields->field_name,$do_crmfields->idfields);
													} elseif ($do_crmfields->field_type == 104) {
														$fieldobject::display_field($do_crmfields->field_name);
													} else {
														$fieldobject::display_field($do_crmfields->field_name,'','input-xlarge-100');
													}
													?>
												</div>
											</div>
										</td>
									<?php
									if ($tot_count != 1 && $tot_count%2 == 0 ) {  ?> 
									</tr>
									<?php 
									} 
								} 
								?>
								</table>
						<?php 
						} ?>
						<hr class="form_hr">
						<div class="left_600">
							<a href="<?php echo NavigationControl::getNavigationLink($module,"users");?>" class="btn btn-inverse">
							<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
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
  echo $do_crmfields->get_js_form_validation($module_id,$module."__addRecord","add");
?>
$.validator.addMethod("notEqual", function(value,element,param) {
	return this.optional(element) || value != param;
  },"Please select a value "
);
</script>