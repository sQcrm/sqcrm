<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Related view
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span7">
			<div class="row-fluid">
				<div class="datadisplay-outer">
					<?php 
					require("detail_view_toptabs.php");
					?>
					<div id="related_view_section">
						<?php
						if ($related_data_information !== false && is_array($related_data_information) && count($related_data_information) > 0) {
							require("related_listview_entry.php");
						} else {
							echo '<div class="alert alert-info>';
							echo '<strong>'._('No related information found').'</strong>';
							echo '</div>';
						}
						?>
					</div>
				</div>
			</div><!--/row-->
		</div><!--/span-->
		<?php 
		require("detail_view_rightblock.php");
		?>
	</div><!--/row-->
</div>