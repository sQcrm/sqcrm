<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class CRMFields.
* @author Abhik Chakraborty
*/
	

class CRMFields extends DataObject {
	public $table = "fields";
	public $primary_key = "idfields";

	/**
	* function to get the information for a specific set of fields of a module 
	* the field_names are supplied as first argument
	* @param array $fields
	* @param integer $idmodule
	* @param string $col_name
	* @return array containing the field information
	* @see view/listview.php
	*/
	public function get_specific_fields_information($fields,$idmodule,$show_all=false,$col_name = 'field_name') {
		$fields_values = "'".implode("','",$fields)."'";
		$display = '';
		if (false === $show_all) {
			$display = " and display = 1 ";
		}
		$col_name_qry = " and $col_name in ($fields_values) order by field($col_name,$fields_values)";
		$qry = "select * from `".$this->getTable()."` where `idmodule` = ? ".$display.$col_name_qry ;
		$this->query($qry,array($idmodule));
		$fields_info = array();
		while ($this->next()) {
			$data = array(
				"table"=>$this->table_name,
				"field_label"=>$this->field_label,
				"field_type"=>$this->field_type
			);
			//$key = array_search($this->field_name,$fields);
			//$fields_info[$fields[$key]] = $data ;
			$fields_info[$this->field_name] =  $data ;
		}
		return $fields_info ;
	}
    
	/**
	* public function to get the field information for module per block
	* @param integer $idblock
	* @param integer $idmodule
	* @return object containing the query data
	*/
	public function get_form_fields_information($idblock,$idmodule) {
		$qry = "
		select * from ".$this->getTable()." 
		where idmodule = ?
		AND idblock = ?
		and display = 1
		order by field_sequence" ;
		$this->query($qry,array($idmodule,$idblock));
	}
    
	/**
	* function to get the field information per module
	* @param integer $idmodule
	* return object containing the query data
	*/
	public function get_field_information_by_module($idmodule) {
		$qry = "select * from ".$this->getTable()." where idmodule = ? and display = 1";        
		return $this->query($qry,array($idmodule));
	}
    
	public function get_field_information_by_module_as_array($idmodule) {
		$qry = "select * from ".$this->getTable()." where idmodule = ? and display = 1";        
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idmodule));
		return $stmt->fetchAll();
	}

	/**
	* function to get the fields of type pick list/multi-select
	* @param integer $idmodule
	* @return void
	*/
	public function get_pick_multiselect_fields($idmodule) {
		$qry = "
		select * from ".$this->getTable()." where 
		idmodule = ? AND field_type  IN (5,6) and display = 1";
		$this->query($qry,array($idmodule));
	}
    
	/**
	* Function to get the field validation information by module
	* @param integer $idmodule
	* @return array
	*/
	public function get_field_validation_info($idmodule) {
		$qry = "
		select 
		field_name,
		field_label,
		field_type,
		field_validation 
		from ".$this->getTable()." 
		where field_validation <> '' 
		AND field_validation IS NOT NULL 
		AND idmodule = ? 
		and display = 1";
		//return $this->query($qry,array($idmodule));
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idmodule));
		return $stmt->fetchAll();
	}
	
	/**
	* Public function get form JS validation
	* @param integer $idmodule
	* @param string $form_id
	* @param string $action
	* @return string JS validation
	*/
	public function get_js_form_validation($idmodule,$form_id,$action,$sqrecord = '') {
		$fields_validations_data = $this->get_field_validation_info($idmodule);
		$validation_array = array() ;
		$field_label_array = array();
		$field_name_as_array = array();
		foreach($fields_validations_data as $validations_data) {
			if ($validations_data["field_type"]== 11) {
				if(preg_match("#^(.*)__editRecord$#i",$form_id) != 0) continue; // dont show password in edit form
			}
			// field type like multi select combo has field name as array so that values are recieved as array
			if ($validations_data["field_type"] == 6) {
				$field_name_as_array[] = $validations_data["field_name"] ; 
			}
			if (strlen($validations_data["field_validation"]) > 2) {
				$validation_array[$validations_data["field_name"]] = json_decode($validations_data["field_validation"],true);
			}
		}
		foreach ($fields_validations_data as $validations_data) {
			if (strlen($validations_data["field_validation"]) > 2) {
				$field_label_array[$validations_data["field_name"]] = $validations_data["field_label"] ;
			}
		}
		$js = '';
		$js .= '$(\'#'.$form_id.'\').validate({' ."\n";
		$js .= 'ignore:"",'."\n";// To allow hidden field validate
		$js .=  'rules : {';
		$js .="\n";
		foreach ($validation_array as $fieldname=>$validations) {
			if (in_array($fieldname,$field_name_as_array)) {
				$js .= "\t".'\''.$fieldname.'[]\' : {' ."\n";
			} else {
				$js .= "\t".$fieldname.' : {' ."\n";
			}
			foreach ($validations as $rule=>$val) {
				if ($rule == 'notEqual') {
					$js .=   "\t\t".$rule.' :"'.$val.'",'."\n";
				} elseif ($rule == 'unique') {
					$sqrecord_param = '';
					if ($sqrecord != '') {
						$sqrecord_param = '&sqrecord='.$sqrecord;
					}
					$js .= "\t\t".'remote :"_ajax_check_unique?field='.$fieldname.'&action='.$action.$sqrecord_param.'"'."\n";
				} else {
					$js .= "\t\t".$rule.' :'.$val.','."\n";
				}
			}
			$js .=    "\t\t".'},'."\n";
		}
		$js .= '},'."\n";

		// Adding JS validate messages, this could be changed as per user requirement
		$js .= 'messages :{ ';
		$js .="\n";
		foreach ($validation_array as $fieldname=>$validations) {
			$js .= "\t".'"'.$fieldname.'" : {' ."\n";
			foreach ($validations as $rule=>$val) {
				if ($rule == 'notEqual') {
					$js .= "\t\t".$rule.' :"'._('Please select ').$field_label_array[$fieldname].'",'."\n";
				} elseif ($rule == 'unique') {
					$js .= "\t\t".'remote :"'.$field_label_array[$fieldname]._(' is already in use').'",'."\n";
				}
			}
			$js .= "\t".'},'."\n";
		}
		$js .= '},'."\n";
		
		$js .= 
		'submitHandler: function(form){
				if(custom_validator(\''.$idmodule.'\')){
					form.submit();
				}
			},'."\n";
		$js .= 
		'highlight: function(label) {
			$(label).closest(\'.control-group\').addClass(\'error\');
			},
			success: function(label) {
			label
				.text(\'OK!\').addClass(\'valid\')
				.closest(\'.control-group\').addClass(\'success\');
			}';
		$js .= "\n".'});'."\n";
		return $js ;
	}
    
	/**
	* function getting the field value from the event controller object depending on the field type
	* if needed do the field conversion
	* @param object $do_crm_fields
	* @param object $evctl
	*/
	public function convert_field_value_onsave($do_crm_fields,$evctl,$action='add') {
		$fieldobject = 'FieldType'.$do_crm_fields["field_type"];
		$field_name = $do_crm_fields["field_name"] ;
		if ($do_crm_fields["field_type"] == 3) {
				if ($evctl->$field_name == "on") { $value = 1 ;}else{ $value = 0 ;}
		} elseif ($do_crm_fields["field_type"] == 6 || $do_crm_fields["field_type"] == 9 
						|| $do_crm_fields["field_type"] == 10 || $do_crm_fields["field_type"] == 30) {
			$value = $fieldobject::convert_before_save($evctl->$field_name);
		} elseif ($do_crm_fields["field_type"] == 11) {
			$value = md5($evctl->$field_name);
		} elseif ($do_crm_fields["field_type"] == 12) {
			if ($_FILES[$field_name]['tmp_name'] != '') {
				$file_size = $_FILES[$field_name]['size'] ;
				if ($action == 'edit') {
					$hidden_file_name = 'upd_'.$field_name;
					$current_file_name_in_db = $evctl->$hidden_file_name ;
					FieldType12::remove_thumb($current_file_name_in_db);
					$value = FieldType12::upload_avatar($_FILES[$field_name]['tmp_name'],$_FILES[$field_name]['name']);
					$value["field_type"] = 12;
					$value["file_size"] = $file_size;
				} else {
					$value = FieldType12::upload_avatar($_FILES[$field_name]['tmp_name'],$_FILES[$field_name]['name']);
					$value["field_type"] = 12;
					$value["file_size"] = $file_size;
				}
			} else { 
				if ($action == 'edit') {
					$hidden_file_name = 'upd_'.$field_name;
					$current_file_name_in_db = $evctl->$hidden_file_name ;
					$value = $current_file_name_in_db ;
				} else {
					$value = '';
				}
			}
		} elseif ($do_crm_fields["field_type"] == 15) {
			$assigned_to_as_group = false ;
			$group_id = 0 ;
			$assigned_to = $evctl->assigned_to_selector;
			if ($assigned_to == 'user') {
				$fld_value = $evctl->user_selector ;
			} else {
				$fld_value = 0 ;
				$group_id = $evctl->group_selector ;
				$assigned_to_as_group = true ;
			}
			$value = array(
				"field_type"=>$do_crm_fields["field_type"],
				"value"=>$fld_value,
				"assigned_to_as_group"=>$assigned_to_as_group,
				"group_id"=>$group_id
			);
		} elseif($do_crm_fields["field_type"] == 165) {
			$field_name = $do_crm_fields["field_name"];
			$value_165 = array();
			$cnt = count($evctl->$field_name);
			if ($cnt > 0) {
				$i=1;
				foreach ($evctl->$field_name as $key=>$val) {
					$tax_value_fld = $field_name.'_'.$i ; 
					$value_165[] = array("tax_name"=>$val,"tax_value"=>$evctl->$tax_value_fld);
					$i++;
				}
				$value = array("field_type"=>$do_crm_fields["field_type"],"value"=>$value_165);
			}
		} else {
			$value = $evctl->$field_name ;
		}
		if (is_array($value)) 
			return $value;
		else
			return CommonUtils::purify_input($value) ;
	}
   
  /**
  * function to display field values depending on field type
  * @param mix $value
  * @param string $field_type
  * @param object $fieldobject
  * @param object $entiry_object
  * @param integer $module_id
  * @param boolean $format 
  */
	public function display_field_value($value,$field_type,$fieldobject,$entiry_object,$module_id,$format=true) {
		if ($field_type == 130) {
			if ($module_id == 4) {
				return $fieldobject::display_value($value,$entiry_object->contact_report_to,$format);
			} else {
				return $fieldobject::display_value($value,$entiry_object->contact_name,$format);
			}
		} elseif ($field_type == 142) {
			return $fieldobject::display_value($value,$entiry_object->contact_name,$format);
		} elseif($field_type == 131) {
			if ($module_id == 6) {
				return $fieldobject::display_value($value,$entiry_object->organization_member_of,$format);
			} else {
				return $fieldobject::display_value($value,$entiry_object->organization_name,$format);
			}
		} elseif ($field_type == 150) {
			return $fieldobject::display_value($value,$entiry_object->potentials_related_to_idmodule,$entiry_object->potentials_related_to_value,$format);
		} elseif ($field_type == 151) {
			return $fieldobject::display_value($value,$entiry_object->events_related_to_idmodule,$entiry_object->events_related_to_value,$format);
		} elseif ($field_type == 7 || $field_type == 8 ) {
			return $fieldobject::display_value($value,$format);
		} elseif ($field_type == 160) {
			return $fieldobject::display_value($value,$entiry_object->vendor_name,$format);
		} elseif ($field_type == 133) {
			return $fieldobject::display_value($value,$entiry_object->potential_name,$format);
		} elseif ($field_type == 170) {
			if ($module_id == 14) {
				return $fieldobject::display_value($value,$entiry_object->quote_subject,$format);
			} else {
				return $fieldobject::display_value($value,$entiry_object->subject,$format);
			}
		} elseif ($field_type == 180) {
			if ($module_id == 15) { 
				return $fieldobject::display_value($value,$entiry_object->so_subject,$format); 
			} else {
				return $fieldobject::display_value($value,$entiry_object->subject,$format);
			}
		} else {
			return $fieldobject::display_value($value);
		}
	}
	
	/**
	* function to get the filed information grouping by block
	* @param integer $idmodule
	* @param boolean $show_all
	* @return array
	* @see modules/Report/ReportFields::get_module_fields_for_report()
	*/
	public function get_fieldinfo_grouped_by_block($idmodule,$show_all=false) {
		$display = '';
		if (false === $show_all) {
			$display = " and f.display = 1 ";
		}
		
		$qry = "
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
		where f.idmodule = ? $display 
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
		return $fields ;
	}
	
	public function get_date_fields($idmodule,$show_all=false) {
		$display = '';
		if (false === $show_all) {
			$display = " and display = 1 ";
		}
		
		$qry = "
		select
		idfields,
		field_name,
		field_label
		from fields 
		where 
		field_type = 9
		and idmodule = ".(int)$idmodule."
		$display
		";
		$this->query($qry);
		$return_array = array();
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$data = array("idfields"=>$this->idfields,"field_label"=>$this->field_label);
				$return_array[] = $data ;
			}
		}
		return $return_array ; 
	}
}