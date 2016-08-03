<?php
if (isset($_REQUEST["widget_id"]) && (int)$_REQUEST["widget_id"] > 0) {
	$widget_id = (int)$_REQUEST["widget_id"] ;
}
?>
<li data-id="<?php echo $widget_id ; ?>" class="li_no_number">
	<div class="datadisplay-outer"><span class="glyphicon glyphicon-move" aria-hidden="true"></span>
		<?php echo _('Calls And Events') ;?><a href="#"><span class="glyphicon glyphicon-remove-sign remove-widget" aria-hidden="true" style="float:right;margin-right:20px;" id="<?php echo $widget_id ; ?>"></span></a>
		<div id="calls_and_events"></div>
	</div>
</li>


<script type="text/javascript">
$(document).ready(function() {
	// load the calendar with the events on pageload
	$.ajax({
		type: "GET",
		url: "/widgets.php",
		data : "widget_name=CallsAndEvents&resource_name=calls_and_events&ajaxreq="+true+"&rand="+generateRandonString(10),
		success: function(result) { 
			$('#calls_and_events').html(result) ;
		},
		beforeSend: function() {
			$('#calls_and_events').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		}
    });
});

/*
* function to load the calendar when next or previous month arrow is clicked
* @param change_type
* @param current_year
* @param current_mon
*/
function change_month_events(change_type,current_year,current_mon) {
	$.ajax({
		type: "GET",
		url: "/widgets.php",
		data : "widget_name=CallsAndEvents&resource_name=calls_and_events&ajaxreq="+true+"&y="+current_year+"&m="+current_mon+"&c="+change_type+"&rand="+generateRandonString(10),
		success: function(result) { 
			$('#calls_and_events').html(result) ;
		},
		beforeSend: function() {
			$('#calls_and_events').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		}
    });
}

/*
* function to load the events when a date is clicked
* @param year
* @param month
* @param day
*/
function load_events_for_day(year,month,day) {
	$.ajax({
		type: "GET",
		url: "/widgets.php",
		data : "widget_name=CallsAndEvents&resource_name=calls_and_events&ajaxreq="+true+"&y="+year+"&m="+month+"&d="+day+"&rand="+generateRandonString(10),
		success: function(result){ 
			$('#events_per_day_list').html(result) ;
		},
		beforeSend: function() {
			$('#events_per_day_list').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		}
    });
}
</script>