<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* class RecurrentEvents 
* @author Abhik Chakraborty
*/ 
	

class RecurrentEvents extends DataObject {
	public $table = "recurrent_events";
	public $primary_key = "idrecurrent_events";

	/* holds the recurrent option selected */
	public $recurrent_option_selected = '';

	/* repeat end type 1: num of occurence , 2: end date */
	protected $repeat_end_type = '';

	/* repeat end type num of occurence */
	protected $repeat_end_num_occurence = '';

	/* repeat end with date instead of num of occurence */
	protected $repeat_end_date = '';

	/* repeat frequency options 1 to 30 */
	protected $repeat_freq_opts = '';

	/* week option for repeat weeks 0 to 6 i.e. sun to sat */
	protected $repeat_week_week_opts = array();

	/* repeat monthly options 1: day of month , 2: on particular day of week in a month */
	protected $repeat_monthly_opts = '';

	/* repeat monthly option days */
	protected $repeat_monthly_opts_days = '';

	/* repeat monthly options week frequency first to fifth occurence of the week */
	protected $repeat_monthly_opts_week_freq = '';

	/* repeat monthly options day of the week 0 to 6 i.e. sun to sat */
	protected $repeat_monthly_opts_week_weekdays = '';
		
	/**
	* function to get the recurrent options
	* @return array $recurrent_options
	*/
	public function get_recurrent_options() {
		$recurrent_options = array(
			1=> _('Daily'),
			2=> _('Every Weekdays (Monday to Friday)'),
			3=> _('Every Monday,Wednesday,Friday'),
			4=>_('Every Tuesday and Thursday'),
			5=>_('Weekly'),
			6=>_('Monthly'),
			7=>_('Yearly')
		);
		return $recurrent_options;
	}
		
	public function get_days_in_week() {
		$days = array(
			7=>_('sun'),
			1=>_('mon'),
			2=>_('tue'),
			3=>_('wed'),
			4=>_('thu'),
			5=>_('fri'),
			6=>_('sat') 
		);
		return $days;
	}
		
	public function get_text_options() {
		$text_options = array(
			1=>_('days'),
			5=>_('weeks'),
			6=>_('months'),
			7=>_('years')
		);
		return $text_options ;
	}
		
	/**
	* function to get the last occurence date from start date and recurrent options
	* @param integer $recurrent_options
	* @param date $start_date
	* @return $last_occurence
	*/
	public function get_repeat_last_occurence_date($recurrent_options,$start_date) {
		$end_type = $this->get_repeat_end_type();
		if ($end_type == 1 ) {
			$repeat_frequency = $this->get_repeat_freq_opt();
			$end_occ = $this->get_repeat_end_num_occurence();
			if ($recurrent_options == 1) {
				$end_occ = $end_occ * $repeat_frequency ;
				$last_occurence = date("Y-m-d",strtotime("+ $end_occ days",strtotime($start_date)));
			} elseif ($recurrent_options == 2) {
				$last_occurence = date("Y-m-d",strtotime("+ $end_occ days",strtotime($start_date)));
			} elseif ($recurrent_options == 6) {
				$last_occurence = date("Y-m-d",strtotime("+ $end_occ months",strtotime($start_date)));
			} elseif ($recurrent_options == 7) {
				$last_occurence = date("Y-m-d",strtotime("+ $end_occ years",strtotime($start_date)));
			}
		} else {
			$last_occurence = $this->get_repeat_end_date() ; //FieldType9 :: convert_before_save( $this->get_repeat_end_date() );
		}
		return $last_occurence ;
	}
		
	/**
	* function to set the recurrent option selected
	* @param integer $recurrent_option
	*/
	public function set_recurrent_option_selected($recurrent_option) {
		$this->recurrent_option_selected = $recurrent_option ;
	}
		
	/**
	* function to get the recurrent option selected
	* @return recurrent_option_selected
	*/
	public function get_recurrent_option_selected() {
		return $this->recurrent_option_selected ;
	}
	
	/**
	* function to set the repeat end type 1: num of occurence , 2: with an end date
	* @param integer $opts
	*/
	public function set_repeat_end_type($opts) {
		$this->repeat_end_type = $opts ;
	}
		
	/**
	* function to get the repeat end type
	* @return repeat_end_type
	*/
	public function get_repeat_end_type() {
		return $this->repeat_end_type ;
	}
		
	/**
	* function to set the repeat end with the number of occurence
	* @param integer $occ
	*/
	public function set_repeat_end_num_occurence($occ) {
		$this->repeat_end_num_occurence = $occ ;
	}
		
	/**
	* function to get the repeat end with the num of occurence
	* @return repeat_end_num_occurence
	*/
	public function get_repeat_end_num_occurence() {
		return $this->repeat_end_num_occurence ;
	}
		
	/**
	* function to set the repeat end with a date
	* @param date $end_date
	*/
	public function set_repeat_end_date($end_date) {
		$this->repeat_end_date = FieldType9 :: convert_before_save($end_date) ;
	}
		
	/**
	* function to get the repeat end when date is set for repeat end
	* @return repeat_end_date
	*/
	public function get_repeat_end_date() {
		return $this->repeat_end_date ;
	}
		
	/**
	* function to set the repeat frequency option 
	* @param integer $opt
	*/
	public function set_repeat_freq_opts($opt){
		$this->repeat_freq_opts = $opt ;
	}
		
	/**
	* function to get the repeat frequency
	* @return repeat_freq_opts
	*/
	public function get_repeat_freq_opt() {
		return $this->repeat_freq_opts ;
	}
		
	/**
	* function to set the repeat week options if week days are selected
	* @param array $opts
	*/
	public function set_repeat_week_week_opts($opts) { 
		$this->repeat_week_week_opts = $opts;
	}
		
	/**
	* function to get the repeat week options when the week days are selected
	* @return repeat_week_week_opts
	*/
	public function get_repeat_week_week_opts() {
		return $this->repeat_week_week_opts ;
	}
		
	/**
	* function to set the repeat monthly options
	* @param integer $opt
	*/
	public function set_repeat_monthly_opts($opt){
		$this->repeat_monthly_opts = $opt;
	}
		
	/**
	* function to get the repeat monthly opts
	* @return repeat_monthly_opts
	*/
	public function get_repeat_monthly_opts() {
		return $this->repeat_monthly_opts ;
	}
		
	/**
	* function to set the repeat monthly options days when repeat_monthly_opts = 1
	* @param integer $days
	*/
	public function set_repeat_monthly_opts_days($days) {
		$this->repeat_monthly_opts_days = $days ;
	}
		
	/**
	* function to get the repeat monthly options days when repeat_monthly_opts = 1
	* @return repeat_monthly_opts_days
	*/
	public function get_repeat_monthly_opts_days() {
		return $this->repeat_monthly_opts_days ;
	}
		
	/**
	* function to set the repeat monthly options week frequency when repeat_monthly_opts = 2
	* @param integer $freq
	*/
	public function set_repeat_monthly_opts_week_freq($freq) {
		$this->repeat_monthly_opts_week_freq = $freq ;
	}
		
	/**
	* function to get the repeat monthly options week frequency when repeat_monthly_opts = 2
	* @return repeat_monthly_opts_week_freq
	*/
	public function get_repeat_monthly_opts_week_freq() {
		return $this->repeat_monthly_opts_week_freq ;
	}
		
	/**
	* function to set the repeat monthly options day of a week when repeat_monthly_opts = 2
	* @param integer $day
	*/
	public function set_repeat_monthly_opts_week_weekdays($day) { 
		$this->repeat_monthly_opts_week_weekdays = $day ;
	}
		
	/**
	* function to get the repeat monthly options day of a week when repeat_monthly_opts = 2
	* @return repeat_monthly_opts_week_weekdays
	*/
	public function get_repeat_monthly_opts_week_weekdays() {
		return $this->repeat_monthly_opts_week_weekdays ;
	}
		
	/**
	* function to get the recurrent options pattern
	* @return array $pattern
	*/
	public function get_recurrent_event_pattern() {
		$pattern = array();
		$pattern["recurrent_options"] = $this->get_recurrent_option_selected();
		$pattern["repeat_end_opts"] = $this->get_repeat_end_type();
		$pattern["repeat_end_num_occurence"] = $this->get_repeat_end_num_occurence();
		$pattern["repeat_end_date"] = $this->get_repeat_end_date();
		$pattern["repeat_freq_opts"] = $this->get_repeat_freq_opt();
		$pattern["weekly_opts"] = $this->get_repeat_week_week_opts();
		$pattern["repeat_monthly_opts"] = $this->get_repeat_monthly_opts();
		$pattern["repeat_monthly_opts_days"] = $this->get_repeat_monthly_opts_days();
		$pattern["repeat_monthly_opts_week_freq"] = $this->get_repeat_monthly_opts_week_freq();
		$pattern["repeat_monthly_opts_week_weekdays"] = $this->get_repeat_monthly_opts_week_weekdays();
		return $pattern ;
	}
	
	/**
	* function to get the recurrent dates
	* @param object $evctl
	* @return array containing the recurrent dates
	*/
	public function get_recurrent_dates($evctl) {
		$recurrent_options = (int)$evctl->recurrent_options ;
		$this->set_recurrent_option_selected($recurrent_options);
		$repeat_end_type = (int)$evctl->repeat_end_opts ;
		$this->set_repeat_end_type($repeat_end_type);
		if ($repeat_end_type == 1) {
			$repeat_end = (int)$evctl->repeat_end_num_occurence ;
			$this->set_repeat_end_num_occurence($repeat_end);
		} elseif ($repeat_end_type == 2) {
			$repeat_end = $evctl->repeat_end_date ;
			$this->set_repeat_end_date($repeat_end);
		}
		$start_date = FieldType9 :: convert_before_save($evctl->start_date);
		switch ($recurrent_options) {
			case 1 :
				$this->set_repeat_freq_opts((int)$evctl->repeat_freq_opts);
				return $this->get_recurrent_dates_daily($start_date);
				break;
			case 2 :
				return $this->get_recurrent_dates_weekdays($start_date);
				break;
			case 3 :
				return $this->get_recurrent_dates_mon_wed_fri($start_date);
				break ;
			case 4 :
				return $this->get_recurrent_dates_tue_thu($start_date);
				break ;
			case 5 :
				$this->set_repeat_freq_opts((int)$evctl->repeat_freq_opts);
				$this->set_repeat_week_week_opts($evctl->weekly_opts);
				return $this->get_recurrent_dates_weekly($start_date);
				break ;
			case 6 :
				$this->set_repeat_freq_opts((int)$evctl->repeat_freq_opts);
				$this->set_repeat_monthly_opts((int)$evctl->repeat_monthly_opts);
				if ((int)$evctl->repeat_monthly_opts == 1) {
					$this->set_repeat_monthly_opts_days((int)$evctl->repeat_monthly_opts_days);
				} elseif ((int)$evctl->repeat_monthly_opts == 2) {
					$this->set_repeat_monthly_opts_week_freq($evctl->repeat_monthly_opts_week_freq);
					$this->set_repeat_monthly_opts_week_weekdays($evctl->repeat_monthly_opts_week_weekdays);
				}
				return $this->get_recurrent_dates_monthly($start_date);
				break ;
			case 7 :
				$this->set_repeat_freq_opts((int)$evctl->repeat_freq_opts);
				return $this->get_recurrent_dates_yearly($start_date);
				break;
		}
	}
		
	/**
	* function to get the differece bewteen two dates
	* @param date $start_date
	* @param date $end_date
	* @param string $type
	* @return $diff
	*/
	public function get_start_end_difference($start_date,$end_date,$type) {
		$diff = abs(strtotime($end_date) - strtotime($start_date));
		$year_diff = floor($diff / 31536000);
		$mon_diff = floor($diff / 2628000);
		$day_diff = floor($diff / (60*60*24));
		$week_diff = ceil($day_diff / 7);
		if ($type == 'day') {
			return $day_diff ;
		} elseif ($type == 'week') {
			return $week_diff ;
		} elseif ($type == 'mon') {
			return $mon_diff ;
		} elseif ($type == 'year') {
			return $year_diff ;
		}
	}
		
	/**
	* function to get the recurrent dates daily
	* @param date $start_date
	* @see self::get_recurrent_dates()
	* @see self::get_repeat_last_occurence_date()
	* @see self::get_repeat_freq_opt()
	* @return array $recurrent_dates
	*/
	public function get_recurrent_dates_daily($start_date) {
		$end_type = $this->get_repeat_end_type();
		if ($end_type == 1) {
			$diff = $this->get_repeat_end_num_occurence();
		} else {
			$last_occurence_date = $this->get_repeat_end_date() ;
			$diff = $this->get_start_end_difference($start_date,$last_occurence_date,'day');
		}
		$repeat_frequency = $this->get_repeat_freq_opt();
		$recurrent_dates = array();
		$rec_date_start = $start_date ;
		$dates_count = 0 ;
		for ($i=1;$i<=$diff;$i++) {
			$rec_date = date("Y-m-d",strtotime("+ $repeat_frequency days",strtotime($rec_date_start)));
			$rec_date_start = $rec_date;
			if ($end_type == 1 ) {
				if ($dates_count > $diff) break ;
			} else {
				if (strtotime($rec_date) > strtotime($last_occurence_date)) break;
			}
			$recurrent_dates[] = $rec_date;
			$dates_count++ ;
		}
		return $recurrent_dates ;
	}
    
	/**
	* function to get the recurrent dates for weekdays
	* @param date $start_date
	* @see self::get_recurrent_dates()
	* @return array $recurrent_dates
	*/
	public function get_recurrent_dates_weekdays($start_date) {
		$end_type = $this->get_repeat_end_type();
		if ($end_type == 1) {
			$diff = $this->get_repeat_end_num_occurence();
		} else {
			$last_occurence_date = $this->get_repeat_end_date() ;
			$diff = $this->get_start_end_difference($start_date,$last_occurence_date,'week');
		}
		$recurrent_dates = array();
		$rec_date_start = $start_date ;
		$dates_count = 0 ;
		$day_numeric = date("N",strtotime($rec_date_start));
		if ($day_numeric > 0 && $day_numeric < 5) {
			for ($i=$day_numeric;$i<5;$i++) {
				$rec_date = date("Y-m-d",strtotime("+ 1 day",strtotime($rec_date_start)));
				$rec_date_start = $rec_date ;
				if ($end_type == 1) {
					if ($dates_count > $diff) break ;
				} else {
					if (strtotime($rec_date) > strtotime($last_occurence_date)) break;
				}
				$recurrent_dates[] = $rec_date ;
				$dates_count++ ;
			}
		} elseif ($day_numeric == 5) {
			$rec_date_start = date("Y-m-d",strtotime("+ 2 day",strtotime($rec_date_start)));  
		} elseif($day_numeric == 6) {
			$rec_date_start = date("Y-m-d",strtotime("+ 1 day",strtotime($rec_date_start)));  
		}
		for ($i=1;$i<=$diff;$i++) {
			for ($j=1;$j<=5;$j++) {
				$rec_date = date("Y-m-d",strtotime("+ 1 day",strtotime($rec_date_start)));
				$rec_date_start = $rec_date ;
				if ($end_type == 1) {
					if($dates_count > $diff ) break ;
				} else {
					if(strtotime($rec_date) > strtotime($last_occurence_date)) break;
				}
				$recurrent_dates[] = $rec_date ;
				$dates_count++ ;
				if ($j == 5) {
					$rec_date_start = date("Y-m-d",strtotime("+ 2 days",strtotime($rec_date_start)));
				}
			}
		}
		return $recurrent_dates ;
	}
    
	/**
	* function to get recurrent dates for mon,wed,fri
	* @param date $start_date
	* @see self::get_recurrent_dates()
	* @return array $recurrent_dates
	*/
	public function get_recurrent_dates_mon_wed_fri($start_date) {
		$end_type = $this->get_repeat_end_type();
		if ($end_type == 1) {
			$diff = $this->get_repeat_end_num_occurence();
		} else {
			$last_occurence_date = $this->get_repeat_end_date() ;
			$diff = $this->get_start_end_difference($start_date,$last_occurence_date,'week');
		}
		$recurrent_dates = array();
		$rec_date_start = $start_date ;
		$dates_count = 0 ;
		for ($i=1;$i<=$diff;$i++) {
			for ($j=1;$j<=5;$j++) {
				$rec_date = date("Y-m-d",strtotime("+ 1 day",strtotime($rec_date_start)));
				$rec_date_start = $rec_date ;
				$day_numeric = date("N",strtotime($rec_date_start));
				if ($end_type == 1) {
					if($dates_count > $diff) break ;
				} else {
					if (strtotime($rec_date) > strtotime($last_occurence_date)) break;
				}
				if ($day_numeric == 1 || $day_numeric == 3 || $day_numeric == 5) {
					$recurrent_dates[] = $rec_date ;
					$dates_count++;
				}
				if ($day_numeric == 5) {
					$rec_date_start = date("Y-m-d",strtotime("+ 2 days",strtotime($rec_date_start)));
				}
			}
		}
		return $recurrent_dates ;
	}
    
	/**
	* function to get the recurrent dates tuesday/thursday
	* @param date $start_date
	* @see self::get_recurrent_dates()
	* @return array $recurrent_dates
	*/
	public function get_recurrent_dates_tue_thu($start_date) {
		$end_type = $this->get_repeat_end_type();
		if ($end_type == 1) {
			$diff = $this->get_repeat_end_num_occurence();
		} else {
			$last_occurence_date = $this->get_repeat_end_date() ;
			$diff = $this->get_start_end_difference($start_date,$last_occurence_date,'week');
		}
		$recurrent_dates = array();
		$rec_date_start = $start_date ;
		$dates_count = 0 ;
		for ($i=1;$i<=$diff;$i++) {
			for ($j=1;$j<6;$j++) {
				$rec_date = date("Y-m-d",strtotime("+ 1 day",strtotime($rec_date_start)));
				$rec_date_start = $rec_date ;
				$day_numeric = date("N",strtotime($rec_date_start));
				if ($end_type == 1) {
					if ($dates_count > $diff) break ;
				} else {
					if (strtotime($rec_date) > strtotime($last_occurence_date)) break;
				}
				if ($day_numeric == 2 || $day_numeric == 4) {
					$recurrent_dates[] = $rec_date ;
					$dates_count++;
				}
				if ($day_numeric == 5) {
					$rec_date_start = date("Y-m-d",strtotime("+ 2 days",strtotime($rec_date_start))); // set to sunday
				}
			}
		}
		return $recurrent_dates ;
	}
    
	/**
	* function to get the recurrent dates weekly
	* @param date $start_date
	* @see self::get_recurrent_dates()
	* @see self::get_repeat_week_week_opts()
	* @see self::get_repeat_freq_opt()
	* @return array $recurrent_dates
	*/
	public function get_recurrent_dates_weekly($start_date) {
		$end_type = $this->get_repeat_end_type();
		if($end_type == 1) {
			$diff = $this->get_repeat_end_num_occurence();
		} else {
			$last_occurence_date = $this->get_repeat_end_date() ;
			$diff = $this->get_start_end_difference($start_date,$last_occurence_date,'week');
		}      
		$recurrent_dates = array();
		$repeat_frequency = $this->get_repeat_freq_opt();
		$week_day_options = $this->get_repeat_week_week_opts();
		$week_day_options_flag = false ;
		$dates_count = 0 ;
		
		if (is_array($week_day_options) && count($week_day_options) > 0) {
			$week_day_options_flag = true ;
		}
		$rec_date_start = $start_date ;
		for ($i=1;$i<=$diff;$i++) {
			$day_numeric = date("N",strtotime($rec_date_start));
			if ($day_numeric != 7) {
				$last_sunday = date("Y-m-d",strtotime("last sunday",strtotime($rec_date_start)));
				$rec_date = date("Y-m-d",strtotime("+ $repeat_frequency weeks",strtotime($last_sunday)));
			} else {
				$rec_date = date("Y-m-d",strtotime("+ $repeat_frequency weeks",strtotime($last_sunday)));
			}
			for ($j=1;$j<=7;$j++) {
				if($end_type == 1) {
					if ($dates_count > $diff) break ;
				} else {
					if (strtotime($rec_date) > strtotime($last_occurence_date)) break;
				}
				$day_numeric = date("N",strtotime($rec_date));
				if ($week_day_options_flag === true) {
					if (in_array($day_numeric,$week_day_options)) {
						$recurrent_dates[] = $rec_date ;
						$rec_date_start = $rec_date ;
						$dates_count++;
					}
				} else {
					$recurrent_dates[] = $rec_date ;
					$rec_date_start = $rec_date ;
					$dates_count++;
				}
				$rec_date = date("Y-m-d",strtotime("+ 1 day",strtotime($rec_date)));
			}
		}
		return $recurrent_dates ;
	}
    
	/**
	* function to get the recurrent dates monthly
	* @param date $start_date
	* @see self::get_recurrent_dates()
	* @see self::get_repeat_monthly_opts()
	* @see self::get_repeat_monthly_opts_days()
	* @see self::get_repeat_monthly_opts_week_freq()
	* @see self::get_repeat_monthly_opts_week_weekdays()
	* @return array $recurrent_dates
	*/
	public function get_recurrent_dates_monthly($start_date) {
		$end_type = $this->get_repeat_end_type();
		if ($end_type == 1) {
			$diff = $this->get_repeat_end_num_occurence();
		} else {
			$last_occurence_date = $this->get_repeat_end_date() ;
			$diff = $this->get_start_end_difference($start_date,$last_occurence_date,'week');
		}
		$recurrent_dates = array();
		$repeat_frequency = $this->get_repeat_freq_opt();
		$repeat_monthly_opts = $this->get_repeat_monthly_opts();
		$rec_date_start = $start_date ;
		$dates_count = 0 ;
		for ($i=1;$i<=$diff;$i++) {
			$rec_date = date("Y-m-d",strtotime("+ $repeat_frequency months",strtotime($rec_date_start)));
			$first_day_of_month = date("Y-m",strtotime($rec_date)).'-01';
			if ($repeat_monthly_opts == 1) {
				$repeat_monthly_opts_days = $this->get_repeat_monthly_opts_days();
				$rec_date = date("Y-m-d",strtotime("+ $repeat_monthly_opts_days days",strtotime("- 1 second ",strtotime($first_day_of_month))));
				if ($end_type == 1) {
					if ($dates_count > $diff) break ;
				} else {
					if (strtotime($rec_date) > strtotime($last_occurence_date)) break;
				}
				$recurrent_dates[] = $rec_date;
				$rec_date_start = $rec_date;
				$dates_count++;
			} elseif ($repeat_monthly_opts == 2) {
				$repeat_monthly_opts_week_freq = $this->get_repeat_monthly_opts_week_freq();
				$repeat_monthly_opts_week_weekdays = $this->get_repeat_monthly_opts_week_weekdays();
				$rec_date = date("Y-m-d",strtotime("$repeat_monthly_opts_week_freq $repeat_monthly_opts_week_weekdays of",strtotime($first_day_of_month)));
				if ($end_type == 1) {
					if ($dates_count > $diff) break ;
				} else {
					if (strtotime($rec_date) > strtotime($last_occurence_date)) break;
				}
				$recurrent_dates[] = $rec_date;
				$rec_date_start = $rec_date;
				$dates_count++;
			} 
		}
		return $recurrent_dates ;
	}
    
	/**
	* function to get the recurrent dates yearly
	* @param date $start_date
	* @see self::get_recurrent_dates()
	* @return array $recurrent_dates
	*/
	public function get_recurrent_dates_yearly($start_date) {
		$end_type = $this->get_repeat_end_type();
		if ($end_type == 1) {
			$diff = $this->get_repeat_end_num_occurence();
		} else {
			$last_occurence_date = $this->get_repeat_end_date() ;
			$diff = $this->get_start_end_difference($start_date,$last_occurence_date,'week');
		}
		$recurrent_dates = array();
		$repeat_frequency = $this->get_repeat_freq_opt();
		$rec_date_start = $start_date ;
		$dates_count = 0 ;
		for ($i=1;$i<=$diff;$i++) {
			$rec_date = date("Y-m-d",strtotime("+ $repeat_frequency years",strtotime($rec_date_start)));
			if ($end_type == 1) {
				if ($dates_count > $diff) break ;
			} else {
				if (strtotime($rec_date) > strtotime($last_occurence_date)) break;
			}
			$recurrent_dates[] = $rec_date;
			$rec_date_start = $rec_date;
			$dates_count++;
		}
		return $recurrent_dates ;
	}
    
	/**
	* function to check if an event is having recurrent information\
	* @param integer $idevents
	* @return recurrent_pattern if found else false
	*/
	public function has_recurrent_events($idevents) {
		$this->query("select * from ".$this->getTable()." where idevents = ?",array($idevents));
		if ($this->getNumRows() > 0) {
			$this->next();
			return $this->recurrent_pattern ;
		} else { return false ; }
	}
    
	/**
	* function to delete recurrent event
	* @param integer $idevents
	*/
	public function delete_recurrent_pattern($idevents) {
		$this->query("delete from ".$this->getTable()." where idevents = ?",array($idevents));
	}
}