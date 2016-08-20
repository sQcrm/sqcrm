<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* User detail 
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="col-md-9">
			<div class="row">
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