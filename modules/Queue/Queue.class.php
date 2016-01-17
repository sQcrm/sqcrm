<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMNotes
* @author Abhik Chakraborty
*/
	
class Queue extends DataObject {
	public $table = "queue";
	public $primary_key = "idqueue";
	
	public $module_group_rel_table = '';
	
	/**
	* function to load the queues 
	* @param integer $iduser
	* @return array
	*/
	public function get_all_queue($iduser = 0) {
		if ((int)$iduser == 0) {
			$iduser = $_SESSION['do_user']->iduser ;
			$user_timezone = $_SESSION['do_user']->user_timezone ;
		} else {
			$do_user = new User() ;
			$do_user->getId($iduser) ;
			$user_timezone = $do_user->user_timezone ;
		}
		$today = TimeZoneUtil::get_user_timezone_date($user_timezone) ;
		$qry="
		select 
		q.*,
		m.name as module_name,
		m.module_label
		from queue q
		inner join module m on m.idmodule = q.related_module_id
		where q.iduser = ?
		and q.queue_date >= ?
		order by q.queue_date,q.related_module_id
		";
		$this->query($qry,array($iduser,$today)) ;
		$return_array = array() ;
		if ($this->getNumRows() > 0) {
			$todayDateObj = new DateTime($today);
			$do_crm_entity = new CRMEntity() ;
			while ($this->next()) {
				$queueDateObj = new DateTime($this->queue_date);
				$diff = $todayDateObj->diff($queueDateObj);
				$day_diff = $diff->format('%R%a'); 
				$data = array(
					"idqueue" => $this->idqueue ,
					"sqcrm_record_id" => $this->sqcrm_record_id ,
					"module_name" => $this->module_name ,
					"module_label" => $this->module_label,
					"idmodule" => $this->related_module_id,
					"entity_identifier" =>$do_crm_entity->get_entity_identifier($this->sqcrm_record_id,$this->module_name)
				) ;
				if ($day_diff == 0) {
					$return_array["today"][] = $data ;
				} elseif ($day_diff == 1) {
					$return_array["tomorrow"][] = $data ;
				} elseif($day_diff > 1) {
					$return_array["later"][] = $data ;
				}
			}
		}
		return $return_array ;
	}
	
	/**
	* event function to render the queue update option 
	* @param object $evctl
	* @return string
	*/
	public function eventAjaxGetQueueEditOptions(EventControler $evctl) {
		$iduser = $_SESSION['do_user']->iduser ;
		if ((int)$evctl->id > 0) {
			if (true === $this->is_valid_queue_id((int)$evctl->id,$iduser)) {
				$this->next();
				$queue_date = $this->queue_date ;
				echo _('Queue date :: ') ;
				echo '<br />'.FieldType9::display_field('queue_date',$queue_date);	
			} else {
				echo _('Queue id is invalid or does not belong to you !') ;
			}
		} else {
			echo _('Queue id is missing') ;
		}
	}
	
	/**
	* event function to update the queue 
	* @param object $evctl
	* @return string
	*/
	public function eventAjaxUpdateQueue(EventControler $evctl) {
		if ((int)$evctl->id > 0 && trim($evctl->date) != '') {
			$iduser = $_SESSION['do_user']->iduser ;
			if (true === $this->is_valid_queue_id((int)$evctl->id,$iduser)) {
				$date = FieldType9::convert_before_save($evctl->date) ;
				$user_timezone = $_SESSION['do_user']->user_timezone ;
				$today = TimeZoneUtil::get_user_timezone_date($user_timezone) ;
				$todayDateObj = new DateTime($today);
				$queueDateObj = new DateTime($date);
				$diff = $todayDateObj->diff($queueDateObj);
				$day_diff = $diff->format('%R%a'); 
				if ($day_diff < 0) {
					echo _('Older date for queue is not allowed !') ;
				} else {
					$qry = "
					update `queue`
					set queue_date = ?
					where `idqueue` = ?
					" ;
					$this->query($qry,array($date,(int)$evctl->id)) ;
					if ($day_diff == 0) {
						echo 'today' ;
					} elseif ($day_diff == 1) {
						echo 'tomorrow' ;
					} elseif ($day_diff > 1) {
						echo 'later' ;
					}
				}
			} else {
				echo _('Queue id is invalid or does not belong to you !') ;
			}
		} else {
			echo _('Either id or date is missing') ;
		}
	}
	
	/**
	* event function to delete the queue
	* @param object $evctl
	* @return string
	*/
	public function eventAjaxDeleteQueue(EventControler $evctl) {
		if ((int)$evctl->id > 0) {
			$iduser = $_SESSION['do_user']->iduser ;
			if (true === $this->is_valid_queue_id((int)$evctl->id,$iduser)) {
				$qry = "
				delete from `queue`
				where `idqueue` = ?
				" ;
				$this->query($qry,array((int)$evctl->id)) ;
				echo '1' ;
			} else {
				echo _('Queue id is invalid or does not belong to you !') ;
			}
		} else {
			echo _('Missing queue id !');
		}
	}
	
	/**
	* function to check if the queue is valid 
	* @param integer $id
	* @param integer $iduser
	* @return boolean
	*/
	public function is_valid_queue_id($id,$iduser) {
		$qry = "
		select * from `queue`
		where 
		`idqueue` = ?
		and `iduser` = ?
		";
		$this->query($qry,array($id,$iduser)) ;
		if ($this->getNumRows() > 0) {
			return true ;
		} else {
			return false ;
		}
	}
	
	/**
	* function to get the queue detail 
	* @param integer $sqcrm_record_id
	* @param integer $related_module_id
	* @param integer $iduser
	* @return array
	*/
	public function get_queue_detail($sqcrm_record_id,$related_module_id,$iduser=0) {
		if ((int)$iduser == 0) {
			$iduser = $_SESSION['do_user']->iduser ;
			$user_timezone = $_SESSION['do_user']->user_timezone ;
		} else {
			$do_user = new User() ;
			$do_user->getId($iduser) ;
			$user_timezone = $do_user->user_timezone ;
		}
		$today = TimeZoneUtil::get_user_timezone_date($user_timezone) ;
		$return_array = array() ;
		$qry = "
		select * from `queue`
		where 
		`iduser` = ?
		and `sqcrm_record_id` = ?
		and `related_module_id` = ?
		and `queue_date` >= ?
		" ;
		$this->query($qry,array($iduser,$sqcrm_record_id,$related_module_id,$today)) ;
		if ($this->getNumRows() > 0) {
			$this->next() ;
			$todayDateObj = new DateTime($today) ;
			$queueDateObj = new DateTime($this->queue_date);
			$diff = $todayDateObj->diff($queueDateObj);
			$day_diff = $diff->format('%R%a'); 
			if ($day_diff == 0) {
				$day = 'today' ;
			} elseif ($day_diff == 1) {
				$day = 'tomorrow' ;
			} elseif ($day_diff > 1) {
				$day = 'later' ;
			}
			$return_array = array(
				"idqueue" => $this->idqueue ,
				"day" => $day
			) ;
		}
		return $return_array ;
	}
	
	/**
	* function to check if queue is allowed for the given module
	* @param integer $idmodule
	* @return boolean
	*/
	public function queue_permitted_for_module($idmodule) {
		$qry = "
		select * from `queue_module_rel`
		where `idmodule` = ?
		" ;
		$this->query($qry,array($idmodule)) ;
		if ($this->getNumRows() > 0) {
			return true ;
		} else {
			return false ;
		}
	}
	
	/**
	* event function to add an entity to queue
	* @param object $evctl
	* @return string
	*/
	public function eventAjaxAddQueue(EventControler $evctl) {
		if (trim($evctl->date) != '' && (int)$evctl->related_module_id > 0 && (int)$evctl->related_record_id > 0) {
			$user_timezone = $_SESSION['do_user']->user_timezone ;
			$date = FieldType9::convert_before_save($evctl->date) ; 
			$today = TimeZoneUtil::get_user_timezone_date($user_timezone) ;
			$todayDateObj = new DateTime($today);
			$queueDateObj = new DateTime($date);
			$diff = $todayDateObj->diff($queueDateObj);
			$day_diff = $diff->format('%R%a'); 
			if ($day_diff < 0) {
				echo _('Older date for queue is not allowed !') ;
			} else {
				$this->addNew() ;
				$this->sqcrm_record_id = (int)$evctl->related_record_id ;
				$this->related_module_id = (int)$evctl->related_module_id ;
				$this->iduser = $_SESSION['do_user']->iduser ; 
				$this->queue_date = $date ;
				$this->add() ;
				echo '1' ;
			}
		} else {
			echo _('Missing module id , date or record id to be added in queue !') ;
		}
	}
	
	/**
	* function to delete the older queue via the cronjob
	* @return void
	* @see /crons/cron_delete_oldqueue.php
	*/
	public function delete_older_queue() {
		$qry = "
		delete from `queue`
		where 
		`queue_date` <= date_sub(curdate(),interval 2 day)
		" ;
		$this->query($qry);
	}
}