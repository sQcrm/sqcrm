<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Pick List/ Multi select combo values manage page
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"picklist")?>"><?php echo _('Pick List')?></a></h3>
				<p><?php echo _('Manage Pick List / Multi-select')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Pick List and Multi-select');?></h4></div>
				<div class="right_300">
					<select name="pck_mulsel_module_selector" id="pck_mulsel_module_selector">
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
				<div class="clear_float"></div>
				<?php 
				require("picklist_entry_view.php");
				?>
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
			$('<div class="modal hide fade in" id="edit_custom_field">' + data + '</div>').modal();
		}).success(function() { $('input:text:visible:first').focus(); });
    }
}
</script>