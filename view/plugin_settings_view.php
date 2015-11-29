<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* plugin setting page
* @author Abhik Chakraborty
*/  
?>
<div id="message"></div>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"plugins")?>"><?php echo _('Plugin')?></a></h3>
				<p><?php echo _('Manage plugins')?></p> 
			</div>		
			<div class="datadisplay-outer">
			<div class="left_300"><p><?php echo _('Available plugins');?></p></div>
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
								<a href="#" class="btn btn-inverse deactivate-plugin" id="<?php echo array_search($plugin,$activated_plugin_names);?>">
									<?php echo _('deactivate plugin');?>
								</a>
							</td>
							<?php
							} else {
							?>
							<td width="20%" id="plugin_activate_<?php echo $plugin;?>">
								<a href="#" class="btn btn-primary activate-plugin" id="<?php echo $plugin;?>">
									<?php echo _('actvate plugin');?>
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
				var content = '<a href="#" class="btn btn-inverse deactivate-plugin" id="'+html+'">deactivate plugin</a>';
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
				var content = '<a href="#" class="btn btn-primary activate-plugin" id="'+plugin_name+'">actvate plugin</a>' ;
				$("#plugin_deactivate_"+id).html(content) ;
				$("#plugin_deactivate_"+id).prop("id","plugin_activate_"+plugin_name.trim()) ;
			}
		});
	});
	
});
</script>