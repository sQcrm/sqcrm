<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class CustomViewFields 
* @author Abhik Chakraborty
*/ 
	

class CustomViewFields extends DataObject {
	public $table = "custom_view_fields";
	public $primary_key = "idcustom_view";
	
	
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
	
	/**
	* get custom vuew fields by id
	* @param integer $id
	* @return mix
	*/
	public function get_custom_view_fields($id) {
		if ((int)$id > 0) {
			$qry = "
			select 
			`cvf`.`custom_view_fields`,
			`cv`.`idmodule` from `custom_view_fields` `cvf` 
			join `custom_view` `cv` on `cv`.`idcustom_view` = `cvf`.`idcustom_view`
			where `cvf`.`idcustom_view` = ?
			" ;
			$this->query($qry,array($id)) ;
			if ($this->getNumRows() > 0) {
				$this->next() ;
				$fields = explode('::',$this->custom_view_fields) ; 
				return $fields ;
			} else {
				return false ;
			}
		} else {
			return false ;
		}
	}
	
	/**
	* function to get the custom view fields information by id
	* @param integer $id 
	* @return mix
	*/
	public function get_custom_view_fields_information($id) {
		$fields = $this->get_custom_view_fields($id);
		if (false !== $fields) {
			$idmodule = $this->idmodule ;
			$do_crm_fields = new CRMFields();
			return $do_crm_fields->get_specific_fields_information($fields,$idmodule,true,'idfields');
		} else {
			return false ;
		}
	}
	
	/**
	* function to add custom view fields
	* @param integer $idcustom_view
	* @param array $fields
	* @return void
	*/
	public function add_custom_view_fields($idcustom_view,$fields) {
		if (is_array($fields) && count($fields) > 0) {
			$this->addNew();
			$this->idcustom_view = $idcustom_view ;
			$this->custom_view_fields = implode('::',$fields) ;
			$this->add();
		}
	}
	
	/**
	* function to edit the custom view fields
	* @param integer $idcustom_view
	* @param array $fields
	*/
	public function update_custom_view_fields($idcustom_view,$fields) {
		if ((int)$idcustom_view > 0 && is_array($fields) && count($fields) > 0) {
			$cv_fields = implode('::',$fields) ; 
			$qry = "
			update `".$this->getTable()."`
			set 
			`custom_view_fields` = ?
			where 
			`idcustom_view` = ?
			";
			$this->query($qry,array($cv_fields,$idcustom_view));
		}
	}

}