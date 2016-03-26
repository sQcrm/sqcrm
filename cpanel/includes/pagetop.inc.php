<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
/**
* Top nevigation menu
* @author Abhik Chakraborty
*/
?>
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="brand" href="#"><img src="/themes/images/logo_medium.jpg"></a>
			<ul class="nav">
				<li class="active">
					<a href="/modules/Home/index">Home</a>
				</li>
				<li class="">
					<a href="/modules/Calendar/index">Calendar</a>
				</li>
				<li class="">
					<a href="/modules/Queue/index">Queue</a>
				</li>
			</ul>
			<ul class="nav">
				<li class="dropdown ">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Sales<b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class="">
							<a href="/modules/Leads/index">Leads</a>
						</li>
						<li class="">
							<a href="/modules/Contacts/index">Contacts</a>
						</li>
						<li class="">
							<a href="/modules/Potentials/index">Prospects</a>
						</li>
						<li class="">
							<a href="/modules/Organization/index">Organization</a>
						</li>
					</ul>
				</li>
			</ul>
			<ul class="nav">
				<li class="dropdown ">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Inventory<b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class="">
							<a href="/modules/Vendor/index">Vendor</a>
						</li>
						<li class="">
							<a href="/modules/Products/index">Products</a>
						</li>
						<li class="">
							<a href="/modules/PurchaseOrder/index">Purchase Order</a>
						</li>
					</ul>
				</li>
			</ul>
			<ul class="nav">
				<li class="dropdown ">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Revenue<b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class="">
							<a href="/modules/Quotes/index">Quotes</a>
						</li>
						<li class="">
							<a href="/modules/SalesOrder/index">Sales Order</a>
						</li>
						<li class="">
							<a href="/modules/Invoice/index">Invoice</a>
						</li>
					</ul>
				</li>
			</ul>
			<ul class="nav">
				<li class="dropdown ">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Analytics<b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class="">
							<a href="/modules/Report/index">Report</a>
						</li>
						<li class="">
							<a href="/modules/Report/custom_report">Custom Reports</a>
						</li>
					</ul>
				</li>
			</ul>				
			<ul class="nav pull-right">
				<li class="dropdown">
					<div id="user-profile">
						<div style="margin-top:7px;">Welcome, Abhik</div>
					</div>
					<ul class="dropdown-menu">
						<li class="">
							<a href="/modules/Settings/profile_list">Settings</a>
						</li>
						<li class="">
							<a href="#" onclick="changeUserAvatar();return false ;">change avatar</a>
						</li>
						<li>
							<a href="/eventcontroler.php?mydb_events[100]=do_user-%3EeventLogout">logout</a>
						</li>
					</ul>
				</li>				
			</ul>
		</div>
	</div>
</div>
<br />
<div id="server_side_message" style="height:40px;margin-top:2px;position:relative;"></div>
<!-- Javascript error message block -->
<div id="js_errors" style="display:none;"></div>