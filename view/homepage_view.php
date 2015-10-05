<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Home page view
* @author Abhik Chakraborty
*/  
?>
<!-- load the home page graph library -->
<link rel="stylesheet" type="text/css" href="/js/plugins/jqplot/jquery.jqplot.min.css" />
<script src="/js/plugins/jqplot/jquery.jqplot.min.js"></script>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span8">
			<div class="row-fluid">
				<?php
				if (is_array($left_block) && count($left_block) > 0) {
					foreach ($left_block as $key=>$val) {
						$component_name = $val["component_name"] ;
						if ($val["id"] == 3) {
							$data_3 = $home_page_graph->get_prospect_pipeline_by_sales_stage_graph();
							require_once('view/homepage_prospect_pipeline_salesstage_view.php');
						} elseif ($val["id"] == 5) {
							$data_4 = $home_page_graph->get_prospect_by_sales_stage_graph_data();
							require_once('view/homepage_prospect_by_salesstage_view.php');
						} elseif ($val["id"] == 4) {
							$data_5 = $home_page_graph->get_leads_by_status_graph();
							require_once('view/homepage_leads_by_status_view.php');
						}
					}
				}
				?>
			</div><!--/row-->
		</div><!--/span-->
		<div class="span4" style="margin-left:10px;">
			<div class="row-fluid">
				<?php
				if (is_array($right_block) && count($right_block) > 0) {
					foreach ($right_block as $key=>$val) {
						$component_name = $val["component_name"] ;
						if ($val["id"] == 1) {
							require_once('view/homepage_livefeed_view.php');
						} elseif ($val["id"] == 2) {
							require_once('view/homepage_calls_and_events.php');
						}
					}
				}
				?>
			</div>
		</div>
	</div><!--/row-->
</div>