<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report view
* @author Abhik Chakraborty
*/
?>
<link rel="stylesheet" type="text/css" href="/js/plugins/jqplot/jquery.jqplot.min.css" />
<script src="/js/plugins/jqplot/jquery.jqplot.min.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.barRenderer.min.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.pointLabels.min.js"></script>
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
				<div class="left_300" id="report_user_filter">
					<?php echo _('User');?><br />
					<select name="report_user_filter_runtime" id="report_user_filter_runtime">
						<option value="0"><?php echo _('All');?></option>
						<?php
						if (is_array($user_list) && count($user_list) >0) {
							foreach($user_list as $key=>$val) {
								$selected = ($selected_user == $key ? 'SELECTED':'');
								echo '<option value="'.$key.'" '.$selected.'>'.$val['firstname'].' '.$val['lastname'].' ('.$val['user_name'].')</option>';
							}
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
		<div class="span12">
			<div class="datadisplay-outer">
				<p><strong><?php echo _('Prospect Forecast 30-60-90 Days'); ?></strong></p>
				<div id="c1">
				</div>
			</div>
		</div>
	</div>
	<div class="clear_float"></div>
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
				<div class="left_300"  id="">
					<p><strong><?php echo _('Detailed report'); ?></strong></p>
				</div>
				<table cellpadding="0" cellspacing="0" border="0" class="datadisplay" id="sqcrmlist">
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
					if ($prospect_forecast->getNumRows() > 0) {
						while ($prospect_forecast->next()) {
							echo '<tr>' ;
							foreach ($fields_info as $fields=>$info) {
								$fieldobject = 'FieldType'.$info["field_type"];
								$val = $do_crm_fields->display_field_value($prospect_forecast->$fields,$info["field_type"],$fieldobject,$do_crm_fields,5) ;
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
	
	<?php
	if (count($prospect_forecast_data) > 0) {
	?>
	$.jqplot.config.enablePlugins = true;
	var s1 = [
		<?php
		$cnt = 0 ;
		$record_count = count($prospect_forecast_data) ;
		foreach($prospect_forecast_data as $key=>$val) { 
			echo $val;
			if ($cnt != $record_count-1) echo ",";
			$cnt++ ; 
		}
		?>
	];
	
	var ticks = [
		<?php
			$cnt = 0 ;
			$record_count = count($prospect_forecast_data) ;
			foreach($prospect_forecast_data as $key=>$val) { 
				echo "'".$key."'";
				if ($cnt != $record_count-1) echo ",";
				$cnt++;
			}
		?>
	] ;
	
	var plot1 = $.jqplot('c1', [s1], {
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
	<?php
	} else { ?>
		$("#c1").append('<p>'+NO_DATA_FOR_GRAPH+'</p>');
	<?php 
	}
	?>
	
	oTable = $('#sqcrmlist').dataTable({
		"paging":   false,
        "info":     false,
        "bSort": false,
        "bFilter" : false,
        "aaSorting": [],
        dom: 'T<"clear">lfrtip',
        tableTools: {
			"sSwfPath": "/js/plugins/DataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
		}
	});      
});
</script>