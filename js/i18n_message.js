// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
* @author Abhik Chakraborty
*/
 
var LOADING = 'loading...';
var NO_MORE_DATA_FOUND = 'No more data found';
var MORE = 'more';
var LOAD_MORE = 'load more';
var UNAUTHORIZED_EDIT = 'You are trying to edit a record which you are not authorized to do.';
var UNAUTHORIZED_DELETE = 'You are trying to delete a record which you are not authorized to do.';
var SAVE = 'Save';
var EDIT = 'Edit';
var CANCEL = 'Cancel';
var SAVE_LW = 'save';
var EDIT_LW = 'edit';
var CANCEL_LW = 'cancel' ;
var CLOSE = 'Close' ;
var CLOSE_LW = 'close';
var CLOSED = 'Closed' ;
var DELETE = 'Delete';
var DELETE_LW = 'delete';
var ACTIVATE = 'activate';
var DEACTIVATE = 'deactivate';
var SET_PERMISSION = 'set permission';
var ADD = 'ADD';
var ADD_LW = 'add';
var OPEN = 'Open';
var REOPEN_LW = 'reopen';
var FILES = 'Files';
var CHANGE = 'Change';
var CHANGE_LW = 'change';

var DATA_DELETED_SUCCESSFULLY = 'Data deleted succesfully.';
var SELECT_ONE_RECORD_BEFORE_DELETE = 'Please select atleast one record before deleting.';
var SELECT_ONE_RECORD_BEFORE_CHANGE_USER = 'Please select atleast one record before changing assigned to.';
var SELECT_ONE_RECORD_BEFORE_CHANGE_EVENT_STATUS = 'Please select atleast one record before changing event status.';
var UPDATED_SUCCESSFULLY = 'Updated successfully.';

// Lead conversion JS error messages
var LEAD_CONVERT_POT_CONTACT_ORG_REQUIRE = 'Converting to Prospect requires a Contact or Organization.';
var LEAD_CONVERT_POT_ONLY_ORG_CONTACT_SELECT = 'You have choosen to select both Organization and Contact to be attached to Prospect. You can attach only one of them.'
var LEAD_CONVERT_POT_NAME_REQUIRE = 'Please add Potential Name.';
var LEAD_CONVERT_POT_EXPECTED_CLOSE_DATE_REQUIRE = 'Please select Expected Closing Date.';
var LEAD_CONVERT_POT_SALES_STAGE_REQUIRE = 'Please select Sales Stage.';
var LEAD_CONVERT_POT_AMOUNT_REQUIRE = 'Please add Amount.';
var LEAD_CONVERT_POT_PROBABILITY_REQUIRE = 'Please add probability.';
var SELECT_POT_CLOSE_LOST_REASON = 'Please select a lost reason' ;
var SELECT_POS_CLOSE_LOST_COMPETITOR = 'Please select a Competitor Name';

var LEAD_CONVERT_ORG_ORGNAME_REQUIRE = 'Please add Organization.';
var LEAD_CONVERT_ORG_INDUSTRY_REQUIRE =  'Please select Industry.';
var LEAD_CONVERT_ORG_SELECT_ORG = 'Please select Organization.';

var LEAD_CONVERT_CONTACT_FIRSTNAME_REQUIRE = 'Please add Contact Firstname.';
var LEAD_CONVERT_CONTACT_LASTNAME_REQUIRE = 'Please add Contact Lastname.';
var LEAD_CONVERT_CONTACT_EMAIL_REQUIRE = 'Please add Contact Email.';
var LEAD_CONVERT_CONTACT_SELECT_CONTACT = 'Please select Contact.';
var LEAD_CONVERT_TRANSFER_RELATED_DATA = 'Please select where to transfer the related data';

//custom field 
var CUSTOM_FIELD_LABEL_REQUIRE = 'Please provide a field label.';
var CUSTOM_FIELD_LENGTH_REQUIRE = 'Please provide a field length.';
var CUSTOM_FIELD_LENGTH_NUMERIC_VALUE = 'Please enter only numeric values for length.';
var CUSTOM_FIELD_OPTION_VALUES_REQUIRE = 'Please provide option values.';
var CUSTOM_FIELD_SPECIAL_CHARCTER_NOT_ALLOWED = 'Special characters are not allowed for values.';
var CUSTOM_FIELD_FIELDTYPE_REQUIRE = 'Please select a field type before saving.';
var CUSTOM_FIELD_DELETE_NOT_ALLOWED = 'The field can not be deleted.';

//custom field mapping
var ALREADY_MAPPED = 'Selected value is already mapped.';

//import map
var IMPORT_MAP_REQUIRE = 'Please map fields before submit.';
var IMPORT_ADD_MAP_SAVE_NAME = 'Please add a name to save the current map.';
var IMPORT_SAVED_MAP_NOT_FOUND = 'The selected saved map does not exit, or its deleted from database.';

//notes
var NOTES_ADDED_SUCCESSFULLY = 'Notes added succesfully.';
var NOTES_REQUIRE = 'Please add some note before saving.';
var NOTES_UPDATED_SUCCESSFULLY = 'Notes updated succesfully.';
var NOTES_DELETED_SUCCESSFULLY = 'Note deleted succesfully.';
var NOTES_CANT_BE_ADDED = 'Notes can not be added, missing record id or module name.';

var EVENT_END_DATE_GREATER_THAN_START_DATE = 'Event end date time must be greater than start date';

//home page
var NO_DATA_FOR_GRAPH = 'No data found to generate the graph';

//export
var SELECT_EXPORT_OPTION = 'Please select an export option.';

// report
var REPORT_SELECT_PREVIOUS_ORDER_OPTION = 'Please select previous order option first';
var REPORT_SELECT_FILTER_TYPE = 'Please select the filter type';
var REPORT_SELECT_FILTER_VALUE = 'Please select the filter value';
var REPORT_SELECT_PREVIOUS_ORDER_BY = 'Please select previous field for advanced filter';
var REPORT_ADD_REPORT_NAME = 'Please add a report name before saving';
var REPORT_SELECT_DATE_FILTER = 'Please select a date filter before submit' ;
var REPORT_SELECT_START_END_DATE = 'Please select a start and end date before submit' ;


//tax settings
var TAX_NAME_NO_EMPTY = 'Please add tax name.';
var TAX_VALUE_NO_EMPTY = 'Please add tax value.';

//line items
var SELECT_LINE_ITEM = 'Please select an item first.';
var ADD_LINE_ITEM_QTY = 'Please add a quantity first.';
var ADD_LINE_ITEM_PRICE = 'Please add a price first.';
var ADD_LINE_DISCOUNT_VALUE = 'Please add discount value';
var LINE_ITEM_QTY_INVALID = 'Line item quantity should be greate or equal to 1';
var LINE_ITEM_PRICE_INVALID = 'Line item price should be greate than 0';
var LINE_ITEM_NAME_INVALID = 'Line item name missing';

//copy organization address
var COPY_ORGANIZATION_ADDRESS_CONFIRM = 'Do you want to copy address infomation ?';

//CustomView
var CV_SELECT_PREVIOUS_ORDER_OPTION = 'Please select previous order option first';
var CV_SELECT_FILTER_TYPE = 'Please select the filter type';
var CV_SELECT_FILTER_VALUE = 'Please select the filter value';
var CV_SELECT_PREVIOUS_ORDER_BY = 'Please select previous field for advanced filter';
var CV_ADD_NAME = 'Please add a custom view name before saving';
var CV_SELECT_FIELDS = 'Please select fields before saving';

//widgets
var NO_MORE_WIDGET_FOUND = 'No more widgets found to be added';
var WIDGET_ADDED_OR_NOT_AVAILABLE = 'Either the widget is already added or its not available' ;
var WIDGET_NOT_ADDED_FOR_DELETE = 'Widget is not added to be deleted' ;

//queue
var SELECT_QUEUE_DATE_BEFORE_SAVE = 'Please select a date before saving' ;
var QUEUE_ADDED_SUCCESSFULLY = 'Added to queue successfully !' ;
var QUEUE_UPDATED_SUCCESSFULLY = 'Queue updated successfully !' ;
var QUEUE_DELETED_SUCCESSFULLY = 'Queue deleted successfully !' ;
var GO_TO_DETAIL = 'go to detail page' ;
var NO_QUEUE_DATA = 'No queue data found !' ;

//ajax image upload
var UPLOAD_SUCCESS = 'Uploaded successfully' ;
var UPLOAD_ERROR = 'Error uploading image, please check the image before uploading' ;

//plugins
var PLUGIN_PERMISSION_SET = 'Permission has been set successfully';

//project
var NO_MEMBER_TO_ADD_PROJECT = 'No member found to be added in to the project';
var SELECT_DUE_DATE_BEFORE_SAVE = 'Please select a due date before saving.';