<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Pick List/ Multi select combo values manage page
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
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"picklist")?>"><?php echo _('Pick List')?></a></li>
				</ol>
				<p class="lead"><?php echo _('Manage Pick List / Multi-select')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-xs-4">
								<h2><small><?php echo _('Pick List and Multi-select');?></small></h2>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-4">
								<select name="pck_mulsel_module_selector" id="pck_mulsel_module_selector" class="form-control input-sm">
									<?php
									foreach ($modules_info as $idmodule=>$val) {
										if (in_array($idmodule,$ignore_modules)) continue ;
										$select = '';
										if ($idmodule == $cf_module) $select = "SELECTED";
										echo '<option value="'.$idmodule.'" '.$select.'>'.$val["label"].'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="clear_float"></div>
						<?php 
						require("picklist_entry_view.php");
						?>
					</div>
				</div>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>
<script>
$("#pck_mulsel_module_selector").change( function() {
	var mid = $("#pck_mulsel_module_selector").val() ;
	//var mid = $(this).attr('value');
	$.ajax({
		type: "GET",
		url: "picklist_list",
		data : "cmid="+mid+"&ajaxreq="+true,
		success: function(result) { 
			$('#pck_mulsel_entry').html(result) ;
		}
	});
});

function edit_pick_mulsel(module,idfields,referrar,referrar_module_id) {
	var href = '/popups/edit_pick_mulsel_modal?&m='+module+'&classname=CRMFields&sqrecord='+idfields+'&referrar='+referrar+'&referrar_module_id='+referrar_module_id;
    if (href.indexOf('#') == 0) { 
		$(href).modal('open');
    } else {
		$.get(href, function(data) {
			//ugly heck to prevent the content getting append when opening the same modal multiple time
			$("#edit_custom_field").html(''); 
			$("#edit_custom_field").attr("id","ugly_heck");
			$('<div class="modal fade" tabindex="-1" role="dialog" id="edit_custom_field">' + data + '</div>').modal();
		}).success(function() { $('input:text:visible:first').focus(); });
    }
}
</script>