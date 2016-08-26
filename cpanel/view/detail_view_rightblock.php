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
			require("detail_view_rightblock_actions.php");
			?>
			<?php
			if ($module_id == 15) {
				require("invoice_payment_view.php");
			}
			?>
		</div>
	</div>
</div>