<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Class FullContact
* FullContact plugin for CRM 
* @link https://www.fullcontact.com/developer/
* @author Abhik Chakraborty
*/

class FullContact extends CRMPluginProcessor {
	public $table = "";
	public $primary_key = "";

	private $api_key = 'your api key';
	private $api_url = 'https://api.fullcontact.com/v2/' ;
	private $api_end_point = '';
	private $api_params = '' ;
    
	/**
	* array holding the social website type returned from full contact as key and icon name from font-awesome css
	*/
	public $available_social_icons = array(
		'facebook'=>'facebook','twitter'=>'twitter','pinterest'=>'pinterest','flickr'=>'flickr',
		'google'=>'google','github'=>'github','youtube'=>'youtube','xing'=>'xing','linkedin'=>'linkedin',
		'instagram'=>'instagram','angellist'=>'angellist','foursquare'=>'foursquare','vimeo'=>'vimeo',
		'amazon'=>'amazon','apple'=>'apple','bitbucket'=>'bitbucket','tumblr'=>'tumblr','windows'=>'windows',
		'reddit'=>'reddit','linux'=>'linux','slideshare'=>'slideshare','google-plus'=>'google-plus',
		'linkedincompany'=>'linkedin'
	);
    
	/**
	* constructor function for the sQcrm plugin
	*/
	public function __construct() {
		$this->set_plugin_title(_('Full Contact Plugin')); // required
		$this->set_plugin_name('FullContact') ; // required same as your class name 
		$this->set_plugin_type(array(7)); // required 
		$this->set_plugin_modules(array(4,6)); // required
		$this->set_plugin_position(1); // required
		$this->set_plugin_description(
			_('This plugin is to get the full information of the Contact or organization by domain name using the FullContact API. 
			You need to have your own api key and you can get it from 
			from <a href="https://www.fullcontact.com/developer/" target="_blank">https://www.fullcontact.com/developer/</a><br /><br />
			You can either place the api key on 
			<br /><br /><i>/plugins/FullContact/FullContact.class.php <br />private $api_key = \'your api key\'</i>
			<br /><br />or better create a <i>config.json</i> on <b>/plugins/FullContact/</b> and the place the api key in the file as <br />
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
		if (file_exists(BASE_PATH.'/plugins/FullContact/config.json')) {
			$config = file_get_contents(BASE_PATH.'/plugins/FullContact/config.json') ;
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
	* function to set the api end point 
	* @param string $end_point
	*/
	public function set_api_end_point($end_point) {
		$this->api_end_point = $end_point ;
	}
	
	/**
	* function to get the api end point
	* @return string
	*/
	public function get_api_end_point() {
		return $this->api_end_point ;
	}
	
	/**
	* function to set the api params in the query string
	* @param array $data
	*/
	public function set_api_params($data) {
		if (is_array($data) && count($data) > 0) {
			$query_string = '';
			foreach ($data as $key=>$val) {
				$query_string .= '&'.$val['key'].'='.$val['val'] ;
			}
			$this->api_params = $query_string ;
		}
	}
	
	/**
	* function to get the api params/query string
	* @return string
	*/
	public function get_api_params() {
		return $this->api_params ;
	}
	
	/**
	* function to init the api
	* @param string $api_end_point
	* @param array $data
	* @see self :: set_api_end_point
	* @see self :: set_api_params
	*/
	protected function init_api($api_end_point,$data) {
		$this->set_api_end_point($api_end_point) ;
		$this->set_api_params($data) ;
	}
	
	/**
	* function to call full contact api 
	* @param string $api_end_point
	* @param array $data
	* @return string
	* @link https://www.fullcontact.com/developer/
	* NOTE : make sure allow-url-fopen is set on php.ini http://us2.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen
	*/
	protected function call_full_contact_api($api_end_point,$data) {
		$this->init_api($api_end_point,$data) ;
		$url = $this->get_api_url().$this->get_api_end_point().'?apiKey='.$this->get_api_key().$this->get_api_params() ;
		$json = file_get_contents($url) ;
		return  $json ;
	}
	
	/**
	* event function to process the request 
	* @param object $evctl
	* @return string 
	*/
	public function eventProcessFullContactAPI(EventControler $evctl) {
		$api_end_point = '';
		$data = array() ;
		switch ($evctl->type) {
			case 'person' :
				$email = $evctl->email ;
				if ($email != '') {
					$data[] = array('key'=>'email','val'=>$email) ;
				}
				$api_end_point = 'person.json' ;
				break ;
			
			case 'company' :
				$website = $evctl->website ;
				// get domain name from website may be a better way considering any url pattern http:// or https:// just www
				$website = str_replace('https://','',$website);
				$website = str_replace('http://','',$website);
				$website = str_replace('www.','',$website);
				if ($website != '') {
					$data[] = array('key'=>'domain','val'=>$website) ;
				}
				$api_end_point = 'company/lookup.json' ;
				break ;
		}
		if (count($data) > 0 && $api_end_point != '') {
			$json = $this->call_full_contact_api($api_end_point,$data) ;
			echo $this->display_social_information($json,$evctl->type) ;
		} else {
			echo _('Missing parameters for the api call') ;
		}
	}
	
	/**
	* function to display the social information gathered via full contact api 
	* @param string $json
	* @param string $type
	*/
	public function display_social_information($json,$type) {
		if ($type == 'person') {
			return $this->construct_contact_social_information($json) ;
		} elseif ($type == 'company') {
			return $this->construct_company_social_information($json) ;
		}
	}
	
	/**
	* function to constract the html view of social information retrieved for contact using the email id
	* @param string $json
	* @return string
	*/
	public function construct_contact_social_information($json) {
		$html = '';
		$icon_not_present = '';
		$social_presence_with_icon = false ;
		if (strlen($json) > 3) {
			$json_decoded = json_decode($json,true) ; 
			if (count($json_decoded) > 0 && array_key_exists('status',$json_decoded) && $json_decoded['status'] == 200) {
				if (array_key_exists('socialProfiles',$json_decoded) && count($json_decoded['socialProfiles']) > 0) {
					$html .= _('Social websites') ;
					$html .= '<br /><br />' ;
					foreach ($json_decoded['socialProfiles'] as $key=>$val) {
						if (array_key_exists($val['typeId'],$this->available_social_icons)) {
							$social_presence_with_icon = true ;
							$icon_name = $this->available_social_icons[$val['typeId']];
							$html .= '<a data-toggle="tooltip" title="'.$val['type'].'" href="'.$val['url'].'" target="_blank"><i class="fa fa-'.$icon_name.' fa-2x"></i></a>' ;
							$html .= '&nbsp;&nbsp;';
						} else {
							$icon_not_present .= '<a href="'.$val['url'].'" target="_blank">'.$val['typeName'].'</a><br />' ;
						}
					}
				}
				if (strlen($icon_not_present) > 4) {
					$icon_not_present = (false === $social_presence_with_icon ? _('Social Websites'):'<br /><br />'. _('Also available on')).'<br /><br />'.$icon_not_present ;
				}
				return (true === $social_presence_with_icon ? $html : '').$icon_not_present ;
			} else {
				return _('No social data found !') ;
			}
		} else {
			return _('No social data found !') ;
		}
	}
	
	/**
	* function to constract the html view of social information retrieved for company using the website
	* @param string $json
	* @return string
	*/
	public function construct_company_social_information($json) {
		$html = '';
		$icon_not_present = '';
		$social_presence_with_icon = false ;
		if (strlen($json) > 3) {
			$json_decoded = json_decode($json,true) ;
			if (count($json_decoded) > 0 && array_key_exists('status',$json_decoded) && $json_decoded['status'] == 200) {
				if (array_key_exists('socialProfiles',$json_decoded) && count($json_decoded['socialProfiles']) > 0) {
					$html .= '<i>'._('Social websites').'</i>' ;
					$html .= '<br /><br />' ;
					foreach ($json_decoded['socialProfiles'] as $key=>$val) {
						if (array_key_exists($val['typeId'],$this->available_social_icons)) {
							$social_presence_with_icon = true ;
							$icon_name = $this->available_social_icons[$val['typeId']];
							$html .= '<a data-toggle="tooltip" title="'.$val['type'].'" href="'.$val['url'].'" target="_blank"><i class="fa fa-'.$icon_name.' fa-2x"></i></a>' ;
							$html .= '&nbsp;&nbsp;';
						} else {
							$icon_not_present .= '<a href="'.$val['url'].'" target="_blank">'.$val['typeName'].'</a><br />' ;
						}
					}
				}
				if (strlen($icon_not_present) > 4) {
					$icon_not_present = (false === $social_presence_with_icon ? '<i>'._('Social Websites').'</i>':'<br /><br /><i>'. _('Also available on').'</i>').'<br /><br />'.$icon_not_present ;
				}
				
				$email_info = '';
				$address_info = '';
				$phone_info = '';
				if (array_key_exists('organization',$json_decoded) && count($json_decoded['organization']) > 0 && array_key_exists('contactInfo',$json_decoded['organization']) && count($json_decoded['organization']['contactInfo']) > 0) {
				
					if (array_key_exists('emailAddresses',$json_decoded['organization']['contactInfo']) && count($json_decoded['organization']['contactInfo']['emailAddresses']) > 0) {
						$email_info .= '<br /><i>'._('Email addresses found').'</i><br /><br />' ;
						foreach ($json_decoded['organization']['contactInfo']['emailAddresses'] as $key=>$email) {
							$email_info .= $email['label'].' - '.'<a "mailto:'.$email['value'].'">'.$email['value'].'</a><br />' ;
						}
					}
					
					if (array_key_exists('addresses',$json_decoded['organization']['contactInfo']) && count($json_decoded['organization']['contactInfo']['addresses']) > 0) {
						$address_info .= '<br /><i>'._('Address').'</i><br /><br />' ; 
						foreach ($json_decoded['organization']['contactInfo']['addresses'] as $key=>$address) {
							$address_info .= '<address>';
							$address_info .= (array_key_exists('addressLine1',$address) && strlen($address['addressLine1']) > 3 ? $address['addressLine1'].'<br />':'');
							$address_info .= (array_key_exists('addressLine2',$address) && strlen($address['addressLine2']) > 3 ? $address['addressLine2'].'<br />':'');
							$address_info .= (array_key_exists('locality',$address) && strlen($address['locality']) > 3 ? $address['locality'].', ' :'');
							$address_info .= (array_key_exists('region',$address) && array_key_exists('name',$address['region']) && strlen($address['region']['name']) > 1 ? $address['region']['name'].', ' :'');
							$address_info .= (array_key_exists('country',$address) && array_key_exists('name',$address['country']) && strlen($address['country']['name']) > 1 ? $address['country']['name'].', ' :'');
							$address_info .= (array_key_exists('postalCode',$address) && strlen($address['postalCode']) > 1 ? $address['postalCode'].' ,' : '');
							$address_info = rtrim($address_info,',');
							$address_info .= '<address><br />' ;
						}
					}
					if (array_key_exists('phoneNumbers',$json_decoded['organization']['contactInfo']) && count($json_decoded['organization']['contactInfo']['phoneNumbers']) > 0) {
						$phone_info .= '<br /><i>'._('Phone numbers').'</i><br /><br />' ; 
						foreach ($json_decoded['organization']['contactInfo']['phoneNumbers'] as $key=>$phone) {
							$phone_info .= $phone['label'].' - '.$phone['number'].'<br />' ;
						}
					}
				}
				$final_data = (true === $social_presence_with_icon ? $html : '').$icon_not_present ; 
				$final_data .= $email_info.$address_info.$phone_info ;
				return $final_data ;
			} else {
				return _('No social data found !') ;
			}
		} else {
			return _('No social data found !') ;
		}
	}
	
	/**
	* function to get the websites for the given organization
	* @param integer $id
	* @return array
	*/
	public function get_organization_websites($id) {
		$return_array = array() ;
		$field_names = array();
		if ((int)$id > 0) {
			$qry = "select `field_name` from `fields` where `idmodule` = 6 and `field_type` = 8 and `table_name` = 'organization'";
			$stmt = $this->getDbConnection()->executeQuery($qry);
			if ($stmt->rowCount() > 0) {
				$select = "select ";
				while ($data = $stmt->fetch()) {
					$select .= $data["field_name"].",";
					$field_names[] = $data["field_name"] ;
				}
				$select = rtrim($select,',') ;
				$select .= " from `organization` where `idorganization` = ?" ;
				$stmt = $this->getDbConnection()->executeQuery($select,array($id));
				if ($stmt->rowCount() > 0) {
					while ($data = $stmt->fetch()) {
						foreach ($field_names as $fieldname) {
							if (strlen($data[$fieldname]) > 3) {
								$return_array[] = $data[$fieldname];
							}
						}
					}
				}
			}
		}
		return $return_array ;
	}
	
}