<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* View modal for the list view of crm module data
* Included in the module/list.php file
* Get the filed information of module for the list view and generate the header for the datatable display
* Sets the fields information in the object member list_view_field_information and sets the object in the persistent session
* @author Abhik Chakraborty
*/
?>
<script>
$(document).ready(function() {
	
});
</script>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<div id="list_view_entry">
					<?php
					require('view/listview_entry.php');
					?>
				</div>
			</div>
		</div>
	</div>
</div>