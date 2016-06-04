<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report view
* @author Abhik Chakraborty
*/
?>
<link rel="stylesheet" type="text/css" href="/js/plugins/jqplot/jquery.jqplot.min.css" />
<script src="/js/plugins/jqplot/jquery.jqplot.min.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.logAxisRenderer.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.canvasAxisTickRenderer.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.highlighter.js"></script>
<script type="text/javascript" src="/js/jquery/plugins/accounting.js"></script>
<link href="/js/plugins/DataTables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<script type="text/javascript" src="/js/plugins/DataTables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<div class="container-fluid">
	<?php
		echo $breadcrumb ;
	?>
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
				<form id="filter_run_time">
				<input type="hidden" name="runtime" value="1">
				<input type="hidden" name="path" value="<?php echo $_GET['path']?>">
				<input type="hidden" name="resource" value="<?php echo $_GET['resource']?>">
				<div class="left_250" style="margin-left:3px;">
				<?php echo _('Date Filter Type');?><br />
					<select name="report_date_filter_type_runtime" id="report_date_filter_type_runtime">
					<?php
					foreach ($date_filter_options as $key=>$val) {
						if (!in_array($key,$allowed_date_filter)) continue;
						$selected = ($date_filter_type == $key ? 'SELECTED':'');
						echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
					}
					?>
					</select>
				</div>
				<div class="clear_float"></div>
				<div class="left_100" style="margin-left:3px;">
					<input type="submit" class="btn btn-primary" id="" value="<?php echo _('generate');?>"/>
				</div>
				<br />
			</form>
			</div>
		</div>
	</div>
	<div class="clear_float"></div>
	<div class="row-fluid">
		<div class="span6">
			<div class="datadisplay-outer">
				<div id="c1">
				</div>
			</div>
		</div>
		
		<div class="span6">
			<div class="datadisplay-outer" >
				<div id="c2">
				</div>
			</div>
		</div>
	</div>
	<div class="clear_float"></div>
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
				<div class="left_300"  id="">
					<p><strong><?php echo $title.' :: '.$series_label['current']; ?></strong></p>
				</div>
				<table cellpadding="0" cellspacing="0" border="0" class="datadisplay" id="sqcrmlist1">
					<thead>
						<tr>
						<?php
						foreach ($fields_info as $key=>$info) {
							echo '<th width="10%">'.$info["field_label"].'</th>';
						}
						?>
						</tr>
					</thead>
					<?php
					if ($detail_data_current->getNumRows() > 0) {
						while ($detail_data_current->next()) {
							echo '<tr>' ;
							foreach ($fields_info as $fields=>$info) {
								$fieldobject = 'FieldType'.$info["field_type"];
								$val = $do_crm_fields->display_field_value($detail_data_current->$fields,$info["field_type"],$fieldobject,$detail_data_current,15,false) ;
								echo '<td class="">'.$val.'</td>';
							}
							echo '</tr>' ;
						}
					}
					?>
				</table>
			</div>
		</div>
	</div>
	
	<div class="clear_float"></div>
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
				<div class="left_300"  id="">
					<p><strong><?php echo $title.' :: '.$series_label['previous']; ?></strong></p>
				</div>
				<table cellpadding="0" cellspacing="0" border="0" class="datadisplay" id="sqcrmlist2">
					<thead>
						<tr>
						<?php
						foreach ($fields_info as $key=>$info) {
							echo '<th width="10%">'.$info["field_label"].'</th>';
						}
						?>
						</tr>
					</thead>
					<?php
					if ($detail_data_previous->getNumRows() > 0) {
						while ($detail_data_previous->next()) {
							echo '<tr>' ;
							foreach ($fields_info as $fields=>$info) {
								$fieldobject = 'FieldType'.$info["field_type"];
								$val = $do_crm_fields->display_field_value($detail_data_previous->$fields,$info["field_type"],$fieldobject,$detail_data_previous,15,false) ;
								echo '<td class="">'.$val.'</td>';
							}
							echo '</tr>' ;
						}
					}
					?>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
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
	
	$.jqplot._noToImageButton = true;
	var currentData = [
		<?php
		$total_records = count($current_range_data);
		$cnt = 0;
		foreach ($current_range_data as $key=>$val) {
			echo "['".$key."',".$val."]";
			if ($cnt != ($total_records-1)) { echo ","; }
			$cnt++;
		}	
		?>
	];
	
	var previousData = [
		<?php
		$total_records = count($previous_range_data);
		$cnt = 0;
		foreach ($previous_range_data as $key=>$val) {
			echo "['".$key."',".$val."]";
			if ($cnt != ($total_records-1)) { echo ","; }
			$cnt++;
		}	
		?>
	];
    var plot1 = $.jqplot("c1", [currentData], {
        seriesColors: ["rgba(78, 135, 194, 0.7)"],
        title: '<?php echo $title?>',
        height:<?php echo $graph_height;?>,
        highlighter: {
            show: true,
            sizeAdjust: 1,
            tooltipOffset: 9
        },
        grid: {
            background: 'rgba(57,57,57,0.0)',
            drawBorder: false,
            shadow: false,
            gridLineColor: '#666666',
            gridLineWidth: .2
        },
        legend: {
            show: true,
            location: 'ne'
        },
        seriesDefaults: {
            rendererOptions: {
                smooth: true,
                animation: {
                    show: true
                }
            },
            showMarker: false
        },
        series: [
            {
                fill: false,
                label: '<?php echo $series_label['current']?>',
            }
        ],
        axesDefaults: {
            rendererOptions: {
                baselineWidth: 1.5,
                baselineColor: '#444444',
                drawBaseline: false
            }
        },
        axes: {
            xaxis: {
                renderer: $.jqplot.DateAxisRenderer,
                tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                tickOptions: {
                    formatString: "%b %e",
                    angle: -30,
                    textColor: '#190707'
                },
                min: "<?php echo $current_start_date;?>",
                max: "<?php echo $current_end_date;?>",
                tickInterval: "<?php echo $interval;?>",
                drawMajorGridlines: false
            },
            yaxis: {
                renderer: $.jqplot.LogAxisRenderer,
                pad: 0,
                rendererOptions: {
                    minorTicks: 1
                },
                tickOptions: {
                    formatter: tickFormatter,
                    escapeHTML:false,
                    showMark: false
                }
            }
        }
    });
    
     var plot2 = $.jqplot("c2", [previousData], {
        seriesColors: ["rgb(143,188,143)"],
        title: '<?php echo $title?>',
        height:<?php echo $graph_height;?>,
        highlighter: {
            show: true,
            sizeAdjust: 1,
            tooltipOffset: 9
        },
        grid: {
            background: 'rgba(57,57,57,0.0)',
            drawBorder: false,
            shadow: false,
            gridLineColor: '#666666',
            gridLineWidth: .2
        },
        legend: {
            show: true,
            location: 'ne'
        },
        seriesDefaults: {
            rendererOptions: {
                smooth: true,
                animation: {
                    show: true
                }
            },
            showMarker: false
        },
        series: [
            {
                fill: false,
                label: '<?php echo $series_label['previous']?>',
            }
        ],
        axesDefaults: {
            rendererOptions: {
                baselineWidth: 1.5,
                baselineColor: '#444444',
                drawBaseline: false
            }
        },
        axes: {
            xaxis: {
                renderer: $.jqplot.DateAxisRenderer,
                tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                tickOptions: {
                    formatString: "%b %e",
                    angle: -30,
                    textColor: '#190707'
                },
                min: "<?php echo $previous_start_date;?>",
                max: "<?php echo $previous_end_date;?>",
                tickInterval: "<?php echo $interval;?>",
                drawMajorGridlines: false
            },
            yaxis: {
                renderer: $.jqplot.LogAxisRenderer,
                pad: 0,
                rendererOptions: {
                    minorTicks: 1
                },
                tickOptions: {
                    formatter: tickFormatter,
                    escapeHTML:false,
                    showMark: false
                }
            }
        }
    });
    
    var oTable1 = $('#sqcrmlist1').dataTable({
		"paging":   false,
        "info":     false,
        "bFilter" : false,
        "aaSorting": [],
        dom: 'T<"clear">lfrtip',
        tableTools: {
			"sSwfPath": "/js/plugins/DataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
		}
	});   
	
	 var oTable2 = $('#sqcrmlist2').dataTable({
		"paging":   false,
        "info":     false,
        "bFilter" : false,
        "aaSorting": [],
        dom: 'T<"clear">lfrtip',
        tableTools: {
			"sSwfPath": "/js/plugins/DataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
		}
	});      
});
</script>