<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class LiveFeedDisplay
* @author Abhik Chakraborty
*/
	

class LiveFeedDisplay extends LiveFeedQueue {
	public $table = "feed_queue";
	public $primary_key = "idfeed_queue";

	public $sql_start = 0 ;
	public $sql_max = 5 ;
    
	/**
	* function to display the live work feed which are viewed at the time of page load
	* @param integer $iduser
	* @param integer $start
	* @param integer $max
	* @return array $feed_array
	*/
	public function display_feed($iduser='',$start=0,$max=0) {
		if ($iduser == '') $iduser = $_SESSION["do_user"]->iduser ;
		$qry = "
		select feed_queue.*,
		user.firstname, 
		user.lastname ,
		user.user_avatar,
		file_uploads.file_extension
		from ".$this->getTable()."
		inner join user on user.iduser = feed_queue.iduser
		left join file_uploads on user.user_avatar = file_uploads.file_name
		where feed_queue.iduser_for = ?
		AND feed_queue.viewed = 1 
		order by feed_queue.date_added desc
		";
		if ($start == 0 && $max == 0) {
			$start = $this->sql_start ;
			$max = $this->sql_max ;
		}
		$qry .= " limit ".$start.",".$max ;
		$this->query($qry,array($iduser));
		$feed_array = array();
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$feed_array[] = $this->construct_feed_text($this);
			}
		}
		return $feed_array ;
	}
    
	/**
	* function to load the live work feed
	* @param integer $iduser
	* @return array $feed_array
	*/
	public function load_live_feed($iduser='') {
		if ($iduser == '') $iduser = $_SESSION["do_user"]->iduser ;
		$qry = "
		select feed_queue.*,
		user.firstname, 
		user.lastname ,
		user.user_avatar,
		file_uploads.file_extension
		from ".$this->getTable()."
		inner join user on user.iduser = feed_queue.iduser
		left join file_uploads on user.user_avatar = file_uploads.file_name
		where feed_queue.iduser_for = ?
		AND feed_queue.viewed = 0 
		order by feed_queue.date_added
		limit 1 
		";
			
		$this->query($qry,array($iduser));
		$feed_array = array();
		if ($this->getNumRows() > 0) {
			$this->next();
			$feed_array = $this->construct_feed_text($this);
			$this->set_feed_viewed($this->idfeed_queue);
		}
		return $feed_array ;
	}
    
	/**
	* function to construct the live feed text to display
	* @param object $obj
	* @return array
	*/
	public function construct_feed_text($obj) {
		$action_user = $obj->firstname.' '.$obj->lastname;
		$thumb = '';
		if ($obj->user_avatar != '') {
			$avatar_path = $GLOBALS['AVATAR_DISPLAY_PATH'] ;
			$thumb = $avatar_path.'/ths_'.$obj->user_avatar.'.'.$obj->file_extension ;
		}
		$date_added = i18nDate::i18n_long_date(TimeZoneUtil::convert_to_user_timezone($obj->date_added,true),true);
		$module_name = CommonUtils::get_module_name_as_text($obj->idmodule);
		$link_identifier = true ;
		$related_identifer_text = '';
		$link_related_identifier = true ;
		switch ($obj->action) {
			case 'add' :
				$content = _('Added').' '.$module_name ;
				break;
			case 'edit' :
				$content = _('Updated').' '.$module_name ;
				break;
			case 'delete' :
				$content = _('Deleted').' '.$module_name.' - ' ;
				$link_identifier = false ;
				break;
			case 'lead_covert' :
				$content = _('Converted').' '.$module_name ;
				break;
			case 'add_contact_lead_convert':
				$content = _('Added').' '.$module_name;
				$related_identifer_text = _('during converting the lead');
				break;
			case 'add_organization_lead_convert':
				$content = _('Added').' '.$module_name;
				$related_identifer_text = _('during converting the lead');
				break;
			case 'add_potential_lead_convert':
				$content = _('Added').' '.$module_name;
				$related_identifer_text = _('during converting the lead');
				break;
			case 'changed_assigned_to':
				$content = _('Changed assigned to ').' '.$module_name;
				break ;			
		}
		
		if ($link_identifier === true) {
			$detail_url = NavigationControl::getNavigationLink($_SESSION["do_module"]->modules_full_details[$obj->idmodule]["name"],"detail",$obj->idrecord);
			$identifier ='&nbsp;<a href="'.$GLOBALS['SITE_URL'].$detail_url.'">'.$obj->identifier.'</a>' ;
		} else {
			$identifier = $obj->identifier ;
		}
		$content.= ' '.$identifier;
		
		if (strlen($related_identifer_text) > 5) {
			if ($link_related_identifier === true) {
				$related_detail_url = NavigationControl::getNavigationLink($_SESSION["do_module"]->modules_full_details[$obj->related_identifier_idmodule]["name"],"detail",$obj->related_identifier_idrecord); 
				$related_identifier = '&nbsp;<a href="'.$GLOBALS['SITE_URL'].$related_detail_url.'">'.$obj->related_identifier.'</a>' ;
			} else {
				$related_identifier = $obj->related_identifier;
			}
			$content.= ' '.$related_identifer_text.$related_identifier;
		}
		return array("user_name"=>$action_user,"avatar"=>$thumb,"content"=>$content,"action_date"=>$date_added);
	}
    
	/**
	* function to set the live feed as viewed
	* @param integer $id
	*/
	public function set_feed_viewed($id) {
		$this->query("update ".$this->getTable()." set `viewed` = 1 where `idfeed_queue` = ?",array($id));
	}
    
	/**
	* function to set the feeds as viewed while login
	* if user is not in the home page or did not login for a long period then the feeds created during that period
	* is set to viewed for that user while login so that they dont start loading on the home page one by one. 
	* This will enable user to view the live feed and old feeds could be seen by scrolling the feed block
	* @param integer $iduser
	*/
	public function set_feed_viewed_onlogin($iduser) {
		$q_upd = "
		update ".$this->getTable()." 
		set `viewed` = ? 
		where `iduser_for` = ? 
		AND `viewed` = ?" ;
		$this->query($q_upd,array(1,$iduser,0));
	}
    
}