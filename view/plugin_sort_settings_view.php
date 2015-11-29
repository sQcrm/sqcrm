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
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"plugins_sort")?>"><?php echo _('Sort Plugin')?></a></h3>
				<p><?php echo _('Sort plugins (display and action)')?></p> 
			</div>		
			
			<?php 
				$detail_plguin_style = 'style="display:none;"';
				$detail_view_plugin_sort = false ;
				if (count($detail_view_plugins) > 1) {
					$detail_plguin_style = 'style="display:block;"';
					$detail_view_plugin_sort = true ;
				}
				
				if (false === $detail_view_plugin_sort) {
					echo '<div class="datadisplay-outer">' ;
					echo _('No detail view pluings available for sorting') ;
					echo '</div>' ;
				}
			?>
			<div class="datadisplay-outer" id="detail-view-plugin-sortable" <?php echo $detail_plguin_style ;?>>
				<div class="left_300"><p><?php echo _('Set display priority for detail view plugins.');?></p></div>
				<br />
				<ol class="serialization-detail-view vertical">
				<?php
				if (count($detail_view_plugins) > 1) {
					foreach ($detail_view_plugins as $key=>$val) {
						$plugin_obj = new $val["name"]() ;
				?>
						<li data-id="<?php echo $val["id"];?>" data-name="<?php echo $val["name"];?>"><i class="icon-move"></i>&nbsp;&nbsp;<?php echo $plugin_obj->get_plugin_title();?></li>
				<?php 
					}
				}
				?>
              </ol>
			</div>
			
			<?php 
				$action_plguin_style = 'style="display:none;"';
				$action_plugin_sort = false ;
				if (count($action_view_plugins) > 1) {
					$action_plguin_style = 'style="display:block;"';
					$action_plugin_sort = true ;
				}
				
				if (false === $action_plugin_sort) {
					echo '<div class="datadisplay-outer">' ;
					echo _('No action pluings available for sorting') ;
					echo '</div>' ;
				}
			?>
			<div class="datadisplay-outer" id="action-plugin-sortable" <?php echo $action_plguin_style ;?>>
				<div class="left_300"><p><?php echo _('Set action priority for action plugins.');?></p></div>
				<br />
				<ol class="serialization-action vertical">
				<?php
				if (count($action_view_plugins) > 1) {
					foreach ($action_view_plugins as $key=>$val) {
						$plugin_obj = new $val["name"]() ;
				?>
						<li data-id="<?php echo $val["id"];?>" data-name="<?php echo $val["name"];?>"><i class="icon-move"></i>&nbsp;&nbsp;<?php echo $plugin_obj->get_plugin_title();?></li>
				<?php 
					}
				}
				?>
              </ol>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>
<script type="text/javascript" src="/js/jquery/plugins/jquery-sortable.js"></script>
<script>
$(document).ready(function() { 
	var detail_view_plugin_group = $("ol.serialization-detail-view").sortable({
		group: 'serialization-detail-view',
		delay: 100,
		onDrop: function ($item, container, _super) {
			var data = detail_view_plugin_group.sortable("serialize").get();
			var jsonString = JSON.stringify(data, null, ' ');
			_super($item, container);
			$.ajax({
				type: "POST",
				<?php
				$e_event = new Event("PluginSettings->eventSortPlugins");
				$e_event->setEventControler("/ajax_evctl.php");
				$e_event->setSecure(false);
				?>
				url: "<?php echo $e_event->getUrl(); ?>&sort_type=display_priority",
				data:"jsonData="+jsonString,
				success:  function(html) {
					// nothing to be done now
				}
			});
		}
	});
	
	var action_plugin_group = $("ol.serialization-action").sortable({
		group: 'serialization-action',
		delay: 500,
		onDrop: function ($item, container, _super) {
			var data = action_plugin_group.sortable("serialize").get();
			var jsonString = JSON.stringify(data, null, ' ');
			_super($item, container);
			$.ajax({
				type: "POST",
				<?php
				$e_event = new Event("PluginSettings->eventSortPlugins");
				$e_event->setEventControler("/ajax_evctl.php");
				$e_event->setSecure(false);
				?>
				url: "<?php echo $e_event->getUrl(); ?>&sort_type=action_priority",
				data:"jsonData="+jsonString,
				success:  function(html) {
					// nothing to be done now
				}
			});
		}
	});
});
</script>