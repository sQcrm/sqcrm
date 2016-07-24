<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Class EmailerSendWithUs
* EmailerSendWithUs plugin for CRM 
* @link https://www.sendwithus.com/docs/api
* @author Abhik Chakraborty
*/
use sendwithus\API;
class EmailerSendWithUs extends CRMPluginProcessor {
	
	private $api_key = '';
    
	/**
	* constructor function for the sQcrm plugin
	*/
	public function __construct() {
		$this->set_plugin_title(_('Emailer with sendwithus API')); // required
		$this->set_plugin_name('EmailerSendWithUs') ; // required same as your class name 
		$this->set_plugin_type(array(7,8)); // required 
		$this->set_plugin_modules(array(4)); // required
		$this->set_list_view_plugin_position(array(1)); // required
		$this->set_detail_view_plugin_position(array(1)); // required
		$this->set_plugin_description(
			_('This plugin is to manage the emailer using sendwithus API <a href="https://www.sendwithus.com" target="_blank">https://www.sendwithus.com</a><br /><br />
			You can either place the api key on 
			<br /><br /><i>/plugins/EmailerSendWithUs/EmailerSendWithUs.class.php <br />private $api_key = \'your api key\'</i>
			<br /><br />or better create a <i>config.json</i> on <b>/plugins/EmailerSendWithUs/</b> and the place the api key in the file as <br />
			<i>{"apiKey":"your api key"}</i> 
			<br /><br />This is recommended so that any upgrade of the plugin could be done easily and your
			config remains unchanged.
			'
			)
		); // optional
	}
	
	/**
	* function to set the api key 
	* @param string $key
	*/
	public function set_api_key($key) {
		$this->api_key = $key ;
	}
	
	/**
	* function to get the api key
	* @return string 
	*/
	public function get_api_key() {
		if (file_exists(BASE_PATH.'/plugins/EmailerSendWithUs/config.json')) {
			$config = file_get_contents(BASE_PATH.'/plugins/EmailerSendWithUs/config.json') ;
			if (strlen($config) > 3) {
				$config_decoded = json_decode($config) ;
				$this->set_api_key($config_decoded->apiKey) ;
			}
		}
		return $this->api_key ;
	}
	
	/**
	* function to get the api url
	* @return string 
	*/
	public function get_api_url() {
		return $this->api_url ;
	}
	
	/**
	* function to get the sendwithus api instance 
	* @return instance sendwithus\API
	*/
	public function get_api_instance() {
		require_once(BASE_PATH.'/plugins/EmailerSendWithUs/libs/sendwithus/vendor/autoload.php');
		$api_key = $this->get_api_key();
		return new API($api_key);
	}
	
	/**
	* function to get the contacts details
	* @param string $ids
	* @return array
	*/
	public function get_contacts_detail($ids) {
		$qry = "
		select idcontacts,firstname,lastname,email,secondary_email,email_opt_out
		from contacts where idcontacts in (".$ids.") and deleted = 0
		";
		$stmt = $this->getDbConnection()->executeQuery($qry);
		$return_array = array();
		if ($stmt->rowCount() >0) {
			while($data = $stmt->fetch()) {
				$return_array[] = array(
					'email'=>$data['email'],
					'idcontacts'=>$data['idcontacts'],
					'firstname'=>$data['firstname'],
					'lastname'=>$data['lastname'],
					'secondary_email'=>$data['secondary_email'],
					'email_opt_out'=>$dat['email_opt_out']
				);
			}
		}
		return $return_array;
	}
	
	/**
	* event function to create a group and save the contact into it
	* @param object $evctl
	* Future feature
	*/
	public function eventCreateGroupAndSaveContacts(EventControler $evctl) {
		$api = $this->get_api_instance();
		$err = '';
		if ($evctl->groupName == '') {
			$err = _('Missing a group name');
		} elseif ($evctl->ids == '') {
			$err = _('Missing contact id(s)');
		}
		
		if (strlen($err) > 2) {
			echo $err;
			exit;
		} 
		
		$contacts = $this->get_contacts_detail($evctl->ids);
		if (count($contacts) > 0 ) {
			// create the group
			$res = $api->create_group($evctl->groupName);
			if ($res->success == 1) {
				$group_id = $res->group->id; 
			} else {
				echo $res->exception->body;
				exit;
			}
			$batch_api = $api->start_batch();
			foreach ($contacts as $key=>$val) {
				if ($val['email_opt_out'] == 1) continue;
				if ($val['email'] == '' && $val['secondary_email'] == '') continue;
				$email = ($val['email'] == '' ? $val['secondary_email'] : $val['email']);
				$contact_data = array(
					'idcontacts' => $val['idcontacts'],
					'firstname' => $val['firstname'],
					'lastname' => $val['lastname']
				);
				$result = $batch_api->create_customer($email,$contact_data,array('groups'=>array($group_id)));
			}
			$result = $batch_api->execute();
			echo '1';
		} else {
			echo _('No contact found to be added in the group');
			exit;
		}
	}
	
	/**
	* event function to save a contact to an existing group
	* @param object $evctl
	* Future feature
	*/
	public function evenSaveContactsToExistingGroup(EventControler $evctl) {
	}
	
	/**
	* event function to send email to selected contacts with a template 
	* @param object $evctl
	*/
	public function evenSendEmailWithTemplate(EventControler $evctl) {
		$err = '';
		if ($evctl->templateId == '') {
			echo _('Please select a template before sending the email');
			exit;
		}
		if ($evctl->ids == '') {
			echo _('Please select atleast one contact before sending the email');
			exit;
		}
		
		$contacts = $this->get_contacts_detail($evctl->ids);
		$contact_count = count($contacts);
		if ($contact_count > 0) {
			$mising_flag = 0;
			$api = $this->get_api_instance();
			$batch_api = $api->start_batch();
			foreach ($contacts as $key=>$val) {
				if ($val['email_opt_out'] == 1) { $mising_flag++; continue; }
				if ($val['email'] == '' && $val['secondary_email'] == '') { $mising_flag++; continue; }
				$email = ($val['email'] == '' ? $val['secondary_email'] : $val['email']);
				$result = $batch_api->send(
					$evctl->templateId,
					array('address'=>$email,'name'=>$val['firstname'].' '.$val['lastname']),
					array('template_data'=>array('first_name'=>$val['firstname'],'last_name'=>$val['lastname']))
				);
			}
			if ($mising_flag == 0) {
				$result = $batch_api->execute();
				echo '1';
			} elseif ($mising_flag < $contact_count) {
				$result = $batch_api->execute();
				echo '2';
			} else {
				echo _('No email sent, it could be contacts are missing the email id or email opt out is on');
				exit;
			}
		} else {
			echo _('No active contact found for sending email');
			exit;
		}
	}
	
	/**
	* event function to send email to an existing group contacts with a template
	* @param object $evctl
	* Future feature, and sendwithus still needs to implement this
	*/
	public function eventSendEmailToGroupWithTemplate(EventControler $evctl) {
	}
}