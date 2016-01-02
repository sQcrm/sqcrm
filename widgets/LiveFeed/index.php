<?php
if (isset($_REQUEST["widget_id"]) && (int)$_REQUEST["widget_id"] > 0) {
	$widget_id = (int)$_REQUEST["widget_id"] ;
}
?>
<li data-id="<?php echo $widget_id ; ?>" class="li_no_number">
	<div class="datadisplay-outer"><i class="icon-move"></i>
		<?php echo _('Live Activity Feed') ;?><a href="#"><i class="icon-remove-sign remove-widget" style="float:right;" id="<?php echo $widget_id ; ?>"></i></a>
		<div id="livefeed_scroll"></div>
	</div>
</li>

<?php
$do_feed_display = new LiveFeedDisplay();
?>

<script type="text/javascript">
	var start = <?php echo $do_feed_display->sql_start;?> ;
	var max =  <?php echo $do_feed_display->sql_max;?> ;
	var sql_end = max ;
	var page_loaded_with_feed = 0;
  
	$(document).ready(function() {
		$.ajax({
			type: "GET",
			url: "/widgets.php",
			data : "widget_name=LiveFeed&resource_name=livefeed&ajaxreq="+true+"&rand="+generateRandonString(10),
			success: function(result) { 
			if (result.trim() == '0') {
				$('#livefeed_scroll').html('<p id="no_feed"><STRONG>no activity stream found</STRONG></p>') ;
			} else {
				$('#livefeed_scroll').html(result) ;
				page_loaded_with_feed = 1 ;
			}
		},
		beforeSend: function() {
			$('#livefeed_scroll').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		}
    });
    
    
    $('#livefeed_scroll').scrollLoad({
		url : '/widgets.php', //your ajax file to be loaded when scroll breaks ScrollAfterHeight
        getData : function() {
			start = sql_end;
			sql_end = start+max;
			return {widget_name:"LiveFeed",resource_name:"livefeed",ajaxreq:"true",start:start,max:max,rand:generateRandonString(10)};
			//you can post some data along with ajax request
        },
        start : function() {
			$('<div class="loading"><img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" /></div>').appendTo(this);
            // you can add your effect before loading data
        },
        ScrollAfterHeight : 95,     //this is the height in percentage after which ajax stars
        onload : function( data ) {
			if(data.trim() == '0') {
				// no more activity stream found
			} else {
				$(this).append( data );
			}
			$('.loading').remove();
        },
        
	});
      
	window.setInterval(
		function() {
			$.ajax({
				type: "GET",
				url: "/widgets.php",
				data : "widget_name=LiveFeed&resource_name=livefeed&ajaxreq="+true+"&livefeed="+true+"&rand="+generateRandonString(10),
				success: function(result) { 
					if (result.trim() != '0') {
						sql_end = sql_end+1;
						if (page_loaded_with_feed == 0) {
							$('#no_feed').hide();
							page_loaded_with_feed = 1 ;
						}
						$(result).hide().prependTo('#livefeed_scroll').slideDown('slow');
					}
				}
			});
	},4000);
});  
</script>