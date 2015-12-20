<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Home page calls and events
* @author Abhik Chakraborty
*/  
?>
<link rel="stylesheet" href="/themes/custom-css/eventcal/eventCalendar.css">
<link rel="stylesheet" href="/themes/custom-css/eventcal/eventCalendar_theme_responsive.css">
<div class="datadisplay-outer">
	<?php echo $component_name ;?>
	<div id="calls_and_events"></div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	// load the calendar with the events on pageload
	$.ajax({
		type: "GET",
		url: "callsandevents",
		data : "ajaxreq="+true+"&rand="+generateRandonString(10),
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
		url: "callsandevents",
		data : "ajaxreq="+true+"&y="+current_year+"&m="+current_mon+"&c="+change_type+"&rand="+generateRandonString(10),
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
		url: "callsandevents",
		data : "ajaxreq="+true+"&y="+year+"&m="+month+"&d="+day+"&rand="+generateRandonString(10),
		success: function(result){ 
			$('#events_per_day_list').html(result) ;
		},
		beforeSend: function() {
			$('#events_per_day_list').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		}
    });
}
</script>