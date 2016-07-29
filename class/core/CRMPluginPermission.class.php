<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMPluginPermission 
* Process the plugins permission
* @author Abhik Chakraborty
*/
	

class CRMPluginPermission extends DataObject {
	public $table = "plugins_permissions" ;
	public $primary_key = "idplugins_permissions" ;

	/**
	* function to add permission attributes
	* @param integer $id
	* @param integer $type (1:all users 2: only for the selected roles 3: only for the selected users)
	* @param array $attributes
	* @return void
	*/
	public function add_permission_attributes($id,$type,$attributes=array()) {
		if (count($attributes) > 0) {
			foreach ($attributes as $key=>$val) {
				if ($type == 2) {
					$qry = "
					insert into `plugins_permissions_attributes` 
					(`idplugins_permissions`,`idrole`) 
					values
					(?, ?)
					";
				} elseif ($type == 3) {
					$qry = "
					insert into `plugins_permissions_attributes` 
					(`idplugins_permissions`,`iduser`) 
					values
					(?, ?)
					";
				}
				$this->getDbConnection()->executeQuery($qry,array($id,$val));
			}
		}
	}
	
	/**
	* function to get the plugin permissions 
	* @param string $plugin_name
	* @return array
	*/
	public function get_plugins_permission($plugin_name) {
		$qry = "select * from `".$this->getTable()."` where `name` = ?";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($plugin_name));
		if ($stmt->rowCount() > 0) {
			$data = $stmt->fetch();
			$type = $data['type'];
			$id = $data['idplugins_permissions'];
			$roles_permission = array();
			$user_permission = array();
			if ($type == 2 || $type == 3) {
				$qry = "select * from `plugins_permissions_attributes` where `idplugins_permissions` = ?";
				$stmt1 = $this->getDbConnection()->executeQuery($qry,array($data['idplugins_permissions']));
				if ($stmt1->rowCount() > 0) {
					while ($attr_data = $stmt1->fetch()) {
						if ($type == 2) {
							$roles_permission[] = $attr_data['idrole'];
						} elseif ($type ==3) {
							$user_permission[] = $attr_data['iduser'];
						}
					}
				}
			}
			return array(
				'id'=>$id,
				'type'=>$type,
				'by_roles_data'=>$roles_permission,
				'by_users_data'=>$user_permission
			);
		} else {
			return false;
		}
	}
	
	/**
	* checks if the plugin is allowed to be accessed by the logged in user
	* @param string $plugin_name
	* @param integer $iduser
	* @return boolean
	*/
	public function is_plugin_allowed($plugin_name,$iduser=0) {
		if ((int)$iduser == 0) { 
			$iduser = $_SESSION['do_user']->iduser;
			$roleid = $_SESSION['do_user']->roleid;
			$is_admin = $_SESSION['do_user']->is_admin;
		} else {
			$do_user = new User();
			$do_user->getId($iduser);
			$iduser = $do_user->iduser;
			$roleid = $do_user->roleid;
			$is_admin = $do_user->is_admin;
		}
		
		$permission = $this->get_plugins_permission($plugin_name);
		
		if (false === $permission && $is_admin == 1) {
			return true;
		} elseif (false === $permission) {
			return false;
		}
		
		if ($is_admin == 1) {
			return true;
		} elseif ($permission['type'] == 1) {
			return true;
		} elseif ($permission['type'] == 2) {
			if (is_array($permission['by_roles_data']) && count($permission['by_roles_data']) > 0) {
				if (in_array($roleid,$permission['by_roles_data'])) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} elseif ($permission['type'] == 3) {
			if (is_array($permission['by_users_data']) && count($permission['by_users_data']) > 0) {
				if (in_array($iduser,$permission['by_users_data'])) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	}
}