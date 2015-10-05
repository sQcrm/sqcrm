<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* create sales order from quote view 
* @author Abhik Chakraborty
*/ 
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12" style="margin-left:3px;">
			<div class="row-fluid">
				<div class="datadisplay-outer">
					<?php
					$e_add_entity = new Event("Invoice->eventAddRecord");
					$e_add_entity->addParam("idmodule",15);
					$e_add_entity->addParam("module","Invoice");
					$e_add_entity->addParam("error_page",NavigationControl::getNavigationLink($module,"add"));
					echo '<form class="form-horizontal" id="Invoice__addRecord" name="Invoice__addRecord" action="/eventcontroler.php" method="post" enctype="multipart/form-data">';
					echo $e_add_entity->getFormEvent();
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
  echo $do_crmfields->get_js_form_validation($module_id,"Invoice__addRecord","edit");
?>
$.validator.addMethod("notEqual", function(value,element,param) {
	return this.optional(element) || value != param;
	},"Please select a value "
);
</script>