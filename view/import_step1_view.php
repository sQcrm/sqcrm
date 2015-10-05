<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Import Step1 view 
* @author Abhik Chakraborty
*/ 
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12" style="margin-left:3px;">
			<div class="row-fluid">
				<div class="datadisplay-outer">
					<?php
					if ($allow_import === false) {
					echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:20px;margin-left:200px;margin-right:200px;">';
					echo '<h4>';
					echo _('Import not allowed !');
					echo '</h4>';
					echo $msg ;
					echo '</div>';
					} else {
					$e_import = new Event("do_import->eventImportStep1");
					echo '<form class="form-horizontal" id="do_import__eventImportStep1" name="do_import__eventImportStep1" action="/eventcontroler.php" method="post" enctype="multipart/form-data">';
					echo $e_import->getFormEvent();
					?>
					<div class="alert alert-info">
						<h4>
						<?php
						echo _("Import Step1");
						?>
						</h4>
						<?php
						echo _('Select the CSV file first which needs to be imported. Please check if the file is having header.');
						echo '<br />';
						echo _('* For date related information please make sure csv file has date format in one of - mm/dd/yyyy,mm-dd-yyyy,yyyy/mm/dd,yyyy-mm-dd');
						echo '<br />';
						echo _('* For checkbox related data please make sure csv file has data as - yes/no or 1/0');
						?>
					</div>
					<div class="control-group">  
						<label class="control-label" for="import_file"><?php echo _('Select a csv file')?></label>  
						<div class="controls">  
							<input type="file" name="import_file" id="import_file" class="input-xlarge-100"> 
						</div>
					</div>
					<div class="control-group">  
						<label class="control-label" for="has_header"><?php echo _('CSV has header ? ')?></label>  
						<div class="controls">  
							<input type="checkbox" name="has_header" id="has_header" class=""> 
						</div>
					</div>
					<div class="form-actions">  
						<a href="<?php echo NavigationControl::getNavigationLink($_SESSION["do_module"]->modules_full_details[$_SESSION["do_import"]->get_import_module_id()]["name"],"list");?>" class="btn btn-inverse">
						<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
						<input type="submit" class="btn btn-primary" value="<?php echo _('Next');?>"/>
					</div>          
					</form>
					<?php 
					} ?>
				</div>
			</div><!--/row-->
		</div><!--/span-->
	</div><!--/row-->
</div>