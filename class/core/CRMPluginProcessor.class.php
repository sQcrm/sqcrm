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
			foreach ($active_plugins as $key=>$plugin) {
				$plugin_object = new $plugin() ;
				if (in_array($idmodule,$plugin_object->get_plugin_modules()) && in_array(7,$plugin_object->get_plugin_type()) && $plugin_object->get_plugin_position() ==1) {
					echo '<div class="box_content" id="'.$plugin.'">' ;
					echo '</div>';
					echo '
					<script>
						$(document).ready(function() {
							load_detail_view_plugin(\''.$plugin.'\',\''.$plugin_object->get_resource_name().'\','.$idmodule.','.$sqcrm_record_id.');
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
			foreach ($active_plugins as $key=>$plugin) {
				$plugin_object = new $plugin() ;
				if (in_array($idmodule,$plugin_object->get_plugin_modules()) && in_array(7,$plugin_object->get_plugin_type()) && $plugin_object->get_plugin_position() ==2) {
					echo '<li id="plugin_'.$plugin_object->get_plugin_name().'" class="">' ;
					echo '<a href="#" onclick = "process_detail_view_tab_plugin(\''.$plugin.'\',\''.$plugin_object->get_resource_name().'\','.$idmodule.','.$sqcrm_record_id.')" data-toggle ="tab" >';
					echo $plugin_object->get_plugin_title();
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
	public function process_action_plugins($idmodule,$form_object,$sqcrm_record_id=0,$entity_object = null,$active_plugins = null) {
		if ($active_plugins == null) {
			parent::load_active_plugins();
			$active_plugins = parent::get_active_plugins();
		}
		if (is_array($active_plugins) && count($active_plugins) > 0) {
			foreach ($active_plugins as $key=>$plugin) {
				$plugin_object = new $plugin() ;
				if (in_array($idmodule,$plugin_object->get_plugin_modules())) {
					$plugin_type = $plugin_object->get_plugin_type() ;
					//if ($plugin_type == 1 || $plugin_type == 2 || $plugin_type == 3 || $plugin_type == 4 || $plugin_type == 5 || $plugin_type == 6) {
						foreach ($plugin_type as $key=>$type) {
							$this->call_action_plugin_method($plugin_object,$type,$idmodule,$form_object,$sqcrm_record_id,$entity_object) ;
							if (strlen($plugin_object->get_error()) > 2) {
								parent::raise_error($plugin_object->get_error()) ;
								return ;
							}
						}
					//}
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
				$plugin_object->before_add($idmodule,$form_object) ;
				break ;
				
			case 2 :
				$plugin_object->after_add($idmodule,$form_object,$sqcrm_record_id,$entity_object) ;
				break ;
			
			case 3 :
				$plugin_object->before_edit($idmodule,$form_object,$sqcrm_record_id) ;
				break ;
				
			case 4 :
				$plugin_object->after_edit($idmodule,$form_object,$sqcrm_record_id,$entity_object) ;
				break ;
				
			case 5 :
				$plugin_object->before_delete($idmodule,$sqcrm_record_id,$entity_object) ;
				break ;
				
			case 6 :
				$plugin_object->after_delete($idmodule,$sqcrm_record_id,$entity_object) ;
				break ;
		}
	}
}
