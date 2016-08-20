<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Custom field page
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
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"customfield")?>"><?php echo _('Custom Fields')?></a></li>
				</ol>
				<p class="lead"><?php echo _('Manage webform custom fields')?></p> 
			</div>
			<div class="datadisplay-outer">
				<h2><small><?php echo _('Custom Fields Mapping');?></small></h2>
				<?php
				$e_map_custom_fields = new Event("CRMFieldsMapping->eventMapLeadsCustomFields");
				echo '<form class="" id="CRMFieldsMapping__eventMapLeadsCustomFields" name="CRMFieldsMapping__eventMapLeadsCustomFields" action="/eventcontroler.php" method="post">';
				echo $e_map_custom_fields->getFormEvent();
				?>		
				<a href="<?php echo NavigationControl::getNavigationLink($module,"customfield");?>" class="btn btn-default active">
				<i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Cancel');?></a>  
				<input type="submit" class="btn btn-primary" value="<?php echo _('Save'); ?>"/>
				<hr class="form_hr">
				<div class="clear_float"></div><br />
				<table class="datadisplay_collapse_center">  
					<thead>
						<tr>  
							<th width="5%"  height="35" align="center">#</th>  
							<th width="20%" height="35" align="center"><?php echo _('Field Label');?></th>  
							<th width="25%" height="35" align="center"><?php echo $modules_info[6]["label"]; ?></th>
							<th width="25%" height="35" align="center"><?php echo $modules_info[4]["label"];?></th>
							<th width="25%" height="35" align="center"><?php echo $modules_info[5]["label"];?></th>
						</tr>
					</thead>
					<tbody>
					<?php
						if (is_array($custom_fields_mapping_info) && count($custom_fields_mapping_info) > 0) {
							$count = 0 ;
							$i=0;
							$j=0;
							$k=0;
							foreach ($custom_fields_mapping_info as $key=>$val) {
								$mapped_data = $val["mapped_data"];
							?>
							<tr>
								<td width="5%"><?php echo ++$count;?></td>
								<td width="20%"><?php echo $val["fieldlabel"];?></td>
								<?php
								echo '<td width="25%">';
								if (is_array($organization_custom_fields) && count($organization_custom_fields) >0) {
									echo '<select name="organization_map_'.$key.'" id="organization_map_'.$key.'" onchange="checkOrgDuplicateMap(\'organization_map_'.$key.'\',\''.$i.'\');" class="org_map_list form-control input-sm">'."\n";
									echo '<option value = "0">'._('Pick One').'</option>'."\n"; 
									foreach ($organization_custom_fields as $org_custom_fields) {
										$selected = '';
										if ($org_custom_fields["idfields"] == $mapped_data["organization"]["idfields"]) $selected = 'SELECTED';
										echo '<option value="'.$org_custom_fields["idfields"].'" '.$selected.'>'.$org_custom_fields["field_label"].'</option>'."\n";
									}
									$i++;
								}
								echo '</td>';
								echo '<td width="25%">';
								if (is_array($contacts_custom_fields) && count($contacts_custom_fields) >0) {
									echo '<select name="contacts_map_'.$key.'" id="contacts_map_'.$key.'" onchange="checkContDuplicateMap(\'contacts_map_'.$key.'\',\''.$j.'\');" class="cont_map_list form-control input-sm">'."\n";
									echo '<option value = "0">'._('Pick One').'</option>'."\n"; 
									foreach ($contacts_custom_fields as $cont_custom_fields) {
										$selected = '';
										if ($cont_custom_fields["idfields"] == $mapped_data["contacts"]["idfields"]) $selected = 'SELECTED';
										echo '<option value="'.$cont_custom_fields["idfields"].'" '.$selected.'>'.$cont_custom_fields["field_label"].'</option>'."\n";
									}
									$j++;
								}
								echo '</td>';
								echo '<td width="25%">';
								if (is_array($potentials_custom_fields) && count($potentials_custom_fields) >0) {
									echo '<select name="potentials_map_'.$key.'" id="potentials_map_'.$key.'" onchange="checkPotDuplicateMap(\'potentials_map_'.$key.'\',\''.$k.'\');" class="pot_map_list form-control input-sm">'."\n";
									echo '<option value = "0">'._('Pick One').'</option>'."\n"; 
									foreach ($potentials_custom_fields as $pot_custom_fields) {
										$selected = '';
										if ($pot_custom_fields["idfields"] == $mapped_data["potentials"]["idfields"]) $selected = 'SELECTED';
										echo '<option value="'.$pot_custom_fields["idfields"].'" '.$selected.'>'.$pot_custom_fields["field_label"].'</option>'."\n";
									}
									$k++;
								}
								echo '</td>';
								echo '</tr>';
							}
						}
						?>
					</tbody>
				</table>
			</form>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>
<script>
function checkOrgDuplicateMap(currentid,element_num) {
	var current_val = $("#"+currentid).val();
	if (current_val != 0) {
		$('.org_map_list option:selected').each(function(i,data){
			if (i != element_num) {
				if (current_val == $(this).val()) {
					display_js_error(ALREADY_MAPPED,'js_errors');
					$('#'+currentid).val("0");
					return false;
				}
			}
		})
	}
}
  
function checkContDuplicateMap(currentid,element_num) {
	var current_val = $("#"+currentid).val();
	if (current_val != 0 ) {
		$('.cont_map_list option:selected').each(function(i,data) {
			if (i != element_num) {
				if (current_val == $(this).val()) {
					display_js_error(ALREADY_MAPPED,'js_errors');
					$('#'+currentid).val("0");
					return false;
				}
			}
		})
	}
}
  
function checkPotDuplicateMap(currentid,element_num) {
	var current_val = $("#"+currentid).val();
	if (current_val != 0 ) {
		$('.pot_map_list option:selected').each(function(i,data) {
			if (i != element_num) {
				if (current_val == $(this).val()) {
					display_js_error(ALREADY_MAPPED,'js_errors');
					$('#'+currentid).val("0");
					return false;
				}
			}
		})
	}
}
</script>