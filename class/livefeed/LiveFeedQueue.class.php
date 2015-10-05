<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class LiveFeedQueue
* @author Abhik Chakraborty
*/
	

class LiveFeedQueue extends DataObject {
	public $table = "feed_queue";
	public $primary_key = "idfeed_queue";
    
	/**
	* function to add live feed
	* @param integer $idrecord
	* @param integer $idmodule
	* @param string $identifier
	* @param string $action
	* @param array $related_identifier
	* @param array $other_assigne
	* @param integer $iduser
	*/
	public function add_feed_queue($idrecord,$idmodule,$identifier,$action,$other_assigne=array(),$related_identifier_info=array(),$iduser = '') {
		if ($iduser == '') $iduser = $_SESSION["do_user"]->iduser ;
		$do_user = new User();
		$feed_users = $do_user->get_parent_users_by_iduser();
		$other_assigne_users = $this->get_other_assigne($iduser,$other_assigne);
		if (count($other_assigne_users) > 0) {
			$feed_users = array_merge($feed_users,$other_assigne_users);
		}
		if (count($feed_users) > 0) {
			array_unique($feed_users);
			foreach ($feed_users as $feed_users) {
				$this->addNew();
				$this->idrecord = $idrecord;
				$this->idmodule = $idmodule;
				$this->identifier = $identifier ;
				$this->action = $action;
				$this->date_added = date("Y-m-d h:i:s");
				$this->iduser = $iduser;
				$this->iduser_for = $feed_users;
				if (count($related_identifier_info) > 0) {
					$related_identifier = $related_identifier_info["related_identifier"];
					$related_identifier_idrecord = $related_identifier_info["related_identifier_idrecord"];
					$related_identifier_idmodule = $related_identifier_info["related_identifier_idmodule"];
				} else {
					$related_identifier = '';
					$related_identifier_record = 0 ;
					$related_identifier_idmodule = 0 ;
				}
				$this->related_identifier = $related_identifier;
				$this->related_identifier_idrecord = $related_identifier_idrecord;
				$this->related_identifier_idmodule = $related_identifier_idmodule;
				$this->add();
			}
		}
	}
    
	/**
	* function to get the other assigne for feed. By default a feed is displayed to the users
	* above the action user. But in some situations its necessary to notify other user like the 
	* users within the same group etc. 
	* This method will return those users for feed.
	* @param integer $action_user_id
	* @param array $other_assigne
	* @param integer $idrecord
	*/
	public function get_other_assigne($action_user_id,$other_assigne,$idrecord=0) { 
		$other_assigne_users = array() ;
		if (array_key_exists("related",$other_assigne)) {
			switch ($other_assigne["related"]) {
				case "group" :
					if (array_key_exists("data",$other_assigne)) {
						if (array_key_exists("key",$other_assigne["data"]) && $other_assigne["data"]["key"] == "oldgroup") {
							$idgroup = $other_assigne["data"]["val"] ;
							$do_group_user_rel = new GroupUserRelation();
							$do_group_user_rel->get_users_related_to_group($idgroup);
							if ($do_group_user_rel->getNumRows() > 0) {
								while ($do_group_user_rel->next()) {
									$other_assigne_users[] = $do_group_user_rel->iduser ;
								}
							}
						}
						if (array_key_exists("key",$other_assigne["data"]) && $other_assigne["data"]["key"] == "newgroup") {
							$idgroup = $other_assigne["data"]["val"] ;
							$do_group_user_rel = new GroupUserRelation();
							$do_group_user_rel->get_users_related_to_group($idgroup);
							if ($do_group_user_rel->getNumRows() > 0) {
								while ($do_group_user_rel->next()) {
									$other_assigne_users[] = $do_group_user_rel->iduser ;
								}
							}
						}
					}
				break;
			}
		}
		return $other_assigne_users ;
	}
    
	/**
	* function to get the feeds which needs to be deleted to make the table feed_queue happy and not having 
	* a lot of data
	* @see crons/cron_delete_oldfeeds.php
	*/
	public function get_feeds_to_be_deleted() {
		$keep_feed_till = $GLOBALS['DAYS_TO_KEEP_FEED'] ;
		$qry = "select `idfeed_queue` from ".$this->getTable()." where DATEDIFF(now(),`date_added`) > ?" ;
		$this->query($qry,array($keep_feed_till));
	}
    
	/**
	* function to delete feed with id
	* @param integer $id
	* @see crons/cron_delete_oldfeeds.php
	*/
	public function delete_feed_queue($id) {
		$qry = "delete from `".$this->getTable()."` where `idfeed_queue` = ? limit 1";
		$this->getDbConnection()->executeQuery($qry,array($id)) ;
	}
}