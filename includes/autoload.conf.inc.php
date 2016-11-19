<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
/**
* load the class from different section of the project using __autoload function 
*/

spl_autoload_register(function ($class) { 
	$cfg_project_directory = str_replace('includes','',dirname(__FILE__)) ;
	// Core radria objects
	include_once($cfg_project_directory.'class/core/radria/BaseObject.class.php');
	include_once($cfg_project_directory.'class/core/radria/Display.class.php');
	include_once($cfg_project_directory.'class/core/radria/EventControler.class.php');
	include_once($cfg_project_directory.'class/core/radria/Event.class.php');
	include_once($cfg_project_directory.'class/core/radria/DataObject.class.php');
	
	/*$it = new RecursiveDirectoryIterator($cfg_project_directory.'class/core/radria/');
	foreach (new RecursiveIteratorIterator($it) as $file) {
		if (preg_match("/\.class\.php$/i", $file) && !preg_match("/^\./", $file)) {
			include_once($file);
		}
	}*/
	
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
	include_once($cfg_project_directory."modules/Settings/Profile.class.php");
	include_once($cfg_project_directory."modules/Settings/Module.class.php");
	include_once($cfg_project_directory."modules/Settings/ModuleStandardPermission.class.php");
	include_once($cfg_project_directory."modules/Settings/ProfileToModuleRelation.class.php");
	include_once($cfg_project_directory."modules/Settings/ProfileToGlobalPermissionRelation.class.php");
	include_once($cfg_project_directory."modules/Settings/ProfileToStandardPermissionRelation.class.php");

	include_once($cfg_project_directory."modules/Settings/Roles.class.php");
	include_once($cfg_project_directory."modules/Settings/RoleProfileRelation.class.php");

	include_once($cfg_project_directory."modules/Settings/Group.class.php");
	include_once($cfg_project_directory."modules/Settings/GroupUserRelation.class.php");

	include_once($cfg_project_directory."modules/Settings/DatasharePermission.class.php");
	include_once($cfg_project_directory."modules/Settings/ModuleToDatashareRelation.class.php");

	include_once($cfg_project_directory."modules/Settings/CRMGlobalSettings.class.php");
	include_once($cfg_project_directory."modules/Settings/TaxSettings.class.php");
	
	include_once($cfg_project_directory."modules/Settings/PluginSettings.class.php");

	//Fields Object Related
	include_once($cfg_project_directory."class/fields/CRMFields.class.php");
	include_once($cfg_project_directory."class/fields/CustomFields.class.php");
	include_once($cfg_project_directory."class/fields/ComboValues.class.php");
	include_once($cfg_project_directory."class/fields/CRMFieldsMapping.class.php");

	include_once($cfg_project_directory."class/export/ExportListData.class.php");
	include_once($cfg_project_directory."class/export/ExportDetailData.class.php");
	include_once($cfg_project_directory."class/export/ExportInventoryData.class.php");
	
	// Entity Modules
	include_once($cfg_project_directory."modules/Contacts/Contacts.class.php");
	include_once($cfg_project_directory."modules/User/User.class.php");
	include_once($cfg_project_directory."modules/User/LoginAudit.class.php");
	include_once($cfg_project_directory."modules/User/HomepageComponents.class.php");
	include_once($cfg_project_directory."modules/User/UserHomepageComponents.class.php");
	include_once($cfg_project_directory."modules/Leads/Leads.class.php");
	include_once($cfg_project_directory."modules/Leads/LeadConversion.class.php");
	include_once($cfg_project_directory."modules/Organization/Organization.class.php");
	include_once($cfg_project_directory."modules/Potentials/Potentials.class.php");
	include_once($cfg_project_directory."modules/Notes/Notes.class.php");
	include_once($cfg_project_directory."modules/Calendar/Calendar.class.php");
	include_once($cfg_project_directory."modules/Calendar/RecurrentEvents.class.php");
	include_once($cfg_project_directory."modules/Calendar/EventsReminder.class.php");

	include_once($cfg_project_directory."modules/Home/HomePageGraphs.class.php");
	include_once($cfg_project_directory."modules/Home/DashboardWidgetProcessor.class.php");

	include_once($cfg_project_directory."modules/Vendor/Vendor.class.php");
	include_once($cfg_project_directory."modules/Products/Products.class.php");

	include_once($cfg_project_directory."modules/Quotes/Quotes.class.php");
	include_once($cfg_project_directory."modules/SalesOrder/SalesOrder.class.php");
	include_once($cfg_project_directory."modules/Invoice/Invoice.class.php");
	include_once($cfg_project_directory."modules/Invoice/InvoicePayments.class.php");
	include_once($cfg_project_directory."modules/PurchaseOrder/PurchaseOrder.class.php");

	include_once($cfg_project_directory."modules/CustomView/CustomView.class.php");
	include_once($cfg_project_directory."modules/CustomView/CustomViewFields.class.php");
	include_once($cfg_project_directory."modules/CustomView/CustomViewFilter.class.php");
	
	include_once($cfg_project_directory."modules/Report/Report.class.php");
	include_once($cfg_project_directory."modules/Report/ReportSorting.class.php");
	include_once($cfg_project_directory."modules/Report/ReportModuleRel.class.php");
	include_once($cfg_project_directory."modules/Report/ReportFields.class.php");
	include_once($cfg_project_directory."modules/Report/ReportFilter.class.php");
	include_once($cfg_project_directory."modules/Report/ReportFolder.class.php");
	include_once($cfg_project_directory."modules/Report/CustomReport.class.php");

	include_once($cfg_project_directory."modules/Import/Import.class.php");
	include_once($cfg_project_directory."modules/Import/ContactsImport.class.php");
	include_once($cfg_project_directory."modules/Import/LeadsImport.class.php");
	include_once($cfg_project_directory."modules/Import/OrganizationImport.class.php");
	include_once($cfg_project_directory."modules/Import/PotentialsImport.class.php");
	include_once($cfg_project_directory."modules/Import/VendorImport.class.php");
	include_once($cfg_project_directory."modules/Import/ProductsImport.class.php");
	
	include_once($cfg_project_directory."modules/Queue/Queue.class.php");
	
	include_once($cfg_project_directory."modules/Project/Project.class.php");
	

	// Email module
	include_once($cfg_project_directory.THIRD_PARTY_LIB_PATH."/PHPMailer/class.phpmailer.php");
	include_once($cfg_project_directory."modules/Email/EmailTemplate.class.php");
	include_once($cfg_project_directory."modules/Email/SQEmailer.class.php");
	include_once($cfg_project_directory."modules/Email/Email.class.php");
	
	// Load all the different fields Object from the class folder which are essential for the CRM
	$it = new RecursiveDirectoryIterator($cfg_project_directory.'class/fields/fieldtypes/');
	foreach (new RecursiveIteratorIterator($it) as $file) {
		if (preg_match("/\.class\.php$/i", $file) && !preg_match("/^\./", $file)) {
			include_once($file);
		}
	}

	// DataDisplay Class
	include_once($cfg_project_directory."class/datadisplay/DataDisplay.class.php");

	//live feed
	include_once($cfg_project_directory."class/livefeed/LiveFeedQueue.class.php");
	include_once($cfg_project_directory."class/livefeed/LiveFeedDisplay.class.php");

	//inventory
	include_once($cfg_project_directory."class/inventory/Lineitems.class.php");
	
});

?>