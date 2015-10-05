<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class NavigationControl 
*	@author Abhik Chakraborty
*/
	

class NavigationControl extends BaseObject {
    
	/**
	* static function to generate the navigation url
	* @param string $module
	* @param string $path
	* @param integer $sqrecord
	* @param string $query_string
	* @return the navigation url
	*/
	static function getNavigationLink($module,$path,$sqrecord="",$query_string="") {
		$url = '';
		if ($sqrecord == "") {
			$url =  "/modules/".$module."/".$path;
		} else {
			$url =  "/modules/".$module."/".$path."?sqrecord=".$sqrecord;
		}
		if ($query_string != "") {
			$url .= $query_string;
		}
		return $url;
	}

}