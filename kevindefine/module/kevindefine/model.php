<?php
class kevindefineModel extends model
{
	
	/**
	 * account.
	 * 
	 * @var string
	 * @access public
	 */
	public $account = "";
	
	/**
	* Construct function, load model of kevincalendar.
	* 
	* @access public
	* @return void
	*/
	public function __construct()
	{
		parent::__construct();
	}
	public function getById($kevincalendarID)
	{
		$kevincalendar = $this->dao->findById((int)$kevincalendarID)->from(TABLE_KEVINCALENDAR)->fetch();
		if(!$kevincalendar) return false;
		return $kevincalendar;
	}
	public function getInofoByMonth($type = 'thisMonth')
	{
		$allDateArray = $this->getKevinCalendar();
		
		$currentMonthDays = $this->getMonthArray($type);
		$firstDay = current($currentMonthDays);//本月第一天
		$lastDay = end($currentMonthDays);//本月最后一天
		$firstDayTimes = strtotime($firstDay);
		$endDayTimes = strtotime($lastDay);
		$week = (int)date("w",  strtotime($firstDay));//获得是周几,周末为0,周一为1
		$lastMonthDays = array();
		$tempDay = '';
		$lastMonthIndex = (0 == $week) ? 6 : ($week - 1);//获得上月几天
		for($i=1; $i<=$lastMonthIndex; $i++)
		{
			$tempDay = date('Y-m-d', strtotime("$firstDay -$i day"));
			$lastMonthDays[$tempDay] = $tempDay;
		}
		$nextMonthDays = array();
		$nextDays = 42 - count($currentMonthDays) - count($lastMonthDays);
		$endDay = end($currentMonthDays);
		for($i=1; $i<=$nextDays; $i++)
		{
			$tempDay = date('Y-m-d', strtotime("$endDay +$i day"));
			$nextMonthDays[$tempDay] = $tempDay;
		}
		$totalMonthDays = array_merge($lastMonthDays, $currentMonthDays, $nextMonthDays);
		ksort($totalMonthDays);
		$globalFirstDay = current($totalMonthDays);
		$globalEndDay = end($totalMonthDays);
		$todos = $this->getTodosByBeginAndEnd($globalFirstDay, $globalEndDay);//获得todo
		$totalMonthObjArray = array();
		foreach($totalMonthDays as $tempDay)
		{
			$tempObj				= new stdclass();
			$tempStatus = '';
			$tempDesc = '';
			$tempID = '';
			//是否存在于记录的日历中
			if(array_key_exists($tempDay, $allDateArray))
			{
				$tempStatus = $allDateArray[$tempDay]->status;
				$tempDesc = $allDateArray[$tempDay]->desc;
				$tempID = $allDateArray[$tempDay]->id;
			}
			else
			{
				$unix=strtotime($tempDay);//获得日期的 Unix 时间戳
				$week = date("w",$unix);//获得是周几,周末为0,周一为1
				if($week > 5 || $week < 1) $tempStatus = 'hol';
				else $tempStatus = 'nor';
			}
			$tempObj->status = $tempStatus;
			$tempObj->desc = $tempDesc;
			$tempObj->date = $tempDay;
			$tempObj->id = $tempID;
			$tempType = '';
			if(strtotime($tempDay) < $firstDayTimes)
			{
				$tempType = 'F';
			}
			else if(strtotime($tempDay) > $endDayTimes)
			{
				$tempType = 'N';
			}
			else
			{
				$tempType = 'C';
			}
			$tempObj->type = $tempType;
			$tempTodoArray = array();
			if(array_key_exists($tempDay, $todos))
			{
				$tempTodoArray = $todos[$tempDay];
			}
			$tempObj->todos = $tempTodoArray;
			$totalMonthObjArray[$tempDay] = $tempObj;
		}
		return $totalMonthObjArray;
	}

	
	public function getMonthArray($date = '')
	{
		$this->app->loadClass('date');
		$date = strtolower($date);

		if($date == 'thismonth')
		{
			extract(date::getThisMonth());
		}
		elseif($date == 'lastmonth')
		{
			extract(date::getLastMonth());
		}
		elseif($date == 'nextmonth')
		{
			$begin = date('Y-m', strtotime('+1 month')) . '-01 00:00:00';
			$end   = date('Y-m', strtotime('+2 month')) . '-00 23:59:59';
		}
		else
		{
			extract(date::getThisMonth());
		}
		$dateArray = array();
		//获得起始相差天数
		$DValue = floor((strtotime($end)-strtotime($begin))/(24*60*60));
		for($i=0; $i<=$DValue; $i++)
		{
			$currentDate = date('Y-m-d', strtotime("$begin +$i day"));
			$dateArray[$currentDate] = $currentDate;
		}
		return $dateArray;
	}
	public function getProductTask($productID, $pager = null, $orderBy = 'id_desc', $type = 'unclosed')
	{
		$projectArray = array();
		$projectArray = $this->getProjectsByProductID($productID);
		$orderBy = 't1.' . $orderBy;
		$tasks = $this->dao->select('t1.*, t2.name AS projectName, t3.realname')
			->from(TABLE_TASK)->alias('t1')
			->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
			->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
			->where('t1.project')->in($projectArray)
			->andWhere('t1.deleted')->eq(0)
			->beginIF($type == 'unclosed')->andWhere("t1.status != 'closed'")->fi()
			->beginIF($type == 'undone')->andWhere("(t1.status = 'wait' or t1.status ='doing')")->fi()
			->beginIF($type == 'needconfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
			->beginIF($type == 'assignedtome')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
			->beginIF($type == 'finishedbyme')->andWhere('t1.finishedby')->eq($this->app->user->account)->fi()
			->beginIF($type == 'delayed')->andWhere('deadline')->between('1970-1-1', helper::now())->andWhere('t1.status')->in('wait,doing')->fi()
			->orderBy($orderBy)
			->page($pager)
			->fetchAll();
		if($tasks) return $this->processTasks($tasks);
		return array();
	}
	
	
    /**
     * Create the link from module,method,extra
     * 
     * @param  string  $module 
     * @param  string  $method 
     * @param  mix     $extra 
     * @access public
     * @return void
     */
    public function getProductLink($module, $method, $extra, $branch = false)
    {
        $link = '';
        if($module != 'kevindefine')return $link;
        $link = helper::createLink($module, $method, "productID=%s&type=$extra");
        return $link;
    }
	
	public function getProducts()
	{
		$account = ',' . $this->app->user->account . ',';
		static $products;
		if($products === null)
		{
			$currentAccount = $this->app->user->account;
			$products = $this->dao->select("distinct(t1.id),t1.name")->from(TABLE_PRODUCT)->alias('t1')
				->beginIF(strpos($this->app->company->admins, $account) !== false)->where('t1.deleted')->eq(0)->andWhere('t1.status')->ne('closed')->fi()
				->beginIF(strpos($this->app->company->admins, $account) === false)
				->where("t1.status != 'closed' AND (
							t1.PO = '$currentAccount'
							or t1.QD = '$currentAccount'
							or t1.RD = '$currentAccount'
							or t1.createdBy = '$currentAccount')")
				->andWhere('t1.deleted')->eq(0)
				->fi()
				->fetchAll('id');
		}
		return $products;
	}
	public function getProjectNameByProject($project)
	{	
		if($project == '') return '';
		$todo = $this->dao->select('name')
			->from(TABLE_PROJECT)
			->where('id')->eq($project)
			->fetch();
		if(!$todo) return '';
		return $todo->name;
	}
	public function getProjectsByProductID($productID)
	{
		$projects = array();//产品下的所有项目组成的数组
		$projectArray = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)
						->where('product')->eq($productID)
						->fetchAll();
		foreach($projectArray as $project)
		{
			$projects[] = $project->project;	
		}
		return $projects;
	}

	public function getProjectBugsALL($projectID, $orderBy = 'id_desc', $pager = null, $build = 0)
	{
		$bugs = $this->dao->select('*')->from(TABLE_BUG)
			->where('project')->eq((int)$projectID)
			->beginIF($build != 0)->andWhere('openedBuild')->eq($build)->fi()
			->andWhere('deleted')->eq(0)
			->orderBy($orderBy)->page($pager)->fetchAll();

		$this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

		return $bugs;
	}
	public function getProjectBugsAssignToNull($projectID, $orderBy = 'id_desc', $pager = null, $build = 0)
	{
		$bugs = $this->dao->select('*')->from(TABLE_BUG)
			->where('project')->eq((int)$projectID)
			->andWhere('assignedTo')->eq('')
			->beginIF($build != 0)->andWhere('openedBuild')->eq($build)->fi()
			->andWhere('deleted')->eq(0)
			->orderBy($orderBy)->page($pager)->fetchAll();

		$this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

		return $bugs;
	}
	public function getProjectBugsByLonglifebugs($projectID, $orderBy = 'id_desc', $pager = null, $build = 0)
	{
		$bugs = $this->dao->findByLastEditedDate("<", date(DT_DATE1, strtotime('-7 days')))->from(TABLE_BUG)
			->andWhere('project')->eq((int)$projectID)
			->andWhere('openedDate')->lt(date(DT_DATE1,strtotime('-7 days')))
			->beginIF($build != 0)->andWhere('openedBuild')->eq($build)->fi()
			->andWhere('status')->ne('closed')
			->andWhere('deleted')->eq(0)
			->orderBy($orderBy)->page($pager)->fetchAll();

		$this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

		return $bugs;
	}
	public function getProjectBugsByMe($projectID, $orderBy = 'id_desc', $pager = null, $build = 0, $type)
	{
		$bugs = $this->dao->select('*')->from(TABLE_BUG)
			->where('project')->eq((int)$projectID)
			->andWhere($type)->eq($this->app->user->account)
			->beginIF($build != 0)->andWhere('openedBuild')->eq($build)->fi()
			->andWhere('deleted')->eq(0)
			->orderBy($orderBy)->page($pager)->fetchAll();

		$this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

		return $bugs;
	}
	public function getProjectBugsByPostponedbugs($projectID, $orderBy = 'id_desc', $pager = null, $build = 0)
	{
		$bugs = $this->dao->findByResolution('postponed')->from(TABLE_BUG)
			->andWhere('project')->eq((int)$projectID)
			->beginIF($build != 0)->andWhere('openedBuild')->eq($build)->fi()
			->andWhere('deleted')->eq(0)
			->orderBy($orderBy)->page($pager)->fetchAll();

		$this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

		return $bugs;
	}
	public function getProjectBugsUnclosed($projectID, $orderBy = 'id_desc', $pager = null, $build = 0)
	{
		$bugs = $this->dao->select('*')->from(TABLE_BUG)
			->where('project')->eq((int)$projectID)
			->andWhere('closedBy')->eq('')
			->beginIF($build != 0)->andWhere('openedBuild')->eq($build)->fi()
			->andWhere('deleted')->eq(0)
			->orderBy($orderBy)->page($pager)->fetchAll();

		$this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

		return $bugs;
	}
	public function getProjectBugsUnresolved($projectID, $orderBy = 'id_desc', $pager = null, $build = 0)
	{
		$bugs = $this->dao->select('*')->from(TABLE_BUG)
			->where('project')->eq((int)$projectID)
			->andWhere('resolvedBy')->eq('')
			->beginIF($build != 0)->andWhere('openedBuild')->eq($build)->fi()
			->andWhere('deleted')->eq(0)
			->orderBy($orderBy)->page($pager)->fetchAll();

		$this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

		return $bugs;
	}


	public function getProjectProductIdbyStory($storyID)
	{
		$projectArray = array();
		$projects = $this->dao->select('b.*, c.name AS projectName')->from(TABLE_STORY)->alias('a')
					->leftJoin(TABLE_PROJECTPRODUCT)->alias('b')->on('a.product = b.product')
					->leftJoin(TABLE_PROJECT)->alias('c')->on('c.id = b.project')
					->leftJoin(TABLE_TEAM)->alias('d')->on('d.project = b.project')
					->where('a.id')->eq($storyID)
					->andWhere('d.account')->eq($this->app->user->account)
					->fetchAll();
		if(!$projects) return $projectArray;
		foreach($projects as $project)
		{
			$projectArray[$project->project] = $project->projectName;	
		}
		return $projectArray;
	}
	public function getProjectStoriesAssignedToMe($projectID, $orderBy = 'pri_asc,id_desc')
	{
		$stories = $this->dao->select('t1.*, t2.*')->from(TABLE_PROJECTSTORY)->alias('t1')
			->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
			->where('t1.project')->eq((int)$projectID)
			->andWhere('t2.assignedTo')->eq($this->app->user->account)
			->andWhere('t2.deleted')->eq(0)
			->orderBy($orderBy)
			->fetchAll('id');
		return $stories;
	}
	public function getProjectStoriesByMe($projectID, $orderBy = 'pri_asc,id_desc', $type = 'openedBy')
	{
		$stories = $this->dao->select('t1.*, t2.*')->from(TABLE_PROJECTSTORY)->alias('t1')
			->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
			->where('t1.project')->eq((int)$projectID)
			->andWhere('t2.' . $type)->eq($this->app->user->account)
			->andWhere('t2.deleted')->eq(0)
			->orderBy($orderBy)
			->fetchAll('id');
		return $stories;
	}
	public function getProjectStoriesByStatus($projectID, $orderBy = 'pri_asc,id_desc', $status = '')
	{
		$stories = $this->dao->select('t1.*, t2.*')->from(TABLE_PROJECTSTORY)->alias('t1')
			->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
			->where('t1.project')->eq((int)$projectID)
			->andWhere('t2.status')->eq($status)
			->andWhere('t2.deleted')->eq(0)
			->orderBy($orderBy)
			->fetchAll('id');
		return $stories;
	}
	public function getProjectStoriesUnclosed($projectID, $orderBy = 'pri_asc,id_desc')
	{
		$stories = $this->dao->select('t1.*, t2.*')->from(TABLE_PROJECTSTORY)->alias('t1')
			->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
			->where('t1.project')->eq((int)$projectID)
			->andWhere('t2.status')->ne('closed')
			->andWhere('t2.deleted')->eq(0)
			->orderBy($orderBy)
			->fetchAll('id');
		return $stories;
	}
	public function getProjectStoriesUncreateTask($projectID, $orderBy = 'pri_asc,id_desc')
	{
		$storyArray = $this->getStoryArrayInTasks();
		$stories = $this->dao->select('t1.*, t2.*')->from(TABLE_PROJECTSTORY)->alias('t1')
			->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
			->where('t1.project')->eq((int)$projectID)
			->andWhere('t1.story')->notin($storyArray)
			->andWhere('t2.status')->ne('closed')
			->andWhere('t2.deleted')->eq(0)
			->orderBy($orderBy)
			->fetchAll('id');
		return $stories;
	}
	public function getProjectStoriesWillClose($projectID, $orderBy = 'pri_asc,id_desc')
	{
		$stories = $this->dao->select('t1.*, t2.*')->from(TABLE_PROJECTSTORY)->alias('t1')
			->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
			->where('t1.project')->eq((int)$projectID)
			->andWhere('t2.stage')->in('developed,released')
			->andWhere('t2.status')->ne('closed')
			->andWhere('t2.deleted')->eq(0)
			->orderBy($orderBy)
			->fetchAll('id');
		return $stories;
	}
	public function getProjectsByStory($storyID)
	{
		$projectArray = array();
		$projects = $this->dao->select('a.*, b.name AS projectName')->from(TABLE_PROJECTSTORY)->alias('a')
						->leftJoin(TABLE_PROJECT)->alias('b')->on('b.id = a.project')
						->where('story')->eq($storyID)
						->fetchAll('project');
		if(!$projects) return $projectArray;
		foreach($projects as $project)
		{
			$projectArray[$project->project] = $project->projectName;	
		}
		return $projectArray;
	}
	public function getStoryArrayInTasks()
	{
		$stories = $this->dao->select('*')->from(TABLE_TASK)
			->where('story')->ne(0)
			->fetchAll();
		$storyArray = array();
		foreach($stories as $story)
		{
			if(in_array($story->project, $storyArray)) continue;
			$storyArray[] = $story->story;
		}
		return $storyArray;
	}
	public function getStoryByID($storyID)
	{
		return $this->dao->findById((int)$storyID)->from(TABLE_STORY)->fetch();
	}

	/**
	* 获得某个周期内所有日期工作类型数组
	* 如果周期为全部设成thisyear
	* return array  
	* e.g array['2015-01-01'] = 'law';
	*/
	public function getStatusArray($date = 'thisMonth')
	{
		$this->app->loadClass('date');
		$date = strtolower($date);

		if($date == 'today') 
		{
			$begin = date::today();
			$end   = $begin;
		}
		elseif($date == 'yesterday') 
		{
			$begin = date::yesterday();
			$end   = $begin;
		}
		elseif($date == 'thisweek')
		{
			extract(date::getThisWeek());
		}
		elseif($date == 'lastweek')
		{
			extract(date::getLastWeek());
		}
		elseif($date == 'thismonth')
		{
			extract(date::getThisMonth());
		}
		elseif($date == 'lastmonth')
		{
			extract(date::getLastMonth());
		}
		elseif($date == 'thisseason')
		{
			extract(date::getThisSeason());
		}
		elseif($date == 'thisyear')
		{
			extract(date::getThisYear());
		}
		elseif($date == 'lastyear')
		{
			extract(date::getLastYear());
		}
		elseif($date == 'all')
		{
			extract(date::getThisYear());
			$date == 'thisyear';
		}
		else
		{
			$begin = $end = $date;
		}
		$kevincalendars = $this->getList($date);
		$dateArray = array();
		//获得起始相差天数
		$DValue = floor((strtotime($end)-strtotime($begin))/(24*60*60));
		for($i=0; $i<=$DValue; $i++)
		{
			$currentDate = date('Y-m-d', strtotime("$begin +$i day"));
			if(array_key_exists($currentDate, $kevincalendars))
			{
				$dateArray[$currentDate] = $kevincalendars[$currentDate]->status;
			}
			else
			{
				$unix=strtotime($currentDate);//获得日期的 Unix 时间戳
				$week = date("w",$unix);//获得是周几,周末为0,周一为1
				if($week > 5 || $week < 1) $dateArray[$currentDate] = 'hol';
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
	public function getStatusArrayOfMonth($year = '', $month = '')
	{
		if('' == $month) $month = date('m');
		if('' == $year) $year = date('Y');

		$begin = $year . '-' . $month . '-01 00:00:00';
		$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$end   = $year . '-' . $month . '-' . $days . ' 23:59:59';
		$kevincalendars = array();
		$stmt = $this->dao->select('*')->from(TABLE_KEVINCALENDAR)
		->where('deleted')->eq(0)
		->andWhere("date >= '$begin'")
		->andWhere("date <= '$end'")
		->orderBy('date')
		->query();

		while($kevincalendar = $stmt->fetch())
		{
			$tempDay = date('j', strtotime($kevincalendar->date));
			$kevincalendars[$tempDay] = $kevincalendar->status;
		}
		for($i=1;$i<=$days;$i++)
		{
			$date = $year. '-' . $month . '-' . $i;//日期
			$date = date('Y-m-d',strtotime($date));
			//是否存在于记录的日历中
			if(!array_key_exists($i, $kevincalendars))
			{
				$unix=strtotime($date);//获得日期的 Unix 时间戳
				$week = date("w",$unix);//获得是周几,周末为0,周一为1
				if($week > 5 || $week < 1) $kevincalendars[$i] = 'hol';
				else $kevincalendars[$i] = 'nor';
				
			}
		}
		ksort($kevincalendars);
		return $kevincalendars;
	}
	public function getTodosByBeginAndEnd($begin, $end)
	{
		if($this->account == "") $this->account = $this->app->user->account;
		$stmt = $this->dao->select('a.*,b.realname')->from(TABLE_TODO)->alias('a')
				->leftJoin(TABLE_USER)->alias('b')->on('a.account = b.account')
				->where('a.account')->eq($this->account)
				->andWhere("a.date >= '$begin'")
				->andWhere("a.date <= '$end'")
				->orderBy('date, begin')
				->fetchGroup('date');
		return $stmt;
	}
	public function getStatusByDate($date = '')
	{
		if('' == $date) return '';
		$kevincalendar = $this->dao->select('*')->from(TABLE_KEVINCALENDAR)
		->where('deleted')->eq(0)
		->andWhere('date')->eq($date)
		->fetch();
		if(!$kevincalendar) return '';
		return $kevincalendar->status;
	}
	/**
	* 获得某月的第几个工作日的日期
	* return int 
	*/
	public function getWorkingDay($year = '', $month = '', $workingdays = 1)
	{
		$dayArray = $this->getStatusArrayOfMonth($year, $month);
		$totalDays = count($dayArray);
		$tempDay = 0;
		for($i = 1; $i <= $totalDays; $i++)
		{
			if('nor' == $dayArray[$i])
			{
				$tempDay += 1;
				if($workingdays == $tempDay) return $i;
			}
		}
		return $totalDays;//没有则返回月底
	}
	
	/**
	 * Process a task, judge it's status.
	 * 
	 * @param  object    $task 
	 * @access private
	 * @return object
	 */
	public function processTask($task)
	{
		$today = helper::today();
	   
		/* Delayed or not?. */
		if($task->status !== 'done' and $task->status !== 'cancel' and $task->status != 'closed')
		{
			if($task->deadline != '0000-00-00')
			{
				$delay = helper::diffDate($today, $task->deadline);
				if($delay > 0) $task->delay = $delay;            
			} 
		}
		return $task;
	}
		
	public function linkProject($storyID, $projectID)
	{
		$this->loadModel('action');	
		$this->loadModel('story');
		$story = $this->getStoryByID($storyID);
		$data = new stdclass();
		$data->project = $projectID;
		$data->product = $story->product;
		$data->story   = $storyID;
		$data->version = $story->version;
		$this->dao->insert(TABLE_PROJECTSTORY)->data($data)->exec();
		$this->story->setStage($storyID);
		$this->action->create('story', $storyID, 'linked2project', '', $projectID); 
	}
	
	/**
	 * Batch process tasks.
	 * 
	 * @param  int    $tasks 
	 * @access private
	 * @return void
	 */
	public function processTasks($tasks)
	{
		foreach($tasks as $task)
		{
			$task = $this->processTask($task);
		}
		return $tasks;
	}
	
}