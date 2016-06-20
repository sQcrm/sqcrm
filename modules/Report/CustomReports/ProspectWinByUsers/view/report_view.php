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
<script type="text/javascript" src="/js/jquery/plugins/accounting.js"></script>
<link href="/js/plugins/DataTables/datatables.min.css" rel="stylesheet">
<link href="/js/plugins/DataTables/Buttons-<?php echo DATATABLE_BUTTONS_VERSION;?>/css/buttons.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="/js/plugins/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="/js/plugins/DataTables/Buttons-<?php echo DATATABLE_BUTTONS_VERSION;?>/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="/js/plugins/DataTables/Buttons-<?php echo DATATABLE_BUTTONS_VERSION;?>/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="/js/plugins/DataTables/Buttons-<?php echo DATATABLE_BUTTONS_VERSION;?>/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="/js/plugins/DataTables/Buttons-<?php echo DATATABLE_BUTTONS_VERSION;?>/js/buttons.print.min.js"></script>
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
						$selected = ($date_filter_type == $key ? 'SELECTED':'');
						echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
					}
					?>
					</select>
				</div>
				<div class="left_300" <?php echo $date_range_display;?> id="report_date_filter_start">
					<?php echo _('Date start');?><br />
					<?php 
					$report_date_start = (isset($_GET['report_date_start_runtime']) ? $_GET['report_date_start_runtime']:'');
					echo FieldType9::display_field('report_date_start_runtime',$report_date_start);
					?>
				</div>
				<div class="left_300" <?php echo $date_range_display;?> id="report_date_filter_end">
					<?php echo _('Date end');?><br />
					<?php 
					$report_date_end = (isset($_GET['report_date_start_runtime']) ? $_GET['report_date_start_runtime']:'');
					echo FieldType9::display_field('report_date_end_runtime',$report_date_end);
					?>
				</div>
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
				<p><strong><?php echo _('Prospect Wins');?> <?php echo ($prospect_win_by_amount["grand_total"] > 0 ? _(' - Totalling ').FieldType30::display_value($prospect_win_by_amount["grand_total"]):'');?></strong></p>
				<div id="c1">
				</div>
			</div>
		</div>
	</div>
	<div class="clear_float"></div>
	<?php
	if (count($group_users) > 0) {
	?>
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
				<p><strong><?php echo _('Members in groups');?></strong></p>
				<?php
				foreach ($group_users as $key=>$val) {
					echo '<strong>'.$key.'</strong> :: ';
					foreach ($val as $k=>$u) {
						echo $u['user_name'].' ('.$u['firstname'].' '.$u['lastname'].' )&nbsp;&nbsp;&nbsp;';
					}
					echo '<br />';
				}
				?>
			</div>
		</div>
	</div>
	<div class="clear_float"></div>
	<?php
	}
	?>
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
					if ($prospect_win->getNumRows() > 0) {
						while ($prospect_win->next()) {
							echo '<tr>' ;
							foreach ($fields_info as $fields=>$info) {
								$fieldobject = 'FieldType'.$info["field_type"];
								$val = $do_crm_fields->display_field_value($prospect_win->$fields,$info["field_type"],$fieldobject,$prospect_win,5,false) ;
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
	$("#report_date_filter_type_runtime").change(function() {
		var date_filter_type = $(this).val();
		if (date_filter_type != 1) {
			$("#report_date_filter_start").hide();
			$("#report_date_filter_end").hide();
		} else {
			$("#report_date_filter_start").show();
			$("#report_date_filter_end").show();
		}
	});
	
	$("#filter_run_time").submit(function() {
		if ($("#report_date_field_runtime").val() == '0') {
			display_js_error(REPORT_SELECT_DATE_FILTER,'js_errors');
			return false;
		}
		
		if ($("#report_date_filter_type_runtime").val() == '1') {
			if ($("#report_date_start_runtime").val() == '' || $("#report_date_end_runtime").val() == '') {
				display_js_error(REPORT_SELECT_START_END_DATE,'js_errors');
				return false;
			}
		}
	});
	
	var currency_symbol = '<?php echo $currency_data["currency_sysmbol"] ;?>';
	var decimal_point = '<?php echo $currency_data["decimal_point"] ;?>';
	var decimal_symbol = '<?php echo $currency_data["decimal_symbol"] ;?>';
	var thousand_seperator = '<?php echo $currency_data["thousand_seperator"] ;?>';
	var currency_symbol_position = '<?php echo $currency_data["currency_symbol_position"] ;?>';
	
	tickFormatter = function(format,val) {
		//return val > 999 ? (val/1000).toFixed(1) + 'k' : val
		var formatted_amt = accounting.formatMoney(val, "", decimal_point, thousand_seperator, decimal_symbol); 
		if (currency_symbol_position == 'left') {
			formatted_amt = currency_symbol+' '+formatted_amt;
		} else if (currency_symbol_position == 'right') {
			formatted_amt = formatted_amt+' '+currency_symbol;
		}
		return formatted_amt;
	}
	
	<?php
	if (count($prospect_win_by_amount["data"]) > 0) {
	?>
	$.jqplot.config.enablePlugins = true;
		var s1 = [
		<?php
		$cnt = 0 ;
		foreach ($prospect_win_by_amount["data"] as $key=>$val) {
			echo $val;
			if ($cnt != count($prospect_win_by_amount["data"])-1) { echo ","; }
				$cnt++;
		}
		?>
		];
        // Can specify a custom tick Array.
        // Ticks should match up one for each y value (category) in the series.
		var ticks = [
			<?php
			$cnt = 0 ;
			foreach ($prospect_win_by_amount["data"] as $key=>$val) {
				echo "'".$key."'";
				if ($cnt != count($prospect_win_by_amount["data"])-1) { echo ","; }
				$cnt++;
			}
			?>
		];
        
		var plot1 = $.jqplot('c1', [s1], {
			// The "seriesDefaults" option is an options object that will
            // be applied to all series in the chart.
			animate:!$.jqplot.use_excanvas,
			height:<?php echo $graph_height;?>,
			seriesDefaults: {
				renderer:$.jqplot.BarRenderer,
				rendererOptions:{ varyBarColor:true,barDirection: 'horizontal'},
				pointLabels: {show:true,location: 'w',escapeHTML:false}
			},
			axes: {
				// Use a category axis on the x axis and use our custom ticks.
				yaxis: {
					renderer: $.jqplot.CategoryAxisRenderer,
					ticks: ticks
				},
				// Pad the y axis just a little so bars can get close to, but
				// not touch, the grid boundaries.  1.2 is the default padding.
				xaxis: {
					pad: 1.05,
					tickOptions: {formatter: tickFormatter,escapeHTML:false}
				}
			},
		});
		window.onresize = function(event) {
			plot1.destroy();
			plot1.replot();
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
		"bFilter" : false,
		"aaSorting": [],
		dom: 'Bfrtip',
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		]
	});      
});
</script>