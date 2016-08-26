<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Home page view
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="datadisplay-outer">
					<?php
					echo _('Welcome ').$_SESSION["do_cpaneluser"]->firstname.', to customer portal.';
					echo '<br />';
					echo _('From the menu you can visit the modules to access the data.');
					echo '<br />';
					echo _('You can always update your information from the profile setting page.');
					?>
				</div>
			</div>
		</div>
	</div>
</div>
