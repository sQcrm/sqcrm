<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class DatasharePermission
* Maintain the standard permission list for datashare across the modules
* @author Abhik Chakraborty
*/


class DatasharePermission extends DataObject {
	public $table = "datashare_standard_permission";
	public $primary_key = "iddatashare_standard_permission";
}