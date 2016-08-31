<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Pick List/ Multi select combo values manage page
* @author Abhik Chakraborty
*/ 
?>
<div id="pck_mulsel_entry" style="margin-top:0px;">
	<?php
	if (is_array($data_array) && count($data_array) > 0) {
	$tot_data = count($data_array);
    $cnt = 0 ;
    echo '<table width="100%" class="datadisplay_col_block">';
    foreach ($data_array as $key=>$val) {
		$cnt++;
		if ($cnt == 1) { 
			echo '<tr>';
		}
		if ($cnt%4 == 0) {
			echo '</tr>';
			echo '<tr>';
		}
		echo '<td width="33%" valign="top">';
		echo '<strong>'.$val["field_label"].'</strong>&nbsp;&nbsp;
              <a href="#" class="btn btn-primary btn-xs" 
                  onclick="edit_pick_mulsel(\''.$module.'\',\''.$key.'\',\'picklist\',\''.$cf_module.'\')">
                <i class="glyphicon glyphicon-edit"></i>
            </a>';
		echo '<br /><br />';
		foreach ($val["combo_data"] as $combo_data) {
			echo $combo_data.'<br />';
		}
		echo '</td>';
		if ($tot_data == $cnt) {
			echo '</tr>';
		}
	}
    echo '</table>';
  }
?>
</div>