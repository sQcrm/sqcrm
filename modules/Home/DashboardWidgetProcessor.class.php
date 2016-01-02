<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class DashboardWidgetProcessor 
* @author Abhik Chakraborty
*/ 
	

class DashboardWidgetProcessor extends CRMDashboardWidget {
	public $table = "user_dashboard_widgets";
	public $primary_key = "iduser_dashboard_widgets";

	function __construct(sqlConnect $conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}

	/**
	* function to get the widgets added by users 
	* @param integer $iduser
	* @return array
	*/
	public function get_user_widgets($iduser=0) {
		if ((int)$iduser == 0) {
			$iduser = $_SESSION["do_user"]->iduser ;
		}
		$return_array = array() ;
		$qry = "
		select * from `".$this->getTable()."`
		where `iduser` = ? 
		order by `sort_order`
		" ;
		$left_widgets = array() ;
		$right_widgets = array() ;
		$this->query($qry,array($iduser)) ;
		if ($this->getNumRows() > 0) {
			while($this->next()) {
				if ($this->position == 1) {
					$left_widgets[] = array(
						"widget_name" => $this->widget_name ,
						"id" => $this->iduser_dashboard_widgets 
					) ;
				} else {
					$right_widgets[] = array(
						"widget_name" => $this->widget_name ,
						"id" => $this->iduser_dashboard_widgets 
					) ;
				}	
			}
			$return_array["left"] = $left_widgets ;
			$return_array["right"] = $right_widgets ;
		}
		return $return_array ;
	}
	
	/**
	* event function to do the widget sorting on dashboard
	* @param object $evctl
	* @return void
	* @see self::re_sort_widgets()
	*/
	public function eventSortWidgets(EventControler $evctl) {
		if (strlen($evctl->jsonData) > 3) {
			$sort_array = json_decode($evctl->jsonData,true) ;
			if (is_array($sort_array) && count($sort_array) >0) {
				if (array_key_exists(0,$sort_array) && count($sort_array[0]) > 0) {
					$sort_order = 0 ;
					foreach($sort_array[0] as $key=>$val) {
						$this->re_sort_widgets($val["id"],$sort_order);
						$sort_order++ ;
					}
				}
				if (array_key_exists(1,$sort_array) && count($sort_array[1]) > 0) {
					$sort_order = 0 ;
					foreach($sort_array[1] as $key=>$val) {
						$this->re_sort_widgets($val["id"],$sort_order);
						$sort_order++ ;
					}
				}
			}
		}
	}
	
	/**
	* function update the sort order of widget
	* @param integer $id
	* @param integer $sort_order
	* @return void
	*/
	public function re_sort_widgets($id,$sort_order) {
		if ((int)$id > 0) {
			$qry = "
			update `".$this->getTable()."`
			set 
			`sort_order` = ?
			where 
			`iduser_dashboard_widgets` = ?
			";
			$this->query($qry,array($sort_order,$id)) ;
		}
	}
	
	/**
	* event function to generate the html content for the widget add option
	* @param object $evctl
	* @return string
	*/
	public function eventGetWidgetAddOptions(EventControler $evctl) {
		$available_widgets = $this->get_available_widgets() ;
		$qry = "
		select * from `".$this->getTable()."`
		where iduser = ?
		" ;
		$this->query($qry,array($_SESSION["do_user"]->iduser)) ;
		$user_widgets = array() ;
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$user_widgets[] = $this->widget_name ;
			}
		}
		$available_widgets_count = count($available_widgets) ;
		$user_widgets_count = count($user_widgets) ;
		
		if ($available_widgets_count == $user_widgets_count) {
			echo '0' ;
		} else {
			$html = '<select name="widget_selector" id="widget_selector">' ;
			foreach ($available_widgets as $key=>$val) {
				if (in_array($key,$user_widgets)) continue ;
				$html .= '<option value="'.$key.'">'.$val['widget_title'].'</option>' ;
			}
			$html .= '</select>' ;
			$html .= '&nbsp;&nbsp;&nbsp;' ;
			$html .= '<select name="widget_position_selector" id = "widget_position_selector">' ;
			$html .= '<option value = "1">'._('on left block of dashboard').'</option>';
			$html .= '<option value = "2">'._('on right block of dashboard').'</option>';
			$html .= '</select>' ;
			$html .= '&nbsp;&nbsp;&nbsp;' ;
			$html .= '<br /><input class="btn btn-primary btn-small bs-prompt" value="'._('save').'" type="button" id="save_widget" style="margin-top:0px;">' ;
			$html .= '&nbsp;&nbsp;&nbsp;' ;
			$html .= '<a href="#" class="btn btn-inverse btn-small bs-prompt" id="cancel_save_widget">';
			$html .= '<i class="icon-white icon-remove-sign"></i>'._('cancel').'</a>' ;
			echo $html ;
		}
	}	
	
	/**
	* function to get the minimum sort order of widget by position by user 
	* @param integer $position
	* @param integer $iduser
	* @return integer
	*/
	public function get_min_widget_order_by_position($position,$iduser) {
		$qry = "
		select min(sort_order) as sort_order
		from `".$this->getTable()."`
		where 
		`iduser` = ? 
		and `position` = ?
		" ;
		$this->query($qry,array($iduser,$position)) ;
		$this->next() ;
		if (!is_null($this->sort_order) && $this->sort_order != '') {
			return $this->sort_order - 1 ;
		} else {
			return 0 ;
		}
	}
	
	/**
	* function to add the user widget
	* @param string $widget_name
	* @param integer $position
	* @param integer $iduser
	* @return mix
	*/
	public function add_user_widget($widget_name,$position,$iduser = 0) {
		if ($iduser == 0) $iduser = $_SESSION["do_user"]->iduser ;
		$qry = "
		select * from `".$this->getTable()."`
		where 
		`widget_name` = ?
		and `iduser` = ?
		" ;
		$this->query($qry,array($widget_name,$iduser));
		if ($this->getNumRows() > 0) {
			return false ;
		} else {
			$widget_path = BASE_PATH.'/widgets/' ;
			$widgets = array_diff(scandir($widget_path,1), array('..', '.'));
			if (in_array($widget_name,$widgets)) {
				$sort_order = $this->get_min_widget_order_by_position($position,$iduser);
				$this->addNew() ;
				$this->widget_name = $widget_name ;
				$this->position = $position ;
				$this->iduser = $iduser ;
				$this->sort_order = $sort_order ;
				$this->add() ;
				return $this->getInsertId() ;
			} else {
				return false ;
			}
		}
	}
	
	/**
	* event function to save the user widget
	* @param object $evctl
	* @return mix
	* @see self::re_sort_widgets()
	*/
	public function eventSaveUsersWidget(EventControler $evctl) {
		if ($evctl->widget_name != '') {
			$result =  $this->add_user_widget($evctl->widget_name,$evctl->position);
			if (false !== $result) {
				echo $result ;
			} else {
				echo '0' ;
			}
		} else {
			echo '0' ;
		}
	}
    
    /**
    * event function to remove a widget from user dashboard
    * @param object $evctl
    * @return mix
    */
    public function eventRemoveUserWidget(EventControler $evctl) {
		if ((int)$evctl->id > 0) {
			$qry = "
			select * from `".$this->getTable()."`
			where 
			`iduser_dashboard_widgets` = ?
			and `iduser` = ?
			" ;
			$this->query($qry,array((int)$evctl->id,$_SESSION["do_user"]->iduser));
			if ($this->getNumRows() > 0) {
				$qry = "
				delete from `".$this->getTable()."`
				where `iduser_dashboard_widgets` = ? limit 1
				" ;
				$this->query($qry,array((int)$evctl->id)) ;
				echo '1' ;
			} else {
				echo '0' ;
			}
		} else {
			echo '0' ;
		}
    }
}