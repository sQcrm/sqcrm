<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt

class SQEmailer extends PHPMailer{
	/**
	* setEmailTemplate load an instance of an email message to be sent or merged
	* @param mix sqlConnect $conx connexion to the database thrue an sqlConnect object or an EmailTemplate object.
	* @param string $templatename name of the template to load
	* @see Ofuz.com , Radria.Sqlfusion.com
	*/
	function setEmailTemplate($templatename="", $conx=null) {
		if (is_object($templatename) && (get_class($templatename) == "EmailTemplate" || is_subclass_of($templatename, "EmailTemplate"))) { 
			$EmailTemplate = $templatename;
			$this->MsgHTML($EmailTemplate->bodyhtml);
			$this->Subject = $EmailTemplate->subject;
			$this->SetFrom($EmailTemplate->senderemail, $EmailTemplate->sendername);
			return true;
		} else {
			if (is_null($conx)) {  $conx = $GLOBALS['conn']; }
			$qry = "select * from ".$this->cfgTemplateTable." where name = ? ";
			$qGetTplt = $conx->getDbConnection()->executeQuery($qry,array($templatename));
			if ($qGetTplt->rowCount() == 1) {
				$data = $qGetTplt->fetch() ;
				$this->Subject = $data["subject"] ;
				$this->MsgHTML($data["bodyhtml"]) ;
				$this->SetFrom($data["senderemail"], $data["sendername"]) ;
				return true;
			} else { return false; }
		}
	}

	/**
	* function to load emailer
	* @param mix sqlConnect $conx connexion to the database thrue an sqlConnect object or an EmailTemplate object.
	* @param string $templatename name of the template to load
	*/
	function loadEmailer($conx, $templatename) {
		$this->setEmailTemplate($templatename, $conx);
	}

  
	/**
	* mergeArray()
	* Merge an Array with a currently loaded email template
	* @param $fields_values Array of fields in format $fields['fieldname']=value;
	*/
	function mergeArray($fields_values) {
		if (strlen($this->Body) > 5) {
			$this->MsgHTML(self::withArray($this->Body, $fields_values));
		}
		$this->Subject = self::withArray($this->Subject, $fields_values);
	}
  
	/**
	* Load the field in the field attribute from the HTML template.
	* get Table Field could be used instead but it will not get the
	* extra fields and multiple tables fields
	* @param String $template HTML template (row, header, footer) where there is fields to be used
	* @access public
	* @return Array $fields indexed on the field name.
	*/
	public static function getMergedField($template) {
		while (preg_match('/\[([^\[]*)\]/', $template, $fieldmatches)) {
			$fields[] = $fieldmatches[1];
			$template = str_replace($fieldmatches[0], "", $template) ;
		}
		return $fields ;
	}
  
	/**
	* The secret sauce.
	* Take a string, extract the fields in [] and replace the fields in [] with
	* their respective values from the $values array.
	* @param string with fields to merge in []
	* @param array $values array with format $values[field_name] = $value
	* @return string merged
	*/
	static function withArray($thestring, $values) {
		$fields = self::getMergedField($thestring) ;
		if (is_array($fields)) {
			foreach ($fields as $field) {
				$thestring = str_replace('['.$field.']', $values[$field], $thestring) ;
			}
		}
		return $thestring;
	}
   
}
?>