<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ReportSorting 
* @author Abhik Chakraborty
*/ 
	

class ReportSorting extends DataObject {
	public $table = "report_sorting";
	public $primary_key = "idreport_sorting";

	public $sort_order = array(
		1=>array('sort'=>'asc','title'=>'Ascending'),
		2=>array('sort'=>'desc','title'=>'Descending')
	);
	
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
	
	/**
	* function to get the sorting fields of a report 
	* @param integer $idreport
	* @return mix
	*/
	public function get_report_sorting_fields($idreport) {
		$qry = "
		select
		f.field_name,
		f.field_label,
		f.table_name,
		f.field_type,
		rs.sort_type,
		rs.sort_field,
		m.module_label
		from report_sorting rs
		join fields f on f.idfields = rs.sort_field
		join module m on m.idmodule = f.idmodule
		where 
		rs.idreport = ?
		order by rs.sequence
		";
		$this->query($qry,array($idreport));
		if($this->getNumRows() > 0) {
			$return_array = array();
			while ($this->next()) {
				$data = array(
					"field_name"=>$this->field_name,
					"field_label"=>$this->field_label,
					"table_name"=>$this->table_name,
					"field_type"=>$this->field_type,
					"module_label"=>$this->module_label,
					"sort_type"=>$this->sort_type
				);
				$return_array[$this->sort_field] = $data;
			}
			return $return_array;
		} else { return false ; }
	}
	
	/**
	* get report sort fields at the time of report add/edit depending on selected fields
	* @param array $report_fields
	* @return array
	*/
	public function get_report_sorting_fields_on_create($report_fields) {
		$qry = "
		select
		f.idfields,
		f.field_name,
		f.field_label,
		m.module_label
		from fields f
		join module m on m.idmodule = f.idmodule
		where 
		f.idfields IN (".implode($report_fields,",").")
		order by field(f.idfields,".implode(",",$report_fields).")
		";
		$this->query($qry);
		$return_array = array();
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$data = array("idfields"=>$this->idfields,"field_label"=>$this->field_label,"module_label"=>$this->module_label);
				$return_array[] = $data ;
			}
		}
		return $return_array ; 
	}
	
	/**
	* add report sort fields
	* @param integer $idreport
	* @param array $fields
	*/
	public function add_report_sort_fields($idreport,$fields) {
		if (is_array($fields) && count($fields) > 0) {
			$sequence = 1;
			foreach ($fields as $key=>$val) {
				if ((int)$val["order_by_field"] > 0) {
					$this->addNew();
					$this->idreport = $idreport;
					$this->sort_field = $val["order_by_field"];
					$this->sort_type = $val["order_by_type"];
					$this->sequence = $sequence;
					$this->add();
					$sequence++;
				}
			} 
		}
	}
	
	/**
	* update report sort fields
	* @param integer $idreport
	* @param array $fields
	*/
	public function update_report_sort_fields($idreport,$fields) {
		$qry = "delete from `".$this->getTable()."` where `idreport` = ? ";
		$this->query($qry,array($idreport));
		$this->add_report_sort_fields($idreport,$fields);
	}
	
	/**
	* function to get the report sorting condition
	* @param integer $idreport
	* @return string
	*/
	public function get_report_sorting_condition($idreport) {
		$order_by = '';
		$sort_fields = $this->get_report_sorting_fields($idreport);
		if (false !==$sort_fields) {
			$order_by = $this->parse_sorting_fields($sort_fields);
		}
		return $order_by;
	}
	
	/**
	* function to parse the sorting fields
	* @param array $sort_fields
	* @return string
	*/
	public function parse_sorting_fields($sort_fields) {
		$order_by = '';
		if (is_array($sort_fields) && count($sort_fields) > 0) {
			$order_by .= " order by ";
			foreach ($sort_fields as $id=>$info) {
				if ($info["field_type"] == 15) {
					$order_by .= "`".$info["field_name"]."` ".$this->sort_order[$info["sort_type"]]["sort"].",";
				} else {
					$order_by .= "`".$info["table_name"]."`.`".$info["field_name"]."` ".$this->sort_order[$info["sort_type"]]["sort"]."," ;
				}
			}
			$order_by = rtrim($order_by,',');
		}
		return $order_by ;
	}
	
	/**
	* get saved order by fields
	* @param integer $idreport
	* @return array
	*/
	public function get_saved_orderby_fields($idreport) {
		$return_array = array();
		$qry =	"
		select * from `".$this->getTable()."`
		where `idreport` = ?
		order by `sequence`
		";
		$this->query($qry,array($idreport));
		$sequence = 1;
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$return_array["order_by_".$sequence] = array("order_by_field"=>$this->sort_field,"order_by_type"=>$this->sort_type);
				$sequence++;
			}
		}
		return $return_array ;
	}
}