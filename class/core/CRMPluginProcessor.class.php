<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMProcessPlugin 
* Process the plugins
* @author Abhik Chakraborty
*/
	

class CRMPluginProcessor extends CRMPluginBase {
	public $table = "plugins" ;
	public $primary_key = "idplugins" ;

	/**
	* process the detail view right side plugins
	* @param integer $idmodule
	* @param integer $sqcrm_record_id
	* @param mix $active_plugins
	* @return void
	*/
	public function process_detail_view_plugin($idmodule,$sqcrm_record_id,$active_plugins = null) {
		if ($active_plugins == null) {
			parent::load_active_plugins();
			$active_plugins = parent::get_active_plugins();
		}
		if (is_array($active_plugins) && count($active_plugins) > 0) {
			usort($active_plugins, function($a, $b) {
				return $a['display_priority'] - $b['display_priority'];
			});
			foreach ($active_plugins as $key=>$plugin) {
				$plugin_object = new $plugin["name"]() ;
				if (in_array($idmodule,$plugin_object->get_plugin_modules()) && in_array(7,$plugin_object->get_plugin_type()) &&  in_array(1,$plugin_object->get_detail_view_plugin_position())) {
					echo '<div class="box_content" id="'.$plugin["name"].'">' ;
					echo '</div>';
					echo '
					<script>
						$(document).ready(function() {
							load_detail_view_plugin(\''.$plugin["name"].'\',\''.$plugin_object->get_resource_name().'\','.$idmodule.','.$sqcrm_record_id.');
						});
					</script>' ;
					echo '<br />' ;
				}
			}
		}
	}
	
	/**
	* process the detail view tab plugin 
	* @param integer $idmodule
	* @param integer $sqcrm_record_id
	* @param mix $active_plugins
	*/
	public function process_detail_view_tab_plugin($idmodule,$sqcrm_record_id,$active_plugins = null) {
		if ($active_plugins == null) {
			parent::load_active_plugins();
			$active_plugins = parent::get_active_plugins();
		}
		if (is_array($active_plugins) && count($active_plugins) > 0) {
			usort($active_plugins, function($a, $b) {
				return $a['display_priority'] - $b['display_priority'];
			});
			foreach ($active_plugins as $key=>$plugin) {
				$plugin_object = new $plugin["name"]() ;
				if (in_array($idmodule,$plugin_object->get_plugin_modules()) && in_array(7,$plugin_object->get_plugin_type()) && in_array(2,$plugin_object->get_detail_view_plugin_position())) {
					echo '<li id="plugin_'.$plugin_object->get_plugin_name().'" class="">' ;
					echo '<a href="#" onclick = "process_detail_view_tab_plugin(\''.$plugin["name"].'\',\''.$plugin_object->get_resource_name().'\','.$idmodule.','.$sqcrm_record_id.')" data-toggle ="tab" >';
					echo $plugin_object->get_plugin_tab_name();
					echo '</a>';
					echo '</li>';
				}
			}
		}
		
	}
	
	/**
	* process the action plugins
	* The action plugin types are -
	* 1 - Before add
	* 2 - After add
	* 3 - Before edit
	* 4 - After edit
	* 5 - Before delete
	* 6 - After delete
	* @param integer $idmodule
	* @param object $form_object
	* @param integer $sqcrm_record_id
	* @param object $entity_object
	* @mix $active_plugins
	* @return void
	*/
	public function process_action_plugins($idmodule,$form_object=null,$action_type,$sqcrm_record_id=0,$entity_object = null,$active_plugins = null) {
		if ($active_plugins == null) {
			parent::load_active_plugins();
			$active_plugins = parent::get_active_plugins();
		}
		if (is_array($active_plugins) && count($active_plugins) > 0) {
			usort($active_plugins, function($a, $b) {
				return $a['action_priority'] - $b['action_priority'];
			});
			foreach ($active_plugins as $key=>$plugin) {
				$plugin_object = new $plugin["name"]() ;
				if (in_array($idmodule,$plugin_object->get_plugin_modules())) {
					$plugin_type = $plugin_object->get_plugin_type() ;
					foreach ($plugin_type as $key=>$type) {
						if ($action_type == $type) {
							$this->reset_plugin();
							$this->call_action_plugin_method($plugin_object,$type,$idmodule,$form_object,$sqcrm_record_id,$entity_object) ;
							if (strlen($plugin_object->get_error()) > 2) {
								parent::raise_error($plugin_object->get_error()) ;
								return ;
							}
						}
					}
				}
			}
		}
	}
	
	/**
	* call the specific functions on the plugins based on the type
	* @param object $plugin_object
	* @param integer $type
	* @param integer $idmodule
	* @param object $form_object
	* @param integer $sqcrm_record_id
	* @param object $entity_object
	* @return void
	*/
	public function call_action_plugin_method($plugin_object,$type,$idmodule,$form_object,$sqcrm_record_id=0,$entity_object = null) {
		switch ($type) {
			case 1 :
				if (method_exists($plugin_object,'before_add')) {
					$plugin_object->before_add($idmodule,$form_object) ;
				}
				break ;
				
			case 2 :
				if (method_exists($plugin_object,'after_add')) {
					$plugin_object->after_add($idmodule,$form_object,$sqcrm_record_id) ;
				}
				break ;
			
			case 3 :
				if (method_exists($plugin_object,'before_edit')) {
					$plugin_object->before_edit($idmodule,$form_object,$sqcrm_record_id,$entity_object) ;
				}
				break ;
				
			case 4 :
				if (method_exists($plugin_object,'after_edit')) {
					$plugin_object->after_edit($idmodule,$form_object,$sqcrm_record_id,$entity_object) ;
				}
				break ;
				
			case 5 :
				if (method_exists($plugin_object,'before_delete')) {
					$plugin_object->before_delete($idmodule,$sqcrm_record_id) ;
				}
				break ;
				
			case 6 :
				if (method_exists($plugin_object,'after_delete')) {
					$plugin_object->after_delete($idmodule,$sqcrm_record_id) ;
				}
				break ;
		}
	}
	
	/**
	* process the list view action plugin 
	* @param integer $idmodule
	* @param mix $active_plugins
	* @return void
	*/
	public function process_listview_action_plugin($idmodule,$active_plugins = null) {
		if ($active_plugins == null) {
			parent::load_active_plugins();
			$active_plugins = parent::get_active_plugins();
		}
		if (is_array($active_plugins) && count($active_plugins) > 0) {
			usort($active_plugins, function($a, $b) {
				return $a['display_priority'] - $b['display_priority'];
			});
			foreach ($active_plugins as $key=>$plugin) {
				$plugin_object = new $plugin["name"]() ;
				if (in_array($idmodule,$plugin_object->get_plugin_modules()) && in_array(8,$plugin_object->get_plugin_type()) && in_array(1,$plugin_object->get_list_view_plugin_position())) {
					echo '<a href="#" class="btn btn-primary btn-xs" id="'.$plugin["name"].'">';
					echo '</a>';
					echo '
					<script>
						$(document).ready(function() {
							load_list_view_action_plugin(\''.$plugin["name"].'\',\''.$plugin_object->get_resource_name().'\','.$idmodule.');
						});
					</script>' ;
				}
			}
		}
	}
}
