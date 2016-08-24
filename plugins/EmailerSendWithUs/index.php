<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/
$idmodule = (int)$_GET["idmodule"];
$plugin_type = (int)$_REQUEST["plugin_type"];
if ($plugin_type == 8) {
	echo '<i class="glyphicon glyphicon-envelope"></i> '._('sendwithus emailer');
?>
<script src="/plugins/EmailerSendWithUs/asset/i18n_message.js"></script>
<script>
$(document).ready(function() {
	$(".listdata_action").on('click','#EmailerSendWithUs', function(e) {
		var sData = oTable.$('input:checkbox').serialize();
		if (sData == '') {
			var err_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
			var err_msg = err_element+'<strong>'+PLUGIN_SWU_PLEASE_SELECT_RECORD+'</strong></div>';
			$("#message").html(err_msg);
			$("#message").show();
			return false ;
		} else {
			var href = '/plugins.php?plugin_name=EmailerSendWithUs&popup=1&idmodule=<?php echo $idmodule;?>&popup_resource=index&'+sData;
			if (href.indexOf('#') == 0) {
				$(href).modal('open');
			} else {
				$.get(href, function(data) {
					//ugly heck to prevent the content getting append when opening the same modal multiple time
					$("#EmailerSendWithUs_model").html(''); 
					$("#EmailerSendWithUs_model").hide();
					$("#EmailerSendWithUs_model").attr("id","ugly_heck");
					$('<div class="modal fade" tabindex="-1" role="dialog" id="EmailerSendWithUs_model">' + data + '</div>').modal();
				}).success(function() { $('input:text:visible:first').focus(); });
			}
		}
	});
});
</script>
<?php
} else {
	include_once('detail_view_plugin.php');
}
?>