<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Custom reports list entry
* @author Abhik Chakraborty
*/
?>
<table class="datadisplay">  
	<thead>  
		<tr>  
			<th>#</th>  
			<th><?php echo _('Report Name');?></th>  
			<th><?php echo _('Description');?></th>  
		</tr>  
	</thead>
<?php
if (count($reports) > 0) {
	$cnt = 0;
?>
	<tbody>
		<?php
		foreach ($reports as $k=>$v) { ?>
		<tr>  
			<td width="5%"><?php echo ++$cnt;?></td>  
			<td width="30%">
				<a href="custom_report?path=<?php echo $v["path"]?>&resource=<?php echo $v["default_resource"]?>">
				<?php echo $v["title"];?>
				</a>
			</td>  
			<td width="65%"><?php echo nl2br($v["description"]);?></td>
		<?php
		}
		?>
	</tbody>
	<?php 
	} else { ?>
	<tbody>
		<tr>
			<td colspan=3><strong><?php echo _('No custom report found !'); ?></strong></td>
		</tr>
	<?php 
	} ?>
</table>