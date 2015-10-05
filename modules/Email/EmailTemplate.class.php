<?php
/** Ofuz Open Source version is released under the GNU Affero General Public License, please read the full license at: http://www.gnu.org/licenses/agpl-3.0.html **/ 
// Copyright 2008 - 2010 all rights reserved, SQLFusion LLC, info@sqlfusion.com
/** Ofuz Open Source version is released under the GNU Affero General Public License, please read the full license at: http://www.gnu.org/licenses/agpl-3.0.html **/

/**
* Modified by sQcrm to enable the email feature to support email templates using the PHP Emailer Library
*/

class EmailTemplate extends DataObject {
	public $table = "emailtemplate";
	public $primary_key = "idemailtemplate";
	private $lang="en_US";
	private $fallback_language="en_US";
  
	/**
	* constructor function.
	* @param mix $email_template_name
	*/
	public function __construct($email_template_name="") {
		parent::__construct();
		if ($GLOBALS['cfg_lang']) {
			$this->setLanguage($GLOBALS['cfg_lang']);
		}
		if (!empty($email_template_name)) {
			$qry ="
			select * from ".$this->getTable()."
			where 
			name= ? 
			and language= ? 
			";
			$this->query($qry,array($email_template_name,$this->getLanguage())) ;
			if ($this->getNumRows() == 0) {	
				$this->query($qry,array($email_template_name,$this->fallback_language)) ;
			}
			/*if ($this->getNumRows() == 0) {
				$this->setError("Template ".$email_template_name." not found");
			}*/
			$this->next();
		}       
	}
  
	/**
	* function to set the language
	* @param string $lang
	*/
	public function setLanguage($lang) {
		$this->lang = $lang;
	}
  
	/**
	* function to get the language
	*/
	public function getLanguage() {
		return $this->lang;
	}
}

?>
