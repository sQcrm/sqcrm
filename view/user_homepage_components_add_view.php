<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* user homepage component add/edit view 
* @author Abhik Chakraborty
*/  
echo '<div id="message"></div>';
$e_add = new Event("UserHomepageComponents->eventAjaxSaveUserHomepageComponent");
$e_add->addParam("iduser",$sqcrm_record_id);
echo '<form class="form-horizontal" id="UserHomepageComponents__eventAjaxSaveUserHomepageComponent" name="UserHomepageComponents__eventAjaxSaveUserHomepageComponent"  method="post" enctype="multipart/form-data">';
echo $e_add->getFormEvent();
foreach ($home_page_components_data as $key=>$val) {
?>
	<label class="checkbox">
		<input type="checkbox" name="home_page_component[]" value="<?php echo $val["id"]?>"> 
		<b><?php echo $val["component_name"];?></b> 
	</label>  
	<br />
<?php
}
?>
<hr class="form_hr">
<div id="homepage_component_submit">
	<input type="submit" class="btn btn-primary" id="" value="<?php echo _('Save');?>"/>
</div>
</form>
<script type="text/javascript" src="/js/jquery/plugins/jquery.form.js"></script>
<script>
$(document).ready(function() {
	var options = {
		target: '#message', //Div tag where content info will be loaded in
		url:'/ajax_evctl.php', //The php file that handles the file that is uploaded
		beforeSubmit: function() {
			$('#homepage_component_submit').html('<img src="/themes/images/ajax-loader1.gif" border="0" />'); //Including a preloader, it loads into the div tag with id uploader
		},
		success:  function(data) {
			//Here code can be included that needs to be performed if Ajax request was successful
			var succ_element = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
			var succ_msg = succ_element+'<strong>'+data+'</strong></div>';
			$("#message").html(succ_msg);
			var submit_btn = '<input type="submit" class="btn btn-primary" id="" value="<?php echo _('Save');?>"/>';
			$("#homepage_component_submit").html(submit_btn);
		}
	};
    
    $('#UserHomepageComponents__eventAjaxSaveUserHomepageComponent').submit(function() {
		$(this).ajaxSubmit(options);
        return false;
    });
    // Ajax submit ends here
});
</script>