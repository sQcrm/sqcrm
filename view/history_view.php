<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* data history view  
* @author Abhik Chakraborty
*/  
            
require("history_view_entry.php");
?>
<div id="more_history"></div>
<?php
if ($data_history !== false) {
?>
<div id='load_more_data' align="center">
	<button class="btn btn-primary btn-large" id="load_more"><?php echo _('load more');?></button>
</div>
<?php 
} ?>
<script>
var start = <?php echo $do_datahistory_display->sql_start ; ?> ;
var sql_max = <?php echo $do_datahistory_display->sql_max ; ?> ;
var sql_end = sql_max ;
var cnt = 0 ;
var qry_string = '';
$("#load_more").click( function() {
	$("#load_more").html(LOADING);
	$("#load_more").attr('disabled','disabled');
	var last_year = $("#last_year").val();
	var last_month = $("#last_month").val();
	var last_postition = $("#last_postition").val();
	$("#last_details").html('');
	$("#last_details").attr("id","ugly_heck");
	cnt++;
	if (cnt > 0) {
		start = sql_end;
		sql_end = start+sql_max;
		qry_string = "&start="+start+"&max="+sql_max;
	}
	$.ajax({
		type: "GET",
		url: "history",
		data : "sqrecord=<?php echo $sqcrm_record_id ;?>&ajaxreq="+true+qry_string+"&ajax_load_more="+true+"&last_year="+last_year+"&last_month="+last_month+"&last_postition="+last_postition,
		success: function(result) { 
			if (result == false) {
				$("#load_more").html(NO_MORE_DATA_FOUND);
			} else {
				$("#more_history").append(result) ;
				$("#load_more").removeAttr('disabled','disabled');
				$("#load_more").html(LOAD_MORE);
			}
		}
	});
});
</script>