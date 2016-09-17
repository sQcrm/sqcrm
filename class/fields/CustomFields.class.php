<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class CustomFields.
* @author Abhik Chakraborty
*/
class CustomFields extends CRMFields {
	public $table = "fields";
	public $primary_key = "idfields";

	/* modules where custom field is not allowed */
	private $modules_without_custom_field = array(1,7,8,9);

	/* Block id for each custom fields */
	private $custom_field_blocks = array(2=>17,3=>6,6=>9,4=>13,5=>15);

	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}

	/**
	* function to get the modules without custom fields
	* @return modules_without_custom_field
	*/
	public function get_module_without_custom_field() {
		return $this->modules_without_custom_field ;
	}

	/**
	* function to get the custom fields for a given module
	* @param integer $idmodule
	* @return object 
	*/
	public function get_custom_fields($idmodule) {
		$qry = "
		select * from ".$this->getTable()." 
		where field_name like '%ctf_%' 
		AND idmodule = ?
		order by field_sequence
		";
		$this->query($qry,array($idmodule));
	}
     
	public function get_custom_fields_as_array($idmodule) {
		$qry = "
		select * from ".$this->getTable()." 
		where field_name like '%ctf_%' 
		AND idmodule = ?
		order by field_sequence
		";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idmodule));
		return $stmt->fetchAll();
	}
    
	/**
	* function to get the custom fields table for the module
	* its not stored in db and hard coded for each module 
	* @param integer $idmodule
	*/
	public function get_custom_fields_tablename($idmodule) {
		$qry = "
		select `table_name` from `customfield_module_map`
		where `idmodule` = ?
		";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idmodule));
		if ($stmt->rowCount() > 0) {
			$data = $stmt->fetch();
			return $data["table_name"];
		} else { return false ; }
	}

	public function get_custom_field_blocks($idmodule) {
		$qry = "
		select `idblock` from `customfield_module_map`
		where `idmodule` = ?
		";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idmodule));
		if ($stmt->rowCount() > 0) {
			$data = $stmt->fetch();
			return $data["idblock"];
		} else { return false ; }
	}
	
	public function get_customfield_module_info() {
		$qry="
		select m.* from module m 
		inner join customfield_module_map cm on cm.idmodule = m.idmodule
		where m.active = 1 
		order by m.name
		";
		$stmt = $this->getDbConnection()->executeQuery($qry);
		$return_array = array();
		while ($data = $stmt->fetch()) {
			$return_array[$data["idmodule"]] = array(
				"label"=>$data["module_label"],
				"name"=>$data["name"]
			);
		}
		return $return_array ;
	}
	
	/**
	* function to add a custom field
	* It will recieve the custom field information for custom field add form
	* Add the data in the fields table and then to the custom field table
	* @see CustomFields::get_custom_fields_tablename()
	* @see popups/add_custom_field_modal.php
	*/
	public function eventAddCustomField(EventControler $evctl) { 
		$idmodule =  $evctl->idmodule;
		$custom_field_type =  $evctl->custom_field_type;
		$req = $evctl->cf_req;
		$field_validation = array();
		$is_required = false ;
		if ($req == 'on') {
			$is_required = true ;
			$field_validation["required"] = true; 
		}
		$field_data_type = '';
		switch ($custom_field_type) {
			case 1 :
				$fld_length = (int)$evctl->cf_len ;
				$field_data_type = 'VARCHAR('.$fld_length.')';
				if ($is_required === true) {
					if ($evctl->cf_max_len != '' || (int)$evctl->cf_max_len > 0) {
						$field_validation["maxlength"] = (int)$evctl->cf_max_len ;
					}
					if ($evctl->cf_min_len != '' || (int)$evctl->cf_min_len > 0) {
						$field_validation["minlength"] = (int)$evctl->cf_min_len ;
					}
				}
				break;
			case 2 :
				$field_data_type = 'TEXT';
				break ;
			case 3 :
				$field_data_type = 'VARCHAR(3)';
				break ;
			
			case 5 :
				$pick_values = $evctl->cf_pick ;
				$not_equal = $evctl->cf_pick_notequal ;
				if ($is_required === true) $field_validation["notEqual"] = $not_equal ;
				$field_data_type = 'VARCHAR(100)';
				break;
			case 6 :
				$pick_values = $evctl->cf_pick ;
				$field_data_type = 'VARCHAR(100)';
				break;
			case 7 :
				$fld_length = (int)$evctl->cf_len ;
				$field_data_type = 'VARCHAR('.$fld_length.')';
				break;
			case 8 :
				$fld_length = (int)$evctl->cf_len ;
				$field_data_type = 'VARCHAR('.$fld_length.')';
				break;
			case 9 :
				$field_data_type = 'DATE';
				break ;
			case 10 :
				$field_data_type = 'VARCHAR(10)';
				break ;
			case 210 :
				$field_data_type = 'VARCHAR(15)';
				break ;
		}
		if (count($field_validation) > 0) {
			$field_validation_entry = json_encode($field_validation);
		} else { $field_validation_entry = ''; }
		$qry = "select * from ".$this->getTable()." where field_name like '%ctf_%' order by idfields desc limit 1 " ;
		$stmt = $this->getDbConnection()->executeQuery($qry);
		if ($stmt->rowCount() > 0) {
			$data = $stmt->fetch();
			$last_custom_field = $data["field_name"];
			$field_sequence = $data["field_sequence"] ;
			$last_custom_field_explode = explode("_",$last_custom_field);
			$custom_field_suffix = $last_custom_field_explode[1];
			$new_custom_field_suffix = $custom_field_suffix+1 ;
			$custom_field_name = "ctf_".$new_custom_field_suffix ;
			$custom_field_sequence = $field_sequence+1;
		} else {
			$custom_field_name = "ctf_1";
			$custom_field_sequence = 1 ;
		}
		
		$insert_data = array(
			'field_name'=>$custom_field_name,
			'field_label'=>CommonUtils::purify_input($evctl->cf_label),
			'field_sequence'=>$custom_field_sequence,
			'idblock'=>$this->get_custom_field_blocks($idmodule),
			'idmodule'=>$idmodule,
			'table_name'=>$this->get_custom_fields_tablename($idmodule),
			'field_type'=>$custom_field_type,
			'field_validation'=>$field_validation_entry
		);
		$this->insert($this->getTable(),$insert_data);
		$idfields = $this->getInsertId() ;
		if ($idfields > 0) {
			if ($custom_field_type == 5 || $custom_field_type == 6) {
				//$pick_values_seperated = explode(PHP_EOL,$evctl->cf_pick);
				$pick_values_seperated = preg_split('/[\r\n]+/',$evctl->cf_pick,-1,PREG_SPLIT_NO_EMPTY);
				$do_combo_values = new ComboValues();
				$do_combo_values->add_combo_values($idfields,$pick_values_seperated);
			}
			// add field to the custom field table for the moduleedit_custom_field_modal
			$qry_alter = "
			alter table `".$this->get_custom_fields_tablename($idmodule)."` 
			add column `$custom_field_name` $field_data_type
			";
			$this->query($qry_alter);
			$_SESSION["do_crm_messages"]->set_message('success',_('Custom field added successfully.'));
			$next_page = NavigationControl::getNavigationLink("Settings","customfield");
			$dis = new Display($next_page);
			$dis->addParam("cmid",$idmodule);
			$evctl->setDisplayNext($dis) ; 
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('Custom field could not be added, please try again ! '));
		}
	}

	/**
	* function to edit the custom field
	* @param object $evctl
	* @see popups/edit_custom_field_modal.php
	*/
	public function eventEditCustomField(EventControler $evctl) {
		$idfields = (int)$evctl->idfields_ed ;
		$update_data = false ;
		if ($idfields > 0) {
			$this->getId($idfields);
			if ($this->getNumRows() > 0) {
				$update_data = true ;
			} else {
				$update_data = false ;
				$_SESSION["do_crm_messages"]->set_message('error',_('Record does not exit.'));
			}
		} else {
			$update_data = false ;
			$_SESSION["do_crm_messages"]->set_message('error',_('Record does not exit.'));
		}
		if ($update_data === true) {
			$custom_field_type =  $evctl->custom_field_type_ed;
			$req = $evctl->cf_req_ed;
			$field_validation = array();
			$is_required = false ;
			if ($req == 'on') {
				$is_required = true ;
				$field_validation["required"] = true; 
			}
			switch ($custom_field_type) {
				case 1 :
					if ($is_required === true) {
						if ($evctl->cf_max_len_ed != '' || (int)$evctl->cf_max_len_ed > 0) {
							$field_validation["maxlength"] = (int)$evctl->cf_max_len_ed ;
						}
						if ($evctl->cf_min_len_ed != '' || (int)$evctl->cf_min_len_ed > 0) {
							$field_validation["minlength"] = (int)$evctl->cf_min_len_ed ;
						}
					}
					break;
				
				case 5 :
					$pick_values = $evctl->cf_pick_ed ;
					$not_equal = $evctl->cf_pick_notequal_ed ;
					if ($is_required === true) $field_validation["notEqual"] = $not_equal ;
					break;
				case 6 :
					$pick_values = $evctl->cf_pick_ed ;
					break;
			}
			if( count($field_validation) > 0) {
				$field_validation_entry = json_encode($field_validation);
			} else { $field_validation_entry = ''; }
			
			$qry_update = "
			update ".$this->getTable()." 
			set `field_label` = ?,
			`field_validation` = ?
			where idfields = ?" ; 
			$this->query($qry_update,array(CommonUtils::purify_input($evctl->cf_label_ed),$field_validation_entry,$idfields));
			if ($custom_field_type == 5 || $custom_field_type == 6) {
				//$pick_values_seperated = explode(PHP_EOL,$evctl->cf_pick);
				$pick_values_seperated = preg_split('/[\r\n]+/',$evctl->cf_pick_ed,-1,PREG_SPLIT_NO_EMPTY);
				$do_combo_values = new ComboValues();
				$do_combo_values->update_combo_values($idfields,$pick_values_seperated);
			}
			$_SESSION["do_crm_messages"]->set_message('success',_('Custom field updated successfully !'));
		}
	}
    
	/**
	* event function to delete the custom field
	* @param object $evctl
	*/
	public function eventAjaxDeleteCustomField(EventControler $evctl) {
		$idfields = (int)$evctl->idfields ;
		$idmodule = (int)$evctl->idmodule ;
		$this->getId($idfields);
		if ($this->getNumRows() > 0) {
			$field_name = $this->field_name ;
			$qry = "delete from `".$this->getTable()."` where `idfields` = ? limit 1 ";
			$this->query($qry,array($idfields));
			switch ($idmodule) {
				case 3 :
					$qry = "
					delete from `custom_field_mapping` 
					where `mapping_field_id` = ? limit 1 ";
					break ;
				case 4 :
					$qry = "
					update `custom_field_mapping` 
					set `contacts_mapped_to` = 0 
					where `contacts_mapped_to` = ?";
					break ;
				case 5 :
					$qry = "
					update `custom_field_mapping` 
					set `potentials_mapped_to` = 0 
					where `potentials_mapped_to` = ?";
					break ;
				case 6 :
					$qry = "
					update `custom_field_mapping` 
					set `organization_mapped_to` = 0 
					where `organization_mapped_to` = ?";
					break ;
			}
			$this->query($qry,array($idfields));
			echo "1";
		} else {
			echo "0";
		}
	}
}