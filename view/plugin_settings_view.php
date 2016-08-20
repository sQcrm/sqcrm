<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* plugin setting page
* @author Abhik Chakraborty
*/  
?>
<div id="message"></div>
<div class="container-fluid">
	<div class="row">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="col-md-9">
			<div class="box_content">
				<ol class="breadcrumb">
					<li class="active"><?php echo _('Settings')?></li>
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"plugins")?>"><?php echo _('Plugin')?></a></li>
				</ol>
				<p class="lead"><?php echo _('Manage plugins')?></p> 
			</div>		
			<div class="datadisplay-outer">
			<h2><small><?php echo _('Available plugins');?></small></h2>
			<?php
			$cnt = 0 ;
			if (is_array($plugins) && count($plugins) >0) {
			?>
				<table class="datadisplay">
					<tbody>
					<?php
					foreach($plugins as $key=>$plugin) {
						if (file_exists(BASE_PATH.'/plugins/'.$plugin.'/'.$plugin.'.class.php')) {
							include_once(BASE_PATH.'/plugins/'.$plugin.'/'.$plugin.'.class.php');
							$do_plugin_obj = new $plugin() ;
							$cnt++;
						} else {
							continue ;
						}
					?>
						<tr id="">
							<td width="5%"><?php echo $cnt;?></td>
							<td width="15%"><?php echo $do_plugin_obj->get_plugin_title();?></td>
							<td width="40%"><?php echo (strlen($do_plugin_obj->get_plugin_description()) > 2 ? $do_plugin_obj->get_plugin_description():_('No Description found !!'))?></td>
							<td width="20%"><?php echo $do_plugin_settings->get_plugin_type_description($do_plugin_obj->get_plugin_type());?></td>
							<?php
							if (in_array($plugin,$activated_plugin_names)) {
							?>
							<td width="20%" id="plugin_deactivate_<?php echo array_search($plugin,$activated_plugin_names);?>">
								<a href="#" class="btn btn-default active deactivate-plugin" id="<?php echo array_search($plugin,$activated_plugin_names);?>">
									<?php echo _('deactivate');?>
								</a>
								<a href="#" class="btn btn-primary set-plugin-permission" id="<?php echo $plugin;?>">
									<?php echo _('set permission');?>
								</a>
							</td>
							<?php
							} else {
							?>
							<td width="20%" id="plugin_activate_<?php echo $plugin;?>">
								<a href="#" class="btn btn-primary activate-plugin" id="<?php echo $plugin;?>">
									<?php echo _('actvate');?>
								</a>
							</td>
							<?php 
							}
							?>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			<?php	
			}
			if ($cnt == 0) {
				echo '<strong>'._('No plugin found').'</strong>';
			}
			?>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="plugin-permission-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3><span class="label label-info"><?php echo _('Set permission for the plugin');?></span></h3>
			</div>
			<div class="modal-body">
				<div class="box_content">
					<input type="hidden" name="plugin_name" id="plugin_name">
					<div class="alert alert-info">
					<?php
					echo _('If the following option is selected then the plugin will be accessible to all the users who have permission to view the module for which the plugin is meant for.');
					?>
					</div>
					<input type="radio" name="all_users" id="all_users">&nbsp;&nbsp;<?php echo _('Allow access to all users');?>
					<div style="margin-top:10px;text-align:center;">
					<?php
					echo _('OR');
					?>
					</div>
					<br />
					<div class="alert alert-info">
					<?php
					echo _('Please select the roles and all the users associated with the role will be having the permission to access the plugin. The associated users should be having permission to view the module for which the plugin is meant for.');
					?>
					</div>
					<input type="radio" name="by_roles" id="by_roles">&nbsp;&nbsp;<?php echo _('Allow access to selected roles');?>
					<div id="roles_options_section" style="display:none;"></div>
					<div style="margin-top:10px;text-align:center;">
					<?php
					echo _('OR');
					?>
					</div>
					<br />
					<div class="alert alert-info">
					<?php
					echo _('Please select the users and the plugin will be accessible to the selected users if they have permission to view the module for which the plugin is meant for.');
					?>
					</div>
					<input type="radio" name="by_users" id="by_users">&nbsp;&nbsp;<?php echo _('Allow access to selected users');?>
					<div id="users_options_section" style="display:none;"></div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default active" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Close');?></a>
				<input type="submit" class="btn btn-primary plugin-set-permission" id="" value="<?php echo _('set permission')?>"/>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() { 
	$(document.body).on('click','.activate-plugin',function(e) {
		var plugin_name = this.id;
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("PluginSettings->eventActivatePlugin");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>&plugin_name="+plugin_name,
			beforeSubmit: function() {
				$("#plugin_activate_"+plugin_name).html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
			},
			success:  function(html) {
				var content = '<a href="#" class="btn btn-default active deactivate-plugin" id="'+html+'">'+DEACTIVATE+'</a>';
				content += '<a href="#" class="btn btn-primary set-plugin-permission" id="'+html+'">'+SET_PERMISSION+'</a>';
				$("#plugin_activate_"+plugin_name).html(content);
				$("#plugin_activate_"+plugin_name).prop("id","plugin_deactivate_"+html.trim());
			}
		});
		return false;
	});
	
	$(document.body).on('click','.deactivate-plugin',function(e) {
		var id = this.id ;
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("PluginSettings->eventDeactivatePlugin") ;
			$e_event->setEventControler("/ajax_evctl.php") ;
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>&id="+id,
			beforeSubmit: function() {
				$("#plugin_deactivate_"+id).html('<img src="/themes/images/ajax-loader1.gif" border="0" />') ;
			},
			success: function(plugin_name) {
				var content = '<a href="#" class="btn btn-primary activate-plugin" id="'+plugin_name+'">'+ACTIVATE+'</a>' ;
				$("#plugin_deactivate_"+id).html(content) ;
				$("#plugin_deactivate_"+id).prop("id","plugin_activate_"+plugin_name.trim()) ;
			}
		});
	});
	
	$(document.body).on('click','.set-plugin-permission',function(e) {
		var id = this.id ;
		$.ajax({
			type: "GET",
			<?php
			$e_event = new Event("PluginSettings->eventGetPluginPermissionsData") ;
			$e_event->setEventControler("/ajax_evctl.php") ;
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>&plugin_name="+id,
			success: function(data) {
				var jsonResponse = JSON.parse(data);
				// init the modal
				$("#plugin-permission-modal #all_users").prop('checked',false);
				$("#plugin-permission-modal #by_roles").prop('checked',false);
				$("#plugin-permission-modal #by_users").prop('checked',false);
				$("#plugin-permission-modal #roles_options_section").hide();
				$("#plugin-permission-modal #users_options_section").hide();
				$("#plugin-permission-modal #plugin_name").val(id);
				
				
				// parse the json data
				if (jsonResponse.all_users === true) {
					$("#plugin-permission-modal #all_users").prop('checked',true);
				} else if (jsonResponse.by_roles === true) {
					$("#plugin-permission-modal #roles_options_section").show();
					$("#plugin-permission-modal #by_roles").prop('checked',true);
				} else if (jsonResponse.by_users === true) {
					$("#plugin-permission-modal #users_options_section").show();
					$("#plugin-permission-modal #by_users").prop('checked',true);
				}
				
				// create the roles data form element
				var rolesFormData = '';
				rolesFormData += '<select class="form-control input-sm" name="roles_data" id="roles_data" size=6 multiple>';
				jsonResponse.by_roles_data.forEach( function(val,key) {
					var selected = (val.selected === true ? "SELECTED" : "");
					rolesFormData += '<option value=\''+val.idrole+'\' '+selected+'>'+val.rolename+'</option>';
				});
				rolesFormData += '</select>';
				$("#plugin-permission-modal #roles_options_section").html(rolesFormData);
				
				// create the users data form element
				var usersFormData = '';
				usersFormData += '<select class="form-control input-sm" name="users_data" id="users_data" size=6 multiple>';
				jsonResponse.by_users_data.forEach( function(val,key) {
					var selected = (val.selected === true ? "SELECTED" : "");
					usersFormData += '<option value=\''+val.iduser+'\' '+selected+'>'+val.user_name+'('+val.firstname+' '+val.lastname+')</option>';
				});
				usersFormData += '</select>';
				$("#plugin-permission-modal #users_options_section").html(usersFormData);
				
				// show the modal
				$("#plugin-permission-modal").modal('show');
			}
		});
	});
	
	// when all users option is checked
	$(document.body).on('click','#all_users',function(e) {
		$("#plugin-permission-modal #by_roles").removeAttr('checked');
		$("#plugin-permission-modal #by_users").removeAttr('checked');
		$("#plugin-permission-modal #roles_options_section").hide();
		$("#plugin-permission-modal #users_options_section").hide();
	});
	
	// when by roles option is checked
	$(document.body).on('click','#by_roles',function(e) {
		$("#plugin-permission-modal #roles_options_section").show();
		$("#plugin-permission-modal #users_options_section").hide();
		$("#plugin-permission-modal #all_users").removeAttr('checked');
		$("#plugin-permission-modal #by_users").removeAttr('checked');
	});
	
	// when by users option is checked
	$(document.body).on('click','#by_users',function(e) {
		$("#plugin-permission-modal #users_options_section").show();
		$("#plugin-permission-modal #roles_options_section").hide();
		$("#plugin-permission-modal #all_users").removeAttr('checked');
		$("#plugin-permission-modal #by_roles").removeAttr('checked');
	});
	
	$(document.body).on('click','.plugin-set-permission',function(e) {
		var formData = {
			plugin_name: $("#plugin-permission-modal #plugin_name").val(),
			all_users: $("#plugin-permission-modal #all_users:checked").val(),
			by_roles: $("#plugin-permission-modal #by_roles:checked").val(),
			by_users: $("#plugin-permission-modal #by_users:checked").val(),
			roles_data: $("#plugin-permission-modal #roles_data").val(),
			users_data: $("#plugin-permission-modal #users_data").val()
		};
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("PluginSettings->eventUpdatePluginPermission") ;
			$e_event->setEventControler("/ajax_evctl.php") ;
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>",
			data: formData,
			beforeSubmit: function() {
			
			},
			success: function(res) {
				if (res.trim() == '1') {
					$("#plugin-permission-modal").modal('hide');
					display_js_success(PLUGIN_PERMISSION_SET,'message');
				} else {
					display_js_error(res,'message');
				}
			}
		});
	});
});
</script>