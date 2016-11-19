<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* datahistory fields page
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="col-md-9">
			<div class="box_content">
				<ol class="breadcrumb">
					<li class="active"><?php echo _('Settings')?></li>
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"datahistory_settings")?>"><?php echo _('Data History')?></a></li>
				</ol>
				<p class="lead"><?php echo _('Manage data history for the modules')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div id="message"></div>
				<div class="row">
					<div class="col-md-12">
						<h2><small><?php echo _('Choose fields for history tracking');?></small></h2>
						<div class="row">
							<div class="col-xs-6">
								<select name="dh_module_selector" id="dh_module_selector" class="form-control input-sm">
									<?php
									foreach ($datahistory_modules as $key=>$val) {
										$select = '';
										if ($val["idmodule"] == $dh_module) $select = "SELECTED";
											echo '<option value="'.$val["idmodule"].'" '.$select.'>'.$val["module_label"].'</option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="clear_float"></div>
						<div id="dh_entry">
							<?php 
							require("datahistory_fields_entry_view.php");
							?>
						</div>
					</div>
				</div>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#dh_entry').on('click','#save-data-history', function(e) {
		var mid = $("#dh_module_selector").val();
		var dh_fields = $("#dh_entry input:checkbox:checked").map(function() {
			return $(this).val();
		}).get();
		$('#dh_entry #dhf_settings').html('<img src="/themes/images/ajax-loader1.gif" border="0" />'); //Including a preloader, it loads into the div tag with id uploader
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("DataHistoryFieldOption->eventAjaxSaveHistoryFields");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>",
			data: "datahistory_fields="+dh_fields+"&mid="+mid,
			success: function(result) { 
				var succ_element = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
				var succ_msg = succ_element+'<strong>'+result+'</strong></div>';
				$("#dh_entry #message").html(succ_msg);
				var submit_btn = '<input type="button" class="btn btn-primary" id="save-data-history" value="<?php echo _('Save');?>"/>';
				$("#dh_entry #dhf_settings").html(submit_btn);
				return false;
			}
		});
    });
    
    $("#dh_module_selector").change( function() {
		var mid = $("#dh_module_selector").val() ;
		$.ajax({
			type: "GET",
			url: "datahistory_settings",
			data : "cmid="+mid+"&ajaxreq="+true,
			success: function(result) { 
				$('#dh_entry').html(result) ;
			}
		});
	});
});
</script>