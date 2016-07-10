<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
/**
* Including the module related JS files
* @author Abhik Chakraborty
*/
if (file_exists(BASE_PATH.'/js/'.$module.'.js')) {
?>
<script src="/js/<?php echo $module;?>.js"></script>
<?php
}
?>
<script src="/js/common.js?v=1.2"></script>
<script src="/js/i18n_message.js?v=1.2"></script>
<script>
/**
* load the detail view right block plugins
* @param string plugin_name
* @param string resource_name
* @param integer idmodule
* @param integer sqcrm_record_id
*/
function load_detail_view_plugin(plugin_name,resource_name,idmodule,sqcrm_record_id) {
	var file_name = '';
	if (resource_name == '') {
		file_name = 'index.php' ;
	} else {
		file_name = resource_name ;
	}
	$.ajax({
		type: "GET",
		url: '/plugins.php',
		data : "plugin_name="+plugin_name+"&resource_name="+file_name+"&idmodule="+idmodule+"&sqrecord="+sqcrm_record_id+"&plugin_position=1",
		beforeSubmit: function() {
			//Including a preloader, it loads into the div tag with id uploader
			$('#'+plugin_name).html('<img src="/themes/images/ajax-loader1.gif" border="0" />'); 
		},
		success: function(result) { 
			$('#'+plugin_name).html(result);
		}
	});
}

/**
* process the detail view tab plugins
* @param string plugin_name
* @param string resource_name
* @param integer idmodule
* @param integer sqcrm_record_id
*/
function process_detail_view_tab_plugin(plugin_name,resource_name,idmodule,sqcrm_record_id) {
	var file_name = '';
	if (resource_name == '') {
		file_name = 'index.php' ;
	} else {
		file_name = resource_name ;
	}
	var current_tab_id = '' ; 
	$('#detail_view_tab_section>li').each(function(){ 
		current_tab_id = $(this).attr('id') ;
		if (current_tab_id == 'plugin_'+plugin_name) {
			$("#"+current_tab_id).addClass('active');
		} else {
			$("#"+current_tab_id).removeClass('active');
		}
	}) ; 
	$.ajax({
		type: "GET",
		url: '/plugins.php',
		data : "plugin_name="+plugin_name+"&resource_name="+file_name+"&idmodule="+idmodule+"&sqrecord="+sqcrm_record_id+"&plugin_position=2",
		beforeSubmit: function() {
			//Including a preloader, it loads into the div tag with id uploader
			$('#detail_view_section').html('<img src="/themes/images/ajax-loader1.gif" border="0" />'); 
		},
		success: function(result) { 
			$('#detail_view_section').html(result);
		}
	});
}

/**
* process the list view action plugins and load the action button via ajax
* @param string plugin_name
* @param string resource_name
* @param integer idmodule
*/
function load_list_view_action_plugin(plugin_name,resource_name,idmodule) {
	var file_name = '';
	if (resource_name == '') {
		file_name = 'index.php' ;
	} else {
		file_name = resource_name ;
	}
	$.ajax({
		type: "GET",
		url: '/plugins.php',
		data : "plugin_name="+plugin_name+"&resource_name="+file_name+"&idmodule="+idmodule+"&plugin_position=1",
		beforeSubmit: function() {
			//Including a preloader, it loads into the div tag with id uploader
			$('#'+plugin_name).html('<img src="/themes/images/ajax-loader1.gif" border="0" />'); 
		},
		success: function(result) { 
			$('#'+plugin_name).html(result);
		}
	});
}
</script>