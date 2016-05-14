<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* class ProspectWinByUsers
* @author Abhik Chakraborty
*/
class ProspectWinByUsers Extends CustomReport {

	/**
	* function to get the prospect wins for graph
	* @param string $where
	* @param integer $iduser
	* @param integer $date_filter_type
	* @param string $start_date
	* @param string $date_end
	* @return array
	*/
	public function get_propect_win($where = '',$iduser=0,$date_filter_type=15,$start_date='',$end_date='') {
		if ($where == '') {
			if ((int)$iduser == 0) {
				$iduser = $_SESSION["do_user"]->iduser ;
			}
			$where = '';
			$user_where = $this->get_report_where($iduser,'p','ptg') ;
			$date_where = $this->get_date_filter_where('p','expected_closing_date',$date_filter_type,$start_date='',$end_date='') ;
			$where = $user_where.$date_where ;
		}
		
		$qry = "
		select sum(amount) as amount,
		case when (u.user_name not like '')
		then
		u.user_name
		else
		g.group_name
		end
		as `assigned_to`
		from potentials p
		left join `user` u on `u`.`iduser` = `p`.`iduser` 
		left join `pot_to_grp_rel` ptg on `ptg`.`idpotentials` = `p`.`idpotentials`
		left join `group` g on `g`.`idgroup` = `ptg`.`idgroup`
		where p.deleted = 0
		and p.sales_stage = 'Close Win'
		$where
		group by assigned_to
		"; 
		//echo $qry;
		$this->query($qry) ;
		$return_array = array() ;
		$grand_total = 0.0 ;
		if ($this->getNumRows() > 0) {
			while($this->next()) {
				$return_array[$this->assigned_to] = $this->amount ;
				$grand_total += $this->amount ;
			}
		}
		return array("data"=>$return_array,"grand_total"=>$grand_total);
	}
	
	/**
	* function to get the prospect wins
	* @param string $where
	* @param integer $iduser
	* @param integer $date_filter_type
	* @param string $start_date
	* @param string $date_end
	* @return array
	*/
	public function get_detailed_win_data($where='',$iduser=0,$date_filter_type,$start_date='',$end_date='') {
		$do_potentials = new Potentials() ;
		$do_potentials->get_list_query() ;
		if ($where == '') {
			$user_where = $this->get_report_where($iduser,'potentials','pot_to_grp_rel') ;
			$date_where = $this->get_date_filter_where('potentials','expected_closing_date',$date_filter_type,$start_date='',$end_date='') ;
			$additonal_where = " AND
			`potentials`.`sales_stage` = 'Close Win'
			";
			$where = $user_where.$date_where.$additonal_where ;
		}
		$qry = $do_potentials->getSqlQuery() ;
		$qry .= $where ;
		$this->query($qry) ;
	}
}