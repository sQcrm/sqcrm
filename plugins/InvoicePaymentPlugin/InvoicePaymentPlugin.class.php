<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Class InvoicePaymentPlugin
* Log the payment made against an invoice 
* @author Abhik Chakraborty
*/

class InvoicePaymentPlugin extends CRMPluginProcessor {
	public $table = "invoice_payments";
	public $primary_key = "idinvoice_payment";
   
	/**
	* constructor function for the sQcrm plugin
	*/
	public function __construct() {
		$this->set_plugin_title(_('Invoice Payment Plugin')); // required
		$this->set_plugin_name('InvoicePaymentPlugin') ; // required same as your class name 
		$this->set_plugin_type(array(7)); // required 
		$this->set_plugin_modules(array(15)); // required
		$this->set_plugin_position(2); // required
		$this->set_plugin_description(
			_('Plugin to log the invoice payments, along with display the payments which are already made for the invoice.')
		); // optional
		$this->set_plugin_tab_name('Invoice Payments');
	}
	
	/**
	* function to get the payments by idinvoice
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
	
	/**
	* function to get the payment modes 
	* @return array
	*/
	public function get_payment_modes() {
		$qry="select * from `payment_mode` order by `mode_name`" ;
		$stmt = $this->getDbConnection()->executeQuery($qry);
		$return_array = array();
		if ($stmt->rowCount() > 0) {
			while ($data = $stmt->fetch()) {
				$return_array[] = array(
					'id' => $data['idpayment_mode'],
					'mode_name' => $data['mode_name']
				);
			}
		}
		return $return_array;
	}
	
	/**
	* function to get the due amount by idinvoice
	* @param integer $idinvoice
	* @return double
	*/
	public function get_due_amount($idinvoice) {
		$do_invoice = new Invoice();
		$do_invoice->getId($idinvoice);
		$grand_total = $do_invoice->grand_total;
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
	* function to check if the payment amount is more than the due amount for the invoice
	* @param integer $idinvoice
	* @param double $payment_amount
	* @return boolean
	*/
	public function is_payment_more_than_due($idinvoice,$payment_amount) {
		$due_amount = $this->get_due_amount($idinvoice);
		if ($due_amount < $payment_amount) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* event function to add the invoice payment 
	* @param object $evctl
	*/
	public function eventAjaxAddInvoicePayment(EventControler $evctl) {
		$err = false ;
		$error_code = 0;
		if (trim($evctl->payment_date) == '') {
			$err = true ;
			$error_code = 1;
		} elseif (trim($evctl->ref_num) == '') {
			$err = true ;
			$error_code = 2;
		} elseif ((int)FieldType30::convert_before_save(trim($evctl->amount)) == 0) {
			$err = true ;
			$error_code = 3;
		} elseif (true === $this->is_payment_more_than_due($evctl->idinvoice,FieldType30::convert_before_save(trim($evctl->amount)))) {
			$err = true ;
			$error_code = 4;
		}
		
		if (true === $err) {
			echo $error_code;
		} else {
			$payment_date = FieldType9::convert_before_save($evctl->payment_date);
			$payment_mode = $evctl->payment_mode;
			$amount = FieldType30::convert_before_save($evctl->amount);
			$ref_num = CommonUtils::purify_input($evctl->ref_num);
			$additional_note = CommonUtils::purify_input($evctl->additional_note);
			$idinvoice = (int)$evctl->idinvoice;
			// add to paymentlog
			$do_paymentlog = new Paymentlog();
			$do_paymentlog->addNew();
			$do_paymentlog->date_added = $payment_date;
			$do_paymentlog->amount = $amount;
			$do_paymentlog->ref_num = $ref_num;
			$do_paymentlog->idpayment_mode = $payment_mode;
			$do_paymentlog->add();
			$idpaymentlog = $do_paymentlog->getInsertId();
			
			// add to invoice payment 
			$this->addNew();
			$this->idinvoice = $idinvoice;
			$this->idpaymentlog = $idpaymentlog;
			$this->additional_note = $additional_note;
			$this->iduser = $_SESSION["do_user"]->iduser;
			$this->add();
			
			$qry = "
			select * from `payment_mode` where `idpayment_mode` = ?
			";
			$stmt = $this->getDbConnection()->executeQuery($qry,array($evctl->payment_mode));
			$data = $stmt->fetch();
			$payment_mode_name = $data['mode_name'];
			$html = '';
			$html .= '<tr>';
			$html .= '<td>'.FieldType9::display_value($payment_date).'</td>';
			$html .= '<td>'.FieldType30::display_value($amount).'</td>';
			$html .= '<td>'.FieldType1::display_value($ref_num).'</td>';
			$html .= '<td>'.FieldType1::display_value($payment_mode_name).'</td>';
			$html .= '<td>'._('charge').'</td>';
			$html .= '<td>'.nl2br($additional_note).'</td>';
			$html .= '</tr>';
			
			$due_amount = FieldType30::display_value($this->get_due_amount($idinvoice));
			
			echo json_encode(array('html'=>$html,'due_amount'=>$due_amount));
		}
	}
}