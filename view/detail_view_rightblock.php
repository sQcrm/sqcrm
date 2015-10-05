<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* detail view right block
* @author Abhik Chakraborty
*/  
?>
<div class="span5" style="margin-left:10px;">
	<div class="row-fluid">
		<?php
		if ($module_id == 2) { // calendar
			require("detail_view_calendar_rightblock.php");
		} else {
			if ($module_id == 3 && $converted_lead === true) {
				require("detail_view_rightblock_converted_leads.php");
			} else {
				require("detail_view_rightblock_actions.php");
			}
		}
		if ($module_id == 3 && $converted_lead === true) {
			// do nothing
		} else {
			require("detail_view_notes.php");
		}
		?>
	</div>
</div>