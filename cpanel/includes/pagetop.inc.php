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
			if (isset($_SESSION["do_cpaneluser"]) && is_object($_SESSION["do_cpaneluser"]) && $_SESSION["do_cpaneluser"]->idcpanel_user > 0 && $_SESSION["do_cpaneluser"]->idcpanel_user !='') {
				$available_user_modules = $_SESSION["do_cpanel_action_permission"]->get_cpanel_user_modules() ;
				$cpanel_modules = $_SESSION["do_cpanel_action_permission"]->get_cpanel_modules() ;
				$module_full_details = $_SESSION["do_cpanel_action_permission"]->full_module_details ;
				if (count($available_user_modules) > 0) {
					echo '<ul class="nav">' ;
					foreach ($available_user_modules as $idmodules) {
						if ($idmodules == 7) continue ;
						$style_li = '';
						if ($idmodules == $module_id) $style_li = 'active' ;
						echo '<li class="'.$style_li.'">' ;
						echo '<a href="/cpanel/modules/'.$module_full_details[$idmodules]["name"].'/index">'.$module_full_details[$idmodules]["module_label"].'</a>';
					}
					echo '</ul>';
				}
			}
			?>
			<ul class="nav pull-right">
				<?php
					$user_profile = '';				
					if (isset($_SESSION["do_cpaneluser"]) && $_SESSION["do_cpaneluser"]->idcpanel_user != '') {
						if ($_SESSION["do_cpaneluser"]->contact_avatar != '') {
							$user_profile .= '<div id="user-profile"><div class="circular_35" style="background-image: url(\''.FieldType12::get_file_name_with_path($_SESSION["do_cpaneluser"]->contact_avatar,'s').'\')"></div></div>';
						} else {
							$user_profile .=  '<div id="user-profile"><div style="margin-top:7px;">'._('Welcome,').' '.$_SESSION["do_cpaneluser"]->firstname.'</div></div>' ;
						}
						echo '<li class="dropdown">';
						echo $user_profile ;
						echo '<ul class="dropdown-menu">';
						echo '<li class=""><a href="/cpanel/modules/User/profile_settings">'._('Profile Settings').'</a></li>';
						$e_logout = new Event("do_cpaneluser->eventLogout");
						echo '<li><a href="/cpanel/'.$e_logout->getUrl().'">logout</a></li>';
						echo '</ul>';
						echo '</li>' ;
					}
					?>
			</ul>
		</div>
	</div>
</div>
<br />
<div id="server_side_message" style="height:40px;margin-top:2px;position:relative;"></div>
<?php
$_SESSION["do_cpanel_messages"]->get_messages(true);
// the session values are cleaned via ajax body onload , check /js/common.js 
?>
<!-- Javascript error message block -->
<div id="js_errors" style="display:none;"></div>