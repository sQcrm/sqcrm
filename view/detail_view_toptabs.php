<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* detail view top nav
* @author Abhik Chakraborty
*/  
?>
<?php
if ($converted_lead == true && $module_id == 3) {
	// do nothing
} else {
?>
<div class="tabtable">
	<ul class="nav nav-tabs">
		<?php
		if ($current_file == 'detail') { 
			$detail_css = "active"; 
			$detail_nav = "#" ;
		} else {  
			$detail_css =""; 
			$detail_nav = NavigationControl::getNavigationLink($module,"detail",$sqcrm_record_id);
		}
		if ($current_file == 'history') { 
			$history_css = "active"; 
			$history_nav = "#";
		} else {  
			$history_css =""; 
			$history_nav = NavigationControl::getNavigationLink($module,"history",$sqcrm_record_id);
		}
		if ($current_file == 'related') {
			$related_css = "active"; 
			$related_nav = "#" ;
		} else { 
			$related_css =""; 
			$related_nav = NavigationControl::getNavigationLink($module,"related",$sqcrm_record_id);
		}
		?>
    
		<li id = "topbar_detail" class="<?php echo $detail_css ; ?>">
			<a href="#" onclick = "load_deail_view_data('<?php echo $module?>','<?php echo $sqcrm_record_id?>','detail')" data-toggle ="tab" >
			<?php echo _('Detail Information ');?>
			</a>
		</li>
		<li id = "topbar_history" class="<?php echo $history_css ; ?>">
			<a href="#" onclick = "load_deail_view_data('<?php echo $module?>','<?php echo $sqcrm_record_id?>','history')" data-toggle ="tab" >
			<?php echo _('History');?>
			</a>
		</li>
		<?php
		if ($module_id != 2) {
		?>
		<li id="topbar_related" class="<?php echo $related_css ; ?>">
			<a href="#" onclick = "load_deail_view_data('<?php echo $module?>','<?php echo $sqcrm_record_id?>','related')" data-toggle ="tab" >
			<?php echo _('Related Information');?>
			</a>
		</li>
		<?php 
		} ?>
	</ul>
</div>
<?php
}
?>