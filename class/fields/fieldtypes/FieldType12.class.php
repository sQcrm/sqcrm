<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType12
* Field Type 12 : Image Uploader for Avatar
* @author Abhik Chakraborty
*/

class FieldType12 extends CRMFields {
	public $table = "fields";
	public $primary_key = "idfields";
	static private $large_thumb_width = 160;
	static private $large_thumb_height = 160;
	static private $medium_thumb_width = 100 ;
	static private $medium_thumb_height = 100 ;
	static private $small_thumb_width = 50 ;
	static private $small_thumb_height = 50 ;

	/**
	* Constructor function 
	*/
	function __construct(sqlConnect $conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
  
	/**
	* Function to get the field type, like Text Box, Text Area, Checkbox etc
	*/
	public static function get_field_type() {
		return _('Image Uploader') ;
	}
     
	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$dimension='s',$css = '') { 
		if ($value=='') {
			echo '<input type="file" class="'.$css.'" name="'.$name.'" id="'.$name.'">';
		} else {
			echo self::display_value($value,$dimension);
			echo '<input type = "hidden" name = "upd_'.$name.'" id="upd_'.$name.'" value="'.$value.'">';
			echo '<input type="file" class="'.$css.'" name="'.$name.'" id="'.$name.'">';
		}
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	* @param string $dimension
	*/
	public static function display_value($value,$dimension='s') {
		if ($value != '') {
			$avatar_path = $GLOBALS['AVATAR_DISPLAY_PATH'] ;
			$do_files_and_attachment = new CRMFilesAndAttachments();
			$do_files_and_attachment->get_file_details_by_name($value);
			$file_extension = '';
			if ($do_files_and_attachment->getNumRows() > 0 ) {
				$do_files_and_attachment->next();
				$file_extension = $do_files_and_attachment->file_extension ;
			}
			switch ($dimension) {
				case 'l' :
					return '<img src = "'.$avatar_path.'/thl_'.$value.'.'.$file_extension.'">';
					break;
				case 'm' :
					return '<img src = "'.$avatar_path.'/thm_'.$value.'.'.$file_extension.'">';
					break;
				case 's' :
					return '<img src = "'.$avatar_path.'/ths_'.$value.'.'.$file_extension.'">';
					break;
			}
		} else { return ''; }
	}
    
	public static function get_file_name_with_path($value,$dimension='s') {
		if ($value != '') {
			$avatar_path = $GLOBALS['AVATAR_DISPLAY_PATH'] ;
			$do_files_and_attachment = new CRMFilesAndAttachments();
			$do_files_and_attachment->get_file_details_by_name($value);
			$file_extension = '';
			if ($do_files_and_attachment->getNumRows() > 0 ) {
				$do_files_and_attachment->next();
				$file_extension = $do_files_and_attachment->file_extension ;
			}
			switch ($dimension) {
				case 'l' :
					return $avatar_path.'/thl_'.$value.'.'.$file_extension;
					break;
				case 'm' :
					return $avatar_path.'/thm_'.$value.'.'.$file_extension;
					break;
				case 's' :
					return $avatar_path.'/ths_'.$value.'.'.$file_extension;
					break;
			}
		} else { return ''; }
	}

	/**
	* Function to upload the avatar image
	* @param string $tmpname
	* @param string $filename
	*/
	public static function upload_avatar($tmpname,$filename) {
		if ($tmpname == '' || $filename == '') return '';
		$image_data = getimagesize($tmpname);
		$allow_upload = false ;
		switch ($image_data['mime']) {
			case 'image/gif' :
				$allow_upload = true;
				break;
			case 'image/jpeg' :
				$allow_upload = true;
				break;
			case 'image/jpg' :
				$allow_upload = true;
				break;
			case 'image/png' :
				$allow_upload = true;
				break;
			default :
				$allow_upload = false ;
				break;
		}
		if ($allow_upload === true ) {
			$avatar_path = $GLOBALS['AVATAR_PATH'] ;
			$new_name = time();
			$image_ext = end(explode('.',$filename));
			$thumb_large_name = 'thl_'.$new_name.'.'.$image_ext ;
			$thumb_medium_name = 'thm_'.$new_name.'.'.$image_ext ;
			$thumb_small_name = 'ths_'.$new_name.'.'.$image_ext ;
			move_uploaded_file($tmpname,$avatar_path.'/'.$thumb_large_name);
			copy($avatar_path.'/'.$thumb_large_name,$avatar_path.'/'.$thumb_medium_name);
			copy($avatar_path.'/'.$thumb_large_name,$avatar_path.'/'.$thumb_small_name);
			self::crop_uploaded_image($avatar_path.'/'.$thumb_large_name,'l',$image_data['mime']);
			self::crop_uploaded_image($avatar_path.'/'.$thumb_medium_name,'m',$image_data['mime']);
			self::crop_uploaded_image($avatar_path.'/'.$thumb_small_name,'s',$image_data['mime']);
			chmod($avatar_path.'/'.$thumb_large_name,0777);
			chmod($avatar_path.'/'.$thumb_medium_name,0777);
			chmod($avatar_path.'/'.$thumb_small_name,0777);
			$return_data = array("name"=>$new_name,'extension'=>$image_ext,'mime'=>$image_data['mime']) ;
			return $return_data ;
		} else {
			// Throw image upload error
		}
	}
    
	/**
	* Function to crop the image to predefined dimensions to be used in different section of the CRM
	* @param string $image
	* @param string $dimension
	* @param string $image_mime
	*/
	public static function crop_uploaded_image($image,$dimension,$image_mime) {
		if ($dimension == 'l') {
			$max_w = self::$large_thumb_width;
			$max_h = self::$large_thumb_height;
		} elseif ($dimension == 'm') {
			$max_w = self::$medium_thumb_width;
			$max_h = self::$medium_thumb_height;
		} elseif($dimension == 's') {
			$max_w = self::$small_thumb_width;
			$max_h = self::$small_thumb_height;
		}
		list($i_width,$i_height) = getimagesize($image);
		$h_coef = $i_height/$i_width;
		$w_coef = $i_width/$i_height;
		if ($i_width > $max_w ) {
			$new_height = $max_w*$h_coef ;
			$new_width = $max_w ;
		} else {
			$new_width = $i_width ;
			$new_height = $i_height ;
		}
		if ($new_height > $max_h && $new_height > 0) {
			$new_width = $max_h *$w_coef ;
			$new_height = $max_h; 
		}
		if ($image_mime == 'image/jpeg' || $image_mime == 'image/jpg' ) {
			$thumb = imagecreatetruecolor($new_width,$new_height);
			$source = imagecreatefromjpeg($image);
			imagecopyresized($thumb,$source,0,0,0,0,$new_width,$new_height,$i_width,$i_height);
			imagejpeg($thumb,$image);
		} elseif ($image_mime == 'image/gif' ) {
			$thumb = imagecreate($new_width,$new_height);
			$source = imagecreatefromgif($image);
			imagecopyresized($thumb,$source,0,0,0,0,$new_width,$new_height,$i_width,$i_height);
			imagegif($thumb,$image);
		} elseif ($image_mime == 'image/png') {
			$thumb = imagecreatetruecolor($new_width,$new_height);
			$source = imagecreatefrompng($image);
			imagecopyresized($thumb,$source,0,0,0,0,$new_width,$new_height,$i_width,$i_height);
			imagepng($thumb,$image);
		}
	}

	/**
	* Function to remove the file from the server
	* @param string $name
	*/
	public static function remove_thumb($name) {
		if($name != '') {
			$avatar_path = $GLOBALS['AVATAR_PATH'] ;
			$do_files_and_attachment = new CRMFilesAndAttachments();
			$do_files_and_attachment->get_file_details_by_name($name);
			$file_extension = '';
			if ($do_files_and_attachment->getNumRows() > 0 ) {
				$do_files_and_attachment->next();
				$file_extension = $do_files_and_attachment->file_extension ;
				$do_files_and_attachment->delete_record($do_files_and_attachment->idfile_uploads);
				$thumb_large_name = 'thl_'.$name.'.'.$file_extension ;
				$thumb_medium_name = 'thm_'.$name.'.'.$file_extension ;
				$thumb_small_name = 'ths_'.$name.'.'.$file_extension ;
				if (is_file($avatar_path.'/'.$thumb_large_name)) {
					unlink($avatar_path.'/'.$thumb_large_name);
				}
				if (is_file($avatar_path.'/'.$thumb_medium_name)) {
					unlink($avatar_path.'/'.$thumb_medium_name);
				}
				if (is_file($avatar_path.'/'.$thumb_small_name)) {
					unlink($avatar_path.'/'.$thumb_small_name);
				}
			}
		}
	}
}