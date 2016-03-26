<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class User, maintain cpanel (customerportal) user related actions
* @author Abhik Chakraborty
*/ 
namespace cpanel_user ;

class User extends \DataObject {
	public $table = "cpanel_user";
	public $primary_key = "idcpanel_user";

	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}

	public function eventLogin(\EventControler $evctl) {
	
	}
}