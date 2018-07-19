<?php

/**
 * The model file
 *
 * @copyright   Kevin
 * @charge: free
 * @license: ZPL (http://zpl.pub/v1)
 * @author      Kevin <3301647@qq.com>
 * @package     kevinplan
 * @link        http://www.zentao.net
 */
?>
<?php

class kevinplanModel extends model {

	/**
	 * $isCharger 是否为项目计划负责人.
	 *
	 * @access public
	 */
	public $isCharger = array();

	/**
	 * $isMember 是否为项目计划成员.
	 *
	 * @access public
	 */
	public $isMember = array();

	/**
	 * $deptchild 子部门.
	 *
	 * @access public
	 */
	public $deptchild = array();

	/**
	 * $privaccount 用户权限.
	 *
	 * @access public
	 */
	public $privplan = array();

	/**
	 * $Filter 筛选类.
	 *
	 * @access public
	 */
	public $Filter	 = null;
	/*
	 * 	$Message
	 */
	public $Message	 = array();

	/**
	 * Construct function, load model of kevinplan.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->app->loadClass('kevin'); //加载kevin类
	}

	/**
	 *  Check $project if No private Die
	 */
	public function hasPrivProjectOrDie($project) {
		if (array_key_exists($project, $this->isCharger) && $this->isCharger[$project]) {
			return true;
		}

		if (!is_numeric($project) || $project <= 0) {
			dao::$errors[''][] = "Please input numeric and value >0";
		} else {
			dao::$errors[''][]	 = "You do not have the private";
			dao::$errors[''][]	 = "Project plan id =$project!";
		}
		kevin::dieIfError(dao::$errors);
	}

	/**
	 *  Check $project if No private Die
	 */
	public function hasPrivProject($project) {
		if (!array_key_exists($project, $this->isCharger)) return false;
		return $this->isCharger[$project];
	}

	public function checkdeptpriv() {
		if (common::hasPriv('kevinplan', 'browseAllPlan')) return 1;
		elseif (common::hasPriv('kevinplan', 'browseDeptPlan')) {
			$deptidarr							 = $this->dao->select('id,id as code')->from(TABLE_DEPT)->where('parent')->eq($this->app->user->dept)
					->andwhere('deleted')->eq(0)->fetchPairs('id', 'code');
			$deptidarr[$this->app->user->dept]	 = $this->app->user->dept;
			$this->deptchild					 = $deptidarr;
			return 1;
		} else return 1;
	}

	public function checkplanpriv(&$objs) {
		if (isset($objs->id)) {
			if (substr_count($objs->members, ',')) $memberarr					 = explode(',', $objs->members);
			else $memberarr					 = array($objs->members);
			$this->privplan[$objs->id]	 = ($objs->charger == $this->app->user->account || in_array($this->app->user->account, $memberarr)) ? true : false;
			if (!$this->privplan[$objs->id]) die(js::alert('Sorry,you have no priviliage!') . js::locate('back'));
		}else {
			foreach ($objs as $obj) {
				$memberarr					 = array();
				if (substr_count($obj->members, ',')) $memberarr					 = explode(',', $obj->members);
				else $memberarr					 = array($obj->members);
				$this->privplan[$obj->id]	 = ($obj->charger == $this->app->user->account || in_array($this->app->user->account, $memberarr)) ? true : false;
			}
		}
	}

	public function defaultShowOfPlan($obj, $method = '') {
		if ($method == '' || !isset($this->config->sklaw->default->$method)) return false;

		if (empty($obj)) {
			foreach ($this->config->sklaw->default->$method as $sqlname => $defaultval)
				$obj->$sqlname = $defaultval;
		} else {
			foreach ($this->config->sklaw->default->$method as $sqlname => $defaultval)
				$obj->$sqlname = isset($obj->$sqlname) ? ($obj->$sqlname) : $defaultval;
		}
		return $obj;
	}

	public function filterCheckin() {
		$keywordsArray = $this->session->kevinplan_filterKeywords;
		if (!empty($_POST)) {
			$Filter = fixer::input('post')->get();

			if (!isset($Filter->methodName) || $Filter->methodName != "groupcreate") {
				$Filter->planFilter = 0;
			}

			if (isset($Filter->charger)) $keywordsArray['charger']		 = $Filter->charger;
			if (isset($Filter->member)) $keywordsArray['member']		 = $Filter->member;
			if (isset($Filter->projectName)) $keywordsArray['projectName']	 = $Filter->projectName;
			if (isset($Filter->methodName)) $keywordsArray['methodName']	 = $Filter->methodName;
			if (isset($Filter->projectPri)) $keywordsArray['projectPri']	 = $Filter->projectPri;

			if (isset($Filter->dept)) $keywordsArray['dept']		 = (int) $Filter->dept;
			if (isset($Filter->project)) $keywordsArray['project']	 = (int) $Filter->project;
			if (isset($Filter->deleted)) $keywordsArray['deleted']	 = (int) $Filter->deleted;
			if (isset($Filter->plan)) $keywordsArray['plan']		 = (int) $Filter->plan;
			if (isset($Filter->planFilter)) $keywordsArray['planFilter'] = (int) $Filter->planFilter;
			if (isset($Filter->planYear)) $keywordsArray['planYear']	 = (int) $Filter->planYear;

			$this->session->set('kevinplan_filterKeywords', $keywordsArray); //set
		}
	}

	public function filterCheckout() {
		$keywordsArray		 = $this->session->kevinplan_filterKeywords;
		if (!$keywordsArray) $keywordsArray		 = array();
		if (!isset($this->Filter)) $this->Filter		 = new stdClass ();
		$Filter				 = &$this->Filter;
		$Filter->methodName	 = isset($keywordsArray['methodName']) ? $keywordsArray['methodName'] : '';
		$Filter->projectName = isset($keywordsArray['projectName']) ? $keywordsArray['projectName'] : '';
		$Filter->charger	 = isset($keywordsArray['charger']) ? $keywordsArray['charger'] : '';
		$Filter->member		 = isset($keywordsArray['member']) ? $keywordsArray['member'] : '';
		$Filter->planYear	 = isset($keywordsArray['planYear']) ? $keywordsArray['planYear'] : '';
		$Filter->plan		 = (int) (isset($keywordsArray['plan']) ? $keywordsArray['plan'] : 0);
		$Filter->projectPri	 = isset($keywordsArray['projectPri']) ? $keywordsArray['projectPri'] : '0';
		$Filter->planFilter	 = (int) (isset($keywordsArray['planFilter']) ? $keywordsArray['planFilter'] : 0);
		$Filter->dept		 = (int) (isset($keywordsArray['dept']) ? $keywordsArray['dept'] : 0);
		$Filter->deleted	 = (int) (isset($keywordsArray['deleted']) ? $keywordsArray['deleted'] : 0);
		$Filter->project	 = (int) (isset($keywordsArray['project']) ? $keywordsArray['project'] : 0);
		if (!$Filter->methodName) $Filter->methodName	 = 'projectlist';

		$this->filterDefault();
	}

	public function filterDefault() {
		if (!isset($this->Filter)) $this->Filter	 = new stdClass ();
		$Filter			 = &$this->Filter;

		$Filter->ShowColList['member']		 = 1;
		$Filter->ShowColList['charger']		 = 1;
		$Filter->ShowColList['dept']		 = 1;
		$Filter->ShowColList['plan']		 = 1;
		$Filter->ShowColList['project']		 = 1;
		$Filter->ShowColList['planYear']	 = 1;
		$Filter->ShowColList['projectName']	 = 1;
		$Filter->ShowColList['deleted']		 = 1;
		$Filter->ShowColList['projectPri']	 = 1;
	}

	/**
	 * get charger.
	 *
	 * @access public
	 * @return array
	 */
	public function getChargerList($plan) {
		if ($plan > 0) {
			$chargerlist = $this->dao->select('distinct charger ')
				->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a')
				->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('b.project=a.id')
				->where('a.deleted')->ne('1')
				->andWhere('b.plan')->eq($plan)
				->andWhere('b.deleted')->ne('1')
				->groupBy('a.charger')
				->fetchPairs();
			//die(json_encode($chargerlist));
		} else {
			$chargerlist = $this->dao->select('distinct charger ')
				->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a')
				->where('a.deleted')//				->beginIF($project>0)->andWhere('a.project')->eq($project)->fi()
				->groupBy('a.charger')
				->fetchPairs();
		}
		return $chargerlist;
	}

	public function getdeptpairs($project = 0, $type = '') {
		$list = $this->dao->select('id,name')
			->from(TABLE_DEPT)->alias('a')
			->where('a.deleted')->eq(0)
			->beginIF($project > 0)->andWhere('a.id')->eq($project)->fi()
			->fetchPairs();

		if ($type == 'Detail') {
			$deptlist[''] = '';
			foreach ($list as $id => $name) {
				$deptlist[$id] = "(" . $id . ")" . $name;
			}
			return $deptlist;
		}
		return $list;
	}

	public function getdeptlist($deptkey = 0) {
		$deptlist = $this->dao->select('distinct name')
			->from(TABLE_DEPT)->alias('a')
			->where('a.deleted')->eq('0')
			->orderBy('a.name')
			->fetchPairs();
		return $deptlist;
	}

	/**
	 * Get Error Msg
	 *
	 * @access public
	 * @return array
	 */
	public function getError() {
		$ErrMsg		 = dao::$errors; // (Must clear errors)
		dao::$errors = '';
		return $ErrMsg;
	}

	/**
	 * statistic By plan.
	 *
	 * @access public
	 * @return array
	 */
	public function getMemberList($plan, $project) {
		$projectArray	 = array();
		if ($project) $plan			 = 0;
		if ($plan > 0) {
			$projectArray = $this->dao->select('project ')
				->from(TABLE_KEVIN_PLAN_PROJECTGROUP)
				->where('plan')->eq($plan)
				->andWhere('deleted')->ne('1')
				->fetchPairs();
		}

		$memberlist = $this->dao->select('distinct member ')
			->from(TABLE_KEVIN_PLAN_MEMBER)->alias('a')
			->where('a.deleted')->eq('0')
			->beginIF($project > 0)->andWhere('a.project')->eq($project)->fi()
			->beginIF($plan)->andWhere('a.project')->in($projectArray)->fi()
			->orderBy('a.member')
			->fetchAll();

		$memberarr						 = array();
		foreach ($memberlist as $memberobj)
			$memberarr[$memberobj->member]	 = $memberobj->member;
		return $memberarr;
	}

	public function getPlanMemberList() {
		$memberlist	 = $this->dao->select('id as code,id')->from(TABLE_KEVIN_PLAN_MEMBER)->fetchPairs();
		array_unshift($memberlist, '');
		$finalarr	 = array_combine($memberlist, $memberlist);
		return $finalarr;
	}

	public function getplanlist($project, $planpriv = 1) {
		$deptpriv		 = $this->checkdeptpriv();
		$planlist		 = $this->dao->select("a.id,concat('[',a.id,']',' ',a.name)")->from(TABLE_KEVIN_PLAN_LIST)->alias('a')
			->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.plan')
			->where('a.deleted')->eq(0)
			->beginIF($planpriv)->andwhere("concat(',',a.charger,',',a.members)")->like('%,' . $this->app->user->account . ',%')->fi()
			->beginIF($project)->andwhere('b.deleted')->eq(0)->fi()
			->beginIF($project)->andwhere('b.project')->eq($project)->fi()
			->beginIF($deptpriv == 3)->andwhere('a.dept')->eq($this->app->user->dept)->fi()
			->beginIF($deptpriv == 2)->andwhere('a.dept')->in($this->deptchild)->fi()
			->fetchPairs();
		$idlist			 = array_keys($planlist);
		array_unshift($idlist, '');
		array_unshift($planlist, '');
		$planlistfinal	 = array();
		$planlistfinal	 = array_combine($idlist, $planlist);
		return $planlistfinal;
	}

	public function getplanlistAll() {
		$planlist = $this->dao->select("a.id,concat('[',a.id,']',' ',a.name)")->from(TABLE_KEVIN_PLAN_LIST)->alias('a')
			->where('a.deleted')->eq(0)
			->fetchPairs();
		return $planlist;
	}

	public function getpronamebycode($projectcode) {
		if (!$projectcode) return 0;
		$proname = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->eq($projectcode)->fetch();
		if ($proname) return $proname->name;
		else return 0;
	}

	/**
	 * statistic By plan.
	 *
	 * @access public
	 * @return array
	 */
	public function getProjectList($project = 0) {
		return $this->dao->select('distinct name,id ')
				->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a')
				->where('a.deleted')->eq('0')
				->beginIF($project > 0)->andWhere('a.id')->eq($project)->fi()
				->orderBy('id')
				->fetchAll();
	}

	public function getprolist($plan) {
		$prolist		 = $this->dao->select("a.id,concat('[',a.id,']',a.name) as name")
				->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a')
				->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.project')
				->where('a.deleted')->eq(0)->andwhere('b.deleted')->eq(0)
				->beginIF($plan)->andwhere('b.plan')->eq($plan)->fi()
				->orderBy('a.id')->fetchPairs('id', 'name');
		$idlist			 = array_keys($prolist);
		array_unshift($idlist, '');
		array_unshift($prolist, '');
		$prolistfinal	 = array();
		$prolistfinal	 = array_combine($idlist, $prolist);
		return $prolistfinal;
	}

	public function groupcreate($plan) {
		$planpro = $this->progroupairs();

		$now		 = helper::now();
		$progroup	 = fixer::input('post')->get();
		$idarr		 = array();
		$choices	 = $progroup->choices;
		foreach ($choices as $key => $choice) {
			if ($choice) {
				if (isset($planpro[$plan . '-' . $choice])) {
					$this->dao->update(TABLE_KEVIN_PLAN_PROJECTGROUP)->set('deleted')->eq(0)->where('id')->eq($planpro[$plan . '-' . $choice])->andwhere('deleted')->eq(1)->exec();
					continue;
				}
				$pairs				 = new stdClass();
				$pairs->plan		 = $plan;
				$pairs->project		 = $choice;
				$pairs->addedBy		 = $this->app->user->account;
				$pairs->addedDate	 = $now;
				$this->dao->insert(TABLE_KEVIN_PLAN_PROJECTGROUP)->data($pairs)
					->autoCheck()
					->exec();
				$idarr[$key]		 = $this->dao->lastInsertID();
			}
		}
		return $idarr;
	}

	/**
	 * Create a kevinplan.
	 *
	 * @param  date   $date
	 * @param  string $account
	 * @access public
	 * @return void
	 */
	public function membercreate($project) {
		$project	 = (int) $project; //id
		if ($project < 1) return 0;
		$projectItem = $this->projectGetByID($project);
		if (!$projectItem) return 0;
//                $userlist=$this->getuserpairs();
//                $deptlist=$this->getdeptpairs();
		$now		 = helper::now();
		$item		 = fixer::input('post')
			->add('project', $project)
			->add('addedBy', $this->app->user->account)
			->add('addedDate', $now)
			->setDefault('deleted', '0')
			->get();
//		$item->name = $plan->name ." ". $item->item;
		$this->dao->insert(TABLE_KEVIN_PLAN_MEMBER)->data($item)
			->autoCheck()
			->batchCheck($this->config->kevinplan->membercreate->requiredFields, 'notempty')
			->exec();
//		if (dao::isError()) return 0;
		$id			 = $this->dao->lastInsertID();
		if ($id) $this->updateprohoursByItem($projectItem);
		return $id;
	}

	public function membercreateDefault($projectItem, $notes = '', $dump = 0) {
		if (!$projectItem) return 0;
		//add default charger
		$memberItem				 = new stdclass();
		$memberItem->project	 = $projectItem->id;
		$memberItem->dept		 = $projectItem->dept;
		$memberItem->member		 = $projectItem->charger;
		$memberItem->hours		 = $projectItem->hoursPlan;
		$memberItem->startDate	 = $projectItem->startDate;
		$memberItem->endDate	 = $projectItem->endDate;
		$memberItem->addedBy	 = $this->app->user->account;
		$memberItem->addedDate	 = helper::now();
		$memberItem->deleted	 = '0';

		$memberItem->notes = ($notes) ? $notes : $this->lang->kevinplan->charger;

		$this->dao->insert(TABLE_KEVIN_PLAN_MEMBER)->data($memberItem)->exec();

		$id = $this->dao->lastInsertID();
		if ($id && $dump) {
			echo " Add a default member for project id = " . $projectItem->id . ", name = " . $projectItem->name . "<br>"
			. "New member plan id = " . $id . ", member = " . $memberItem->member . ", hours = " . $memberItem->hours . "<br>";
		}
		return $id;
	}

	/**
	 * Get info of a item.
	 * @param  int    $id
	 * @access public
	 * @return object|bool
	 */
	public function memberGetByID($id) {
		$item = $this->dao->findById((int) $id)->from(TABLE_KEVIN_PLAN_MEMBER)->fetch();
		if (!$item) return null;
		return $item;
	}

	/**
	 * Get item array
	 *
	 *
	 * @access public
	 * @return array
	 */
	public function memberGetList($Filter, $deleted, $planpriv, $page) {

		if (!is_numeric($Filter->plan) || $Filter->plan < 0) $Filter->plan		 = 0;
		$Filter->projectName = '';
		if (!is_numeric($Filter->project)) {
			$Filter->projectName = $Filter->project;
			$Filter->project	 = 0;
		}
		//die(json_encode($Filter));
		$procharger		 = '';
		$plancharger	 = '';
		$propairs		 = '';
		$planpairs		 = '';
		if ($Filter->type == 'my') $Filter->member	 = $this->app->user->account;
		$member			 = &$Filter->member;
		if ($member == 'all') $member			 = '';
		elseif ($member == 'myproject') {
			$procharger	 = $this->app->user->account;
			$member		 = '';
		} elseif ($member == 'myplan') {
			$plancharger = $this->app->user->account;
			$propairs	 = $this->dao->select("concat(plan,'-', b.project) as code,b.project")->from(TABLE_KEVIN_PLAN_LIST)->alias('a')
				->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.plan')
				->where('a.deleted')->eq(0)->andwhere('b.deleted')->eq(0)
				->andwhere('a.charger')->eq($plancharger)
				->fetchPairs('code', 'project');
			$member		 = '';
		}

		// }
		if ($Filter->project) {
			$member			 = '';
			$page			 = null;
			$Filter->plan	 = 0;
		}
		if ($Filter->plan) {
			// if(is_numeric($plan)&&$plan>0){
			$planpairs = $this->dao->select("concat(plan,'-',b.project) as code,b.project")->from(TABLE_KEVIN_PLAN_LIST)->alias('a')
				->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.plan')
				->where('a.deleted')->eq(0)->andwhere('b.deleted')->eq(0)
				->beginIF($planpriv)->andwhere("concat(',',a.charger,',',a.members)")->like('%,' . $this->app->user->account . ',%')->fi()
				->beginIF($Filter->plan)->andwhere('a.id')->eq($Filter->plan)->fi()
				->fetchPairs('code', 'project');
		} 

		return $this->dao->select("p.*,a.*")->from(TABLE_KEVIN_PLAN_PROJECT)->alias('p')
				->leftJoin(TABLE_KEVIN_PLAN_MEMBER)->alias('a')->on('a.project=p.id')
				->where('a.deleted')->eq($deleted)
				->andWhere('p.deleted')->eq('0')
				->beginIF($Filter->projectPri)->andwhere('p.pri')->eq($Filter->projectPri)->fi()
				->beginIF($plancharger && isset($propairs) && is_array($propairs))->andwhere('a.project')->in($propairs)->fi()
				->beginIF($Filter->plan && is_array($planpairs))->andwhere('a.project')->in($planpairs)->fi()
				->beginIF($procharger)->andwhere('p.charger')->eq($procharger)->fi()
				->beginIF($Filter->member)->andWhere('a.member')->eq($Filter->member)->fi()
				->beginIF(!$Filter->project && $Filter->dept)->andWhere('a.dept')->eq($Filter->dept)->fi()
				->beginIF($Filter->planYear)->andWhere('p.planYear')->eq($Filter->planYear)->fi()
				->beginIF($Filter->project)->andWhere('p.id')->eq($Filter->project)->fi()
				->beginIF($Filter->projectName)->andWhere('p.name')->eq($Filter->projectName)->fi()
				->beginIF(!$Filter->projectName && $Filter->project)->andWhere('p.id')->eq($Filter->project)->fi()
				->orderBy('a.hours desc')
				->beginIF($page)->page($page)->fi()
				->fetchAll();
	}

	/**
	 * Update a kevinplan.
	 *
	 * @param  int    $id
	 * @access public
	 * @return void
	 */
	public function memberupdate($id) {
		$oldItem = $this->memberGetByID($id);
		if (!$oldItem) return false;
		$now	 = helper::now();
		$item	 = fixer::input('post')
			->stripTags($this->config->kevinplan->editor->memberedit['id'], $this->config->allowedTags)
			->add('lastEditedBy', $this->app->user->account)
			->add('lastEditedDate', $now)
			->get();

		//$item = $this->loadModel('file')->processEditor($item, $this->config->kevinplan->editor->memberedit['id']);
		$this->dao->update(TABLE_KEVIN_PLAN_MEMBER)->data($item)
			->autoCheck()
			->batchCheck($this->config->kevinplan->memberupdate->requiredFields, 'notempty')
			->where('id')->eq($id)
			->exec();

		if (!dao::isError()) {
			$this->updateprohours($oldItem->project);
			return common::createChanges($oldItem, $item);
		}
		return false;
	}

	/*
	 * 批量添加没有用户的项目的用户
	 */

	public function planbatchcreatemember($planItem) {
		if (!$planItem) return 0;
		$projectExistMemberList	 = $this->dao->select("count( *) as count1,a.project from kv_plan_member a left join kv_plan_projectgroup b on a.project = b.project  where b.plan = $planItem->id group by a.project")
			->fetchPairs('project', 'project');
		$projectlist			 = $this->dao->select("a.*")->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a')
			->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.project')
			->where('a.deleted')->eq('0')
			->andwhere('a.id')->notin($projectExistMemberList)
			->andwhere('b.plan')->eq($planItem->id)
			->fetchAll();
		$count					 = 0;
		echo "Only add member for those project which hoursPlan = 0 and members count = 0 <br>";

		//die(json_encode($projectExistMemberList));
		foreach ($projectlist as $projectItem) {
			// exist.
			if (array_key_exists($projectItem->id, $projectExistMemberList) || $projectItem->hoursPlan == 0) continue;
			echo "No." . $count . '<br>';
			//add member
			$memberid = $this->membercreateDefault($projectItem, '', 1);
			$count += 1;
		}
		return $count;
	}

	/**
	 * Create a kevinplan.
	 *
	 * @param  date   $date
	 * @param  string $account
	 * @access public
	 * @return void
	 */
	public function plancreate() {
		$now		 = helper::now();
		$chargerinfo = $this->dao->select('realname')->from(TABLE_USER)->where('account')->eq($this->post->charger)->fetch();
		$kevinplan	 = fixer::input('post')
			->add('chargerName', $chargerinfo->realname)
			->add('addedBy', $this->app->user->account)
			->add('addedDate', $now)
			->get();
		$this->dao->insert(TABLE_KEVIN_PLAN_LIST)->data($kevinplan)
			->autoCheck()
			->batchCheck($this->config->kevinplan->plancreate->requiredFields, 'notempty')
			->exec();
		return $this->dao->lastInsertID();
	}

	/**
	 * Get info of a kevinplan.
	 * @param  int    $id
	 * @access public
	 * @return object|bool
	 */
	public function planGetByID($id) {
		$planItem = $this->dao->findById((int) $id)->from(TABLE_KEVIN_PLAN_LIST)->fetch();
		if (!$planItem) return null;
		return $planItem;
	}

	/**
	 * Get charger of a kevinplan.
	 * @param  int    $id
	 * @access public
	 * @return object|bool
	 */
	public function planGetByProject($id) {
		$planItem = $this->dao->select('c.*')->from(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('a')
			->leftJoin(TABLE_KEVIN_PLAN_PROJECT)->alias('b')->on('a.project=b.id')
			->leftJoin(TABLE_KEVIN_PLAN_LIST)->alias('c')->on('a.plan=c.id')
			->where('a.project')->eq($id)->andwhere('a.deleted')->eq(0)
			->fetch();
		if (!$planItem) return null;
		return $planItem;
	}

	/**
	 * Get info of a item.
	 * @param  int    $id
	 * @access public
	 * @return object|bool
	 */
	public function planGetList($pager, $name, $deleted, $planpriv = 1) {
		$deptpriv = $this->checkdeptpriv();
		return $this->dao->select("*")->from(TABLE_KEVIN_PLAN_LIST)
				->where('deleted')->eq($deleted)
				->beginIF($planpriv)->andwhere("concat(',',charger,',',members)")->like('%,' . $this->app->user->account . ',%')->fi()
				->beginIF($name)->andwhere('name')->like("%$name%")->fi()
				->beginIF($deptpriv == 3)->andwhere('dept')->eq($this->app->user->dept)->fi()
				->beginIF($deptpriv == 2)->andwhere('dept')->in($this->deptchild)->fi()
				->page($pager)
				->fetchAll();
	}

	/**
	 * Get info of a item.
	 * @param  int    $id
	 * @access public
	 * @return object|bool
	 */
	public function planNameGetPairs() {
		return $this->dao->select("id,name")->from(TABLE_KEVIN_PLAN_LIST)
				->orderBy('id')
				->fetchPairs('id', 'name');
	}

	/**
	 * Update a kevinplan.
	 *
	 * @param  int    $id
	 * @access public
	 * @return void
	 */
	public function planupdate($id) {
		$now		 = helper::now();
		$chargerinfo = $this->dao->select('realname')->from(TABLE_USER)->where('account')->eq($this->post->charger)->fetch();

		$item					 = fixer::input('post')->get();
		$members				 = '';
		foreach ($item->members as $member)
			$members.=$member . ',';
		$item->members			 = $members;
		$item->chargerName		 = $chargerinfo->realname;
		$item->lastEditedBy		 = $this->app->user->account;
		$item->lastEditedDate	 = $now;

		$this->dao->update(TABLE_KEVIN_PLAN_LIST)->data($item)
			->autoCheck()
			->batchCheck($this->config->kevinplan->planupdate->requiredFields, 'notempty')
			->where('id')->eq($id)
			->exec();
	}

	/**
	 * checkpriv
	 *
	 * @param  int    $id
	 * @access public
	 * @return void
	 */
	public function privmember(&$objs) {
		if (!$objs) return;
		if (isset($objs->id)) {
			$this->isMember[$objs->id] = ($objs->member == $this->app->user->account) ? true : false;
		} else {
			foreach ($objs as $obj)
				$this->isMember[$obj->id] = ($obj->member == $this->app->user->account) ? true : false;
		}
	}

	/**
	 * checkpriv
	 *
	 * @param  int    $id
	 * @access public
	 * @return void
	 */
	public function privcharger(&$objs) {
		if (isset($objs->projectCode)) $teamember	 = $this->dao->select('account as id,account')->from(TABLE_TEAM)->where('project')->eq($objs->projectCode)->fetchPairs();
		else $teamember	 = array();
		if (isset($objs->id)) {
			$this->isCharger[$objs->id] = ($objs->charger == $this->app->user->account || in_array($this->app->user->account, $teamember)) ? true : false;
//				if(!$this->isCharger[$objs->id])die(js::alert('You have no priviliage!').js::locate('back'));
		} elseif (empty($objs)) {
			$this->isCharger[0] = false;
		} else {
			foreach ($objs as $obj)
				$teamarr[$obj->projectCode]				 = $obj->projectCode;
			$teams									 = $this->dao->select('*')->from(TABLE_TEAM)->where('project')->in($teamarr)->fetchAll();
			$allteam								 = array();
			foreach ($teams as $team)
				$allteam[$team->project][$team->account] = $team->account;
			foreach ($objs as $obj) {
				if (!isset($allteam[$obj->projectCode])) $allteam[$obj->projectCode]	 = array();
				$this->isCharger[$obj->id]	 = ($obj->charger == $this->app->user->account || in_array($this->app->user->account, $allteam[$obj->projectCode])) ? true : false;
			}
		}
	}

	/**
	 * Get info of a item.
	 * @param  int    $id
	 * @access public
	 * @return object|bool
	 */
	public function progroupairs() {
		return $this->dao->select("concat(plan,'-',project) as code,id as value")->from(TABLE_KEVIN_PLAN_PROJECTGROUP)->fetchPairs('code', 'value');
	}

	/**
	 * Create a project.
	 *
	 * @param  date   $date
	 * @param  string $account
	 * @access public
	 * @return void
	 */
	public function projectColCheck($projectItem, $checkObjExist) {
		dao::$errors = array();
		if (isset($projectItem->hoursPlan)) {
			//hoursPlan
			$projectItem->hoursPlan = abs((int) $projectItem->hoursPlan);
			if ($projectItem->hoursPlan == 0) {
				dao::$errors[''][] = "『hoursPlan』 can not be 0.";
			}
		}

		//projectCode
		if ($projectItem->projectCode == '') $projectItem->projectCode = 0;
		if (!is_numeric($projectItem->projectCode)) {
			dao::$errors[''][] = "『projectCode』 must be a numeric";
		}
		$projectItem->projectCode = abs((int) $projectItem->projectCode);

		//name
		if (!$projectItem->name) {
			dao::$errors[''][] = "『name』 Must Input a valid project plan name.";
		}

		//planYear
		$projectItem->planYear = (int) $projectItem->planYear;
		if ($projectItem->planYear <= 1970) {
			dao::$errors[''][] = "『planYear』 Must larger than 1970";
		}
		if (dao::$errors) return 0; //error
		if ($checkObjExist) {
			if ($this->projectCheckDuplicate($projectItem)) return 0;
		}
		return 1;
	}

	/**
	 * Create a project.
	 *
	 * @param  date   $date
	 * @param  string $account
	 * @access public
	 * @return void
	 */
	public function projectCheckDuplicate($projectItem) {
		if ($projectItem->projectCode) {
			$projectExist = $this->projectgetByYear($projectItem->planYear, $projectItem->projectCode);
			if ($projectExist) {
				if (isset($projectItem->id) && $projectExist->id == $projectItem->id) return 0;
				dao::$errors[''][]	 = "『planYear』-『projectCode』 must be unique.";
				dao::$errors[''][]	 = " - In Year = $projectItem->planYear,Exist the Project Code PID = $projectItem->projectCode.";
				dao::$errors[''][]	 = " - Whitch one\'s project plan ID is $projectExist->id. ";
				dao::$errors[''][]	 = " - You can not add a duplicate one.";
				dao::$errors[''][]	 = " - charger is $projectExist->charger.";
				return 1;
			}
		}
		return 0;
	}

	/**
	 * Create a project.
	 *
	 * @param  date   $date
	 * @param  string $account
	 * @access public
	 * @return void
	 */
	public function projectcreate($planItem) {
		if (!$planItem) {
			dao::$errors = "please input planItem";
			return 0;
		}
		if (!$planItem->id) {
			dao::$errors = "please input planItem id is wrong";
			return 0;
		}
		$now		 = helper::now();
		$projectItem = fixer::input('post')
			->add('addedBy', $this->app->user->account)
			->add('addedDate', $now)
			->setDefault('deleted', '0')
			->get();
		//die(json_encode($projectItem->projectCode));
		if (0 == $this->projectColCheck($projectItem, 1)) return 0;

		$this->dao->insert(TABLE_KEVIN_PLAN_PROJECT)->data($projectItem)
			->autoCheck()
			->batchCheck($this->config->kevinplan->projectcreate->requiredFields, 'notempty')
			->exec();
		$id				 = $this->dao->lastInsertID();
		if (!$id) return 0;
		$projectItem->id = $id;

		//add group
		$pairs				 = new stdClass();
		$pairs->plan		 = $planItem->id;
		$pairs->project		 = $id;
		$pairs->addedBy		 = $this->app->user->account;
		$pairs->addedDate	 = $now;
		$this->dao->insert(TABLE_KEVIN_PLAN_PROJECTGROUP)->data($pairs)->autoCheck()->exec();

		//add member
		$memberid = $this->membercreateDefault($projectItem);
		return $id;
	}

	/**
	 * delete a kevinplan.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function projectdelete($projectItem, $plan = 0) {
		$project = (int) $projectItem->id; //to int
		$plan	 = abs((int) $plan);
		if ($plan) {  //for plan
			$this->dao->delete("*")->from(TABLE_KEVIN_PLAN_PROJECTGROUP)->where("project = $project AND plan = $plan")->exec();
		} else { //for project
			$target = $projectItem->deleted ? '0' : '1';
			$this->dao->update(TABLE_KEVIN_PLAN_PROJECT)->set('deleted')->eq('1')->where('id')->eq($project)->andwhere('deleted')->eq($target)->exec();
		}
	}

	/**
	 * Get info of a item.
	 * @param  int    $id
	 * @access public
	 * @return object|bool
	 */
	public function projectGetByFilter($Filter, $planpriv, $pager, $orderby = '') {

		$proListNotIn = array();
		if ('groupcreate' == $Filter->methodName) {
			$planNotIn	 = $Filter->plan;
			$plan		 = $Filter->planFilter;
		} else {
			$plan		 = $Filter->plan;
			$planNotIn	 = 0;
		}
		if (!$plan) $planpriv = 0;
		if ($planNotIn) {
			$proListNotIn = $this->dao->select("distinct a.id")->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a')
				->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.project')
				->where('a.deleted')->eq($Filter->deleted)
				->beginIF($Filter->dept)->andwhere('a.dept')->eq($Filter->dept)->fi()
				->andwhere('b.plan')->eq($planNotIn)->fi()
				->fetchPairs();
		}
		$smtm = $this->dao->select("distinct a.*")->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a');
		if ($planpriv) {
			$smtm = $smtm->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.project')
					->leftJoin(TABLE_KEVIN_PLAN_LIST)->alias('c')->on('b.plan=c.id');
		}
		return $smtm->where('true')
				->beginIF($Filter->projectPri)->andwhere('a.pri')->eq($Filter->projectPri)->fi()
				->beginIF(!$plan)->andwhere('a.deleted')->eq($Filter->deleted)->fi()
				->beginIF($Filter->dept)->andwhere('a.dept')->eq($Filter->dept)->fi()
				->beginIF($planpriv)->andwhere("concat(',', c.charger, ',', c.members)")->like('%,' . $this->app->user->account . ',%')->fi()
				->beginIF($Filter->projectName)->andwhere('a.name')->like("%$Filter->projectName%")->fi()
				->beginIF($Filter->charger)->andwhere("(a.charger = '$Filter->charger' or a.charger2 = '$Filter->charger')")->fi()
				->beginIF($Filter->planYear)->andwhere('a.planYear')->eq($Filter->planYear)->fi()
				->beginIF($plan)->andwhere('b.plan')->eq($plan)->fi()
				->beginIF($planNotIn)->andwhere('a.id')->notin($proListNotIn)->fi()
				//	->beginIF($deptpriv==3)->andwhere('a.dept')->eq($this->app->user->dept)->fi()
				//->beginIF($deptpriv==2)->andwhere('a.dept')->in($this->deptchild)->fi()
				->page($pager)
				->beginIF($orderby)->orderBy($orderby)->fi()
				->fetchAll();
	}

	/**
	 * Get info of a item.
	 * @param  int    $id
	 * @access public
	 * @return object|bool
	 */
	public function projectGetList($pager, $name, $deleted, $targetPlan, $charger, $planFilter = '', $planpriv = 1) {

//            if($plan){
//                $planremove=$this->dao->select('project,plan')->from(TABLE_KEVIN_PLAN_PROJECTGROUP)->where('plan')->eq($plan)->andwhere('deleted')->eq(0)->fetchPairs('project','plan');
//                $projectremove=array_keys($planremove);
//            }else $projectremove=array();
//            $planarr=$this->dao->select('project,plan')->from(TABLE_KEVIN_PLAN_PROJECTGROUP)->where('plan')->eq($plan)->andwhere('deleted')->eq(0)->fetchPairs('project','plan');
//            $projectarr=array_keys($planarr);
//            return $this->dao->select("*")->from(TABLE_KEVIN_PLAN_PROJECT)
//                                            ->where('name')->like("%$name%")
//                                            ->beginIF($charger)->andwhere('charger')->eq($charger)->fi()
//                                             ->beginIF($plan!=0)->andwhere('id')->in($projectarr)->fi()
//                                             ->beginIF($plan)->andwhere('id')->notin($projectremove)->fi()
//											->beginIF($deptpriv==3)->andwhere('dept')->eq($this->app->user->dept)->fi()
//											->beginIF($deptpriv==2)->andwhere('dept')->in($this->deptchild)->fi()
//                                            ->andwhere('deleted')->eq($deleted)
//                                             ->page($pager)
//                                             ->fetchAll();
		$sourceProArray = array();
		if ($planFilter != 0) {
			$sourceProArray = $this->dao->select("distinct a.id")->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a')
				->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.project')
				->where('b.deleted')->eq(0)
				->andwhere('b.plan')->eq($planFilter)->fi()
				->andwhere('a.deleted')->eq($deleted)
				->fetchPairs();
		}
		//die(json_encode($sourceProArray));
		return $this->dao->select("distinct a.*")->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a')
				->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.project')
				->beginIF($planpriv)->leftJoin(TABLE_KEVIN_PLAN_LIST)->alias('c')->on('b.plan=c.id')->fi()
				->where('b.deleted')->eq(0)
				->andwhere('a.deleted')->eq($deleted)
				->beginIF($planpriv)->andwhere("concat(',', c.charger, ',', c.members)")->like('%,' . $this->app->user->account . ',%')->fi()
				->beginIF($name)->andwhere('a.name')->like("%$name%")->fi()
				->beginIF($charger)->andwhere('a.charger')->eq($charger)->fi()
				->beginIF($targetPlan != 0)->andwhere('b.plan')->eq($targetPlan)->fi()
				->beginIF($planFilter)->andwhere('a.id')->notin($sourceProArray)->fi()
				//											->beginIF($deptpriv==3)->andwhere('a.dept')->eq($this->app->user->dept)->fi()
				//											->beginIF($deptpriv==2)->andwhere('a.dept')->in($this->deptchild)->fi()
				->page($pager)
				->fetchAll();
	}

	/**
	 * Get info of a project.
	 * @param  int    $id
	 * @access public
	 * @return object|bool
	 */
	public function projectGetByID($id) {
		$projectItem = $this->dao->findById((int) $id)->from(TABLE_KEVIN_PLAN_PROJECT)->fetch();
		if (!$projectItem) return null;
		return $projectItem;
	}

	/**
	 * get Project by year and code.
	 *
	 * @param  int $planYear
	 * @param  int $projectCode
	 * @access public
	 * @return void
	 */
	public function projectgetByYear($planYear, $projectCode) {
		return $this->dao->select("*")->from(TABLE_KEVIN_PLAN_PROJECT)
				->where("`planYear` = $planYear AND `projectCode` = $projectCode")
				->beginIF(0 == $planYear)->orderBy('projectCode')->fi()
				->fetch();
	}

	/**
	 * Get info of a item.
	 * @param  int    $id
	 * @access public
	 * @return object|bool
	 */
	public function projectNameGetPairs() {
		return $this->dao->select("id, name")->from(TABLE_KEVIN_PLAN_PROJECT)
				->orderBy('id')
				->fetchPairs('id', 'name');
	}

	/**
	 * Update a project.
	 *
	 * @param  int    $id
	 * @access public
	 * @return void
	 */
	public function projectupdate($projectItem) {
		$id				 = $projectItem->id;
		$now			 = helper::now();
		$plans			 = $this->post->plans;
		$item			 = fixer::input('post')
			->add('lastEditedBy', $this->app->user->account)
			->add('lastEditedDate', $now)
			->remove('plans')
			->get();
		$itemCheck		 = $item;
		$itemCheck->id	 = $id;
		if (0 == $this->projectColCheck($itemCheck, 1)) return 0;

		$flag = $this->dao->update(TABLE_KEVIN_PLAN_PROJECT)->data($item)
			->autoCheck()
			->batchCheck($this->config->kevinplan->projectedit->requiredFields, 'notempty')
			->where('id')->eq($id)
			->exec();
		//var_dump($flag);

		if ($flag && $plans) {
			$planpro = $this->progroupairs();
			//only can add project can not delete
			foreach ($plans as $plan) {
				if (isset($planpro[$plan . '-' . $id])) {
					$this->dao->update(TABLE_KEVIN_PLAN_PROJECTGROUP)->set('deleted')->eq(0)->where('id')->eq($planpro[$plan . '-' . $id])->andwhere('deleted')->eq(1)->exec();
					continue;
				}
				$pairs				 = new stdClass();
				$pairs->plan		 = $plan;
				$pairs->project		 = $id;
				$pairs->addedBy		 = $this->app->user->account;
				$pairs->addedDate	 = $now;
				$this->dao->insert(TABLE_KEVIN_PLAN_PROJECTGROUP)->data($pairs)->autoCheck()->exec();
//                $idarr[$key]=$this->dao->lastInsertID();
			}

			$count = $this->updateprohoursByItem($projectItem);
		}
	}

	/**
	 * Make same with the project hours
	 *
	 * @access public
	 * @return array
	 */
	public function updateplanhours($id) {
		$now	 = helper::now();
		$hoursum = $this->dao->select('sum(hoursPlan) as totalhours')->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a')
			->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.project')
			->where('b.plan')->eq($id)
			->andwhere('a.deleted')->eq(0)->andwhere('b.deleted')->eq(0)
			->fetchAll();
		$this->dao->update(TABLE_KEVIN_PLAN_LIST)->set('hoursPlan')->eq($hoursum[0]->totalhours)
			->set('lastEditedBy')->eq($this->app->user->account)
			->set('lastEditedDate')->eq($now)
			->where('id')->eq($id)
			->andwhere('deleted')->eq(0)
			->exec();
	}

	/**
	 * Make same with the project hours
	 *
	 * @access public
	 * @return array
	 */
	public function updateprohours($id = 0) {
		$this->Message		 = array();
		$this->Message[''][] = "Function 『updateprohours』:";

		$now		 = helper::now();
		$countBatch	 = 0;
		if ($id <= 0) {
			$prohours = fixer::input('post')->get();
			if (!$prohours || !isset($prohours->choices)) {
				dao::$errors[''][] = "Function 『updateprohours』 Please set id for project to update hours!";
				return 0;
			}
			$choices	 = $prohours->choices;
			$countBatch	 = count($choices);
		} elseif (is_numeric($id)) {
			$choices = array($id);
		}
		$count = 0;
		foreach ($choices as $choice) {
			$projectItem = $this->dao->findById((int) $choice)->from(TABLE_KEVIN_PLAN_PROJECT)->fetch();
			if (!$projectItem) continue; //error
			$count += $this->updateprohoursByItem($projectItem);
		}
		$this->Message[''][] = "Update count = $count";
		if ($count) $this->Message[''][] = "Please refreash your page manually";
		$this->Message[''][] = "Function id done.";

		if ($countBatch > 0) dao::$errors = $this->Message;
		return $count;
	}

	/**
	 * Make same with the project hours
	 *
	 * @access public
	 * @return array
	 */
	public function updateprohoursByItem($projectItem) {
		if (!$projectItem || !property_exists($projectItem, 'id')) {
			dao::$errors[''][] = "Please project plan item!";
			return 0;
		}

		$project	 = $projectItem->id;
		$hoursumItem = $this->dao->select('sum(hours) as totalhours')->from(TABLE_KEVIN_PLAN_MEMBER)->where('project')->eq($project)->andwhere('deleted')->eq(0)->fetch();
		$hoursSum	 = ($hoursumItem) ? (int) $hoursumItem->totalhours : 0;
		if ($hoursSum < 0) $hoursSum	 = 0;

		if ($projectItem->hoursPlan == $hoursSum) return 0; //same do not update
		$hr = $this->dao->update(TABLE_KEVIN_PLAN_PROJECT)->set('hoursPlan')->eq($hoursSum)
			->set('lastEditedBy')->eq($this->app->user->account)
			->set('lastEditedDate')->eq(helper::now())
			->where('id')->eq($project)
			->exec();
		return ($hr) ? 1 : 0;
	}

	/**
	 * statistic By plan.
	 *
	 * @access public
	 * @return array
	 */
	public function statisticByMember() {
		//->beginIF($planpriv)->andwhere("concat(',', a.charger, ',', a.members)")->like('%,' . $this->app->user->account . ',%')->fi()
		$Filter	 = &$this->Filter;
		$proList = array();
		if ($Filter->plan) {
			$proList = $this->dao->select("b.project")->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a')
				->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.project')
				->where('a.deleted')->eq(0)->andwhere('b.deleted')->eq(0)
				->andwhere('b.plan')->eq($Filter->plan)
				->fetchPairs('project', 'project');
		}
		return $this->dao->select('sum(a.hours) as YValue, a.member as XValue')
				->from(TABLE_KEVIN_PLAN_MEMBER)->alias('a')
				->beginIF($Filter->planYear)->leftJoin(TABLE_KEVIN_PLAN_PROJECT)->alias('b')->on('b.id=a.project')->fi()
				->where('a.deleted')->eq('0')
				->beginIF($Filter->planYear)->andwhere('b.planYear')->eq($Filter->planYear)->fi()
				->beginIF($Filter->dept)->andwhere('a.dept')->eq($Filter->dept)->fi()
				->beginIF($proList)->andwhere('a.project')->in($proList)->fi()
				->groupBy('a.member')
				->orderBy('YValue desc')
				->fetchAll();
	}

	/**
	 * statistic By module.
	 *
	 * @access public
	 * @return array
	 */
	public function statisticByProject() {
		$Filter	 = &$this->Filter;
		$proList = array();
		if ($Filter->plan) {
			$proList = $this->dao->select("b.project")->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a')
				->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.project')
				->where('a.deleted')->eq(0)->andwhere('b.deleted')->eq(0)
				->andwhere('b.plan')->eq($Filter->plan)
				->fetchPairs('project', 'project');
		}
		return $this->dao->select('sum(a.hours) as YValue, b.name as XValue')
				->from(TABLE_KEVIN_PLAN_MEMBER)->alias('a')
				->leftJoin(TABLE_KEVIN_PLAN_PROJECT)->alias('b')->on('a.project=b.id')
				->where('a.deleted')->eq('0')
				->beginIF($Filter->planYear)->andwhere('b.planYear')->eq($Filter->planYear)->fi()
				->beginIF($Filter->dept)->andwhere('a.dept')->eq($Filter->dept)->fi()
				->beginIF($proList)->andwhere('a.project')->in($proList)->fi()
				->groupBy('b.name')
				->orderBy('YValue DESC')
				->fetchAll();
	}

	/**
	 * statistic By plan.
	 *
	 * @access public
	 * @return array
	 */
	public function statisticByPlan() {
		$Filter						 = &$this->Filter;
		$Filter->ShowColList['plan'] = 0;
		return $this->dao->select('a.hoursPlan as YValue, a.name as XValue')
				->from(TABLE_KEVIN_PLAN_LIST)->alias('a')
				->where('a.deleted')->eq('0')
				->beginIF($Filter->planYear)->andwhere('a.planYear')->eq($Filter->planYear)->fi()
				->beginIF($Filter->dept)->andwhere('a.dept')->eq($Filter->dept)->fi()
				->orderBy('YValue DESC')
				->fetchAll();
	}

	/**
	 * statistic By module.
	 *
	 * @access public
	 * @return array
	 */
	public function statisticByGroup() {
		$Filter						 = &$this->Filter;
		$Filter->ShowColList['plan'] = 0;

		return $this->dao->select('count(b.plan) as YValue, a.name as XValue')
				->from(TABLE_KEVIN_PLAN_LIST)->alias('a')
				->leftJoin(TABLE_KEVIN_PLAN_PROJECTGROUP)->alias('b')->on('a.id=b.plan')
				->where('a.deleted')->eq('0')
				->andwhere('b.deleted')->eq('0')
				->beginIF($Filter->planYear)->andwhere('a.planYear')->eq($Filter->planYear)->fi()
				->beginIF($Filter->dept)->andwhere('a.dept')->eq($Filter->dept)->fi()
				->groupBy('b.plan')
				->orderBy('YValue DESC')
				->fetchAll();
	}

}
