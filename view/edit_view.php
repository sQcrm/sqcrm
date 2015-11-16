<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Entity edit view 
* @author Abhik Chakraborty
*/ 
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12" style="margin-left:3px;">
			<div class="row-fluid">
				<div class="datadisplay-outer">
					<?php
					$e_edit_entity = new Event($module."->eventEditRecord");
					$e_edit_entity->addParam("idmodule",$module_id);
					$e_edit_entity->addParam("module",$module);
					$e_edit_entity->addParam("sqrecord",$sqcrm_record_id);
					if (isset($_REQUEST["return_page"]) && strlen($_REQUEST["return_page"]) > 2) {
						$e_edit_entity->addParam("return_page",$_REQUEST["return_page"]);
					}
					$e_edit_entity->addParam("error_page",NavigationControl::getNavigationLink($module,"edit",$sqcrm_record_id));
					echo '<form class="form-horizontal" id="'.$module.'__editRecord" name="'.$module.'__editRecord" action="/eventcontroler.php" method="post" enctype="multipart/form-data">';
					echo $e_edit_entity->getFormEvent();
					require("edit_view_form_fields.php");
					?>          
					</form>
				</div>
			</div><!--/row-->
		</div><!--/span-->
	</div><!--/row-->
</div>
<script>
<?php 
	echo $do_crmfields->get_js_form_validation($module_id,$module."__editRecord","edit");
?>
$.validator.addMethod("notEqual", function(value,element,param) {
	return this.optional(element) || value != param;
	},"Please select a value "
);
</script>