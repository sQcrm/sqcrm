<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Login view
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span3"></div>
		<div class="span6" style="margin-left:3px;">
			<div class="box_content">
				<?php
				$e_login = new Event("\cpanel_user\User->eventLogin");
				if ($login_next_url == '') {
					$goto_after_login ="/cpanel/modules/Home/index";
				} else {
				// need to check if cross-side script is added 
					$goto_after_login = $_SERVER['REQUEST_URI'];
					// lets do some purify
					$goto_after_login = strip_tags($goto_after_login);
				}
				$e_login->addParam("goto",$goto_after_login);
				if ((int)$sqcrm_record_id > 0) {
					$e_login->addParam("sqrecord",$sqcrm_record_id);
				}
				echo '<form class="form-horizontal" id="\cpanel_user\User__eventLogin" name="\cpanel_user\User__eventLogin" action="'.CPANEL_EVENTCONTROLER_PATH.'eventcontroler.php" method="post">';
				echo $e_login->getFormEvent();
				?>
				<table style="margin: 0 auto;">
					<tr>
						<td align="center">
							<strong><?php echo _('Customer Portal');?></strong>
							<hr class="form_hr">
							<br />
						</td>
					</tr>
					<tr>
						<td align="">
							<span class="add-on"><i class="icon-user"></i></span>
							<input name="user_name" id="user_name" class="username" placeholder="<?php echo _('Email');?>" type="text">
						</td>
					</tr>
					
					<tr>
						<td align="">
							<span class="add-on"><i class="icon-lock"></i></span>
							<input name="user_password" id="user_password" class="password" placeholder="<?php echo _('Password');?>" type="password">
						</td>
					</tr>
					
					<tr>
						<td align="right">
							<input type="submit" name="login_submit" class="btn btn-primary" value="<?php echo _('Login');?>">
						</td>
					</tr>
				</table>
				</form>
			</div>
		</div>
		<div class="span3"></div>
		</div><!--/span-->
	</div><!--/row-->
</div>