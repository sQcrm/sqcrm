<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Products 
* @author Abhik Chakraborty
*/ 
	

class Products extends DataObject {
	public $table = "products";
	public $primary_key = "idproducts";

	/*variable to store the total of list query , does not consider the limit and where into account */
	protected $list_tot_rows = 0 ;

	/* public array to hold the field information for the list view */
	public $list_view_field_information = array();

	public $list_view_fields = array("product_name","product_category","idvendor","assigned_to");

	/* Array holding the field values to be displayed by the popup section */
	public $popup_selection_fields = array("product_name","product_category","idvendor","assigned_to");

	/* On popup select returned field, should be one of popup_selection_fields*/
	public $popup_selection_return_field = "product_name";

	/* default order by in the list view */
	protected $default_order_by = "`products`.`product_name`";

	public $module_group_rel_table = "products_to_grp_rel";

	public $list_query_group_by = "`products`.`idproducts`";

	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}

	/**
	* sets the list view total without filter condition
	* @param integer $tot 
	*/
	public function set_list_tot_rows($tot) {
		$this->list_tot_rows = $tot ;
	}

	/**
	* gets the total num of rows for the list query without filer condition
	* @return list_tot_rows
	*/
	public function get_list_tot_rows() {
		return $this->list_tot_rows ;
	}
    
	/**
	* function to get the default order by in list value
	* @return default_order_by
	*/
	public function get_default_order_by() {
		return $this->default_order_by ; 
	}

	/**
	* gets the list query for display of data
	* @param integer $listid
	* sets the query using the dataobject setSqlQuery() and accessed using getSqlQuery() with persistent Object
	*/
	public function get_list_query($listid = '') {
		$qry = "
		select `products`.*,
		`products_pricing`.*,
		`products_custom_fld`.*,
		`products_stock`.*,
		`products_to_grp_rel`.`idgroup`,
		`vendor`.`vendor_name` as `vendor_name`,
		group_concat(concat(products_tax.tax_name,'::',products_tax.tax_value)) as tax_value,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `products`
		inner join `products_pricing` on `products_pricing`.`idproducts` = `products`.`idproducts`
		inner join `products_custom_fld` on `products_custom_fld`.`idproducts` = `products`.`idproducts`
		inner join `products_stock` on `products_stock`.`idproducts` = `products`.`idproducts`
		left join `user` on `user`.`iduser` = `products`.`iduser`
		left join `products_to_grp_rel` on `products_to_grp_rel`.`idproducts` = `products`.`idproducts`
		left join `vendor` on `vendor`.`idvendor` = `products`.`idvendor`
		left join `group` on `group`.`idgroup` = `products_to_grp_rel`.`idgroup`
		left join `products_tax` on `products_tax`.`idproducts` = `products`.`idproducts`
		where `products`.`deleted` = 0 ";
		$this->setSqlQuery($qry);
	}
    
	/**
	* function getId(), gets the details of the entity by the primary key
	* Its Overwrite of the data object getId()
	* purpose to Overwrite this method is to get the details not just from entity table but also from 
	* the other related tables
	* @param integer $sqcrm_record_id
	*/
	public function getId($sqcrm_record_id) {
		$qry = "
		select `products`.*,
		`products_pricing`.*,
		`products_custom_fld`.*,
		`products_stock`.*,
		`products_to_grp_rel`.`idgroup`,
		`vendor`.`vendor_name` as `vendor_name`,
		group_concat(concat(products_tax.tax_name,'::',products_tax.tax_value)) as tax_value,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `products`
		inner join `products_pricing` on `products_pricing`.`idproducts` = `products`.`idproducts`
		inner join `products_custom_fld` on `products_custom_fld`.`idproducts` = `products`.`idproducts`
		inner join `products_stock` on `products_stock`.`idproducts` = `products`.`idproducts`
		left join `user` on `user`.`iduser` = `products`.`iduser`
		left join `products_to_grp_rel` on `products_to_grp_rel`.`idproducts` = `products`.`idproducts`
		left join `vendor` on `vendor`.`idvendor` = `products`.`idvendor`
		left join `group` on `group`.`idgroup` = `products_to_grp_rel`.`idgroup`
		left join `products_tax` on `products_tax`.`idproducts` = `products`.`idproducts`
		where 
		`products`.`idproducts` = ?
		And `products`.`deleted` = 0 ";
		$this->query($qry,array($sqcrm_record_id));
		return $this->next();
	}
    
	/**
	* Event function to add the vendor data
	* @param object $evctl
	*/
	public function eventAddRecord(EventControler $evctl) {
		$permission = $_SESSION["do_crm_action_permission"]->action_permitted('add',12) ; 
		if (true === $permission) {
			$do_process_plugins = new CRMPluginProcessor() ;
			$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,1);
			if (strlen($do_process_plugins->get_error()) > 2) {
				$_SESSION["do_crm_messages"]->set_message('error',$do_process_plugins->get_error());
				$next_page = NavigationControl::getNavigationLink($evctl->module,"add");
				$dis = new Display($next_page);
				$evctl->setDisplayNext($dis) ;
			} else {
				$do_crm_fields = new CRMFields();
				//$do_crm_fields->get_field_information_by_module((int)$evctl->idmodule);
				$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
				// Insert the data into the related tables 
				$table_entity = 'products';
				$table_entity_custom = 'products_custom_fld';
				$table_entity_to_grp = 'products_to_grp_rel';
				$table_products_pricing = 'products_pricing';
				$table_products_stock = 'products_stock';
				$table_product_tax = 'products_tax';
				$entity_data_array = array();
				$custom_data_array = array();
				$product_stock_data_array = array();
				$product_pricing_data_array = array();
				$product_tax_data_array = array();
				$assigned_to_as_group = false ;
				foreach ($crm_fields as $crm_fields) {
					$field_name = $crm_fields["field_name"];
					if ($field_name == 'assigned_to' && $crm_fields["table_name"] == $table_entity && $crm_fields["idblock"] > 0) 
						$field_name = 'iduser' ;
					$field_value = $do_crm_fields->convert_field_value_onsave($crm_fields,$evctl);
					if (is_array($field_value) && count($field_value) > 0) {
						if ($field_value["field_type"] == 15) {
							$value = $field_value["value"];
							$assigned_to_as_group = $field_value["assigned_to_as_group"];
						} elseif ($field_value["field_type"] == 12) {
							$value = $field_value["name"];
							$avatar_array[] = $field_value ;
						} elseif ($field_value["field_type"] == 165) {
							$value = $field_value["value"] ; 
							$fld_165 = $field_name ;
						}
					} else { $value = $field_value ; }
					if ($crm_fields["table_name"] == $table_entity && $crm_fields["idblock"] > 0) {
						$entity_data_array[$field_name] = $value ;
					} elseif ($crm_fields["table_name"] == $table_entity_custom && $crm_fields["idblock"] > 0) {
						$custom_data_array[$field_name] = $value ;
					} elseif ($crm_fields["table_name"] == $table_products_stock && $crm_fields["idblock"] > 0) {
						$product_stock_data_array[$field_name] = $value ;
					} elseif ($crm_fields["table_name"] == $table_products_pricing && $crm_fields["idblock"] > 0) {
						$product_pricing_data_array[$field_name] = $value ;
					} elseif ($crm_fields["table_name"] == $table_product_tax && $crm_fields["idblock"] > 0) {
						$product_tax_data_array[$field_name] = $value ;
					}
				}
				//add to entity table
				$this->insert($table_entity,$entity_data_array);
				$id_entity = $this->getInsertId() ;
				if ($id_entity > 0) {
					//adding the added_on
					$q_upd = "
					update `".$this->getTable()."` set 
					`added_on` = ? 
					where `".$this->primary_key."` = ?" ;
					$this->query($q_upd,array(date("Y-m-d H:i:s"),$id_entity));
					$custom_data_array["idproducts"] = $id_entity;
					$product_stock_data_array["idproducts"] = $id_entity;
					$product_pricing_data_array["idproducts"] = $id_entity ; 
					$this->insert($table_entity_custom,$custom_data_array);
					$this->insert($table_products_stock,$product_stock_data_array);
					$this->insert($table_products_pricing,$product_pricing_data_array);
					//If the assigned_to to set as group then it goes to the table entity group relation table
					if ($assigned_to_as_group === true) {
						$this->insert($table_entity_to_grp,array("idproducts"=>$id_entity,"idgroup"=>$group_id));
					}
					$product_tax_count = count($product_tax_data_array[$fld_165]);
					if ($product_tax_count > 0) {
						foreach ($product_tax_data_array[$fld_165] as $key=>$val) {
							$ins_qry = "
							insert into `$table_product_tax` (`idproducts`,`tax_name`,`tax_value`) 
							values
							(?,?,?)
							";
							$this->query(
								$ins_qry,
								array(
									$id_entity,
									$val["tax_name"],
									$val["tax_value"]
								)
							);
						}
					}
					// record the data history
					$do_data_history = new DataHistory();
					$do_data_history->add_history($id_entity,(int)$evctl->idmodule,'add'); 
					//record the feed
					$feed_other_assigne = array() ;
					if ($assigned_to_as_group === true) {
						$feed_other_assigne = array("related"=>"group","data" => array("key"=>"newgroup","val"=>$group_id));
					}
					$do_feed_queue = new LiveFeedQueue();
					$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->product_name,'add',$feed_other_assigne);
					
					// process after add plugin
					$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,2,$id_entity);
					
					$_SESSION["do_crm_messages"]->set_message('success',_('New Product has been added successfully ! '));
					$next_page = NavigationControl::getNavigationLink($evctl->module,"detail");
					$dis = new Display($next_page);
					$dis->addParam("sqrecord",$id_entity);
					$evctl->setDisplayNext($dis) ; 
				} else {
					$_SESSION["do_crm_messages"]->set_message('error',_('Operation failed due to query error !'));
					$next_page = $evctl->error_page ;
					$dis = new Display($next_page); 
					$evctl->setDisplayNext($dis) ; 
				}
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to add record ! '));
			$next_page = NavigationControl::getNavigationLink($evctl->module,"list");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}
    
	/**
	* Event function to update the organization data
	* @param object $evctl
	*/
	public function eventEditRecord(EventControler $evctl) {
		$id_entity = (int)$evctl->sqrecord;
		if ($id_entity > 0 && true === $_SESSION["do_crm_action_permission"]->action_permitted('edit',12,(int)$evctl->sqrecord)) {
			$obj = $this->getId($id_entity);
			$obj = (object)$obj; // convert the data array to Object
			$do_process_plugins = new CRMPluginProcessor() ;
			// process before update plugin. If any error is raised display that.
			$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,3,$id_entity,$obj);
			if (strlen($do_process_plugins->get_error()) > 2) {
				$_SESSION["do_crm_messages"]->set_message('error',$do_process_plugins->get_error());
				$next_page = NavigationControl::getNavigationLink($evctl->module,"edit");
				$dis = new Display($next_page);
				$dis->addParam("sqrecord",$id_entity); 
				if ($evctl->return_page != '') { 
					$dis->addParam("return_page",$evctl->return_page);
				}
				$evctl->setDisplayNext($dis) ;
			} else {
				$do_crm_fields = new CRMFields();
				$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
				$table_entity = 'products';
				$table_entity_custom = 'products_custom_fld';
				$table_entity_to_grp = 'products_to_grp_rel';
				$table_products_pricing = 'products_pricing';
				$table_products_stock = 'products_stock';
				$table_product_tax = 'products_tax';
				$entity_data_array = array();
				$custom_data_array = array();
				$product_stock_data_array = array();
				$product_pricing_data_array = array();
				$product_tax_data_array = array();
				$assigned_to_as_group = false ;
				foreach ($crm_fields as $crm_fields) {
					$field_name = $crm_fields["field_name"];
					$field_value = $do_crm_fields->convert_field_value_onsave($crm_fields,$evctl,'edit');
					if (is_array($field_value) && count($field_value) > 0) {
						if ($field_value["field_type"] == 15) {
							$field_name = 'iduser';
							$value = $field_value["value"];
							$assigned_to_as_group = $field_value["assigned_to_as_group"];
							$group_id = $field_value["group_id"];
						} elseif ($field_value["field_type"] == 12) {
							$value = $field_value["name"];
							$avatar_array[] = $field_value ;
						} elseif ($field_value["field_type"] == 165) {
							$value = $field_value["value"] ; 
							$fld_165 = $field_name ;
						}
					} else { $value = $field_value ; }
					if ($crm_fields["table_name"] == $table_entity && $crm_fields["idblock"] > 0) {
						$entity_data_array[$field_name] = $value ;
					} elseif ($crm_fields["table_name"] == $table_entity_custom && $crm_fields["idblock"] > 0) {
						$custom_data_array[$field_name] = $value ;
					} elseif ($crm_fields["table_name"] == $table_products_stock && $crm_fields["idblock"] > 0) {
						$product_stock_data_array[$field_name] = $value ;
					} elseif ($crm_fields["table_name"] == $table_products_pricing && $crm_fields["idblock"] > 0) {
						$product_pricing_data_array[$field_name] = $value ;
					} elseif ($crm_fields["table_name"] == $table_product_tax && $crm_fields["idblock"] > 0) {
						$product_tax_data_array[$field_name] = $value ;
					}
				}
				$this->update(array($this->primary_key=>$id_entity),$table_entity,$entity_data_array);
				//updating the last_modified,last_modified_by
				$q_upd = "
				update `".$this->getTable()."` set 
				`last_modified` = ? ,
				`last_modified_by` = ? 
				where `".$this->primary_key."` = ?";
				$this->query($q_upd,array(date("Y-m-d H:i:s"),$_SESSION["do_user"]->iduser,$id_entity));
				if (count($custom_data_array) > 0) {
					$this->update(array($this->primary_key=>$id_entity),$table_entity_custom,$custom_data_array);
				}
				if (count($product_stock_data_array) > 0) {
					$this->update(array($this->primary_key=>$id_entity),$table_products_stock,$product_stock_data_array);
				}
				if (count($product_pricing_data_array) > 0) {
					$this->update(array($this->primary_key=>$id_entity),$table_products_pricing,$product_pricing_data_array);
				}
				// product tax
				$this->query("delete from `$table_product_tax` where `idproducts` = ? ",array($id_entity));
				$product_tax_count = count($product_tax_data_array[$fld_165]);
				if ($product_tax_count > 0) {
					foreach ($product_tax_data_array[$fld_165] as $key=>$val) {
						$q_ins = "
						insert into `$table_product_tax` (`idproducts`,`tax_name`,`tax_value`) 
						values
						(?,?,?)
						" ;
						$this->query($q_ins,array($id_entity,$val["tax_name"],$val["tax_value"]));
					}
				}
				if ($assigned_to_as_group === false) {
					$qry_grp_rel = "DELETE from `$table_entity_to_grp` where `idproducts` = ? LIMIT 1";
					$this->query($qry_grp_rel,array($id_entity));
				} else {
					$qry_grp_rel = "select * from `$table_entity_to_grp` where `idproducts` = ?";
					$this->query($qry_grp_rel,array($id_entity));
					if ($this->getNumRows() > 0) {
						$this->next();
						$id_grp_rel = $this->idproducts_to_grp_rel ;
						$q_upd = "
						update `$table_entity_to_grp` set 
						`idgroup` = ?
						where `idproducts_to_grp_rel` = ? LIMIT 1" ;
						$this->query($q_upd,array($group_id,$id_grp_rel));
					} else {
						$this->insert($table_entity_to_grp,array("idproducts"=>$id_entity,"idgroup"=>$group_id));
					}
				}
				// Record the history
				$do_data_history = new DataHistory();
				$do_data_history->add_history($id_entity,(int)$evctl->idmodule,'edit');
				$do_data_history->add_history_value_changes($id_entity,(int)$evctl->idmodule,$obj,$evctl);
				//record the feed
				$feed_other_assigne = array() ;
				if ($assigned_to_as_group === true) {
					$feed_other_assigne = array("related"=>"group","data" => array("key"=>"newgroup","val"=>$group_id));
				}
				$do_feed_queue = new LiveFeedQueue();
				$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->product_name,'edit',$feed_other_assigne);
				
				// process after update plugin
				$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,4,$id_entity,$obj);
				
				$_SESSION["do_crm_messages"]->set_message('success',_('Data updated successfully !'));
				$next_page = NavigationControl::getNavigationLink($evctl->module,"detail");
				$dis = new Display($next_page);
				$dis->addParam("sqrecord",$id_entity);
				$evctl->setDisplayNext($dis) ; 
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to edit the record ! '));
			$next_page = NavigationControl::getNavigationLink($evctl->module,"list");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}
		
	/**
	* function get product data for inventory line items
	* @param object $evctl
	* @return string
	*/
	function eventGetIdLineItem(EventControler $evctl) { 
		$this->getId((int)$evctl->id);
		if ((int)$this->idproducts > 0) {
			echo 
			json_encode(
				array(
					"id"=>$this->idproducts,
					"product_name"=>$this->product_name,
					"description"=>$this->description,
					"product_price"=>$this->product_price,
					"quantity_in_stock"=>$this->quantity_in_stock,
					"is_active"=>$this->is_active,
					"tax_value"=>$this->tax_value
				)
			);	
		} else { echo json_encode(array("id"=>0)); }
	}
	
	/**
	* function to get the product tax by product id
	* @param integer $idproducts
	* @return mix
	*/
	public function get_products_tax($idproducts) {
		$qry = "select * from `products_tax` where `idproducts` = ? ";
		$this->query($qry,array($idproducts));
		if ($this->getNumRows() > 0) {
			$return_array = array();
			while ($this->next()) {
				$return_array[$this->tax_name] = $this->tax_value ;
			}
			return $return_array ;
		} else { return false ; }
	}
	
	/**
	* function to get the related purchase order for product
	* @param integer $idproducts
	*/
	public function get_related_purchase_order($idproducts) {
		$qry = "
		select `purchase_order`.*,
		`purchase_order_custom_fld`.*,
		`purchase_order_address`.*,
		`vendor`.`vendor_name`,
		concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) as `contact_name`,
		`purchase_order_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `purchase_order`
		inner join `purchase_order_address` on `purchase_order_address`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
		inner join `purchase_order_custom_fld` on `purchase_order_custom_fld`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
		left join `user` on `user`.`iduser` = `purchase_order`.`iduser`
		left join `purchase_order_to_grp_rel` on `purchase_order_to_grp_rel`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
		left join `vendor` on `vendor`.`idvendor` = `purchase_order`.`idvendor`
		left join `group` on `group`.`idgroup` = `purchase_order_to_grp_rel`.`idgroup`
		left join `contacts` on `contacts`.`idcontacts` = `purchase_order`.`idcontacts`
		inner join `lineitems` on `lineitems`.`recordid` = `purchase_order`.`idpurchase_order`
		where 
		`purchase_order`.`deleted` = 0
		and `lineitems`.`idmodule` = 16
		and `lineitems`.`item_type` = 'product'
		and `lineitems`.`item_value` = ".(int)$idproducts;
		$this->setSqlQuery($qry);
	}
	
	/**
	* function to get the related quotes for product
	* @param integer $idproducts
	*/
	public function get_related_quotes($idproducts) {
		$qry = "
		select `quotes`.*,
		`quotes_custom_fld`.*,
		`quotes_address`.*,
		`organization`.`organization_name` as `org_name`,
		`potentials`.`potential_name`,
		`quotes_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `quotes`
		inner join `quotes_address` on `quotes_address`.`idquotes` = `quotes`.`idquotes`
		inner join `quotes_custom_fld` on `quotes_custom_fld`.`idquotes` = `quotes`.`idquotes`
		left join `user` on `user`.`iduser` = `quotes`.`iduser`
		left join `quotes_to_grp_rel` on `quotes_to_grp_rel`.`idquotes` = `quotes`.`idquotes`
		left join `organization` on `organization`.`idorganization` = `quotes`.`idorganization`
		left join `group` on `group`.`idgroup` = `quotes_to_grp_rel`.`idgroup`
		left join `potentials` on `potentials`.`idpotentials` = `quotes`.`idpotentials`
		inner join `lineitems` on `lineitems`.`recordid` = `quotes`.`idquotes`
		where 
		`quotes`.`deleted` = 0
		and `lineitems`.`idmodule` = 13
		and `lineitems`.`item_type` = 'product'
		and `lineitems`.`item_value` = ".(int)$idproducts;
		$this->setSqlQuery($qry);
	}
	
	/**
	* function to get the related sales order for product
	* @param integer $idproducts
	*/
	public function get_related_sales_order($idproducts) {
		$qry = "
		select `sales_order`.*,
		`sales_order_custom_fld`.*,
		`sales_order_address`.*,
		`organization`.`organization_name` as `org_name`,
		concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) as `contact_name`,
		`potentials`.`potential_name`,
		`quotes`.`subject` as `quote_subject`,
		`sales_order_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `sales_order`
		inner join `sales_order_address` on `sales_order_address`.`idsales_order` = `sales_order`.`idsales_order`
		inner join `sales_order_custom_fld` on `sales_order_custom_fld`.`idsales_order` = `sales_order`.`idsales_order`
		left join `user` on `user`.`iduser` = `sales_order`.`iduser`
		left join `sales_order_to_grp_rel` on `sales_order_to_grp_rel`.`idsales_order` = `sales_order`.`idsales_order`
		left join `organization` on `organization`.`idorganization` = `sales_order`.`idorganization`
		left join `group` on `group`.`idgroup` = `sales_order_to_grp_rel`.`idgroup`
		left join `potentials` on `potentials`.`idpotentials` = `sales_order`.`idpotentials`
		left join `quotes` on `quotes`.`idquotes` = `sales_order`.`idquotes`
		left join `contacts` on `contacts`.`idcontacts` = `sales_order`.`idcontacts`
		inner join `lineitems` on `lineitems`.`recordid` = `sales_order`.`idsales_order`
		where 
		`sales_order`.`deleted` = 0
		and `lineitems`.`idmodule` = 14
		and `lineitems`.`item_type` = 'product'
		and `lineitems`.`item_value` = ".(int)$idproducts;
		$this->setSqlQuery($qry);
	}
	
	/**
	* function to get the related invoice for product
	* @param integer $idproducts
	*/
	public function get_related_invoice($idproducts) {
		$qry = "
		select `invoice`.*,
		`invoice_custom_fld`.*,
		`invoice_address`.*,
		`organization`.`organization_name` as `org_name`,
		concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) as `contact_name`,
		`potentials`.`potential_name`,
		`sales_order`.`subject` as `so_subject`,
		`invoice_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `invoice`
		inner join `invoice_address` on `invoice_address`.`idinvoice` = `invoice`.`idinvoice`
		inner join `invoice_custom_fld` on `invoice_custom_fld`.`idinvoice` = `invoice`.`idinvoice`
		left join `user` on `user`.`iduser` = `invoice`.`iduser`
		left join `invoice_to_grp_rel` on `invoice_to_grp_rel`.`idinvoice` = `invoice`.`idinvoice`
		left join `organization` on `organization`.`idorganization` = `invoice`.`idorganization`
		left join `group` on `group`.`idgroup` = `invoice_to_grp_rel`.`idgroup`
		left join `potentials` on `potentials`.`idpotentials` = `invoice`.`idpotentials`
		left join `sales_order` on `sales_order`.`idsales_order` = `invoice`.`idsales_order`
		left join `contacts` on `contacts`.`idcontacts` = `invoice`.`idcontacts`
		inner join `lineitems` on `lineitems`.`recordid` = `invoice`.`idinvoice`
		where 
		`invoice`.`deleted` = 0
		and `lineitems`.`idmodule` = 15
		and `lineitems`.`item_type` = 'product'
		and `lineitems`.`item_value` = ".(int)$idproducts;
		$this->setSqlQuery($qry);
	}
}