<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMPluginBase 
* @author Abhik Chakraborty
*/

class CRMPluginBase extends DataObject {
	public $table = "plugins" ;
	public $primary_key = "idplugins" ;
	
	// holds the plugin name
	protected $plugin_name = '' ;
	
	// holds the plugin type
	protected $plugin_type = array() ;
	
	// holds the plugin type
	protected $plugin_title = '' ;
	
	// holds the plugin modules
	protected $plugin_modules = array();
	
	// holds the plugin position
	protected $plugin_position = 0 ;
	
	// holds the active plugins
	protected $active_plugins = array() ;
	
	// holds the resource/file name
	protected $resource_name = '';
	
	// holds the plugin description
	protected $plugin_description = '' ;
	
	// holds the tab name of the plugin (detail view top tab)
	protected $plugin_tab_name = '';
	
	// holds error
	protected $error = '' ;
	
	/**
	* set the plugin name 
	* @param string $plugin_name
	*/
	public function set_plugin_name($plugin_name) {
		$this->plugin_name = $plugin_name ;
	}
	
	/**
	* get the plugin name
	* @return string
	*/
	public function get_plugin_name() {
		return $this->plugin_name ;
	}
	
	/**
	* set the plugin type
	* @param array $type
	*/
	public function set_plugin_type($type) {
		$this->plugin_type = $type ;
	}
	
	/**
	* get the plugin type
	* @return integer
	*/
	public function get_plugin_type() {
		return $this->plugin_type ;
	}
	
	/**
	* set the plugin title 
	* @param string
	*/
	public function set_plugin_title($title) {
		$this->plugin_title = $title ;
	}
	
	/**
	* get the plugin title
	* @return string
	*/
	public function get_plugin_title() {
		return $this->plugin_title ;
	}
	
	/**
	* set the plugin modules 
	* @param array $modules
	*/
	public function set_plugin_modules($modules) {
		$this->plugin_modules = $modules ;
	}
	
	/**
	* get the plugin modules
	* @return array
	*/
	public function get_plugin_modules() {
		return $this->plugin_modules ;
	}
	
	/**
	* set the plugin postion
	* @param integer $position
	*/
	public function set_plugin_position($position) {
		$this->plugin_position = $position ;
	}
	
	/**
	* get the plugin position
	* @return integer
	*/
	public function get_plugin_position() {
		return $this->plugin_position ;
	}
	
	/**
	* set the resource/file name
	* @param string $resource_name
	*/
	public function set_resource_name($resource_name) {
		$this->resource_name = $resource_name ;
	}
	
	/**
	* get the resource name
	* @return string
	*/
	public function get_resource_name() {
		return $this->resource_name ;
	}
	
	/**
	* set the plugin description 
	* @param string $description
	*/
	public function set_plugin_description($description) {
		$this->plugin_description = $description ;
	}
	
	/**
	* get the plugin description
	* @return string
	*/
	public function get_plugin_description() {
		return $this->plugin_description ;
	}
	
	/**
	* get the active plugins
	* @return array
	* @see self::load_active_plugins
	*/
	public function get_active_plugins() {
		return $this->active_plugins ;
	}
	
	/**
	* raise the error
	* @param string $error
	*/
	public function raise_error($error) {
		$this->error = $error ;
	}
	
	/**
	* set the tab name of the plugin (detail view top tab)
	* @param string $tab_name 
	*/
	public function set_plugin_tab_name($tab_name) {
		$this->plugin_tab_name = $tab_name ;
	}
	
	/**
	* get the plugin tab name for detail view top tab 
	* @return string
	*/
	public function get_plugin_tab_name() {
		return $this->plugin_tab_name ;
	}
	
	/**
	* get error
	* @return string 
	*/
	public function get_error() {
		return $this->error ;
	}
	
	public function reset_plugin() {
		$this->error = '';
	}
	
	/**
	* function to load the active plugins
	* the active plugins are loaded on persistent object to be accessed across the application
	* @param boolean $settings
	* @return void
	*/
	public function load_active_plugins($settings= false) {
		$qry = "
		select * from `".$this->getTable()."`" ;
		$stmt = $this->getDbConnection()->prepare($qry);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			$plugins = array();
			$do_plugin_permission = new CRMPluginPermission();
			while ($row = $stmt->fetch()) {
				if (false === $settings) {
					if (true === $do_plugin_permission->is_plugin_allowed($row["name"])) {
						$plugins[$row["idplugins"]] = array(
							"name"=>$row["name"],
							"action_priority"=>$row["action_priority"],
							"display_priority"=>$row["display_priority"]
						);
					}
				} else {
					$plugins[$row["idplugins"]] = array(
						"name"=>$row["name"],
						"action_priority"=>$row["action_priority"],
						"display_priority"=>$row["display_priority"]
					);
				}
			}
			$this->active_plugins = $plugins ;
		}
	}
}