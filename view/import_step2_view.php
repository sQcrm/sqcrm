<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Import Step2 view 
* @author Abhik Chakraborty
*/ 
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12" style="margin-left:3px;">
			<div class="row-fluid">
				<div class="datadisplay-outer">
					<?php
					if ($allow_step2 === false) {
					echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:20px;margin-left:200px;margin-right:200px;">';
					echo '<h4>';
					echo _('Import not allowed !');
					echo '</h4>';
					echo $msg ;
					echo '</div>';
					} else {
					$e_import = new Event("do_import->eventImportStep2");
					echo '<form class="form-horizontal" id="do_import__eventImportStep2" name="do_import__eventImportStep2" action="/eventcontroler.php" method="post" enctype="multipart/form-data">';
					echo $e_import->getFormEvent();
					?>
					<div class="alert alert-info">
						<h4>
						<?php
						echo _("Import Step2");
						?>
						</h4>
						<?php
						echo _('Select the fields to be mapped with the csv data.');
						echo '<br />';
						echo _('Please map all the mandatory fields for better data.');
						echo '<br />';
						echo _('Check');
						echo '&nbsp;';
						echo $_SESSION["do_module"]->modules_full_details[$import_module_id]["name"];
						echo '&nbsp;';
						echo _('module');
						echo '&nbsp;';
						echo '<a class="" data-toggle="modal" href="#module_mandatory_fields"><b>'._('mandatory fields').'</b></a>';
						?>
					</div>
					<?php
					if ($saved_map !== false) {
					?>
					<div class="left_600">
						<?php
						echo _('Select a saved map to use in the current import');
						echo '&nbsp;&nbsp;';
						echo '<select name="use_saved_map" id="use_saved_map">';
						echo '<option value="0">'._('select').'</option>';
						foreach ($saved_map as $key=>$val) {
							echo '<option value="'.$val["id"].'">'.$val["map_name"].'</option>';
						}
						echo '</select>';
						?>
					</div>
					<?php 
					} ?>
					<?php
					if ($_SESSION["do_crm_action_permission"]->action_permitted('delete',$module_id) === true) {
					?>
					<div class="left_170" style="display:none;" id="delete_saved_map_section">
						<a href="#" id="delete_saved_map"><?php echo _('delete');?></a>
					</div>
					<?php 
					} else { ?>
					<div class="left_170" style="display:none;" id="delete_saved_map_section"></div>
					<?php 
					} ?>
					<div class="clear_float"></div>
					<br />
					<div class="left_200">
						<a href="<?php echo NavigationControl::getNavigationLink("Import","index","","?return_module=".$_SESSION["do_import"]->get_import_module_id());?>" class="btn btn-inverse">
						<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
						<input type="submit" class="btn btn-primary" id="import_step_2" value="<?php echo _('Next');?>"/>
					</div>
					<?php
					if ($_SESSION["do_crm_action_permission"]->action_permitted('add',$module_id) === true) {
					?>
					<div class="left_170">
						<input type="checkbox" name="save_import_map_ck" id="save_import_map_ck">&nbsp;<?php echo _('Save map for future');?>
					</div>
					<div class="left_170" style="display:none;" id="save_import_map_section">
						<input type="text" name="save_import_map" id="save_import_map">
					</div>
					<?php 
					} ?>
					<div class="clear_float"></div>
					<hr class="form_hr">
					<div class="clear_float"></div>
					<table class="datadisplay">  
						<tbody>
							<?php
							for ($i=0;$i<$row_length;$i++) {
							?>
							<tr>
								<td width="30%">
									<select name="field_map_<?php echo $i; ?>" id="<?php echo 'map_'.$i;?>" onChange = "check_duplicate_map('<?php echo 'map_'.$i;?>','<?php echo $i;?>');" class="import_map_fld">
										<option value="0"><?php echo _('Select field to map'); ?></option>
										<?php
										foreach ($module_fields as $key=>$val) {
											echo '<option value="'.$val["field_name"].'">'.$val["field_label"].'</option>'."\n";
										}
										?>
									</select>
								</td>
								<td width="20%"><?php echo $mapping_first_row[$i] ; ?></td>
								<td width="20%"><?php echo $mapping_second_row[$i] ; ?></td>
								<td width="20%"><?php echo $mapping_third_row[$i] ; ?></td>
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
					<hr class="form_hr">
					<div class="left_600">
						<a href="<?php echo NavigationControl::getNavigationLink("Import","index","","?return_module=".$_SESSION["do_import"]->get_import_module_id());?>" class="btn btn-inverse">
						<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
						<input type="submit" class="btn btn-primary" id="import_step_2" value="<?php echo _('Next');?>"/>
					</div>
					</form>
					<?php 
					} ?>
				</div>
			</div><!--/row-->
		</div><!--/span-->
	</div><!--/row-->
</div>
<script>
function check_duplicate_map(id,element_num) {
	var current_val = $("#"+id).val();
    if (current_val != 0) {
		$('.import_map_fld option:selected').each(function(i,data) {
			if (i != element_num) {
				if (current_val == $(this).val()) {
					display_js_error(ALREADY_MAPPED,'js_errors');
					$('#'+id).val("0");
					return false;
				}
			}
		})
	}
}

$(document).ready(function() {
	$("#save_import_map_ck").click( function() {
		if ($("#save_import_map_ck").is(':checked') == false) {
			$("#save_import_map_section").hide();
		} else {
			$("#save_import_map_section").show();
		}
    });
    
    // when the saved map value is changed the data is loaded and set the map selection options
    $("#use_saved_map").change( function() {
		var saved_map_id = $(this).val();
		if (saved_map_id > 0) {
			$("#delete_saved_map_section").show();
			$.ajax({
				type: "GET",
				<?php
				$e_load_maps = new Event("do_import->eventLoadSavedMaps");
				$e_load_maps->setEventControler("/ajax_evctl.php");
				$e_load_maps->addParam("import_module_id",$import_module_id);
				$e_load_maps->setSecure(false);
				?>
				datatype:"json",
				url: "<?php echo $e_load_maps->getUrl(); ?>&id="+saved_map_id,
				beforeSubmit: function() {
					// $("#load_more_notes_btn").html(LOADING);
					// $("#load_more_notes_btn").attr('disabled','disabled');
				},
				success:  function(html) {
					if (html == 0) {
						display_js_error(IMPORT_SAVED_MAP_NOT_FOUND,'js_errors');
					} else {
						var mapped_data = html;
						$.each(mapped_data,function(index,value) {
							$('.import_map_fld').each(function(i,data) {
								if (index == i) {
									var map_selector = 'map_'+i;
									$('#'+map_selector+' option[value='+value+']').prop('selected',true);
								}
							});
						});
					}
				}
			});
		} else {
			$("#delete_saved_map_section").hide();
			$('.import_map_fld').each(function(i,data) {
				var map_selector = 'map_'+i;
				$('#'+map_selector+' option[value=0]').prop('selected',true);
			});
		}
    });
    
    //on submit the page do some validation
    $("#do_import__eventImportStep2").submit( function() {
		var mapped_count = 0 ;
		$('.import_map_fld option:selected').each(function(i,data) {
			if ($(this).val() != 0) {
				mapped_count++ ;
			}
		});
		
		if (mapped_count == 0) {
			display_js_error(IMPORT_MAP_REQUIRE,'js_errors');
			return false;
		}
		
		if ($("#save_import_map_ck").is(':checked') == true) {
			if ($("#save_import_map").val() == '') {
				display_js_error(IMPORT_ADD_MAP_SAVE_NAME,'js_errors');
				return false;
			}
		}
	});
});
</script>
<div class="modal hide fade" id="module_mandatory_fields">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<h3>
		<?php 
		echo _('Mandatory Fields for');
		echo '&nbsp;';
		echo $_SESSION["do_module"]->modules_full_details[$import_module_id]["name"];
		?>
		</h3>
	</div>
	<div class="modal-body">
		<div class="alert alert-info">
			<?php
			if (is_array($mandatory_fields) && sizeof($mandatory_fields) > 0) {
				foreach ($mandatory_fields as $key=>$val) {
					echo '<b>';
					echo $val["field_label"];
					echo '</b>';
					echo '<br />';
				}
			}
			?> 
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo _('Ok');?></a>
	</div>
	</form>
</div>    