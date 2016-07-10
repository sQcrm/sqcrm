<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Class ListviewAction
* A demo plugin for crm
* @author Abhik Chakraborty
*/


class ListviewAction extends CRMPluginProcessor {
	public $table = "";
	public $primary_key = "";
    
	public function __construct() {
		$this->set_plugin_title(_('List view action plugin')); // required
		$this->set_plugin_name('ListviewAction') ; // required same as your class name 
		$this->set_plugin_type(array(8)); // required 
		$this->set_plugin_modules(array(2)); // required
		$this->set_list_view_plugin_position(array(1)); // required
		$this->set_resource_name('test.php'); // optional else it will look for index.php in your plugin folder
		$this->set_plugin_description(_('This is a test plugin called ListviewAction for the list view top tab')); // optional
	}
	
	/**
	* event function to handle form data
	* @param object $evctl
	*/
	public function eventTest(EventControler $evctl) {
		$count = count($evctl->ids);
		$_SESSION["do_crm_messages"]->set_message('success',_('List view action plugin EventControler called and selected record count is ').$count);
	}
}