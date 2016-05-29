<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Invoice 
* @author Abhik Chakraborty
*/ 
namespace cpanel_invoice ;
class Invoice extends \Invoice {
	public $table = "invoice";
	public $primary_key = "idinvoice";
	protected $lookup_field = 'idcontacts';
	
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}

	public function get_lookup_field() {
		return $this->lookup_field ;
	}
}