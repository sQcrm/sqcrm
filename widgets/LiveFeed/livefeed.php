<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* live activity feed
* @author Abhik Chakraborty
*/  
 
$do_feed_display = new LiveFeedDisplay();
if (isset($_REQUEST["livefeed"]) && $_REQUEST["livefeed"]== true) {
	$live_feed = $do_feed_display->load_live_feed();
	if (count($live_feed) > 0) {
?>
		<div class="notes_content">
			<?php if (strlen($live_feed["avatar"]) > 3) { ?>
			<img src="<?php echo $live_feed["avatar"]?>" style="width:20px;height:20px;" />
			<?php } else { ?>
			<span class="add-on"><i class="icon-user"></i></span>
			<?php } ?>
			<strong><?php echo $live_feed["user_name"];?></strong>
			<p id="content_">
				<?php echo $live_feed["content"];?>
			</p>
			<span class="notes_date_added"><?php echo $live_feed["action_date"];?></span>
		</div>
		<hr class="form_hr">
<?php
	} else { echo "0"; }
} else {
	if (isset($_REQUEST["start"]) && isset($_REQUEST["max"])) {
		$start = (int)$_REQUEST["start"] ;
		$max= (int)$_REQUEST["max"] ;
	} else {
		$start = 0 ;
		$max= 0 ;
	}
	$live_feeds = $do_feed_display->display_feed('',$start,$max);
	if (count($live_feeds) > 0) {
		foreach ($live_feeds as $live_feed) {
?>
			<div class="notes_content">
				<?php if (strlen($live_feed["avatar"]) > 3) { ?>
					<img src="<?php echo $live_feed["avatar"]?>" style="width:20px;height:20px;" />
				<?php } else { ?>
					<span class="add-on"><i class="icon-user"></i></span>
				<?php } ?>
				<strong><?php echo $live_feed["user_name"];?></strong>
				<p id="content_">
					<?php echo $live_feed["content"];?>
				</p>
				<span class="notes_date_added"><?php echo $live_feed["action_date"];?></span>
			</div>
			<hr class="form_hr">
<?php 
		} 
	} else { echo "0"; }
}
?>