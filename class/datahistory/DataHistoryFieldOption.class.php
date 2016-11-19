<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class DataHistoryFieldOption
* @author Abhik Chakraborty
*/
	

class DataHistoryFieldOption extends DataObject {
	public $table = "data_history_field_opt";
	public $primary_key = "";
    
	/** 
	* array to hold the information of fields for module to be recored in the history when the values are changed 
	* @deprecated v-0.4
	*/
	public $data_history_fields = array(
		2=>array(122,123,127,128,129,130,131,132),
		3=>array(33,34,35,37,41),
		6=>array(48,56,59,60),
		4=>array(75,76,78,82,80,94),
		5=>array(112,114,117,119),
		12=>array(165,169,176,177),
		13=>array(190,191,192,193,194,195),
		14=>array(224,225,226,227,228,229,230,231),
		15=>array(260,261,262,263,264,265,266,267),
		16=>array(296,297,298,299,300,301)
	);
                                
	/**
	* function to get the fields for the data history which needs to be recored when the value is changed
	* @param integer $idmodule
	* @return array
	* can be maintained from the admin section
	*/                            
	public function get_data_history_fields($idmodule) {
		$fields_array = array();
		$qry="select `idfields` from `data_history_field_opt` where `idmodule` = ?";
		$this->query($qry,array((int)$idmodule));
		if ($this->getNumRows() > 0 ) {
			while ($this->next()) {
				$fields_array[] = $this->idfields ; 
			}
		}
		return $fields_array;
	}
	
	/**
	* function to get the modules for the data history 
	* @return array
	*/
	public function get_modules_for_datahistory() {
		$qry = "
		select 
		m.idmodule,
		m.module_label 
		from module m 
		join fields f on f.idmodule = m.idmodule 
		where 
		m.active = 1 
		and m.idmodule <> 7
		group by m.idmodule 
		order by m.name" ;
		$this->query($qry);
		$return_array = array();
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$return_array[] = array("idmodule"=>$this->idmodule,"module_label"=>$this->module_label);
			}
		}
		return $return_array ;
	}
	
	/**
	* function to ge the data history field options by module
	* gets the available fields for a module for which data history option could be set
	* @param integer $idmodule
	* @return array
	*/
	public function get_datahistory_field_options($idmodule) {
		$qry="
		select 
		f.idfields,
		f.field_label, 
		case when dhf.idfields is not null then 'yes' else 'no' end as selected 
		from fields f 
		left join data_history_field_opt dhf on dhf.idfields = f.idfields 
		where 
		f.idmodule = ? 
		and f.display = 1 
		";
		$this->query($qry,array($idmodule));
		$return_array = array();
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$return_array[] = array("idfields"=>$this->idfields,"field_label"=>$this->field_label,"selected"=>$this->selected);
			}
		}
		return $return_array ;
	}
	
	/**
	* event function to save the data history field options for a module
	* @param object $evctl
	* @see view/datahistory_fields_entry_view.php
	*/
	public function eventAjaxSaveHistoryFields(EventControler $evctl) {
		if ((int)$evctl->mid > 0) {
			$fields_data = $evctl->datahistory_fields ;
			$fields = array();
			
			if ($fields_data != '') {
				$fields = explode(',',$fields_data);
			}
			
			$qry = "
			delete from `data_history_field_opt`
			where `idmodule` = ?
			";
			$this->query($qry,array((int)$evctl->mid));
			
			if (is_array($fields) && count($fields) > 0) {
				foreach ($fields as $key=>$val) { 
					$qry = "
					insert into `data_history_field_opt` 
					(`idmodule`,`idfields`)
					values
					(?,?)
					";
					$this->query($qry,array((int)$evctl->mid,$val));
				}
			}
			echo _('Data history field information has been saved !');
			
		} else {
			echo _('Operation failed ! Missing module id !');
		}
	}
}
