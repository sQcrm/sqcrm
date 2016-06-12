<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/
$idmodule = (int)$_GET["idmodule"] ;
echo '<i class="icon-white icon-play"></i>'._('test action plugin');
?>
<script>
$(document).ready(function() {
	$(".listdata_action").on('click','#ListviewAction', function(e) {
		var sData = oTable.$('input:checkbox').serialize();
		if (sData == '') {
			var err_element = '<div class="alert alert-error sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
			var err_msg = err_element+'<strong>Please select some records first !</strong></div>';
			$("#message").html(err_msg);
			$("#message").show();
			return false ;
		} else {
			var href = '/plugins.php?plugin_name=ListviewAction&popup=1&idmodule=<?php echo $idmodule;?>&popup_resource=test&'+sData;
			if (href.indexOf('#') == 0) {
				$(href).modal('open');
			} else {
				$.get(href, function(data) {
					//ugly heck to prevent the content getting append when opening the same modal multiple time
					$("#ListviewAction_model").html(''); 
					$("#ListviewAction_model").hide();
					$("#ListviewAction_model").attr("id","ugly_heck");
					$('<div class="modal hide fade" id="ListviewAction_model">' + data + '</div>').modal();
				}).success(function() { $('input:text:visible:first').focus(); });
			}
		}
	});
});
</script>