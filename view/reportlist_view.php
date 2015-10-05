<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Report list view
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
				<div id="message"></div>
				<div>
					<!-- add new button -->
					<?php 
					if ($_SESSION["do_crm_action_permission"]->action_permitted('add',$module_id) === true) {
					?>
					<a href="/modules/<?php echo $module?>/add" class="btn btn-primary btn-mini bs-prompt">
					<i class="icon-white icon-plus"></i> <?php echo _('add new');?>
					</a>
					<?php 
					} ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<div class="row-fluid">
				<?php
				foreach ($left as $key=>$val) {
					$folder_name =  $val["name"];
				?>
				<div class="datadisplay-outer" id="report_folder_<?php echo $val["idreport_folder"];?>">
					<?php
					$reports = $do_report->get_reports_by_folder($val["idreport_folder"]);
					require('view/reportlist_view_entry.php');
					?>
				</div>
				<?php 
				} ?>	
			</div>
		</div>	
		<div class="span6">
			<div class="row-fluid">
				<?php
				foreach ($right as $key=>$val) {
					$folder_name =  $val["name"];
				?>
				<div class="datadisplay-outer" id="report_folder_<?php echo $val["idreport_folder"];?>">
					<?php
					$reports = $do_report->get_reports_by_folder($val["idreport_folder"]);
					require('view/reportlist_view_entry.php');
				?>
				</div>
				<?php 
				} ?>
			</div>
		</div>
	</div>
</div>
<div class="modal hide" id="report_delete_confirm">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge badge-warning"><?php echo _('WARNING!');?></span>
	</div>
	<div class="modal-body">
		<?php echo _('Are you sure you want to delete the records.');?>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
		<input type="submit" class="btn btn-primary" value="<?php echo _('Delete')?>"/>
	</div>
</div>
<script>
function del_report(idreport,report_folder,folder_name) {
	$("#report_delete_confirm").modal('show');
	$("#report_delete_confirm .btn-primary").click(function() {
		$("#report_delete_confirm").modal('hide');
		$.ajax({
			type: "POST",
			<?php
			$e_del_single = new Event("CRMDeleteEntity->eventAjaxDeleteSingleEntity");
			$e_del_single->setEventControler("/ajax_evctl.php");
			$e_del_single->addParam('module',$module);
			$e_del_single->addParam('referrer','list');
			$e_del_single->setSecure(false);
			?>
			url: "<?php echo $e_del_single->getUrl(); ?>&sqrecord="+idreport,
			success:  function(html) {
				ret_data = html.trim();
				if (ret_data == '0') {
					var err_element = '<div class="alert alert-error sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
					var err_msg = err_element+'<strong>'+UNAUTHORIZED_DELETE+'</strong></div>';
					$("#message").html(err_msg);
					$("#message").show();
				} else {
					$.ajax({
						type: "GET",
						url: "list",
						data : "ajaxreq="+true+"&folderid="+report_folder+"&foldername="+folder_name,
						success: function(result) { 
							var folder_block = 'report_folder_'+report_folder ;
							$('#'+folder_block).html(result);
							var succ_element = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
							var succ_msg = succ_element+'<strong>'+DATA_DELETED_SUCCESSFULLY+'</strong></div>';
							$("#message").html(succ_msg);
							$("#message").show();
						}
					});
				}
			}
		});
	});
}
</script>