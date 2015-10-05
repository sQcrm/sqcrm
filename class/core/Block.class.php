<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class BLock
* @author Abhik Chakraborty
*/
	

class Block extends DataObject {
	public $table = "block";
	public $primary_key = "idblock";
    
	public function get_block_by_module($idmodule) {
		$qry = "
		select block.* 
		from ".$this->getTable()." 
		inner join fields on fields.idblock = block.idblock
		where block.idmodule = ? 
		group by block.idblock 
		order by block.sequence
		";
		$this->query($qry,array($idmodule));
	}
    
}
