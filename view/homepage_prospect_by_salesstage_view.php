<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Home page prospect by sales stage graph
* @author Abhik Chakraborty
*/  
?>
<div class="datadisplay-outer">
	<?php echo $component_name ;?>
	<div id="prospect_by_sales_stage"></div>
</div>
<script src="/js/plugins/jqplot/plugins/jqplot.pieRenderer.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	<?php
    if (sizeof($data_4) > 0) {
	?>
		var data = [
			<?php
			$cnt = 0;
			foreach ($data_4 as $key=>$val) {
				echo "['".$key."',".$val."]";
				if ($cnt != sizeof($data_4)-1) { echo ","; }
				$cnt++;
			}
			?>
		];
		var plot1 = $.jqplot ('prospect_by_sales_stage', [data], { 
			seriesDefaults: {
				// Make this a pie chart.
				renderer: $.jqplot.PieRenderer, 
				rendererOptions: {
					// Put data labels on the pie slices.
					// By default, labels show the percentage of the slice.
					showDataLabels: true
				}
			}, 
			legend: { show:true, location: 'e' }
		});
		
		window.onresize = function(event) {
			plot1.replot();
		};
	<?php 
	} else { ?>
		$("#prospect_by_sales_stage").append('<p>'+NO_DATA_FOR_GRAPH+'</p>');
	<?php 
	} ?>
});
</script>
