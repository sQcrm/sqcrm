<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Class Roles
* Maintain the Roles and user hierarchy information of crm
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
		$this->set_plugin_position(1); // required
		$this->set_resource_name('test.php'); // optional else it will look for index.php in your plugin folder
		$this->set_plugin_description(_('This is a test plugin called HelloWorld for the detail view right block')); // optional
	}
	
	public function before_add($idmodule,$form_object) {
		$this->raise_error(_('There is a error in the form please check this'));
	}
}