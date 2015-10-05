<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class TimeZoneUtil to maintain the date depending on user's time zone and server time zone
* @author Abhik Chakraborty
*/
	

class TimeZoneUtil {
  
	/**
	* function to covert server time to to user time zone
	* @param date $time
	* @param boolean $time
	* @param string $user_time_zone
	* @return date in user timezone
	*/
	public static function convert_to_user_timezone($date,$time=false,$user_time_zone="") {
		return $date;
	}
  
	/**
	* function to get the date in user timezone
	* @param string user_zone
	* @return date in user zone
	*/
	public static function get_user_timezone_date($user_zone="") {
		return date("Y-m-d");
	}
  
	/**
	* function to get the time in user time zone
	* @param string $user_zone
	*/
	public static function get_user_timezone_time($user_zone="") {
		return date("H:i:s",time()) ;
	}
}
