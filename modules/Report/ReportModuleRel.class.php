<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ReportModuleRel 
* @author Abhik Chakraborty
*/ 
	

class ReportModuleRel extends DataObject {
	public $table = "report_module_rel";
	public $primary_key = "idreport_module_rel";
	
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
	
	/**
	* get primary modules
	* @return array
	*/
	public function get_primary_modules() {
		return array(
			2=>_('Calendar'),
			3=>_('Leads'),
			4=>_('Contacts'),
			5=>_('Potentials'),
			6=>_('Organization'),
			11=>_('Vendor'),
			12=>_('Products'),
			13=>_('Quotes'),
			14=>_('Sales Order'),
			15=>_('Invoice'),
			16=>_('Purchase Order')
		);
	}
	
	/**
	* get secondary modules by primary module id
	* @param integer $primary_module_id
	* @return array
	*/
	public function get_secondary_modules($primary_module_id) {
		$secondary_module = array(
			2=>array(
				3=>_('Leads'),
				4=>_('Contacts'),
				5=>_('Potentials'),
				6=>_('Organization')
			),
			3=>array(),
			4=>array(
				6=>_('Organization'),
				5=>_('Potentials')
			),
			5=>array(
				4=>_('Contacts'),
				6=>_('Organization')
			),
			6=>array(
				4=>_('Contacts'),
				5=>_('Potentials')
			),
			11=>array(
				12=>_('Products'),
				16=>_('Purchase Order')
			),
			12=>array(
				11=>_('Vendor')
			),
			13=>array(
				5=>_('Potentials'),
				6=>_('Organization'),
				12=>_('Products')
			),
			14=>array(
				4=>_('Contacts'),
				5=>_('Potentials'),
				6=>_('Organization'),
				12=>_('Products'),
				13=>_('Quotes')
			),
			15=>array(
				4=>_('Contacts'),
				5=>_('Potentials'),
				6=>_('Organization'),
				12=>_('Products'),
				14=>_('Sales Order')
			),
			16=>array(
				11=>_('Vendor'),
				12=>_('Products')
			)
		);
		return $secondary_module[$primary_module_id];
	}
	
	/**
	* get report modules by idreport
	* @param integer $idreport
	* @return array
	*/
	public function get_report_modules($idreport) {
		$qry = "
		select 
		rm.primary_module,
		rm.secondary_module,
		m1.name as primary_module_name,
		m2.name as secondary_module_name
		from report_module_rel rm
		left join module m1 on m1.idmodule = rm.primary_module
		left join module m2 on m2.idmodule = rm.secondary_module 
		where rm.idreport = ?
		";
		$this->query($qry,array($idreport));
		$return_array = array();
		$this->next();
		$return_array["primary"] = array("idmodule"=>$this->primary_module,"module_name"=>$this->primary_module_name);
		$return_array["secondary"] = array("idmodule"=>$this->secondary_module,"module_name"=>$this->secondary_module_name);
		return $return_array;
	}
	
	/**
	* add report module relation
	* @param integer $idreport
	* @param integer $primary_module
	* @param integer $secondary_module
	*/
	public function add_report_module_rel($idreport,$primary_module,$secondary_module) {
		$this->addNew();
		$this->idreport = $idreport;
		$this->primary_module = $primary_module;
		$this->secondary_module = $secondary_module;
		$this->add();
	}
	
	/**
	* update report module relation
	* @param integer $idreport
	* @param integer $primary_module
	* @param integer $secondary_module
	*/
	public function update_report_module_rel($idreport,$primary_module,$secondary_module) {
		$qry = "
		update `".$this->getTable()."`
		set `primary_module` = ?,
		`secondary_module` = ?
		where idreport = ?
		";
		$this->query($qry,array($primary_module,$secondary_module,$idreport));
	}
}