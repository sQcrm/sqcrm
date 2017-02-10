<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ProjectEmailer
* Handles different emailing feature related to the project
* @author Abhik Chakraborty
*/ 
	

class ProjectEmailer extends SQEmailer {
	
	/**
	* function to send the project invitation email
	* @param object $project
	* @param integer $idinvitation
	* @param integer $idinvitee
	* @param integer $idsender
	* @return void
	*/
	public function send_project_member_invitation_email(Project $project, $idinvitation, $idinvitee, $idsender = 0) {
		$do_invitee = new User();
		$do_invitee->getId($idinvitee);
		
		if ($do_invitee->getNumRows() == 0) return ;
		
		if ((int)$idsender == 0) {
			$sender_firstname = $_SESSION["do_user"]->firstname;
			$sender_lastname = $_SESSION["do_user"]->lastname;
		} else {
			$do_user = new User();
			$do_user->getId($idsender);
			$sender_firstname = $do_user->firstname;
			$sender_lastname = $do_user->lastname;
		}
		
		$email_template = new EmailTemplate("project_invitation");
		$email_data = array(
			'CRM_NAME'=>CRM_NAME,
			'firstname'=>$do_invitee->firstname,
			'lastname'=>$do_invitee->lastname,
			'activation_url'=>SITE_URL.'/Project/invitation/'.$idinvitation,
			'project_name'=>$project->project_name,
			'sender_firstname'=>$sender_firstname,
			'sender_lastname'=>$sender_lastname
		);
		$this->IsSendmail();
		$this->setEmailTemplate($email_template);
		$this->mergeArray($email_data);
		$this->AddAddress($do_invitee->email, $do_invitee->firstname.' '.$do_invitee->lastname);
		$this->send();
		
		$do_invitee->free();
	}
	
	/**
	* function to send the project invitation accept/reject email
	* @param integer $idinvitation
	* @param string $type
	* @return void
	*/
	public function send_project_accept_reject_email($idinvitation,$type) {
		$qry = "select * from `project_members` where `idproject_members` = ?";
		$stmt = $GLOBALS['conn']->executeQuery($qry,array($idinvitation));
		$data = $stmt->fetch();
		$idproject = $data['idproject'];
		$sender = $data['sender'];
		$idinvitee = $data['iduser'];
		$do_project = new Project();
		$do_sender = new User();
		$do_invitee = new User();
		
		$do_project->getId($idproject);
		$do_invitee->getId($sender);
		$do_sender->getId($sender);
		
		if ($do_project->getNumRows() > 0 && $do_sender->getNumRows() > 0 && $do_invitee->getNumRows() > 0) {
			if ($type == 'accept') {
				$email_template = new EmailTemplate("project_invitation_accepted");
			} elseif ($type == 'reject') {
				$email_template = new EmailTemplate("project_invitation_rejected");
			}
			$email_data = array(
				'CRM_NAME'=>CRM_NAME,
				'firstname'=>$do_invitee->firstname,
				'lastname'=>$do_invitee->lastname,
				'project_name'=>$do_project->project_name,
				'sender_firstname'=>$do_sender->firstname,
				'sender_lastname'=>$do_sender->lastname
			);
			
			$this->IsSendmail();
			$this->setEmailTemplate($email_template);
			$this->mergeArray($email_data);
			$this->AddAddress($do_sender->email, $do_sender->firstname.' '.$do_sender->lastname);
			$this->send();
			
			$do_project->free();
			$do_sender->free();
			$do_invitee->free();
		}
	}
	
	/**
	* function to send email when a member is removed from the project
	* @param integer $idproject
	* @param integer $iduser
	* @return void
	*/
	public function send_removed_from_project_email($idproject, $iduser) {
		$do_project = new Project();
		$do_user = new User();
		$do_project->getId($idproject);
		$do_user->getId($iduser);
		
		if ($do_project->getNumRows() > 0 && $do_user->getNumRows() > 0) {
			$email_template = new EmailTemplate("project_revoke_access");
			$email_data = array(
				'CRM_NAME'=>CRM_NAME,
				'firstname'=>$do_user->firstname,
				'lastname'=>$do_user->lastname,
				'project_name'=>$do_project->project_name
			);
			
			$this->IsSendmail();
			$this->setEmailTemplate($email_template);
			$this->mergeArray($email_data);
			$this->AddAddress($do_user->email, $do_user->firstname.' '.$do_user->lastname);
			$this->send();
			
			$do_project->free();
			$do_user->free();
		}
	}
	
	/**
	* function to send email when a task is assigned to an user
	* @param integer $idassignee
	* @param array $data
	* @return void
	*/
	public function send_task_assigned_email($idassignee, $data) {
		if ((int)$idassignee > 0) {
			$do_user = new User();
			$do_user->getId($idassignee);
			$email_template = new EmailTemplate("project_task_assigned");
			$email_data = array(
				'CRM_NAME'=>CRM_NAME,
				'firstname'=>$do_user->firstname,
				'lastname'=>$do_user->lastname,
				'task_title'=>$data['task_title'],
				'assignee_firstname'=>$data['assignee_firstname'],
				'project_name'=>$data['project_name'],
				'task_url'=> $data['task_url']
			);
			$this->resetSenderData();
			$this->IsSendmail();
			$this->setEmailTemplate($email_template);
			$this->mergeArray($email_data);
			$this->AddAddress($do_user->email, $do_user->firstname.' '.$do_user->lastname);
			$this->send();
			$do_user->free();
		}
	}
	
	/**
	* function to send email when a new task is added
	* @param array $project_member
	* @param array $data
	* @param integer $iduser
	* @return void
	*/
	public function send_new_task_email($project_member, $data, $iduser) {
		$task_note = $data['task_note'];
		$task_note = FieldType200::display_value($task_note, false);
		$task_note = str_replace('/themes/images/emoji-pngs',SITE_URL.'/themes/images/emoji-pngs',$task_note);
		$email_receiptents = $this->get_task_email_receiptents($project_member, $data, $iduser);
		
		if (count($email_receiptents) > 0) {
			$email_data = array(
				'CRM_NAME'=>CRM_NAME,
				'firstname'=>$data['firstname'],
				'lastname'=>$data['lastname'],
				'email' => $data['email'],
				'task_title'=>$data['task_title'],
				'project_name'=>$data['project_name'],
				'task_note'=>$task_note,
				'task_url'=>$data['task_url']
			);
			
			if (array_key_exists('mentions',$email_receiptents)) {
				$mentioned_template = new EmailTemplate("project_new_task");
				$this->resetSenderData();
				$this->setSenderData($data['email'], $data['firstname'].' '.$data['lastname']);
				$this->IsSendmail();
				$this->setEmailTemplate($mentioned_template);
				$this->mergeArray($email_data);
				
				foreach ($email_receiptents['mentions'] as $key=>$val) {
					// send mentioned email
					$this->AddAddress($val['email'], $val['firstname'].' '.$val['lastname']);
					$this->send();
				}
			}
			
			if (array_key_exists('no_mentions',$email_receiptents)) {
				$task_template = new EmailTemplate("project_new_task");
				$this->resetSenderData();
				$this->setSenderData($data['email'], $data['firstname'].' '.$data['lastname']);
				$this->IsSendmail();
				$this->setEmailTemplate($task_template);
				$this->mergeArray($email_data);
				
				foreach ($email_receiptents['no_mentions'] as $key=>$val) {
					// send normal email
					$this->AddAddress($val['email'], $val['firstname'].' '.$val['lastname']);
					$this->send();
				}
			}
		}
	}
	
	/**
	* function to send task discussion email
	* @param array $project_member
	* @param array $data
	* @param integer $iduser
	* @return void
	*/
	public function send_task_discussion_email($project_member,$data,$iduser) {
		$task_note = $data['task_note'];
		$task_note = FieldType200::display_value($task_note,false);
		$task_note = str_replace('/themes/images/emoji-pngs',SITE_URL.'/themes/images/emoji-pngs',$task_note);
		$email_receiptents = $this->get_task_email_receiptents($project_member, $data, $iduser);
		
		if (count($email_receiptents) > 0) {
			$email_data = array(
				'CRM_NAME'=>CRM_NAME,
				'firstname'=>$data['firstname'],
				'lastname'=>$data['lastname'],
				'email' => $data['email'],
				'task_title'=>$data['task_title'],
				'project_name'=>$data['project_name'],
				'task_note'=>$task_note,
				'task_note_url'=>$data['task_note_url'],
			);
				
			if (array_key_exists('mentions',$email_receiptents)) {
				$mentioned_note_template = new EmailTemplate("project_task_note_mention");
				$this->resetSenderData();
				$this->setSenderData($data['email'], $data['firstname'].' '.$data['lastname']);
				$this->IsSendmail();
				$this->setEmailTemplate($mentioned_note_template);
				$this->mergeArray($email_data);
				
				foreach ($email_receiptents['mentions'] as $key=>$val) {
					// send mentioned email
					$this->AddAddress($val['email'], $val['firstname'].' '.$val['lastname']);
					$this->send();
				}
			}
			
			if (array_key_exists('no_mentions',$email_receiptents)) {
				$task_note_template = new EmailTemplate("project_task_note");
				$this->resetSenderData();
				$this->setSenderData($data['email'], $data['firstname'].' '.$data['lastname']);
				$this->IsSendmail();
				$this->setEmailTemplate($task_note_template);
				$this->mergeArray($email_data);
				
				foreach ($email_receiptents['no_mentions'] as $key=>$val) {
					// send normal email
					$this->AddAddress($val['email'], $val['firstname'].' '.$val['lastname']);
					$this->send();
				}
			}
		}
	}
	
	/**
	* function to get the email receiptents when a task is added or note is added
	* @param array $project_member
	* @param array $data
	* @param integer $iduser
	* @return array
	*/
	public function get_task_email_receiptents($project_member, $data, $iduser) {
		$email_receiptents = array();
		$members = $project_member['assigned_to'];
		
		if (count($project_member['other_assignee']) > 0) {
			$members = array_merge($members, $project_member['other_assignee']);
		}
		
		$task_note = $data['task_note'];
		
		if ($task_note != '') {
			preg_match_all("/(^|[^@\w])@(\w{1,15})\b/im", $task_note, $mentioned_users);
		}
		
		$do_project = new Project();
		
		foreach ($members as $key=>$val) {
			if ($key == $iduser) continue;
			$mentioned = false;
			$subscription_value = $do_project->get_email_subscription_for_project_by_user($data['idproject'], $key);
			
			if (is_array($mentioned_users) && array_key_exists(2,$mentioned_users) && count($mentioned_users[2]) > 0) {
				foreach ($mentioned_users[2] as $k=>$v) {
					if ($val['user_name'] == $v && ($subscription_value == 1 || $subscription_value == 2)) {
						$mentioned  =true;
						$email_receiptents['mentions'][] = array(
							'iduser'=>$val['iduser'],
							'user_name'=>$val['user_name'],
							'firstname'=>$val['firstname'],
							'lastname'=>$val['lastname'],
							'email'=>$val['email']
						);
						
						break;
					}
				}
			}
			
			if (false === $mentioned &&  $subscription_value == 1) {
				$email_receiptents['no_mentions'][] = array(
					'iduser'=>$val['iduser'],
					'user_name'=>$val['user_name'],
					'firstname'=>$val['firstname'],
					'lastname'=>$val['lastname'],
					'email'=>$val['email']
				);
			}
		}
		
		return $email_receiptents;
	}
}