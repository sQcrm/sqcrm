<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Used for loading the data using the jquery datatable server side proceesing modal
* Gets the module name as a GET and gets the list query for the module
* The fileds information is stored in the memmber array list_view_field_information of the object
* @see view/listview.php
* @author Abhik Chakraborty
*/

include_once("config.php") ;
$m = $_GET["m"];
$object = '' ;
$mid = $_SESSION["do_module"]->get_idmodule_by_name($m,$_SESSION["do_module"]);  
$do_data_display = new DataDisplay();
$lp = false ;
// if where is already in the query then no need to add the WHERE string
$method_param_used = false ;

//custom view date and advanced filter setup
$custom_view_date_filter_qry = '';
$custom_view_adv_filter_qry = false;
$additional_where_condition = '';

if (isset($_GET["lp"]) && $_GET["lp"] == 'y' && isset($_GET["lp_object"]) && $_GET["lp_object"] != '') {
	$object = $_SESSION[$_GET["lp_object"]] ;
	$method = $_GET["method"];
	$mid = $_GET["lp_mid"];
	$method_param = $_GET["method_param"];
	$do_data_display->set_ds_show_edit_link(false);
	$do_data_display->set_ds_show_detail_link(false);
	$do_data_display->set_ds_show_delete_link(false);
	$do_data_display->set_ds_show_record_selector(false);
    if ($method_param != '' ) {
		$method_param = json_decode($method_param,true);
		if (is_array($method_param) && count($method_param) > 0 ) {
			call_user_func_array(array($object,$method),$method_param);
			$method_param_used = true ;
		} else {
			$object->$method();
		}
    } else {
		$object->$method();
    }
} else {
	$do_crm_list_view = new CRMListView();
	$object = $do_crm_list_view->get_list_view_object($m,"list");
	$object->get_list_query();
	
	//check if the custom view is on and get the date filter and advanced filter query
	if (isset($_REQUEST["custom_view_id"]) && (int)$_REQUEST["custom_view_id"] > 0) {
		$do_custom_view_filter = new CustomViewFilter() ;
		$additional_where_condition = '';
		$custom_view_date_filter_qry = $do_custom_view_filter->parse_custom_view_date_filter((int)$_REQUEST["custom_view_id"]);
		$custom_view_adv_filter_qry = $do_custom_view_filter->parse_custom_view_advanced_filter((int)$_REQUEST["custom_view_id"]);
		$additional_where_condition .= ' '.$custom_view_date_filter_qry;
		//print_r($custom_view_adv_filter_qry);
		if(false !== $custom_view_adv_filter_qry) {
			$additional_where_condition = ' '.$custom_view_adv_filter_qry["where"] ;
			$do_data_display->set_ds_additional_query_param($custom_view_adv_filter_qry["bind_params"]) ;
		}
		$do_data_display->set_ds_additional_where($additional_where_condition);
	}
}
  
$entity_table_name = $object->getTable() ;

//CRMListView::get_listview_field_info() sets the list_view_field_information
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
  
  
if (isset($_GET["iDisplayStart"]) && $_GET["iDisplayLength"] != '-1') {
	$do_data_display->set_ds_sql_start($_GET["iDisplayStart"]);
	$do_data_display->set_ds_sql_max($_GET["iDisplayLength"]);
}

$sOrder = "";
if (isset( $_GET['iSortCol_0'])) {
	$sOrder = "ORDER BY  ";
    for ($i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ) {
		if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ) {
			$sort_order = (strtolower($_GET['sSortDir_'.$i]) == 'desc' ? 'desc' : 'asc');
			if ($fields_info[$ahColumns[ intval( $_GET['iSortCol_'.$i] )]]["field_type"] == 131) {
				if ($mid == 6) {
					$sOrder .= " organization_member_of ".$sort_order .", ";
				} else {
					$sOrder .= " organization_name ".$sort_order .", ";
				}
			} elseif ($fields_info[$ahColumns[ intval( $_GET['iSortCol_'.$i] )]]["field_type"] == 130) {
				if ($mid == 4) {
					$sOrder .= " contact_report_to ".$sort_order .", ";
				} else {
					$sOrder .= " contact_name ".$sort_order .", ";
				}
			} elseif ($fields_info[$ahColumns[ intval( $_GET['iSortCol_'.$i] )]]["field_type"] == 150) {
				$sOrder .= " related_to_value ".$sort_order .", ";
			} else {
				$sOrder .= $ahColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ".$sort_order.", ";
			}
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
			if ($fields_info[$aColumns[$i]]["field_type"] == 15) { // assigned_to
				$sWhere .= " `user`.`user_name` LIKE ? OR `group`.`group_name` LIKE ? )";
				$search_param[] = '%'.$_GET['sSearch'].'%';
				$search_param[] = '%'.$_GET['sSearch'].'%';
			} elseif ($fields_info[$aColumns[$i]]["field_type"] == 131) { // organization
				if ($mid == 6) {
					//$sWhere .= " ".$fields_info[$aColumns[$i]]["table"].".".$aColumns[$i]." LIKE ? )";
					$sWhere .= " `org2`.`organization_name` like ? )";
					$search_param[] = '%'.$_GET['sSearch'].'%';
				} else {
					$sWhere .= " `organization`.`organization_name` LIKE ? )";            
					$search_param[] = '%'.$_GET['sSearch'].'%';
				}
			} elseif ($fields_info[$aColumns[$i]]["field_type"] == 130) {
				if ($mid == 4) {
					$sWhere .= " concat(`cnt2`.`firstname`,' ',`cnt2`.`lastname`) LIKE ? )";
					$search_param[] = '%'.$_GET['sSearch'].'%';          
				} else {
					$sWhere .= " concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) LIKE ? )";
					$search_param[] = '%'.$_GET['sSearch'].'%';
				}
			} elseif ($fields_info[$aColumns[$i]]["field_type"] == 150) {
				$sWhere .= " sqorg.organization_name LIKE ? OR concat(sqcnt.firstname,' ',sqcnt.lastname) LIKE ? )";
				$search_param[] = '%'.$_GET['sSearch'].'%';
				$search_param[] = '%'.$_GET['sSearch'].'%';
			} elseif ($fields_info[$aColumns[$i]]["field_type"] == 160) {
				if($mid == 12) {
					$sWhere .= " `vendor`.`vendor_name` like ? ";
					$search_param[] = '%'.$_GET['sSearch'].'%';
				}
			} else {
				$sWhere .= " ".$fields_info[$aColumns[$i]]["table"].".".$aColumns[$i]." LIKE ? )";
				$search_param[] = '%'.$_GET['sSearch'].'%';
			}
		} else {
			if ($fields_info[$aColumns[$i]]["field_type"] == 15) { // assigned_to
				$sWhere .= " `user`.`user_name` LIKE ? OR `group`.`group_name` LIKE ? OR ";
				$search_param[] = '%'.$_GET['sSearch'].'%';
				$search_param[] = '%'.$_GET['sSearch'].'%';
			} elseif ($fields_info[$aColumns[$i]]["field_type"] == 130) {
				if ($mid == 4) {
					$sWhere .= " concat(`cnt2`.`firstname`,' ',`cnt2`.`lastname`) LIKE ? OR ";
					$search_param[] = '%'.$_GET['sSearch'].'%'; 			
				} else {
					$sWhere .= " concat(`contacts`.`firstname`,' ',`contacts`.`lastname`)  LIKE ? OR ";
					$search_param[] = '%'.$_GET['sSearch'].'%'; 
				}
			} elseif ($fields_info[$aColumns[$i]]["field_type"] == 131) {
				if ($mid == 6) {
					$sWhere .= " `org2`.`organization_name` LIKE ? OR ";
					$search_param[] = '%'.$_GET['sSearch'].'%'; 
				} else {
					$sWhere .= " `organization`.`organization_name` LIKE ? OR ";
					$search_param[] = '%'.$_GET['sSearch'].'%'; 
				}
			} elseif ($fields_info[$aColumns[$i]]["field_type"] == 150) {
				$sWhere .= " sqorg.organization_name ? OR concat(sqcnt.firstname,' ',sqcnt.lastname) LIKE ? OR ";
				$search_param[] = '%'.$_GET['sSearch'].'%'; 
				$search_param[] = '%'.$_GET['sSearch'].'%'; 
			} elseif ($fields_info[$aColumns[$i]]["field_type"] == 160) {
				if ($mid == 12) {
					$sWhere .= " `vendor`.`vendor_name` like ? OR";
					$search_param[] = '%'.$_GET['sSearch'].'%';
				}
			} else {
				$sWhere .= " ".$fields_info[$aColumns[$i]]["table"].".".$aColumns[$i]." LIKE ? OR ";
				$search_param[] = '%'.$_GET['sSearch'].'%'; 
			}
		}
	}      
}

$query_params = array(); 

//print_r($custom_view_adv_filter_qry);
if (false !== $custom_view_adv_filter_qry) {
	if (count($custom_view_adv_filter_qry["bind_params"]) > 0) {
		$query_params = $custom_view_adv_filter_qry["bind_params"] ;
		if (count($search_param) > 0) {
			foreach ($search_param as $key=>$val) {
				array_push($query_params,$val) ;
			}
		}
	}
} elseif(count($search_param) > 0) {
	$query_params = $search_param ;
}


for ($i=0 ; $i<$_GET['iColumns'] ; $i++ ) {
	if ($_GET['sSearch_'.$i] != '') {
		if ($sWhere != ""  ) {
			$sWhere .= " AND ";
		} else {
			$sWhere .= "WHERE ";
		}
	}
}

$do_data_display->set_ds_where_cond($sWhere);
$do_data_display->set_ds_search_params($query_params);
$do_data_display->set_ds_fields_info($fields_info);
$do_data_display->set_ds_object($object);
$do_data_display->display_data($mid); 
?>