<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class CRMGlobalSettings
* Maintain the global setting of the crm
* @author Abhik Chakraborty
*/


class CRMGlobalSettings extends DataObject {
	public $table = "crm_global_settings";
	public $primary_key = "idcrm_global_settings";
  
	/**
	* event function update currency setting
	* @param object $evctl
	*/
	public function eventAjaxUpdateCurrencySettings(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			$qry = "
			select * from ".$this->getTable()."
			where `setting_name` = 'currency_setting' " ;
			$this->query($qry);
			$this->next();
			$id = $this->idcrm_global_settings ;
			$currency = $evctl->currency ;
			$currency_explode = explode("-",$currency);
			$currency_iso_code = $currency_explode[0];
			$currency_symbol = $currency_explode[1];
			$currency_symbol_position = $evctl->currency_symbol_position ;
			$decimal_point = $evctl->decimal_point;
			$decimal_symbol = $evctl->decimal_symbol ;
			$thousand_seperator = $evctl->thousand_seperator ;
			$setting_data = json_encode(
				array(
					"currency_iso_code"=>$currency_iso_code,
					"currency_sysmbol"=>$currency_symbol,
					"currency_symbol_position"=>$currency_symbol_position,
					"decimal_point"=>$decimal_point,
					"decimal_symbol"=>$decimal_symbol,
					"thousand_seperator"=>$thousand_seperator
				)
			);
			$qry = "
			update ".$this->getTable()." 
			set `setting_data` = ?
			where 
			`idcrm_global_settings` = ?" ;
			$this->query($qry,array($setting_data,$id));
			echo _('Setting data updates successfully');
		}
	}
  
	/**
	* function to get setting data by setting name
	* @param string $name
	*/
	public function get_setting_data_by_name($name) {
		$qry = "
		select * from ".$this->getTable()."
		where `setting_name` = ?";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($name));
		if ($stmt->rowCount() > 0) {
			$row = $stmt->fetch();
			return $row['setting_data'];
		} else { return false ; }
	}
  
	/**
	* function to get inventory prefixes (quotes,invoice,so,po)
	* @return array
	*/
	public function get_inventory_prefixes(){
		$qry = "
		select * from `".$this->getTable()."` 
		where `setting_name` in ('quote_num_prefix','invoice_num_prefix','salesorder_num_prefix','purchaseorder_num_prefix')
		";
		$this->query($qry);
		$return_data = array();
		while ($this->next()) {
			$return_data[$this->setting_name] = $this->setting_data;
		}
		return $return_data;
	}
  
	/**
	* function to get inventory terms and condition (quotes,invoice,so,po)
	*/
	public function get_inventory_terms_condition() {
		$qry = "
		select * from `".$this->getTable()."` 
		where 
		`setting_name` in ('quote_terms_condition','invoice_terms_condition','salesorder_terms_condition','purchaseorder_terms_condition')
		";
		$this->query($qry);
		$return_data = array();
		while ($this->next()) {
			$return_data[$this->setting_name] = $this->setting_data;
		}
		return $return_data;
	}
  
	/**
	* function to get inventory logo
	* @return string
	*/
	public function get_inventory_logo() {
		$qry = "select * from ".$this->getTable()." where `setting_name` = 'inventory_logo' " ;
		$this->query($qry);
		$this->next();
		return $this->setting_data ;
	}
  
	/**
	* event function update inventory prefixes
	* @param object $evctl
	*/
	public function eventAjaxUpdateInventoryPrefixes(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			if ($evctl->type != '') {
				$qry = '';
				switch ($evctl->type) {
					case 'quote_num_prefix':
						$qry = "update `".$this->getTable()."` set setting_data = ? where setting_name = 'quote_num_prefix'" ;
						break;
					case 'invoice_num_prefix':
						$qry = "update `".$this->getTable()."` set setting_data = ? where setting_name = 'invoice_num_prefix'" ;
						break;
					case 'so_num_prefix':
						$qry = "update `".$this->getTable()."` set setting_data = ? where setting_name = 'salesorder_num_prefix'" ;
						break;
					case 'po_num_prefix':
						$qry = "update `".$this->getTable()."` set setting_data = ? where setting_name = 'purchaseorder_num_prefix'" ;
						break;
					default :
						$qry = '';
						break;
				}
				if (strlen($qry) > 3) {
					$this->query($qry,array($evctl->value));
				}
			}
			echo '1';
		}
	}
  
	/**
	* event function update inventory terms and condition
	* @param object $evctl
	*/
	public function eventAjaxUpdateInventoryTermsCond(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			if ($evctl->type != '') {
				$qry = '';
				switch ($evctl->type) {
					case 'q':
						$qry = "update `".$this->getTable()."` set setting_data = ? where setting_name = 'quote_terms_condition'" ;
						break;
					case 'i':
						$qry = "update `".$this->getTable()."` set setting_data = ? where setting_name = 'invoice_terms_condition'" ;
						break;
					case 's':
						$qry = "update `".$this->getTable()."` set setting_data = ? where setting_name = 'salesorder_terms_condition'" ;
						break;
					case 'p':
						$qry = "update `".$this->getTable()."` set setting_data = ? where setting_name = 'purchaseorder_terms_condition'" ;
						break;
					default :
						$qry = '';
						break;
				}
				if (strlen($qry) > 3) {
					$this->query($qry,array($evctl->term_cond));
					echo nl2br($evctl->term_cond);
				}
			}
		}
	}
  
	/**
	* event function update inventory logo
	* @param object $evctl
	*/
	public function eventAjaxUpdateInventoryLogo(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			if ($_FILES["inventory_logo"]["name"] == '') {
				echo '0';
			} else {
				$upload_path = $GLOBALS['FILE_UPLOAD_PATH'];
				$filename = $_FILES["inventory_logo"]["name"];
				$tempname = $_FILES["inventory_logo"]["tmp_name"];
				$new_name = str_replace(" ","",microtime());
				$file_ext = end(explode('.',$filename));
				$upload_file_name = $new_name.'.'.$file_ext ;
				move_uploaded_file($tempname,$upload_path.'/'.$upload_file_name);
				$qry = "update `".$this->getTable()."` set setting_data = ? where setting_name = 'inventory_logo'";
				$this->query($qry,array($upload_file_name));
				echo $upload_file_name;
			}
		}
	}
  
	/**
	* event function update company address
	* @param object $evctl
	*/
	public function eventAjaxUpdateCompanyAddress(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			if (trim($evctl->company_address) != '') {
				$qry = "update `".$this->getTable()."` set setting_data = ? where setting_name = 'company_address'";
				$this->query($qry,array($evctl->company_address));
				echo nl2br($evctl->company_address);
			}
		}
	}
}