<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class LiveFeed 
* @author Abhik Chakraborty
*/ 
	

class LiveFeed extends DashboardWidgetProcessor {
	public $table = "";
	public $primary_key = "";

	function __construct() {
		$this->set_widget_title(_('Live Activity Feed'));
	}
    
}