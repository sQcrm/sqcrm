<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/** 
* Application Configuration
* holds the Settings menu array
* @author Abhik Chakraborty
*/

$core_settings = array(
	_('User & Access Management')=> array(
		_('Profiles')=>array(
			'module'=>'Settings',
			'default_file'=>'profile_list',
			'files_list'=>array('profile_list','profile_add','profile_edit','profile_details','profile_permissions','profile_delete')
		),
		_('Roles')=>array(
			'module'=>'Settings',
			'default_file'=>'roles_list',
			'files_list'=>array('roles_list','roles_add','roles_edit',"roles_delete")
		),
		_('User')=>array(
			'module'=>'User',
			'default_file'=>'users',
			'files_list'=>array('users','add','edit','detail','list','index')
		),
		_('Group')=>array(
			'module'=>'Settings',
			'default_file'=>'group_list',
			'files_list'=>array('group_list','group_add','group_edit','group_detail')
		),
		_('Sharing')=>array(
			'module'=>'Settings',
			'default_file'=>'datashare_details',
			'files_list'=>array('datashare_details')
		)
	),
	_('Data Form Management')=>array(
		_('Custom Fields')=>array(
			'module'=>'Settings',
			'default_file'=>'customfield',
			'files_list'=>array('customfield','customfield_mapping')
		),
		_('Pick List & Multi-select')=>array(
			'module'=>'Settings',
			'default_file'=>'picklist',
			'files_list'=>array('picklist')
		),
		_('Data History Management')=>array(
			'module'=>'Settings',
			'default_file'=>'datahistory_settings',
			'files_list'=>array('datahistory_settings')
		)
	),
	_('Global Settings')=>array(
		_('Currency')=>array(
			'module'=>'Settings',
			'default_file'=>'currency',
			'files_list'=>array('currency')
			),
		_('Tax')=>array(
			'module'=>'Settings',
			'default_file'=>'tax_settings',
			'files_list'=>array('tax_settings')
		),
		_('Inventory Settings')=>array(
			'module'=>'Settings',
			'default_file'=>'inventory_settings',
			'files_list'=>array('inventory_settings')	
		)	
	),
	_('Developer')=>array(
		_('Plugins')=>array(
			'module'=>'Settings',
			'default_file'=>'plugins',
			'files_list'=>array('plugins')
		),
		_('Sort Plugins')=>array(
			'module'=>'Settings',
			'default_file'=>'plugins_sort',
			'files_list'=>array('plugins_sort')
		)
	)
);
?>