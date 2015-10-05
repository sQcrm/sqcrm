<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Used for loading the data using the jquery datatable server side proceesing modal
* Gets the module name as a GET and gets the list query for the module
* The fileds information is stored in the memmber array list_view_field_information of the object
* @see popups/listdata_popup_modal
*/

include_once("config.php") ;
$m = $_GET["m"];
$object = '' ;

$mid = $_SESSION["do_module"]->get_idmodule_by_name($m,$_SESSION["do_module"]);    

$do_crm_list_view = new CRMListView();
$object = $do_crm_list_view->get_list_view_object($m,"popup");
  
//--handle FieldType142
if ($_REQUEST["org_dependent"] == 'yes' && (int)$_REQUEST["idorganization"] > 0) {
	$do_organization = new Organization();
	$do_organization->get_contacts((int)$_REQUEST["idorganization"]);
	$object->setSqlQuery($do_organization->getSqlQuery());
} else {
	$object->get_list_query();
}

$entity_table_name = $object->getTable() ;
$fields_info = $object->list_view_field_information ;
  
/**
* FIXME
* For some reason when the array index starts with 0 the text box search works well but the asc/desc on the header does not
* So $aColumns array is used for text search and adding a new array $ahColumns for header sort
*/
$aColumns = array();
$col_count = 0 ; 
foreach ($fields_info as $field_name=>$info) {
	$aColumns[$col_count++] = $field_name ;
}
    
$ahColumns = array();
$hcol_count = 1 ; 
foreach ($fields_info as $field_name=>$info) {
	$ahColumns[$hcol_count++] = $field_name ;
}
    
  
$do_data_display = new DataDisplay();
if (isset($_GET["iDisplayStart"]) && $_GET["iDisplayLength"] != '-1') {
	$do_data_display->set_ds_sql_start($_GET["iDisplayStart"]);
	$do_data_display->set_ds_sql_max($_GET["iDisplayLength"]);
}
$sOrder = "";

if (isset( $_GET['iSortCol_0'])) {
	$sOrder = "ORDER BY  ";
	for ($i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++) {
		if ($_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i])] == "true") {
			$sort_order = (strtolower($_GET['sSortDir_'.$i]) == 'desc' ? 'desc' : 'asc');
			$sOrder .= $ahColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ".$sort_order." ,";
		}
	}
	$sOrder = substr_replace( $sOrder, "", -2 );
	if ($sOrder == "ORDER BY") {
		$sOrder = "";
	}
}
$do_data_display->set_ds_order_by($sOrder);
  
// Get the security parameter for the user and add to the where condition
$security_where = "";
$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition($entity_table_name,$mid);

$do_data_display->set_ds_list_security($security_where);
  

/* Filtering - NOTE this does not match the built-in DataTables filtering which does it
* word by word on any field. It's possible to do here, but concerned about efficiency
* on very large tables, and MySQL's regex functionality is very limited
*/
$sWhere = "";
$search_param = array() ;
if ($_GET['sSearch'] != "") {
	$sWhere .= " AND (";
	for ($i=0;$i<count($aColumns);$i++) {
		if ($i == (count($aColumns)-1)) {
			if ($aColumns[$i] == 'assigned_to') {
				$sWhere .= " `user`.`user_name` LIKE ? OR `group`.`group_name` LIKE ? )";
				$search_param[] = '%'.$_GET['sSearch'].'%';
				$search_param[] = '%'.$_GET['sSearch'].'%';
			} else {
				$sWhere .= " ".$fields_info[$aColumns[$i]]["table"].".".$aColumns[$i]." LIKE ?  )";
				$search_param[] = '%'.$_GET['sSearch'].'%';
			}
		} else {
			if ($aColumns[$i] == 'assigned_to') {
				$sWhere .= "`user`.`user_name` LIKE ? OR `group`.`group_name` LIKE ? OR ";
				$search_param[] = '%'.$_GET['sSearch'].'%';
				$search_param[] = '%'.$_GET['sSearch'].'%';
			} else {
				$sWhere .= " ".$fields_info[$aColumns[$i]]["table"].".".$aColumns[$i]." LIKE ? OR ";
				$search_param[] = '%'.$_GET['sSearch'].'%';
			}
		}
	}      
}
  
for ($i=0 ; $i<$_GET['iColumns'] ; $i++) {
	if ($_GET['sSearch_'.$i] != '') {
		if ($sWhere != "") {
			$sWhere .= " AND ";
		} else {
			$sWhere .= "WHERE ";
		}
	}
}
$do_data_display->set_ds_where_cond($sWhere);
$do_data_display->set_ds_search_params($search_param);
$do_data_display->set_ds_fields_info($fields_info);
$do_data_display->set_ds_object($object);
$do_data_display->display_data($mid,true);
?>