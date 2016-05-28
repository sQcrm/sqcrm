<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Invoice 
* @author Abhik Chakraborty
*/ 
namespace cpanel_invoice ;
class Invoice extends \Invoice {
	public $table = "invoice";
	public $primary_key = "idinvoice";
	protected $lookup_field = 'idcontacts';
	
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}

	public function get_lookup_field() {
		return $this->lookup_field ;
	}
	
	/**
	* function to get the invoice due amount
	* @param integer $idinvoice
	* @param double $grand_total
	* @return double
	*/
	public function get_due_amount($idinvoice,$grand_total) {
		$total_payment_made = 0 ;
		
		$qry="
		select coalesce(sum(p.amount),0) as amount
		from paymentlog p
		join invoice_payments ip on ip.idpaymentlog = p.idpaymentlog
		where ip.idinvoice = ? and p.transaction_type = 'charge'
		";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idinvoice));
		
		if ($stmt->rowCount() > 0) {
			$data = $stmt->fetch();
			$total_payment_made = $data['amount'];
		}
		
		return $grand_total - $total_payment_made ;
	}
	
	/**
	* get the payments made for the invoice
	* @param integer $idinvoice
	* @return array
	*/
	public function get_invoice_payments($idinvoice) {
		$qry = "
		select ip.additional_note,
		pl.date_added,
		pl.amount,
		pl.ref_num,
		pl.transaction_type,
		pm.mode_name
		from invoice_payments ip 
		join paymentlog pl on pl.idpaymentlog = ip.idpaymentlog 
		join payment_mode pm on pm.idpayment_mode = pl.idpayment_mode
		where ip.idinvoice = ?
		order by ip.idinvoice_payments desc
		";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idinvoice));
		$return_array = array();
		if ($stmt->rowCount() > 0) {
			while ($data = $stmt->fetch()) {
				$return_array[] = array(
					'date_added' => $data['date_added'],
					'additional_note' => $data['additional_note'],
					'amount' => $data['amount'],
					'ref_num' => $data['ref_num'],
					'transaction_type' => $data['transaction_type'],
					'mode_name' => $data['mode_name']
				);
			}
		}
		return $return_array ;
	}
}