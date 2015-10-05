<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
/**
* load the class from different section of the project using __autoload function 
*/

spl_autoload_register(function ($class) {
	// Core radria objects
	$it = new RecursiveDirectoryIterator($cfg_project_directory.'class/core/radria/');
	foreach (new RecursiveIteratorIterator($it) as $file) {
		if (preg_match("/\.class\.php$/i", $file) && !preg_match("/^\./", $file)) {
			include_once($file);
		}
	}

	//Data History
	$it = new RecursiveDirectoryIterator($cfg_project_directory.'class/datahistory/');
	foreach (new RecursiveIteratorIterator($it) as $file) {
		if (preg_match("/\.class\.php$/i", $file) && !preg_match("/^\./", $file)) {
			include_once($file);
		}
	}
	
	// Core Objects
	$it = new RecursiveDirectoryIterator($cfg_project_directory.'class/core/');
	foreach (new RecursiveIteratorIterator($it) as $file) {
		if (preg_match("/\.class\.php$/i", $file) && !preg_match("/^\./", $file)) { 
			include_once($file);
		}
	}
	
	//Utils
	$it = new RecursiveDirectoryIterator($cfg_project_directory.'class/utils/');
	foreach (new RecursiveIteratorIterator($it) as $file) {
		if (preg_match("/\.class\.php$/i", $file) && !preg_match("/^\./", $file)) {
			include_once($file);
		}
	}
	
	//i18n 
	$it = new RecursiveDirectoryIterator($cfg_project_directory.'class/i18n/');
	foreach (new RecursiveIteratorIterator($it) as $file) {
		if (preg_match("/\.class\.php$/i", $file) && !preg_match("/^\./", $file)) {
			include_once($file);
		}
	}
	
	// CRM essential Objects
	include_once("modules/Settings/Profile.class.php");
	include_once("modules/Settings/Module.class.php");
	include_once("modules/Settings/ModuleStandardPermission.class.php");
	include_once("modules/Settings/ProfileToModuleRelation.class.php");
	include_once("modules/Settings/ProfileToGlobalPermissionRelation.class.php");
	include_once("modules/Settings/ProfileToStandardPermissionRelation.class.php");

	include_once("modules/Settings/Roles.class.php");
	include_once("modules/Settings/RoleProfileRelation.class.php");

	include_once("modules/Settings/Group.class.php");
	include_once("modules/Settings/GroupUserRelation.class.php");

	include_once("modules/Settings/DatasharePermission.class.php");
	include_once("modules/Settings/ModuleToDatashareRelation.class.php");

	include_once("modules/Settings/CRMGlobalSettings.class.php");
	include_once("modules/Settings/TaxSettings.class.php");

	//Fields Object Related
	include_once("class/fields/CRMFields.class.php");
	include_once("class/fields/CustomFields.class.php");
	include_once("class/fields/ComboValues.class.php");
	include_once("class/fields/CRMFieldsMapping.class.php");

	include_once("class/export/ExportListData.class.php");
	include_once("class/export/ExportDetailData.class.php");
	include_once("class/export/ExportInventoryData.class.php");
	
	// Entity Modules
	include_once("modules/Contacts/Contacts.class.php");
	include_once("modules/User/User.class.php");
	include_once("modules/User/LoginAudit.class.php");
	include_once("modules/User/HomepageComponents.class.php");
	include_once("modules/User/UserHomepageComponents.class.php");
	include_once("modules/Leads/Leads.class.php");
	include_once("modules/Leads/LeadConversion.class.php");
	include_once("modules/Organization/Organization.class.php");
	include_once("modules/Potentials/Potentials.class.php");
	include_once("modules/Notes/Notes.class.php");
	include_once("modules/Calendar/Calendar.class.php");
	include_once("modules/Calendar/RecurrentEvents.class.php");
	include_once("modules/Calendar/EventsReminder.class.php");

	include_once("modules/Home/HomePageGraphs.class.php");

	include_once("modules/Vendor/Vendor.class.php");
	include_once("modules/Products/Products.class.php");

	include_once("modules/Quotes/Quotes.class.php");
	include_once("modules/SalesOrder/SalesOrder.class.php");
	include_once("modules/Invoice/Invoice.class.php");
	include_once("modules/PurchaseOrder/PurchaseOrder.class.php");

	include_once("modules/CustomView/CustomView.class.php");
	include_once("modules/CustomView/CustomViewFields.class.php");
	include_once("modules/CustomView/CustomViewFilter.class.php");
	
	include_once("modules/Report/Report.class.php");
	include_once("modules/Report/ReportSorting.class.php");
	include_once("modules/Report/ReportModuleRel.class.php");
	include_once("modules/Report/ReportFields.class.php");
	include_once("modules/Report/ReportFilter.class.php");
	include_once("modules/Report/ReportFolder.class.php");

	include_once("modules/Import/Import.class.php");
	include_once("modules/Import/ContactsImport.class.php");
	include_once("modules/Import/LeadsImport.class.php");
	include_once("modules/Import/OrganizationImport.class.php");
	include_once("modules/Import/PotentialsImport.class.php");
	include_once("modules/Import/VendorImport.class.php");
	include_once("modules/Import/ProductsImport.class.php");

	// Email module
	include_once(THIRD_PARTY_LIB_PATH."/PHPMailer/class.phpmailer.php");
	include_once("modules/Email/EmailTemplate.class.php");
	include_once("modules/Email/SQEmailer.class.php");
	include_once("modules/Email/Email.class.php");
	
	// Load all the different fields Object from the class folder which are essential for the CRM
	$it = new RecursiveDirectoryIterator($cfg_project_directory.'class/fields/fieldtypes/');
	foreach (new RecursiveIteratorIterator($it) as $file) {
		if (preg_match("/\.class\.php$/i", $file) && !preg_match("/^\./", $file)) {
			include_once($file);
		}
	}

	// DataDisplay Class
	include_once("class/datadisplay/DataDisplay.class.php");

	//live feed
	include_once("class/livefeed/LiveFeedQueue.class.php");
	include_once("class/livefeed/LiveFeedDisplay.class.php");

	//inventory
	include_once("class/inventory/Lineitems.class.php");
	
});
?>