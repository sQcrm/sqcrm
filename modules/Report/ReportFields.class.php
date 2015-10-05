<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ReportFields 
* @author Abhik Chakraborty
*/ 
	

class ReportFields extends DataObject {
	public $table = "report_fields";
	public $primary_key = "idreport_fields";
	
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
	
	/**
	* get the fields by report fields
	* @param integer $idmodule
	* @return array
	*/
	public function get_module_fields_for_report($idmodule) {
		$do_crm_fields = new CRMFields();
		return $do_crm_fields->get_fieldinfo_grouped_by_block($idmodule,true);
		/*$qry = "
		select
		f.idfields,
		f.field_label,
		f.field_name,
		f.idblock,
		f.field_type,
		case 
			when b.block_label <> '' then b.block_label 
			else 'Other' 
		end as block_name
		from fields f
		left join block b on b.idblock = f.idblock
		where f.idmodule = ?
		order by 
		case when
		f.idblock <> 0 then 0 else 1 end,
		b.sequence,
		f.field_sequence
		" ;
		$this->query($qry,array($idmodule));
		$fields = array();
		while($this->next()){
			$fields[$this->idblock][$this->block_name][] = array(
				"idfields"=>$this->idfields,
				"field_label"=>$this->field_label,
				"field_name"=>$this->field_name,
				"field_type"=>$this->field_type
			);
		}
		return $fields ;*/
	}
	
	/**
	* add report fields
	* @param integer $idreport
	* @param array $report_fields
	*/
	public function add_report_fields($idreport,$report_fields) {
		$report_fields_string = implode('::',$report_fields);
		$this->addNew();
		$this->idreport = $idreport;
		$this->report_fields = $report_fields_string;
		$this->add();
	}
	
	/**
	* update report fields
	* @param integer $idreport
	* @param array $report_fields
	*/
	public function update_report_fields($idreport,$report_fields) {
		$report_fields_string = implode('::',$report_fields);
		$qry = "
		update `".$this->getTable()."`
		set `report_fields` = ?
		where `idreport` = ?
		";
		$this->query($qry,array($report_fields_string,$idreport));
	}
	
	/**
	* get report fields
	* @param integer $idreport
	* @return array
	*/
	public function get_report_fields($idreport) {
		$qry = "select * from `".$this->getTable()."` where `idreport` = ? ";
		$this->query($qry,array($idreport));
		$this->next();
		$fields = $this->report_fields;
		$fields_array = explode("::",$fields);
		$qry = "
		select * from `fields` 
		where `idfields` in (".implode(",",$fields_array).")
		order by field(idfields,".implode(",",$fields_array).")
		";
		$this->query($qry);
		$return_arrray = array();
		while ($this->next()) {
			$return_arrray[$this->idfields] = array(
				"idfields"=>$this->idfields,
				"field_label"=>$this->field_label,
				"field_name"=>$this->field_name,
				"field_type"=>$this->field_type,
				"table_name"=>$this->table_name,
				"idmodule"=>$this->idmodule
			);
		}
		return $return_arrray ;
	}
	
	/**
	* get report fields (idfields)
	* @param integer $idreport
	* @return mix
	*/
	public function get_report_fields_ids($idreport) {
		$qry = "select * from `".$this->getTable()."` where `idreport` = ? ";
		$this->query($qry,array($idreport));
		if ($this->getNumRows() > 0) {
			$this->next();
			$report_fields = $this->report_fields;
			if ($report_fields != '')
				return explode("::",$report_fields);
			else return false ;
		} else { return false ; }
	}
	
}