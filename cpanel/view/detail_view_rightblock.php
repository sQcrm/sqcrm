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
		require("detail_view_rightblock_actions.php");
		?>
		<?php
		if ($module_id == 15) {
			require("invoice_payment_view.php");
		}
		?> 
	</div>
</div>