<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class DataHistory
* @author Abhik Chakraborty
*/
	

class DataHistoryDisplay extends DataHistory {
	public $table = "data_history";
	public $primary_key = "iddata_history";
	
	public $sql_start = 0 ;
	public $sql_max = 50 ;
    
	/**
	* Constructor function 
	*/
	function __construct(sqlConnect $conx=NULL, $table_name="") {
			parent::__construct($conx, $table_name);
	}
    
	/**
	* function to get the data history
	* @param integer $id_referrer
	* @param integer $idmodule
	* @param integer $start
	* @param integer $max
	* @return array if data found, else false
	*/
	public function get_data_history($id_referrer,$idmodule,$start=0,$max=0) {
		$return_array = array();
		$qry = "
		select data_history.*, user.firstname, user.lastname , file_uploads.file_extension,
		user.user_avatar from data_history 
		inner join user on user.iduser = data_history.iduser
		left join file_uploads on user.user_avatar = file_uploads.file_name
		where data_history.id_referrer = ?
		AND data_history.idmodule = ?
		order by data_history.iddata_history desc";
		if ($start == 0 && $max == 0) {
			$start = $this->sql_start ;
			$max = $this->sql_max ;
		}
		$qry .= " limit ".(int)$start.",".(int)$max ;
		$this->query($qry,array($id_referrer,$idmodule));
		$data_history_array = array() ;
		if ($this->getNumRows() > 0) { 
			while ($this->next()) {
				$date_modified = TimeZoneUtil::convert_to_user_timezone($this->date_modified,true);
				$y = date("Y",strtotime($date_modified));
				$m = date("M",strtotime($date_modified));
				$data_history_array[$y][$m][] = $this->get_data_history_display_text($this);
			}
			$return_array["data"] = $data_history_array;
			if (isset($_REQUEST["last_year"]) && $_REQUEST["last_month"] && $_REQUEST["last_postition"]) {
				$return_array["last_details"] = array(
					"last_year"=>$_REQUEST["last_year"],
					"last_month"=>$_REQUEST["last_month"],
					"last_postition"=>$_REQUEST["last_postition"]
				);
			} else {
				$return_array["last_details"] = array(
					"last_year"=>'',
					"last_month"=>'',
					"last_postition"=>''
				);
			}
			return $return_array ;
		} else{ return false ; }
	}
    
	/**
	* function to get data history display text
	* @param object $obj
	* @param boolean $link
	* @param boolean $user_history
	* @return array $ret_array
	*/
	public function get_data_history_display_text($obj,$link=false,$user_history=false) {
		$row1 = '<strong>'.$obj->firstname.' '.$obj->lastname.'</strong> '._('on ').'<i>'.i18nDate::i18n_long_time(TimeZoneUtil::convert_to_user_timezone($obj->date_modified,true)).'</i>' ;
		switch ($obj->action) {
			case 'add':
				if ($user_history === true) {
					$row2 = _('Added').' '.CommonUtils::get_module_name_as_text($obj->idmodule);
				} else {
					$row2 = _('Added the record');
				}
				break;
			case 'delete':
				if ($user_history === true) {
					$row2 = _('Deleted').' '.CommonUtils::get_module_name_as_text($obj->idmodule);
				} else {
					$row2 = _('Deleted the record');
				}
				break;
			case 'edit':
				if ($user_history === true) {
					$row2 = _('Updated').' '.CommonUtils::get_module_name_as_text($obj->idmodule);
				} else {
					$row2 = _('Updated the record');
				}
				break;
			case 'value_changes':
				$do_crm_fields = new CRMFields();
				$do_crm_fields->getId($obj->idfields);
				if ($do_crm_fields->getNumRows() > 0) {
					$field_label = $do_crm_fields->field_label ;
					$old_value = $obj->old_value;
					$new_value = $obj->new_value ;
					if ($do_crm_fields->field_type == 9) {
						$old_value = FieldType9::display_value($old_value);
						$new_value = FieldType9::display_value($new_value);
					}
					if ($user_history === true) {
						$row2 = _('Changed value in').' '.CommonUtils::get_module_name_as_text($obj->idmodule).' , '
											.$field_label.' :: '.$old_value.' >>> '.$new_value;
					} else {
						$row2 = _('Changed').' '.$field_label.' :: '.$old_value.' >>> '.$new_value ;
					}
				}
				$do_crm_fields->free();
				break ;       
		}
		if ($obj->user_avatar != '') {
			$avatar_path = $GLOBALS['AVATAR_DISPLAY_PATH'] ;
			$thumb = $avatar_path.'/ths_'.$obj->user_avatar.'.'.$obj->file_extension ;
		}
		if ($link === true) {
			$detail_url = NavigationControl::getNavigationLink($_SESSION["do_module"]->modules_full_details[$obj->idmodule]["name"],"detail",$obj->id_referrer);
			$row2 .='&nbsp;<a href="'.$GLOBALS['SITE_URL'].$detail_url.'">'.$GLOBALS['SITE_URL'].$detail_url.'</a>' ;
		}
		$ret_array = array("avatar"=>$thumb,"row1"=>$row1,"row2"=>$row2);
		return $ret_array ;
	}
    
	/**
	* function to get the data history for an user
	* @param integer $iduser
	* @param integer $start
	* @param integer $max
	* @return array if found else false
	*/
	public function get_data_history_user($iduser,$start=0,$max=0) {
		$return_array = array();
		$qry = "
		select data_history.*, user.firstname, user.lastname , file_uploads.file_extension,
		user.user_avatar from data_history 
		inner join user on user.iduser = data_history.iduser
		left join file_uploads on user.user_avatar = file_uploads.file_name
		where data_history.iduser = ?
		order by data_history.iddata_history desc";
		if ($start == 0 && $max == 0) {
			$start = $this->sql_start ;
			$max = $this->sql_max ;
		}
		$qry .= " limit ".(int)$start.",".(int)$max ;
		$this->query($qry,array($iduser));
		$data_history_array = array() ;
		if ($this->getNumRows() > 0 ) { 
			while ($this->next()) {
				$date_modified = TimeZoneUtil::convert_to_user_timezone($this->date_modified,true);
				$y = date("Y",strtotime($date_modified));
				$m = date("M",strtotime($date_modified));
				$data_history_array[$y][$m][] = $this->get_data_history_display_text($this,true,true);
			}
			$return_array["data"] = $data_history_array;
			if (isset($_REQUEST["last_year"]) && $_REQUEST["last_month"] && $_REQUEST["last_postition"]) {
				$return_array["last_details"] = array(
					"last_year"=>$_REQUEST["last_year"],
					"last_month"=>$_REQUEST["last_month"],
					"last_postition"=>$_REQUEST["last_postition"]
				);
			} else {
				$return_array["last_details"] = array(
					"last_year"=>'',
					"last_month"=>'',
					"last_postition"=>''
				);
			}
			return $return_array ;
		} else { return false ; }
	}
}
