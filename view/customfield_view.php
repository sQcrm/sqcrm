<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Custom field page
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div id="message"></div>
	<div class="row">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="col-md-9">
			<div class="box_content">
				<ol class="breadcrumb">
					<li class="active"><?php echo _('Settings')?></li>
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"customfield")?>"><?php echo _('Custom Fields')?></a></li>
				</ol>
				<p class="lead"><?php echo _('Manage webform custom fields')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div class="row">
					<div class="col-md-12">
						<h2><small><?php echo _('Custom Fields');?></small></h2>
						<div class="row">
							<div class="col-xs-4">
								<select name="cf_module_selector" id="cf_module_selector" class="form-control input-sm">
								<?php
								foreach ($module_with_customfield as $idmodule=>$val) {
									$select = '';
									if ($idmodule == $cf_module) $select = "SELECTED";
									echo '<option value="'.$idmodule.'" '.$select.'>'.$val["label"].'</option>';
								}
								?>
								</select>
							</div>
							<div class="col-xs-8">
								<a href="#" class="btn btn-primary" onclick="add_custom_field('<?php echo $module;?>','customfield');">
								<i class="glyphicon glyphicon-plus"></i><?php echo _('Add New')?></a>
								<a href="<?php echo NavigationControl::getNavigationLink($module,"customfield_mapping")?>" class="btn btn-primary" id="map_custom_field">
								<i class="glyphicon glyphicon-edit"></i><?php echo _('Map Custom Fields')?></a>
							</div>
						</div>
						<br />
					</div>
				</div>
				<div class="clear_float"></div>
				<?php 
				require("customfield_entry_view.php");
				?>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="delete_confirm">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3><span class="label label-warning"><?php echo _('WARNING')?></span></h3>
			</div>
			<div class="modal-body">
				<?php echo _('Are you sure you want to delete the custom field.');?>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
				<input type="submit" class="btn btn-primary" value="<?php echo _('Delete')?>"/>
			</div>
		</div>
	</div>
</div>

<script>

function delete_custom_field(idmodule,idfields) {
	$("#delete_confirm").modal('show');
	$("#delete_confirm .btn-primary").off('click');
	$("#delete_confirm .btn-primary").click(function() {
		$("#delete_confirm").modal('hide');
		$.ajax({
			type: "POST",
			<?php
			$e_del = new Event("CustomFields->eventAjaxDeleteCustomField");
			$e_del->setEventControler("/ajax_evctl.php");
			$e_del->addParam('idmodule',$cf_module);
			$e_del->setSecure(false);
			?>
			url: "<?php echo $e_del->getUrl(); ?>&idfields="+idfields,
			success:  function(html) {
				ret_data = html.trim();
				if (ret_data == '0') {
					var err_element = '<div class="alert alert-error sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
					var err_msg = err_element+'<strong>'+CUSTOM_FIELD_DELETE_NOT_ALLOWED+'</strong></div>';
					$("#message").html(err_msg);
					$("#message").show();
				} else {
					$.ajax({ 
						type: "GET",
						url: "customfield",
						data : "ajaxreq="+true+"&cmid="+idmodule,
						success: function(result) { 
							$('#cf_entry').html(result);
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