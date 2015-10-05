<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class DataExport 
*	@author Abhik Chakraborty
*/
	

class ExportDetailData extends DataObject {
	public $table = "";
	public $primary_key = "";

	
	/**
	* event function list data 
	* @param object $evctl
	* @see self:: export_list_data()
	*/
	public function eventExportDetailDataPDF(EventControler $evctl) {
		$module = $evctl->m ;
		$module_id = $evctl->mid ;
		$record_id = $evctl->record_id ;
		$this->export_detail_data_pdf($module,$module_id,$record_id);
	}
	
	/**
	* function to export list data as PDF
	* @param string $file_name
	* @param array $fields_info
	* @param integer $module_id
	* Library used for PDF generation is tcpdf http://www.tcpdf.org/
	* @see http://www.tcpdf.org/performances.php
	*/
	public function export_detail_data_pdf($module,$module_id,$record_id) {
		$do_crmfields = new CRMFields();
		$do_block = new Block();
		$do_block->get_block_by_module($module_id);
	
		$module_obj = new $module();
		$module_obj->getId($record_id);
	
		if ($module_obj->getNumRows() > 0 ) {
			$do_crmfields = new CRMFields();
			include_once(THIRD_PARTY_LIB_PATH."/mpdf/mpdf.php");
			$pdf = new mPDF();
			$do_crm_entity = new CRMEntity();
			$entity_identity = $do_crm_entity->get_entity_identifier($record_id,$module,$module_obj);
			$html = '';
			$html .= '<div style="float:left"><h3>'.$entity_identity.'</h3></div><div style="clear:both;"></div>';
			while ($do_block->next()) {
				$html .= '
				<table cellspacing="0" cellpadding="1" border="1" width="800px;">
					<tbody>
						<tr style="background-color:#eeeeee;line-height:100%;">
							<td colspan="4" height="35"><b>'.$do_block->block_label.'</b></td>
						</tr>';
				
				$do_crmfields->get_form_fields_information($do_block->idblock,$module_id) ;
				$num_fields = $do_crmfields->getNumRows() ;
				$tot_count = 0 ;
				while ($do_crmfields->next()) {
					$fieldobject = 'FieldType'.$do_crmfields->field_type;
					$fields_count++;
					$tot_count++;
					if ($tot_count == 1 || $tot_count%2 != 0  ) { 
						$html .= '<tr>';
					}
					$html .='<td style="background-color:#FDFFBD;width:25%;" height="20">'.$do_crmfields->field_label.'</td>';
					$fld_name =  $do_crmfields->field_name;
					$field_value = '';
					if ($do_crmfields->field_type == 12) {
						$field_value = $fieldobject::display_value($module_obj->$fld_name,'l');
					} elseif ($do_crmfields->field_type == 11) {
						$field_value = $fieldobject::display_value($module_obj->$fld_name,$module,$sqcrm_record_id,$fld_name,true);
					} else {
						$field_value = $do_crmfields->display_field_value($module_obj->$fld_name,$do_crmfields->field_type,$fieldobject,$module_obj,$module_id,false);
					}
					$html .='<td height="20" style="width:25%;">'.$field_value.'</td>';
					if ($tot_count != 1 && $tot_count%2 == 0  ) $html .= '</tr>';
					if ($num_fields == $tot_count && $tot_count%2 != 0) {
						$html .='
							<td style="background-color:#FDFFBD;width:25%" height="20">&nbsp;</td>
							<td height="20" style="width:25%">&nbsp;</td>
						</tr>';
					}
				}
				$html .= '</tbody></table>';
				$html .= '<br>';
			}
			/*$pdf->writeHTML($html, true, false, false, false, '');
			$pdf->Output($module.'_'.$record_id.'.pdf', 'I');
			exit();*/
			//echo $html;exit;
			$pdf->WriteHTML($html);
			$pdf->Output($module.'_'.$record_id.'.pdf', 'I');
			exit();
		}
	}
}