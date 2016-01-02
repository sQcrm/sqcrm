<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMDashboardWidget 
* @author Abhik Chakraborty
*/

class CRMDashboardWidget extends DataObject {
	public $table = "user_dashboard_widgets" ;
	public $primary_key = "iduser_dashboard_widgets" ;
	
	// holds the widget title
	protected $widget_title = '' ;
	
	/**
	* set the widget title 
	* @param string
	*/
	public function set_widget_title($title) {
		$this->widget_title = $title ;
	}
	
	/**
	* get the widget title
	* @return string
	*/
	public function get_widget_title() {
		return $this->widget_title ;
	}
	
	/**
	* get the available widgets by reading the widgets folder
	* @return array
	*/
	public function get_available_widgets() {
		$widget_path = BASE_PATH.'/widgets/' ;
		$available_widgets =  array() ;
		$widgets = array_diff(scandir($widget_path,1), array('..', '.'));
		foreach($widgets as $key=>$widget) {
			if (file_exists(BASE_PATH.'/widgets/'.$widget.'/'.$widget.'.class.php')) {
				include_once(BASE_PATH.'/widgets/'.$widget.'/'.$widget.'.class.php') ;
				$widget_obj = new $widget() ;
				$available_widgets[$widget] = array(
					"widget_title"=>$widget_obj->get_widget_title()
				) ;
			}
		}
		return $available_widgets ;
	}
	
}