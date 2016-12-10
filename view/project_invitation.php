<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* project invitation view
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
		<?php
			if (false === $allow_action) {
			?>
			<div class="datadisplay-outer">
				<div class="alert alert-danger">
					<strong>
					<?php
					echo _('The project invitation you are trying to access is invalid, please check again.');
					?>
					</strong>
				</div>
			</div>
		<?php
		} else {
		?>
			<div class="datadisplay-outer">
				<div class="alert alert-info">
				<?php
				echo _('You are invited to join the project ').'<strong>'.$project_name.'</strong>';
				?>
				</div>
				<hr class="form_hr">
				<?php
				$e_accept = new Event("Project->eventAcceptRejectProjectInvitation");
				$e_accept->addParam('id',$idinvite);
				$e_accept->addParam('idproject',$idproject);
				$e_accept->addParam('action','accept');
				
				$e_reject = new Event("Project->eventAcceptRejectProjectInvitation");
				$e_reject->addParam('id',$idinvite);
				$e_reject->addParam('idproject',$idproject);
				$e_reject->addParam('action','reject');
				?>
				<a href="/<?php echo $e_accept->getUrl();?>">
					<button type="button" class="btn btn-primary"><?php echo _('accept')?></button>
				</a>
				
				<a href="/<?php echo $e_reject->getUrl();?>">
					<button type="button" class="btn btn-warning"><?php echo _('reject')?></button>
				</a>
			</div>
		<?php
		}
		?>
		</div>
	</div>
</div>

