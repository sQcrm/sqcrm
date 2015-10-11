<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class CustomView 
* @author Abhik Chakraborty
*/ 
	

class CustomView extends DataObject {
	public $table = "custom_view";
	public $primary_key = "idcustom_view";
	
	
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
	
	/**
	* function to get the custom views for the module
	* @param integer $idmodule 
	* @param integer $iduser
	* @return array
	*/
	public function get_custom_views($idmodule,$iduser = 0 ) {
		if ((int)$iduser == 0) $iduser = $_SESSION["do_user"]->iduser ;
		$qry = "
		select * from `".$this->getTable()."`
		where `idmodule` = ?
		and `deleted` = 0 
		and (`iduser` = ? or `is_public` = 1 )
		order by name
		";
		$this->query($qry,array($idmodule,$iduser));
		$return_array = array();
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$return_array[] = array(
					"idcustom_view"=> $this->idcustom_view,
					"name"=> $this->name,
					"iduser"=> $this->iduser,
					"is_editable"=> $this->is_editable,
					"is_default"=>$this->is_default
				);
			}
		}
		return $return_array ;
	}
	
	/**
	* check if module has custom view by idmodule
	* @param integer $idmodule
	* @return boolean
	*/
	public function has_custom_view($idmodule) {
		$qry = "select * from `custom_view_module_rel` where `idmodule` = ? ";
		$this->query($qry,array((int)$idmodule));
		if ($this->getNumRows() > 0 ) {
			return true ;
		} else {
			return true ;
		}
	}
	
	/**
	* get the default custom view by idmodule
	* @param integer $idmodule
	* @return integer
	*/
	public function get_default_custom_view($idmodule) {
		$qry = "select * from `".$this->getTable()."` where `idmodule` = ? and `is_default` = 1";
		$this->query($qry,array((int)$idmodule));
		$this->next();
		return $this->idcustom_view ;
	}
	
	/**
	* event function to add a custom view
	* @param object $evctl
	* @return void
	*/
	public function eventAddRecord(EventControler $evctl) { 
		if (trim($evctl->cvname) == '') { 
			$_SESSION["do_crm_messages"]->set_message('error',_('Please add a custom view name before saving !'));
			$next_page = NavigationControl::getNavigationLink("CustomView","add");
			$dis = new Display($next_page); 
			$dis->addParam("target_module_id",(int)$evctl->target_module_id);
			$evctl->setDisplayNext($dis);
		} elseif ((int)$evctl->target_module_id == 0) {
			$_SESSION["do_crm_messages"]->set_message('error',_('Missing target module for custom view !'));
			$next_page = NavigationControl::getNavigationLink("CustomView","add");
			$dis = new Display($next_page); 
			$evctl->setDisplayNext($dis);
		} elseif (false === $_SESSION["do_crm_action_permission"]->action_permitted('add',17)) {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to add record !'));
			$next_page = NavigationControl::getNavigationLink($_SESSION["do_module"]->modules_full_details[$evctl->target_module_id]["name"],"list");
			$dis = new Display($next_page); 
			$evctl->setDisplayNext($dis);
		} else {
			$this->addNew();
			$this->name = $evctl->cvname ;
			$this->iduser = $_SESSION["do_user"]->iduser ;
			$this->is_default = ($evctl->is_default == 'on' ? 1 : 0) ;
			if ($_SESSION["do_user"]->is_admin == 1) {
				$this->is_public = ($evctl->is_public == 'on' ? 1 : 0) ;
			}
			$this->idmodule = (int)$evctl->target_module_id ;
			$this->is_editable = 1 ;
			$this->add() ;
			$idcustom_view = $this->getInsertId();
			
			//reset default custom view if is_default is set 
			if ($evctl->is_default == 'on') {
				$this->reset_default_custom_view($idcustom_view,$evctl->target_module_id);
			}
			
			//add custom view fields
			$do_custom_view_fields = new CustomViewFields();
			$do_custom_view_fields->add_custom_view_fields($idcustom_view,$evctl->cv_fields);
			
			//add custom view filter
			$do_custom_view_filter = new CustomViewFilter();
			$do_custom_view_filter->add_custom_view_date_filter($idcustom_view,$evctl->cv_date_field,$evctl->cv_date_field_type,$evctl->cv_date_start,$evctl->cv_date_end);
			
			//add advanced filter 
			$adv_filter_data = array(
				"cv_adv_fields_1"=>$evctl->cv_adv_fields_1,
				"cv_adv_fields_type_1"=>$evctl->cv_adv_fields_type_1,
				"cv_adv_fields_val_1"=>$_POST["cv_adv_fields_val_1"],
				"cv_adv_fields_2"=>$evctl->cv_adv_fields_2,
				"cv_adv_fields_type_2"=>$evctl->cv_adv_fields_type_2,
				"cv_adv_fields_val_2"=>$_POST["cv_adv_fields_val_2"],
				"cv_adv_fields_3"=>$evctl->cv_adv_fields_3,
				"cv_adv_fields_type_3"=>$evctl->cv_adv_fields_type_3,
				"cv_adv_fields_val_3"=>$_POST["cv_adv_fields_val_3"],
				"cv_adv_fields_4"=>$evctl->cv_adv_fields_4,
				"cv_adv_fields_type_4"=>$evctl->cv_adv_fields_type_4,
				"cv_adv_fields_val_4"=>$_POST["cv_adv_fields_val_4"],
				"cv_adv_fields_5"=>$evctl->cv_adv_fields_5,
				"cv_adv_fields_type_5"=>$evctl->cv_adv_fields_type_5,
				"cv_adv_fields_val_5"=>$_POST["cv_adv_fields_val_5"],
			);
			$do_custom_view_filter->add_custom_view_adv_filter($idcustom_view,$adv_filter_data);
			
			//redirect after adding the custom view
			$next_page = NavigationControl::getNavigationLink($_SESSION["do_module"]->modules_full_details[$evctl->target_module_id]["name"],"list",'','&custom_view_id='.$idcustom_view);
			$dis = new Display($next_page); 
			$evctl->setDisplayNext($dis);
		}
	}
	
	/**
	* function to edit custom view
	* @param object $evctl
	* @return void
	*/
	public function eventEditRecord(EventControler $evctl) { 
		$do_edit = true ;
		$error_message = '';
		if ((int)$evctl->sqrecord == 0) {
			$error_message = _('Missing record id');
			$do_edit = false ;
		} elseif ((int)$evctl->target_module_id == 0) {
			$error_message = _('Missing target module for custom view !');
			$do_edit = false ;
		} elseif (trim($evctl->cvname) == '') {
			$error_message = _('Please add a custom view name before saving !');
			$do_edit = false ;
		} elseif ((int)$evctl->sqrecord > 0) {
			$this->getId($evctl->sqrecord) ;
			if ($this->getNumRows() > 0) {
				if ($module_obj->iduser != $_SESSION["do_user"]->iduser) {
					$error_message = _('You do not have permission to edit the record !');
					$do_edit = false  ;
				} 
				if ($module_obj->is_editable == 0) {
					$error_message = _('You do not have permission to edit the record !');
					$do_edit = false  ;
				}
				if ($module_obj->deleted == 1) {
					$error_message = _('You do not have permission to edit the record !');
					$do_edit = false  ;
				}
			} else {
				$error_message = _('Custom view not found !');
				$do_edit = false ;
			}
		}
		
		if (true === $do_edit) {
			$idcustom_view = (int)$evctl->sqrecord ;
			$this->getId($idcustom_view) ;
			$is_public = $this->is_public ;
			$is_default =  ($evctl->is_default == 'on' ? 1 : 0) ;
			if ($_SESSION["do_user"]->is_admin == 1) {
				$is_public = ($evctl->is_public == 'on' ? 1 : 0) ;
			}
			$qry = "
			update `".$this->getTable()."`
			set 
			`name` = ? ,
			`is_default` = ? ,
			`is_public` = ? 
			where 
			`idcustom_view` = ?
			";
			$this->query($qry,array($evctl->cvname,$is_default,$is_public,$evctl->sqrecord));
			
			//reset default custom view if is_default is set 
			if ($evctl->is_default == 'on') {
				$this->reset_default_custom_view($idcustom_view,$evctl->target_module_id);
			}
			
			//update custom view fields
			$do_custom_view_fields = new CustomViewFields();
			$do_custom_view_fields->update_custom_view_fields($evctl->sqrecord,$evctl->cv_fields);
			
			//add custom view filter
			$do_custom_view_filter = new CustomViewFilter();
			$do_custom_view_filter->update_custom_view_date_filter($evctl->sqrecord,$evctl->cv_date_field,$evctl->cv_date_field_type,$evctl->cv_date_start,$evctl->cv_date_end);
			
			//update advanced filter 
			$adv_filter_data = array(
				"cv_adv_fields_1"=>$evctl->cv_adv_fields_1,
				"cv_adv_fields_type_1"=>$evctl->cv_adv_fields_type_1,
				"cv_adv_fields_val_1"=>$_POST["cv_adv_fields_val_1"],
				"cv_adv_fields_2"=>$evctl->cv_adv_fields_2,
				"cv_adv_fields_type_2"=>$evctl->cv_adv_fields_type_2,
				"cv_adv_fields_val_2"=>$_POST["cv_adv_fields_val_2"],
				"cv_adv_fields_3"=>$evctl->cv_adv_fields_3,
				"cv_adv_fields_type_3"=>$evctl->cv_adv_fields_type_3,
				"cv_adv_fields_val_3"=>$_POST["cv_adv_fields_val_3"],
				"cv_adv_fields_4"=>$evctl->cv_adv_fields_4,
				"cv_adv_fields_type_4"=>$evctl->cv_adv_fields_type_4,
				"cv_adv_fields_val_4"=>$_POST["cv_adv_fields_val_4"],
				"cv_adv_fields_5"=>$evctl->cv_adv_fields_5,
				"cv_adv_fields_type_5"=>$evctl->cv_adv_fields_type_5,
				"cv_adv_fields_val_5"=>$_POST["cv_adv_fields_val_5"],
			);
			$do_custom_view_filter->update_custom_view_adv_filter($idcustom_view,$adv_filter_data);
			
			//redirect after adding the custom view
			$next_page = NavigationControl::getNavigationLink($_SESSION["do_module"]->modules_full_details[$evctl->target_module_id]["name"],"list",'','&custom_view_id='.$idcustom_view);
			$dis = new Display($next_page); 
			$evctl->setDisplayNext($dis);
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',$error_message);
			$next_page = NavigationControl::getNavigationLink("CustomView","edit",$idcustom_view);
			$dis = new Display($next_page); 
			$evctl->setDisplayNext($dis);
		}
	}
	
	/**
	* function to reset the default custom view during add/edit 
	* @param integer $idcustom_view
	* @param integer $idmodule
	* @return void
	*/
	public function reset_default_custom_view($idcustom_view,$idmodule) {
		$qry = "
		update `".$this->getTable()."`
		set 
		is_default = 0
		where 
		`idcustom_view` <> ?
		and `idmodule` = ?
		and `iduser` = ?
		";
		$this->query($qry,array($idcustom_view,$idmodule,$_SESSION["do_user"]->iduser));
	}
	
	/**
	* event function to delete custom view
	* @param object $evctl
	* @return mix
	*/
	function eventAjaxDeleteCustomView(EventControler $evctl) {
		$do_delete = true ;
		$is_default = false ;
		if ((int)$evctl->sqrecord > 0 && (int)$evctl->idmodule > 0) {
			$this->getId($evctl->sqrecord) ;
			if ($this->getNumRows() > 0) {
				if ($this->iduser != $_SESSION["do_user"]->iduser) $do_delete = false ;
				if ($this->is_editable == 0) $do_delete = false ;
				if ($this->deleted == 1) $do_delete = false ;
				if ($this->is_default == 1) $is_default = true ;
			} else {
				$do_delete = false ;
			}
		} else {
			$do_delete = false ;
		}
		
		if (true === $do_delete) {
			$qry = "
			update `".$this->getTable()."`
			set `deleted` = 1
			where 
			`idcustom_view` = ?
			" ;
			$this->query($qry,array($evctl->sqrecord));
			
			if (true === $is_default) {
				$qry = "
				update `".$this->getTable()."`
				set 
				`is_default` = 1
				where 
				`idmodule` = ? 
				and `is_editable` = 0
				" ;
				$this->query($qry,array($evctl->idmodule));
			}
			$_SESSION[$module]["pinned_list_view"] = 0;
			echo $this->get_default_custom_view($evctl->idmodule) ;
		} else {
			echo '0' ;
		}
	}
}