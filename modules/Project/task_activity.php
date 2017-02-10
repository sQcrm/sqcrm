<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* task activity
* @author Abhik Chakraborty
*/  

if (isset($_REQUEST['ajaxreq']) && $_REQUEST['ajaxreq'] == true) {
	$idtask = (int)$_REQUEST['idtasks'];
	if ($idtask == 0) echo '0';
	if ((int)$sqcrm_record_id == 0) echo '0';
	
	$do_task = new Tasks();
	if (isset($_REQUEST['start_after']) && (int)$_REQUEST['start_after'] > 0) {
		$do_task->get_task_activity($idtask,$_REQUEST['start_after']);
	} else {
		$do_task->get_task_activity($idtask);
	}
	
	if ($do_task->getNumRows() > 0) {
		$activity_data = array();
		while ($do_task->next()) {
			$data = array();
			$avatar = '';
			if ($do_task->activity_type == 1) {
				if ($do_task->user_avatar == '') {
					$avatar = '/themes/images/blank_avatar.jpg';
				} else {
					$avatar = FieldType12::get_file_name_with_path($do_task->user_avatar,'m');
				}
			}
			
			$data['id'] = $do_task->idtask_activity;
			$data['date_added'] = i18nDate::i18n_long_date($do_task->date_added,true);
			$allow_note_edit = ($do_task->allow_note_edit == 1 || $do_task->iduser == $_SESSION["do_user"]->iduser ? 1 : 0);
			$data['allow_note_edit'] = $allow_note_edit;
			$data['firstname'] = $do_task->firstname;
			$data['lastname'] = $do_task->lastname;
			$data['activity_type'] = $do_task->activity_type;
			$data['user_avatar'] = $avatar;
			$desc = '';
			
			switch ($do_task->activity_type) {
				case 1:
					$data['description'] = FieldType200::display_value($do_task->description, false);
					$do_files_and_attachment = new CRMFilesAndAttachments();
					$do_files_and_attachment->get_uploaded_files($do_task->get_sub_module_id(),$do_task->idtask_activity);
					$note_documents = '';
					if ($do_files_and_attachment->getNumRows() > 0) {
						$e_download = new Event("CRMFilesAndAttachments->eventDownloadFiles");
						$e_download->setEventControler("/eventcontroler.php");
						$note_documents = '<p>';
						while ($do_files_and_attachment->next()) {
							$e_download->addParam("fileid",$do_files_and_attachment->idfile_uploads);
							$download_link = $e_download->getLink($do_files_and_attachment->file_description);
							$note_documents .= '<div id="'.$do_task->idtask_activity.'" class="note-file-'.$do_files_and_attachment->idfile_uploads.'">';
							$note_documents .= '<span>'.$download_link.'</span>';
							if ($allow_note_edit == 1) {
								$note_documents .= '<span class="notes_content_right"><a href="#" title="'._('delete file').'" class="task-note-file-delete" id="'.$do_files_and_attachment->idfile_uploads.'"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a>';
							}
							$note_documents .= '</div>';
						}
						$note_documents .='</p>';
					}
					$data['files'] = $note_documents;
					break;
				
				case 2:
					$labels = explode(',',$do_task->description);
					if (count($labels) > 0) {
						$desc = '<a href="#" onclick="return false;"><strong>'.$do_task->firstname.' '.$do_task->lastname.'</strong></a>'._(' has added the label(s) ');
						foreach ($labels as $val) {
							$desc .= '<span class="label" style="background-color:grey;">'.$val.'</span>&nbsp;';
						}
					}
					$data['description'] = $desc;
					break;
				
				case 3: 
					$desc = '<a href="#" onclick="return false;"><strong>'.$do_task->firstname. ' '.$do_task->lastname.'</strong></a>'._(' has set the due date ');
					$desc .= '<span class="notes_date_added">'.FieldType9::display_value($do_task->description).'</span>';
					$data['description'] = $desc;
					break;
					
				case 4:
					$qry = "select * from `user` where `iduser` in (".$do_task->description.")";
					$stmt = $GLOBALS['conn']->executeQuery($qry);
					if ($stmt->rowCount() > 0) {
						$desc = '<a href="#" onclick="return false;"><strong>'.$do_task->firstname. ' '.$do_task->lastname.'</strong></a>'._(' has assigned ');
						while ($user = $stmt->fetch()) {
							$desc .= '<a href="#" onclick="return false;">'.$user['firstname'].' '.$user['lastname'].'</a>';
							$desc .= ' ';
						}
					}
					$data['description'] = $desc;
					break;
					
				case 5:
					$labels = explode(',',$do_task->description);
					if (count($labels) > 0) {
						$desc = '<a href="#" onclick="return false;"><strong>'.$do_task->firstname.' '.$do_task->lastname.'</strong></a>'._(' has removed the label(s) ');
						foreach ($labels as $val) {
							$desc .= '<span class="label" style="background-color:grey;">'.$val.'</span>&nbsp;';
						}
					}
					$data['description'] = $desc;
					break;
					
				case 6:
					$qry = "select * from `user` where `iduser` in (".$do_task->description.")";
					$stmt = $GLOBALS['conn']->executeQuery($qry);
					if ($stmt->rowCount() > 0) {
						$desc = '<a href="#" onclick="return false;"><strong>'.$do_task->firstname. ' '.$do_task->lastname.'</strong></a>'._(' has unassigned ');
						while ($user = $stmt->fetch()) {
							$desc .= '<a href="#" onclick="return false;">'.$user['firstname'].' '.$user['lastname'].'</a>';
							$desc .= ' ';
						}
					}
					$data['description'] = $desc;
					break;
					
				case 7:
					$desc = '<a href="#" onclick="return false;"><strong>'.$do_task->firstname. ' '.$do_task->lastname.'</strong></a>'._(' has changed the title to ');
					$desc .= '<span class="label label-info">'.$do_task->description.'</span>';
					$data['description'] = $desc;
					break;
					
				case 8: 
					$qry = "select * from task_priority where idtask_priority = ?";
					$stmt = $GLOBALS['conn']->executeQuery($qry, array($do_task->description));
					$priority = $stmt->fetch();
					$desc = '<a href="#" onclick="return false;"><strong>'.$do_task->firstname. ' '.$do_task->lastname.'</strong></a>'._(' has changed the priority to ');
					$desc .= $do_task->render_task_priority_display($priority['idtask_priority'],$priority['priority']);
					$data['description'] = $desc;
					break;
					
				case 9: 
					$text = ' ';
					$text .= _('has ');
					if ($do_task->description == 1) {
						$text .= '<span class="label label-danger" style="font-size: 16px;"><i class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="vertical-align:middle"></i> '._('closed').'</span></span>';
					} else {
						$text .= '<span class="label label-info" style="font-size: 16px;">'._('re-opened').'</span>';
					}
					$desc = '<a href="#" onclick="return false;"><strong>'.$do_task->firstname. ' '.$do_task->lastname.'</strong></a>'.$text._(' the task');
					$data['description'] = $desc;
					break;
			}
			
			$activity_data[] = $data;
		}
		echo json_encode($activity_data);
	} else {
		echo '0';
	}
} 
?>