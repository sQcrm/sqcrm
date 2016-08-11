<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
/**
* Top nevigation menu
* @author Abhik Chakraborty
*/
?>
<div class="container">
	<nav role="navigation" class="navbar navbar-default navbar-fixed-top navbar-inverse">
		<div class="navbar-header">
			<button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="/themes/images/logo_medium.jpg"></a>
        </div>
        
		<div id="navbarCollapse" class="collapse navbar-collapse">   
			<?php
			$sales_drop_down = array() ;
			$inventory_drop_down = array();
			$revenue_drop_down = array();
			$analytics_drop_down = array();
			// check the user privileges and display the menu accordingly
			if (isset($_SESSION["do_user"]) && is_object($_SESSION["do_user"]) && $_SESSION["do_user"]->iduser > 0 && $_SESSION["do_user"]->iduser !='') {
				$module_privileges = $_SESSION["do_user"]->get_user_module_privileges();
				$modules_with_full_info = $_SESSION["do_module"]->get_modules_with_full_info();
				echo '<ul class="nav navbar-nav">';
				if (is_array($module_privileges) && count($module_privileges) > 0) {
					foreach ($module_privileges as $key=>$val) {
						if ($modules_with_full_info[$key]["menu_item"] == 0) continue;
						if ($val["module_permission"] == 1) {
							if ($key == 3 || $key== 4 || $key == 5 || $key == 6) {
								$sales_drop_down[] = $key ;
								continue ;
							} elseif ($key == 11 || $key== 12 || $key == 16) {
								$inventory_drop_down[] = $key ; 
								continue ;
							} elseif ($key == 13 || $key == 14 || $key == 15 ) {
								$revenue_drop_down[] = $key ;
								continue ;
							} elseif ($key == 10) {
								$analytics_drop_down[] = $key ;
								continue ;
							}
							$style_li = '';
							if ($module_id == $key) $style_li = 'active' ;
								echo '<li class="'.$style_li.'"><a href="'.NavigationControl::getNavigationLink($modules_with_full_info[$key]["name"],"index").'">'.$modules_with_full_info[$key]["label"].'</a></li>';
						}
					}
				}
				// Sales dropdown 
				if (count($sales_drop_down) > 0) {
					echo '<li class="dropdown">';
					$dropdown_selected = '';
					$dropdown_selected = (in_array($module_id,$sales_drop_down) ? 'active' : '') ;
					echo '<a data-toggle="dropdown" class="dropdown-toggle '.$dropdown_selected.'" href="#">'._('Sales').' <b class="caret"></b></a>';
					echo '<ul role="menu" class="dropdown-menu">';
					foreach ($sales_drop_down as $k) {
						$style_li = '';
						if ($module_id == $k) $style_li = 'active' ;
						echo '<li class="'.$style_li.'"><a href="'.NavigationControl::getNavigationLink($modules_with_full_info[$k]["name"],"index").'">'.$modules_with_full_info[$k]["label"].'</a></li>';
					}
					echo '</ul>';
					echo '</li>';
				}
				// Inventory dropdown menu
				if (count($inventory_drop_down) > 0) {
					echo '<li class="dropdown">';
					$dropdown_selected = '';
					$dropdown_selected = (in_array($module_id,$inventory_drop_down) ? 'active' : '') ;
					echo '<a data-toggle="dropdown" class="dropdown-toggle '.$dropdown_selected.'" href="#">'._('Inventory').' <b class="caret"></b></a>';
					echo '<ul role="menu" class="dropdown-menu">';
					foreach ($inventory_drop_down as $k) {
						$style_li = '';
						if ($module_id == $k) $style_li = 'active' ;
						echo '<li class="'.$style_li.'"><a href="'.NavigationControl::getNavigationLink($modules_with_full_info[$k]["name"],"index").'">'.$modules_with_full_info[$k]["label"].'</a></li>';
					}
					echo '</ul>';
					echo '</li>';
				}
				// Revenue dropdown menu
				if (count($revenue_drop_down) > 0) {
					echo '<li class="dropdown">';
					$dropdown_selected = '';
					$dropdown_selected = (in_array($module_id,$revenue_drop_down) ? 'active' : '') ;
					echo '<a data-toggle="dropdown" class="dropdown-toggle '.$dropdown_selected.'" href="#">'._('Revenue').' <b class="caret"></b></a>';
					echo '<ul role="menu" class="dropdown-menu">';
					foreach ($revenue_drop_down as $k) {
						$style_li = '';
						if ($module_id == $k) $style_li = 'active' ;
						echo '<li class="'.$style_li.'"><a href="'.NavigationControl::getNavigationLink($modules_with_full_info[$k]["name"],"index").'">'.$modules_with_full_info[$k]["label"].'</a></li>';
					}
					echo '</ul>';
					echo '</li>';
				}
				// Analytics dropdown menu
				if (count($analytics_drop_down) > 0) {
					echo '<li class="dropdown">';
					$dropdown_selected = '';
					$dropdown_selected = (in_array($module_id,$analytics_drop_down) ? 'active' : '') ;
					echo '<a data-toggle="dropdown" class="dropdown-toggle '.$dropdown_selected.'" href="#">'._('Analytics').' <b class="caret"></b></a>';
					echo '<ul role="menu" class="dropdown-menu">';
					foreach ($analytics_drop_down as $k) {
						$style_li = '';
						if ($module_id == $k && $current_file != 'custom_report') $style_li = 'active' ;
						echo '<li class="'.$style_li.'"><a href="'.NavigationControl::getNavigationLink($modules_with_full_info[$k]["name"],"index").'">'.$modules_with_full_info[$k]["label"].'</a></li>';
					}
					$style_li = '';
					if ($current_file == 'custom_report') $style_li = 'active' ;
					echo '<li class="'.$style_li.'"><a href="'.NavigationControl::getNavigationLink($modules_with_full_info[$k]["name"],"custom_report").'">'._('Custom Reports').'</a></li>';
					echo '</ul>';
					echo '</li>';
				}
				echo '</ul>';
			}
			?>
               
            <ul class="nav navbar-nav navbar-right">
				<?php
				$user_profile = '';
				if (isset($_SESSION["do_user"]) && $_SESSION["do_user"]->iduser != '') {
					if ($_SESSION["do_user"]->user_avatar != '') {
						//$user_profile .= '<div id="user-profile"><div class="circular_35" style="background-image: url(\''.FieldType12::get_file_name_with_path($_SESSION["do_user"]->user_avatar,'s').'\')"></div></div>';
						$user_profile .= '<div id="user-profile" style="top:-5px;float:left;left:-5px;position:relative;"><div class="circular_35" style="background-image: url(\'http://demo.sqcrm.com/cache/thumb/ths_1436945322.jpg\')"></div></div>';	
					} else {
						$user_profile .=  '<div id="user-profile"><div>'._('Welcome,').' '.$_SESSION["do_user"]->firstname.'</div></div>' ;
					}
					echo '<li class="dropdown">';
					echo '<a data-toggle="dropdown" class="dropdown-toggle" href="#">'.$user_profile.' <b class="caret"></b></a>';
					echo '<ul role="menu" class="dropdown-menu">';
					if ($_SESSION["do_user"]->is_admin == 1) {
						$setting_li = '';
						if ($admin_modules === true) $setting_li = 'active' ;
						echo '<li class="'.$setting_li.'"><a href="/modules/Settings/profile_list">'._('Settings').'</a></li>';
					}
					echo '<li class=""><a href="#" onclick="changeUserAvatar();return false ;">'._('change avatar').'</a></li>';
					$e_logout = new Event("do_user->eventLogout");
					echo '<li><a href="/'.$e_logout->getUrl().'">logout</a></li>';
					echo '</ul>';
					echo '</li>';
				}
				?>
				<li>&nbsp;&nbsp;</li>
            </ul>
        </div>
    </nav>
</div>
<br />
<div id="server_side_message" style="height:40px;margin-top:2px;position:relative;"></div>
<?php
$_SESSION["do_crm_messages"]->get_messages(true);
// the session values are cleaned via ajax body onload , check /js/common.js 
?>
<!-- Javascript error message block -->
<div id="js_errors" style="display:none;"></div>