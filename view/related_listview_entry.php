<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* View modal for the list view of crm related data
* Included in the module/related.php file
* Get the filed information of module for the list view and generate the header for the datatable display
* Sets the fields information in the object member list_view_field_information and sets the object in the persistent session
* @author Abhik Chakraborty
*/
function get_field_info_related_view($related_module,$mid) {
	$do_crm_list_view = new CRMListView();
	return $do_crm_list_view->get_listview_field_info($related_module,$mid,"related");
}
?>
<script>
function get_dont_sort(table_div_id) {
	var dontSort = [];
	$('#'+table_div_id+' thead th').each( function() {
		if ($(this).hasClass('no_sort')) {
			dontSort.push({"bSortable": false});
		} else {
			dontSort.push(null);
		}
	});
	return dontSort ;
}
/*
* function to delete individual record and is used when we use related infomation tab from the detailview page.
*/

$(".datadisplay").on('click','.delete_entity',function(e) {
	var related_record_id = $(this).closest('table').attr('id') ;
	var sqrecord = $(this).closest('a').attr('id') ; 
	//alert(sqrecord);
	var parent_div = 'related_'+related_record_id ;
	var referrer_sqcrm_record_id = '<?php echo $sqcrm_record_id ; ?>';
	var qry_string = "&referrer=related&related_record_id="+related_record_id+"&referrer_sqcrm_record_id="+referrer_sqcrm_record_id+"&sqrecord="+sqrecord;
	$("#delete_confirm").modal('show');
	$("#delete_confirm .btn-primary").click(function() {
		$("#delete_confirm").modal('hide');
		$.ajax({
			type: "GET",
			<?php
			$e_del = new Event("CRMDeleteEntity->eventAjaxDeleteSingleEntity");
			$e_del->setEventControler("/ajax_evctl.php");
			$e_del->setSecure(false);
			?>
			url: "<?php echo $e_del->getUrl(); ?>"+qry_string,
			success:  function(html) {
				ret_data = html.trim();
				if (ret_data == 0) {
					var err_element = '<div class="alert alert-error sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
					var err_msg = err_element+'<strong>'+UNAUTHORIZED_DELETE+'</strong></div>';
					$("#message").html(err_msg);
					$("#message").show();
				} else {
					$.ajax({
						type: "GET",
						url: "related",
						data : "ajaxreq="+true+"&related_record_id="+related_record_id+"&referrer_sqcrm_record_id="+referrer_sqcrm_record_id+"&ajaxreq=true",
						success: function(result) { 
							$('#'+parent_div).html(result);
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
});
</script>
<?php
if ($related_data_information !== false && is_array($related_data_information) && count($related_data_information) > 0) {
	foreach ($related_data_information as $key=>$val) {
		echo '<div id="related_'.$val["id"].'">';
		require("related_listview_each_entry.php");
		echo '</div>';
	}
} else {
    echo '<div class="alert alert-info">';
    echo '<strong>'._('No related information found').'</strong>';
    echo '</div>';
}
?>
<div class="modal hide fade" id="delete_confirm">
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