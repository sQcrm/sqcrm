<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMNotes
* @author Abhik Chakraborty
*/
	
class Notes extends DataObject {
	public $table = "notes";
	public $primary_key = "idnotes";

	public $sql_start = 0 ;
	public $sql_max = 20 ;
	public $module_group_rel_table = '';
	
	
	public function __construct() {
		$this->sql_max = LIST_VIEW_PAGE_LENGTH ;
	}
    
	/**
	* event function to add notes
	* @param object $evctl
	* @see view/detail_view_notes.php
	*/
	function eventAddNotes(EventControler $evctl) {
		$permission = $_SESSION["do_crm_action_permission"]->action_permitted('add',8) ;
		if (true === $permission) {
			$add_note = false ;
			$error = '';
			if ((int)$evctl->idmodule > 0  && (int)$evctl->sqrecord > 0) {
				if (trim($evctl->entity_notes) != '') {
					$add_note = true ;
					$this->addNew();
					$this->notes = CommonUtils::purify_input($evctl->entity_notes);
					$this->notes = $evctl->entity_notes;
					$this->sqcrm_record_id = (int)$evctl->sqrecord ;
					$this->related_module_id = (int)$evctl->idmodule ;
					$this->date_added = date("Y-m-d H:i:s");
					$this->iduser = $_SESSION["do_user"]->iduser ;
					$this->add();
					$idnotes = $this->getInsertId() ;
					$files_count = count($_FILES["note_files"]["name"]);
					if ($files_count > 0) {
						for ($i=0;$i<$files_count;$i++) {
							$this->upload_and_save_notes_files( 
								$_FILES["note_files"]["name"][$i],
								$_FILES["note_files"]["tmp_name"][$i],
								$_FILES["note_files"]["type"][$i],
								$_FILES["note_files"]["size"][$i],
								$idnotes
							);
						}
					}
					
					// send nudge email 
					$this->send_note_nudge_email($idnotes,$evctl->entity_notes,(int)$evctl->idmodule,(int)$evctl->sqrecord);
					$this->get_note_by_id($idnotes);
					$this->next();
					$do_user = new User() ;
					$active_users = $do_user->get_active_users() ;
					$this->display_note($this,$active_users);
				} else {
					echo '1' ;
				}
			} else {
				echo '2' ;
			}
			/*if ($add_note === true) {
				echo 1;
			} else {
				$error_html = '';
				$error_html .= '<div class="alert alert-error sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
				$error_html .= $error;
				$error_html .= '</div>';
				echo $error_html ;
			}*/
		}
	}
  
	/**
	* event function to load notes
	* @param object $evctl
	* @see view/detail_view_notes.php
	*/
	function eventAjaxLoadNotes(EventControler $evctl) {
		if($evctl->sql_start != '' && $evctl->sql_max != '') {
			$start = (int)$evctl->sql_start;
			$max = (int)$evctl->sql_max;
		} else {
			$start = $this->sql_start;
			$max = $this->sql_max;
		}
		$this->get_notes((int)$evctl->sqrecord,(int)$evctl->idmodule,$start,$max);
		if ($this->getNumRows() > 0) {
			$do_user = new User() ;
			$active_users = $do_user->get_active_users() ;
			while ($this->next()) {
				$this->display_note($this,$active_users);
			}
		} else {
			return 0 ;
		}
	}
  
	/**
	* function to get notes
	* @param integer $idreferrer
	* @param integer $idmodule
	* @param integer $start
	* @param integer $max
	*/
	public function get_notes($idreferrer,$idmodule,$start=0,$max=0) {
		$security_where = '';
		$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition($this->getTable(),8);
		$qry  = "
		select notes.*,
		user.firstname, 
		user.lastname , 
		file_uploads.file_extension,
		user.user_avatar 
		from notes
		inner join user on user.iduser = notes.iduser
		left join file_uploads on user.user_avatar = file_uploads.file_name
		where 
		notes.sqcrm_record_id = ?
		AND notes.related_module_id = ?
		$security_where
		order by notes.starred desc,notes.idnotes desc
		";
		if ($start == 0 && $max == 0) {
			$start = $this->sql_start ;
			$max = $this->sql_max ;
		}
		//$qry .= " limit ".(int)$start.",".(int)$max ;
		$this->query($qry,array($idreferrer,$idmodule));
	}
	
	public function get_note_by_id($idnotes) {
		$qry  = "
		select notes.*,
		user.firstname, 
		user.lastname , 
		file_uploads.file_extension,
		user.user_avatar 
		from notes
		inner join user on user.iduser = notes.iduser
		left join file_uploads on user.user_avatar = file_uploads.file_name
		where 
		notes.idnotes = ?
		";
		$this->query($qry,array($idnotes));
	}
  
	function eventAjaxLoadNoteById(EventControler $evctl) {
		if ((int)$evctl->idnotes > 0 ) {
			$this->get_note_by_id((int)$evctl->idnotes) ;
			$do_user = new User() ;
			$active_users = $do_user->get_active_users() ;
			$this->display_note($this,$active_users);
		}
	}
  
	/**
	* function to display each note
	* @param object $obj
	*/
	function display_note($obj,$active_users) {
		$note_documents = '';
		$avatar_path = $GLOBALS['AVATAR_DISPLAY_PATH'] ;
		if ($obj->user_avatar != '') {
			$thumb = '<img src="'.$avatar_path.'/ths_'.$obj->user_avatar.'.'.$obj->file_extension.'" style="width:20px;height:20px;" />';
		} else {
			$thumb = '<span class="add-on"><i class="icon-user"></i></span>';
		}
		$note_content = $obj->notes;
		/*if (strlen($note_content) > 200 ) {
			$note_content = substr($note_content, 0, 200);
			$note_content .= '&nbsp;<a href="#" onclick="view_more_notes(\''.$obj->idnotes.'\'); return false;">more...</a>';
		}*/
		$note_content = CommonUtils::format_display_text($note_content);
		$note_content = FieldType200::display_value($note_content);
		$do_files_and_attachment = new CRMFilesAndAttachments();
		$do_files_and_attachment->get_uploaded_files(8,$obj->idnotes);
		if ($do_files_and_attachment->getNumRows() > 0) {
			$e_download = new Event("CRMFilesAndAttachments->eventDownloadFiles");
			$e_download->setEventControler("/eventcontroler.php");
			$note_documents = '<p>';
			while ($do_files_and_attachment->next()) {
				$e_download->addParam("fileid",$do_files_and_attachment->idfile_uploads);
				$download_link = $e_download->getLink($do_files_and_attachment->file_description);
				$note_documents .= $download_link.'<br />';
			}
			$note_documents .='</p>';
		}
		$date_added = i18nDate::i18n_long_date(TimeZoneUtil::convert_to_user_timezone($obj->date_added,true),true);
		$html = <<<html
		<div class="notes_content" id="note{$obj->idnotes}">{$thumb}
			<strong>{$obj->firstname} {$obj->lastname}</strong>
			<span class="notes_content_right" style="display:none;">
				<a href="#" onclick="display_edit_notes('{$obj->idnotes}'); return false;">edit</a> | 
				<a href="#" onclick="delete_notes('{$obj->idnotes}'); return false;">delete</a>
			</span>
			<p id="content_{$obj->idnotes}">
				{$note_content}
			</p>
			{$note_documents}
			<p id="content_hidden_{$obj->idnotes}" style="display:none;"></p>
			<a href="#note-content-{$obj->idnotes}" onclick="return false ;" id="note-content-{$obj->idnotes}"><span class="notes_date_added">{$date_added}</span></a>
      </div>
      <hr class="form_hr">
html;
		$do_files_and_attachment->free();
		echo $html;
	}
  
	/**
	* event function to load full note content
	* @param object $evctl
	* @see view/detail_view_notes
	*/
	function eventAjaxLoadFullNote(EventControler $evctl) {
		if ((int)$evctl->idnotes > 0) {
			$this->getId((int)$evctl->idnotes);
			$notes_content = CommonUtils::format_display_text($this->notes);
			$notes_content .= FieldType200::display_value($notes_content);
		}
		$html = <<<html
			{$notes_content}
html;
		echo $html;
	}
  
	/**
	* function to upload and save the notes documents
	* @param string $name
	* @param string $tempname
	* @param string $type
	* @param $size
	* @param $sqrecord
	* @see class/core/CRMFilesAndAttachments.class.php
	* @see class/fields/fieltype/FieldType21.class.php
	*/
	public function upload_and_save_notes_files($name,$tempname,$type,$size,$sqrecord) {
		$field_value = FieldType21::upload_file($tempname,$name);
		$do_files_and_attachment = new CRMFilesAndAttachments();
		$do_files_and_attachment->addNew();
		$do_files_and_attachment->file_name = $field_value["name"];
		$do_files_and_attachment->file_mime = $type;
		$do_files_and_attachment->file_size = $size;
		$do_files_and_attachment->file_extension = $field_value["extension"];
		$do_files_and_attachment->idmodule = 8;
		$do_files_and_attachment->id_referrer = $sqrecord;
		$do_files_and_attachment->iduser = $_SESSION["do_user"]->iduser ;
		$do_files_and_attachment->file_description = $name;
		$do_files_and_attachment->date_modified = date("Y-m-d H:i:s");
		$do_files_and_attachment->add();
		$do_files_and_attachment->free();
	}
  
	/**
	* event function to display the note edit form
	* @param object $evctl
	* @see class/fields/fieltype/FieldType20.class.php
	*/
	function eventAjaxDisplayUpdateNoteField(EventControler $evctl) { 
		if ((int)$evctl->idnotes > 0) {
			if ($_SESSION["do_crm_action_permission"]->action_permitted('edit',8,(int)$evctl->idnotes) === true) { 
				$this->getId((int)$evctl->idnotes);
				$notes = $this->notes ;
				$html = FieldType200::display_field('entity_notes_edit_'.$this->idnotes,$notes,'expand_text_area');
				$html .= '<br /><input type="button" onclick="edit_notes(\''.$this->idnotes.'\')" class="btn btn-primary" value="'._('update').'"/>';
				$html .= '&nbsp;<input type="button" onclick="close_edit_notes(\''.$this->idnotes.'\')" class="btn btn-inverse" value="'._('cancel').'"/>';
				echo $html;
			} else { 
				echo '0';
			}
		}
	}
  
	/**
	* event function to update the notes
	* @param object $evctl
	*/ 
	function eventAjaxUpdateNotes(EventControler $evctl) {
		if ((int)$evctl->idnotes > 0 && $_SESSION["do_crm_action_permission"]->action_permitted('edit',8,(int)$evctl->idnotes) === true) {
			$notes = CommonUtils::purify_input($evctl->notes_edit_data);
			$this->cleanValues();
			$this->notes = $notes;
			$this->update((int)$evctl->idnotes);
			/*if (strlen($notes) > 200) {
				$notes = substr($notes, 0, 200);
				$notes .= '&nbsp;<a href="#" onclick="view_more_notes(\''.$this->idnotes.'\'); return false;">more...</a>';
			}*/
			$notes = CommonUtils::format_display_text($notes);
			echo FieldType200::display_value($notes);
		}
	}   
  
	/**
	* event function to delete the notes
	* @param object $evctl
	* first it will delete the note and then check if there are some document attached with it.
	* if documents are attached then delete from the database and then from physical location
	* @see class/core/CRMFilesAndAttachments.class.php
	* @see class/fields/fieldtypes/FieldType21.class.php
	*/
	function eventAjaxDeleteNotes(EventControler $evctl) {
		if ((int)$evctl->idnotes > 0) {
			if ($_SESSION["do_crm_action_permission"]->action_permitted('delete',8,(int)$evctl->idnotes) === true) {
				$qry = "
				delete from `".$this->getTable()."`
				where 
				`idnotes` = ?
				" ;
				$this->query($qry,array((int)$evctl->idnotes));
				$do_files_and_attachment = new CRMFilesAndAttachments();
				$do_files_and_attachment->get_uploaded_files(8,(int)$evctl->idnotes);
				if ($do_files_and_attachment->getNumRows() > 0) {
					while ($do_files_and_attachment->next()) {
						$file_name = $do_files_and_attachment->file_name;
						$file_extension = $do_files_and_attachment->file_extension;
						$do_files_and_attachment->delete_record($do_files_and_attachment->idfile_uploads);
						FieldType21::remove_file($file_name,$file_extension);
					}
				}
				echo 1;
			} else { echo 0 ; }
		}
	}
	
	/**
	* function to send nudge email on a note
	* @param integer $idnotes
	* @param string $note_content
	* @param integer $related_module_id
	* @param integer $sqcrm_record_id
	*/
	public function send_note_nudge_email($idnotes,$note_content,$related_module_id,$sqcrm_record_id) {
		if ((int)$idnotes > 0) {
			$mentioned_email_receiptents  = array() ;
			preg_match_all("/(^|[^@\w])@(\w{1,15})\b/im", $note_content, $mentioned_users);
			if (is_array($mentioned_users) && array_key_exists(2,$mentioned_users) && count($mentioned_users[2]) >0) {
				$do_user = new User() ;
				$active_users = $do_user->get_active_users() ;
				$current_user = $_SESSION["do_user"]->iduser ;
				$active_users_key_as_username = array() ;
				foreach ($active_users as $key=>$users) {
					if ($users["iduser"] == $current_user) continue ;
					$active_users_key_as_username[$users["user_name"]] = array(
						"firstname"=>$users["firstname"],
						"lastname"=>$users["lastname"],
						"email"=>$users["email"]
					) ;
				}
				foreach ($mentioned_users[2] as $key=>$val) {
					if (array_key_exists($val,$active_users_key_as_username)) {
						$mentioned_email_receiptents[] = $active_users_key_as_username[$val] ;
					}
				}
			}
			if (is_array($mentioned_email_receiptents) && count($mentioned_email_receiptents) > 0) {
				$email_template = new EmailTemplate("send_notes_user_mentioned_email") ;
				$emailer = new SQEmailer();
				$do_module = new Module() ;
				$do_module->getId($related_module_id) ;
				$entity_url = NavigationControl::getNavigationLink($do_module->name,'detail',$sqcrm_record_id,'#note'.$idnotes) ;
				$view_url = '<a href="'.SITE_URL.$entity_url.'">'._('view on sQcrm').'</a>';
				$note_content = FieldType200::display_value($note_content);
				$note_content = str_replace('/themes/images/emoji-pngs',SITE_URL.'/themes/images/emoji-pngs',$note_content);
				foreach ($mentioned_email_receiptents as $key=>$mentioned) {
					$to_email = $mentioned["email"] ;
					$email_data = array(
						"notes_content" => $note_content ,
						"firstname" => $mentioned["firstname"] ,
						"lastname" => $mentioned["lastname"] ,
						"view_url" => $view_url,
						"module_name" => CommonUtils::get_module_name_as_text($related_module_id),
						"user_name" =>$_SESSION["do_user"]->user_name
					);
					$emailer->IsSendmail();
					$emailer->setEmailTemplate($email_template);
					$emailer->mergeArray($email_data);
					$emailer->AddAddress($to_email, $mentioned["firstname"].' '.$mentioned["lastname"]);
					$emailer->send() ;
				}
			}
		}
	}
}