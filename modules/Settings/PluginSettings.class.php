<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class PluginSettings
* Maintain the settings for the plugins
* @author Abhik Chakraborty
*/


class PluginSettings extends CRMPluginBase {
	public $table = "plugins";
	public $primary_key = "idplugins";
	
	/**
	* get available plugins by reading the plugin folder
	* @return array
	*/
	public function get_available_plugins() {
		$plugin_path = BASE_PATH.'/plugins/' ;
		return array_diff(scandir($plugin_path,1), array('..', '.'));
	}
	
	/**
	* get the plugin type description for settings
	* @param array $type
	* @param mix $pos 
	* @param return string
	*/
	public function get_plugin_type_description($type,$pos = null) {
		$string = '';
		if (in_array(1,$type) || in_array(2,$type) || in_array(3,$type) || in_array(4,$type) || in_array(5,$type) || in_array(6,$type)) {
			$string .= _('Action type plugin');
		} elseif (in_array(7,$type)) {
			$string .= _('Detail view plugin');
		}
		return $string ;
	}
	
	/**
	* get the activated plugins for setting page 
	* @return array 
	*/
	public function get_activated_plugins() {
		$this->load_active_plugins();
		return $this->get_active_plugins(); 
	}
	
	/**
	* event function to activate a plugin from setting page
	* @param object $evctl
	*/
	public function eventActivatePlugin(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			if (trim($evctl->plugin_name) != '') {
				if (false === $this->get_plugin_by_name($evctl->plugin_name)) {
					$this->addNew() ;
					$this->name = trim($evctl->plugin_name) ;
					$this->add() ;
					$plugin_id = $this->getInsertId() ;
					$this->do_sorting_on_activate(trim($evctl->plugin_name));
					echo $plugin_id ;
				}
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to delete record !'));
			$next_page = NavigationControl::getNavigationLink("Settings","plugis");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}
	
	/**
	* event function to deactivate plugin 
	* @param object $evctl
	*/
	public function eventDeactivatePlugin(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if ((int)$evctl->id > 0 && true === $permission) {
			$this->getId($evctl->id) ;
			if ($this->getNumRows() > 0) {
				$plugin_name = $this->name ;
				$qry = "
				delete from `".$this->getTable()."`
				where `idplugins` = ?
				";
				$this->query($qry,array($evctl->id));
				$this->call_on_deactivate_plugin_method($plugin_name) ;
				echo $plugin_name ;
			}
		}
	}
	
	/**
	* call the method while activating a plugin
	* the on_activate plugin must be implemented by the plugins and will be called during the activation
	* @param string $name
	*/
	public function call_on_activate_plugin_method($name) {
		include_once(BASE_PATH.'/plugins/'.$name.'/'.$name.'.class.php');
		$do_plugin_obj = new $name() ;
		if (method_exists($do_plugin_obj,'on_activate')) {
			$do_plugin_obj->on_activate() ;
		}
	}
	
	/**
	* call the method while deactivating a plugin
	* the on_deactivate plugin must be implemented by the plugins and will be called during the deactivation
	* @param string $name
	*/
	public function call_on_deactivate_plugin_method($name) {
		include_once(BASE_PATH.'/plugins/'.$name.'/'.$name.'.class.php');
		$do_plugin_obj = new $name() ;
		if (method_exists($do_plugin_obj,'on_deactivate')) {
			$do_plugin_obj->on_deactivate() ;
		}
	}
	
	/**
	* get the plugin by name (which is already activated)
	* @param string $name
	* @return mix
	*/
	public function get_plugin_by_name($name) {
		$this->query("select * from `".$this->getTable()."` where `name` = ?",array($name)) ;
		if ($this->getNumRows() > 0) {
			$this->next();
			return array(
				"id"=>$this->idplugins,
				"name"=>$this->name,
				"action_priority"=>$this->action_priority,
				"display_priority"=>$this->display_priority
			);
		} else {
			return false ;
		}
	}
	
	/**
	* do the plugin sorting while activating 
	* @param string $name
	*/
	public function do_sorting_on_activate($name) {
		include_once(BASE_PATH.'/plugins/'.$name.'/'.$name.'.class.php');
		$do_plugin_obj = new $name() ;
		if (in_array(7,$do_plugin_obj->get_plugin_type())) {
			$qry = "
			select max(display_priority) as display_priority
			from `".$this->getTable()."`
			";
			$this->query($qry);
			if ($this->getNumRows() > 0) {
				$this->next();
				if ((int)$this->display_priority > 0) {
					$display_priority = $this->display_priority + 1 ;
				} else {
					$display_priority = 1 ;
				}
				$qry = "
				update `".$this->getTable()."` 
				set `display_priority` = ?
				where `name` = ?
				";
				$this->query($qry,array($display_priority,$name));
			}
		} 
		if (in_array(1,$do_plugin_obj->get_plugin_type()) || in_array(2,$do_plugin_obj->get_plugin_type()) 
		|| in_array(3,$do_plugin_obj->get_plugin_type()) || in_array(4,$do_plugin_obj->get_plugin_type())
		|| in_array(5,$do_plugin_obj->get_plugin_type()) || in_array(6,$do_plugin_obj->get_plugin_type()) ) {
			$qry = "
			select max(action_priority) as action_priority
			from `".$this->getTable()."`
			";
			$this->query($qry);
			if ($this->getNumRows() > 0) {
				$this->next();
				if ((int)$this->action_priority > 0) {
					$action_priority = $this->action_priority + 1 ;
				} else {
					$action_priority = 1 ;
				}
				$qry = "
				update `".$this->getTable()."` 
				set `action_priority` = ?
				where `name` = ?
				";
				$this->query($qry,array($action_priority,$name));
			}
		}
	}
	
	/**
	* event function load the active plugins for sortable block
	* @param object $evctl
	*/
	public function eventLoadActivePluginSortable(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (false === $permission) echo '0' ;
		$return_array = array() ;
		$qry = "
		select * from `".$this->getTable()."`
		where `display_priority` > 0 
		order by `display_priority`
		" ;
		$this->query($qry) ;
		if ($this->getNumRows() > 0) {
			$detail_view_plugin = array() ;
			while ($this->next()) {
				$detail_view_plugin[$this->idplugins] = $this->name ;
			}
		}
		if (count($detail_view_plugin) > 1) {
			$html = '';
			$html .= '<div class="left_300"><p>'._('Set display priority for detail view plugins.').'</p></div><br />';
			$html .= '<ol class="serialization-detail-view vertical">' ;
			foreach ($detail_view_plugin as $key=>$val) {
				$html .= '<li data-id="'.$key.'" data-name="'.$val.'"><i class="icon-move"></i>'.$val.'</li>';
			}
			$html .= '</ol>';
			$return_array["detail_view_plugin"] = $html ;
		}
		
		$qry = "
		select * from `".$this->getTable()."`
		where `action_priority` > 0 
		order by `action_priority`
		" ;
		$this->query($qry) ;
		if ($this->getNumRows() > 0) {
			$action_plugin = array() ;
			while ($this->next()) {
				$action_plugin[$this->idplugins] = $this->name ;
			}
		}
		if (count($action_plugin) > 1) {
			$html = '';
			$html .= '<div class="left_300"><p>'._('Set display priority for detail view plugins.').'</p></div><br />';
			$html .= '<ol class="serialization-detail-view vertical">' ;
			foreach ($action_plugin as $key=>$val) {
				$html .= '<li data-id="'.$key.'" data-name="'.$val.'"><i class="icon-move"></i>'.$val.'</li>';
			}
			$html .= '</ol>';
			$return_array["action_plugin"] = $html ;
		}
		if (count($return_array) > 0) {
			echo json_encode($return_array) ;
		} else {
			echo '0' ;
		}
	}
	
	/**
	* event function to sort the plugins
	* @param object $evctl
	* @see self::do_sort_plugings()
	*/
	public function eventSortPlugins(EventControler $evctl) {	
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			$json = $evctl->jsonData ;
			$sort_array = json_decode($json,true) ;
			if (is_array($sort_array) && count($sort_array) > 0) {
				$this->do_sort_plugings($evctl->sort_type,$sort_array);
			}
		}
	}
	
	/**
	* sort the plugins
	* @param string $type
	* @param array $data
	*/
	public function do_sort_plugings($type,$data) {
		$sort_data = $data[0] ;
		if (is_array($sort_data) && count($sort_data) > 0 && ($type =='display_priority' || $type == 'action_priority')) {
			$column_name = ($type == 'display_priority' ? 'display_priority':'action_priority');
			foreach($sort_data as $key=>$val) {
				$qry = "
				update `".$this->getTable()."`
				set `".$column_name."` = ?
				where `idplugins` = ?
				" ;
				$this->query($qry,array($key+1,$val["id"])) ;
			}
		}
	}
}
