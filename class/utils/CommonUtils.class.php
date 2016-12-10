<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CommonUtils used across the CRM 
* @author Abhik Chakraborty
*/
	

class CommonUtils extends DataObject {
	public $table = "";
	public $primary_key = "";

	/**
	* function to format a text before rendering to html
	* @param string $text
	* @return formated string
	*/
	public static function format_display_text($text) {
		$text = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a href=\"\\2\" target = \"_blank\">\\2</a>'", $text);
		$text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://\\2\" target = \"_blank\">\\2</a>'", $text);
		$text = preg_replace("#(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $text);
		$text = nl2br($text);
		return ($text) ;
	}
    
	/**
	* Get the IP address of the user
	* @return IP address of the user
	*/
	public static function get_user_ip_address() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
  
	/**
	* function to get the text for modules 
	* @param integer $idmodule
	* @return string module_name
	*/
	public static function get_module_name_as_text($idmodule) {
		switch($idmodule) {
			case 2: 
				$module_name = _('Calendar Activity');
				break;
			case 3: 
				$module_name = _('Lead');
				break;
			case 4: 
				$module_name = _('Contact');
				break;
			case 5: 
				$module_name = _('Prospect');
				break;
			case 6: 
				$module_name = _('Organization');
				break;
			case 11: 
				$module_name = _('Vendor');
				break;
			case 12: 
				$module_name = _('Products');
				break;
			case 13: 
				$module_name = _('Quotes');
				break;
			case 14: 
				$module_name = _('Sales Order');
				break;
			case 15: 
				$module_name = _('Invoice');
				break;
			case 16: 
				$module_name = _('Purchase Order');
				break;
		}
		return $module_name ;
	}
  
	/**
	* function to purify the input values
	* @param mix input
	* use strip_tags function with allowed tags in the input
	* TODO : use some library to handle better sanitization of input like htmlpurifier
	*/
	public static function purify_input($input) {
		$allow = '<p><b><strong><i><u>';
		return strip_tags($input,$allow);
	}
  
	/**
	* function to get the start and end date for a specific quarter from today
	* @param string $qtr
	* @return array
	*/
	public static function get_quarter_date_range($qtr='current') {
		$year = date('Y');
		$m = date('m'); 
		switch ($qtr) {
			case 'current':
				if ($m >=1 && $m <=3) {
					$start = $year.'-01-01';
					$end = $year.'-03-31';
				} elseif ($m >=3 && $m<= 6) {
					$start = $year.'-04-01';
					$end = $year.'-06-30';
				} elseif($m >=6 && $m<=9) {
					$start = $year.'-07-01';
					$end = $year.'-09-30';
				} else {
					$start = $year.'-10-01';
					$end = $year.'-12-31';
				}
				break;
			case 'previous':
				if ($m >=1 && $m <=3) {
					$start = ($year-1).'-10-01';
					$end = ($year-1).'-12-31';
				} elseif($m >=3 && $m<= 6) {
					$start = $year.'-01-01';
					$end = $year.'-03-31';
				} elseif($m >=6 && $m<=9) {
					$start = $year.'-04-01';
					$end = $year.'-06-30';
				} else {
					$start = $year.'-07-01';
					$end = $year.'-09-30';
				}
				break ;
			case 'next' :
				if ($m >=1 && $m <=3) {
					$start = $year.'-04-01';
					$end = $year.'-06-30';
				} elseif($m >=3 && $m<= 6) {
					$start = $year.'-04-01';
					$end = $year.'-06-30';
				} elseif($m >=6 && $m<=9) {
					$start = $year.'-10-01';
					$end = $year.'-12-31';
				} else {
					$start = ($year+1).'-01-01';
					$end = ($year+1).'-03-31';
				}
				break;
		}
		return array("start"=>$start,"end"=>$end);
	}
  
	/**
	* function to get the year start and end date
	* @param string $y
	* @return array
	*/
	public static function get_year_date_range($y='current') {
		switch ($y) {
			case 'current':
				$year = date("Y");
				$start = $year.'-01-01';
				$end = $year.'-12-31';
				break;
			case 'previous':
				$year = date("Y")-1;
				$start = $year.'-01-01';
				$end = $year.'-12-31';
				break;
			case 'next':
				$year = date("Y")+1;
				$start = $year.'-01-01';
				$end = $year.'-12-31';
				break;
		}
		return array("start"=>$start,"end"=>$end);
	}
  
	/**
	* function to get the start end end date for week range
	* @param string $w
	* @return array
	*/
	public static function get_week_date_range($w='current') {
		switch ($w) {
			case 'current':
				$d = strtotime("today");
				$start_week = strtotime("last sunday midnight",$d);
				$end_week = strtotime("next saturday",$d);
				$start = date("Y-m-d",$start_week); 
				$end = date("Y-m-d",$end_week); 
				break;
			case 'previous':
				$d = strtotime("-1 week +1 day");
				$start_week = strtotime("last sunday midnight",$d);
				$end_week = strtotime("next saturday",$d);
				$start = date("Y-m-d",$start_week); 
				$end = date("Y-m-d",$end_week); 
				break;
			case 'next':
				$d = strtotime("+1 week -1 day");
				$start_week = strtotime("last sunday midnight",$d);
				$end_week = strtotime("next saturday",$d);
				$start = date("Y-m-d",$start_week); 
				$end = date("Y-m-d",$end_week); 
				break;
		}
		return array("start"=>$start,"end"=>$end);
	}
  
	public static function get_month_date_range($m='current') {
		switch ($m) {
			case 'current':
				$s = new DateTime("first day of this month");
				$e = new DateTime("last day of this month");
				$start = $s->format('Y-m-d'); 
				$end  = $e->format('Y-m-d');
				break;
			case 'previous':
				$s = new DateTime("first day of last month");
				$e = new DateTime("last day of last month");
				$start = $s->format('Y-m-d'); 
				$end  = $e->format('Y-m-d');
				break;
			case 'next':
				$s = new DateTime("first day of next month");
				$e = new DateTime("last day of next month");
				$start = $s->format('Y-m-d'); 
				$end  = $e->format('Y-m-d');
				break;
		}
		return array("start"=>$start,"end"=>$end);
	}
	
	/**
	* get the date filter options 
	* @return array
	*/
	public static function get_date_filter_options() {
		return array(
			1=>_('Custom'),
			2=>_('Previous FY'),
			3=>_('Current FY'),
			4=>_('Next FY'),
			5=>_('Previous FQ'),
			6=>_('Current FQ'),
			7=>_('Next FQ'),
			8=>_('Yesterday'),
			9=>_('Today'),
			10=>_('Tomorrow'),
			11=>_('Last Week'),
			12=>_('This Week'),
			13=>_('Next Week'),
			14=>_('Last Month'),
			15=>_('This Month'),
			16=>_('Next Month'),
			17=>_('Last 7 days'),
			18=>_('Last 30 days'),
			19=>_('Last 60 days'),
			20=>_('Last 90 days'),
			21=>_('Next 7 days'),
			22=>_('Next 30 days'),
			23=>_('Next 60 days'),
			24=>_('Next 90 days')
		);
	}
  
	/**
	* get advanced filter options
	* @return array
	*/
	public static function get_advanced_filter_options() {
		return array(
			0=>_('none'),
			1=>_('equal'),
			2=>_('not equal'),
			3=>_('contains'),
			4=>_('does not contains'),
			5=>_('less than'),
			6=>_('greater than'),
			7=>_('less than or equal'),
			8=>_('greater than or equal')
		);
	}
	
	/**
	* function to generate a random string of specified length
	* @param integer $length
	* @NOTE : The string generated in this function is not very secure
	*/
	public static function generate_random_string($length = 8) {
		return substr(str_shuffle(MD5(microtime())), 0, $length);
	}
	
	public static function get_random_color() {
		return sprintf("#%06x",rand(0,16777215));
	}
}