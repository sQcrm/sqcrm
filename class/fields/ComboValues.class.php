<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class ComboValues 
* @author Abhik Chakraborty
*/
	

class ComboValues extends DataObject {
	public $table = "combo_values";
	public $primary_key = "idcombo_values";
	
	protected $non_editable_combo_fields = array(19,25,117,122,131);
    
	/**
	* function to get the combo fields which are not editable from the setting section
	* @see modules/Settings/picklist_list.php
	*/
	public function get_non_editable_combo_fields() {
		return $this->non_editable_combo_fields ;
	}

	/**
	* function to get the combo values for a field
	* @param integer $idfields
	* @return object
	*/
	public function get_combo_values($idfields) {
		$qry = "
		select * from ".$this->getTable()." 
		where idfields = ? 
		order by sequence";
		$this->query($qry,array($idfields));
	}
	
	/**
	* function to add combo data for a field
	* @param integer $idfields
	* @param array $values
	*/
	public function add_combo_values($idfields,$values=array()) {
		if (count($values) > 0) {
			$cnt = 0 ;
			foreach ($values as $val) {
				$cnt++;
				$this->insert($this->getTable(),array('idfields'=>$idfields,'combo_option'=>$val,'combo_value'=>$val,'sequence'=>$cnt));
			}
		}
	}
    
	/**
	* function to update the combo values
	* it first deletes all the existing data for the field in combo values
	* @param integer $idfields
	* @param array $values
	*/
	public function update_combo_values($idfields,$values=array()) {
		if (count($values) > 0) {
			$this->query("delete from ".$this->getTable()." where idfields = ?",array($idfields));
			$this->add_combo_values($idfields,$values);
		}
	}
    
	/**
	* function to edit the combo values
	* @param object $evctl
	*/
	public function eventEditComboValues(EventControler $evctl) { 
		$idfields = (int)$evctl->idfields;
		if ($idfields > 0) {
			$referrar_module_id = (int)$evctl->referrar_module_id ;
			$pick_values_seperated = preg_split('/[\r\n]+/',$evctl->pick_values,-1,PREG_SPLIT_NO_EMPTY);
			$this->update_combo_values($idfields,$pick_values_seperated);
			//check if the field is mapped with some other fields and if yes then update the mapped fields also
			$do_crm_fields_mapping = new CRMFieldsMapping();
			$mapped_fields = $do_crm_fields_mapping->is_mapped($idfields);
			if (is_array($mapped_fields) && count($mapped_fields) > 0) {
				foreach($mapped_fields as $mapped_fieldid){
					$this->update_combo_values($mapped_fieldid,$pick_values_seperated);
				}
			}
			$_SESSION["do_crm_messages"]->set_message('success',_('Data has been updated successfully.'));
			$next_page = NavigationControl::getNavigationLink("Settings","picklist");
			$dis = new Display($next_page);
			$dis->addParam("cmid",$referrar_module_id);
			$evctl->setDisplayNext($dis) ;
		}
	}
}
