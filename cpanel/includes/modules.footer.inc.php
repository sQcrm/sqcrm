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
<script src="/cpanel/js/common.js"></script>
<script src="/cpanel/js/i18n_message.js"></script>