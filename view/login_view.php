<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Login view
* @author Abhik Chakraborty
*/  
?>
<div class="container">    
	<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
		<div class="panel panel-info" >
			<div class="panel-heading">
				<div class="panel-title"><?php echo _('Sign In');?></div>
			</div>     
			<div style="padding-top:30px" class="panel-body" >
				<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
					<?php
					$e_login = new Event("User->eventLogin");
					if ($login_next_url == '') {
						$goto_after_login = NavigationControl::getNavigationLink("Home","index") ;
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
					echo '<form role="form" class="form-horizontal" id="User__eventLogin" name="User__eventLogin" action="/eventcontroler.php" method="post">';
					echo $e_login->getFormEvent();
					?>
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input name="user_name" id="user_name" type="text" class="form-control" value="" placeholder="<?php echo _('Username');?>">                                        
					</div>
					
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input name="user_password" id="user_password" type="password" class="form-control" placeholder="<?php echo _('Password');?>">
					</div>

					<div style="margin-top:10px" class="form-group">
						<!-- Button -->
						<div class="col-sm-12 controls">
							<input type="submit" name="login_submit" class="btn btn-success" value="<?php echo _('Login');?>">
						</div>
					</div>
				</form>     
			</div>                     
		</div>  
	</div>
</div>
