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
}