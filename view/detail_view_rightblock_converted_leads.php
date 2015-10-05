<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Detail view right side actions 
* @author Abhik Chakraborty
*/  
?>
<div class="box_content">
	<?php
    echo _('The lead has been converted');
    echo '<br />';
    echo _('Converted By :').$lead_conversion_matrix["user"]["user_name"].' ( '.$lead_conversion_matrix["user"]["fullname"].' )';
    echo '<br />';
    echo _('Converted On : ').$lead_conversion_matrix["conversion_date"]["conversion_date"];
    echo '<br />';
    echo 'The conversion matrix is as - ';
    echo '<br />';
    if (array_key_exists("potential",$lead_conversion_matrix)) {
		echo _('Prospect created');
		echo '<br />';
		echo '<a href="'.NavigationControl::getNavigationLink("Potentials","detail",$lead_conversion_matrix["potential"]["idpotentials"]).'">'.$lead_conversion_matrix["potential"]["potential_name"].'</a>';
		echo '<br />';
    }
    if (array_key_exists("organization",$lead_conversion_matrix)) {
		echo _('Organization created');
		echo '<br />';
		echo '<a href="'.NavigationControl::getNavigationLink("Organization","detail",$lead_conversion_matrix["organization"]["idorganization"]).'">'.$lead_conversion_matrix["organization"]["organization_name"].'</a>' ;
		echo '<br />';
    }
    if (array_key_exists("contact",$lead_conversion_matrix)) {
		echo _('Contact created');
		echo '<br />';
		echo '<a href="'.NavigationControl::getNavigationLink("Contacts","detail",$lead_conversion_matrix["contact"]["idcontacts"]).'">'.$lead_conversion_matrix["contact"]["contact_name"].'</a>' ;
		echo '<br />';
    }
	?>
</div>