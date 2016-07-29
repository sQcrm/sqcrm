<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Class HelloWorld
* A demo plugin for crm
* @author Abhik Chakraborty
*/


class HelloWorld extends CRMPluginProcessor {
	public $table = "";
	public $primary_key = "";
    
	public function __construct() {
		$this->set_plugin_title(_('Some Test Plugin')); // required
		$this->set_plugin_name('HelloWorld') ; // required same as your class name 
		$this->set_plugin_type(array(7)); // required 
		$this->set_plugin_modules(array(2,3,4,5,6,11,12,13,14,15,16,17)); // required
		$this->set_detail_view_plugin_position(array(1,2)); // required
		$this->set_plugin_tab_name('Hi there !'); // required
		$this->set_resource_name('test.php'); // optional else it will look for index.php in your plugin folder
		$this->set_plugin_description(_('This is a test plugin called HelloWorld for the detail view right block')); // optional
	}
	
	/**
	* event function to handle form data
	* @param object $evctl
	*/
	public function eventSampleHelloWorld(EventControler $evctl) {
		// do something with these data
		$module_id = $evctl->idmodule ;
		$record_id = $evctl->sqcrm_record_id ;
		$text_data = $evctl->sample_text ;
		echo 'Module Id ::'.$module_id.', Record Id ::'.$record_id.', Text you entered ::'.$text_data ;
	}
}