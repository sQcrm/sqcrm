<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/** 
* Application Configuration for currency setting
* holds the currency array
* @author Abhik Chakraborty
*/
$currency_array = array(
	'AED'=>array('name' => _('United Arab Emirates Dirham'), 'symbol'=>'د.إ', 'hex'=>'&#x62f;&#x2e;&#x625;'),
	'ANG'=>array('name' => _('NL Antillian Guilder'), 'symbol'=>'ƒ', 'hex'=>'&#x192;'),
	'ARS'=>array('name' => _('Argentine Peso'), 'symbol'=>'$', 'hex'=>'&#x24;'),
	'AUD'=>array('name' => _('Australian Dollar'), 'symbol'=>'A$', 'hex'=>'&#x41;&#x24;'),
	'BRL'=>array('name' => _('Brazilian Real'), 'symbol'=>'R$', 'hex'=>'&#x52;&#x24;'),
	'BSD'=>array('name' => _('Bahamian Dollar'), 'symbol'=>'B$', 'hex'=>'&#x42;&#x24;'),
	'CAD'=>array('name' => _('Canadian Dollar'), 'symbol'=>'$', 'hex'=>'&#x24;'),
	'CHF'=>array('name' => _('Swiss Franc'), 'symbol'=>'CHF', 'hex'=>'&#x43;&#x48;&#x46;'),
	'CLP'=>array('name' => _('Chilean Peso'), 'symbol'=>'$', 'hex'=>'&#x24;'),
	'CNY'=>array('name' => _('Chinese Yuan Renminbi'), 'symbol'=>'¥', 'hex'=>'&#xa5;'),
	'COP'=>array('name' => _('Colombian Peso'), 'symbol'=>'$', 'hex'=>'&#x24;'),
	'CZK'=>array('name' => _('Czech Koruna'), 'symbol'=>'Kč', 'hex'=>'&#x4b;&#x10d;'),
	'DKK'=>array('name' => _('Danish Krone'), 'symbol'=>'kr', 'hex'=>'&#x6b;&#x72;'),
	'EUR'=>array('name' => _('Euro'), 'symbol'=>'€', 'hex'=>'&#x20ac;'),
	'FJD'=>array('name' => _('Fiji Dollar'), 'symbol'=>'FJ$', 'hex'=>'&#x46;&#x4a;&#x24;'),
	'GBP'=>array('name' => _('British Pound'), 'symbol'=>'£', 'hex'=>'&#xa3;'),
	'GHS'=>array('name' => _('Ghanaian New Cedi'), 'symbol'=>'GH₵', 'hex'=>'&#x47;&#x48;&#x20b5;'),
	'GTQ'=>array('name' => _('Guatemalan Quetzal'), 'symbol'=>'Q', 'hex'=>'&#x51;'),
	'HKD'=>array('name' => _('Hong Kong Dollar'), 'symbol'=>'$', 'hex'=>'&#x24;'),
	'HNL'=>array('name' => _('Honduran Lempira'), 'symbol'=>'L', 'hex'=>'&#x4c;'),
	'HRK'=>array('name' => _('Croatian Kuna'), 'symbol'=>'kn', 'hex'=>'&#x6b;&#x6e;'),
	'HUF'=>array('name' => _('Hungarian Forint'), 'symbol'=>'Ft', 'hex'=>'&#x46;&#x74;'),
	'IDR'=>array('name' => _('Indonesian Rupiah'), 'symbol'=>'Rp', 'hex'=>'&#x52;&#x70;'),
	'ILS'=>array('name' => _('Israeli New Shekel'), 'symbol'=>'₪', 'hex'=>'&#x20aa;'),
	'INR'=>array('name' => _('Indian Rupee'), 'symbol'=>'₹', 'hex'=>'&#x20b9;'),
	'ISK'=>array('name' => _('Iceland Krona'), 'symbol'=>'kr', 'hex'=>'&#x6b;&#x72;'),
	'JMD'=>array('name' => _('Jamaican Dollar'), 'symbol'=>'J$', 'hex'=>'&#x4a;&#x24;'),
	'JPY'=>array('name' => _('Japanese Yen'), 'symbol'=>'¥', 'hex'=>'&#xa5;'),
	'KRW'=>array('name' => _('South-Korean Won'), 'symbol'=>'₩', 'hex'=>'&#x20a9;'),
	'LKR'=>array('name' => _('Sri Lanka Rupee'), 'symbol'=>'₨', 'hex'=>'&#x20a8;'),
	'MAD'=>array('name' => _('Moroccan Dirham'), 'symbol'=>'.د.م', 'hex'=>'&#x2e;&#x62f;&#x2e;&#x645;'),
	'MMK'=>array('name' => _('Myanmar Kyat'), 'symbol'=>'K', 'hex'=>'&#x4b;'),
	'MXN'=>array('name' => _('Mexican Peso'), 'symbol'=>'$', 'hex'=>'&#x24;'),
	'MYR'=>array('name' => _('Malaysian Ringgit'), 'symbol'=>'RM', 'hex'=>'&#x52;&#x4d;'),
	'NOK'=>array('name' => _('Norwegian Kroner'), 'symbol'=>'kr', 'hex'=>'&#x6b;&#x72;'),
	'NZD'=>array('name' => _('New Zealand Dollar'), 'symbol'=>'$', 'hex'=>'&#x24;'),
	'PAB'=>array('name' => _('Panamanian Balboa'), 'symbol'=>'B/.', 'hex'=>'&#x42;&#x2f;&#x2e;'),
	'PEN'=>array('name' => _('Peruvian Nuevo Sol'), 'symbol'=>'S/.', 'hex'=>'&#x53;&#x2f;&#x2e;'),
	'PHP'=>array('name' => _('Philippine Peso'), 'symbol'=>'₱', 'hex'=>'&#x20b1;'),
	'PKR'=>array('name' => _('Pakistan Rupee'), 'symbol'=>'₨', 'hex'=>'&#x20a8;'),
	'PLN'=>array('name' => _('Polish Zloty'), 'symbol'=>'zł', 'hex'=>'&#x7a;&#x142;'),
	'RON'=>array('name' => _('Romanian New Lei'), 'symbol'=>'lei', 'hex'=>'&#x6c;&#x65;&#x69;'),
	'RSD'=>array('name' => _('Serbian Dinar'), 'symbol'=>'RSD', 'hex'=>'&#x52;&#x53;&#x44;'),
	'RUB'=>array('name' => _('Russian Rouble'), 'symbol'=>'руб', 'hex'=>'&#x440;&#x443;&#x431;'),
	'SEK'=>array('name' => _('Swedish Krona'), 'symbol'=>'kr', 'hex'=>'&#x6b;&#x72;'),
	'SGD'=>array('name' => _('Singapore Dollar'), 'symbol'=>'S$', 'hex'=>'&#x53;&#x24;'),
	'THB'=>array('name' => _('Thai Baht'), 'symbol'=>'฿', 'hex'=>'&#xe3f;'),
	'TND'=>array('name' => _('Tunisian Dinar'), 'symbol'=>'DT', 'hex'=>'&#x44;&#x54;'),
	'TRY'=>array('name' => _('Turkish Lira'), 'symbol'=>'TL', 'hex'=>'&#x54;&#x4c;'),
	'TTD'=>array('name' => _('Trinidad/Tobago Dollar'), 'symbol'=>'$', 'hex'=>'&#x24;'),
	'TWD'=>array('name' => _('Taiwan Dollar'), 'symbol'=>'NT$', 'hex'=>'&#x4e;&#x54;&#x24;'),
	'USD'=>array('name' => _('US Dollar'), 'symbol'=>'$', 'hex'=>'&#x24;'),
	'VEF'=>array('name' => _('Venezuelan Bolivar Fuerte'), 'symbol'=>'Bs', 'hex'=>'&#x42;&#x73;'),
	'VND'=>array('name' => _('Vietnamese Dong'), 'symbol'=>'₫', 'hex'=>'&#x20ab;'),
	'XAF'=>array('name' => _('CFA Franc BEAC'), 'symbol'=>'FCFA', 'hex'=>'&#x46;&#x43;&#x46;&#x41;'),
	'XCD'=>array('name' => _('East Caribbean Dollar'), 'symbol'=>'$', 'hex'=>'&#x24;'),
	'XPF'=>array('name' => _('CFP Franc'), 'symbol'=>'F', 'hex'=>'&#x46;'),
	'ZAR'=>array('name' => _('South African Rand'), 'symbol'=>'R', 'hex'=>'&#x52;')
);

?>