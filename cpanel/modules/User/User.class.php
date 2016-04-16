<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class User, maintain cpanel (customerportal) user related actions
* @author Abhik Chakraborty
*/ 
namespace cpanel_user ;

class User extends \DataObject {
	public $table = "cpanel_user";
	public $primary_key = "idcpanel_user";
	protected $subordinate_users = array();
	protected $subordinate_contacts = array();

	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
	
	/**
	* event function for cpanel login
	* @param object $evctl
	* @return void
	*/
	public function eventLogin(\EventControler $evctl) {
		$login_success = false ;
		if ($evctl->user_name !='' && $evctl->user_password !='') {
			$qry = "
			select cu.*,c.firstname,c.lastname,c.contact_avatar from ".$this->getTable()." cu
			join contacts c on c.idcontacts = cu.idcontacts
			where 
			cu.`email` = ? 
			AND cu.`password` = ?
			AND c.deleted = 0
			AND c.portal_user = 1
			" ;
			$this->query($qry,array($evctl->user_name,MD5($evctl->user_password)));
			if ($this->getNumRows() == 1) { 
				$this->next() ;
				$idcpanel_user = $this->idcpanel_user ;
				$login_success = true ;
			} elseif ($this->getNumRows() > 1) {
				$msg = _('Looks like you have multiple login for the customer portal for different organization, please ask the provider to reset the password for one');
			} else {
				$msg = _('Authentication failed ! Invalid login details');
			}
			if (true === $login_success) {
				$this->set_subordinates($this->idcpanel_user,$this->idorganization);
				$_SESSION["do_cpanel_action_permission"]->load_cpanel_user_modules($this->idorganization);
				$this->sessionPersistent("do_cpaneluser","logout.php",TTL_LONG);
				if (!is_object($_SESSION["do_global_settings"])) {
					$do_global_settings = new \CRMGlobalSettings();
					$do_global_settings->sessionPersistent("do_global_settings", "logout.php", TTL);
				}
				$dis = new \Display($evctl->goto); //@see view/login_view
				if ((int)$evctl->sqrecord > 0) {
					$dis->addParam("sqrecord",(int)$evctl->sqrecord);
				}
				$evctl->setDisplayNext($dis) ; 
			} else {
				$_SESSION["do_cpanel_messages"]->set_message('error',$msg);
			}
		} else {
			$_SESSION["do_cpanel_messages"]->set_message('error',_('Missing email or password for authentication'));
		}
	}
	
	/**
	* function to set the subordinate users (contacts) for cpanel users
	* @param integer $idcpanel_user
	* @param integer $idorganization
	* @return void
	*/
	public function set_subordinates($idcpanel_user,$idorganization) {
		$qry = "select * from cpanel_user_roles where idcpanel_user = ? and idorganization = ?";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idcpanel_user,$idorganization));
		if ($stmt->rowCount() > 0) {
			$data = $stmt->fetch() ;
			$parentrole = $data["parentrole"] ;
			$qry = "
			select 
			cu.idcontacts,
			cu.idcpanel_user
			from 
			cpanel_user cu 
			join cpanel_user_roles cur on cur.idcpanel_user = cu.idcpanel_user
			where cu.idorganization = ?
			and cur.parentrole like ?
			" ;
			$stmt1 = $this->getDbConnection()->executeQuery($qry,array($idorganization,$parentrole.'::%'));
			if ($stmt1->rowCount() > 0) {
				while ($data=$stmt1->fetch()) {
					$this->subordinate_users[] = $data["idcpanel_user"];
					$this->subordinate_contacts[] = $data["idcontacts"];
				}
			}
		}
	}
	
	/**
	* function to get the subordinate users of cpanel user
	* @return array
	*/
	public function get_subordinate_users() {
		return $this->subordinate_users ;
	}
	
	/**
	* function to get the subordinate contacts of cpanel user
	* @return array
	*/
	public function get_subordinate_contacts() {
		return $this->subordinate_contacts ;
	}
	
	/**
	* event function to upload the cpanel profile image
	* Updates the contacts table
	* @param object $evctl
	* @return string
	*/
	public function eventUploadUserAvatar(\EventControler $evctl) {
		if ($_FILES["contact_avatar"]["name"] == '') {
			echo '0' ;
		} else {
			if ($_FILES['contact_avatar']['tmp_name'] != '') {
				$file_size = $_FILES['contact_avatar']['size'] ;
				$hidden_file_name = 'upd_contact_avatar' ;
				$current_file_name_in_db = $evctl->$hidden_file_name  ;
				if ($current_file_name_in_db != '') {
					\FieldType12::remove_thumb($current_file_name_in_db) ;
				}
				$value = \FieldType12::upload_avatar($_FILES['contact_avatar']['tmp_name'],$_FILES['contact_avatar']['name']) ;
				if (is_array($value) && array_key_exists('name',$value)) {
					$qry = "
					update `contacts`
					set `contact_avatar` = ?
					where `idcontacts` = ?
					limit 1
					" ;
					$this->getDbConnection()->executeQuery($qry,array($value['name'],$_SESSION["do_cpaneluser"]->idcontacts)) ;
					$do_files_and_attachment = new \CRMFilesAndAttachments();
					$do_files_and_attachment->addNew();
					$do_files_and_attachment->file_name = $value["name"];
					$do_files_and_attachment->file_mime = $value["mime"];
					$do_files_and_attachment->file_size = $file_size ;
					$do_files_and_attachment->file_extension = $value["extension"];
					$do_files_and_attachment->idmodule = 4;
					$do_files_and_attachment->id_referrer = $_SESSION["do_cpaneluser"]->idcontacts;
					$do_files_and_attachment->iduser = 0;
					$do_files_and_attachment->date_modified = date("Y-m-d H:i:s");
					$do_files_and_attachment->add() ;
					$_SESSION["do_cpaneluser"]->contact_avatar = $value["name"] ;
					echo \FieldType12::get_file_name_with_path($value["name"],'s') ;
				} else {
					echo '0' ;
				}
			} else {
				echo '0' ;
			}
		}
	}
	
	/**
	* event function to update the cpanel user password
	* @param object $evctl
	* @return string
	*/
	public function eventChangePassword(\EventControler $evctl) {
		$err = '';
		if ($_SESSION["do_cpaneluser"]->idcpanel_user > 0) {
			if ($evctl->password == '') {
				$err = _('Please add a password before update') ;
			} elseif (strlen($evctl->password) < 8) {
				$err = _('Password should be minimum of 8 characters long') ;
			} elseif ($evctl->password != $evctl->confirm_password) {
				$err = _('Password and confirm password not matching') ;
			}
		} else {
			$err = _('Oops your session has expired ! Please refresh the page !') ;
		}
		if (trim($err) == '') {
			$qry = "
			update `cpanel_user` 
			set password = ?
			where idcpanel_user = ?
			limit 1
			" ;
			$stmt = $this->getDbConnection()->executeQuery($qry,array(md5($evctl->password),$_SESSION["do_cpaneluser"]->idcpanel_user));
			echo '1' ;
		} else {
			echo $err ;
		}
	}
	
	/**
	* event function to signout
	* @param object $evctl
	*/
	public function eventLogout(\EventControler $evctl) {
		//do login audit
		$this->setFree();
		$this->free();
		// Unset all of the session variables.
		$_SESSION = array();
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/cpanel/');
		}
		// Finally, destroy the session.
		session_destroy();
		$dis = new \Display('/cpanel/modules/User/login');
		$evctl->setDisplayNext($dis) ; 
	}	
}