<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Detail view right side actions 
* @author Abhik Chakraborty
*/  
?>
<div class="box_content">
    <?php
    if ($module_id == 13) { ?>
	<ul class="nav nav-list">	
		<li>
			<?php
			$e_quote_pdf = new Event("\cpanel_export\ExportInventoryData->eventQuotesPDF");
			$e_quote_pdf->setEventControler(CPANEL_EVENTCONTROLER_PATH.'cpanel/'."eventcontroler.php");
			$e_quote_pdf->addParam("m", $module);
			$e_quote_pdf->addParam("mid", $module_id);
			$e_quote_pdf->addParam("record_id", $sqcrm_record_id);
			?>
			<a href="/<?php echo $e_quote_pdf->getUrl() ; ?>">
			<img src="/themes/images/pdf.png" style="vertical-align:center;">
			<?php echo _('generate pdf'); ?>
			</a>
		</li>
	</ul>
	<?php
    } ?>
    
    <?php
    if ($module_id == 15) { ?>
	<ul class="nav nav-list">	
		<li>
			<?php
			$e_inv_pdf = new Event("\cpanel_export\ExportInventoryData->eventInvoicePDF");
			$e_inv_pdf->setEventControler(CPANEL_EVENTCONTROLER_PATH.'cpanel/'."eventcontroler.php");
			$e_inv_pdf->addParam("m", $module);
			$e_inv_pdf->addParam("mid", $module_id);
			$e_inv_pdf->addParam("record_id", $sqcrm_record_id);
			?>
			<a href="/<?php echo $e_inv_pdf->getUrl() ; ?>">
			<img src="/themes/images/pdf.png" style="vertical-align:center;">
			<?php echo _('generate pdf'); ?>
			</a>
		</li>
	</ul>
	<?php
    } ?>
</div>