<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/  
?>
<?php
if (is_array($twitter_handlers) && count($twitter_handlers) > 0) {
	foreach ($twitter_handlers as $handler) {
	?>
	<a class="twitter-timeline" href="https://twitter.com/<?php echo $handler;?>" height="500">
	<?php echo _('Tweets by');?> <?php echo $handler;?>
	</a> 
	<script async src="//platform.twitter.com/widgets.js" charset="utf-8">
	<br />
	<hr class="form_hr">
	<?php
	}
} else {
?>
<strong><?php echo _('Twitter Timeline');?></strong>
<br />
<hr class="form_hr">
<?php
	echo _('No twitter handler found to show timeline');
}
?>