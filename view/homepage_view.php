<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Home page view
* @author Abhik Chakraborty
*/  
?>
<!-- load the home page graph library -->
<link rel="stylesheet" type="text/css" href="/js/plugins/jqplot/jquery.jqplot.min.css" />
<script src="/js/plugins/jqplot/jquery.jqplot.min.js"></script>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<div class="datadisplay-outer">
					<div class="left_300" id="select_widget">
						<a href="#" class="btn btn-primary btn-small bs-prompt" id="add_widget">
							<i class="icon-white icon-plus"></i> <?php echo _('add a widget to dashboard');?>
						</a>
					</div>
					<div class="left_600" id="available_widgets" style="display:none;">	
					</div>
					<div class="clear_float"></div>
				</div>
			</div>
		</div>
	</div>
	<?php
	if (count($widgets) == 0) { ?>
	<div class="row-fluid" id="no_widget_message_block">
		<div class="span12">
			<div class="row-fluid">
				<div class="datadisplay-outer">
					<div class="alert alert-info">
					<?php
						echo '<h4>';
						echo _('No widget added for the dashboard !');
						echo '</h4>';
						echo '<br />';
						echo _('Hello ').$_SESSION["do_user"]->firstname.' '.$_SESSION["do_user"]->lastname.',' ;
						echo '<br />';
						echo _('Currently you have added no widget for the dashboard.');
						echo '<br />';
						echo _('Add some widget to your dashboard, you can arrange the widgets by dragging and dropping on the dashboard.') ;
						echo '<br />';
						echo _('You can remove the added widget if you want, and they will be available for adding again.');
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
	?>
	<div class="row-fluid">
		<div class="span6">
			<div class="row-fluid">
				<ol class="serialization-home-page" style="margin-left:0px;" id="widget_left">
				<?php
				if (array_key_exists('left',$widgets) && count($widgets['left']) > 0) {
					foreach($widgets['left'] as $key=>$val) {
						$widget_id = 0 ;
						if (file_exists(BASE_PATH.'/widgets/'.$val['widget_name'].'/index.php')) {
							$widget_id = $val['id'] ;
							require_once(BASE_PATH.'/widgets/'.$val['widget_name'].'/index.php');
						}
					}
				}
				?>
				</ol>
			</div><!--/row-->
		</div><!--/span-->
		<div class="span6">
			<div class="row-fluid">
				<ol class="serialization-home-page" style="margin-left:0px;" id="widget_right">
				<?php
				if (array_key_exists('right',$widgets) && count($widgets['right']) > 0) {
					foreach($widgets['right'] as $key=>$val) {
						$widget_id = 0 ;
						if (file_exists(BASE_PATH.'/widgets/'.$val['widget_name'].'/index.php')) {
							$widget_id = $val['id'] ;
							require_once(BASE_PATH.'/widgets/'.$val['widget_name'].'/index.php');
						}
					}
				}
				?>
				</ol>
			</div>
		</div>
	</div><!--/row-->
</div>
<script type="text/javascript" src="/js/jquery/plugins/jquery-sortable.js"></script>
<script>
$(document).ready(function() { 
	// sorting the widgets
	var widget_sort = $("ol.serialization-home-page").sortable({
		group: 'serialization-home-page',
		delay: 100,
		onDrop: function ($item, container, _super) {
			container.el.addClass("li_no_number");
			var data = widget_sort.sortable("serialize").get();
			var jsonString = JSON.stringify(data, null, ' ');
			_super($item, container);
			$.ajax({
				type: "POST",
				<?php
				$e_event = new Event("DashboardWidgetProcessor->eventSortWidgets");
				$e_event->setEventControler("/ajax_evctl.php");
				$e_event->setSecure(false);
				?>
				url: "<?php echo $e_event->getUrl(); ?>",
				data:"jsonData="+jsonString,
				success:  function(html) {
					// nothing to be done now
				}
			});
		}
	});
	
	//remove widgets
	$(".container-fluid").on('click','.remove-widget', function(e) {
		var id = $(this).attr('id') ;
		$(this).parent().parent().fadeOut(800, function(){ 
			$(this).remove();
		});
		$.ajax({
			type : "GET" ,
			<?php
			$e_event = new Event("DashboardWidgetProcessor->eventRemoveUserWidget");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>",
			data: "id="+id,
			success: function(html) {
				if (html.trim() == '0') {
					display_js_error(WIDGET_NOT_ADDED_FOR_DELETE,'js_errors');
					return false ;
				} else {
					false ;
				}
			}
		}) ;
		return false ;
	});
	
	// add a widgets
	$(".container-fluid").on('click','#add_widget', function(e) {
		$.ajax({
			type : "GET",
			<?php
			$e_event = new Event("DashboardWidgetProcessor->eventGetWidgetAddOptions");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>",
			success:  function(html) {
				if (html.trim() == 0) {
					display_js_error(NO_MORE_WIDGET_FOUND,'js_errors');
					return false ;
				} else {
					$('#available_widgets').html(html);
					$('#select_widget').hide('slow');
					$('#available_widgets').show('slow');
				}
			}
		});
	});
	
	//cancel save widgets
	$(".container-fluid").on('click','#cancel_save_widget', function(e) {
		$('#available_widgets').hide('slow');
		$('#select_widget').show('slow');
	});
	
	// save widget to db and load it on specific position (left/right)
	$(".container-fluid").on('click','#save_widget', function(e) {
		$.ajax({
			type : "POST",
			<?php
			$e_event = new Event("DashboardWidgetProcessor->eventSaveUsersWidget");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>",
			data: "widget_name="+$('#widget_selector').val()+"&position="+$('#widget_position_selector').val(),
			success:  function(html) {
				if (html.trim() == '0') {
					display_js_error(WIDGET_ADDED_OR_NOT_AVAILABLE,'js_errors');
				} else {
					$.ajax({
						type : "GET",
						url: "/widgets.php",
						data : "widget_name="+$('#widget_selector').val()+"&resource_name=index&widget_id="+html.trim()+"&ajaxreq="+true+"&rand="+generateRandonString(10),
						success: function(result) { 
							if ($('#widget_position_selector').val() == '1') {
								$('#widget_left').prepend(result) ;
								$('#no_widget_message_block').hide('slow') ;
							} else {
								$('#widget_right').prepend(result) ;
								$('#no_widget_message_block').hide('slow') ;
							}
						}
					});
				}
				$('#available_widgets').hide('slow');
				$('#select_widget').show('slow');
			}
		});
	});
});
</script>