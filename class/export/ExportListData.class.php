<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class DataExport 
*	@author Abhik Chakraborty
*/
	

class ExportListData extends DataObject {
	public $table = "";
	public $primary_key = "";

	/**
	* export data function 
	* @param string $file_name
	* @param array $fields_info
	* @param string $type
	* @param integer $module_id
	*/
	public function export_data($file_name,$fields_info,$type,$module_id) {
		switch ($type) {
			case "excel":
				$this->export_to_excel($file_name,$fields_info,$module_id);
				break;
				
			case "pdf":
				$this->export_to_pdf($file_name,$fields_info,$module_id);
				break;
				
			case "csv":
				$this->export_to_csv($file_name,$fields_info,$module_id);
				break;
		}
	}
	
	/**
	* function to export the list view data
	* @param string $module
	* @param integer $module_id
	* @param string $type
	* @param integer $view_id
	* @see self::export_data()
	*/
	public function export_list_data($module,$module_id,$type,$view_id=0) {
		$file_name = $module;
		$object = new $module();
		$do_crm_fields = new CRMFields();
		$do_crm_list_view  = new CRMListView() ;
		$fields_info = $do_crm_list_view->get_listview_field_info($module,$module_id,"list",$view_id);
		$entity_table_name = $object->getTable() ;
		$security_where = "";
		$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition($entity_table_name,$module_id);
		$additional_where_condition = '' ; 
		$group_by = '';
		$order_by = '';
		$object->get_list_query();
		$qry = $object->getSqlQuery();
		if (property_exists($object,"list_query_group_by") === true && $object->list_query_group_by != '') {
			$group_by = " group by ".$object->list_query_group_by ;
		}
		
		if ($object->get_default_order_by() != "") {
			$order_by = " order by ".$object->get_default_order_by() ;
		}
		
		if ((int)$view_id > 0) {
			$do_custom_view_filter = new CustomViewFilter() ;
			$custom_view_date_filter_qry = $do_custom_view_filter->parse_custom_view_date_filter($view_id);
			$custom_view_adv_filter_qry = $do_custom_view_filter->parse_custom_view_advanced_filter($view_id);
			$additional_where_condition .= ' '.$custom_view_date_filter_qry ;
			
			if (false !== $custom_view_adv_filter_qry) {
				$additional_where_condition .= ' '.$custom_view_adv_filter_qry["where"] ;
			}
			
			$qry .= $security_where.$additional_where_condition.$group_by.$order_by ;
			
			if (false !== $custom_view_adv_filter_qry) {
				$this->query($qry,$custom_view_adv_filter_qry["bind_params"]);
			} else {
				$this->query($qry);
			}
		} else {
			$this->query($qry.$security_where.$group_by.$order_by);
		}
		$this->export_data($file_name,$fields_info,$type,$module_id);
	}
	
	/**
	* event function list data 
	* @param object $evctl
	* @see self:: export_list_data()
	*/
	public function eventExportListData(EventControler $evctl) {
		$module = $evctl->m ;
		$module_id = $evctl->mid ;
		$export_type = $evctl->export_list_opt ;
		$view_id = $evctl->vid ;
		$this->export_list_data($module,$module_id,$export_type,$view_id);
	}
	
	
	/**
	* function to export list data as excel
	* @param string $file_name
	* @param array $fields_info
	* @param integer $module_id
	* Library used for the xls generation is PHPExcel https://phpexcel.codeplex.com/
	*/
	public function export_to_excel($file_name,$fields_info,$module_id) {
		include_once(THIRD_PARTY_LIB_PATH."/PHPExcel/Classes/PHPExcel.php");
		if ($this->getNumRows() > 0 ) {
			$objPHPExcel = new PHPExcel(); 
			$do_crmfields = new CRMFields();
			$rowNumber = 1; 
			$col = 'A'; 
			foreach ($fields_info as $field=>$info) {
				$objPHPExcel->getActiveSheet()->setCellValue($col.$rowNumber,$info["field_label"]); 
				$col++; 
			}
			$rowNumber = 2; 
			while ($this->next()) {
				$col = 'A'; 
				foreach ($fields_info as $fields=>$info) {
					$fieldobject = 'FieldType'.$info["field_type"];
					$val = $do_crmfields->display_field_value($this->$fields,$info["field_type"],$fieldobject,$this,$module_id,false);
					$objPHPExcel->getActiveSheet()->setCellValue($col.$rowNumber,$val); 
					$col++; 
				}
				$rowNumber++; 
			}
			// Freeze pane so that the heading line won't scroll 
			$objPHPExcel->getActiveSheet()->freezePane('A2'); 
			// Save as an Excel BIFF (xls) file 
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 

			header('Content-Type: application/vnd.ms-excel'); 
			header('Content-Disposition: attachment;filename="'.$file_name.'.xls"'); 
			header('Cache-Control: max-age=0'); 

			$objWriter->save('php://output'); 
			exit(); 
		}
		//https://phpexcel.codeplex.com/discussions/359829
	}
	
	/**
	* function to export list data as CSV
	* @param string $file_name
	* @param array $fields_info
	* @param integer $module_id
	*/
	public function export_to_csv($file_name,$fields_info,$module_id) {
		if ($this->getNumRows() > 0) {
			$do_crmfields = new CRMFields();
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename='.$file_name.'.csv');
			$output = fopen('php://output', 'w');
			$csv_header = array();
			foreach ($fields_info as $field=>$info) {
				$csv_header[] = $info["field_label"];
			}
			fputcsv($output, $csv_header);
			while ($this->next()) {
				$data_array = array();
				foreach ($fields_info as $fields=>$info) {
					$fieldobject = 'FieldType'.$info["field_type"];
					$val = $do_crmfields->display_field_value($this->$fields,$info["field_type"],$fieldobject,$this,$module_id,false);
					$data_array[] = $val ;
				}
				fputcsv($output, $data_array);
			}
			fclose($output);
			exit();
		}
	}
	
	/**
	* function to export list data as PDF
	* @param string $file_name
	* @param array $fields_info
	* @param integer $module_id
	* Library used for PDF generation is tcpdf http://www.tcpdf.org/
	* @see http://www.tcpdf.org/performances.php
	*/
	public function export_to_pdf($file_name,$fields_info,$module_id) {
		if ($this->getNumRows() > 0 ) {
			$do_crmfields = new CRMFields();
			include_once(THIRD_PARTY_LIB_PATH."/tcpdf/tcpdf.php");
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			// set font
			$pdf->SetFont('helvetica', '', 8);
			// add a page
			$pdf->AddPage();
			$header = '<table><tr>';
			foreach($fields_info as $field=>$info){	
				$header .= '<td><b>'.$info["field_label"].'</b></td>';
			}
			$header .= '</tr>';
			$pdf->writeHTML($header, true, false, false, false, '');
			while ($this->next()) {
				$data_row = '<table><tr>';
				foreach ($fields_info as $fields=>$info) {
					$fieldobject = 'FieldType'.$info["field_type"];
					$val = $do_crmfields->display_field_value($this->$fields,$info["field_type"],$fieldobject,$this,$module_id,false);
					$data_row .= '<td>'.$val.'</td>';
				}
				$data_row .= '</tr></table>';
				$pdf->writeHTML($data_row, true, false, false, false, '');
			}
			$pdf->writeHTML('</table>', true, false, false, false, '');
			$pdf->Output($file_name.'.pdf', 'I');
			exit();
		}
	}
}