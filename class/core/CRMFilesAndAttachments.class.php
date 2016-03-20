<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMFilesAndAttachments 
* @author Abhik Chakraborty
*/
	

class CRMFilesAndAttachments extends DataObject {
	public $table = "file_uploads";
	public $primary_key = "idfile_uploads";

	/**
	* Function get the details by file name
	* @param string $filename
	*/
	public function get_file_details_by_name($filename) {
		$qry = "select * from ".$this->getTable()." where file_name = ?";
		$this->query($qry,array($filename));
	}

	public function delete_record($id) {
		if ((int)$id > 0) {
			$qry="delete from ".$this->getTable()." where ".$this->getPrimaryKey()."= ? limit 1";
			$stmt = $this->getDbConnection()->executeQuery($qry,array($id));
		}
	}
  
	/**
	* function to get the uploaded files
	* @param integer $idmodule
	* @param integer $idreferrer
	*/
	public function get_uploaded_files($idmodule,$idreferrer) {
		$qry = "
		select * 
		from ".$this->getTable()." 
		where idmodule = ? 
		AND id_referrer = ?";
		$this->query($qry,array($idmodule,$idreferrer));
	}
  
	/**
	* event function to download files
	* @param object $evctl
	* The script is using chunk download just to make sure that the large file download works without memory leak
	*/
	function eventDownloadFiles(EventControler $evctl) {
		if ((int)$evctl->fileid > 0) { 
			$this->getId((int)$evctl->fileid);
			$upload_path = $GLOBALS['FILE_UPLOAD_PATH'];
			$file_name = $this->file_name ;
			$file_desc = $this->file_description;
			$file_mime = $this->file_mime;
			if ($file_mime == '' || $file_mime == 'unknown') {
				$file_mime = 'application/octet-stream';
			}
			$file_size = $this->file_size;
			$saved_file_name = $file_name.'.'.$this->file_extension ;
			$file_download = $upload_path.'/'.$saved_file_name ;
			if (is_file($file_download)) { 
				ob_end_clean();
				header("Cache-Control:no-store,no-cache,must-revalidate");
				header("Cache-Control:post-check=0,pre-check=0",false);
				header("Pragma:no-cache");
				header("Expires:".gmdate("D,d M Y H:i:s",mktime(date("H")+2,date("l"),date("s"),date("m"),date("d"),date("Y")))." GMT");
				header("Last-Modified:".gmdate("D,d M Y H:i:s")." GMT");
				header("Content-Type:".$file_mime);
				header("Content-Length:".$file_size);
				header("Content-Disposition:inline;filename=$file_desc");
				header("Content-Transfer-Encoding:binary\n");
				if ($file = fopen($file_download,'rb')) { 
					while (!feof($file) and (connection_status() == 0 )) {
						print(fread($file,1024*8));
						flush();
					}
				}
			}
		}
	}
}// Make sure no new line after this brace since its using file download sript so it might not work