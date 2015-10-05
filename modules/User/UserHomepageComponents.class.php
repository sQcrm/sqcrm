<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class UserHomepageComponents
* @author Abhik Chakraborty
*/ 

class UserHomepageComponents extends DataObject {
    
	public $table = "user_homepage_component";
	protected $primary_key = "iduser_homepage_component";
	
	function __construct(sqlConnect $conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
    
	/**
	* function to get the user home page component
	* @param integer $iduser
	* @return array $return_array if data found else return false
	*/
	public function get_user_homepage_components($iduser) {
		$qry = "
		select hc.*
		from homepage_component hc
		join user_homepage_component uhc on uhc.idhomepage_component = hc.idhomepage_component
		where uhc.iduser = ?
		order by hc.position,hc.sequence
		";
		$this->query($qry,array($iduser));
		if ($this->getNumRows() > 0) {
			$return_array = array();
			while ($this->next()) {
				$data = array(
					"id"=>$this->idhomepage_component,
					"component_name"=>$this->component_name,
					"position"=>$this->position,
					"sequence"=>$this->sequence
				);
				$return_array[] = $data;
			}
			return $return_array ;
		} else { return false ; }
	}
    
	/**
	* event function to save the user's home page component 
	* @param object $evctl
	*/
	public function eventAjaxSaveUserHomepageComponent(EventControler $evctl) {
		$iduser =  $evctl->iduser;
		$idhome_page_components = $evctl->home_page_component;
		if (is_array($idhome_page_components) && count($idhome_page_components) > 0 && (int)$iduser > 0) {
			foreach ($idhome_page_components as $id) {
				$this->addNew();
				$this->iduser = (int)$iduser;
				$this->idhomepage_component = (int)$id;
				$this->add();
			}
			echo _('Data added successfully !');
		}
	}
    
	/**
	* event function to update the user's home page component
	* @param object $evctl
	*/
	public function eventAjaxUpdateUserHomepageComponent(EventControler $evctl) {
		$iduser =  $evctl->iduser;
		$idhome_page_components = $evctl->home_page_component;
		$this->query("delete from ".$this->getTable()." where iduser = ? ",array($iduser));
		if (is_array($idhome_page_components) && count($idhome_page_components) > 0 && (int)$iduser > 0) {
			foreach ($idhome_page_components as $id) {
				$this->addNew();
				$this->iduser = (int)$iduser;
				$this->idhomepage_component = (int)$id;
				$this->add();
			}
			echo _('Data updated successfully !');
		}
	}
}