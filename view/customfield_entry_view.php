<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Custom field page
* @author Abhik Chakraborty
*/ 
?>
<div id="cf_entry" style="margin-top:0px;">
	<?php
	if ($cf_module == 3) {
	?>
	<table class="datadisplay_collapse_center">  
		<thead>
			<tr>  
				<th width="5%">#</th>  
				<th width="25%"><?php echo _('Field Label');?></th>  
				<th width="15%"><?php echo _('Field Type');?></th>  
				<th width="45%">
					<table class="datadisplay_collapse_center">
						<thead>
							<tr>
								<th colspan="3" align="center" style="text-align:center;"><?php echo _('Mapping field with other module'); ?></th>
							</tr>
							<tr>
								<th width="12%"><?php echo $modules_info[6]["label"]; ?></th>
								<th width="15%"><?php echo $modules_info[4]["label"];?></th>
								<th width="15%"><?php echo $modules_info[5]["label"];?></th>
							</tr>
						</thead>
					</table>
				</th>
				<th width="10%"><?php echo _('Action'); ?></th>
			</tr>
		</thead>
	</table>
	<table class="datadisplay_collapse_center">  
		<tbody>
			<?php
			if ($do_custom_field->getNumRows() > 0) {
				$count = 0 ;
				$do_fields_mapping = new CRMFieldsMapping();
				while ($do_custom_field->next()) {
					$fieldobject = 'FieldType'.$do_custom_field->field_type;
					$field_type_name = $fieldobject::get_field_type();
					$mapped_fields_info = $do_fields_mapping->get_custom_field_mapped_detail($do_custom_field->idfields);
			?>
			<tr>
				<td width="5%"><?php echo ++$count; ?></td>
				<td width="25%"><?php echo $do_custom_field->field_label;?></td>
				<td width="15%"><?php echo $field_type_name ; ?></td>
				<?php 
				if (count($mapped_fields_info) > 0) {
				?>
				<td colspan=1 width="15%"><?php echo $mapped_fields_info["organization"]["mapped_fieldlabel"]; ?></td>
				<td colspan=1 width="15%"><?php echo $mapped_fields_info["contacts"]["mapped_fieldlabel"]; ?></td>
				<td colspan=1 width="15%"><?php echo $mapped_fields_info["potentials"]["mapped_fieldlabel"]; ?></td>
				<?php } else { ?>
				<td colspan=1 width="15%"></td>
				<td colspan=1 width="15%"></td>
				<td colspan=1 width="15%"></td>
				<?php } ?>
				<td width="10%">
					<a href="#" class="btn btn-primary btn-xs" 
					onclick="edit_custom_field('<?php echo $module;?>',<?php echo $do_custom_field->idfields ;?>,'customfield');">
					<i class="glyphicon glyphicon-edit"></i>
					</a>
					<a href="#" class="btn btn-primary btn-xs" 
					onclick="delete_custom_field('<?php echo $cf_module;?>',<?php echo $do_custom_field->idfields ;?>);">
					<i class="glyphicon glyphicon-trash"></i>
					</a> 
				</td>
			</tr>
			<?php
			}
		}
		?>
		</tbody>
	</table>  
	<?php 
	} else { ?>
	<table class="datadisplay_collapse_center">
		<thead>
			<th width="5%" height="35">#</th>  
			<th width="40%" height="35"><?php echo _('Field Label');?></th>  
			<th width="40%" height="35"><?php echo _('Field Type');?></th>
			<th width="15%" height="35"><?php echo _('Action');?></th>  
		</thead>
	</table>
	<table class="datadisplay_collapse_center">
		<tbody>  
			<?php
			if ($do_custom_field->getNumRows() > 0) {
				$count = 0 ;
				while ($do_custom_field->next()) {
					$fieldobject = 'FieldType'.$do_custom_field->field_type;
					$field_type_name = $fieldobject::get_field_type();
				?>
				<tr>
					<td width="5%"><?php echo ++$count ;?></td>
					<td width="40%"><?php echo $do_custom_field->field_label;?></td>
					<td width="40%"><?php echo $field_type_name ; ?></td>
					<td width="15%">
						<a href="#" class="btn btn-primary btn-xs" 
						onclick="edit_custom_field('<?php echo $module;?>',<?php echo $do_custom_field->idfields ;?>,'customfield');">
						<i class="glyphicon glyphicon-edit"></i>
						</a>
						<a href="#" class="btn btn-primary btn-xs" 
						onclick="delete_custom_field('<?php echo $cf_module;?>',<?php echo $do_custom_field->idfields ;?>);">
						<i class="glyphicon glyphicon-trash"></i>
						</a>
					</td>
				</tr>
				<?php
				}
			}
			?>
		</tbody>
	</table>
	<?php 
	} ?>
</div>