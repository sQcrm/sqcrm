<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Class TwitterTimeline
* TwitterTimeline plugin for CRM 
* Twitter widget for showing the timeline using the twitter widget
* @author Abhik Chakraborty
* @see https://publish.twitter.com/
*/

class TwitterTimeline extends CRMPluginProcessor {
	public $table = "";
	public $primary_key = "";
    
	/**
	* constructor function for the sQcrm plugin
	*/
	public function __construct() {
		$this->set_plugin_title(_('Twitter Timeline')); // required
		$this->set_plugin_name('TwitterTimeline') ; // required same as your class name 
		$this->set_plugin_type(array(7)); // required 
		$this->set_plugin_modules(array(4,6)); // required
		$this->set_detail_view_plugin_position(array(1)); // required
		$this->set_plugin_description(
			_('This plugin is to show the tweets using the twitter widget. This plugin will read the available twitter handler
			<br />by looking at the twitter <a href="http://www.sqcrm.com/documentation/field-types.html" target="_blank">field type</a>
			'
			)
		); // optional
	}
	
	/**
	* function to get the twitter handler for a given module 
	* checks the field information by field type = 210 and retrieves the saved value
	* @param integer $idrecord
	* @param integer $idmodule
	* @param integer $primary_key
	*/
	public function get_twitter_handler($idrecord,$idmodule,$primary_key) {
		$return_array = array();
		$qry = "
		select `field_name`,`table_name` from `fields` where
		`idmodule` = ? and `field_type` = 210 and `display` = 1
		";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idmodule));
		if ($stmt->rowCount() > 0) {
			while ($data = $stmt->fetch()) {
				$qry_data = "
				select `".$data['field_name']."` from `".$data['table_name']."`
				where `".$primary_key."` = ?
				";
				$stmt_data = $this->getDbConnection()->executeQuery($qry_data,array($idrecord));
				if ($stmt_data->rowCount() > 0) {
					$field_data = $stmt_data->fetch();
					if ($field_data[$data['field_name']] != '') {
						$return_array[] = $field_data[$data['field_name']];
					}
				}
			}
		}
		
		return $return_array ;
	}
}