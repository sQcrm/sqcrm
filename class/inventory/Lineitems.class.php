<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Lineitems 
* @author Abhik Chakraborty
*/ 
	

class Lineitems extends DataObject {
	public $table = "lineitems";
	public $primary_key = "idlineitems";
    
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
	
	/**
	* function to get the line items 
	* @param integer $idmodule
	* @param integer $idrecord
	*/
	public function get_line_items($idmodule,$idrecord) {
		$qry = "select * from `".$this->getTable()."` where `recordid` = ? and `idmodule` = ? ";
		$this->query($qry,array((int)$idrecord,(int)$idmodule));
	}

	/**
	* function to add line items
	* @param integer $idmodule
	* @param integer $idrecord
	* @param array $data 
	*/
	public function add_line_items($idmodule,$idrecord,$data)	{
		if ((int)$idmodule > 0 && (int)$idrecord > 0 && is_array($data) && count($data) > 1) {
			foreach ($data["line_item_selector_opt"] as $key=>$val) {
				$item_type = $val;
				$item_name = $data["line_item_name"][$key] ;
				$item_value = $data["line_item_value"][$key] ;
				$item_description = $data["line_item_description"][$key] ;
				$item_quantity = $data["line_item_quantity"][$key] ;
				$item_price = $data["line_item_price"][$key] ;
				$discount_type = $data["line_discount_type"][$key] ;
				$discount_value = $data["line_discount_value"][$key] ;
				$discounted_amount = $data["line_discounted_amount_value"][$key] ;
				$tax_values = $data["line_item_tax_values"][$key] ;
				$taxed_amount = $data["line_item_tax_total"][$key] ;
				$total_after_discount = $data["line_total_after_discount_given"][$key] ;
				$total_after_tax = $total_after_discount+$taxed_amount ;
				$net_total = $data["line_net_price"][$key] ;
				$data_array = array(
					"idmodule"=>(int)$idmodule,
					"recordid"=>(int)$idrecord,
					"item_type"=>$item_type,
					"item_name"=>$item_name,
					"item_value"=>$item_value,
					"item_description"=>$item_description,
					"item_quantity"=>$item_quantity,
					"item_price"=>$item_price,
					"discount_type"=>$discount_type,
					"discount_value"=>$discount_value,
					"discounted_amount"=>$discounted_amount,
					"tax_values"=>$tax_values,
					"taxed_amount"=>$taxed_amount,
					"total_after_discount"=>$total_after_discount,
					"total_after_tax"=>$total_after_tax,
					"net_total"=>$net_total
				);
				$this->insert("lineitems",$data_array);
			}
		}
	}
  
	/**
	* function to edit line items
	* @param integer $idmodule
	* @param integer $idmodule
	* @param array $data 
	*/
	public function edit_line_items($idmodule,$idrecord,$data) {
		if ((int)$idmodule > 0 && (int)$idrecord > 0 && is_array($data) && count($data) > 1) {
			$line_items_edited_ids = array();
			foreach ($data["line_item_selector_opt"] as $key=>$val) {
				$item_type = $val;
				$item_name = $data["line_item_name"][$key] ;
				$item_value = $data["line_item_value"][$key] ;
				$item_description = $data["line_item_description"][$key] ;
				$item_quantity = $data["line_item_quantity"][$key] ;
				$item_price = $data["line_item_price"][$key] ;
				$discount_type = $data["line_discount_type"][$key] ;
				$discount_value = $data["line_discount_value"][$key] ;
				$discounted_amount = $data["line_discounted_amount_value"][$key] ;
				$tax_values = $data["line_item_tax_values"][$key] ;
				$taxed_amount = $data["line_item_tax_total"][$key] ;
				$total_after_discount = $data["line_total_after_discount_given"][$key] ;
				$total_after_tax = $total_after_discount+$taxed_amount ;
				$net_total = $data["line_net_price"][$key] ;
				$data_array = array(
					"idmodule"=>(int)$idmodule,
					"recordid"=>(int)$idrecord,
					"item_type"=>$item_type,
					"item_name"=>$item_name,
					"item_value"=>$item_value,
					"item_description"=>$item_description,
					"item_quantity"=>$item_quantity,
					"item_price"=>$item_price,
					"discount_type"=>$discount_type,
					"discount_value"=>$discount_value,
					"discounted_amount"=>$discounted_amount,
					"tax_values"=>$tax_values,
					"taxed_amount"=>$taxed_amount,
					"total_after_discount"=>$total_after_discount,
					"total_after_tax"=>$total_after_tax,
					"net_total"=>$net_total
				);
				if (array_key_exists($key,$data["idlineitems"])) {
					$this->update(array($this->primary_key=>$data["idlineitems"][$key]),"lineitems",$data_array);
					$line_items_edited_ids[] = $data["idlineitems"][$key];
				} else {
					$this->insert("lineitems",$data_array);
					$last_id = $this->getInsertId() ;
					$line_items_edited_ids[] = $last_id;
				}
			}
			$ids = implode(',',$line_items_edited_ids);
			$qry = "delete from lineitems where idmodule=? and recordid=? and idlineitems not in (".$ids.")";
			$this->query($qry,array($idmodule,$idrecord));
		}
	}
}