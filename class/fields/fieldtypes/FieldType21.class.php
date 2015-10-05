<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType21
* Field Type 21 : Multiple file uploader
* @author Abhik Chakraborty
*/

class FieldType21 extends CRMFields {
	public $table = "fields";
	public $primary_key = "idfields";
    
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
		return _('Multiple File Uploader') ;
	}
     
	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	* 
	*/
	public static function display_field($name,$value = '',$css = '') { 
		$add_more = _('add more');
		$remove_input = _('remove');
		$html = '';
		echo <<<html
		<span id= "mul_file_{$name}_1">
			<input type="file" class="{$css}" name="{$name}[]" id="{$name}">
		</span>
		<div id="more_mul_files_{$name}"></div>
		<a href="#" id="add_mul_files_{$name}">{$add_more}</a>
		<script>
			var num_of_uploader = 1;
			$("#add_mul_files_{$name}").click( function() {
				num_of_uploader++;
				var input_file = '<input type="file" name="{$name}[]" id="{$name}" class="{$class}">';
				var remove_input_file = '<a style="margin-left:100px;margin-top:5px;" href="#" onclick="remove_file_input_{$name}(\''+num_of_uploader+'\',\'{$name}\')">{$remove_input}</a>';
				var new_file_uploader = '<span id="mul_file_{$name}_'+num_of_uploader+'" class="more_file_inputs">'+input_file+remove_input_file+'<br /></span>';
				$("#more_mul_files_{$name}").append(new_file_uploader);
			});
			
			function remove_file_input_{$name}(input_file_number,fld_name) {
				var span_id = 'mul_file_'+fld_name+'_'+input_file_number ;
				$("#"+span_id).html('');
				$("#"+span_id).hide();
			}
		</script>
html;
  }
  
	/**
	* function to upload file
	* @param string $tmpname
	* @param string $filename
	* @return array $return_data
	*/
	public static function upload_file($tmpname,$filename) {
		$upload_path = $GLOBALS['FILE_UPLOAD_PATH'];
		$new_name = str_replace(" ","",microtime());
		$file_ext = end(explode('.',$filename));
		$upload_file_name = $new_name.'.'.$file_ext ;
		move_uploaded_file($tmpname,$upload_path.'/'.$upload_file_name);
		$return_data = array("name"=>$new_name,'extension'=>$file_ext);
		return $return_data;
	}

	/**
	* Function to remove the file from the server
	* @param string $name
	* @param string $extension
	*/
	public static function remove_file($name,$extension) {
		if($name != ''){
		$upload_path = $GLOBALS['FILE_UPLOAD_PATH'];
		$saved_file_name = $name.'.'.$extension;
		if(is_file($upload_path.'/'.$saved_file_name)){
			unlink($upload_path.'/'.$saved_file_name);
		}
		}
	}
}