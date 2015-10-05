<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* reports view entry per folder
* @author Abhik Chakraborty
*/
?>
<p><?php echo $folder_name; ?></p>
<table class="datadisplay">  
	<thead>  
		<tr>  
			<th>#</th>  
			<th><?php echo _('Report Name');?></th>  
			<th><?php echo _('Description');?></th>  
			<th><?php echo _('Action')?></th>  
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
			<td><?php echo ++$cnt;?></td>  
			<td>
				<a href="<?php echo NavigationControl::getNavigationLink($module,"run_report",$v["idreport"])?>">
				<?php echo $v["name"];?>
				</a>
			</td>  
			<td><?php echo nl2br($v["description"]);?></td>
			<td>
				<a href="<?php echo NavigationControl::getNavigationLink($module,"edit",$v["idreport"])?>" class="btn btn-primary btn-mini">
				<i class="icon-white icon-edit"></i>
				</a>
				<a href="#" onclick = "del_report('<?php echo $v["idreport"];?>','<?php echo $val["idreport_folder"];?>','<?php echo $folder_name;?>')" class="btn btn-primary btn-mini bs-prompt" id="">
				<i class="icon-white icon-trash"></i>
				</a>
			</td>  
		<?php
		}
		?>
	</tbody>
	<?php 
	} ?>
</table>