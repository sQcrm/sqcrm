<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Import 
* @author Abhik Chakraborty
*/ 

class Import extends DataObject {
	public $table = "import";
	public $primary_key = "idimport";
	/* csv has header */
	public $has_header = false ;
	/* csv delimiter */
	public $delimiter = ',';
	/* module id of the data to be imported */
	protected $import_module_id = '';
	/* file name which is uploaded for import */
	private $csv_file_name = '';
	/* holds the maaped fields to the csv data */
	private $mapped_fields = array();
	/* row length of the csv */
	private $csv_row_length = 0 ;
	/* full length of the csv */
	private $csv_full_length = 0 ;
	/* max reocrd to be imported per loop */
	public $max_record_insert_per_loop = 100 ;
  
	/**
	* function to set if csv has header
	* @param boolean $bool
	*/
	public function set_has_header($bool) {
		$this->has_header = $bool ;
	}
  
	/**
	* function to get if the csv has header
	* @return boolean has_header
	*/
	public function get_has_header() {
		return $this->has_header ; 
	}
  
	/**
	* function to set the uploaded csv file name
	* @param string $name
	*/
	public function set_csv_file_name($name) {
		$this->csv_file_name = $name ;
	}
  
	/**
	* function to get the uploaded csv file name
	* @return string csv_file_name
	*/
	public function get_csv_file_name() {
		return $this->csv_file_name ;
	}

	/**
	* function to get the allowed modules for csv import
	* @return array
	*/
	public function get_allowed_modules_for_import() {
		return array(3,4,5,6,11,12);
	}
  
	/**
	* function to set the module id of the data to be imported
	* @param integer $idmodule
	*/
	public function set_import_module_id($idmodule) {
		$this->import_module_id = $idmodule ;
	}
  
	/**
	* function to get the module id of the data to be imported
	* @return integer import_module_id
	*/
	public function get_import_module_id() {
		return $this->import_module_id ;
	}
  
	/**
	* function to set the row length of the csv
	* @param integer $length
	*/
	public function set_csv_row_length($length) {
		$this->csv_row_length = $length ;
	}
  
	/**
	* function to get the row length of csv
	* @return integer csv_row_length
	*/
	public function get_csv_row_length() {
		return $this->csv_row_length ;
	}
  
	/**
	* function to set the mapped fields
	* @param integer $key
	* @param string $val
	*/
	public function set_mapped_fields($key,$val) {
		$this->mapped_fields[$key] = $val;
	}
  
	/**
	* function to get the mapped fields for the import
	* @return array mapped_fields
	*/
	public function get_mapped_fields() {
		return $this->mapped_fields ;
	}
  
	/**
	* function to get the max record to be inserted per loop
	* @return integer max_record_insert_per_loop
	*/
	public function get_max_record_insert_per_loop() {
		return $this->max_record_insert_per_loop ;
	}
  
	/**
	* function to set the csv full length
	* loops through the csv and sets the total length
	*/
	public function set_csv_full_length() {
		$count = 0 ;
		$upload_path = $GLOBALS['CSV_IMPORT_PATH'];
		$handle = fopen($upload_path.'/'.$this->get_csv_file_name(),"r");
		while (( $data = fgetcsv($handle, 4096, $this->delimiter) ) !== false) {
			$count++;
		}
		if ($this->get_has_header() === true) {
			$count = $count - 1 ;
		}
		$this->csv_full_length = $count ;
	}
  
	/**
	* function to get the full length of the csv
	* @return integer csv_full_length
	*/
	public function get_csv_full_length() {
		return $this->csv_full_length ; 
	}
  
	/** 
	* upload the csv file and then read the file to parse 3 lines
	* to display on the import mapping page
	* @param object $evctl
	*/
	public function eventImportStep1(EventControler $evctl) {
		// upload code goes below
		$do_parse = true ;
		$upload_path = $GLOBALS['CSV_IMPORT_PATH'];
		$file_found = false ;
		if ($_FILES["import_file"]["tmp_name"] != '') {
			$uploaded_file_name = $_FILES["import_file"]["name"];
			$uploaded_tmp_name = $_FILES["import_file"]["tmp_name"];
			$new_name = str_replace(" ","",microtime());
			$file_ext = end(explode('.',$uploaded_file_name));
			$file_name = $new_name.'.'.$file_ext ;
			move_uploaded_file($uploaded_tmp_name,$upload_path.'/'.$file_name);
			$file_found = true ;
			$this->set_csv_file_name($file_name);
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('No files uploaded !'));
			$do_parse = false ;
		}
		
		if ($file_found === true) {
			if ($evctl->has_header == 'on') {
				$this->set_has_header(true);
			}
			$next_page = NavigationControl::getNavigationLink("Import","import_step2");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ; 
		}
	}
  
	/**
	* event function import step 2.
	* gets the requested mapping information and sets it.
	* @param object $evctl
	*/
	public function eventImportStep2(EventControler $evctl) { 
		$this->clean_previous_imports();
		$csv_row_length = $this->get_csv_row_length();
		if ($csv_row_length > 0) {
			for ($i=0;$i<$csv_row_length;$i++) {
				$mapped_form_field_name = "field_map_".$i;
				$this->set_mapped_fields($i,$evctl->$mapped_form_field_name);
			}
			if ($evctl->save_import_map_ck == 'on' && $evctl->save_import_map != '') {
				$this->save_mapped_fields($evctl->save_import_map);
			}
			$next_page = NavigationControl::getNavigationLink("Import","import_step3");
			$dis = new Display($next_page);
			$dis->addParam("start",$id_entity);
			$evctl->setDisplayNext($dis) ; 
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('Import failed ! Empty CSV file.'));
		}
	}
  
	/**
	* function to save the field mapping for future use
	* @param string $map_name
	* @see self :: eventImportStep2
	*/
	public function save_mapped_fields($map_name) {
		$import_module_id = $this->get_import_module_id() ;
		$mapped_fields = $this->get_mapped_fields();
		$this->getDbConnection()->insert(
			"import_map",
			array(
				"map_name"=>CommonUtils::purify_input($map_name),
				"idmodule"=>$import_module_id,
				"date_added"=>date("Y-m-d H:i:s"),
				"iduser"=>$_SESSION["do_user"]->iduser,
				"map_data"=>json_encode($mapped_fields)
			)
		);
	}
  
	/**
	* event function to load the saved map by the id
	* @param object $evctl
	* @return json data if there is record else 0
	* @see view/import_step2_view.php
	*/
	public function eventLoadSavedMaps(EventControler $evctl) {
		$id = (int)$evctl->id ; 
		$import_module_id = (int)$evctl->import_module_id ;
		$qry = "
		select `map_data` 
		from `import_map`
		where 
		`idimport_map` = ?" ;
		$stmt = $this->getDbConnection()->executeQuery($qry,array($id));
		if ($stmt->rowCount() > 0) {
			$data  = $stmt->fetch();
			$map_data = $data["map_data"];
			header("content-type: application/json");
			echo $map_data;
		} else { echo "0" ; }
	}
  
	/**
	* function to get the saved maps for a module
	* @param integer $import_module_id
	* @return array if found else false
	* @see modules/Import/import_step2.php
	* @see view/import_step2_view.php
	*/
	public function get_saved_maps($import_module_id) {
		$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition("import_map",9);
		$qry = "
		select 
		`idimport_map`,
		`map_name` 
		from 
		`import_map` 
		where 
		`idmodule` = ? ".$security_where ;
		$stmt = $this->getDbConnection()->executeQuery($qry,array($import_module_id));
		if ($stmt->rowCount() > 0 ) {
			$return_array = array();
			while ($rs = $stmt->fetch()) {
				$data = array("id"=>$rs["idimport_map"],"map_name"=>$rs["map_name"]);
				$return_array[] = $data;
			}
			return $return_array;
		} else { return false ; }
	}
  
	/**
	* function to import the data from the csv file
	* @param object $module_object
	* @param integer $start
	* @return boolean
	* loops through the csv data and calls the function import_save() of the module for data insert
	*/
	public function import_data($module_object,$start=0) { 
		if (is_object($module_object)) { 
			$do_crm_fields = new CRMFields();
			//$do_crm_fields->get_field_information_by_module($this->get_import_module_id());
			$crm_fields = $do_crm_fields->get_field_information_by_module_as_array($this->get_import_module_id());
			$upload_path = $GLOBALS['CSV_IMPORT_PATH'];
			$handle = fopen($upload_path.'/'.$this->get_csv_file_name(),"r");
			$lines_count = 0 ;
			$insert_count = 0 ;
			$max_insert = $this->get_max_record_insert_per_loop();
			while (( $data = fgetcsv($handle, 4096, $this->delimiter) ) !== false) {
				if($lines_count == 0 && $this->get_has_header() === true) {
					$lines_count++;
					continue ;
				}
				if ($lines_count <= $start) {
					$lines_count++;
					continue ;
				} 
				$lines_count++;
				if ($insert_count == $max_insert) break;
        
				$idrecord = $module_object->import_save($this,$crm_fields,$data);
				if ($idrecord !== false) {
					$insert_count++;
					// add the record here
					$this->getDbConnection()->insert(
						$this->getTable(),
						array(
							"idmodule"=>$this->get_import_module_id(),
							"idrecord"=>$idrecord,
							"date_imported"=>date("Y-m-d"),
							"iduser"=>$_SESSION["do_user"]->iduser
						)
					);
				}
			}
			if($insert_count > 0 )
				return true;
			else
				return false ;
		}
	}
  
	/**
	* function to parse the import csv file 
	* @param string $filename
	* @param integer $max_lines
	*/
	public function parse_import_file($filename,$max_lines){
		$lines_count = 0 ;
		$data_count = 0 ;
		$row_array = array();
		$upload_path = $GLOBALS['CSV_IMPORT_PATH'];
		$handle = fopen($upload_path.'/'.$filename,"r");
		while ( ( ( $data = fgetcsv($handle, 4096, $this->delimiter) ) !== false) && ( $max_lines == -1 || $line_count < $max_lines)) {
			if ( count($data) == 1 && isset($data[0]) && $data[0] == '') {
				break;
			}
			$file_data_count = count($data);
			if ($file_data_count > $data_count) {
				$data_count = $file_data_count;
			}
			array_push($row_array,$data);
			$line_count++;
		}
		$return_array = array(
			"rows"=>$row_array,
			"field_count"=>$data_count
		);
		return $return_array;
	}
  
	/**
	* function to format the import data before saving
	* @param integer $field_type
	* @param mix value
	* @return the formatted value
	* @TODO format data for other types, right now its done only for checkbox( fieldtype 3 ) and date( fieldtype 9)
	*/
	public function format_data_before_save($field_type,$val) {
		if ($field_type == 3) {
			if ($val == '') return 0 ;
			if (strtolower($val) == 'yes') {
				return 1;
			} elseif (strtolower($val) == 'no') {
				return 0 ;
			} else {
				return CommonUtils::purify_input($val);
			} 
		} elseif ($field_type == 9) {
			if ($val == '') return $val;
			return date("Y-m-d",strtotime($val)) ;
		} else {
		return $val ;
		}
	}
  
	/**
	* Event function to set the import as finish
	* @param object $evctl
	*/
	public function eventFinishImport(EventControler $evctl) {
		$import_module_id = $this->get_import_module_id() ;
		$this->clean_previous_imports();
		$next_page = NavigationControl::getNavigationLink($_SESSION["do_module"]->modules_full_details[$import_module_id]["name"],"list");
		$dis = new Display($next_page);
		$evctl->setDisplayNext($dis) ; 
	}
  
	/**
	* Event function to discard the last import
	* will set the deleted = 0 for the last imported data for the module
	* @param object $evctl
	*/
	public function eventDiscardImport(EventControler $evctl) {
		$import_module_id = $this->get_import_module_id() ;
		switch ($import_module_id) {
			case 3 :
				$import_object = new LeadsImport();
				break;
			case 4 :
				$import_object = new ContactsImport();
				break;  
			case 5 :
				$import_object = new PotentialsImport();
				break;
			case 6 :
				$import_object = new OrganizationImport();
				break;
			case 11 :
				$import_object = new VendorImport();
				break;
			case 12 :
				$import_object = new ProductsImport();
				break;
		}
		
		$qry = "
		select * from ".$this->getTable()." 
		where 
		`idmodule` = ".$import_module_id." 
		AND `iduser` = ".$_SESSION["do_user"]->iduser ; 
		$stmt = $this->getDbConnection()->executeQuery($qry,array("idmodule"=>$import_module_id,"iduser"=>$_SESSION["do_user"]->iduser));
		if ($stmt->rowCount() > 0) {
			while ($data = $stmt->fetch()) {
				$qry = "
				update ".$import_object->getTable()." 
				set `deleted` = 1
				where `".$import_object->primary_key."` = ?
				AND `iduser` =  ? limit 1 ";
				$import_object->query($qry,array($data["idrecord"],$data["iduser"]));
			}
		}
		$this->clean_previous_imports();
		$next_page = NavigationControl::getNavigationLink("Import","index");
		$dis = new Display($next_page);
		$dis->addParam("return_module",$this->get_import_module_id());
		$evctl->setDisplayNext($dis) ; 
	}
  
	/**
	* function to clean the temporary data history for the last imported data
	*/
	public function clean_previous_imports() {
		$import_module_id = $this->get_import_module_id();
		$qry = "
		delete from ".$this->getTable()." 
		where idmodule = ? 
		AND
		iduser = ?";
		$this->getDbConnection()->executeQuery($qry,array($import_module_id,$_SESSION["do_user"]->iduser));
	}
    
}