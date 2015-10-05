<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ProductsImport
* @author Abhik Chakraborty
*/ 
	

class ProductsImport extends Products {
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
  
	/**
	* function to save the imported vendor
	* @param object $import_object
	* @param object $do_crm_fields
	* @param array $data
	* @return integer inseted recordid
	*/
	public function import_save($import_object,$crm_fields,$data) {
		$mapped_fields = $import_object->get_mapped_fields();
		$table_entity = 'products';
		$table_entity_custom = 'products_custom_fld';
		$table_entity_to_grp = 'products_to_grp_rel';
		$table_products_pricing = 'products_pricing';
		$table_products_stock = 'products_stock';
		$table_product_tax = 'products_tax';
    
		$entity_data_array = array();
		$custom_data_array = array();
		$product_stock_data_array = array();
		$product_pricing_data_array = array();
		$product_tax_data_array = array();
		
		$tax_settings = new TaxSettings();
		$product_service_tax = $tax_settings->product_service_tax();
		foreach ($crm_fields as $crm_fields) {
			$field_name = $crm_fields["field_name"];
			if ($crm_fields["field_type"] == 165) {
				foreach ($product_service_tax as $key=>$val) {
					$mapped_field_key = array_search($val["tax_name"],$mapped_fields);
					if ($mapped_field_key !== false) {
						$product_tax_data_array[$val["tax_name"]] = $data[$mapped_field_key];
					}
				}
			} else {
				$mapped_field_key = array_search($field_name,$mapped_fields);
				if ($mapped_field_key !== false) {
					$field_value = $data[$mapped_field_key];
					$field_value = $import_object->format_data_before_save($crm_fields["field_type"],$field_value);
				} else { $field_value = ''; }
				if ($field_name == 'assigned_to') { 
					$field_name = 'iduser';
					$field_value = $_SESSION["do_user"]->iduser ;
				}
				if ($crm_fields["table_name"] == $table_entity && $crm_fields["idblock"] > 0) {
					if ($field_name == 'idvendor') {
						$field_value = $this->map_products_vendor($field_value);
					}
					$entity_data_array[$field_name] = $field_value ;
				} elseif ($crm_fields["table_name"] == $table_entity_custom && $crm_fields["idblock"] > 0) {
					$custom_data_array[$field_name] = $field_value ;
				} elseif ($crm_fields["table_name"] == $table_products_stock && $crm_fields["idblock"] > 0) {
					$product_stock_data_array[$field_name] = $field_value ;
				} elseif ($crm_fields["table_name"] == $table_products_pricing && $crm_fields["idblock"] > 0) {
					$product_pricing_data_array[$field_name] = $field_value ;
				}
			}
		}
		//print_r($product_tax_data_array);exit;
		$this->insert($table_entity,$entity_data_array);
		$id_entity = $this->getInsertId() ;
		if ($id_entity > 0) {
			//adding the added_on
			$q_upd = "
			update `".$this->getTable()."` 
			set `added_on` = ? 
			where `".$this->primary_key."` = ?" ;
			$this->query($q_upd,array(date("Y-m-d H:i:s"),$id_entity));
			$custom_data_array["idproducts"] = $id_entity;
			$product_stock_data_array["idproducts"] = $id_entity;
			$product_pricing_data_array["idproducts"] = $id_entity ;
			$this->insert($table_entity_custom,$custom_data_array);
			$this->insert($table_products_stock,$product_stock_data_array);
			$this->insert($table_products_pricing,$product_pricing_data_array);
			if (is_array($product_tax_data_array) && count($product_tax_data_array) > 0) {
				foreach ($product_tax_data_array as $tax_name=>$tax_value) {
					$q_ins = "
					insert into `$table_product_tax` (`idproducts`,`tax_name`,`tax_value`) 
					values
					(?,?,?)
					";
					$this->query($q_ins,array($id_entity,$tax_name,$tax_value));
				}
			}
			
			$do_data_history = new DataHistory();
			$do_data_history->add_history($id_entity,12,'add'); 
			$do_data_history->free();
			return $id_entity;
		} else { return false ; }
	}
  
	public function map_products_vendor($vendor_name) {
		$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition('vendor',11);
		$qry = "select * from `vendor` where `vendor_name` = ? ".$security_where;
		$stmt = $this->getDbConnection()->executeQuery($qry,array($vendor_name));
		if ($stmt->rowCount() > 0) {
			$data=$stmt->fetch();
			$idvendor = $data["idvendor"];
			return $idvendor ;
		} else {
			if (strlen($vendor_name) > 0) {
				$do_vendor = new Vendor();
				$data = array(
					"vendor_name"=>CommonUtils::purify_input($vendor_name),
					"iduser"=>$_SESSION["do_user"]->iduser,
					"added_on"=>date("Y-m-d H:i:s")
				);
				$do_vendor->insert("vendor",$data);
				$idvendor = $do_vendor->getInsertId() ;
				$do_vendor->insert("vendor_address",array("idvendor"=>$idvendor));
				$do_vendor->insert("vendor_custom_fld",array("idvendor"=>$idvendor));
				$do_vendor->free();
				$do_data_history = new DataHistory();
				$do_data_history->add_history($idvendor,11,'add'); 
				$do_data_history->free();
				return  $idvendor;
			}
		}
	}
  
	/**
	* function to get the last imported leads for listing them
	*/
	public function list_imported_data() {
		$qry = "
		select `products`.*,
		`products_pricing`.*,
		`products_custom_fld`.*,
		`products_stock`.*,
		`products_to_grp_rel`.`idgroup`,
		`vendor`.`vendor_name` as `vendor_name`,
		group_concat(concat(products_tax.tax_name,'::',products_tax.tax_value)) as tax_value,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `products`
		inner join `import` on `import`.`idrecord` = `products`.`idproducts`
		inner join `products_pricing` on `products_pricing`.`idproducts` = `products`.`idproducts`
		inner join `products_custom_fld` on `products_custom_fld`.`idproducts` = `products`.`idproducts`
		inner join `products_stock` on `products_stock`.`idproducts` = `products`.`idproducts`
		left join `user` on `user`.`iduser` = `products`.`iduser`
		left join `products_to_grp_rel` on `products_to_grp_rel`.`idproducts` = `products`.`idproducts`
		left join `vendor` on `vendor`.`idvendor` = `products`.`idvendor`
		left join `group` on `group`.`idgroup` = `products_to_grp_rel`.`idgroup`
		left join `products_tax` on `products_tax`.`idproducts` = `products`.`idproducts`
		where `products`.`deleted` = 0 
		AND `import`.`idmodule` = 12 
		AND `products`.`iduser` = ".$_SESSION["do_user"]->iduser."
		AND `import`.`iduser` = ".$_SESSION["do_user"]->iduser ;
		$this->setSqlQuery($qry);    
	}
  
}