<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Data History fields
* @author Abhik Chakraborty
*/  

?>
<div class="datadisplay-outer">
	<div class="form-group" style="margin-left:10px;">  
		<?php
		echo '<div id="message"></div>';
		if (count($datahistory_fields) > 0) {
			foreach ($datahistory_fields as $key=>$fields_info) {
			?>
			<label class="checkbox" for="">
				<input type="checkbox" name="datahistory_fields[]" value="<?php echo $fields_info["idfields"];?>" <?php echo ($fields_info["selected"] == 'yes' ? 'CHECKED':'') ?>>
				<?php echo $fields_info["field_label"];?>
			</label>
			<br />
			<?php
			}
		}
		?>
	</div>
	<hr class="form_hr">
	<div id="dhf_settings">
		<input type="button" class="btn btn-primary" id="save-data-history" value="<?php echo _('Save');?>"/>
	</div>
</div>
