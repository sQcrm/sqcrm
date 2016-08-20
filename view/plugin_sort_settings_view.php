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
				<p class="lead"><?php echo _('Sort plugins (display and action)')?></p> 
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
				<h2><small><?php echo _('Set display priority for detail view plugins.');?></small></h2>
				<br />
				<ol class="serialization-detail-view vertical">
				<?php
				if (count($detail_view_plugins) > 1) {
					foreach ($detail_view_plugins as $key=>$val) {
						$plugin_obj = new $val["name"]() ;
				?>
						<li data-id="<?php echo $val["id"];?>" data-name="<?php echo $val["name"];?>"><i class="glyphicon glyphicon-move" style="cursor: pointer;"></i>&nbsp;&nbsp;<?php echo $plugin_obj->get_plugin_title();?></li>
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
				<h2><small><?php echo _('Set action priority for action plugins.');?></small></h2>
				<br />
				<ol class="serialization-action vertical">
				<?php
				if (count($action_view_plugins) > 1) {
					foreach ($action_view_plugins as $key=>$val) {
						$plugin_obj = new $val["name"]() ;
				?>
						<li data-id="<?php echo $val["id"];?>" data-name="<?php echo $val["name"];?>"><i class="glyphicon glyphicon-move" style="cursor: pointer;"></i>&nbsp;&nbsp;<?php echo $plugin_obj->get_plugin_title();?></li>
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