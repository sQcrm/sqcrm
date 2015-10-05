<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* User detail 
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="row-fluid">
				<div class="datadisplay-outer">
					<?php 
					require("user_detail_view_toptabs.php");
					?>
					<div id="detail_view_section">
						<?php
						require("detail_view_entry.php");
						?>
					</div>
				</div>
			</div><!--/row-->
		</div><!--/span-->
	</div><!--/row-->
</div>