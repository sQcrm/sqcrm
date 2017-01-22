<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Detail view right side actions 
* @author Abhik Chakraborty
*/  
?>
<div class="box_content">
	<?php
    if ($module_id == 3) { ?>
	<ul class="list-group">
		<li class="list-group-item">
			<a href="#" onclick = "convert_lead('<?php echo $sqcrm_record_id ;?>')">
			<img src="/themes/images/convert.png" style="vertical-align:center;">
			<?php echo _('convert lead');?>
			</a>
		</li>
		<li class="list-group-item">
			<?php
			$lead_event_create_qry_str = '?related_to='.$sqcrm_record_id.'&related_to_module=3';
			?>
			<a href="<?php echo NavigationControl::getNavigationLink("Calendar","add",'',$lead_event_create_qry_str); ?>" onclick = "">
			<img src="/themes/images/calendar.png" style="vertical-align:center;">
			<?php echo _('create event');?>
			</a>
		</li>
	</ul>
	<?php
    } elseif ($module_id == 4) { ?>
	<ul class="list-group">
		<li class="list-group-item">
			<?php
			$cnt_pot_create_qry_string = '?related_to='.$sqcrm_record_id.'&related_to_module=4';
			?>
			<a href="<?php echo NavigationControl::getNavigationLink("Potentials","add",'',$cnt_pot_create_qry_string); ?>" onclick = "">
			<img src="/themes/images/prospect.png" style="vertical-align:center;">
			<?php echo _('create prospect');?>
			</a>
		</li>
		<li class="list-group-item">
			<?php
			$cnt_event_create_qry_str = '?related_to='.$sqcrm_record_id.'&related_to_module=4';
			?>
			<a href="<?php echo NavigationControl::getNavigationLink("Calendar","add",'',$cnt_event_create_qry_str); ?>" onclick = "">
			<img src="/themes/images/calendar.png" style="vertical-align:center;">
			<?php echo _('create event');?>
			</a>
		</li>
		<?php
		if ($portal_user['portal_user'] == 1) {
			$e_activate_cpanel_login = new Event("Contacts->eventActivateCpanelLogin");
			$e_activate_cpanel_login->addParam("record_id", $sqcrm_record_id);
			if ($portal_user['activated'] == 1) { 
			?>
			<li class="list-group-item">
				<a href="/<?php echo $e_activate_cpanel_login->getUrl() ; ?>">
				<img src="/themes/images/customer.png" style="vertical-align:center;">
				<?php echo _('regenerate portal login and send email'); ?>
				</a>
			</li>
			<?php
			} else {
			?>
			<li class="list-group-item">
				<a href="/<?php echo $e_activate_cpanel_login->getUrl() ; ?>">
				<img src="/themes/images/customer.png" style="vertical-align:center;">
				<?php echo _('activate portal login and send email'); ?>
				</a>
			</li>
			<?php
			}
		}
		?>
	</ul>
	<?php 
    } elseif ($module_id == 6 ) { ?>
    <ul class="list-group">
		<li class="list-group-item">
			<?php
			$org_pot_create_qry_string = '?related_to='.$sqcrm_record_id.'&related_to_module=6';
			?>
			<a href="<?php echo NavigationControl::getNavigationLink("Potentials","add",'',$org_pot_create_qry_string); ?>" onclick = "">
			<img src="/themes/images/prospect.png" style="vertical-align:center;">
			<?php echo _('create prospect');?>
			</a>
		</li>
		<li class="list-group-item">
			<?php
			$org_event_create_qry_str = '?related_to='.$sqcrm_record_id.'&related_to_module=6';
			?>
			<a href="<?php echo NavigationControl::getNavigationLink("Calendar","add",'',$org_event_create_qry_str); ?>" onclick = "">
			<img src="/themes/images/calendar.png" style="vertical-align:center;">
			<?php echo _('create event');?>
			</a>
		</li>
	</ul>
	<?php
    } elseif ($module_id == 5 ) { ?>
    <ul class="list-group">
		<li class="list-group-item">
			<?php
			$pot_event_create_qry_str = '?related_to='.$sqcrm_record_id.'&related_to_module=5';
			?>
			<a href="<?php echo NavigationControl::getNavigationLink("Calendar","add",'',$pot_event_create_qry_str); ?>" onclick = "">
			<img src="/themes/images/calendar.png" style="vertical-align:center;">
			<?php echo _('create event');?>
			</a>
		</li>
	</ul>
    <?php
    } elseif ($module_id == 13) { ?>
	<ul class="list-group">	
		<li class="list-group-item">
			<?php
			$e_quote_pdf = new Event("ExportInventoryData->eventQuotesPDF");
			$e_quote_pdf->addParam("m", $module);
			$e_quote_pdf->addParam("mid", $module_id);
			$e_quote_pdf->addParam("record_id", $sqcrm_record_id);
			?>
			<a href="/<?php echo $e_quote_pdf->getUrl() ; ?>">
			<img src="/themes/images/pdf.png" style="vertical-align:center;">
			<?php echo _('generate pdf'); ?>
			</a>
		</li>
		<li class="list-group-item">
			<a href="#" onclick="send_quote_with_email('<?php echo $sqcrm_record_id;?>','<?php echo $module_obj->idorganization;?>');return false;">
				<img src="/themes/images/email.png" style="vertical-align:center;">
				<?php echo _('send quote with email'); ?>
			</a>
		</li>
		<li class="list-group-item">
			<a href="<?php echo NavigationControl::getNavigationLink("Quotes","create_sales_order",$sqcrm_record_id,'&return_page=detail'); ?>" onclick = "">
			<img src="/themes/images/sales_order.png" style="vertical-align:center;">
			<?php echo _('create sales order');?>
			</a>
		</li>
		<li class="list-group-item">
			<a href="<?php echo NavigationControl::getNavigationLink("Quotes","create_invoice",$sqcrm_record_id,'&return_page=detail'); ?>" onclick = "">
			<img src="/themes/images/invoice.png" style="vertical-align:center;">
			<?php echo _('create invoice');?>
			</a>
		</li>
	</ul>
	<?php
    } elseif ($module_id == 14) { ?>
	<ul class="list-group">	
		<li class="list-group-item">
			<?php
			$e_so_pdf = new Event("ExportInventoryData->eventSalesOrderPDF");
			$e_so_pdf->addParam("m", $module);
			$e_so_pdf->addParam("mid", $module_id);
			$e_so_pdf->addParam("record_id", $sqcrm_record_id);
			?>
			<a href="/<?php echo $e_so_pdf->getUrl() ; ?>">
			<img src="/themes/images/pdf.png" style="vertical-align:center;">
			<?php echo _('generate pdf'); ?>
			</a>
		</li>
		<li class="list-group-item">
			<a href="#" onclick="send_salesorder_with_email('<?php echo $sqcrm_record_id;?>','<?php echo $module_obj->idorganization;?>');return false;">
			<img src="/themes/images/email.png" style="vertical-align:center;">
			<?php echo _('send sales order with email'); ?>
			</a>
		</li>
		<li class="list-group-item">
			<a href="<?php echo NavigationControl::getNavigationLink("SalesOrder","create_invoice",$sqcrm_record_id,'&return_page=detail'); ?>" onclick = "">
			<img src="/themes/images/invoice.png" style="vertical-align:center;">
			<?php echo _('create invoice');?>
			</a>
		</li>
	</ul>
    <?php } elseif ($module_id == 15) { ?>
	<ul class="list-group">	
		<li class="list-group-item">
			<?php
			$e_inv_pdf = new Event("ExportInventoryData->eventInvoicePDF");
			$e_inv_pdf->addParam("m", $module);
			$e_inv_pdf->addParam("mid", $module_id);
			$e_inv_pdf->addParam("record_id", $sqcrm_record_id);
			?>
			<a href="/<?php echo $e_inv_pdf->getUrl() ; ?>">
			<img src="/themes/images/pdf.png" style="vertical-align:center;">
			<?php echo _('generate pdf'); ?>
			</a>
		</li>
		<li class="list-group-item">
			<a href="#" onclick="send_invoice_with_email('<?php echo $sqcrm_record_id;?>','<?php echo $module_obj->idorganization;?>');return false;">
			<img src="/themes/images/email.png" style="vertical-align:center;">
			<?php echo _('send invoice with email'); ?>
			</a>
		</li>
	</ul>
    <?php } elseif ($module_id == 16) { ?>
	<ul class="list-group">	
		<li class="list-group-item">
			<?php
			$e_po_pdf = new Event("ExportInventoryData->eventPurchaseOrderPDF");
			$e_po_pdf->addParam("m", $module);
			$e_po_pdf->addParam("mid", $module_id);
			$e_po_pdf->addParam("record_id", $sqcrm_record_id);
			?>
			<a href="/<?php echo $e_po_pdf->getUrl() ; ?>">
			<img src="/themes/images/pdf.png" style="vertical-align:center;">
			<?php echo _('generate pdf'); ?>
			</a>
		</li>
		<li class="list-group-item">
			<a href="#" onclick="send_po_with_email('<?php echo $sqcrm_record_id;?>','<?php echo $module_obj->idcontacts;?>');return false;">
			<img src="/themes/images/email.png" style="vertical-align:center;">
			<?php echo _('send purchase order with email'); ?>
			</a>
		</li>
	</ul>
	<?php } elseif ($module_id == 19) { 
		if (true === $allowed_actions['task_create']) { ?>
			<ul class="list-group">	
				<li class="list-group-item">
					<a href="/modules/Project/<?php echo $sqcrm_record_id;?>/task/add" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-tasks"></i> <?php echo _('create a new task')?></a>
				</li>
				<li class="list-group-item">
					<a href="#" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-list"></i> <?php echo _('all tasks')?></a>
				</li>
			</ul>
		<?php 
		}
		
		// load the project member secton if allowed
		if (true === $allowed_actions['project_members']) {
			require("project_members_view.php");
		}
		
		// load the project email subscription
		require("project_email_subscription_view.php");
	?>
    <?php } elseif ($module_id == 11) { ?>
	<ul class="list-group">	
		<li class="list-group-item">
			<a href="<?php echo NavigationControl::getNavigationLink("Vendor","create_purchase_order",$sqcrm_record_id,'&return_page=detail'); ?>" onclick = "">
			<img src="/themes/images/purchase_order.png" style="vertical-align:center;">
			<?php echo _('create purchase order');?>
			</a>
		</li>
	</ul>
    <?php } elseif ($module_id == 12) { ?>
	<ul class="list-group">	
		<li class="list-group-item">
			<a href="<?php echo NavigationControl::getNavigationLink("Products","create_purchase_order",$sqcrm_record_id,'&return_page=detail'); ?>" onclick = "">
			<img src="/themes/images/purchase_order.png" style="vertical-align:center;">
			<?php echo _('create purchase order');?>
			</a>
		</li>
	</ul>
    <?php } ?>
    <?php
	$do_queue = new Queue() ;
	if (true === $do_queue->queue_permitted_for_module($module_id)) {
		echo '<div id="queue_section" style="margin-left:16px;">' ;
		echo '</div>' ;
	}
    ?>
</div>
<?php
if ($_SESSION["do_crm_action_permission"]->action_permitted('view',18) === true) { ?>
<script>
$(document).ready(function() {
	// if queue view is allowed then load the queue section 
	$.ajax({
		type: "GET",
		url: "/modules/Queue/list",
		data : "ajaxreq="+true+"&module=Queue&rand="+generateRandonString(10)+"&related="+true+"&related_module_id=<?php echo $module_id;?>&related_record_id=<?php echo $sqcrm_record_id;?>",
		success: function(result) { 
			$('#queue_section').html(result) ;
		},
		beforeSend: function() {
			$('#queue_section').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		}
    });
});
</script>
<?php
}
?>