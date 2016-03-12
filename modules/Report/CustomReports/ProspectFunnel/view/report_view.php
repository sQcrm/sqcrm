<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report view
* @author Abhik Chakraborty
*/
?>
<link rel="stylesheet" type="text/css" href="/js/plugins/jqplot/jquery.jqplot.min.css" />
<script src="/js/plugins/jqplot/jquery.jqplot.min.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.funnelRenderer.min.js"></script>
<script type="text/javascript" src="/js/jquery/plugins/accounting.js"></script>
<link href="/js/plugins/DataTables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<script type="text/javascript" src="/js/plugins/DataTables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<div class="container-fluid">
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
		<div class="span6">
			<div class="datadisplay-outer">
				<p><?php echo _('Prospect Funnel By Stage and Amount');?> <?php echo ($funnel_data_by_amount["grand_total"] > 0 ? _(' - Totalling ').FieldType30::display_value($funnel_data_by_amount["grand_total"]):'');?></p>
				<div id="c1">
				</div>
			</div>
		</div>
		
		<div class="span6">
			<div class="datadisplay-outer" >
				<p><?php echo _('Prospect Funnel By Stage and Number');?> <?php echo ($funnel_data_by_no["grand_total"] > 0 ? _(' - Totalling ').$funnel_data_by_no["grand_total"]:'');?></p>
				<div id="c2"></div>
			</div>
		</div>
	</div>
	<div class="clear_float"></div>
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
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
					if ($prospect_funnel->getNumRows() > 0) {
						while ($prospect_funnel->next()) {
							echo '<tr>' ;
							foreach ($fields_info as $fields=>$info) {
								$fieldobject = 'FieldType'.$info["field_type"];
								$val = $do_crm_fields->display_field_value($prospect_funnel->$fields,$info["field_type"],$fieldobject,$do_crm_fields,5) ;
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
			display_js_error('Please select a date filter before submit','js_errors');
			return false;
		}
		
		if ($("#report_date_filter_type_runtime").val() == '1') {
			if ($("#report_date_start_runtime").val() == '' || $("#report_date_end_runtime").val() == '') {
				display_js_error('Please select a start and end date before submit','js_errors');
				return false;
			}
		}
	});
	
	var currency_symbol = '<?php echo $currency_data["currency_sysmbol"] ;?>';
	var decimal_point = '<?php echo $currency_data["decimal_point"] ;?>';
	var decimal_symbol = '<?php echo $currency_data["decimal_symbol"] ;?>';
	var thousand_seperator = '<?php echo $currency_data["thousand_seperator"] ;?>';
	var currency_symbol_position = '<?php echo $currency_data["currency_symbol_position"] ;?>';
	
	function formatFunnelAmount(amountArray) {
		var returnArray = [] ;
		amountArray.forEach(function(val, index, array) {
			var formatted_amt = accounting.formatMoney(val, "", decimal_point, thousand_seperator, decimal_symbol); 
			if (currency_symbol_position == 'left') {
				formatted_amt = currency_symbol+' '+formatted_amt;
			} else if (currency_symbol_position == 'right') {
				formatted_amt = formatted_amt+' '+currency_symbol;
			}
			returnArray[index] = formatted_amt ;
		}) ;
		return returnArray ;
	}
	<?php
	if (count($funnel_data_by_amount["data"]) > 0) {
	?>
	$.jqplot.config.enablePlugins = true;
	var s1 = [
		<?php
		$cnt = 0 ;
		$record_count = count($funnel_data_by_amount["data"]) ;
		foreach($funnel_data_by_amount["data"] as $key=>$val) { ?>
			[<?php echo '"'.$val["sales_stage"].'"'?>,<?php echo $val["amount"];?>]<?php if ($cnt != $record_count-1) echo ","; ?>
			<?php
			$cnt++ ; 
		}
		?>
	];
	
	var funnelAmount = [
		<?php
			$cnt = 0 ;
			$record_count = count($funnel_data_by_amount["data"]) ;
			foreach($funnel_data_by_amount["data"] as $key=>$val) { 
				echo $val["amount"];
				if ($cnt != $record_count-1) echo ",";
				$cnt++;
			}
		?>
	] ;
	
	var plot1 = $.jqplot('c1', [s1], {
		legend:{
			"show":true,"location":"w"
		},
		seriesDefaults: {
			renderer:$.jqplot.FunnelRenderer,
			rendererOptions:{
				sectionMargin: 4,
				widthRatio: 0.3,
				showDataLabels: true,
				dataLabels:formatFunnelAmount(funnelAmount)
			}
		}
	});
	<?php
	} else { ?>
		$("#c1").append('<p>'+NO_DATA_FOR_GRAPH+'</p>');
	<?php 
	}
	?>
	
	<?php
	if (count($funnel_data_by_no["data"]) > 0) {?>
	$.jqplot.config.enablePlugins = true;
	var s2 = [
		<?php
		$cnt = 0 ;
		$record_count = count($funnel_data_by_no["data"]) ;
		foreach($funnel_data_by_no["data"] as $key=>$val) { ?>
			[<?php echo '"'.$val["sales_stage"].'"'?>,<?php echo $val["total_vol"];?>]<?php if ($cnt != $record_count-1) echo ","; ?>
			<?php
			$cnt++ ; 
		}
		?>
	];
	var plot2 = $.jqplot('c2', [s2], {
		legend:{
			"show":true,"location":"w"
		},
		seriesDefaults: {
			renderer:$.jqplot.FunnelRenderer,
			rendererOptions:{
				sectionMargin: 4,
				widthRatio: 0.3,
				showDataLabels: true,
				dataLabels:'value'
			}
		}
	});
	<?php
	} else { ?>
		$("#c2").append('<p>'+NO_DATA_FOR_GRAPH+'</p>');
	<?php 
	}
	?>
	
	oTable = $('#sqcrmlist').dataTable({
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