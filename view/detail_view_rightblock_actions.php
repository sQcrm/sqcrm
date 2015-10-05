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
    <ul class="nav nav-list">
		<li>
			<a href="#" onclick = "convert_lead('<?php echo $sqcrm_record_id ;?>')">
			<img src="/themes/images/convert.png" style="vertical-align:center;">
			<?php echo _('convert lead');?>
			</a>
		</li>
		<li>
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
	<ul class="nav nav-list">
		<li>
			<?php
			$cnt_pot_create_qry_string = '?related_to='.$sqcrm_record_id.'&related_to_module=4';
			?>
			<a href="<?php echo NavigationControl::getNavigationLink("Potentials","add",'',$cnt_pot_create_qry_string); ?>" onclick = "">
			<img src="/themes/images/prospect.png" style="vertical-align:center;">
			<?php echo _('create prospect');?>
			</a>
		</li>
		<li>
			<?php
			$cnt_event_create_qry_str = '?related_to='.$sqcrm_record_id.'&related_to_module=4';
			?>
			<a href="<?php echo NavigationControl::getNavigationLink("Calendar","add",'',$cnt_event_create_qry_str); ?>" onclick = "">
			<img src="/themes/images/calendar.png" style="vertical-align:center;">
			<?php echo _('create event');?>
			</a>
		</li>
	</ul>
	<?php 
    } elseif ($module_id == 6 ) { ?>
    <ul class="nav nav-list">
		<li>
			<?php
			$org_pot_create_qry_string = '?related_to='.$sqcrm_record_id.'&related_to_module=6';
			?>
			<a href="<?php echo NavigationControl::getNavigationLink("Potentials","add",'',$org_pot_create_qry_string); ?>" onclick = "">
			<img src="/themes/images/prospect.png" style="vertical-align:center;">
			<?php echo _('create prospect');?>
			</a>
		</li>
		<li>
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
    <ul class="nav nav-list">
		<li>
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
	<ul class="nav nav-list">	
		<li>
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
		<li>
			<a href="#" onclick="send_quote_with_email('<?php echo $sqcrm_record_id;?>','<?php echo $module_obj->idorganization;?>');return false;">
				<img src="/themes/images/email.png" style="vertical-align:center;">
				<?php echo _('send quote with email'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo NavigationControl::getNavigationLink("Quotes","create_sales_order",$sqcrm_record_id,'&return_page=detail'); ?>" onclick = "">
			<img src="/themes/images/sales_order.png" style="vertical-align:center;">
			<?php echo _('create sales order');?>
			</a>
		</li>
		<li>
			<a href="<?php echo NavigationControl::getNavigationLink("Quotes","create_invoice",$sqcrm_record_id,'&return_page=detail'); ?>" onclick = "">
			<img src="/themes/images/invoice.png" style="vertical-align:center;">
			<?php echo _('create invoice');?>
			</a>
		</li>
	</ul>
	<?php
    } elseif ($module_id == 14) { ?>
	<ul class="nav nav-list">	
		<li>
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
		<li>
			<a href="#" onclick="send_salesorder_with_email('<?php echo $sqcrm_record_id;?>','<?php echo $module_obj->idorganization;?>');return false;">
			<img src="/themes/images/email.png" style="vertical-align:center;">
			<?php echo _('send sales order with email'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo NavigationControl::getNavigationLink("SalesOrder","create_invoice",$sqcrm_record_id,'&return_page=detail'); ?>" onclick = "">
			<img src="/themes/images/invoice.png" style="vertical-align:center;">
			<?php echo _('create invoice');?>
			</a>
		</li>
	</ul>
    <?php } elseif ($module_id == 15) { ?>
	<ul class="nav nav-list">	
		<li>
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
		<li>
			<a href="#" onclick="send_invoice_with_email('<?php echo $sqcrm_record_id;?>','<?php echo $module_obj->idorganization;?>');return false;">
			<img src="/themes/images/email.png" style="vertical-align:center;">
			<?php echo _('send invoice with email'); ?>
			</a>
		</li>
	</ul>
    <?php } elseif ($module_id == 16) { ?>
	<ul class="nav nav-list">	
		<li>
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
		<li>
			<a href="#" onclick="send_po_with_email('<?php echo $sqcrm_record_id;?>','<?php echo $module_obj->idcontacts;?>');return false;">
			<img src="/themes/images/email.png" style="vertical-align:center;">
			<?php echo _('send purchase order with email'); ?>
			</a>
		</li>
	</ul>
    <?php } elseif ($module_id == 11) { ?>
	<ul class="nav nav-list">	
		<li>
			<a href="<?php echo NavigationControl::getNavigationLink("Vendor","create_purchase_order",$sqcrm_record_id,'&return_page=detail'); ?>" onclick = "">
			<img src="/themes/images/purchase_order.png" style="vertical-align:center;">
			<?php echo _('create purchase order');?>
			</a>
		</li>
	</ul>
    <?php } elseif ($module_id == 12) { ?>
	<ul class="nav nav-list">	
		<li>
			<a href="<?php echo NavigationControl::getNavigationLink("Products","create_purchase_order",$sqcrm_record_id,'&return_page=detail'); ?>" onclick = "">
			<img src="/themes/images/purchase_order.png" style="vertical-align:center;">
			<?php echo _('create purchase order');?>
			</a>
		</li>
	</ul>
    <?php } ?>
</div>