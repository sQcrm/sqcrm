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
			<?php
				$inventory_drop_down = array();
				$revenue_drop_down = array();
				// check the user privileges and display the menu accordingly
				if (isset($_SESSION["do_user"]) && is_object($_SESSION["do_user"]) && $_SESSION["do_user"]->iduser > 0 && $_SESSION["do_user"]->iduser !='') {
					$module_privileges = $_SESSION["do_user"]->get_user_module_privileges();
					$modules_with_full_info = $_SESSION["do_module"]->get_modules_with_full_info();
					echo '<ul class="nav">';
					if (is_array($module_privileges) && count($module_privileges) > 0) {
						foreach ($module_privileges as $key=>$val) {
							if ($modules_with_full_info[$key]["menu_item"] == 0) continue;
								if ($val["module_permission"] == 1) {
									if ($key == 11 || $key== 12 || $key == 16) {
										$inventory_drop_down[] = $key ; 
										continue ;
									} elseif ($key == 13 || $key == 14 || $key == 15 ) {
										$revenue_drop_down[] = $key ;
										continue ;
									}
									$style_li = '';
									if ($module_id == $key) $style_li = 'active' ;
									echo '<li class="'.$style_li.'"><a href="'.NavigationControl::getNavigationLink($modules_with_full_info[$key]["name"],"index").'">'.$modules_with_full_info[$key]["label"].'</a></li>';
								}
							}
						}
						if (count($inventory_drop_down) > 0) {
							echo '</ul>';
							echo '<ul class="nav">';
							echo '<li class="dropdown">';
							echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'._('Inventory').'<b class="caret"></b></a>';
							echo '<ul class="dropdown-menu">';
							foreach ($inventory_drop_down as $k) {
								echo '<li><a href="'.NavigationControl::getNavigationLink($modules_with_full_info[$k]["name"],"index").'">'.$modules_with_full_info[$k]["label"].'</a></li>';
							}
							echo '</ul>';
							echo '</li>';
							echo '</ul>';
						}
						
						if (count($revenue_drop_down) > 0) {
							echo '</ul>';
							echo '<ul class="nav">';
							echo '<li class="dropdown">';
							echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'._('Revenue').'<b class="caret"></b></a>';
							echo '<ul class="dropdown-menu">';
							foreach ($revenue_drop_down as $k) {
								echo '<li><a href="'.NavigationControl::getNavigationLink($modules_with_full_info[$k]["name"],"index").'">'.$modules_with_full_info[$k]["label"].'</a></li>';
							}
							echo '</ul>';
							echo '</li>';
							echo '</ul>';
						}
					}
				?>
				<ul class="nav pull-right">
					<?php
					if (isset($_SESSION["do_user"]) && $_SESSION["do_user"]->iduser != '') {
						if ($_SESSION["do_user"]->user_avatar != '') {
							echo '<li><div class="circular_35" style="background-image: url(\''.FieldType12::get_file_name_with_path($_SESSION["do_user"]->user_avatar,'s').'\')"></div></li>';
						} else {
							echo '<li>'. _('Welcome,').' '.$_SESSION["do_user"]->firstname.'</li>' ;
						}
						if ($_SESSION["do_user"]->is_admin == 1) {
							$setting_li = '';
							if ($admin_modules === true) $setting_li = 'active' ;
							echo '<li class="'.$setting_li.'"><a href="/modules/Settings/profile_list">'._('Settings').'</a></li>';
						}
						$e_logout = new Event("do_user->eventLogout");
						echo '<li><a href="/'.$e_logout->getUrl().'">logout</a></li>';
					}
					?>
				</ul>
		</div>
	</div>
</div>
<br />
<div id="server_side_message" style="height:40px;margin-top:2px;position:relative;"></div>
<?php
$_SESSION["do_crm_messages"]->get_messages(true);
$_SESSION["do_crm_messages"]->errase_message();
?>
<!-- Javascript error message block -->
<div id="js_errors" style="display:none;"></div>