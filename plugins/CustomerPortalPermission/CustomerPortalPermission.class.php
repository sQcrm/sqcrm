<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Class CustomerPortalPermission
* Manages the customer portal related information for the organization
* @author Abhik Chakraborty
*/
class CustomerPortalPermission extends CRMPluginProcessor {
	public $table = "";
	public $primary_key = "";
	protected $hierarchy_data = array() ;
	public $role_id = '';
	public $role_hierarchy = array() ;
	public $used_roles = array() ;
	public $data_array = array() ;
	
	/**
	* constructor function for the sQcrm plugin
	*/
	public function __construct() {
		$this->set_plugin_title(_('Customer Portal Permission Management Plugin')); // required
		$this->set_plugin_name('CustomerPortalPermission') ; // required same as your class name 
		$this->set_plugin_type(array(7)); // required 
		$this->set_plugin_modules(array(6)); // required
		$this->set_plugin_position(2); // required
		$this->set_plugin_description(
			_('This plugin will help to manage the different permission levels for the customer portal logins.'
			)
		); // optional
		$this->set_plugin_tab_name('cPanel Permission');
	}
	
	/**
	* function to get the modules to be availble for the customer support
	* @param integer $idorganization
	* @return array
	*/
	public function get_cpanel_modules($idorganization) {
		$qry = "
		select 
		m.idmodule,
		m.name,
		m.module_label, 
		case when cmo.idmodule is not null then 1 else 0 end as activated 
		from module m join cpanel_modules cm on cm.idmodule = m.idmodule 
		left join cpanel_modules_org cmo on cmo.idmodule = m.idmodule and cmo.idorganization = ?
		" ;
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idorganization));
		$return_array = array() ;
		if ($stmt->rowCount() > 0) {
			while ($data = $stmt->fetch()) {
				$return_array[] = array(
					"idmodule" => $data["idmodule"],
					"name" => $data["name"],
					"module_label" => $data["module_label"],
					"activated" => $data["activated"],
				);
			}
		}
		return $return_array ;
	}
	
	/**
	* delete the customer portal user hierarchy
	* @param integer $idorganization
	* @see self::eventSaveRoleHierarchy()
	*/
	public function delete_cpanel_user_hierarchy($idorganization) {
		$qry = "delete from cpanel_user_roles where idorganization = ?";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idorganization));
	}
	
	/**
	* function to delete the cpanel modules mapping for the organization
	* @param integer $idmodule
	* @return void
	* @see self::eventSaveCpanelModules()
	*/
	public function delete_cpanel_modules($idorganization) {
		$qry = "delete from cpanel_modules_org where idorganization = ?";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idorganization));
	}
	
	/**
	* event function to save the customer portal user hierarchy 
	* @param object $evctl
	* @return string
	* @see self::delete_cpanel_user_hierarchy()
	* @see self::create_cpanel_user_hierarchy()
	*/
	public function eventSaveRoleHierarchy(EventControler $evctl) {
		$err = '';
		if ((int)$evctl->sqcrm_record_id > 0 ) {
			$data = json_decode($evctl->data,true) ;
			if (is_array($data) && count($data) > 0) {
				$this->delete_cpanel_user_hierarchy($evctl->sqcrm_record_id) ;
				$this->create_cpanel_user_hierarchy($data,$evctl->sqcrm_record_id) ;
			} else {
				$err = _('Missing hierarchy data') ;
			}
		} else {
			$err = _('Missing organization id') ;
		}
		if (strlen($err) > 3) {
			echo $err ;
		} else {
			echo '1' ;
		}
	}
	
	/**
	* event function to save the modules for customer portal
	* @param object $evctl
	* @return string
	* @see self::delete_cpanel_modules()
	* @see self::activate_cpanel_modules()
	*/
	public function eventSaveCpanelModules(EventControler $evctl) {
		$err = '';
		if ((int)$evctl->sqcrm_record_id > 0 ) {
			$data = explode(',',$evctl->data) ;
			if (is_array($data) && count($data) > 0) {
				$this->delete_cpanel_modules($evctl->sqcrm_record_id) ;
				$this->activate_cpanel_modules($data,$evctl->sqcrm_record_id) ;
			} else {
				$$this->delete_cpanel_modules($evctl->sqcrm_record_id) ;
			}
		} else {
			$err = _('Missing organization id') ;
		}
		if (strlen($err) > 3) {
			echo $err ;
		} else {
			echo '1' ;
		}
	}
	
	/**
	* function to create the customer support user hierarchy
	* @param array $data
	* @param integer $idorganization
	* @param string $parentrole
	* @return void
	*/
	public function create_cpanel_user_hierarchy($data,$idorganization,$parentrole="") {
		if (is_array($data)) {
			foreach ($data as $key=>$val) {
				if ($this->role_id == '') {
					$role_id = 'N1' ;
					$this->role_id = $role_id ;
					$parentrole_ins = 'N1' ;
				} else {
					$role_int = str_replace("N","",$this->role_id);
					$new_role_int = $role_int+1;
					$role_id = "N".$new_role_int;
					$this->role_id = $role_id ;
					$parentrole_ins = $parentrole.'::'.$role_id ;
				}
				
				if ($parentrole == '') {
					$parentrole_ins = $role_id ;
				} else {
					$parentrole_ins = $parentrole.'::'.$role_id ;
				}
				$this->insert_cpanel_user_roles($idorganization,$role_id,$parentrole_ins,$val["id"]) ;
				if (array_key_exists('children',$val) && count($val['children']) > 0) {
					$this->create_cpanel_user_hierarchy($val['children'],$idorganization,$parentrole_ins) ;
				}
			}
		}
	}
	
	/**
	* function insert the customer support hierarchy information
	* @param integer $idorganization
	* @param string $role_id
	* @param string parentrole
	* @param integer $idcpanel_user
	* @return void
	*/
	public function insert_cpanel_user_roles($idorganization,$role_id,$parentrole,$idcpanel_user) {
		$qry = "
		insert into cpanel_user_roles
		(idorganization,idcpanel_user,roleid,parentrole)
		values
		(?,?,?,?)
		" ;
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idorganization,$idcpanel_user,$role_id,$parentrole));
	}
	
	/**
	* function to get the customer support users
	* get the users who are in hierarchy and who are not in hierarchy
	* @param integer $idorganization
	* @return array
	* @see self::get_cpanel_users_hierarchy()
	* @see self::get_cpanel_users_not_in_hierarchy()
	*/
	public function get_cpanel_users($idorganization) {
		$users = array() ;
		$hierarchy_enabled = $this->get_cpanel_users_hierarchy($idorganization) ;
		if (count($hierarchy_enabled) > 0) {
			$users = $hierarchy_enabled ;
		}
		$hierarchy_not_enabled = $this->get_cpanel_users_not_in_hierarchy($idorganization) ;
		if (count($hierarchy_not_enabled) > 0) {
			foreach ($hierarchy_not_enabled as $key=>$val) {
				$users[] = $val ;
			}
		}
		return $users ;
	}
	
	/**
	* function to get the customer support who are added as hierarchy
	* @param integer $idorganization
	* @param string $parentrole
	* @return array 
	*/
	public function get_cpanel_users_hierarchy($idorganization,$parentrole='') {
		if ($parentrole == '') {
			$qry = "
			select c.firstname,c.lastname,cu.email,cpr.* from cpanel_user_roles cpr 
			join cpanel_user cu on cu.idcpanel_user = cpr.idcpanel_user
			join contacts c on c.idcontacts = cu.idcontacts
			where cpr.idorganization = ? and cpr.roleid = cpr.parentrole and cu.idorganization = ?
			order by cpr.roleid
			";
			$stmt = $this->getDbConnection()->executeQuery($qry,array($idorganization,$idorganization));
		} else {
			$qry = "
			select c.firstname,c.lastname,cu.email,cpr.* from cpanel_user_roles cpr 
			join cpanel_user cu on cu.idcpanel_user = cpr.idcpanel_user
			join contacts c on c.idcontacts = cu.idcontacts
			where cpr.idorganization = ? and cpr.parentrole like ? and cu.idorganization = ?
			order by cpr.roleid
			";
			$stmt = $this->getDbConnection()->executeQuery($qry,array($idorganization,$parentrole.'::%',$idorganization));
		}
		$return_array = array() ;
		if ($stmt->rowCount() > 0) {
			while ($data = $stmt->fetch()) {
				if (!in_array($data["idcpanel_user"],$this->used_roles)) {
					$this->used_roles[] = $data["idcpanel_user"] ;
					$temp_data = array();
					$temp_data["id"] = $data["idcpanel_user"] ;
					$temp_data["name"] = $data["firstname"].' '.$data["lastname"].' ('.$data["email"].')' ;
					$temp_data["is_open"] = true ;
					$temp_data["children"] = $this->get_cpanel_users_hierarchy($idorganization,$data["parentrole"]) ;
					$return_array[] = $temp_data ;
					unset($temp_data);
				}
			}
		}
		return $return_array ;
	}
	
	/**
	* function to get the customer support users who are not added in the hierarchy
	* @param integer $idorganization
	* @return array
	*/
	public function get_cpanel_users_not_in_hierarchy($idorganization) {
		$qry = "
		select c.firstname,c.lastname,cu.* from cpanel_user cu
		left join cpanel_user_roles cpr on cpr.idcpanel_user = cu.idcpanel_user and cu.idorganization = ?
		join contacts c on c.idcontacts = cu.idcontacts
		where cpr.idcpanel_user is null		
		";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idorganization));
		$return_array = array() ;
		if ($stmt->rowCount() > 0) {
			while ($data = $stmt->fetch()) {
				$return_array[] = array(
					"id" => $data["idcpanel_user"],
					"name" => $data["firstname"].' '.$data["lastname"].' ('.$data["email"].')',
					"is_open" => true 
				);
			}
		}
		return $return_array ;
	}
	
	/**
	* function to activate the cpanel module mapping
	* @param array $data
	* @param integer $idmodule
	* @return void
	* @see self::insert_cpanel_modules()
	*/
	public function activate_cpanel_modules($data,$idorganization) {
		if (is_array($data) && count($data) >0) {
			foreach($data as $idmodule) {
				$this->insert_cpanel_modules($idmodule,$idorganization) ;
			}
		}
	}
	
	/**
	* function to insert the cpanel module mapping
	* @param integer $idmodule
	* @param integer $idorganization
	* @return void
	*/
	public function insert_cpanel_modules($idmodule,$idorganization) {
		$qry = "
		insert into cpanel_modules_org
		(idorganization,idmodule)
		values
		(?,?)
		" ;
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idorganization,$idmodule));
	}
}