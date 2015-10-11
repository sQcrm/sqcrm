<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class TaxSettings
* Maintain the tax setting of the crm
* @author Abhik Chakraborty
*/


class TaxSettings extends DataObject {
	public $table = "";
	public $primary_key = "";
  
	/**
	* function to get the product service tax
	* @return array
	*/
	public function product_service_tax() {
		$qry = "select * from product_service_tax";
		$this->query($qry);
		$return_array = array();
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$return_array[] = array(
					"idproduct_service_tax"=>$this->idproduct_service_tax,
					"tax_name"=>$this->tax_name,
					"tax_value"=>$this->tax_value
				);
			}
		}
		return $return_array ; 
	}
  
	/**
	* function to get the shipping handling tax 
	* @return array
	*/
	public function shipping_handling_tax() {
		$qry = "select * from shipping_handling_tax";
		$this->query($qry);
		$return_array = array();
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$return_array[] = array(
					"idshipping_handling_tax"=>$this->idshipping_handling_tax,
					"tax_name"=>$this->tax_name,
					"tax_value"=>$this->tax_value
				);
			}
		}
		return $return_array ; 
	} 
  
	/**
	* event function to edit setting tax data
	* @param object $evctl
	*/
	function eventEditTaxData(EventControler $evctl) { 
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			$tax_type = $evctl->tax_type;
			$tax_name = $evctl->tax_name;
			$tax_value = $evctl->tax_value;
			if ($tax_name == '' || $tax_value == '') {
				$_SESSION["do_crm_messages"]->set_message('error',_('Missing tax name or tax value ! '));
				$next_page = NavigationControl::getNavigationLink("Settings","tax_settings");
				$dis = new Display($next_page);
				$evctl->setDisplayNext($dis) ;
			} else {
				if ($tax_type == 'ps') {
					$qry = "
					update product_service_tax 
					set 
					tax_name = ? ,
					tax_value = ?
					where idproduct_service_tax = ?
					";
				} else {
					$qry = "
					update shipping_handling_tax 
					set 
					tax_name = ? ,
					tax_value = ?
					where idshipping_handling_tax = ?
					";
				}
				$this->query($qry,array($tax_name,$tax_value,$evctl->id));
				echo 1;
			}
		}
	}
  
	/**
	* event function save tax setting
	* @param object $evctl
	*/
	function eventSaveTaxData(EventControler $evctl) { 
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			$tax_type = $evctl->tax_type;
			$tax_name = $evctl->tax_name;
			$tax_value = $evctl->tax_value;
			if ($tax_name == '' || $tax_value == '') {
				$_SESSION["do_crm_messages"]->set_message('error',_('Missing tax name or tax value ! '));
				$next_page = NavigationControl::getNavigationLink("Settings","tax_settings");
				$dis = new Display($next_page);
				$evctl->setDisplayNext($dis) ;
			} else {
				if ($tax_type == 'ps') {
					$this->insert(
						"`product_service_tax`",
						array(
							"tax_name"=>CommonUtils::purify_input($evctl->tax_name),
							"tax_value"=>CommonUtils::purify_input($evctl->tax_value)
						)
					);
					$id = $this->getInsertId() ;
					$this->query("select * from `product_service_tax` where idproduct_service_tax = ?",array($id));
					$this->next();
					$return_array = array("id"=>$id,"tax_name"=>$this->tax_name,"tax_value"=>$this->tax_value);
				} else {
					$this->insert(
						"`shipping_handling_tax`",
						array(
							"tax_name"=>CommonUtils::purify_input($evctl->tax_name),
							"tax_value"=>CommonUtils::purify_input($evctl->tax_value)
						)
					);
					$id = $this->getInsertId() ;
					$this->query("select * from `shipping_handling_tax` where idshipping_handling_tax = ?",array($id));
					$this->next();
					$return_array = array("id"=>$id,"tax_name"=>$this->tax_name,"tax_value"=>$this->tax_value);
				}
				echo json_encode($return_array) ;
			}
		}
	}
  
	/**
	* event function to delete the tax setting
	* @param object $evctl
	*/
	function eventDeleteTaxData(EventControler $evctl) { 
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			if ((int)$evctl->id > 0) {
				if ($evctl->tax_type == 'ps') {
					$this->query("delete from `product_service_tax` where idproduct_service_tax = ?",array((int)$evctl->id));
				} else {
					$this->query("delete from `shipping_handling_tax` where idshipping_handling_tax = ?",array((int)$evctl->id));
				}
				echo 1;
			} else {
				$_SESSION["do_crm_messages"]->set_message('error',_('Missing id ! '));
				$next_page = NavigationControl::getNavigationLink("Settings","tax_settings");
				$dis = new Display($next_page);
				$evctl->setDisplayNext($dis) ;
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to delete record !'));
			$next_page = NavigationControl::getNavigationLink("Settings","index");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}
}