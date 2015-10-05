<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* import_step3.php, will read the uploaded csv file in step1 and will parse it for mapping
* @author Abhik Chakraborty
*/
?>
<?php
if (isset($_REQUEST["start"]) && (int)$_REQUEST["start"] > 0) {
	$start = (int)$_REQUEST["start"] ;
} else {
	$start = 0 ; 
}

$import_module_id = $_SESSION["do_import"]->get_import_module_id();

switch ($import_module_id) {
	case 3 :
		$import_object = new LeadsImport();
		$list_special_object = "LeadsImport";
		break;
	case 4 :
		$import_object = new ContactsImport();
		$list_special_object = "ContactsImport";
		break;  
	case 5 :
		$import_object = new PotentialsImport();
		$list_special_object = "PotentialsImport";
		break;
	case 6 :
		$import_object = new OrganizationImport();
		$list_special_object = "OrganizationImport";
		break;
	case 11 :
		$import_object = new VendorImport();
		$list_special_object = "VendorImport";
		break;
	case 12 :
		$import_object = new ProductsImport();
		$list_special_object = "ProductsImport";
		break;
}

if ($_SESSION["do_import"]->get_csv_full_length() > $start) {
	$total_per_loop = $_SESSION["do_import"]->get_max_record_insert_per_loop() ;
	$total_records = $_SESSION["do_import"]->get_csv_full_length() ;
	if ($total_records - $start > $total_per_loop)
		$next = $start+ $total_per_loop ;
	else
		$next = $total_records - $start ;
  
	require_once('view/import_step3_view.php');
}
    
if ($_SESSION["do_import"]->import_data($import_object,$start) === true) {
	$start = $_SESSION["do_import"]->get_max_record_insert_per_loop() + $start ;
	$next_page = NavigationControl::getNavigationLink("Import","import_step3");
?>
<script type="text/javascript">
window.setInterval(
  function(){
    window.location.href = "<?php echo $next_page."?start=".$start; ?>" ; 
  },9000);
</script>
<?php
} else {
?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12">
      <div class="datadisplay-outer">
        <div class="alert alert-success alert-block" style="height:20px;margin-top:20px;margin-left:200px;margin-right:200px;">
          <b>
            <?php
				echo _('Data import completed. If you discard the current import then the imported data will be deleted.');
            ?>
          </b>
        </div>
      </div>
      <div class="datadisplay-outer">
      <div class="left_600">
      <?php
		$e_discard = new Event("do_import->eventDiscardImport");
      ?>
      <a href="/<?php echo $e_discard->getUrl();?>" class="btn btn-inverse">
        <i class="icon-white icon-remove-sign"></i> <?php echo _('Discard');?></a>  
      <?
        $e_finish = new Event("do_import->eventFinishImport");
      ?>
      <a href="/<?php echo $e_finish->getUrl();?>" class="btn btn-primary">
        <?php echo _('Done');?></a>  
      </div>
      <div class="clear_float"></div>
      <hr class="form_hr">
      <div class="clear_float"></div>
      </div>
<?php
	$do_crm_list_view = new CRMListView();
	$fields_info = $do_crm_list_view->get_listview_field_info($_SESSION["do_module"]->modules_full_details[$import_module_id]["name"],$import_module_id,"list");
	if(!is_object($_SESSION[$list_special_object])) {
		$ImportModule = new $list_special_object();
		$ImportModule->sessionPersistent($list_special_object,"logout.php", TTL);
	}
	$list_special = true;
	$lp_mid = $import_module_id;
	$_SESSION[$list_special_object]->list_view_field_information = $fields_info;
	$method = "list_imported_data";
	require_once('view/listview_entry.php');
}
?>