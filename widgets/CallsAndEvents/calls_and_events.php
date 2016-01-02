<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Home page call and events
* @author Abhik Chakraborty
*/ 
?>
<link rel="stylesheet" href="/themes/custom-css/eventcal/eventCalendar.css">
<link rel="stylesheet" href="/themes/custom-css/eventcal/eventCalendar_theme_responsive.css">
<?php
include_once(BASE_PATH.'/widgets/CallsAndEvents/CallsAndEvents.class.php') ;
$current_date = TimeZoneUtil::get_user_timezone_date();
$current_date_exploded = explode("-",$current_date);
$current_year = $current_date_exploded[0];
$current_month = $current_date_exploded[1];

if (isset($_REQUEST["y"]) && isset($_REQUEST["m"])) {
	$year = (int)$_REQUEST["y"];
	$month = (int)$_REQUEST["m"];
	if (isset($_REQUEST["c"])) {
		$change_type = $_REQUEST["c"] ;
		if ($change_type == 'p') {
			$ym = date("Y-m-d",strtotime("- 1 month",strtotime($year.'-'.$month.'-01')));
		} elseif ($change_type == 'n') {
			$ym = date("Y-m-d",strtotime("+ 1 month",strtotime($year.'-'.$month.'-01')));
		}
		$year = date("Y",strtotime($ym));
		$month = date("m",strtotime($ym));
	}
} else {
	$year = $current_year;
	$month = $current_month;
}
//if($current_year == $year)
$currentday = ($month == $current_month && $year == $current_year) ? date('j', time()) : 0;

$first_of_month = mktime(0,0,0,$month,1,$year);
$first_day = strftime('%w', $first_of_month);
$monthname = date('F', mktime(0,0,0,$month,1));
$days_in_month = date('t', $first_of_month);

$calendar = new CallsAndEvents();
$day_with_events = $calendar->get_events_count_by_day($year,$month);

$load_for_day = false ;
if (isset($_REQUEST["d"])) {
	$events_day = (int)$_REQUEST["d"] ;
	$load_for_day = true ;
}
if ($load_for_day === false) {
?>
<div data-current-year="<?php echo $year;?>" class="eventCalendar-wrap">
	<div style="height: 212px;" class="eventsCalendar-slider">
		<div style="width:100%" class="eventsCalendar-monthWrap currentMonth">
			<div class="eventsCalendar-currentTitle">
              <a href="#" class="monthTitle" onClick="return false;"><?php echo $monthname.' '.$year;?></a>
            </div>
            <ul class="eventsCalendar-daysList showAsWeek showDayNames">
				<li class="eventsCalendar-day-header"><?php echo _('sun');?></li>
				<li class="eventsCalendar-day-header"><?php echo _('mon');?></li>
				<li class="eventsCalendar-day-header"><?php echo _('tue');?></li>
				<li class="eventsCalendar-day-header"><?php echo _('wed');?></li>
				<li class="eventsCalendar-day-header"><?php echo _('thu');?></li>
				<li class="eventsCalendar-day-header"><?php echo _('fri');?></li>
				<li class="eventsCalendar-day-header"><?php echo _('sat');?></li>
				<?php
				$day = 1;
				for ($y = 0; $y < 6; $y++) {
					for ($x = 0; $x < 7; $x++) {
						if (($day == 1 && $x < $first_day) || $day > $days_in_month) {
							echo '<li class="eventsCalendar-day empty"></li>'."\n";
						} else { 
							$strDay = ($day < 10) ? '0' . $day : $day;
							if (array_key_exists($day,$day_with_events)) {
								echo '<li id="dayList_'.$day.'" rel="'.$day.'" class="eventsCalendar-day dayWithEvents">';
								echo '<a href="#" onClick="load_events_for_day(\''.$year.'\',\''.$month.'\',\''.$day.'\');return false;">'.$day.'</a>';
							} elseif ($day == $currentday) {
								echo '<li id="dayList_'.$day.'" rel="'.$day.'" class="eventsCalendar-day today">';
								echo '<a href="#" onClick="return false;">'.$day.'</a>';
							} else {
								echo '<li id="dayList_'.$day.'" rel="'.$day.'" class="eventsCalendar-day">';
								echo '<a href="#" onClick="return false;">'.$day.'</a>';
							}
							echo '</li>';
							$day++;
						}
					}
					if ($day > $days_in_month) break;
				}
              ?>
          </ul>
        </div>
        <a href="#" class="arrow prev" onClick="change_month_events('p','<?php echo $year;?>','<?php echo $month;?>');return false;">
            <span>prev</span>
        </a>
        <a href="#" class="arrow next" onClick="change_month_events('n','<?php echo $year;?>','<?php echo $month;?>');return false;">
          <span>next</span>
        </a>
      </div>
      <div style="width: 100%;" class="eventsCalendar-list-wrap" id="events_per_day_list"></div>
  </div>
<?php
} elseif ($load_for_day === true) {
	$full_day_of_event = $year."-".$month."-".$events_day ; 
    $calendar->get_all_events_by_day($year,$month,$events_day);
    if ($calendar->getNumRows() > 0) {
		echo '<p style="font-size: 13px;color :#dadddc;font-weight: bold;">'.date('l jS F Y',strtotime($full_day_of_event)).'</h5>';
		echo '<div class="eventsCalendar-list-content scrollable">';
		echo '<ul style="opacity: 1; left: 0px; height: auto; display: block;" class="eventsCalendar-list">';
		while ($calendar->next()) {
			$event_link = NavigationControl::getNavigationLink("Calendar","detail",$calendar->idevents);
			echo '<li id="'.$calendar->idevents.'" style="margin-top:2px;">';
			echo '<time><small>'.FieldType10::display_value($calendar->start_time).'</small></time>';
			echo '<a href="'.$event_link.'" target="_blank"><span class="eventTitle"><small>'.$calendar->event_type.'::</small>'.$calendar->subject.'</span></a>';
			echo '<p class="eventDesc">'.nl2br($calendar->description).'</p>';
			echo '</li>';
			echo '<hr class="form_hr">';
		}
		echo '</ul>';
		echo '</div>';
	}
}
?>