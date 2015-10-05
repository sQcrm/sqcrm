<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMEntityRecentlyViewed 
* @author Abhik Chakraborty
*/

class CRMEntityRecentlyViewed extends DataObject {
	public $table = "breadcrumb";
	public $primary_key = "idbreadcrumb";
  
	/**
	* function to add record as recently viewed
	* @param integer $idrecord
	* @param integer $idmodule
	* @param integer $iduser
	*/
	public function add_recently_viewed($idrecord,$idmodule,$iduser='') {
		if ($iduser == '') $iduser = $_SESSION["do_user"]->iduser ;
		if ($this->is_in_recently_viewed($idrecord,$idmodule,$iduser) === false) {
			$this->addNew();
			$this->idrecord = $idrecord ;
			$this->idmodule = $idmodule ;
			$this->iduser = $iduser ;
			$this->date_added = date("Y-m-d H:i:s");
			$this->add();
		} else {
			$qry = "
			update `".$this->getTable()."` set `date_added` = ? 
			where `idrecord` = ?
			AND `idmodule` = ?
			AND `iduser` = ?
			limit 1 ";
			$this->query($qry,array(date("Y-m-d H:i:s"),$idrecord,$idmodule,$iduser));
		}
	}
  
	/**
	* function to check if a record if already there in the recently viewed table
	* @param integer $idrecord
	* @param integer $idmodule
	* @param integer $iduser
	* @return boolean
	*/
	public function is_in_recently_viewed($idrecord,$idmodule,$iduser='') {
		if($iduser == '') $iduser = $_SESSION["do_user"]->iduser ;
		$qry = "
		select * from `".$this->getTable()."` 
		where `idrecord` = ?
		AND `idmodule` = ?
		AND `iduser` = ?";
		$this->query($qry,array($idrecord,$idmodule,$iduser));
		if ($this->getNumRows() > 0) {
			return true ;
		} else { return false ; }
	}
  
	/**
	* event function to load the recently viewed infomation
	* @param object $evctl
	* @return string html
	*/
	function eventAjaxLoadRecentlyViewed(EventControler $evctl) {
		$html = '';
		$iduser = $_SESSION["do_user"]->iduser ;
		$this->get_recently_viewed($iduser) ; 
		if ($this->getNumRows() > 0 ) {
			$do_crm_entity = new CRMEntity();
			$html .= '<ul id="recently_viewed">
						<li><a href="" class="current">'._('Recently Viewed').'</a></li>
					';
			while ($this->next()) {
				$identifier = '';
				$record_url = '';
				$module = $_SESSION["do_module"]->modules_full_details[$this->idmodule]["name"];
				$identifier = $do_crm_entity->get_entity_identifier($this->idrecord,$module);
				$record_url = NavigationControl::getNavigationLink($module,"detail",$this->idrecord);
				$html .= '<li><a href="'.$record_url.'">'.$identifier.'</a></li>';
			}
			$html .='</ul>';
		}
		echo $html ;
	}
  
	/**
	* function to get the recently viewed data
	* @param integer $iduser
	*/
	public function get_recently_viewed($iduser) {
		$qry = "
		select * from `".$this->getTable()."` 
		where `iduser` = ? 
		order by `date_added` desc limit ".$GLOBALS['MAX_RECENT_VIEW'];
		$this->query($qry,array($iduser));
	}
  
}