<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class DataDisplay 
*	@author Abhik Chakraborty
*/
	

class DataDisplay extends DataObject {
	public $table = "";
	public $primary_key = "";

	/* list object of the module usually a session persistent object */
	protected $ds_object = '';

	/* data table display sql start index */
	protected $ds_sql_start = 0 ;

	/* datatable display sql end index */
	protected $ds_sql_end = 50 ;

	/* data table display sql max index */
	protected $ds_sql_max = 50;

	/* stores the field information for the datatable display */
	protected $ds_fields_info = array() ;

	/* stores the order by information for the datatable display */
	protected $ds_order_by = '';
	
	/* stores the security_where for the datatable display */
	protected $ds_list_security = '';

	/* stores the where condition for the datatable display */
	protected $ds_where_cond = '';
	
	protected $ds_search_params = array();
	
	/* stores the additional where clause */
	protected $ds_additional_where_cond = '';
	
	/* stores additional query param*/
	protected $ds_additional_query_params = array();
	
	/* show edit link */
	protected $ds_show_edit_link = true ;
	
	/* show deltail link */
	protected $ds_show_detail_link = true ;
	
	/* show delete link */
	protected $ds_show_delete_link = true ;
	
	/* show records selector ( checkbox )*/
	protected $ds_record_selector = true ;

	/**
	* function to set the object for the list view on datatable display
	* @param object $object
	*/
	public function set_ds_object($object) {
		$this->ds_object = $object ;
	}

	/**
	* gets the object , usually the persistent object for the module for which the data is displayed
	* @return ds_object
	*/
	public function get_ds_object() {
		return $this->ds_object ;
	}

	/**
	* function to set the sql start limit
	* @param integer $start
	*/
	public function set_ds_sql_start($start) {
		$this->ds_sql_start = $start ;
	}

	/**
	* gets the sql limit start for the datatable display
	* @return ds_sql_start
	*/
	public function get_ds_sql_start() {
		return $this->ds_sql_start;
	}

	/**
	* function to set the sql max limit for the datatable display
	* @param integer $max
	*/
	public function set_ds_sql_max($max) {
		$this->ds_sql_max = $max;
	}
  
	/**
	* gets the sql max limit for the datatable display
	* @return ds_sql_max
	*/
	public function get_ds_sql_max() {
		return $this->ds_sql_max ;
	}

	/**
	* sets the field information for the module in datatable display
	* @param array $field_info
	*/
	public function set_ds_fields_info($field_info) {
		$this->ds_fields_info = $field_info ;
	}

	/**
	* gets the field information for the datatable display
	* @return ds_fields_info
	*/
	public function get_ds_fields_info() {       
		return $this->ds_fields_info ; 
	}

	/**
	* function to set the order by for the query
	* @param string $order_by
	*/
	public function set_ds_order_by($order_by) {
		$this->ds_order_by = $order_by ;
	}

	/**
	* gets the order by for the query in datatable display
	* @return ds_order_by
	*/
	public function get_ds_order_by() {
		return $this->ds_order_by ;
	}
    
	/**
	* function to set show/hide edit link
	* @param boolean $bool
	*/
	public function set_ds_show_edit_link($bool) {
		$this->ds_show_edit_link = $bool ;
	}
    
	/**
	* function to get the show/hide edit link value
	* @return boolean ds_show_edit_link 
	*/
	public function get_ds_show_edit_link() {
		return $this->ds_show_edit_link ;
	}
    
	/**
	* function to set the show/hide detail link
	* @param boolean $bool
	*/
	public function set_ds_show_detail_link($bool) {
		$this->ds_show_detail_link = $bool ;
	}
    
	/**
	* function to get the show/hide detail link value
	* @return boolean ds_show_detail_link 
	*/
	public function get_ds_show_detail_link() {
		return $this->ds_show_detail_link ;
	}
    
	/**
	* function to set the show/hide delete link
	* @param boolean $bool
	*/
	public function set_ds_show_delete_link($bool) {
		$this->ds_show_delete_link = $bool ;
	}
    
	/**
	* function to get the show/hide delete link value
	* @return boolean ds_show_delete_link
	*/
	public function get_ds_show_delete_link() {
		return $this->ds_show_delete_link ;
	}
    
	/**
	* function to set the records selector true/false
	* @param boolean $bool
	*/
	public function set_ds_show_record_selector($bool) {
		$this->ds_record_selector = $bool;
	}
    
	/**
	* function to get the records selector value 
	* @return boolean ds_record_selector
	*/
	public function get_ds_show_record_selector() {
		return $this->ds_record_selector ;
	}
	
	/**
	* function to set the sequery where 
	* usually related to the user who is viewing the data as per the roles and profile information
	* the condition will be added to the query to allow user for viewig which they are supposed to.
	* @param string $where
	*/
	public function set_ds_list_security($where) {
		$this->ds_list_security = $where ;
	}

	/**
	* gets the security where condition
	* @return ds_list_security
	*/
	public function get_ds_list_security() {
		return $this->ds_list_security;
	}

	/**
	* function to set the where condition related to filter search etc
	* @param string $where
	*/
	public function set_ds_where_cond($where) {
		$this->ds_where_cond = $where ;
	}

	/**
	* gets the where condition
	* @return ds_where_cond
	*/
	public function get_ds_where_cond() {
		return $this->ds_where_cond ;
	}
    
	/**
	* function to set the search params
	* @param array $param
	*/
	public function set_ds_search_params($params) {
		$this->ds_search_params = $params ; 
	}
    
	/**
	* function to get the search params
	* @return array ds_search_params
	*/
	public function get_ds_search_params() {
		return	$this->ds_search_params ;
	}
    
    public function set_ds_additional_where($where) {
		$this->ds_additional_where_cond = $where ;
    }
    
    public function get_ds_additional_where() {
		return $this->ds_additional_where_cond ;
    }
    
    public function set_ds_additional_query_param($param) {
		$this->ds_additional_query_params = $param ;
    }
    
    public function get_ds_additional_query_param() {
		return $this->ds_additional_query_params ;
    }
    
	/**
	* function to display the list data in datatable
	* the object for the viewing module must be set before the function call and all other member variables
	* @param integer $module_id
	* @param boolean $popup
	* @param string $primary_key
	* @see listdata.php, listdata_popup.php
	* The param $primary_key is used for a special purpose. When we use to view the related information we send 
	* the primary key value since if we see some related data for a module and if we do not send the primary key name
	* then the primary key will be used from the module instead of related modules
	* EX: if we want to see the related Contacts for a Prospect then we must send the primary key name 'idcontacts'
	* else it will take 'idpotential' as the primary key value. The query will not get the idpotentials since it will
	* run on Contacts and we do not want potential information on related tab.
	* @see listdata_related.php
	*/
	public function display_data($module_id,$popup = false,$primary_key='') {
		$do_crmfields = new CRMFields();
		$object = $this->get_ds_object();
		$module_info = $_SESSION["do_module"]->get_modules_with_full_info();
		$module_name = $module_info[$module_id]["name"];
		//Get the list query from the object
		$qry = $object->getSqlQuery();
		
		//security where condition
		$security_where = $this->get_ds_list_security();
			
		$group_by = '';
		if (property_exists($object,"list_query_group_by") === true && $object->list_query_group_by != '') {
			$group_by = " group by ".$object->list_query_group_by ;
		}
		//echo $qry.$security_where.$group_by;exit;
		if ($object->get_list_tot_rows() == 0 ) {
			if (count($this->get_ds_additional_query_param()) > 0) {
				//echo '<br />1 '.$qry.$security_where.$this->get_ds_additional_where().$group_by ;
				$this->query($qry.$security_where.$this->get_ds_additional_where().$group_by,$this->get_ds_additional_query_param());
			} elseif (strlen($this->get_ds_additional_where()) > 3) { 
				//echo '<br />2 '.$qry.$security_where.$this->get_ds_additional_where().$group_by ;
				$this->query($qry.$security_where.$this->get_ds_additional_where().$group_by);
			} else {
				//echo '<br />3 '.$qry.$security_where.$group_by ;
				$this->query($qry.$security_where.$group_by);
			}
			//Get the total number of records
			$iTotal = $this->getNumRows();
			$object->set_list_tot_rows($iTotal) ; 
		} else {
			$iTotal = $object->get_list_tot_rows(); 
		}
			
		if ($this->get_ds_where_cond() != '') {
			if ($object->get_list_tot_rows() == 0 ) {
				if (strlen($this->get_ds_additional_where()) > 3) {
					//echo '<br />11 '.$qry.$security_where.$this->get_ds_additional_where().$this->get_ds_where_cond().$group_by ;
					$this->query($qry.$security_where.$this->get_ds_additional_where().$this->get_ds_where_cond().$group_by,$this->get_ds_search_params());
				} else {
					//echo '<br />12 '.$qry.$security_where.$this->get_ds_where_cond().$group_by ;
					$this->query($qry.$security_where.$this->get_ds_where_cond().$group_by,$this->get_ds_search_params());
				}
				//Get the total number of records
				$iTotal = $this->getNumRows();
				$object->set_list_tot_rows($iTotal) ; 
			} else {
				$iTotal = $object->get_list_tot_rows(); 
			}
		} else {
			if (strlen($this->get_ds_additional_where()) > 3) {
				//print_r($this->get_ds_search_params());
				//echo '<br />13 '.$qry.$security_where.$this->get_ds_additional_where().$group_by;
				$this->query($qry.$security_where.$this->get_ds_additional_where().$group_by,$this->get_ds_search_params());
			} else {
				//echo '<br />14 '.$qry.$security_where.$group_by;
				$this->query($qry.$security_where.$group_by);
			}
			//Get the total number of records
			$iTotal = $this->getNumRows();
			$object->set_list_tot_rows($iTotal) ; 
		}
			
		// Get the query limit and then add to the query
		$limit = '';
		$sql_start = $this->get_ds_sql_start();
		$sql_max = $this->get_ds_sql_max();
		if ($sql_start != '' && $sql_max != -1) {
			$limit = " LIMIT ".$sql_start.", ".$sql_max;
		}
			
		// Get order by and then add to the query
		if ($this->get_ds_order_by() != '') {
			$order_by = " ".$this->get_ds_order_by();
		} else {
			if ($object->get_default_order_by() != "") {
				$order_by = " order by ".$object->get_default_order_by() ;
			}
		}
			
		// Get the where condition for the filter
		$where = '';
		
		if (strlen($this->get_ds_additional_where()) > 3) {
			$where .= $this->get_ds_additional_where() ;
		}
		
		if ($this->get_ds_where_cond() != '') {
			$where .= $this->get_ds_where_cond() ;
		}
		
		//The following part is ugly and we need something on Radria Core to use SELECT FOUND_ROWS()
		if ($this->get_ds_where_cond() != '') { 
			if (count($this->get_ds_search_params()) > 0)
				$this->query($qry.$security_where.$where.$group_by,$this->get_ds_search_params());
			else
				$this->query($qry.$security_where.$where.$group_by);
			$iFilteredTotal = $this->getNumRows();
		} else { $iFilteredTotal = $iTotal ; }
		
		//And finally execute the query
		$qry = $qry.$security_where.$where.$group_by.$order_by.$limit ;
		
		if (count($this->get_ds_search_params()) > 0)
			$this->query($qry,$this->get_ds_search_params());
		else
			$this->query($qry);
			
		$output = array(
			"sEcho"=>intval($_GET['sEcho']),
			"iTotalRecords"=>$iTotal,
			"iTotalDisplayRecords"=>$iFilteredTotal,
			"aaData"=>array()
		);
			
		$edit = false ;
		$delete = false ;
		$detail = false ;
		
		if ($this->get_ds_show_edit_link() === true && $popup === false && $_SESSION["do_crm_action_permission"]->action_permitted('edit',$module_id) === true) {
			$edit = true ;
		}
		if ($this->get_ds_show_delete_link() === true && $popup === false && $_SESSION["do_crm_action_permission"]->action_permitted('delete',$module_id) === true) {
			$delete = true ;
		}
		if ($this->get_ds_show_detail_link() === true && $popup === false && $_SESSION["do_crm_action_permission"]->action_permitted('view',$module_id) === true) {
			$detail = true ;
		}
			
		if ($this->getNumRows() > 0) {
			$fields_info = $this->get_ds_fields_info();
			if ($primary_key == '') {
				$pkey = $object->primary_key ;
			} else {
				$pkey = $primary_key ;
			}
			while ($this->next()) {
				$row = array();
				if ($this->get_ds_show_record_selector() === true ) {
					if ($popup === true) {
							$retrun_fields = $object->popup_selection_return_field;
							$retrun_field_list = explode(",",$retrun_fields);
							$retrun_field = '';
							$cnt_return_fields = 0 ;
							foreach ($retrun_field_list as $retrun_fields) {
								if($cnt_return_fields > 0 ) $retrun_field .= ' ';
								$retrun_field .= $this->$retrun_fields;
								$cnt_return_fields++;
							}
							//$retrun_field
							if (isset($_GET["special_field"]) && $_GET["special_field"] == 'yes') {
								$special_field = $_GET["special_field"] ;
								$special_field_name = $_GET["special_field_name"] ;
								$chk_input = '<input type="checkbox" data-dismiss="modal" id="chk_popup'.$this->$pkey.'" onclick="return return_popup_selected_special(\'chk_popup'.$this->$pkey.'\');" value="'.$this->$pkey.'::'.$retrun_field.'">';
							} elseif ($_REQUEST["line_item"] == 'yes') {
								$chk_input = '<input type="checkbox" data-dismiss="modal" id="chk_popup'.$this->$pkey.'" onclick="return return_popup_line_item(\'chk_popup'.$this->$pkey.'\',\''.$module_name.'\',\''.$_REQUEST["line_level"].'\');" value="'.$this->$pkey.'">';
							} elseif (isset($_REQUEST["copy_org_address"])  && $_REQUEST["copy_org_address"]== 'yes') {
								$chk_input = '<input type="checkbox" data-dismiss="modal" id="chk_popup'.$this->$pkey.'" onclick="return return_popup_copy_org_addr(\'chk_popup'.$this->$pkey.'\',\''.$_REQUEST["target_module"].'\');" value="'.$this->$pkey.'::'.$retrun_field.'">';
							} elseif (isset($_REQUEST['copy_cnt_address']) && $_REQUEST['copy_cnt_address'] =='yes') {
								$chk_input = '<input type="checkbox" data-dismiss="modal" id="chk_popup'.$this->$pkey.'" onclick="return copy_cnt_address(\'chk_popup'.$this->$pkey.'\',\''.$_REQUEST["target_module"].'\');" value="'.$this->$pkey.'::'.$retrun_field.'">';
							} else {
								$chk_input = '<input type="checkbox" data-dismiss="modal" id="chk_popup'.$this->$pkey.'" onclick="return return_popup_selected(\'chk_popup'.$this->$pkey.'\');" value="'.$this->$pkey.'::'.$retrun_field.'">';
							}
					} else {
						if ($module_id == 7 && $this->$pkey == 1) {
							$chk_input = '<input type="hidden" name="chk[]" value="'.$this->$pkey.'">';
						} else {
							$chk_input = '<input type="checkbox" class="sel_record" name="chk[]" value="'.$this->$pkey.'">';
						}
					}
					$row[] = $chk_input;
				} else {
					$chk_input = '<input type="hidden" name="chk[]" value="'.$this->$pkey.'">';
					$row[] = $chk_input;
				}
				foreach ($fields_info as $fields=>$info) {
					$fieldobject = 'FieldType'.$info["field_type"];
					$row[] = $do_crmfields->display_field_value($this->$fields,$info["field_type"],$fieldobject,$this,$module_id);
				}
				$action_links = '';
				if ($detail === true) {
					if ($action_links != '') $action_links.= '&nbsp;|&nbsp;';
					$action_links .= '<a href="/modules/'.$module_name.'/detail?sqrecord='.$this->$pkey.'">'._('detail').'</a>';
				}
				if ($edit === true) {
					if ($action_links != '') $action_links.= '&nbsp;|&nbsp;';
					$action_links .= '<a href="/modules/'.$module_name.'/edit?sqrecord='.$this->$pkey.'">'._('edit').'</a> ';
				}
				if ($delete === true) {
					if ($module_id == 7 ) {
						if ($this->$pkey != 1) { // admin user delete is not allowed
							if ($action_links != '') $action_links.= '&nbsp;|&nbsp;';
							$action_links .= '<a class="delete_entity_user" id="'.$this->$pkey.'" href="#">'._('delete').'</a>';
						}
					} else {
						if ($action_links != '') $action_links.= '&nbsp;|&nbsp;';
						$action_links .= '<a class="delete_entity" id="'.$this->$pkey.'" href="#">'._('delete').'</a>';
					}
				}
				if ($action_links != '') {
					$row[] =  $action_links ;
				}
				if ($action_links == '')  $row[] = '';
				$output["aaData"][] = $row ;
			}
		}
		echo json_encode( $output );
	}   
}