<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* detail view right block
* @author Abhik Chakraborty
*/  
?>
<div class="col-md-5">
	<div class="row">
		<div class="col-md-12">
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
				//require("detail_view_notes.php");
			}
			// process the detail view right block active modules
			$do_process_plugins = new CRMPluginProcessor() ;
			$do_process_plugins->process_detail_view_plugin($module_id,$sqcrm_record_id,$active_plugins);
			?>
		</div>
	</div>
</div>