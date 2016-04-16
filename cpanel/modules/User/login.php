<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Login page
* @author Abhik Chakraborty
*/
if (is_object($_SESSION["do_cpaneluser"]) && $_SESSION["do_cpaneluser"]->idcpanel_user > 0) { ?>
<script type="text/javascript">
	window.location= '<?php echo '/cpanel/modules/Home/';?>' ;
</script>
<?php
} else {
	require_once('view/login_view.php');
}
?>