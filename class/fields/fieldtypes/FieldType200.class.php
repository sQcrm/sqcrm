<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType200
* Field Type 20 : Text Area Expanding along with emoji and @mention feature
* @author Abhik Chakraborty
*/

class FieldType200 extends CRMFields {
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
		return _('Expanding Text Area along with emoji and @mention feature') ;
	}
     
	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$css = '') {
		$current_user = $_SESSION["do_user"]->iduser ;
		$do_user = new User() ;
		$active_users = $do_user->get_active_users() ;
		$active_users_but_me = array() ;
		foreach ($active_users as $key=>$users) {
			if ($users["iduser"] == $current_user) continue ;
			$active_users_but_me[] = $users["user_name"].'('.$users["firstname"].' '.$users["lastname"].')' ;
		}
		$mention_users_json = json_encode($active_users_but_me) ;
		echo '<textarea class="'.$css.'" name="'.$name.'" id="'.$name.'" rows="7">'.$value.'</textarea>';
		echo 
		"\n".'<script>
			$("#'.$name.'").autogrow({onInitialize: true});
			$(document).ready(function() { 
				var mentionUsers = \''.$mention_users_json.'\' ;
				$(\'#'.$name.'\').textcomplete([
					{ // emoji strategy
						match: /\B:([\-+\w]*)$/,
						search: function (term, callback) {
							callback($.map(emojies, function (emoji) {
								return emoji.indexOf(term) === 0 ? emoji : null;
							}));
						},
						template: function (value) {
							return \'<img width="20" height="20" src="/themes/images/emoji-pngs/\' + value + \'.png"></img>\' + value;
						},
						replace: function (value) {
							return \':\' + value + \': \';
						},
						index: 1
					},
					{ // mentions strategy
						mentions : $.parseJSON(mentionUsers),
						match: /\B@(\w*)$/,
						search: function (term, callback) {
							callback($.map(this.mentions, function (mention) {
								return mention.indexOf(term) === 0 ? mention : null;
							}));
						},
						index: 1,
						replace: function (mention) {
							var mentionedUserName = mention.split(\'(\') ;
							return \'@\' + mentionedUserName[0] + \' \';
						}
					}
				]); 
			});
			
		</script>'."\n";
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	*/
	public static function display_value($value) {
		$return_val =  nl2br($value) ;
		$return_val = preg_replace_callback(
			'/:(.*?):+/',
			function ($matches) {
				if (file_exists(BASE_PATH.'/themes/images/emoji-pngs/'.$matches[1].'.png')) {
					return '<img style="vertical-align: middle;" width="20" height="20" src="/themes/images/emoji-pngs/'.trim($matches[1]).'.png"></img>';
				}
			},
			$return_val
		);
		
		$return_val = preg_replace_callback(
			'/(^|[^@\w])@(\w{1,15})\b/im',
			function ($matches) {
				return ' <a href="#" onclick="return false ;">'.'@'.$matches[2].'</a>' ;
			},
			$return_val
		);
		
		return $return_val ;
	}
}
