<?php

class kevinhourscountModel extends model {
	
	public $ProjectItem = null; //product Item
	public $TodoList = array(); //product Item
	public $begin = 0; //product Item
	public $end = 0; //product Item
	
	/**
	 * Construct function, load model of todo.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}
	
	public function CountProjectByAccout($begin,$end) {
		if(!$this->ProjectItem) return false; //set outside
		$ProjectItem = &$this->ProjectItem;//ref
		$projectID = $ProjectItem->id;

		$this->begin = $begin;
		$this->end = $end; 

		$ProjectItem->totalHours = 0.0;
		$ProjectItem->HoursArray = $this->dao->select('sum( a.minutes)/60 as SumOfHours, a.account, b.code, b.realname, b.dept, c.name as deptName ')
			->from(TABLE_TODO)->alias('a')
			->leftJoin(TABLE_USER)->alias('b')->on('b.account = a.account')
			->leftJoin(TABLE_DEPT)->alias('c')->on('c.id = b.dept')
			->where('a.project')->eq($projectID)
			->andWhere('a.status')->eq('done')
			->andWhere("a.date >= '$begin'")
			->andWhere("a.date <= '$end'")
			->andWhere("(a.hourstype = 'ove' or a.hourstype = 'nor')")
			->groupBy('a.account')
			->orderBy('SumOfHours desc')->fetchAll();
		$HoursArray = &$ProjectItem->HoursArray;
		if(!$HoursArray ) return false;

		foreach ($HoursArray as $user):
			$ProjectItem->totalHours += $user->SumOfHours;
		endforeach;
	}

	
	/**
	 * Get over list of a user.
	 * 
	 * @param  date   $date 
	 * @param  string $account 
	 * @param  string $status   all|today|thisweek|lastweek|before, or a date.
	 * @param  int    $limit    
	 * @access public
	 * @return void
	 */
	public function CountProjectByMonth($begin,$end, $account) {
		if(!$this->ProjectItem) return false; //set outside

		$ProjectItem = &$this->ProjectItem;//ref
		$projectID = $ProjectItem->id;
		$ProjectItem->HoursArrayByMonth = array();
		$HoursArrayByMonth = & $ProjectItem->HoursArrayByMonth;
		$monthList = array();
		kevin::getMonthList($begin,$end,$monthList);
		if(count($monthList) == 0) return false;
		//初始化
		foreach ($monthList as $month) {
			$HoursArrayByMonth[$month] = new stdclass();
			$item = & $todos[$month] ;
			$item->month = $month;
			$item->SumOfHours = 0;
		}

		$todosQry = $this->dao->select("DATE_FORMAT(`date`,'%Y-%m') as month, account, sum(minutes)/60 as SumOfHours")->from(TABLE_TODO)
			->where('account')->eq($account)
			->andWhere("date >= '$begin'")
			->andWhere("date <= '$end'")
			->andWhere('status')->eq('done')
			->andWhere("(hourstype = 'ove' or hourstype = 'nor')")
			->groupBy("month")
			->orderBy("month")
			->fetchAll();
		foreach ($todosQry as $item) {
			if(!array_key_exists($item->month, $monthList)) continue;
			$todos[$item->month]->SumOfHours = $item->minutesSum;
		}
		return true;
	}
	
	public function todoGetListByProject($projectID, $date, $pager) {
		extract(kevin::getBeginEndTime($date));
		$this->TodoList	 =  $this->dao->select('*')
			->from(TABLE_TODO)->alias('a')
			->leftJoin(TABLE_USER)->alias('b')->on('b.account = a.account')
			->where('a.project')->eq($projectID)
			->andWhere('a.status')->eq('done')
			->andWhere("a.date >= '$begin'")
			->andWhere("a.date <= '$end'")
			->andWhere("(a.hourstype = 'ove' or a.hourstype = 'nor')")
			->orderBy('a.date desc')
			->page($pager)
			->fetchAll();
	}
}
