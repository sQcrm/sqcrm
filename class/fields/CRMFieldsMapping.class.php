<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class CRMFieldsMapping.
* @author Abhik Chakraborty
*/
	

class CRMFieldsMapping extends DataObject {
	public $table = "custom_field_mapping";
	public $primary_key = "idcustom_field_mapping";
	
	/*
	* holds the predefined fields mapping between leads.organization,contacts and potential.
	* any one of the mapped fields data changes will effect all other in the map list.
	* usage: pick list and multiselect options where changing one will get mapped to others.
	*/
	public $predefined_mappings = array(
		34=>array(56), // 33=> Leads Industry , 56=> Org Industry
		56=>array(34), // 33=> Leads Industry , 56=> Org Industry
		39=>array(57), // 39=> Leads Rating , 57=> Org Rating
		57=>array(39), // 39=> Leads Rating , 57=> Org Rating
		33=>array(80), // 33=> Leads Lead Source, 80=> Contact Lead Source
		80=>array(33), // 33=> Leads Lead Source, 80=> Contact Lead Source
		33=>array(116),// 33=> Leads Lead Source, 116=> Potential Lead Source
		116=>array(33)// 33=> Leads Lead Source, 116=> Potential Lead Source
	);

	/**
	* Function to check if the field is mapped for dropdown list / multiselect 
	* @param integer $idfields
	* @return array $return_data if mapped else boolean false
	* TODO Right now the mapping data is hardcoded so needs to be database driven 
	*/
	public function is_mapped($idfields) {
		if (array_key_exists($idfields,$this->predefined_mappings)) {
			return $this->predefined_mappings[$idfields] ;
		} 
		return false ;
	}

	/**
	* function to get the custom fields mapping
	* gets the fields which are mapped to other modules by Leads
	* @see view/customfield_mapping_view.php
	*/
	public function get_custom_field_mappings() {
		$qry_cst_fields = "
		select * from `fields` 
		where field_name like '%ctf_%' AND idmodule = 3 
		order by idfields ";
		$stmt = $this->getDbConnection()->executeQuery($qry_cst_fields);
		$mapping_array = array();
		while ($fields = $stmt->fetch()) {
			$data = $this->get_custom_field_mapped_detail($fields["idfields"]);
			if (count($data) > 0) {
				$mapping_array[$fields["idfields"]]["fieldlabel"] = $fields["field_label"];
				$mapping_array[$fields["idfields"]]["mapped_data"] = $data;
			} else {
				$mapping_array[$fields["idfields"]]["fieldlabel"] = $fields["field_label"];
				$mapping_array[$fields["idfields"]]["mapped_data"] = array();
			}
		}
		return $mapping_array;
	}
    
	/**
	* function to get the custom fields mapping details for a field(Leads)
	* @param integer $idfields
	* gets all the fields which are mapped to a field from Leads
	*/
	public function get_custom_field_mapped_detail($idfields) {
		$qry = "
		select org_ctf.field_label as org_ctf_field_label,
		cnt_ctf.field_label as cnt_ctf_field_label,
		pot_ctf.field_label as pot_ctf_field_label,
		custom_field_mapping.* from custom_field_mapping 
		left join fields as org_ctf on org_ctf.idfields = custom_field_mapping.organization_mapped_to 
		left join fields as cnt_ctf on cnt_ctf.idfields = custom_field_mapping.contacts_mapped_to 
		left join fields as pot_ctf on pot_ctf.idfields = custom_field_mapping.potentials_mapped_to
		where custom_field_mapping.mapping_field_id = ?";
		$this->query($qry,array($idfields));
		if ($this->getNumRows() > 0 ) {
			$this->next();
			$data = array(
				"organization"=>array(
					"mapped_fieldlabel"=>$this->org_ctf_field_label,
					"idfields"=>$this->organization_mapped_to
				),
				"contacts"=>array(
					"mapped_fieldlabel"=>$this->cnt_ctf_field_label,
					"idfields"=>$this->contacts_mapped_to
				),
				"potentials"=>array(
					"mapped_fieldlabel"=>$this->pot_ctf_field_label,
					"idfields"=>$this->potentials_mapped_to
				)
			);
			return $data ; 
		} else {
			return array();
		}
	}
    
	/**
	* event function to record the custom field mapping of leads
	* @param object $evctl
	*/
	public function eventMapLeadsCustomFields(EventControler $evctl) {
		$qry = "
		select * from `fields` 
		where field_name like '%ctf_%' AND idmodule = 3 
		order by idfields ";
		$stmt = $this->getDbConnection()->executeQuery($qry);
		if ($stmt->rowCount() > 0) {
			$q_clean_table = "TRUNCATE ".$this->getTable();
			$this->query($q_clean_table);
			while ($data = $stmt->fetch()) {
				$idfields_lead = $data["idfields"];
				$org_field_name = 'organization_map_'.$idfields_lead;
				$cnt_field_name = 'contacts_map_'.$idfields_lead;
				$pot_field_name = 'potentials_map_'.$idfields_lead;
				$org_map_field = (int) $evctl->$org_field_name ;
				$cnt_map_field = (int) $evctl->$cnt_field_name ;
				$pot_map_field = (int) $evctl->$pot_field_name ;
				if ($org_map_field == '' || $org_map_field == 0) $org_map_field = 0 ;
				if ($cnt_map_field == '' || $cnt_map_field == 0) $cnt_map_field = 0 ;
				if ($pot_map_field == '' || $pot_map_field == 0) $pot_map_field = 0 ;
				
				$this->insert(
					$this->getTable(),
					array(
						'mapping_field_id'=>$idfields_lead,
						'organization_mapped_to'=>$org_map_field,
						'contacts_mapped_to'=>$cnt_map_field,
						'potentials_mapped_to'=>$pot_map_field
					)
				);
			}
			$_SESSION["do_crm_messages"]->set_message('success',_('Fields mapping has been saved successfully !'));
			$next_page  = NavigationControl::getNavigationLink("Settings","customfield");
			$dis = new Display($next_page);
			$dis->addParam("sqrecord",$idprofile);
			$evctl->setDisplayNext($dis) ;
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('No mapping found to be saved !'));
			$next_page  = NavigationControl::getNavigationLink("Settings","customfield");
			$dis = new Display($next_page);
			$dis->addParam("sqrecord",$idprofile);
			$evctl->setDisplayNext($dis) ;
		}
	}
}