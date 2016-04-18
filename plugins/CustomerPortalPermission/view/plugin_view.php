<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/  
?>
<link href="/plugins/CustomerPortalPermission/asset/jqtree.css" rel="stylesheet">
<script type="text/javascript" src="/plugins/CustomerPortalPermission/asset/tree.jquery.js"></script>
<script type="text/javascript" src="/plugins/CustomerPortalPermission/asset/i18n_message.js"></script>
<div id="cpanel_user_settings">
<?php
if (count($users) == 0) {
	echo '<div class="alert alert-info">' ;
	echo _('There is no portal user associated with this organization.');
	echo '<br />' ;
	echo _('Once some contacts associated with this organization is created as portal user they will appear here for hierarchy creation.');
	echo '</div>' ;
} else {
	echo '<div class="alert alert-info">' ;
	echo _('By default each portal user can see their own data.');
	echo '<br />' ;
	echo _('You can create a hierarchy by dragging and dropping the listed customer portal users.');
	echo '<br />' ;
	echo _('Portal users having subordinate users as defined in the hierarchy can see data from subordinate users.');
	echo '</div>' ;
}
?>
<?php
if (count($users) > 0) {
?>
<div id="tree1"></div>
<hr class="form_hr"> 
<div id="save_tree_submit_area">
	<input type="button" class="btn btn-primary" id="save_tree" value="save"/>
</div>
<hr class="form_hr"> 
<script>
var data = <?php echo json_encode($users);?> ;
$('#tree1').tree({
    data: data,
    autoOpen: true,
    dragAndDrop: true
});
</script>
<?php
}
echo '<div class="alert alert-info">' ;
echo _('Select the modules to be available for customer support users');
echo '<br />' ;
echo _('You can check/uncheck the modules for activation/deactivation');
echo '</div>' ;
foreach ($cpanel_modules as $key=>$val) {
	echo '<input type="checkbox" class="cpanel_modules" name="cpanel_modules[]" value="'.$val["idmodule"].'" '.($val["activated"] == 1 ? 'CHECKED':'').'>'.$val["module_label"].'</option>' ;
	echo '<br />' ;
}
echo '<hr class="form_hr">' ;
echo '<div id="save_modules_submit_area">' ;
echo '<input type="button" class="btn btn-primary" id="save_cpanel_modules" value="save"/>';
echo '</div>' ;
echo '<hr class="form_hr">' ;
?>
</div>
<script>
$(document).ready(function() {

	$("#cpanel_user_settings").on('click','#save_tree', function(e) {
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("CustomerPortalPermission->eventSaveRoleHierarchy");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>&idmodule=<?php echo $idmodule;?>&sqcrm_record_id=<?php echo $sqcrm_record_id;?>",
			data:"data="+$('#tree1').tree('toJson'),
			beforeSubmit: function() {
				$("#save_tree_submit_area").html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
			},
			success:  function(html) {
				if (html.trim() == '1') {
					display_js_success(PLUGIN_CP_HIERARCHY_CREATED,'message');
				} else {
					display_js_error(html,'message');
				}
				$("#save_tree_submit_area").html('<input type="button" class="btn btn-primary" id="save_tree" value="save"/>') ;
			}
		});
		return false;
	});
	
	$("#cpanel_user_settings").on('click','#save_cpanel_modules', function(e) {
		var data = new Array();
		$("input[name='cpanel_modules[]']:checked").each(function(i) {
			data.push($(this).val());
		});
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("CustomerPortalPermission->eventSaveCpanelModules");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>&idmodule=<?php echo $idmodule;?>&sqcrm_record_id=<?php echo $sqcrm_record_id;?>",
			data:"data="+data,
			beforeSubmit: function() {
				$("#save_modules_submit_area").html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
			},
			success:  function(html) {
				if (html.trim() == '1') {
					display_js_success(PLUGIN_CP_MODULES_SAVED,'message');
				} else {
					display_js_error(html,'message');
				}
				$("#save_modules_submit_area").html('<input type="button" class="btn btn-primary" id="save_cpanel_modules" value="save"/>') ;
			}
		});
		return false;
	});
});
</script>