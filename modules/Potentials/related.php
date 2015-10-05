<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Related information view 
* @author Abhik Chakraborty
* The related information is loaded using the data tables and data table needs JS code to load the data from server
* In the detail page when the related link is cliked then related_listview_entry.php is loaded inside div id "detail_view_section"
* The related page may have multiple data tables so each is loaded with the file related_listview_each_entry.php
* If the Ajax request comes with an additional parameter "related_record_id" then we will only load that particular related information
* The related information regarding module and methods are stored in an array $related_data_information and by looping through each
* datatabele is loaded in "related_listview_each_entry"
* @see view/related_listview_each_entry.php
* @see view/related_listview_entry.php
* @see view/related_listview.php
* @see class/core/CRMRelatedInformation.class.php
*/  

$do_related_info = new CRMRelatedInformation();

if($_GET["related_record_id"] == 0 || $_GET["related_record_id"] == ''){
  $related_data_information = $do_related_info->get_related_information($module_id);
}
if(isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true){
  if(isset($_GET["related_record_id"]) && (int)$_GET["related_record_id"] > 0 ){
    $idrelated_information = (int)$_GET["related_record_id"] ;
    $sqcrm_record_id = (int)$_GET["referrer_sqcrm_record_id"];
    $do_related_info->getId($idrelated_information);
    $key = $do_related_info->related_module;
    $val["id"] = $idrelated_information;
    $val["method"] = $do_related_info->method_name ;
    $val["heading"] = $do_related_info->heading ;
    require_once('view/related_listview_each_entry.php');
  }else{
    require_once('view/related_listview_entry.php');
  }
}else{
  require_once('view/related_listview.php');
}
?>