<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ModuleToDatashareRelation
* Maintain the module datashare permissions
* @author Abhik Chakraborty
*/


class ModuleToDatashareRelation extends DataObject {
	public $table = "module_datashare_rel";
	public $primary_key = "idmodule_datashare_rel";
    
	/**
	* Function to get the datashare permissions for module
	* The datashare permissions for each active module could be
	* 1 - Public : Read Only
	* 2 - Public : Read/Edit
	* 3 - Public : Read/Edit/Delete
	* 4 - Private
	* NOTE : This is the top level permission, if its set to 1,2 or 3 then irrespective of the permission
	* set in the profile, this top level permission will be considered across the CRM.
	* It is always recomended to set the permission to private - 4 so that the permission for each profile is set for the CRM
	* In some special cases if we want some of the module data is accessible to all level of users we can choose 1,2 or 3
	* depending on the requirement from these
	* @see modules/Settings/Profile.class.php
	* @see modules/Settings/Roles.class.php
	* @see modules/User/User.class.php
	*/
	public function get_module_datashare_permissions() {
		$qry = "
		select module_datashare_rel.idmodule_datashare_rel,module_datashare_rel.idmodule,datashare_standard_permission.*,
		module.name,module.module_label from module_datashare_rel
		inner join module on module.idmodule = module_datashare_rel.idmodule
		inner join datashare_standard_permission on datashare_standard_permission.iddatashare_standard_permission = module_datashare_rel.permission_flag
		where module.active = 1 order by module.module_sequence";
		$this->query($qry);
	}

	/**
	* function to update the datashare permission across the module
	* @param object $evctl
	*/
	public function eventUpdateModuleDataShareRel(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			$qry = "select idmodule_datashare_rel,idmodule from `".$this->getTable()."`";
			$stmt = $this->getDbConnection()->executeQuery($qry);
			while ($data = $stmt->fetch()) {
				$datashare_permission_form_name = 'mod_'.$data["idmodule"];
				$permission_flag = $evctl->$datashare_permission_form_name;
				$this->cleanValues();
				$this->permission_flag = $permission_flag ;
				$this->update($data["idmodule_datashare_rel"]);
			}
			$dis = new Display($evctl->next_page);
			$evctl->setDisplayNext($dis) ;
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to add record ! '));
			$next_page = NavigationControl::getNavigationLink("Settings","index");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}

	/**
	* function to get all datashare permissions
	* @return array
	*/
	public function get_all_datashare_permissions() {
		$qry = "select * from ".$this->getTable();
		$this->query($qry);
		$data_share_permission = array();
		while ($this->next()) {
			$data_share_permission[$this->idmodule] = $this->permission_flag ;
		}
		return $data_share_permission ;
	}
      
}