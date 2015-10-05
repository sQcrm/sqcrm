<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class login audit
* @author Abhik Chakraborty
*/ 

class LoginAudit extends DataObject {
    
	public $table = "login_audit";
	protected $primary_key = "idlogin_audit";
	
	/* public array to hold the field information for the list view */
	public $list_view_field_information = array();
	
	/*variable to store the total of list query , does not consider the limit and where into account */
	protected $list_tot_rows = 0 ;
	
	/* default order by in the list view */
	protected $default_order_by = "`login_audit`.`idlogin_audit`  desc";
    
	/**
	* sets the list view total without filter condition
	* @param integer $tot 
	*/
	public function set_list_tot_rows($tot) {
		$this->list_tot_rows = $tot ;
	}

	/**
	* gets the total num of rows for the list query without filer condition
	* @return list_tot_rows
	*/
	public function get_list_tot_rows() {
		return $this->list_tot_rows ;
	}
    
	/**
	* function to get the default order by in list value
	* @return default_order_by
	*/
	public function get_default_order_by() {
		return $this->default_order_by ; 
	}

	/**
	* Login audit method to keep track of logged in/ logout user detail as history
	* @param string $action 
	* @param integer $iduser 
	*/
	public function do_login_audit($action="Login", $iduser = "") {
		if ($iduser == "") { $iduser = $_SESSION["do_user"]->iduser ; }
		$this->addNew();
		$this->action_date = date("Y-m-d h:i:s");
		$this->ip_address = CommonUtils::get_user_ip_address();
		$this->action = $action;
		$this->iduser = $iduser;
		$this->add();
	}
    
	/**
	* function to get the login audit for user
	* @param integer $iduser
	*/
	public function get_login_audit($iduser) {
		$qry = "
		select idlogin_audit,
		ip_address,
		action_date,
		action
		from ".$this->getTable()." where iduser = ".(int)$iduser;				
		$this->setSqlQuery($qry);
	}
}