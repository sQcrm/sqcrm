<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* class RevenueByInvoicePayments
* @author Abhik Chakraborty
*/
class RevenueByInvoicePayments Extends CustomReport {
	
	/**
	* function to get the revenue details for the graph
	* @param array $current_range
	* @param array $previous_range
	* @param integer $iduser
	* @param integer $date_filter_type
	* @return array
	*/
	public function revenue_by_invoice_payments($current_range,$previous_range,$iduser=0,$date_filter_type=15) {
		$where_current = '';
		$where_previous = '';
		if ((int)$iduser == 0) {
			$iduser = $_SESSION["do_user"]->iduser ;
		}
		$user_where = $this->get_report_where($iduser,'i','igr') ;
		$current_range_where = " AND
		`p`.`date_added` >= '".$current_range['start']." 00:00:00'
		and `p`.`date_added` <= '".$current_range['end']." 23:59:59'
		";
		
		$previous_range_where = " AND
		`p`.`date_added` >= '".$previous_range['start']." 00:00:00'
		and `p`.`date_added` <= '".$previous_range['end']." 23:59:59'
		";
		
		$where_current = $user_where.$current_range_where ;
		$where_previous = $user_where.$previous_range_where ;
		
		$qry_current = "
		select sum(p.amount) as total, date(p.date_added) as payment_date 
		from paymentlog p 
		join invoice_payments ip on ip.idpaymentlog = p.idpaymentlog 
		join invoice i on i.idinvoice = ip.idinvoice
		left join `user` u on `u`.`iduser` = `i`.`iduser`
		left join `invoice_to_grp_rel` igr on `igr`.`idinvoice` = `i`.`idinvoice`
		left join `group` g on `g`.`idgroup` = `igr`.`idgroup`
		where i.deleted = 0 
		$where_current
		group by payment_date
		";
		
		$qry_previous = "
		select sum(p.amount) as total, date(p.date_added) as payment_date 
		from paymentlog p 
		join invoice_payments ip on ip.idpaymentlog = p.idpaymentlog 
		join invoice i on i.idinvoice = ip.idinvoice
		left join `user` u on `u`.`iduser` = `i`.`iduser`
		left join `invoice_to_grp_rel` igr on `igr`.`idinvoice` = `i`.`idinvoice`
		left join `group` g on `g`.`idgroup` = `igr`.`idgroup`
		where i.deleted = 0 
		$where_previous
		group by payment_date
		";
		
		$current_range_data = array();
		$previous_range_data = array();
		
		$stmt = $this->getDbConnection()->executeQuery($qry_current);
		if ($stmt->rowCount() > 0) {
			while($data = $stmt->fetch()) {
				if ((int)$data['total'] > 0) {
					$current_range_data[$data['payment_date']] = $data['total'] ;
				}
			}
		}
		
		$stmt = $this->getDbConnection()->executeQuery($qry_previous);
		if ($stmt->rowCount() > 0) {
			while($data = $stmt->fetch()) {
				if ((int)$data['total'] > 0) {
					$previous_range_data[$data['payment_date']] = $data['total'] ;
				}
			}
		}
		
		return array('current'=>$current_range_data,'previous'=>$previous_range_data);
	}
	
	/**
	* function to get all the dates between 2 dates
	* @param array $range
	* @return array
	*/
	public function get_all_days_in_range($range) {
		$start = $range['start'];
		$end = $range['end'];
		$dates = array();
		while (strtotime($start) <= strtotime($end)) {
			$dates[] = $start ;
			$start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
		}
		return $dates;
	}
	
	/**
	* function get the detailed data for the revenue
	* @param array $range
	* @param integer $iduser
	* @return string
	*/
	public function get_detailed_data($range,$iduser=0) {
		$where = '';
		if ((int)$iduser == 0) {
			$iduser = $_SESSION["do_user"]->iduser ;
		}
		$user_where = $this->get_report_where($iduser,'invoice','invoice_to_grp_rel') ;
		$range_where = " AND
		`paymentlog`.`date_added` >= '".$range['start']." 00:00:00'
		and `paymentlog`.`date_added` <= '".$range['end']." 23:59:59'
		";
		$where = $user_where.$range_where;
		$qry = "
		select
		invoice.idinvoice,
		invoice.invoice_status,
		paymentlog.date_added,
		paymentlog.amount,
		paymentlog.ref_num,
		paymentlog.transaction_type,
		payment_mode.mode_name
		from paymentlog 
		join invoice_payments on invoice_payments.idpaymentlog = paymentlog.idpaymentlog 
		join payment_mode on payment_mode.idpayment_mode = paymentlog.idpayment_mode
		join invoice on invoice.idinvoice = invoice_payments.idinvoice
		left join `user` on `user`.`iduser` = `invoice`.`iduser`
		left join `invoice_to_grp_rel` on `invoice_to_grp_rel`.`idinvoice` = `invoice`.`idinvoice`
		left join `group` on `group`.`idgroup` = `invoice_to_grp_rel`.`idgroup`
		where invoice.deleted = 0 
		";
		$order_by = " order by invoice.idinvoice";
		$qry .= $where.$order_by;
		return $qry;
	}
}