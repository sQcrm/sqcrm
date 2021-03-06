<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* View modal for the list view of crm module data
* Included in the module/list.php file
* Get the filed information of module for the list view and generate the header for the datatable display
* Sets the fields information in the object member list_view_field_information and sets the object in the persistent session
* @author Abhik Chakraborty
*/

$custom_view_id = 0 ;
if ($_SESSION["do_crm_action_permission"]->action_permitted('view',17)) {
	if (isset($_REQUEST["custom_view_id"]) && (int)$_REQUEST["custom_view_id"] > 0) {
		$custom_view_id = (int)$_REQUEST["custom_view_id"] ;
	} else {
		if (isset($_SESSION[$module]["pinned_list_view"]) && (int)$_SESSION[$module]["pinned_list_view"] > 0 ) {
			$custom_view_id = $_SESSION[$module]["pinned_list_view"] ;
		} else {
			$custom_view_id = $default_custom_view ; // check list.php for the module ex. modules/Leads/list.php
		}
	}
}

?>
<script>
$(document).ready(function() {
	/*
	* function to delete multiple records from the list view page for a module 
	*/
	$('#delete_data').click(function() {
		var sData = oTable.$('input:checkbox').serialize();
		if (sData == '') {
			var err_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
			var err_msg = err_element+'<strong>'+SELECT_ONE_RECORD_BEFORE_DELETE+'</strong></div>';
			$("#message").html(err_msg);
			$("#message").show();
			return false ;
		}
		$("#delete_confirm").modal('show');
		$("#delete_confirm .btn-primary").off('click');
		$("#delete_confirm .btn-primary").click(function() {
			$("#delete_confirm").modal('hide');
			$.ajax({
				type: "POST",
				<?php
				$e_del_mul = new Event("CRMDeleteEntity->eventAjaxDeleteMultipleEntity");
				$e_del_mul->setEventControler("/ajax_evctl.php");
				$e_del_mul->addParam('module',$module);
				$e_del_mul->addParam('referrer','list');
				$e_del_mul->setSecure(false);
				?>
				url: "<?php echo $e_del_mul->getUrl(); ?>&"+sData,
				success:  function(html) {
					ret_data = html.trim();
					if (ret_data == '0') {
						var err_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
						var err_msg = err_element+'<strong>'+UNAUTHORIZED_DELETE+'</strong></div>';
						$("#message").html(err_msg);
						$("#message").show();
					} else if (ret_data.length > 2) {
						var err_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
						var err_msg = err_element+'<strong>'+ret_data+'</strong></div>';
						$("#message").html(err_msg);
						$("#message").show();
					}else {
						$.ajax({
							type: "GET",
							url: "list",
							data : "ajaxreq="+true,
							success: function(result) { 
								$('#list_view_entry').html(result);
								var succ_element = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
								var succ_msg = succ_element+'<strong>'+DATA_DELETED_SUCCESSFULLY+'</strong></div>';
								$("#message").html(succ_msg);
								$("#message").show();
							}
						});
					}
				}
			});
		});
	});
  
	// delete single entry
	$(".datadisplay").on('click','.delete_entity', function(e) {
		var sqrecord = $(this).closest('a').attr('id') ; 
		var qry_string = "sqrecord="+sqrecord+"&referrer=list";
		$("#delete_confirm").modal('show');
		$("#delete_confirm .btn-primary").off('click');
		$("#delete_confirm .btn-primary").click(function() {
			$("#delete_confirm").modal('hide');
			$.ajax({
				type: "GET",
				<?php
					$e_del_single = new Event("CRMDeleteEntity->eventAjaxDeleteSingleEntity");
					$e_del_single->setEventControler("/ajax_evctl.php");
					$e_del_single->addParam('module',$module);
					$e_del_single->addParam('referrer','list');
					$e_del_single->setSecure(false);
				?>
				url: "<?php echo $e_del_single->getUrl(); ?>&"+qry_string,
				success:  function(html) {
					ret_data = html.trim();
					if (ret_data == '0') {
						var err_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
						var err_msg = err_element+'<strong>'+UNAUTHORIZED_DELETE+'</strong></div>';
						$("#message").html(err_msg);
						$("#message").show();
					} else if (ret_data.length > 2) {
						var err_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
						var err_msg = err_element+'<strong>'+ret_data+'</strong></div>';
						$("#message").html(err_msg);
						$("#message").show();
					} else {
						$.ajax({
							type: "GET",
							url: "list",
							data : "ajaxreq="+true,
							success: function(result) { 
								$('#list_view_entry').html(result);
								var succ_element = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
								var succ_msg = succ_element+'<strong>'+DATA_DELETED_SUCCESSFULLY+'</strong></div>';
								$("#message").html(succ_msg);
								$("#message").show();
							}
						});
					}
				}
			});
		});
	});

	//change assigned to
	$("#change_assigned_to").click(function() {
		var sData = oTable.$('input:checkbox').serialize();
		if (sData == '') {
			var err_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
			var err_msg = err_element+'<strong>'+SELECT_ONE_RECORD_BEFORE_CHANGE_USER+'</strong></div>';
			$("#message").html(err_msg);
			$("#message").show();
			return false ;
		} else {
			var href = '/popups/change_assignedto_modal?m=<?php echo $module;?>&referrar=list&'+sData;
			if (href.indexOf('#') == 0) {
				$(href).modal('open');
			} else {
				$.get(href, function(data) {
					//ugly heck to prevent the content getting append when opening the same modal multiple time
					$("#listview_change_assignedto").html(''); 
					$("#listview_change_assignedto").hide();
					$("#listview_change_assignedto").attr("id","ugly_heck");
					$('<div class="modal fade" tabindex="-1" role="dialog" id="listview_change_assignedto">' + data + '</div>').modal();
				}).success(function() { $('input:text:visible:first').focus(); });
			}
		}
	});
  
	// export list data
	$("#export_list_data").click(function() {
		var custom_view_id = 0
		if ( $("#customview_filter").length !== 0) {
			var custom_view_data = $("#customview_filter").val() ;
			var custom_view_detail_info = custom_view_data.split('::');
			custom_view_id = custom_view_detail_info[0];
		}
		var href = '/popups/export_list_data_modal?m=<?php echo $module;?>&custom_view_id='+custom_view_id;
		if (href.indexOf('#') == 0) { 
			$(href).modal('open');
		} else {
			$.get(href, function(data) {
				//ugly heck to prevent the content getting append when opening the same modal multiple time
				$("#export_list_data_modal").html(''); 
				$("#export_list_data_modal").attr("id","ugly_heck");
				$('<div class="modal fade" tabindex="-1" role="dialog" id="#export_list_data_modal">' + data + '</div>').modal();
			}).success(function() { $('input:text:visible:first').focus(); });
		}
	});
	
	//filter with custom view 
	$("#customview_filter").change(function() {
		var custom_view_data = $("#customview_filter").val() ;
		var custom_view_detail_info = custom_view_data.split('::');
		var custom_view_id = custom_view_detail_info[0];
		$.ajax({
			type: "GET",
			url: "list",
			data : "custom_view_id="+custom_view_id+"&ajaxreq="+true+"&module=<?php echo $module;?>",
			success: function(result){ 
				if (custom_view_detail_info[1] == 1) {
					var custom_view_edit = '<a href="/modules/CustomView/edit?sqrecord='+custom_view_id+'" class="btn btn-primary btn-xs" id="custom_view_edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';
					$('#custom_view_edit').html(custom_view_edit);
				} else {
					$('#custom_view_edit').html('');
				}
				
				if (custom_view_detail_info[2] == 1) {
					var custom_view_delete = '<a href="#" class="btn btn-primary btn-xs" id="custom_view_delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
					$('#custom_view_delete').html(custom_view_delete);
				} else {
					$('#custom_view_delete').html('');
				}
				
				$('#list_view_entry').html(result) ;
			}
		});
	});
	
	// delete a custom view
	$("#custom_view_delete").click(function() {
		var custom_view_data = $("#customview_filter").val() ;
		var custom_view_detail_info = custom_view_data.split('::');
		var custom_view_id = custom_view_detail_info[0];
		$("#delete_confirm").modal('show');
		$("#delete_confirm .btn-primary").off('click');
		$("#delete_confirm .btn-primary").click(function() {
			$("#delete_confirm").modal('hide');
			var qry_string = "sqrecord="+custom_view_id+"&referrer=list";
			$.ajax({
				type: "POST",
				<?php
				$e_del_single = new Event("CustomView->eventAjaxDeleteCustomView");
				$e_del_single->setEventControler("/ajax_evctl.php");
				$e_del_single->addParam('module',$module);
				$e_del_single->addParam('idmodule',$module_id);
				$e_del_single->setSecure(false);
				?>
				url: "<?php echo $e_del_single->getUrl(); ?>&"+qry_string,
				success:  function(html) {
					ret_data = html.trim();
					if (ret_data == '0') {
						var err_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
						var err_msg = err_element+'<strong>'+UNAUTHORIZED_DELETE+'</strong></div>';
						$("#message").html(err_msg);
						$("#message").show();
					} else {
						$.ajax({
							type: "GET",
							url: "list",
							data : "custom_view_id="+ret_data+"&ajaxreq="+true+"&module=<?php echo $module;?>",
							success: function(result) { 
								$("#customview_filter option[value='"+ret_data+"']").prop('selected', true);
								$("#customview_filter option[value='"+custom_view_data+"']").remove();
								$('#custom_view_delete').html('') ;
								$('#custom_view_delete').html('') ;
								$('#list_view_entry').html(result) ;
							}
						});
					}
				}
			});
		});
	}) ;
	
	//change calendar status
	$("#change_event_status").click(function() {
		var sData = oTable.$('input:checkbox').serialize();
		if (sData == '') {
			var err_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
			var err_msg = err_element+'<strong>'+SELECT_ONE_RECORD_BEFORE_CHANGE_EVENT_STATUS+'</strong></div>';
			$("#message").html(err_msg);
			$("#message").show();
			return false ;
		} else {
			var href = '/popups/change_event_status_modal?m=<?php echo $module;?>&referrar=list&'+sData;
			if (href.indexOf('#') == 0) {
				$(href).modal('open');
			} else {
				$.get(href, function(data) {
					//ugly heck to prevent the content getting append when opening the same modal multiple time
					$("#listview_change_event_status").html(''); 
					$("#listview_change_event_status").hide();
					$("#listview_change_event_status").attr("id","ugly_heck");
					$('<div class="modal fade" tabindex="-1" role="dialog" id="listview_change_event_status">' + data + '</div>').modal();
				}).success(function() { $('input:text:visible:first').focus(); });
			}
		}
	});
});
</script>
<div class="container-fluid">
	<div class="row">
		<?php
		if ($module == 'User') {
		include_once("modules/Settings/settings_leftmenu.php");
		?>
		<div class="col-md-9">
		<?php
		} else {
		?>
			<div class="col-md-12">
		<?php 
		} ?>
				<!-- Tool section on list view - delete,change user etc /-->
				<div class="datadisplay-outer listdata_action">
					<div id="message"></div>
					<div class="col-md-8">
						<!-- add new button -->
						<?php 
						if ($_SESSION["do_crm_action_permission"]->action_permitted('add',$module_id) === true) {
						?>
						<a href="/modules/<?php echo $module?>/add" class="btn btn-primary btn-xs">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <?php echo _('add new');?>
						</a>
						<?php 
						} 
						?>
						<!-- delete button -->
						<?php 
						if ($_SESSION["do_crm_action_permission"]->action_permitted('delete',$module_id) === true) {
							if ($module_id == 7) {
						?>
						<a href="#" class="btn btn-primary btn-xs" id="delete_data_user">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> <?php echo _('delete');?>
						</a>
						<?php 
							} else {
						?>
						<a href="#" class="btn btn-primary btn-xs" id="delete_data">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> <?php echo _('delete');?>
						</a>
						<?php
							}
						} ?>
						<!-- import button -->
						<?php 
						if ($module_id == 3 || $module_id == 4 || $module_id == 5 || $module_id == 6 || $module_id == 11 
						|| $module_id == 12) {
							if ($_SESSION["do_crm_action_permission"]->action_permitted('add',$module_id) === true) {
							?>
						<a href="<?php echo NavigationControl::getNavigationLink("Import","index","","?return_module=".$module_id); ?>" class="btn btn-primary btn-xs" id="delete_data">
						<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span> <?php echo _('import');?>
						</a>
						<?php 
							}
						} ?>
             
						<!-- change assigned to button -->
						<?php 
						if ($module_id != 7 && $_SESSION["do_crm_action_permission"]->action_permitted('edit',$module_id) === true) {
						?>
						<a href="#" class="btn btn-primary btn-xs" id="change_assigned_to">
						<span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo _('change assigned to');?>
						</a>
						<?php 
						} ?>
         
						<!-- Export button -->
						<?php
						if ($module_id != 7) {
						?>
						<a href="#" class="btn btn-primary btn-xs" id="export_list_data">
						<span class="glyphicon glyphicon-download" aria-hidden="true"></span> <?php echo _('Export');?>
						</a>
						<?php 
						} ?>
						
						<!-- Change Bulk Calendar Status -->
						<?php
						if ($module_id == 2) {
						?>
						<a href="#" class="btn btn-primary btn-xs" id="change_event_status">
						<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> <?php echo _('change event status');?>
						</a>
						<?php
						}
						?>
						<!-- Show the list view action plugin buttons-->
						<?php
						// process the detail view right block active modules
						$do_process_list_plugins = new CRMPluginProcessor();
						$do_process_list_plugins->process_listview_action_plugin($module_id,$active_plugins);
						?>
					</div>
					<div class="col-md-4">	
						<form class="form-inline">
						<?php
						if (true === $custom_view_allowed) { // defined on modules/{module_name}/list.php
							if ($_SESSION["do_crm_action_permission"]->action_permitted('view',17)) {
								if (is_array($custom_view_data) && count($custom_view_data) > 0) {
									$is_editable_selected_custom_view = false ;
									$is_deleteable_selected_custom_view = false ;
								?>
								<div class="form-group">
								<select name="customview_filter" id="customview_filter" class="form-control input-sm">
								<?php
								foreach ($custom_view_data as $key=>$val) { 
									$is_editable_custom_view = false ;
									$is_deleteable_custom_view = false ;
									$select = '';
									$custom_view_detail_info = '';
									$custom_view_detail_info .= $val["idcustom_view"];
									if ($val["is_editable"] == 1 && $val["iduser"] == $_SESSION["do_user"]->iduser) {
										$is_editable_custom_view = true ;
										$custom_view_detail_info .= '::1';
										$is_deleteable_custom_view = true ;
										$custom_view_detail_info .= '::1';
									}
									if ((int)$custom_view_id > 0) {
										if ($custom_view_id == $val["idcustom_view"]) {
											$select = "SELECTED";
											if (true === $is_editable_custom_view) $is_editable_selected_custom_view = true ;
											if (true === $is_deleteable_custom_view) $is_deleteable_selected_custom_view = true ;
										}
									} elseif ($val["is_default"] == 1) {
										$select = "SELECTED";
										if (true === $is_editable_custom_view) $is_editable_selected_custom_view = true ;
										if (true === $is_deleteable_custom_view) $is_deleteable_selected_custom_view = true ;
									}
									?>
									<option value="<?php echo $custom_view_detail_info;?>" <?php echo $select; ?> ><?php echo $val["name"];?></option>
									<?php 
									}
									?>
								</select>
								</div>
								<?php
								}
							}
							?>
							<div class="form-group">
							<!--<div style="margin-top:3px;margin-left:0px;float:right;">-->
								<?php
								if ($_SESSION["do_crm_action_permission"]->action_permitted('add',17)) { ?>
									<a href="<?php echo NavigationControl::getNavigationLink("CustomView","add","","?target_module_id=".$module_id); ?>" class="btn btn-primary btn-xs">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
									</a>
								<?php 
								}
								?>
								<span id="custom_view_edit">
									<?php
									if ($_SESSION["do_crm_action_permission"]->action_permitted('edit',17) && true === $is_editable_selected_custom_view) {
									?>
									<a href="<?php echo NavigationControl::getNavigationLink("CustomView","edit",$custom_view_id); ?>" class="btn btn-primary btn-xs" id="custom_view_edit">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
									</a>
									<?php
									}
									?>
								</span>
								<span id="custom_view_delete">
									<?php
									if ($_SESSION["do_crm_action_permission"]->action_permitted('delete',17) && true === $is_deleteable_selected_custom_view) {
									?>
									<a href="#" class="btn btn-primary btn-xs" id="custom_view_delete">
									<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
									</a>
									<?php
									}
									?>
								</span>
							</div>
						<?php } ?>
						</form>
					</div>
					<div class="clear_float"></div>
				</div>
				<!-- Tools section ends here /-->
				<div id="list_view_entry">
					<?php
					require('view/listview_entry.php');
					?>
				</div>
			</div><!--/span-->
		</div><!--/row-->
	</div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="delete_confirm">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3><span class="label label-warning"><?php echo _('WARNING')?></span></h3>
			</div>
			<div class="modal-body">
				<?php echo _('Are you sure you want to delete the record(s).');?>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
				<input type="submit" class="btn btn-primary" value="<?php echo _('Delete')?>"/>
			</div>
		</div>
	</div>
</div>