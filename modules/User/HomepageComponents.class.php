<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class HomepageComponents
* @author Abhik Chakraborty
*/ 

class HomepageComponents extends DataObject {
    
	public $table = "homepage_component";
	protected $primary_key = "idhomepage_component";
	
	function __construct(sqlConnect $conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
    
}
?>
