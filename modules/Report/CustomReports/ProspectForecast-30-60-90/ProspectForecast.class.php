<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* class ProspectForecast
* @author Abhik Chakraborty
*/
class ProspectForecast Extends CustomReport {

	/**
	* function to get the prospect forecasting
	* @param string $where
	* @param integer $iduser
	* @return array
	*/
	public function get_prospect_forecast($where = '',$iduser=0) {
		if ($where == '') {
			if ((int)$iduser == 0) {
				$iduser = $_SESSION["do_user"]->iduser ;
			}
			$where = '';
			$where = $this->get_report_where($iduser,'p','ptg') ;
		}
		
		$start_date_30 = TimeZoneUtil::get_user_timezone_date() ;
		$date_obj = new DateTime($start_date_30) ;
		$add_30 = $date_obj->modify('+29 day') ; // since 0-30 i.e 30 days including current day
		$end_date_30 = $add_30->format('Y-m-d') ;
		
		$date_obj = new DateTime($end_date_30) ;
		$add_1 = $date_obj->modify('+1 day') ;
		$start_date_60 = $add_1->format('Y-m-d') ;
		$date_obj = new DateTime($start_date_60) ;
		$add_30 = $date_obj->modify('+29 day') ; // since 31-60 i.e 30 days including 31st
		$end_date_60 = $add_30->format('Y-m-d') ;
		
		$date_obj = new DateTime($end_date_60) ;
		$add_1 = $date_obj->modify('+1 day') ;
		$start_date_90 = $add_1->format('Y-m-d') ;
		$date_obj = new DateTime($start_date_90) ;
		$add_30 = $date_obj->modify('+29 day') ; // since 61-90 i.e 30 days including 61st
		$end_date_90 = $add_30->format('Y-m-d') ;
		
		$qry = "
		select '0-30 Days' as day_range,coalesce(sum(p.amount),0) as amount
		from potentials p
		left join pot_to_grp_rel ptg on ptg.idpotentials = p.idpotentials
		where 
		p.deleted = 0 
		$where
		and p.expected_closing_date >= '$start_date_30' and p.expected_closing_date <= '$end_date_30'
		union all 
		select '31-60 Days' as day_range,coalesce(sum(p.amount),0) as amount
		from potentials p
		left join pot_to_grp_rel ptg on ptg.idpotentials = p.idpotentials
		where 
		p.deleted = 0 
		$where
		and p.expected_closing_date >= '$start_date_60' and p.expected_closing_date <= '$end_date_60'
		union all 
		select '61-90 Days' as day_range,coalesce(sum(p.amount),0) as amount
		from potentials p
		left join pot_to_grp_rel ptg on ptg.idpotentials = p.idpotentials
		where 
		p.deleted = 0 
		$where
		and p.expected_closing_date >= '$start_date_90' and p.expected_closing_date <= '$end_date_90'
		"; 
		$this->query($qry) ;
		$return_array = array() ;
		
		while ($this->next()) {
			if ((int)$this->amount > 0) $return_array[$this->day_range] = $this->amount ;
		}
		return $return_array ;
	}
	
	/**
	* function to get the detailed forcasting data 
	* @param string $where
	* @param integer $iduser
	* @return void
	*/
	public function get_detailed_forecast_data($where='',$iduser=0) {
		$do_potentials = new Potentials() ;
		$do_potentials->get_list_query() ;
		if ($where == '') {
			$user_where = $this->get_report_where($iduser,'potentials','pot_to_grp_rel') ;
		}
		$date_where = '';
		$start_date = TimeZoneUtil::get_user_timezone_date() ;
		$date_obj = new DateTime($start_date) ;
		$add_89 = $date_obj->modify('+89 day') ; 
		$end_date = $add_89->format('Y-m-d') ;
		
		$date_where = " and `potentials`.`expected_closing_date` >= '$start_date' 
		and `potentials`.`expected_closing_date` <= '$end_date' 
		" ;
		
		$qry = $do_potentials->getSqlQuery() ;
		$qry .= $user_where.$date_where. " order by `potentials`.`expected_closing_date`" ;
		$this->query($qry) ;
	}
}