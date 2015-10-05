<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Detail view entry 
* @author Abhik Chakraborty
*/  
?>
<hr class="form_hr">
<?php
if (is_array($update_history) && count($update_history) > 0) { ?>
	<div class="alert alert-info">
		<?php
        if (array_key_exists("add",$update_history)) {
			if ($update_history["add"]["user_avatar"] == '') {
				echo '<span class="add-on"><i class="icon-user"></i></span>';
			} else {
				echo '<img src="'.$update_history["add"]["user_avatar"].'" style="width:20px;height:20px;" />';
			}
			echo '&nbsp;&nbsp;';
			echo '<strong>'.$update_history["add"]["user_name"].'</strong>';
			echo '<p>'._('added the record on ').'<i>'.$update_history["add"]["modified"].'</i></p>';
        }
        if (array_key_exists("update",$update_history)) {
			if ($update_history["update"]["user_avatar"] == '') {
				echo '<span class="add-on"><i class="icon-user"></i></span>';
			} else {
				echo '<img src="'.$update_history["update"]["user_avatar"].'" style="width:20px;height:20px;" />';
			}
			echo '&nbsp;&nbsp;';
			echo '<strong>'.$update_history["update"]["user_name"].'</strong>';
			echo '<p>'._('updated the record on ').'<i>'.$update_history["update"]["modified"].'</i></p>';
        }
		?>
	</div>
<?php
}
?>
<div class="left_500">
	<a href="<?php echo NavigationControl::getNavigationLink($module,"list");?>" class="btn btn-inverse">
	<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
	<?php
    if ($converted_lead == true && $module_id == 3) {
		echo '&nbsp;';
    } else {
		if ($_SESSION["do_crm_action_permission"]->action_permitted('edit',$module_id) === true) {
			echo '<a href="'.NavigationControl::getNavigationLink($module,"edit",$sqcrm_record_id,"&return_page=detail").'" class="btn btn-primary"><i class="icon-white icon-edit"></i>'._('Edit').'</a>';
		}
    }
	?>
</div>
<div class="right_100">
	<?php
	if ($module_id != 7 && $module_id !=13 && $module_id !=14 && $module_id !=15 && $module_id !=16) {
		$e_export = new Event("ExportDetailData->eventExportDetailDataPDF");
		$e_export->addParam("m", $module);
		$e_export->addParam("mid", $module_id);
		$e_export->addParam("record_id", $sqcrm_record_id);
	?>
	<a href="/<?php echo $e_export->getUrl() ; ?>"><img src="/themes/images/pdf.png"></a>
	<?php 
	} ?>
</div>
<div class="clear_float"></div>
<br />
<?php
while ($do_block->next()) { ?>
<div class="box_content_header"><?php echo $do_block->block_label;?>
	<table width="100%" class="datadisplay_detail">
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
				<td width="20%" class="datadisplay_detail_td_level">
					<?php echo $do_crmfields->field_label;?>
				</td>
				<td width="20%" class="datadisplay_detail_td_item">
					<?php
					$fld_name =  $do_crmfields->field_name;
					if ($do_crmfields->field_type == 12) {
						echo $fieldobject::display_value($module_obj->$fld_name,'l');
					} elseif ($do_crmfields->field_type == 11) {
						echo $fieldobject::display_value($module_obj->$fld_name,$module,$sqcrm_record_id,$fld_name,true);
					} else {
						echo $do_crmfields->display_field_value($module_obj->$fld_name,$do_crmfields->field_type,$fieldobject,$module_obj,$module_id);
					}
					?>
				</td>
			<?php
			if ($tot_count != 1 && $tot_count%2 == 0 ) {  ?> 
			</tr>
          <?php 
          } 
        } ?>
      </table>
	</div><br />
<?php 
} ?>
<?php 
if ($module_id == 13 || $module_id == 14 || $module_id == 15 || $module_id == 16) {
	require("detail_view_line_items.php");
}
?>
<div class="left_600">
	<a href="<?php echo NavigationControl::getNavigationLink($module,"list");?>" class="btn btn-inverse">
	<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
	<?php
	if ($converted_lead == true && $module_id == 3) {
		echo '&nbsp;';
	} else {
		if ($_SESSION["do_crm_action_permission"]->action_permitted('edit',$module_id) === true) {
			echo '<a href="'.NavigationControl::getNavigationLink($module,"edit",$sqcrm_record_id,"&return_page=detail").'" class="btn btn-primary"><i class="icon-white icon-edit"></i>'._('Edit').'</a>';
		}
		echo '&nbsp;';
	}
	?>
</div>
<div class="clear_float"></div>
<hr class="form_hr">