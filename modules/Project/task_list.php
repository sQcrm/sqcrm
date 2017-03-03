<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* task listing
* @author Abhik Chakraborty
*/  
require THIRD_PARTY_LIB_PATH.'/paginator/vendor/autoload.php';
use JasonGrimes\Paginator;
$search_on = false;
$signed_in_user = $_SESSION["do_user"]->iduser;
$itemsPerPage = LIST_VIEW_PAGE_LENGTH;
$currentPage = (isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1);
$status = (isset($_REQUEST['status']) ? (int)$_REQUEST['status'] : 1);
$labels = (isset($_REQUEST['labels']) ? $_REQUEST['labels'] : []);
$assignee = (isset($_REQUEST['assignee']) ? $_REQUEST['assignee'] : []);
$priority = (isset($_REQUEST['priority']) ? $_REQUEST['priority'] : []);
$search_param = [];
$search_param['status'] = $status;

if ($status != 1) $search_on = true;

$labels_query_string = (count($labels) > 0 ? http_build_query(array('labels'=>$labels)) : '');
$assignee_query_string = (count($assignee) > 0 ? http_build_query(array('assignee'=>$assignee)) : '');
$priority_query_string = (count($priority) > 0 ? http_build_query(array('priority'=>$priority)) : '');

$do_project = new Project();
$do_project->getId($sqcrm_record_id);
$do_task = new Tasks();
$project_name = $do_project->project_name;
$task_labels_selector = $do_task->get_task_labels($sqcrm_record_id);
$project_members = $do_project->get_project_members($do_project);
$task_priorities = $do_task->get_task_priority();

$project_members_selector = [];
$task_priorities_selector = [];

foreach ($project_members['assigned_to'] as $key=>$val) {
	$project_members_selector[] = [
		'id'=>$key,
		'name'=>$val['firstname'].' '.$val['lastname']
	];
}

if (count($project_members['other_assignee']) > 0) {
	foreach ($project_members['other_assignee'] as $key=>$val) {
		$project_members_selector[] = [
			'id'=>$key,
			'name'=>$val['firstname'].' '.$val['lastname']
		];
	}
}

foreach ($task_priorities as $key=>$val) {
	$task_priorities_selector[] = [
		'id'=>$val['id'],
		'name'=>$val['priority']
	];
}

$lables_searched_on = [];
$assignee_searched_on = [];
$priority_searched_on = [];

if (count($labels) > 0) {
	$search_on = true;
	foreach ($labels as $key=>$val) {
		$search_param['labels'][] = $val;
		$lables_searched_on[] = $val;
	}
}

if (count($assignee) > 0) {
	$search_on = true;
	foreach ($assignee as $key=>$val) {
		$search_param['assignee'][] = $val;
		$assignee_searched_on[] = $val;
	}
}

if (count($priority) > 0) {
	$search_on = true;
	foreach ($priority as $key=>$val) {
		$search_param['priority'][] = $val;
		$priority_searched_on[] = $val;
	}
}

$records = [];
$totalItems = 0;
$response = $do_task->get_tasks($sqcrm_record_id, $search_param);

if ($response['recordCount'] > 0) {
	$totalItems = $response['recordCount'];
	foreach ($response['data'] as $k=>$data) {
		$priority_dis = $do_task->render_task_priority_display($data['priority'], $data['task_priority'],12);
		$labels_dis = '';
		$assignee_dis = '';
		$title_dis = '';
		
		if ($data['task_status'] == 1) {
			$title_dis .= '<a style="font-size:14px;text-decoration:none;" href="/modules/Project/'.$sqcrm_record_id.'/task/'.$data['idtasks'].'"><strong>'.$data['task_title'].'</strong></a>';
		} else {
			$title_dis .= '<a style="font-size:14px;text-decoration:none;" href="/modules/Project/'.$sqcrm_record_id.'/task/'.$data['idtasks'].'"><s><strong>'.$data['task_title'].'</strong></s></a>';
		}
		$title_dis .= '<br />';
		$title_dis .= '<span style="color :#777777;">#'.$data['idtasks'].'&nbsp;';
		$title_dis .= _('created by ').'<a href="#" style="color :#777777;" onclick="return false;">'.$data['firstname'].' '.$data['lastname'].'</a>&nbsp;';
		$title_dis .= _('on ').i18nDate::i18n_long_date($data['date_created'],true).'</span>';
		
		if ($data['task_labels'] != '') {
			$labels_arr = explode(',',$data['task_labels']);
			if (count($labels_arr) > 0) {
				foreach ($task_labels_selector as $key=>$val) {
					if (in_array($val['id'], $labels_arr)) {
						$labels_dis .= '<span class="label" style="background-color:grey;font-size: 12px;"">'.$val['name'].'</span>&nbsp;';
					}
				}
			}
		}
		
		if ($data['task_assignees'] != '') {
			$assignee_arr = explode(',', $data['task_assignees']);
			if (count($assignee_arr) > 0) {
				foreach ($project_members['assigned_to'] as $key=>$val) {
					if (in_array($key, $assignee_arr)) {
						if ($val['user_avatar'] != '') {
							$avatar = FieldType12::get_file_name_with_path($val['user_avatar'],'s');
						} else {
							$avatar = '/themes/images/blank_avatar.jpg';
						}
						$assignee_dis .= '<img width="20px;" height="20px;" src="'.$avatar.'" title="'.$val['firstname'].' '.$val['lastname'].'('.$val['user_name'].')">&nbsp;';
					}
				}
				
				if (count($project_members['other_assignee']) > 0) {
					foreach ($project_members['other_assignee'] as $key=>$val) {
						if (in_array($key, $assignee_arr)) {
							if ($val['user_avatar'] != '') {
								$avatar = FieldType12::get_file_name_with_path($val['user_avatar'],'s');
							} else {
								$avatar = '/themes/images/blank_avatar.jpg';
							}
							$assignee_dis .= '<img width="20px;" height="20px;" src="'.$avatar.'" title="'.$val['firstname'].' '.$val['lastname'].'('.$val['user_name'].')">&nbsp;';
						}
					}
				}
			}
		}
		
		$records[] = [
			'<input class="sel_record" name="chk[]" value="'.$data['idtasks'].'" type="checkbox">',
			$title_dis,
			$labels_dis,
			$priority_dis,
			$assignee_dis,
			FieldType9::display_value($data['due_date'])
		];
	}
}

$urlPattern = '/modules/Project/'.$sqcrm_record_id.'/task/list?page=(:num)&status='.$status;
$urlPattern .= ($labels_query_string != '' ? '&'.$labels_query_string : '');
$urlPattern .= ($assignee_query_string != '' ? '&'.$assignee_query_string : '');
$urlPattern .= ($priority_query_string != '' ? '&'.$priority_query_string : '');

$paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
include_once('view/task_list_view.php');
?>