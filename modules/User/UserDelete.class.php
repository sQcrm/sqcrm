<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class UserDelete
* @author Abhik Chakraborty
*/ 

class UserDelete extends User {
    
	public $table = "user";
	public $primary_key = "iduser";

	function __construct(sqlConnect $conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
  
	/**
	* function to delete user 
	* before deleting the data the user data must be transferred to a different user
	* some modules do not contain data like Home : 1 and Settings : 9 
	* and User : 7 module data is accessible by admin only.
	* @param integer $iduser
	* @return boolean
	* @param integer $iduser_data_transfer
	*/
	public function delete_single_user($iduser,$iduser_data_transfer){
		if ((int)$iduser > 0  && (int)$iduser_data_transfer > 0) {
			$do_module = new Module();
			$do_module->getAll();
			while ($do_module->next()) {
				if ($do_module->idmodule == 1 || $do_module->idmodule ==7 || $do_module->idmodule == 9) continue ;
				$module_name = $do_module->name ;
				$object = new $module_name();
				$this->transfer_data_before_delete($object,$iduser,$iduser_data_transfer);
			}
			$this->transfer_history_data($iduser,$iduser_data_transfer);
			$this->transfer_live_feed($iduser,$iduser_data_transfer);
			$this->transfer_login_audit($iduser,$iduser_data_transfer);
			$this->query("update `user` set `deleted` = ? where `iduser` = ?",array(1,$iduser));
			return true ;
		} else {
			return false ;
		}
	}
  
	/**
	* function to delete multiple user at a time
	* @param array $idusers
	* @param integer $iduser_data_transfer
	* @return boolean
	* @see self::delete_single_user
	*/
	public function delete_multiple_user($idusers,$iduser_data_transfer) {
		if ((int)$iduser_data_transfer > 0 && is_array($idusers) && count($idusers) > 0) {
			foreach ($idusers as $iduser) {
				$this->delete_single_user($iduser,$iduser_data_transfer);
			}
			return true ;
		} else {
			return false ;
		}
	}
  
	/**
	* function to transfer module specific data to other user when deleting an user
	* @param object $object
	* @param integer $iduser
	* @param integer $iduser_data_transfer
	*/
	public function transfer_data_before_delete($object,$iduser,$iduser_data_transfer) {
		$qry = "
		update `".$object->getTable()."` 
		set `iduser` = ? where `iduser` = ? " ;
		$this->query($qry,array($iduser_data_transfer,$iduser));
	}
  
	/**
	* function to transfer history data to other user when deleting an user
	* @param integer $iduser
	* @param integer $iduser_data_transfer
	*/
	public function transfer_history_data($iduser,$iduser_data_transfer) {
		$qry = "
		update `data_history` 
		set `iduser` = ? 
		where `iduser` = ?" ;
		$this->query($qry, array($iduser_data_transfer,$iduser));
	}
  
	/**
	* function to transfer live feed data to other user when deleting an user
	* @param integer $iduser
	* @param integer $iduser_data_transfer
	*/
	public function transfer_live_feed($iduser,$iduser_data_transfer) {
		$qry = "
		update `feed_queue` 
		set `iduser` = ?
		where `iduser` = ?" ;
		$this->query($qry,array($iduser_data_transfer,$iduser));
	
		$qry = "
		update `feed_queue` 
		set `iduser_for` = ? 
		where `iduser_for` = ?";
		$this->query($qry,array($iduser_data_transfer,$iduser));
	} 
  
	/**
	* function to transfer login audit data to other user when deleting an user
	* @param integer $iduser
	* @param integer $iduser_data_transfer
	*/
	public function transfer_login_audit($iduser,$iduser_data_transfer) {
		$qry = "
		update `login_audit` 
		set `iduser` = ? 
		where `iduser` = ?" ;
		$this->query($qry,array($iduser_data_transfer,$iduser));
	}
}
?>