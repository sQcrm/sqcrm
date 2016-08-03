<?php
if (isset($_REQUEST["widget_id"]) && (int)$_REQUEST["widget_id"] > 0) {
	$widget_id = (int)$_REQUEST["widget_id"] ;
}
?>
<li data-id="<?php echo $widget_id ; ?>" class="li_no_number">
	<div class="datadisplay-outer"><span class="glyphicon glyphicon-move" aria-hidden="true"></span>
		<?php echo _('Prospect Pipeline By Sales Stage') ;?><a href="#"><span class="glyphicon glyphicon-remove-sign remove-widget" aria-hidden="true" style="float:right;margin-right:20px;" id="<?php echo $widget_id ; ?>"></span></a>
		<div id="prospect_pipeline_by_sales_stage"></div>
	</div>
</li>

<script type="text/javascript">
$(document).ready(function() {
	// load the calendar with the events on pageload
	$.ajax({
		type: "GET",
		url: "/widgets.php",
		data : "widget_name=ProspectPipeline&resource_name=prospect_pipeline_salesstage&ajaxreq="+true+"&rand="+generateRandonString(10),
		success: function(result) { 
			$('#prospect_pipeline_by_sales_stage').html(result) ;
		},
		beforeSend: function() {
			$('#prospect_pipeline_by_sales_stage').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		}
    });
});
</script>