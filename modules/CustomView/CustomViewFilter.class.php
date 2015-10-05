<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class CustomViewFilter 
* @author Abhik Chakraborty
*/ 
	

class CustomViewFilter extends DataObject {
	public $table = "custom_view_filter";
	public $primary_key = "idcustom_view_filter";
	
	
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
	
	/**
	* get the fields for date filter by module for custom view 
	* @param integer $idmodule
	* @return array
	*/
	public function get_date_filter_fields($idmodule) {
		$qry = "select `idfields`,`field_name`,`field_label` from fields where `idmodule` = ? and `field_type` = 9 "; 
		$this->query($qry,array((int)$idmodule));
		$return_array = array();
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$data = array("idfields"=>$this->idfields,"field_label"=>$this->field_label);
				$return_array[] = $data ;
			}
		}
		return $return_array ; 
	}
	
	/**
	* get the date filter options 
	* @return array
	* @see ViewFilterUtils::get_date_filter_otions()
	*/
	public function get_custom_view_date_filter_options() {
		return ViewFilterUtils::get_date_filter_otions();
	}
	
	/**
	* get the advanced filter options
	* @return array
	* @see ViewFilterUtils::get_advanced_filter_options()
	*/
	public function get_advanced_filter_options() {
		return ViewFilterUtils::get_advanced_filter_options();
	}
	
	/**
	* get the saved date filter options for the custom view
	* @return string
	*/
	public function get_saved_date_filter() {
		$qry = "
		select 
		cvdf.*,
		f.field_name,
		f.field_label,
		f.table_name,
		f.field_type,
		f.idmodule
		from `custom_view_date_filter` cvdf
		join fields f on f.idfields = cvdf.idfield 
		where `idcustom_view` = ?
		";
		return $qry ;
	}
	
	/**
	* parse the date filter for custom view
	* @param integer $idcustom_view
	* @param array $data
	* @see ViewFilterUtils::get_parsed_date_filter()
	*/
	public function parse_custom_view_date_filter($idcustom_view,$data=array()) {
		return ViewFilterUtils::get_parsed_date_filter($idcustom_view,$this,$data);
	}
	
	/**
	* parse the advanced filter options for the query condition 
	* @param integer $idcustom_view
	* @return mix
	* @see ViewFilterUtils::get_adv_filter_conditions()
	*/
	public function parse_custom_view_advanced_filter($idcustom_view) { 
		$this->query($this->get_saved_advanced_filter(),array((int)$idcustom_view));
		return ViewFilterUtils::parse_advanced_filter($this);
	}
	
	/**
	* function to add custom view date filter
	* @param integer $idcustom_view
	* @param integer $idfield
	* @param integer $filter_type
	* @param string $start_date
	* @param string $end_date
	* @return void
	*/
	public function add_custom_view_date_filter($idcustom_view,$idfield,$filter_type,$start_date,$end_date) {
		if ((int)$idfield > 0 && (int)$filter_type > 0) {
			$start_date = '';
			$end_date = '';
			if ($start_date != '' && $end_date != '') {
				$start_date = FieldType9::convert_before_save($start_date);
				$end_date = FieldType9::convert_before_save($end_date);
			}
			
			$this->insert(
			"custom_view_date_filter",
				array(
					"idcustom_view"=>$idcustom_view,
					"idfield"=>$idfield,
					"filter_type"=>$filter_type,
					"start_date"=>$start_date,
					"end_date"=>$end_date
				)
			);
		}
	}
	
	/**
	* function to add custom view advanced filter options
	* @param integer $idcustom_view
	* @param array $adv_filter_data
	* @return void
	*/
	public function add_custom_view_adv_filter($idcustom_view,$adv_filter_data) {
		if ((int)$idcustom_view > 0) {
			if ($adv_filter_data["cv_adv_fields_1"] != '' && $adv_filter_data["cv_adv_fields_type_1"] != '0' && $adv_filter_data["cv_adv_fields_val_1"] != '') {
				$this->add_each_custom_view_advanced_filter($idcustom_view,$adv_filter_data["cv_adv_fields_1"],$adv_filter_data["cv_adv_fields_type_1"],$adv_filter_data["cv_adv_fields_val_1"]);
			}
			
			if ($adv_filter_data["cv_adv_fields_2"] != '' && $adv_filter_data["cv_adv_fields_type_2"] != '0' && $adv_filter_data["cv_adv_fields_val_2"] != '') {
				$this->add_each_custom_view_advanced_filter($idcustom_view,$adv_filter_data["cv_adv_fields_2"],$adv_filter_data["cv_adv_fields_type_2"],$adv_filter_data["cv_adv_fields_val_2"]);
			}
			
			if ($adv_filter_data["cv_adv_fields_3"] != '' && $adv_filter_data["cv_adv_fields_type_3"] != '0' && $adv_filter_data["cv_adv_fields_val_3"] != '') {
				$this->add_each_custom_view_advanced_filter($idcustom_view,$adv_filter_data["cv_adv_fields_3"],$adv_filter_data["cv_adv_fields_type_3"],$adv_filter_data["cv_adv_fields_val_3"]);
			}
			
			if ($adv_filter_data["cv_adv_fields_4"] != '' && $adv_filter_data["cv_adv_fields_type_4"] != '0' && $adv_filter_data["cv_adv_fields_val_4"] != '') {
				$this->add_each_custom_view_advanced_filter($idcustom_view,$adv_filter_data["cv_adv_fields_4"],$adv_filter_data["cv_adv_fields_type_4"],$adv_filter_data["cv_adv_fields_val_4"]);
			}
			
			if ($adv_filter_data["cv_adv_fields_5"] != '' && $adv_filter_data["cv_adv_fields_type_5"] != '0' && $adv_filter_data["cv_adv_fields_val_5"] != '') {
				$this->add_each_custom_view_advanced_filter($idcustom_view,$adv_filter_data["cv_adv_fields_5"],$adv_filter_data["cv_adv_fields_type_5"],$adv_filter_data["cv_adv_fields_val_5"]);
			}
		}
	}
	
	/**
	* function to add each advanced filter 
	* @param integer $idcustom_view
	* @param integer $field
	* @param integer $type
	* @param string $val
	* @return void
	*/
	public function add_each_custom_view_advanced_filter($idcustom_view,$field,$type,$val) {
		$this->addNew();
		$this->idcustom_view = $idcustom_view;
		$this->filter_type = $type ;
		$this->filter_field = $field ;
		$this->filter_value = $val ;
		$this->add() ;
	}
	
	/**
	* function to update the custom view date filter
	* @param integer $idcustom_view
	* @param integer $idfield
	* @param integer $filter_type
	* @param string $start_date
	* @param string $end_date
	* @return void
	*/
	public function update_custom_view_date_filter($idcustom_view,$idfield,$filter_type,$start_date,$end_date) {
		$qry = "
		select * from `custom_view_date_filter`
		where `idcustom_view` = ?
		";
		$this->query($qry,array($idcustom_view));
		if ($this->getNumRows() > 0) {
			if ((int)$idfield > 0 && (int)$filter_type > 0) {
				$cv_start_date = '';
				$cv_end_date = '';
				if ($start_date != '' && $end_date != '') {
					$cv_start_date = FieldType9::convert_before_save($start_date);
					$cv_end_date = FieldType9::convert_before_save($end_date);
				}
				$qry = "
				update `custom_view_date_filter`
				`idfield` = ?,
				`filter_type` = ?,
				`start_date` = ?,
				`end_date` = ?
				where 
				`idcustom_view` = ?
				";
				$this->query($idfield,$filter_type,$cv_start_date,$cv_end_date,$idcustom_view);
			}
		} else {
			$this->add_custom_view_date_filter($idfield,$filter_type,$cv_start_date,$cv_end_date,$idcustom_view) ;
		}
	}
	
	/**
	* function to update the custom view advanced filter
	* @param integer $idcustom_view
	* @param array $adv_filter_data
	* @return void
	* @see self::add_custom_view_adv_filter()
	*/
	public function update_custom_view_adv_filter($idcustom_view,$adv_filter_data) {
		$qry = "
		delete from `custom_view_filter`
		where `idcustom_view` = ?
		";
		$this->query($qry,array($idcustom_view));
		$this->add_custom_view_adv_filter($idcustom_view,$adv_filter_data);
	}
	
	/**
	* function to get the date filter information for custom view
	* @param integer $id
	* @return mix
	*/
	public function get_date_filter_information($id) {
		if ((int)$id > 0) {
			$this->query($this->get_saved_date_filter(),array($id));
			if ($this->getNumRows() > 0) {
				$this->next() ;
				return array(
					"idfield" => $this->idfield,
					"filter_type" => $this->filter_type,
					"start_date" => $this->start_date,
					"end_date" => $this->end_date
				);
			} else {
				return false ;
			}
		} else {
			return false ;
		}
	}
	
	/**
	* get the saved advanced filter query
	* @return string
	*/
	public function get_saved_advanced_filter() {
		$qry = "
		select 
		cvf.*,
		f.field_name,
		f.field_label,
		f.table_name,
		f.field_type,
		f.idmodule
		from custom_view_filter cvf
		join fields f on f.idfields = cvf.filter_field
		where cvf.idcustom_view = ?
		";
		return $qry ;
	}
	
	/**
	* get the saved advanced filter information
	* @param integer $id
	* @return mix
	*/
	public function get_saved_advanced_filter_information($id) {
		if ((int)$id > 0) {
			$this->query($this->get_saved_advanced_filter(),array($id)) ;
			if ($this->getNumRows() > 0) {
				$return_array = array() ;
				$cnt= 1;
				while ($this->next()) {
					$return_array["cv_adv_fields_$cnt"] = $this->filter_field ;
					$return_array["cv_adv_fields_type_$cnt"] = $this->filter_type ;
					$return_array["cv_adv_fields_val_$cnt"] = $this->filter_value ;
					$cnt++ ;
				}
				return array("advanced_filter_options"=>$return_array) ;
			} else {
				return false ;
			}
		} else {
			return false ;
		}
	}
	
}