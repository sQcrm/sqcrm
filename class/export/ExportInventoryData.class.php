<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class DataExport 
*	@author Abhik Chakraborty
*/
	

class ExportInventoryData extends DataObject {
	public $table = "";
	public $primary_key = "";

	/**
	* function to generate inventory address
	* @param object $obj 
	* @param string $inv
	* @return string $to_addr
	*/
	public function generate_to_address($obj,$inv) {
		$to_addr = '';
		if (is_object($obj)) { 
			if($obj->org_name != '') { 
				$to_addr .= $obj->org_name.'<br>';
			}
			
			$billing_address_fld = $inv.'_billing_address';
			$billing_city_fld = $inv.'_billing_city';
			$billing_state_fld = $inv.'_billing_state';
			$billing_country_fld =$inv.'_billing_country';
			$billing_pobox_fld =$inv.'_billing_po_box';
			
			if ($obj->$billing_address_fld != '') {
				$to_addr .= nl2br($obj->$billing_address_fld).'<br>';
			}
			if ($obj->$billing_city_fld != '') {
				$to_addr .= $obj->$billing_city_fld.',';
			}
			if ($obj->$billing_state_fld != '') {
				$to_addr .= $obj->$billing_state_fld.',';
			}
			if ($obj->$billing_country_fld != '') {
				$to_addr .= $obj->$billing_country_fld.',';
			}
			$to_addr = rtrim($to_addr,',');
			$to_addr .= '<br>';
			if ($obj->$billing_pobox_fld != '') {
				$to_addr .= $obj->$billing_pobox_fld ;
			}
		}
		return $to_addr ;
	}
	
	/**
	* event function to generate quote PDF
	* @param object $evctl
	* @see self::generate_inventory_pdf()
	*/
	public function eventQuotesPDF(EventControler $evctl) {
		$record_id = $evctl->record_id ;
		$this->generate_inventory_pdf($record_id,13);
	}

	/**
	* event function to generate SalesOrder PDF
	* @param object $evctl
	* @see self::generate_inventory_pdf()
	*/
	public function eventSalesOrderPDF(EventControler $evctl) {
		$record_id = $evctl->record_id ;
		$this->generate_inventory_pdf($record_id,14);
	}
	
	/**
	* event function to generate Invoice PDF
	* @param object $evctl
	* @see self::generate_inventory_pdf()
	*/
	public function eventInvoicePDF(EventControler $evctl) {
		$record_id = $evctl->record_id ;
		$this->generate_inventory_pdf($record_id,15);
	}
	
	/**
	* event function to generate PO PDF
	* @param object $evctl
	* @see self::generate_inventory_pdf()
	*/
	public function eventPurchaseOrderPDF(EventControler $evctl) {
		$record_id = $evctl->record_id ;
		$this->generate_inventory_pdf($record_id,16);
	}

	/**
	* function to generate inventory (quote,invoice,sales order,purchase order) PDF
	* @param integer $idquotes
	* @param boolean $save
	* @see http://www.mpdf1.com/mpdf/index.php
	*/
	public function generate_inventory_pdf($idrecord,$idmodule,$save=false) {
		include_once(THIRD_PARTY_LIB_PATH."/mpdf/mpdf.php");
		$pdf = new mPDF();
		$crm_global_settings = new CRMGlobalSettings();
		if ($idmodule == 13) {
			$obj = new Quotes();
			$obj->getId($idrecord);
			$prefix = $crm_global_settings->get_setting_data_by_name('quote_num_prefix');
			$inventory_type = _('QUOTE');
			$inv = 'q';
			$inv_number_fld = 'quote_number';
			$inv_date_fld = 'valid_till';
			$file_name = 'Quote_'.$prefix.$obj->quote_number.'.pdf';
			$inventory_number = _('Quote #');
		} elseif($idmodule == 14) {
			$obj = new SalesOrder();
			$obj->getId($idrecord);
			$prefix = $crm_global_settings->get_setting_data_by_name('salesorder_num_prefix');
			$inventory_type = _('SALES ORDER');
			$inv = 'so';
			$inv_number_fld = 'sales_order_number';
			$inv_date_fld = 'due_date';
			$file_name = 'SalesOrder_'.$prefix.$obj->sales_order_number.'.pdf';
			$inventory_number = _('Sales Order #');
		} elseif($idmodule == 15) {
			$obj = new Invoice();
			$obj->getId($idrecord);
			$prefix = $crm_global_settings->get_setting_data_by_name('invoice_num_prefix');
			$inventory_type = _('INVOICE');
			$inv = 'inv';
			$inv_number_fld = 'invoice_number';
			$inv_date_fld = 'due_date';
			$file_name = 'Invoice_'.$prefix.$obj->invoice_number.'.pdf';
			$inventory_number = _('Invoice #');
		} elseif($idmodule == 16) {
			$obj = new PurchaseOrder();
			$obj->getId($idrecord);
			$prefix = $crm_global_settings->get_setting_data_by_name('purchaseorder_num_prefix');
			$inventory_type = _('PURCHASE ORDER');
			$inv = 'po';
			$inv_number_fld = 'po_number';
			$inv_date_fld = 'due_date';
			$file_name = 'PurchaseOrder_'.$prefix.$obj->po_number.'.pdf';
			$inventory_number = _('Purchase Order #');
		}
		
		$inventory_logo = $crm_global_settings->get_setting_data_by_name('inventory_logo');
		$company_address = $crm_global_settings->get_setting_data_by_name('company_address');
		$do_lineitems = new Lineitems();
		$do_lineitems->get_line_items($idmodule,$idrecord);
		$lineitems = array();
		
		if ($do_lineitems->getNumRows() > 0 ) {
			while ($do_lineitems->next()) {
				$lineitems[] = array(
					"idlineitems"=>$do_lineitems->idlineitems,
					"item_type"=>$do_lineitems->item_type,
					"item_name"=>$do_lineitems->item_name,
					"item_value"=>$do_lineitems->item_value,
					"item_description"=>$do_lineitems->item_description,
					"item_quantity"=>$do_lineitems->item_quantity,
					"item_price"=>$do_lineitems->item_price,
					"discount_type"=>$do_lineitems->discount_type,
					"discount_value"=>$do_lineitems->discount_value,
					"discounted_amount"=>$do_lineitems->discounted_amount,
					"tax_values"=>$do_lineitems->tax_values,
					"taxed_amount"=>$do_lineitems->taxed_amount,
					"total_after_discount"=>$do_lineitems->total_after_discount,
					"total_after_tax"=>$do_lineitems->total_after_tax,
					"net_total"=>$do_lineitems->net_total
				);
			}
		}
		
		$html = '';
		
		if (is_array($lineitems) && count($lineitems) > 0 ) {
			//--load the stylesheet
			$stylesheet = file_get_contents(BASE_PATH.'/themes/custom-css/inventory_export.css');
			$pdf->WriteHTML($stylesheet,1);
			
			$html .= '
			<div>
				<div class="inv_wrapper">
					<h1 class="inv_heading">'.$inventory_type.'</h1>
					<div class="inv_address_wrapper">
						<p class="inv_address_section">
							<span class="inv_address_to_from">FROM:</span><br>
							'.nl2br($company_address).'
						</p>
					</div>
					<div class="inv_company_address_wrapper">
						<img class="inv_company_address_logo" src="'.$GLOBALS['FILE_UPLOAD_DISPLAY_PATH'].'/'.$inventory_logo.'">
					</div>
				</div>
				<div style="clear:both;"></div>
				<div class="inv_wrapper">
				<div class="inv_address_wrapper">
					<p class="inv_address_section">
						<span class="inv_address_to_from">To:</span><br>'.$this->generate_to_address($obj,$inv).'
					</p>
				</div>
				<div class="inv_brief_section">
					<table class="inv_brief_section_table">
						<tr>
							<th class="inv_brief_section_table_heading">
								<span>'.$inventory_number.'</span>
							</th>
							<td class="inv_brief_section_table_content">
								<span>'.$prefix.$obj->$inv_number_fld.'</span>
							</td>
						</tr>
						<tr>
							<th class="inv_brief_section_table_heading">
								<span>Date</span>
							</th>
							<td class="inv_brief_section_table_content">
								<span>'.FieldType9::display_value($obj->$inv_date_fld).'</span>
							</td>
						</tr>
						<tr>
							<th class="inv_brief_section_table_heading">
								<span>Amount Due</span>
							</th>
							<td class="inv_brief_section_table_content">
								<span>'.FieldType30::display_value($obj->grand_total).'</span>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div style="clear:both;"></div>
			<br>
			';
			$html .='
			<table class="inv_line_table">
				<thead>
					<tr>
						<th class="inv_line_table_header inv_line_table_header_width_30">
							<span>Item Name</span>
						</th>
						<th class="inv_line_table_header inv_line_table_header_width_10">
							<span>Qty</span>
						</th>
						<th class="inv_line_table_header inv_line_table_header_width_30">
							<span>Price</span>
						</th>
						<th class="inv_line_table_header inv_line_table_header_width_15">
							<span>Total</span>
						</th>
						<th class="inv_line_table_header inv_line_table_header_width_15">
							<span>Net Price</span>
						</th>
					</tr>
				</thead>
			';
			$html .='<tbody>';
			foreach ($lineitems as $key=>$items) {
				$line_discount = '';
				if ($items["discount_type"] == 'direct') {
					$line_discount =  _('Discount').'-'.FieldType30::display_value($items["discount_value"]);
				} elseif ($items["discount_type"] == 'percentage') {
					$line_discount =  _('Discount').'-'.$items["discount_value"].' %';
				} else {
					$line_discount =  _('Discount').'-'._('no discount');
				}
				$line_tax = '';
				if ($items["tax_values"] != '') {
					$line_tax = ' - '.rtrim($items["tax_values"],',');
				} else {
					$line_tax = ' - no tax';
				}
					
				$html .='
				<tr>
					<td class="inv_line_table_content inv_line_table_content_vertical_top">
						<div class="inv_line_table_content_block">
							'.$items["item_name"].' <br><br>
							'.nl2br($items["item_description"]).'
						</div>
					</td>
					<td class="inv_line_table_content inv_line_table_content_vertical_top">
						<div class="inv_line_table_content_block">
							'.FieldType16::display_value($items["item_quantity"]).'
						</div>
					</td>
					<td class="inv_line_table_content">
						<div class="inv_line_table_content_block">
							'.FieldType30::display_value($items["item_price"]).'
						</div>
						<div class="inv_line_table_content_block">
							'.$line_discount.'
						</div>
						<div class="inv_line_table_content_block">
							<b>Total after discount</b>
						</div>
						<div class="inv_line_table_content_block">
							<b>Tax </b> '.$line_tax.'
						</div>
					</td>
					<td class="inv_line_table_content">
						<div class="inv_line_table_content_block">
							'.FieldType30::display_value($items["item_price"]*$items["item_quantity"]).'
						</div>
						<div class="inv_line_table_content_block">
							'.FieldType30::display_value($items["discounted_amount"]).'
						</div>
						<div class="inv_line_table_content_block">
							'.FieldType30::display_value($items["total_after_discount"]).'
						</div>
						<div class="inv_line_table_content_block">
							'.FieldType30::display_value($items["taxed_amount"]).'
						</div>
					</td>
					<td class="inv_line_table_content inv_line_table_content_vertical_top">
						'.FieldType30::display_value($items["net_total"]).'
					</td>
				</tr>';
			}
			$html .='
			</tbody></table>';
				
			$net_discount = '';
			if ($obj->discount_type == 'percentage') {
				$net_discount = $obj->discount_value.' %';
			} elseif ($obj->discount_type == 'direct') {
				$net_discount =FieldType30::display_value($obj->discount_value);
			} else {
				$net_discount = _('no discount');
			}
			$net_tax = '';
			
			if ($obj->tax_values !='') {
				$net_tax = rtrim($obj->tax_values,',');
			}
				
			$ship_hand_tax = '';
				
			if ($obj->shipping_handling_tax_values != '') {
				$ship_hand_tax = rtrim($obj->shipping_handling_tax_values,',');
			}
			
			$final_adj = '';
				
			if ($obj->final_adjustment_type == 'add') {
				$final_adj=  '(+)';
			} elseif ($obj->final_adjustment_type == 'deduct') {
				$final_adj= '(-)';
			}
			$html .='
			<div style="clear:both;"></div>
			<br>
			<div class="inv_grand_total_section">
				<table class="inv_grand_total_table">
					<tr>
						<td class="inv_grand_total_table_header">
							<span><b>Net Total</b></span>
						</td>
						<td class="inv_grand_total_table_content">
							<span>'.FieldType30::display_value($obj->net_total).'</span>
						</td>
					</tr>
					<tr>
						<td class="inv_grand_total_table_header">
							<span><b>Discount -</b> '.$net_discount.'</span>
						</td>
						<td class="inv_grand_total_table_content">
							<span>'.FieldType30::display_value($obj->discounted_amount).'</span>
						</td>
					</tr>
					<tr>
						<td class="inv_grand_total_table_header">
							<span><b>Tax -</b> '.$net_tax.' </span>
						</td>
						<td class="inv_grand_total_table_content">
							<span>'.FieldType30::display_value($obj->taxed_amount).'</span>
						</td>
					</tr>
					<tr>
						<td class="inv_grand_total_table_header">
							<span><b>Shipping/Handling charges</b></span>
						</td>
						<td class="inv_grand_total_table_content">
							<span>'.FieldType30::display_value($obj->shipping_handling_charge).'</span>
						</td>
					</tr>
					<tr>
						<td class="inv_grand_total_table_header">
							<span><b>Shipping/Handling Tax -</b>'.$ship_hand_tax.'</span>
						</td>
						<td class="inv_grand_total_table_content">
							<span>'.FieldType30::display_value($obj->shipping_handling_taxed_amount).'</span>
						</td>
					</tr>
					<tr>
						<td class="inv_grand_total_table_header">
							<span><b>Final Adjustment</b>'.$final_adj.'</span>
						</td>
						<td class="inv_grand_total_table_content">
							<span>'.FieldType30::display_value($obj->final_adjustment_amount).'</span>
						</td>
					</tr>
					<tr>
						<td class="inv_grand_total_table_header">
							<span><b>Grand Total</span>
						</td>
						<td class="inv_grand_total_table_content">
							<span>'.FieldType30::display_value($obj->grand_total).'</span>
						</td>
					</tr>
				</table>
			</div>
			<div style="clear:both;"></div>
			<br>
			<h3 class="inv_terms_cond_section">
				<span>Terms & Condition</span>
			</h3>
			<div style="top:2px;">
				<p>
					'.nl2br($obj->terms_condition).'
				</p>
			</div></div>';
		}
		if (true===$save) {
			$pdf->WriteHTML($html);
			$pdf->Output(OUTBOUND_PATH.'/'.$file_name, 'F');
			return $file_name;
		} else {
			$pdf->WriteHTML($html,2);
			$pdf->Output($file_name, 'D');
			exit();
		}
	}
	
}