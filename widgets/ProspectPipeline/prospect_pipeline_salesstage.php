<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Home page prospect pipeline by sales stage graph
* @author Abhik Chakraborty
*/
include_once(BASE_PATH.'/widgets/ProspectPipeline/ProspectPipeline.class.php') ;
$prospect_pipeline = new ProspectPipeline() ;
$data_3 = $prospect_pipeline->get_prospect_pipeline_by_sales_stage_graph();
$crm_global_settings = new CRMGlobalSettings();
$currency = $crm_global_settings->get_setting_data_by_name('currency_setting');
$currency_data = json_decode($currency,true);
?>
<script src="/js/plugins/jqplot/plugins/jqplot.barRenderer.min.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.pointLabels.min.js"></script>
<script type="text/javascript" src="/js/jquery/plugins/accounting.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var currency_symbol = '<?php echo $currency_data["currency_sysmbol"] ;?>';
	var decimal_point = '<?php echo $currency_data["decimal_point"] ;?>';
	var decimal_symbol = '<?php echo $currency_data["decimal_symbol"] ;?>';
	var thousand_seperator = '<?php echo $currency_data["thousand_seperator"] ;?>';
	var currency_symbol_position = '<?php echo $currency_data["currency_symbol_position"] ;?>';
  
	tickFormatter = function(format,val) {
		var formatted_amt = accounting.formatMoney(val, "", decimal_point, thousand_seperator, decimal_symbol); 
		if (currency_symbol_position == 'left') {
			formatted_amt = currency_symbol+' '+formatted_amt;
		} else if (currency_symbol_position == 'right') {
			formatted_amt = formatted_amt+' '+currency_symbol;
		}
		return formatted_amt;
	}
	<?php
    if (sizeof($data_3) > 0) {
	?>
	$.jqplot.config.enablePlugins = true;
		var s1 = [
			<?php
			$cnt = 0 ;
			foreach ($data_3 as $key=>$val) {
				echo $val;
				if ($cnt != sizeof($data_3)-1) { echo ","; }
				$cnt++;
			}
			?>
		];
        // Can specify a custom tick Array.
        // Ticks should match up one for each y value (category) in the series.
        var ticks = [
			<?php
			$cnt = 0 ;
			foreach ($data_3 as $key=>$val) {
				echo "'".$key."'";
				if ($cnt != sizeof($data_3)-1) { echo ","; }
				$cnt++;
			}
			?>
        ];
        
        var plot2 = $.jqplot('prospect_pipeline_by_sales_stage', [s1], {
			// The "seriesDefaults" option is an options object that will
            // be applied to all series in the chart.
            animate:!$.jqplot.use_excanvas,
            seriesDefaults: {
				renderer:$.jqplot.BarRenderer,
                rendererOptions:{ varyBarColor:true},
                pointLabels: {show:true,location: 's',escapeHTML:false}
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
                    pad: 1.05,
                    tickOptions: {formatter: tickFormatter,escapeHTML:false}
                }
            },
        });
        window.onresize = function(event) {
			plot2.destroy();
			plot2.replot();
        }
        /*$(window).resize(function() {
			plot2.replot( { resetAxes: true } );
		});*/
    <?php 
    } else { ?>
		$("#prospect_pipeline_by_sales_stage").append('<p>'+NO_DATA_FOR_GRAPH+'</p>');
    <?php 
    } ?>
});
</script>