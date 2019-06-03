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
	* Construct function,
	* 
	* @access public
	* @return void
	*/
	public function __construct()
	{
		parent::__construct();
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