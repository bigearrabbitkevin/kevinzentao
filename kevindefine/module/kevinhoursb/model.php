<?php

class kevinhoursbModel extends model {

	/**
	 * $account.
	 * 
	 * @var account
	 * @access public
	 */
	public $account = "";

	/**
	 * Construct function, load model of kevinhoursb.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	public function batchcreate() {
		$kevinhoursbs = fixer::input('post')->get();
		for ($i = 0; $i < $this->config->kevinhoursb->batchcreate; $i++) {
			if ($kevinhoursbs->dates[$i] != '') {
				$kevinhoursb		 = new stdclass();
				$kevinhoursb->date	 = $kevinhoursbs->dates[$i];
				$kevinhoursb->status = $kevinhoursbs->statuses[$i];
				$kevinhoursb->desc	 = $kevinhoursbs->descs[$i];

				$this->dao->insert(TABLE_KEVINHOURSB_MONTHCOUNT)->data($kevinhoursb)->autoCheck()->exec();
				if (dao::isError()) {
					echo js::error(dao::getError());
					die(js::reload('parent'));
				}
			} else {
				unset($kevinhoursbs->dates[$i]);
				unset($kevinhoursbs->statuses[$i]);
				unset($kevinhoursbs->descs[$i]);
			}
		}
	}

	public function create() {
		$kevinhoursb = fixer::input('post')->get();
		$this->dao->insert(TABLE_KEVINHOURSB_MONTHCOUNT)->data($kevinhoursb)
			->autoCheck()
			->exec();
		return $this->dao->lastInsertID();
	}

	public function itemGet($account, $YearMonth) {
		$item = $this->dao->select('*')->from(TABLE_KEVINHOURSB_MONTHCOUNT)
			->where('deleted')->ne('1')
			->andWhere('account')->eq($account)
			->andWhere('YearMonth')->eq($YearMonth)
			->orderBy('code,account')
			->fetch();
		return $item;
	}

	public function getById($id) {
		$item = $this->dao->findById((int) $id)->from(TABLE_KEVINHOURSB_MONTHCOUNT)->fetch();

		if ($item) $this->logSplit($item);
		return $item;
	}

	public function getInofoByMonth($month = 'thisMonth', $methodName = '') {
		$currentMonthDays	 = $this->getMonthArray($month);
		$firstDay			 = current($currentMonthDays); //本月第一天
		$lastDay			 = end($currentMonthDays); //本月最后一天
		$firstDayTimes		 = strtotime($firstDay);
		$endDayTimes		 = strtotime($lastDay);
		$week				 = (int) date("w", strtotime($firstDay)); //获得是周几,周末为0,周一为1
		$lastMonthDays		 = array();
		$tempDay			 = '';
		$preDays			 = ( $week <= 2) ? ( $week + 6) : ($week - 1); //获得上月几天
		for ($i = $preDays; $i >= 1; $i--) {
			$tempDay				 = date('Y-m-d', strtotime("$firstDay -$i day"));
			$lastMonthDays[$tempDay] = $tempDay;
		}
		$nextMonthDays	 = array();
		$nextDays		 = 42 - count($currentMonthDays) - count($lastMonthDays);
		$endDay			 = end($currentMonthDays);
		for ($i = 1; $i <= $nextDays; $i++) {
			$tempDay				 = date('Y-m-d', strtotime("$endDay +$i day"));
			$nextMonthDays[$tempDay] = $tempDay;
		}
		$totalMonthDays	 = array_merge($lastMonthDays, $currentMonthDays, $nextMonthDays);
		$globalFirstDay	 = current($totalMonthDays);
		$globalEndDay	 = end($totalMonthDays);
	
		$totalMonthObjArray = array();
		foreach ($totalMonthDays as $tempDay) {
			$tempObj	 = new stdclass();
			$tempStatus	 = '';
			$tempDesc	 = '';
			$tempID		 = '';

			$unix	 = strtotime($tempDay); //获得日期的 Unix 时间戳
			$week	 = date("w", $unix); //获得是周几,周末为0,周一为1
			if ($week > 5 || $week < 1) {
				$tempStatus = 'hol';
			} else {
				$tempStatus = 'nor';
			}
			$tempObj->status = $tempStatus;
			$tempObj->desc	 = $tempDesc;
			$tempObj->date	 = $tempDay;
			$tempObj->id	 = $tempID;
			$tempType		 = '';
			if (strtotime($tempDay) < $firstDayTimes) {
				$tempType = 'F';
			} else if (strtotime($tempDay) > $endDayTimes) {
				$tempType = 'N';
			} else {
				$tempType = 'C';
			}
			$tempObj->type	 = $tempType;
			$totalMonthObjArray[$tempDay]	 = $tempObj;
		}
		return $totalMonthObjArray;
	}

	public function getList($YearMonth, $orderBy = "`YearMonth`,`code`") {

		$items = $this->dao->select('a.*,b.code,b.realname,c.name as DeptName')->from(TABLE_KEVINHOURSB_MONTHCOUNT)->alias('a')
			->leftjoin(TABLE_USER)->alias('b')->on('a.account = b.account')
			->leftjoin(TABLE_DEPT)->alias('c')->on('a.dept = c.id')
			->where('a.deleted')->eq(0)
			->andWhere("a.YearMonth = '$YearMonth'")
			->orderBy($orderBy)
			->fetchAll();

		foreach ($items as $item) {
			$this->logSplit($item);
		}
		return $items;
	}

	public function getMonthArray($date = '') {
		$this->app->loadClass('date');
		$date = strtolower($date);
		if (is_numeric($date) && 6 == strlen($date)) {
			$date	 = $date . '01';
			$begin	 = date('Y-m', strtotime("$date")) . '-01 00:00:00';
			$end	 = date('Y-m', strtotime("$date +1 month")) . '-00 23:59:59';
		} else {
			if ($date == 'thismonth') {
				extract(date::getThisMonth());
			} elseif ($date == 'lastmonth') {
				extract(date::getLastMonth());
			} elseif ($date == 'nextmonth') {
				$begin	 = date('Y-m', strtotime('+1 month')) . '-01 00:00:00';
				$end	 = date('Y-m', strtotime('+2 month')) . '-00 23:59:59';
			} else {
				extract(date::getThisMonth());
			}
		}

		$dateArray	 = array();
		//获得起始相差天数
		$DValue		 = floor((strtotime($end) - strtotime($begin)) / (24 * 60 * 60));
		for ($i = 0; $i <= $DValue; $i++) {
			$currentDate			 = date('Y-m-d', strtotime("$begin +$i day"));
			$dateArray[$currentDate] = $currentDate;
		}
		return $dateArray;
	}

	public function getMonthBySeason($month = '', $season = '') {
		$monthArray = array();
		if ('' == $month && $season == '') {
			return $monthArray;
		}
		if ('' == $season) {
			$monthArray[] = $month;
			return $monthArray;
		}
		for ($i = 1; $i < 4; $i++) {
			$currentMonth	 = sprintf("%02d", 3 * ($season - 1) + $i);
			$monthArray[]	 = $currentMonth;
		}
		return $monthArray;
	}

	/**
	 * 获得某个周期内所有日期工作类型数组
	 * 如果周期为全部设成thisyear
	 * return array  
	 * e.g array['2015-01-01'] = 'law';
	 */
	public function getStatusArray($date = 'thisMonth') {
		extract(kevin::getBeginEndTime($date));

		$kevinhoursbs	 = $this->getList($begin, $end);
		$dateArray		 = array();
		//获得起始相差天数
		$DValue			 = floor((strtotime($end) - strtotime($begin)) / (24 * 60 * 60));
		for ($i = 0; $i <= $DValue; $i++) {
			$currentDate = date('Y-m-d', strtotime("$begin +$i day"));
			if (array_key_exists($currentDate, $kevinhoursbs)) {
				$dateArray[$currentDate] = $kevinhoursbs[$currentDate]->status;
			} else {
				$unix					 = strtotime($currentDate); //获得日期的 Unix 时间戳
				$week					 = date("w", $unix); //获得是周几,周末为0,周一为1
				if ($week > 5 || $week < 1) $dateArray[$currentDate] = 'hol';
				else $dateArray[$currentDate] = 'nor';
			}
		}
		return $dateArray;
	}

	/**
	 * 获得特定月份的日期工作类型数组
	 * return: array 
	 * e.g:array[1] = 'nor';
	 */
	public function getStatusArrayOfMonth($year = '', $month = '') {
		if ('' == $month) $month	 = date('m');
		if ('' == $year) $year	 = date('Y');

		$begin			 = $year . '-' . $month . '-01 00:00:00';
		$days			 = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$end			 = $year . '-' . $month . '-' . $days . ' 23:59:59';
		$kevinhoursbs	 = array();
		$stmt			 = $this->dao->select('*')->from(TABLE_KEVINHOURSB_MONTHCOUNT)
			->where('deleted')->eq(0)
			->andWhere("date >= '$begin'")
			->andWhere("date <= '$end'")
			->orderBy('date')
			->query();

		while ($kevinhoursb = $stmt->fetch()) {
			$tempDay				 = date('j', strtotime($kevinhoursb->date));
			$kevinhoursbs[$tempDay]	 = $kevinhoursb->status;
		}
		for ($i = 1; $i <= $days; $i++) {
			$date	 = $year . '-' . $month . '-' . $i; //日期
			$date	 = date('Y-m-d', strtotime($date));
			//是否存在于记录的日历中
			if (!array_key_exists($i, $kevinhoursbs)) {
				$unix				 = strtotime($date); //获得日期的 Unix 时间戳
				$week				 = date("w", $unix); //获得是周几,周末为0,周一为1
				if ($week > 5 || $week < 1) $kevinhoursbs[$i]	 = 'hol';
				else $kevinhoursbs[$i]	 = 'nor';
			}
		}
		ksort($kevinhoursbs);
		return $kevinhoursbs;
	}

	public function getStatusByDate($date = '') {
		if ('' == $date) return '';
		$kevinhoursb = $this->dao->select('*')->from(TABLE_KEVINHOURSB_MONTHCOUNT)
			->where('deleted')->eq(0)
			->andWhere('date')->eq($date)
			->fetch();
		if (!$kevinhoursb) return '';
		return $kevinhoursb->status;
	}

	/**
	 * 获得某月的第几个工作日的日期
	 * 
	 * @param  string $year 
	 * @param  string $month 
	 * @param  int $workingdays 
	 * @access public
	 * @return int 
	 */
	public function getWorkingDay($year = '', $month = '', $workingdays = 1) {
		$dayArray	 = $this->getStatusArrayOfMonth($year, $month);
		$totalDays	 = count($dayArray);
		$tempDay	 = 0;
		for ($i = 1; $i <= $totalDays; $i++) {
			if ('nor' == $dayArray[$i]) {
				$tempDay += 1;
				if ($workingdays == $tempDay) return $i;
			}
		}
		return $totalDays; //没有则返回月底
	}

	public function getYearList($year = 10) {
		$yearList = array();
		for ($i = -1; $i < $year; $i++) {
			$yearList[date('Y') - $i] = (date('Y') - $i) . '年';
		}
		return $yearList;
	}

	public function logSplit(&$item) {
		$stringList	 = explode(',', $item->log);
		$count		 = count($stringList);
		$i			 = 0;
		$min		 = min($count, 31);
		for ($j = 1; $j <= $min; $j++) {
			$col		 = 'D' . $j;
			$item->$col	 = $stringList[$j - 1];
		}
	}

	public function logUnion(&$item) {
		//if (!isset($item->D1)) return "";

		$log = $item->D1;
		for ($i = 2; $i <= 31; $i++) {
			$col = 'D' . $i;
			//if (!isset($item->$col)) break;
			$log.= ',' . $item->$col;
		}
		return $log;
	}

	public function update($id) {
		$item	 = fixer::input('post')->get();
		$log	 = $this->logUnion($item);
		$this->dao->update(TABLE_KEVINHOURSB_MONTHCOUNT)
			->set('log')->eq($log)
			->where('id')->eq($id)
			->autoCheck()
			->exec();
	}

}
