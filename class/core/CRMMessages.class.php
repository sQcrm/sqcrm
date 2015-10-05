<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMMessames, controlls the different messages (error,warning etc in the CRM)
* @author Abhik Chakraborty
*/
	

class CRMMessages extends DataObject {
	public $table = "";
	public $primary_key = "";

	protected $message_type = '';
	protected $message_content = '';
	protected $message_stack = array();
  
	/**
	* function to set the message type
	* @param string $type
	*/
	public function set_message_type($type) {
		$this->message_type = $type ;
	}
	
	/**
	* function to get the message type
	* @return $message_type
	*/
	public function get_message_type() {
		return $this->message_type ;
	}

	/**
	* Function to set the message content
	* @param string $content
	*/
	public function set_message_content($content) {
		$this->message_content = $content ;
	}

	/**
	* function to get the message content
	* @return $message_content
	*/
	public function get_message_content() {
		return $this->message_content ;
	}

	/**
	* function to push the message in a stack
	* useful when multiple messages and warnings are generated from single operation
	*/
	public function push_to_message_stack() {
		$this->message_stack[] = array($this->get_message_type()=>$this->get_message_content());
	}

	/**
	* function to get the message stack
	* @return $message_stack
	*/
	public function get_message_stack() {
		return $this->message_stack ;
	}

	/**
	* function to set the message
	* @param string $type
	* @param string $content
	*/
	public function set_message($type,$content) {
		$this->set_message_type($type);
		$this->set_message_content($content);
		$this->push_to_message_stack();
	}
	
	/**
	* function to get messages
	* @param boolean $close
	* @param boolean $top
	* Useful when multiple messages/warnings etc needs to be displayed
	*/
	public function get_messages($close=false,$top=true) {
		if (is_array($this->get_message_stack() ) && count($this->get_message_stack())> 0 ) {
			foreach ($this->get_message_stack() as $key=>$val) {
				foreach ($val as $type=>$content) {
					echo $this->display_message($type,$content,$close,$top);
				}
			}
		}
	}

	/**
	* function to display the message
	* @param string $type
	* @param string $content
	* @param boolean $close
	* @param boolean $auto_close
	*/
	public function display_message($type,$content,$close=false,$top=true,$auto_close=true) {
		$html = '';
		$top_message_css = '';
		$div_id = '';
		if ($top === true) {
			$top_message_css = 'sqcrm-top-message' ;
		}
		if ($auto_close === true) {
			$div_id = 'sqcrm_auto_close_messages';
		}
		switch ($type) {
			case 'error':
				if (strlen($content) > 0) {
					$html = '<div class="alert alert-error '.$top_message_css.'" id="'.$div_id.'">';
					if ($close === true) $html .= '<a href="#" class="close" data-dismiss="alert">&times;</a>';
					$html .= '<strong>'.$content.'</strong>';
					$html .= '</div>';
				}
			break;

			case 'warning':
				if (strlen($content) > 0) {
					$html = '<div class="alert '.$top_message_css.'" id="'.$div_id.'">';
					if ($close === true) $html .= '<a href="#" class="close" data-dismiss="alert">&times;</a>';
					$html .= '<strong>'.$content.'</strong>';
					$html .= '</div>';
				}
			break;

			case 'success':
				if (strlen($content) > 0) {
					$html = '<div class="alert alert-success '.$top_message_css.'" id="'.$div_id.'">';
					if ($close === true) $html .= '<a href="#" class="close" data-dismiss="alert">&times;</a>';
					$html .= '<strong>'.$content.'</strong>';
					$html .= '</div>';
				}
			break;
		
			case 'info':
				if (strlen($content) > 0 ) {
					$html = '<div class="alert alert-info '.$top_message_css.'" id="'.$div_id.'">';
					if ($close === true) $html .= '<a href="#" class="close" data-dismiss="alert">&times;</a>';
					$html .= '<strong>'.$content.'</strong>';
					$html .= '</div>';
				}
			break;
		}
		return $html;
	}

	/**
	* function to clean the message stack, type and content
	* @see includes/pagetop.inc.php
	*/
	public function errase_message() {
		unset($this->message_stack);
		$this->set_message_type('');
		$this->set_message_content('');
	}
}
