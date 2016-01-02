<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Home page lead by lead status
* @author Abhik Chakraborty
*/  
include_once(BASE_PATH.'/widgets/LeadsByLeadsStatus/LeadsByLeadsStatus.class.php') ;
$leads_by_leads_status = new LeadsByLeadsStatus() ;
$data_5 = $leads_by_leads_status->get_leads_by_status_graph();
?>
<script src="/js/plugins/jqplot/plugins/jqplot.barRenderer.min.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.pointLabels.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	<?php
    if(sizeof($data_5) > 0) {
	?>
    $.jqplot.config.enablePlugins = true;
    var s1 = [
		<?php
		$cnt = 0 ;
		foreach ($data_5 as $key=>$val) {
			echo $val;
			if ($cnt != sizeof($data_5)-1) { echo ","; }
			$cnt++;
		}
		?>
	];
	// Can specify a custom tick Array.
	// Ticks should match up one for each y value (category) in the series.
	var ticks = [
		<?php
        $cnt = 0 ;
        foreach ($data_5 as $key=>$val) {
			echo "'".$key."'";
			if ($cnt != sizeof($data_5)-1) { echo ","; }
			$cnt++;
        }
      ?>
	];
      
	var plot3 = $.jqplot('leads_by_lead_status', [s1], {
		// The "seriesDefaults" option is an options object that will
		// be applied to all series in the chart.
		animate:!$.jqplot.use_excanvas,
		seriesDefaults: {
			renderer:$.jqplot.BarRenderer,
			rendererOptions:{ varyBarColor:true},
			pointLabels: {show:true,location: 's'}
		},
		axes: {
			// Use a category axis on the x axis and use our custom ticks.
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks
			},
			// Pad the y axis just a little so bars can get close to, but
			// not touch, the grid boundaries.  1.2 is the default padding.
			yaxis: {
				pad: 1.05
             }
          },
      });
      
	window.onresize = function(event) {
		plot3.destroy();
		plot3.replot();
	};
	
	/*$(window).resize(function() {
          plot3.replot( { resetAxes: true } );
    });*/
	<?php
    } else {
	?>
		$("#leads_by_lead_status").append('<p>'+NO_DATA_FOR_GRAPH+'</p>');
	<?php 
	} ?>
});
</script>