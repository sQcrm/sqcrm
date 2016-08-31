<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/  
?>
<strong><?php echo _('Email activity on sendwithus');?></strong>
<hr class="form_hr">
<?php
	if ($err != '') {
		echo '<div class="alert alert-danger">';
		echo $err;
		echo '</div>';
	} else {
		if ($primary_email != '') {
			echo '<br />';
			echo _('Primary Email : ').$primary_email;
			echo '<br /><br />';
			if (count($primary_email_log_data) > 0) { ?>
			<table class="datadisplay">
				<thead>
					<tr>
						<th width="30%"><?php echo _('Date Sent');?></th> 
						<th width="50%"><?php echo _('Template Name');?></th>
						<th width="20%"><?php echo _('Status');?></th> 
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($primary_email_log_data as $key=>$val) { ?>
					<tr>
						<td><?php echo $val['sent_at'];?></td>
						<td><?php echo $val['template_name'];?></td>
						<td><?php echo $val['status'];?></td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
			<?php	
			} else {
				echo _('No activity found for this email id');
				echo '<br />';
			}
			echo '<hr class="form_hr">';
		}
		
		if ($secondary_email != '') {
			echo '<br />';
			echo _('Secondary Email : ').$secondary_email;
			echo '<br /><br />';
			if (count($secondary_email_log_data) > 0) { ?>
			<table class="datadisplay">
				<thead>
					<tr>
						<th width="30%"><?php echo _('Date Sent');?></th> 
						<th width="50%"><?php echo _('Template Name');?></th>
						<th width="20%"><?php echo _('Status');?></th> 
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($secondary_email_log_data as $key=>$val) { ?>
					<tr>
						<td><?php echo $val['sent_at'];?></td>
						<td><?php echo $val['template_name'];?></td>
						<td><?php echo $val['status'];?></td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
			<?php	
			} else {
				echo _('No activity found for this email id');
			}
		}
	}
?>