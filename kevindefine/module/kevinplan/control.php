<?php

/**
 * The control file
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

class kevinplan extends control {

	public function __construct() {
		parent::__construct();
		$this->loadModel('doc');
		$this->loadModel('user');
		$this->loadModel('action');
		$this->loadModel('kevincom');
	}

	/**
	 * ajax Get Plan list.
	 * 
	 * @param  string|date 
	 * @param  string      
	 * @access public
	 * @return void
	 */
	public function ajaxGetPlanlist() {
		$items = $this->dao->select("*")->from(TABLE_KEVIN_PLAN_LIST)
			->where('deleted')->eq('0')
			->orderBy('id')
			->fetchAll();
		die(json_encode($items));
	}

	/**
	 * ajax Get project name.
	 * 
	 * @param  string|date 
	 * @param  string      
	 * @access public
	 * @return void
	 */
	public function ajaxgetprojectname($projectcode, $rawval) {
		$proname = $this->kevinplan->getpronamebycode($projectcode);
		if (!$proname) die(html::input('name', $rawval, "class='form-control' placeholder='Input projectCode.Like 云计算网站'") . js::alert($this->lang->kevinplan->pronotexist));
		die(html::input('name', $proname, "class='form-control'"));
	}

	/**
	 * ajax Get Plan by ID.
	 * 
	 * @param  string|date 
	 * @param  string      
	 * @access public
	 * @return void
	 */
	public function ajaxGetPlan($id) {
		$Plan			 = $this->kevinplan->planGetByID($id, true);
		if (!$Plan) die(json_encode($Plan));
		$Plan->sublist	 = array();
		$kk;
		$kk->id			 = "1";
		$kk->name		 = "kevin";
		$kk->Value		 = "kevin1";

		$Plan->sublist[0] = $kk;

		$kk->id				 = "2";
		$Plan->sublist[2]	 = $kk;
		$kk->id				 = "2";
		$Plan->sublist[3]	 = $kk;
		die(json_encode($Plan));
	}

	/**
	 * Create a project exist.
	 * 
	 * @param  string|date 
	 * @param  string      
	 * @access public
	 * @return void
	 */
	public function groupcreate($plan = 0, $recTotal = 0, $recPerPage = 30, $pageID = 1) {
		$plan	 = (int) $plan;
		$this->projectFilter(); //get form filter value
		$Filter	 = &$this->kevinplan->Filter;

		$Filter->planFilter	 = 0; //无源
		$Filter->type		 = "all";
		if ($plan) $Filter->plan		 = $plan; //当前计划
		if ($Filter->project) $Filter->plan		 = 0;

		if (!empty($_POST)) {
			$kevinformtype = $this->post->kevinformtype;
			if ($kevinformtype != "projectFilter") {
				if ($plan > 0) $proidarr = $this->kevinplan->groupcreate($plan);
				if (dao::isError()) die(js::error(dao::getError()));
				if (isonlybody()) die(js::reload('parent.parent'));
				die(js::locate($this->createLink('kevinplan', 'projectlist', "type=$type&plan=$plan"), 'parent'));
			}
		}
		$planItem = $this->kevinplan->planGetByID($plan);

		if (!$planItem) {
			die(js::error("No Plan exist!"));
		}
		$this->projectFilter();
		$Filter				 = &$this->kevinplan->Filter;
		$Filter->plan		 = $plan; //当前
		$Filter->methodName	 = 'groupcreate';

		$this->app->loadClass('pager', $static						 = true);
		$pager						 = new pager($recTotal, $recPerPage, $pageID);
		$keywords					 = $this->session->kevinplan_projectKeywords;
		$name						 = isset($keywords['name']) ? $keywords['name'] : '';
		$deleted					 = isset($keywords['deleted']) ? $keywords['deleted'] : 0;
		$this->view->keywordsArray	 = $this->session->kevinplan_projectKeywords;
		if (!$this->view->keywordsArray) $this->view->keywordsArray	 = array();
		$userlist					 = $this->user->getPairs('noletter');
		$deptlist					 = $this->kevinplan->getdeptpairs();
		$planlist					 = $this->kevinplan->getplanlist(0);
		$this->view->chargerList	 = $this->kevinplan->getChargerList(0);
		$this->view->planItem		 = $planItem;
		$this->view->userlist		 = $userlist;
		$this->view->deptlist		 = $deptlist;
		$this->view->type			 = "all";

		//$ProjectArray				 = $this->kevinplan->projectGetList($pager, $name, $deleted, $planFilter, $charger, $plan);

		$ProjectArray				 = $this->kevinplan->projectGetByFilter($Filter, 1, $pager); //$planpriv = 1
		$this->view->ProjectArray	 = $ProjectArray;
		$this->kevinplan->privcharger($ProjectArray);
		$this->view->progrouplist	 = $this->kevinplan->progroupairs();
		$this->view->plan			 = $plan;
		$this->view->planlist		 = $planlist;
		$this->view->groupcreate	 = 1;
		$this->view->pager			 = $pager;
		//网页位置
		$this->view->title			 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->groupcreate;
		$this->view->position[]		 = $this->lang->kevinplan->projectlist;
		$this->view->position[]		 = $this->lang->kevinplan->groupcreate;

		//die(js::alert(var_dump($ProjectArray)));
		$this->display();
	}

	/**
	 * index.
	 *      
	 * @access public
	 * @return void
	 */
	public function index() {
		//网页位置
		$this->view->title		 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->index;
		$this->view->position[]	 = $this->lang->kevinplan->index;
		$this->display();
	}

	/**
	 * memberlist.
	 * 
	 * @param  enum    $type
	 * @param  int    $plan
	 * @param  int    $project
	 * @param  string    $member
	 * @access public
	 * @return void
	 */
	public function memberlist($type = 'my', $plan = 0, $project = 0, $member = '', $recPerPage = 30, $recTotal = 0, $pageID = 1) {
		if ($type != 'my' && !commonModel::hasPriv('kevinplan', 'browseDeptPlan') && !commonModel::hasPriv('kevinplan', 'browseAllPlan')) $type	 = 'my';
		$this->projectFilter(); //get form filter value
		$Filter	 = &$this->kevinplan->Filter;

		$Filter->planFilter	 = 0; //无源
		$Filter->type		 = $type;
		if ($plan) $Filter->plan		 = $plan; //当前计划
		if ($project) $Filter->project	 = $project; //当前计划
		if ($Filter->project) $Filter->plan		 = 0;

		if ($member == '0') $Filter->member	 = $this->app->user->account; //set current
		else if ($member) $Filter->member	 = $member;

		//die(json_encode($Filter));
		$this->app->loadClass('pager', $static					 = true);
		$page					 = new pager($recTotal, $recPerPage, $pageID);
		$projectItem			 = $this->kevinplan->projectGetByID($Filter->project);
		$this->kevinplan->privcharger($projectItem);
		$this->view->userlist	 = $this->user->getPairs('noletter');
		$itemArray				 = $this->kevinplan->memberGetList($Filter, 0, 1, $page);
		$deleteArray			 = $this->kevinplan->memberGetList($Filter, 1, 1, $page);
		$deptArray				 = $this->kevinplan->getdeptpairs(0, '');
		$this->view->deptArray	 = $deptArray;
		$this->view->deptinfo	 = $deptArray;

		$this->view->itemArray	 = $itemArray;
		$planpriv				 = 1;
		$this->view->planlist	 = $this->kevinplan->getplanlist($Filter->project, $planpriv);
		$this->view->projectlist = $this->kevinplan->getprolist($Filter->plan);
		$this->view->memberlist	 = $this->kevinplan->getMemberList($Filter->plan, $Filter->project);
		$this->kevinplan->privmember($itemArray);
		$this->view->projectItem = $projectItem;
		$this->view->page		 = $page;

		$this->view->planarrs	 = array();
		if ($projectItem) $this->view->planarrs	 = $this->kevinplan->getplanlist($projectItem->id, 0);

		//网页位置
		$this->view->title			 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->memberlist;
		$this->view->position[]		 = html::a($this->createLink('kevinplan', 'planlist'), $this->lang->kevinplan->plan);
		$this->view->position[]		 = $this->lang->kevinplan->memberlist;
		$this->view->member			 = $Filter->member;
		$this->view->memberlistdel	 = $deleteArray;
		$this->view->type			 = $type;
		$this->display();
	}

	/**
	 * Create a schedual.
	 * 
	 * @param  string|date 
	 * @param  string      
	 * @access public
	 * @return void
	 */
	public function membercreate($project, $cpID = 0) {
		$projectItem = $this->kevinplan->projectGetByID($project);
		$this->kevinplan->privcharger($projectItem);
		if ($this->kevinplan->isCharger[$project]) {
			if (!empty($_POST)) {
				$id = $this->kevinplan->membercreate($project);
				if (dao::isError()) die(js::error(dao::getError()));
				if (isonlybody()) die(js::reload('parent.parent'));
				die(js::locate($this->createLink('kevinplan', 'memberlist'), 'parent'));
			}
			$iteminfo	 = new stdClass();
			if (is_numeric($cpID) && $cpID > 0) $iteminfo	 = $this->kevinplan->memberGetByID($cpID);
			$iteminfo	 = $this->kevinplan->defaultShowOfPlan($iteminfo, 'membercreate');
			$userlist	 = $this->user->getPairs('');
			$deptlist	 = $this->kevinplan->getdeptpairs(0, 'Detail');

			$this->view->userlist		 = $userlist;
			$this->view->deptlist		 = $deptlist;
			$this->view->iteminfo		 = $iteminfo;
			$this->view->projectPairs	 = $this->kevinplan->projectNameGetPairs();
			$this->view->project		 = $project;

			$this->view->projectItem = $this->kevinplan->projectGetByID($project);
			$this->view->cpID		 = $cpID;
			//$this->view->memberlist	 = $this->kevinplan->getPlanMemberList();
			//网页位置
			$this->view->title		 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->membercreate;
			$this->view->position[]	 = $this->lang->kevinplan->membercreate;

			$this->display();
		} else die(js::alert('Sorry,you have no priviliage to create items in this project!') . js::locate('back'));
	}

	/**
	 * Edit a kevinplan.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function memberdelete($id, $confirm = "no") {
		if (!isonlybody()) die(js::error('Can not run in un-model view'));
		$item		 = $this->kevinplan->memberGetByID($id);
		$projectItem = $this->kevinplan->projectGetByID($item->project);
		if (!$item) die(js::error('Can not find item'));
		if (!$projectItem) die(js::error('Can not find project'));

		$this->kevinplan->privcharger($projectItem);
		if ($this->kevinplan->isCharger[$item->project]) {
			if (!$id || $id <= 0) {
				die(js::error("ID is wrong! =" . $id) . js::closeModal('parent.parent', ''));
			}
			if ($confirm != 'yes') {
				die(js::confirm($this->lang->kevinplan->confirmPlanDelete . " id = " . $id, inlink('memberdelete', "id=$id&confirm=yes")) . js::closeModal('parent.parent', ''));
			}

			($this->dao->update(TABLE_KEVIN_PLAN_MEMBER)->set('deleted')->eq('1')->where('id')->eq((int) $id)->andwhere('deleted')->eq(0)->exec())
				or ( $this->dao->update(TABLE_KEVIN_PLAN_MEMBER)->set('deleted')->eq('0')->where('id')->eq((int) $id)->andwhere('deleted')->eq(1)->exec());

			$this->kevinplan->updateprohoursByItem($projectItem);

			/* if ajax request, send result. */
			if ($this->server->ajax) {
				if (dao::isError()) {
					$response['result']	 = 'fail';
					$response['message'] = dao::getError();
				} else {
					$response['result']	 = 'success';
					$response['message'] = '';
					$this->action->create('kevinplan', $id, 'deleted');
				}
				$this->send($response);
			}
			die(js::reload('parent.parent'));
		} else die(js::alert('Sorry,you have no priviliage to delete or undelete items in this project!') . js::closeModal('parent.parent', ''));
	}

	/**
	 * View a kevinplan.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function memberview($id) {
		$userlist	 = $this->user->getPairs('noletter');
		$deptlist	 = $this->kevinplan->getdeptpairs(0, 'Detail');

		$this->view->userlist		 = $userlist;
		$this->view->deptlist		 = $deptlist;
		$this->view->projectPairs	 = $this->kevinplan->projectNameGetPairs();
		$memberItem					 = $this->kevinplan->memberGetByID($id);
		$projectItem				 = $this->kevinplan->projectGetByID($memberItem->project);
		$this->kevinplan->privcharger($projectItem);
		$this->kevinplan->privmember($memberItem);
		if (!$this->kevinplan->isCharger[$memberItem->project] && !$this->kevinplan->isMember[$id]) die(js::alert('Sorry,you have no priviliage to view this item in this project!'));

		$this->view->memberItem = $memberItem;

		$this->view->projectItem = $projectItem;
		//网页位置
		$this->view->title		 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->memberedit;
		$this->view->position[]	 = $this->lang->kevinplan->memberedit;
		$this->display();
	}

	/**
	 * Edit a kevinplan.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function memberedit($id) {
		$item		 = $this->kevinplan->memberGetByID($id);
		$projectItem = $this->kevinplan->projectGetByID($item->project);
		$this->kevinplan->privmember($item);
		$this->kevinplan->privcharger($projectItem);
		if ($this->kevinplan->isMember[$id] || $this->kevinplan->isCharger[$item->project]) {
			if (!empty($_POST)) {
				$changes = $this->kevinplan->memberupdate($id);
				if (dao::isError()) die(js::error(dao::getError()));

				if (isonlybody()) die(js::reload('parent.parent'));
				die(js::locate($this->createLink('kevinplan', 'memberview', "id=$id"), 'parent'));
			}
			$userlist	 = $this->user->getPairs();
			$deptlist	 = $this->kevinplan->getdeptpairs(0, 'Detail');

			$this->view->userlist	 = $userlist;
			$this->view->deptlist	 = $deptlist;
			$this->view->projectItem = $projectItem;
			$this->view->memberItem	 = $this->kevinplan->memberGetByID($id);
			//网页位置
			$this->view->title		 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->memberedit;
			$this->view->position[]	 = $this->lang->kevinplan->memberedit;
			$this->display();
		} else die(js::alert('Sorry,you have no priviliage to edit this item in this project!'));
	}

	/*
	 * 批量添加没有用户的项目的用户
	 */

	public function planbatchcreatemember($id, $confirm = 'no') {
		if (!isonlybody()) die(js::error('Can not run in un-model view'));
		$planItem = $this->kevinplan->planGetByID($id);
		if (!$planItem) {
			die(js::error(js::alert("Can not find Plan with id =" . $id)));
		}
		if ($confirm != 'yes') {
			die(js::confirm("Please confirm:plan batch create member id = $id , name = " . $planItem->name, inlink('planbatchcreatemember', "id=$id&confirm=yes")));
		}

		$count = $this->kevinplan->planbatchcreatemember($planItem);
		if (dao::isError()) die(js::error(dao::getError()));
		echo '<br>Finished planbatchcreatemember fun. change count =' . $count . "<br>";
		die();
	}

	/**
	 * Create a plan.
	 * 
	 * @param  string|date 
	 * @param  string      
	 * @access public
	 * @return void
	 */
	public function plancreate() {
		if (!empty($_POST)) {
			$plan = $this->kevinplan->plancreate();
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinplan', 'planlist'), 'parent'));
		}

		$userlist				 = $this->user->getPairs('');
		$deptlist				 = $this->kevinplan->getdeptpairs(0, 'Detail');
		;
		$this->view->userlist	 = $userlist;
		$this->view->deptlist	 = $deptlist;
		//网页位置
		$this->view->title		 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->plancreate;
		$this->view->position[]	 = $this->lang->kevinplan->plancreate;

		$this->display();
	}

	/**
	 * delete a kevinplan.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function plandelete($id, $confirm = "no") {
		$planItem = $this->kevinplan->planGetByID($id);
		$this->kevinplan->checkplanpriv($planItem);
		if (!$id || $id <= 0) {
			die(js::error("ID is wrong! =" . $id));
		}
		if ($confirm != 'yes') {
			die(js::confirm($this->lang->kevinplan->confirmDelete . "kevinplan plan id = " . $id, inlink('plandelete', "id=$id&confirm=yes")));
		}

		($this->dao->update(TABLE_KEVIN_PLAN_LIST)->set('deleted')->eq('1')->where('id')->eq((int) $id)->andwhere('deleted')->eq(0)->exec()) or ( $this->dao->update(TABLE_KEVIN_PLAN_LIST)->set('deleted')->eq('0')->where('id')->eq((int) $id)->andwhere('deleted')->eq(1)->exec());

		/* if ajax request, send result. */
		if ($this->server->ajax) {
			if (dao::isError()) {
				$response['result']	 = 'fail';
				$response['message'] = dao::getError();
			} else {
				$response['result']	 = 'success';
				$response['message'] = '';
				$this->action->create('kevinplan', $id, 'deleted');
			}
			$this->send($response);
		}
		if (isonlybody()) die(js::reload('parent.parent'));
		die(js::locate($this->createLink('kevinplan', 'planlist'), 'parent'));
	}

	/**
	 * Edit a kevinplan.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function planedit($id) {
		$planItem = $this->kevinplan->planGetByID($id);
		$this->kevinplan->checkplanpriv($planItem);
		if (!empty($_POST)) {
			$this->kevinplan->planupdate($id);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinplan', 'planlist'), 'parent'));
		}
		$userlist				 = $this->user->getPairs();
		$deptlist				 = $this->kevinplan->getdeptpairs(0, 'Detail');
		$this->view->userlist	 = $userlist;
		$this->view->deptlist	 = $deptlist;

		$this->view->planItem	 = $planItem;
		//网页位置
		$this->view->title		 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->planedit;
		$this->view->position[]	 = $this->lang->kevinplan->planedit;
		$this->display();
	}

	public function options() {
		//position
		$this->view->title		 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->options;
		$this->view->position[]	 = $this->lang->kevinplan->common;
		$this->view->position[]	 = $this->lang->kevinplan->options;
		$this->display();
	}

	/**
	 * Filter kevinplans.
	 * @access public
	 * @return void
	 */
	private function planFilter() {
		$Filter = new stdClass();
		if (!empty($_POST)) {
			$kevinformtype = $this->post->kevinformtype;
			if ($kevinformtype == "planFilter") {
				$name						 = $this->post->name;
				$deleted					 = $this->post->deleted;
				$keywordsArray['name']		 = $name;
				$keywordsArray['deleted']	 = (int) $deleted;
				$this->session->set('kevinplan_plan_filterKeywords', $keywordsArray); //set
			}
		}
		$keywords		 = $this->session->kevinplan_plan_filterKeywords;
		$Filter->name	 = isset($keywords['name']) ? $keywords['name'] : '';
		$Filter->deleted = isset($keywords['deleted']) ? $keywords['deleted'] : 0;
		return $Filter;
	}

	/**
	 * planlist.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function planlist($recTotal = 0, $recPerPage = 30, $pageID = 1) {
		$this->app->loadClass('pager', $static				 = true);
		$pager				 = new pager($recTotal, $recPerPage, $pageID);
		$this->view->Filter	 = $this->planFilter();
		$Filter				 = &$this->view->Filter;

		$userlist				 = $this->user->getPairs('noletter');
		$deptlist				 = $this->kevinplan->getdeptpairs();
		$this->view->userlist	 = $userlist;
		$this->view->deptlist	 = $deptlist;
		$this->view->PlanArray	 = $this->kevinplan->planGetList($pager, $Filter->name, $Filter->deleted);

		$this->view->pager		 = $pager;
		//网页位置
		$this->view->title		 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->planlist;
		//$this->view->position[] = html::a($this->createLink('kevinplan', 'index'), $this->lang->kevincom->index);
		$this->view->position[]	 = $this->lang->kevinplan->planlist;
		$this->display();
	}

	/**
	 * View a doc.
	 * 
	 * @param  int    $docID 
	 * @access public
	 * @return void
	 */
	public function planview($id) {
		$this->view->planItem = $this->kevinplan->planGetByID($id);
		$this->kevinplan->checkplanpriv($this->view->planItem);
		if (!$id) die(js::error($this->lang->notFound));

		$userlist	 = $this->user->getPairs('noletter');
		$deptlist	 = $this->kevinplan->getdeptpairs(0, 'Detail');

		$this->view->userlist	 = $userlist;
		$this->view->deptlist	 = $deptlist;

		$this->view->title		 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->planview;
		$this->view->position[]	 = html::a($this->createLink('kevinplan', 'memberlist'), $this->lang->kevinplan->memberlist);
		$this->view->position[]	 = $this->lang->kevinplan->planview;

		$this->view->actions	 = $this->loadModel('action')->getList('kevinplan', $id);
		$this->view->preAndNext	 = $this->loadModel('common')->getPreAndNextObject('kevinplan', $id);
		//$this->view->keTableCSS = $this->doc->extractKETableCSS($doc->content);

		$this->display();
	}

	/**
	 * projectcode.
	 * 
	 * @param  int    $code 
	 * @access public
	 * @return void
	 */
	public function projectcode($code) {
		if (!$code) die(js::error('please input the project code!'));
		$projectItem = $this->loadModel('project')->getById($code);
		if (!$projectItem) die(js::error('No this project code!'));

		$prolist					 = $this->dao->select("*")
			->from(TABLE_KEVIN_PLAN_PROJECT)->alias('a')
			->where('a.projectCode')->eq($code)
			->fetchAll();
		$userlist					 = $this->user->getPairs('noletter');
		$deptlist					 = $this->kevinplan->getdeptpairs(0, 'Detail');
		$this->view->userlist		 = $userlist;
		$this->view->deptlist		 = $deptlist;
		$this->view->project		 = $projectItem;
		$this->view->ProjectArray	 = $prolist;
		//网页位置
		$this->view->title			 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->projectCode;
		//$this->view->position[] = html::a($this->createLink('kevinplan', 'index'), $this->lang->kevincom->index);
		$this->view->position[]		 = $this->lang->kevinplan->projectCode;
		$this->display();
	}

	/**
	 * Create a project.
	 * 
	 * @param  string|date 
	 * @param  string      
	 * @access public
	 * @return void
	 */
	public function projectcreate($plan) {
		$plan		 = abs((int) $plan);
		$planItem	 = $this->kevinplan->planGetByID($plan);
		if (!$planItem) {
			die(js::error("Can not find Plan with id =" . $plan));
		}
//            $this->kevinplan->privcharger($plan);
//            if($this->kevinplan->isCharger){
//            }else die(js::alert('Sorry,you have no priviliage to create projects in this plan!'));
		if (!empty($_POST)) {
			$project = $this->kevinplan->projectcreate($planItem);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinplan', 'projectview', "id=$id"), 'parent'));
		}


		$this->view->planItem	 = $planItem;
		$this->view->userlist	 = $this->user->getPairs('');
		$this->view->deptlist	 = $this->kevinplan->getdeptpairs(0, 'Detail');

		//网页位置
		$this->view->title		 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->projectcreate;
		$this->view->position[]	 = $this->lang->kevinplan->projectcreate;

		$this->display();
	}

	/**
	 * delete a kevinplan.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function projectdelete($project, $plan = 0, $confirm = "no") {
		$project = (int) $project; //to int
		$plan	 = abs((int) $plan);

		$projectItem	 = $this->kevinplan->projectGetByID($project);
		$this->kevinplan->privcharger($projectItem);
		$this->kevinplan->hasPrivProjectOrDie($project);
		$var			 = "project=$project&plan=$plan ";
		if (!$projectItem) dao::$errors[][] = "Can not get projectplan id = $project";

		if (dao::isError()) die(js::error(dao::getError()));
		$type = ($plan) ? 'group' : 'project';

		if ($confirm != 'yes') {
			$msgout = $this->lang->kevinplan->confirmDelete . "kevinplan $type ,  " . $var;
			if ($plan) {  //for plan
				$msgout .= ". Remove project from group id= $plan where projectplan id = $project,";
			} else { //for project
				$msgout .= ". Direct delete/undelete project where projectplan id = $project";
			}
			die(js::confirm($msgout, inlink('projectdelete', "$var&confirm=yes")));
		}

		$this->kevinplan->projectdelete($projectItem, $plan);

		/* if ajax request, send result. */
		if ($this->server->ajax) {
			if (dao::isError()) {
				$response['result']	 = 'fail';
				$response['message'] = dao::getError();
			} else {
				$response['result']	 = 'success';
				$response['message'] = '';
				//$this->action->create('kevinplan', $id, 'deleted');
			}
			$this->send($response);
		} else {
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinplan', 'projectlist'), 'parent'));
		}
	}

	/**
	 * Edit a project.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function projectedit($id) {
		$id			 = (int) $id; //to int
		$projectItem = $this->kevinplan->projectGetByID($id);
		$this->kevinplan->privcharger($projectItem);
		$this->kevinplan->hasPrivProjectOrDie($id);
		if (!empty($_POST)) {

			$this->kevinplan->projectupdate($projectItem);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinplan', 'projectlist'), 'parent'));
		}

		$this->view->userlist	 = $this->user->getPairs();
		$this->view->deptlist	 = $this->kevinplan->getdeptpairs(0, 'Detail');

		$this->view->projectItem = $projectItem;
		$this->view->planlist	 = $this->kevinplan->getplanlistAll();
		$planarrs				 = $this->kevinplan->getplanlist($id, 0);
		unset($planarrs['']);
		$this->view->planarrs	 = array_keys($planarrs); //get index array
		//网页位置
		$this->view->title		 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->projectedit;
		$this->view->position[]	 = $this->lang->kevinplan->projectedit;
		$this->display();
	}

	/**
	 * projectlist.
	 *
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function projectexport($plan = 0) {
		$plan				 = (int) $plan;
		$this->projectFilter();
		$Filter				 = &$this->kevinplan->Filter;
		$Filter->planFilter	 = 0;
		$Filter->plan		 = $plan; //当前计划

		$pager					 = null;
		$this->view->planItem	 = $this->kevinplan->planGetByID($Filter->plan);
		$this->view->userlist	 = $this->user->getPairs('noletter');
		$this->view->deptlist	 = $this->kevinplan->getdeptpairs();

		$this->view->ProjectArray	 = $this->kevinplan->projectGetByFilter($Filter, 1, null, ' dept,pri,hoursPlan DESC'); //$planpriv = 1
		$this->view->plan			 = $Filter->plan;
		//网页位置
		$this->view->title			 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->projectlist;
		$this->view->position[]		 = $this->lang->kevinplan->projectlist;
		$this->display();
	}

	/**
	 * Filter kevinplans.
	 * @access public
	 * @return void
	 */
	private function projectFilter() {
		$SourceMethod = 'projectlist';
		if (!empty($_POST)) {
			$kevinformtype = $this->post->kevinformtype;
			if ($kevinformtype == "projectFilter") {
				$this->kevinplan->filterCheckin(); //checkin
			}
		}

		$this->kevinplan->filterCheckout();
	}

	/**
	 * View a doc.
	 * 
	 * @param  int    $docID 
	 * @access public
	 * @return void
	 */
	public function projectview($id) {
		$id						 = (int) $id; //to int
		$projectItem			 = $this->kevinplan->projectGetByID($id);
		$this->kevinplan->privcharger($projectItem);
		$this->kevinplan->hasPrivProjectOrDie($id);
		$userlist				 = $this->user->getPairs('noletter');
		$chargerList			 = $this->user->getPairs();
		$deptlist				 = $this->kevinplan->getdeptpairs(0, 'Detail');
		;
		$this->view->chargerList = $chargerList;
		$this->view->userlist	 = $userlist;
		$this->view->deptlist	 = $deptlist;
		$this->view->projectItem = $projectItem;
		$this->view->title		 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->projectview;
//		$this->view->position[]	 = html::a($this->createLink('kevinplan', 'memberlist'), $this->lang->kevinplan->memberlist);
		$this->view->position[]	 = $this->lang->kevinplan->planview;
		$this->view->planarrs	 = $this->kevinplan->getplanlist($id, 0);
		$this->view->actions	 = $this->loadModel('action')->getList('kevinplan', $id);
		$this->view->preAndNext	 = $this->loadModel('common')->getPreAndNextObject('kevinplan', $id);
		//$this->view->keTableCSS = $this->doc->extractKETableCSS($doc->content);


		$this->display();
	}

	/**
	 * projectlist.
	 *
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function projectlist($type = 'my', $plan = 0, $charger = '', $recTotal = 0, $recPerPage = 30, $pageID = 1) {
		$plan = (int) $plan;
		if (!empty($_POST)) {
			$kevinformtype = $this->post->kevinformtype;
			if ($this->post->kevinformtype == "projectlist") {
				$count = $this->kevinplan->updateprohours();
				if (dao::isError()) die(js::error(dao::getError()));
				if (isonlybody()) die(js::reload('parent.parent'));
				die(js::locate(helper::createLink('kevinplan', 'projectlist', "type=$type&plan=$plan&charger=$charger"), 'parent'));
			}
		}

		$browseAllPlan	 = (common::hasPriv('kevinplan', 'browseAllPlan')) || (common::hasPriv('kevinplan', 'browseDeptPlan'));
		$this->projectFilter();
		$Filter			 = &$this->kevinplan->Filter;

		$Filter->planFilter	 = 0; //无源
		$Filter->methodName	 = 'projectlist';

		if ($charger) $Filter->charger		 = $charger;
		if ($plan) $Filter->plan			 = $plan; //当前计划
		$this->view->planItem	 = $this->kevinplan->planGetByID($Filter->plan);

		if ($this->view->planItem) {
			$this->kevinplan->checkplanpriv($this->view->planItem);
		}

		$Filter->type = $type;
		if ($type == 'my' || !$browseAllPlan) {
			$Filter->charger = $this->app->user->account;
		}

		$this->app->loadClass('pager', $static		 = true);
		$pager		 = new pager($recTotal, $recPerPage, $pageID);
		$keywords	 = $this->session->kevinplan_projectKeywords;
		$name		 = isset($keywords['name']) ? $keywords['name'] : '';
		$deleted	 = isset($keywords['deleted']) ? $keywords['deleted'] : 0;

		$this->view->planlist = $this->kevinplan->getplanlist(0);


		$this->view->chargerList	 = $this->kevinplan->getChargerList($Filter->plan);
		$this->view->charger		 = $charger;
		$this->view->userlist		 = $this->user->getPairs('noletter');
		$this->view->deptlist		 = $this->kevinplan->getdeptpairs();
		$this->view->progrouplist	 = $this->kevinplan->progroupairs();

		$ProjectArray				 = $this->kevinplan->projectGetByFilter($Filter, 1, $pager); //$planpriv = 1
		$this->view->ProjectArray	 = $ProjectArray;
		$this->kevinplan->privcharger($ProjectArray);
		$this->view->groupcreate	 = 0;
		$this->view->plan			 = $Filter->plan;
		$this->view->pager			 = $pager;
		$this->view->type			 = $type;
		//网页位置
		$this->view->title			 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->projectlist;
		$this->view->position[]		 = html::a($this->createLink('kevinplan', 'memberlist'), $this->lang->kevinplan->common);
		$this->view->position[]		 = $this->lang->kevinplan->projectlist;
		$this->display();
	}

	/**
	 * Statistic kevinplans. 
	 * @param  string    $date 
	 * @param  string    $type 
	 * @access public
	 * @return void
	 */
	public function statistic($type = 'member') {

		$this->projectFilter();
		$Filter				 = &$this->kevinplan->Filter;
		$Filter->planFilter	 = 0;

		$this->view->planlist	 = $this->kevinplan->getplanlist(0);
		$this->view->planItem	 = $this->kevinplan->planGetByID($Filter->plan);

		//绘制图表
		if ('member' == $type) {
			$this->view->userlist	 = $this->user->getPairs('noletter');
			$this->view->items		 = $this->kevinplan->statisticByMember();
		} else if ('project' == $type) {
			$this->view->items = $this->kevinplan->statisticByProject();
		} else if ('plan' == $type) {
			$this->view->items = $this->kevinplan->statisticByPlan();
		} else if ('group' == $type) {
			$this->view->items = $this->kevinplan->statisticByGroup();
//					$this->view->plan=$this->kevinplan->planNameGetPairs();
		} else {
			$this->view->ErrorMsg = 'input type is wrong. no suche type of "' . $type . '"';
		}

		//网页位置
		$this->view->title			 = $this->lang->kevinplan->common . $this->lang->colon . $this->lang->kevinplan->statistic;
		$this->view->position[]		 = $this->lang->kevinplan->statistic;
		//页面参数
		$this->view->statisticType	 = $type;
		$this->display();
	}

	public function updatecosthours($plan,$proid,$project =0){
		$this->app->loadClass('date');
		extract(kevin::getBeginEndTime('thisYear'));
		$costhours = $this->dao->select('account,sum(minutes)/60 as SumOfHours')
			->from(TABLE_TODO)
			->where('project')->eq($project)
			->andWhere('status')->eq('done')
			->andWhere("date >= '$begin'")
			->andWhere("date <= '$end'")
			->andWhere("(hourstype = 'ove' or hourstype = 'nor')")
			->groupBy('account')
			->orderBy('SumOfHours desc')->fetchPairs();
		$this->kevinplan->filterCheckout();
		$Filter = &$this->kevinplan->Filter;

		$Filter->planFilter	 = 0; //无源
		if ($plan) $Filter->plan		 = $plan; //当前计划
		if ($project) $Filter->project	 = $proid; //当前计划

		$itemArray = $this->kevinplan->memberGetList($Filter, 0, 1, null);
		$sum=0;
		foreach ($itemArray as $item) {
			if(isset($costhours[$item->member])){
				$this->dao->update(TABLE_KEVIN_PLAN_MEMBER)->set('hoursCost')->eq($costhours[$item->member])->where('id')->eq($item->id)->exec();
				$sum+=$costhours[$item->member];
			}
		}
		$this->dao->update(TABLE_KEVIN_PLAN_PROJECT)->set('hoursCost')->eq($sum)->where('id')->eq($proid)->exec();
		die(js::reload('parent.parent'));
	}

	public function updateplanhours($id, $confirm = 'no') {
		if (!isonlybody()) die(js::error('Can not run in un-model view'));
		if ($confirm != 'yes') {
			die(js::confirm($this->lang->kevinplan->updateplanhours . " id = " . $id, inlink('updateplanhours', "id=$id&confirm=yes")));
		}

		$this->kevinplan->updateplanhours($id);
		if (dao::isError()) die(js::error(dao::getError()));
		die(js::reload('parent.parent'));
	}

	public function updateprohours($id) {
		$id			 = (int) $id; //to int
		$projectItem = $this->kevinplan->projectGetByID($id);
		$this->kevinplan->privcharger($projectItem);
		$this->kevinplan->hasPrivProjectOrDie($id);
		$this->kevinplan->updateprohours($id);
		if (dao::isError()) die(js::error(dao::getError()));
		kevin::dieIfDaoError();
		if (isonlybody()) die(js::reload('parent.parent'));
		die(js::locate(helper::createLink('kevinplan', 'memberlist', "type=&plan=&project=$id"), 'parent'));
	}

}
