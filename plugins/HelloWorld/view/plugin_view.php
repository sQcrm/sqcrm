<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/  
?>
<div id="message"></div>
<strong>Plugin Hello World !! </strong>
<br />
This is a demo plugin for the detail view  <br />
The plugin is meant for 2 positions and you are currently viewing <?php echo ($plugin_position == 1 ? 'right block' : 'tab');?><br />
The module name is <?php echo $module_name ; ?> <br />
The record id is <?php echo $sqcrm_record_id; ?> <br />
Voila, now we can do some operation with these data.<br />
Below is a sample form <br /><br />
<hr class="form_hr">
<?php
if ($plugin_position == 1) {
	echo '<form class="form-horizontal" id="HelloWorld__eventSampleHelloWorld" name="HelloWorld__eventSampleHelloWorld"  method="post">';
?>
	<label class="control-label" for="sample_text"><?php echo _('Add some text');?></label>
	<div class="controls">
	<?php
		echo FieldType1::display_field('sample_text');
	?>
	</div>
	<hr class="form_hr">
	<div id="sample_submit_area">
		<input type="submit" class="btn btn-primary" id="sample_submit_button" value="<?php echo _('Save');?>"/>
	</div>
</form>
<?php
} else {
	echo '<form class="form-horizontal" id="HelloWorld__eventSampleHelloWorld_2" name="HelloWorld__eventSampleHelloWorld_2"  method="post">';
?>
	<label class="control-label" for="sample_text_2"><?php echo _('Add some text');?></label>
	<div class="controls">
	<?php
		echo FieldType1::display_field('sample_text_2');
	?>
	</div>
	<hr class="form_hr">
	<div id="sample_submit_area_2">
		<input type="submit" class="btn btn-primary" id="sample_submit_button" value="<?php echo _('Save');?>"/>
	</div>
</form>
<?php 
}
?>

<script type="text/javascript">
$(document).ready(function() {
	$('#HelloWorld__eventSampleHelloWorld').submit(function() {
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("HelloWorld->eventSampleHelloWorld");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>&idmodule=<?php echo $idmodule;?>&sqcrm_record_id=<?php echo $sqcrm_record_id;?>",
			data:"sample_text="+$("#sample_text").val(),
			beforeSubmit: function() {
				$("#sample_submit_area").html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
			},
			success:  function(html) {
				display_js_success(html,'message');
			}
		});
        return false;
    });
    
    $('#HelloWorld__eventSampleHelloWorld_2').submit(function() {
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("HelloWorld->eventSampleHelloWorld");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>&idmodule=<?php echo $idmodule;?>&sqcrm_record_id=<?php echo $sqcrm_record_id;?>",
			data:"sample_text="+$("#sample_text_2").val(),
			beforeSubmit: function() {
				$("#sample_submit_area_2").html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
			},
			success:  function(html) {
				display_js_success(html,'message');
			}
		});
        return false;
    });
});
</script>