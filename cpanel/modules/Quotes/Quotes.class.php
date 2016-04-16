<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Quotes 
* @author Abhik Chakraborty
*/ 
namespace cpanel_quotes ;
class Quotes extends \Quotes {
	public $table = "quotes";
	public $primary_key = "idquotes";
	protected $lookup_field = 'idorganization';
	
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}

	public function get_lookup_field() {
		return $this->lookup_field ;
	}
}