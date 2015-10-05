<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class i18nDate
* @author Abhik Chakraborty
*/ 
   
class i18nDate {
	private static $lang = "en_US";
    
	/**
	* Constructor to set the current language set
	* Incase we want an object instentiation 
	*/
	function __construct() {
		if (isset($GLOBALS['cfg_lang'])) { 
			$this->setLanguage($GLOBALS['cfg_lang']);
		}
	}

	/**
	* Function to get language
	* @return string language
	*/
	static function getLanguage() {
		if (isset($GLOBALS['cfg_lang'])) { 
			self::$lang = $GLOBALS['cfg_lang'];
		}
		return self::$lang ;
	}
  
	/**
	* function to convert time to a different format
	* @param time $time
	* @return string formated time
	*/
	static function i18n_time($time) {
		$curr_lang = self::getLanguage();
		if(isset($GLOBALS['cfg_time_formats'][$curr_lang]['time'])) {
			return strftime($GLOBALS['cfg_time_formats'][$curr_lang]['time'],strtotime($date));
		} else {
			return strftime("%l:%M %P",strtotime($time));
		}
	}
  
	static function i18n_long_time($time) {
		$curr_lang = self::getLanguage();
		if (isset($GLOBALS['cfg_time_formats'][$curr_lang]['time'])) {
			return strftime($GLOBALS['cfg_time_formats'][$curr_lang]['time'],strtotime($date));
		} else {
			return strftime("%A %e , %l:%M %P",strtotime($time));
		}
	}
  
	/**
	* function to format date
	* @param date $date
	* @param boolean $show_time
	* @return formated date
	*/
	static function i18n_long_date($date,$show_time=false) {
		$curr_lang = self::getLanguage();
		if ($show_time === true) {
			if (isset($GLOBALS['cfg_time_formats'][$curr_lang]['datetime'])) {
				return strftime($GLOBALS['cfg_time_formats'][$curr_lang]['datetime'],strtotime($date));
			} else {
				return strftime("%A, %B %e, %Y, %l:%M %P",strtotime($date));
			}
		} else {
			if (isset($GLOBALS['cfg_time_formats'][$curr_lang]['date'])) {
				return strftime($GLOBALS['cfg_time_formats'][$curr_lang]['date'],strtotime($date));
			} else {
				return strftime("%A, %B %e, %Y",strtotime($date));
			}
		}
	}
}	
?>