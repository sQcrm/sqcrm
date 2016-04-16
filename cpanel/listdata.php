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
$mid = $_GET["mid"] ;
$module_namespace = $_GET["module_namespace"] ;
$lp = false ;
// if where is already in the query then no need to add the WHERE string
$method_param_used = false ;

$module_object_name = $module_namespace.'\\'.$m ;
$module_object = new $module_object_name() ;

$fields = $module_object->list_view_fields ;
$do_crmfields = new \CRMFields();
$fields_info = $do_crmfields->get_specific_fields_information($fields,$mid,true);
 
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
$hcol_count = 0 ; 
foreach ($fields_info as $field_name=>$info) {
	$ahColumns[$hcol_count++] = $field_name ;
}

$sql_start = 0 ;
$sql_limit = 50 ;
  
if (isset($_GET["iDisplayStart"]) && $_GET["iDisplayLength"] != '-1') {
	$sql_start = $_GET["iDisplayStart"] ; 
	$sql_limit = $_GET["iDisplayLength"] ; 
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

if(count($search_param) > 0) {
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

$module_object->get_list_query() ;
$qry = $module_object->getSqlQuery() ;
// Get the security parameter for the user and add to the where condition
$security_where = "";
$security_where = $_SESSION["do_cpanel_action_permission"]->get_cpanal_user_where_condition($module_object->table,$module_object->get_lookup_field());
if (strlen($sWhere) > 3) {
	$module_object->query($qry.$security_where.$sWhere,$query_params);
	$iTotal = $module_object->getNumRows();
} else {
	$module_object->query($qry.$security_where);
	$iTotal = $module_object->getNumRows();
}

if ($sql_start != '' && $sql_limit != -1) {
	$limit = " LIMIT ".$sql_start.", ".$sql_limit;
}

if ($sOrder != '') {
	$order_by = " ".$sOrder;
} else {
	if ($module_object->get_default_order_by() != "") {
		$order_by = " order by ".$module_object->get_default_order_by() ;
	}
}

$where = '';
if ($sWhere != '') {
	$where .= $sWhere ;
}

if ($sWhere != '') { 
	if (count($query_params) > 0)
		$module_object->query($qry.$security_where.$where,$query_params);
	else
		$module_object->query($qry.$security_where.$where);
	$iFilteredTotal = $module_object->getNumRows();
} else { $iFilteredTotal = $iTotal ; }

$qry = $qry.$security_where.$where.$order_by.$limit ;

if (count($query_params) > 0)
	$module_object->query($qry,$query_params);
else
	$module_object->query($qry);
	
$output = array(
	"sEcho"=>intval($_GET['sEcho']),
	"iTotalRecords"=>$iTotal,
	"iTotalDisplayRecords"=>$iFilteredTotal,
	"aaData"=>array()
);

if ($module_object->getNumRows() > 0) {
	$pkey = $module_object->primary_key ;
	while ($module_object->next()) {
		$row = array();
		foreach ($fields_info as $fields=>$info) {
			$fieldobject = 'FieldType'.$info["field_type"];
			$row[] = $do_crmfields->display_field_value($module_object->$fields,$info["field_type"],$fieldobject,$module_object,$mid,false);
		}
		$action_links = '';
		$action_links .= '<a href="/cpanel/modules/'.$m.'/detail?sqrecord='.$module_object->$pkey.'">'._('detail').'</a>';
		
		if ($action_links != '') {
			$row[] =  $action_links ;
		}
		if ($action_links == '')  $row[] = '';
		$output["aaData"][] = $row ;
	}
}
echo json_encode( $output );  
?>