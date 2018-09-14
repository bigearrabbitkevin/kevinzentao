<?php

/**
 * The control file of dept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: control.php 4157 2013-01-20 07:09:42Z wwccss $
 * @link        http://www.zentao.net
 */
class kevinuser extends control {

	/**
	 * Construct function, set menu. 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct($moduleName = '', $methodName = '') {
		parent::__construct($moduleName, $methodName);
		$this->loadModel('company')->setMenu();
		$this->kevinuser->setMenu();
	}
	
	
	/**
	 * Browse departments and users of a company.
	 *
	 * @param  int    $param
	 * @param  string $type
	 * @param  string $orderBy
	 * @param  int    $recTotal
	 * @param  int    $recPerPage
	 * @param  int    $pageID
	 * @access public
	 * @return void
	 */
	public function browse($param = 0, $type = 'bydept', $orderBy = 'locked,id', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		$this->loadModel('search');
		$this->loadModel('dept');
		
		$deptID = $type == 'bydept' ? (int) $param : 0;
		
		/* Save session. */
		$this->session->set('userList', $this->app->getURI(true));
		
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);
		
		/* Append id for secend sort. */
		$sort = $this->loadModel('common')->appendOrder($orderBy);
		
		/* Build the search form. */
		$queryID															 = $type == 'bydept' ? 0 : (int) $param;
		$this->config->company->browse->search['actionURL']					 = $this->createLink('kevinuser', 'browse', "param=myQueryID&type=bysearch");
		$this->config->company->browse->search['queryID']					 = $queryID;
		$this->config->company->browse->search['params']['dept']['values']	 = array('' => '') + $this->dept->getOptionMenu();
		
		if ($type == 'bydept') {
			$childDeptIds	 = $this->dept->getAllChildID($deptID);
			$users			 = $this->dept->getUsers($childDeptIds, $pager, $sort);
		} else {
			if ($queryID) {
				$query = $this->search->getQuery($queryID);
				if ($query) {
					$this->session->set('userQuery', $query->sql);
					$this->session->set('userForm', $query->form);
				} else {
					$this->session->set('userQuery', ' 1 = 1');
				}
			}
			$users = $this->loadModel('user')->getByQuery($this->session->userQuery, $pager, $sort);
		}
		$this->view->title		= $this->lang->kevinuser->common . $this->lang->colon . $this->lang->dept->common;
		
		$this->view->position[]	 = $this->lang->dept->common;
		$this->view->users		 = $users;
		$this->view->searchForm	 = $this->fetch('search', 'buildForm', $this->config->company->browse->search);
		$this->view->deptTree	 = $this->dept->getTreeMenu($rooteDeptID = 0, array('kevinuserModel', 'createMemberLinkOfBrowse'));
		$this->view->parentDepts = $this->dept->getParents($deptID);
		$this->view->depts		 = $this->kevinuser->getDeptArray();
		$this->view->orderBy	 = $orderBy;
		$this->view->deptID		 = $deptID;
		$this->view->pager		 = $pager;
		$this->view->param		 = $param;
		$this->view->type		 = $type;
		
		$this->display();
	}
	
	/**
	 * Batch create users.
	 *
	 * @param  int    $deptID
	 * @access public
	 * @return void
	 */
	public function batchCreate($deptID = 0)
	{
		$this->loadModel('dept');
		$groups    = $this->dao->select('id, name, role')->from(TABLE_GROUP)->fetchAll();
		$groupList = array('' => '');
		$roleGroup = array();
		foreach($groups as $group)
		{
			$groupList[$group->id] = $group->name;
			if($group->role) $roleGroup[$group->role] = $group->id;
		}

		if(!empty($_POST))
		{
			$this->kevinuser->batchCreate();
			die(js::locate($this->createLink('kevinuser', 'browse'), 'parent'));
		}
		
		/* Set custom. */
		foreach(explode(',', $this->config->kevinuser->customBatchCreateFields) as $field) $customFields[$field] = $this->lang->kevinuser->$field;
		$this->view->customFields = $customFields;
		$this->view->showFields   = $this->config->kevinuser->custom->batchCreateFields;
		
		$title      = $this->lang->kevinuser->common . $this->lang->colon . $this->lang->kevinuser->batchCreate;
		$position[] = $this->lang->kevinuser->batchCreate;
		$this->view->title     = $title;
		$this->view->position  = $position;
		$this->view->depts     = $this->dept->getOptionMenu();
		$this->view->deptID    = $deptID;
		$this->view->groupList = $groupList;
		$this->view->roleGroup = $roleGroup;
		
		$this->display();
	}
	
	/**
	 * Create a suer.
	 *
	 * @param  int    $deptID
	 * @access public
	 * @return void
	 */
	public function create($deptID = 0)
	{
		$this->loadModel('dept');
//		$this->lang->set('menugroup.user', 'company');
//		$this->lang->user->menu      = $this->lang->company->menu;
//		$this->lang->user->menuOrder = $this->lang->company->menuOrder;
//
		if(!empty($_POST))
		{
			$this->kevinuser->create();
			if(dao::isError()) die(js::error(dao::getError()));
			die(js::locate($this->createLink('kevinuser', 'browse'), 'parent'));
		}
		$groups    = $this->dao->select('id, name, role')->from(TABLE_GROUP)->fetchAll();
		$groupList = array('' => '');
		$roleGroup = array();
		foreach($groups as $group)
		{
			$groupList[$group->id] = $group->name;
			if($group->role) $roleGroup[$group->role] = $group->id;
		}
		
		$title      = $this->lang->kevinuser->common . $this->lang->colon . $this->lang->kevinuser->create;
		$position[] = $this->lang->kevinuser->create;
		$this->view->title     = $title;
		$this->view->position  = $position;
		$this->view->depts     = $this->dept->getOptionMenu();
		$this->view->groupList = $groupList;
		$this->view->roleGroup = $roleGroup;
		$this->view->deptID    = $deptID;
		
		$this->display();
	}
	
	/**
	 * Batch delete class.
	 *
	 * @access public
	 * @return void
	 */
	public function classbatchdelete($confirm = 'no') {
		if ($confirm == 'no') {
			$this->session->set('classIDList', $this->post->classIDList);
			die(js::confirm($this->lang->kevinuser->confirmDelete, inlink('classbatchdelete', "confirm=yes")));
		} else {
			$data = $this->dao->select('deleted')->from(TABLE_KEVIN_USER_CLASS)->where('id')->eq($this->session->classIDList[0])->fetch();
			if($data->deleted) {
				$this->dao->update(TABLE_KEVIN_USER_CLASS)->set('deleted')->eq(0)->where('id')->in($this->session->classIDList)->exec();
				die(js::alert($this->lang->kevinuser->successUnDelete) . js::locate($this->createLink('kevinuser', 'classlist'), 'parent'));
			} else {
				$this->dao->update(TABLE_KEVIN_USER_CLASS)->set('deleted')->eq(1)->where('id')->in($this->session->classIDList)->exec();
				die(js::alert($this->lang->kevinuser->successDelete) . js::locate($this->createLink('kevinuser', 'classlist'), 'parent'));
			}
		}
	}

	/**
	 * Batch edit class.
	 *
	 * @access public
	 * @return void
	 */
	public function classbatchedit() {
		if ($this->post->role) {
			$allChanges = $this->kevinuser->classBatchUpdate();
			if (!empty($allChanges)) {
				foreach ($allChanges as $classID => $changes) {
					if (empty($changes)) continue;
					$actionID = $this->loadModel('action')->create('kevinuserclass', $classID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			die(js::alert($this->lang->kevinuser->successBatchEdit) . js::locate($this->createLink('kevinuser', 'classlist'), 'parent'));
		}

		$classIDList = $this->post->classIDList ? $this->post->classIDList : die(js::locate($this->createLink('kevinuser', 'classlist'), 'parent'));
		if (count($classIDList) > $this->config->kevinuser->batchEditNum) {
			die(js::alert($this->lang->kevinuser->batchEditMsg) . js::locate($this->createLink('kevinuser', 'classlist'), 'parent'));
		}
		$classes	 = $this->dao->select('*')->from(TABLE_KEVIN_USER_CLASS)->where('id')->in($classIDList)->fetchAll('id');

		$this->view->showFields		 = $this->config->kevinuser->classBatchEditFields;
		$this->view->roleList		 = array(0 => '') + $this->kevinuser->getRoleList();
		$this->view->classify1List	 = array(0 => '') + $this->kevinuser->getClassify1List();
		$this->view->classify2List	 = array(0 => '') + $this->kevinuser->getClassify2List();
		$this->view->classify3List	 = array(0 => '') + $this->kevinuser->getClassify3List();
		$this->view->title			 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->classBatchEdit;
		$this->view->position[]		 = $this->lang->kevinuser->classBatchEdit;
		$this->view->classIDList	 = $classIDList;
		$this->view->classes		 = $classes;

		$this->display();
	}

	/**
	 * Create class.
	 *
	 * @access public
	 * @return void
	 */
	public function classcreate($id = '') {
		if (!empty($_POST)) {
			$classID = $this->kevinuser->classCreate();
			if (dao::isError()) die(js::error(dao::getError()));
			$this->loadModel('action')->create('kevinuserclass', $classID, 'Created');
			die(js::alert($this->lang->kevinuser->successCreate) . js::locate($this->createLink('kevinuser', 'classlist'), 'parent.parent'));
		}
		
		$this->view->class			 = !empty($id)?$this->kevinuser->getClass($id):array();
		
		$this->view->roleList		 = $this->kevinuser->getRoleList();
		$this->view->classify1List	 = $this->kevinuser->getClassify1List();
		$this->view->classify2List	 = $this->kevinuser->getClassify2List();
		$this->view->classify3List	 = $this->kevinuser->getClassify3List();
		$this->view->title			 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->classcreate;
		$this->view->position[]		 = $this->lang->kevinuser->manage;
		$this->view->func			 = "create";

		$this->display('kevinuser', 'classedit');
	}

	/**
	 * Delete a class.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function classdelete($id, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevinuser->confirmDelete, inlink('classdelete', "id=$id&confirm=yes")));
		} else {
			$data = $this->dao->select('deleted')->from(TABLE_KEVIN_USER_CLASS)->where('id')->eq($id)->fetch();
			if ($data->deleted) {
				$this->dao->update(TABLE_KEVIN_USER_CLASS)->set('deleted')->eq(0)->where('id')->eq($id)->exec();
				die(js::alert($this->lang->kevinuser->successUnDelete) . js::locate($this->createLink('kevinuser', 'classlist'), 'parent.parent'));
			} else {
				$this->dao->update(TABLE_KEVIN_USER_CLASS)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
				die(js::alert($this->lang->kevinuser->successDelete) . js::locate($this->createLink('kevinuser', 'classlist'), 'parent.parent'));
			}
		}
	}

	/**
	 * Update the class.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function classedit($id) {
		if (!empty($_POST)) {
			$allChanges = $this->kevinuser->classUpdate($id);
			if (!empty($allChanges)) {
				foreach ($allChanges as $classID => $changes) {
					if (empty($changes)) continue;

					$actionID = $this->loadModel('action')->create('kevinuserclass', $classID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			if (dao::isError()) die(js::error(dao::getError()));
			die(js::alert($this->lang->kevinuser->successSave) . js::locate($this->createLink('kevinuser', 'classlist'), 'parent.parent'));
		}
		$this->view->roleList		 = $this->kevinuser->getRoleList();
		$this->view->classify1List	 = $this->kevinuser->getClassify1List();
		$this->view->classify2List	 = $this->kevinuser->getClassify2List();
		$this->view->classify3List	 = $this->kevinuser->getClassify3List();

		$class						 = $this->kevinuser->getClass($id);
		$this->view->title			 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->classedit;
		$this->view->position[]		 = $this->lang->kevinuser->manage;
		$this->view->actions		 = $this->loadModel('action')->getList('kevinuserclass', $id);
		$this->view->class			 = $class;
		$this->view->func			 = "edit";
		$this->display();
	}

	/**
	 * Class list.
	 * 
	 * @param  int    $recTotal,$recPerPage,$pageID
	 * @access public
	 * @return void
	 */
	public function classlist($orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);
		$filter	 = [];
		
		$role			 = $filter['role']	 = '';
		$classify1				 = $filter['classify1']		 = '';
		$classify2				 = $filter['classify2']		 = '';
		$classify3				 = $filter['classify3']		 = '';
		$deleted				 = $filter['deleted']		 = '';
		if (!empty($_POST)) {
			$pager					 = pager::init(0, 20, 1);
			if(isset($_POST['role'])) $this->session->set('role', $_POST['role']);
			if(isset($_POST['classify1'])) $this->session->set('classify1', $_POST['classify1']);
			if(isset($_POST['classify2'])) $this->session->set('classify2', $_POST['classify2']);
			if(isset($_POST['classify3'])) $this->session->set('classify3', $_POST['classify3']);
			if(isset($_POST['deleted'])) {
				$this->session->set('classdeleted', $_POST['deleted']);
			}else{
				$this->session->set('classdeleted', '');
			}
		}
		
		if(empty($orderBy)) {
			if($this->session->classOrderBy){
				$orderBy = $this->session->classOrderBy;
			}else{
				$orderBy = 'id_desc';
			}
		}else{
			$this->session->set('classOrderBy', $orderBy);
		}
		
		if($this->session->role) {
			$role = $filter['role'] = $this->session->role;
		}
		
		if($this->session->classify1) {
			$classify1 = $filter['classify1'] = $this->session->classify1;
		}	
		
		if($this->session->classify2) {
			$classify2 = $filter['classify2'] = $this->session->classify2;
		}	
		
		if($this->session->classify3) {
			$classify3 = $filter['classify3'] = $this->session->classify3;
		}
		
		if($this->session->classdeleted) {
			$deleted = $filter['deleted'] = $this->session->classdeleted;
		}
		
		$this->view->role = $role;
		$this->view->classify1		 = $classify1;
		$this->view->classify2		 = $classify2;
		$this->view->classify3		 = $classify3;
		$this->view->roleList		 = array(0 => '') + $this->kevinuser->getRoleList();
		$this->view->classify1List	 = array(0 => '') + $this->kevinuser->getClassify1List();
		$this->view->classify2List	 = array(0 => '') + $this->kevinuser->getClassify2List();
		$this->view->classify3List	 = array(0 => '') + $this->kevinuser->getClassify3List();
		$classList					 = $this->kevinuser->getClassList($orderBy, $pager, $filter);
		$this->view->title			 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->classlist;
		$this->view->position[]		 = $this->lang->kevinuser->manage;
		$this->view->classList		 = $classList;
		$this->view->orderBy		 = $orderBy;
		$this->view->deleted		 = isset($deleted)?$deleted:'';
		$this->view->pager			 = $pager;
		$this->view->recTotal		 = $recTotal;
		$this->view->recPerPage		 = $recPerPage;
		$this->view->pageID		 = $pageID;
		$this->display();
	}

	/**
	 *  View class.
	 * 
	 * @param  int $id
	 * @access public
	 * @return void
	 */
	public function classview($id) {
		$class					 = $this->kevinuser->getClass($id);
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->classview;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->actions	 = $this->loadModel('action')->getList('kevinuserclass', $id);
		$this->view->class		 = $class;
		$this->display();
	}
	

	/**
	 * Batch delete dept.
	 *
	 * @param  String $confirm
	 * @access public
	 * @return void
	 */
	public function deptbatchdelete($confirm = 'no') {
		if ($confirm == 'no') {
			$this->session->set('deptIDList', $this->post->deptIDList);
			die(js::confirm($this->lang->kevinuser->confirmDelete, inlink('deptbatchdelete', "confirm=yes")));
		} else {
			$data = $this->dao->select('deleted')->from(TABLE_DEPT)->where('id')->eq($this->session->deptIDList[0])->fetch();
			if($data->deleted) {
				$this->dao->update(TABLE_DEPT)->set('deleted')->eq(0)->where('id')->in($this->session->deptIDList)->exec();
				die(js::alert($this->lang->kevinuser->successUnDelete) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent'));
			} else {
				$this->dao->update(TABLE_DEPT)->set('deleted')->eq(1)->where('id')->in($this->session->deptIDList)->exec();
				die(js::alert($this->lang->kevinuser->successDelete) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent'));
			}
		}
	}

	/**
	 * Batch edit dept.
	 *
	 * @access public
	 * @return void
	 */
	public function deptbatchedit() {
		$deptModel = $this->loadModel('dept');
		if ($this->post->parent) {
			$allChanges = $this->kevinuser->deptBatchUpdate();
			if (!empty($allChanges)) {
				foreach ($allChanges as $deptID => $changes) {
					if (empty($changes)) continue;

					$actionID = $this->loadModel('action')->create('kevinuserdept', $deptID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			die(js::alert($this->lang->kevinuser->successBatchEdit) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent'));
		}

		$users = $this->loadModel('user')->getPairs('noletter|noclosed');

		$deptIDList	 = $this->post->deptIDList ? $this->post->deptIDList : die(js::locate($this->createLink('kevinuser', 'deptlist'), 'parent'));
		if (count($deptIDList) > $this->config->kevinuser->batchEditNum) {
			die(js::alert($this->lang->kevinuser->batchEditMsg) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent'));
		}
		$depts		 = $this->dao->select('*')->from(TABLE_DEPT)->where('id')->in($deptIDList)->fetchAll('id');

		$this->view->showFields		 = $this->config->kevinuser->deptBatchEditFields;
		$this->view->optionMenu		 = $deptModel->getOptionMenu();
		$this->view->users			 = $users;
		$this->view->groups		 = $this->loadModel('group')->getPairs();
		$this->view->title			 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->deptBatchEdit;
		$this->view->position[]		 = $this->lang->kevinuser->deptBatchEdit;
		$this->view->deptIDList		 = $deptIDList;
		$this->view->depts			 = $depts;

		$this->display();
	}

	/**
	 * create dept. 
	 * 
	 * @access public
	 * @return void
	 */
	public function deptcreate($id = '') {
		$deptModel = $this->loadModel('dept');
		if (!empty($_POST)) {
			$deptID = $this->kevinuser->deptCreate();
			if (dao::isError()) die(js::error(dao::getError()));
			$this->loadModel('action')->create('kevinuserdept', $deptID, 'Created');
			die(js::alert($this->lang->kevinuser->successCreate) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent.parent'));
		}
		
		if(!empty($id))	$this->view->dept		 = $deptModel->getById($id);
		$users					 = $this->loadModel('user')->getPairs('noletter|noclosed');
		$this->view->optionMenu	 = $deptModel->getOptionMenu();

		$this->view->users		 = $users;
		$this->view->groups		 = $this->loadModel('group')->getPairs();
		$this->view->func		 = 'create';
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->deptcreate;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->display('kevinuser', 'deptedit');
	}

	/**
	 * Delete a dept.
	 * 
	 * @param  int    $id 
	 * @param  string    $confirm 
	 * @access public
	 * @return void
	 */
	public function deptdelete($id, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevinuser->confirmDelete, inlink('deptdelete', "id=$id&confirm=yes")));
		} else {
			$data = $this->dao->select('deleted')->from(TABLE_DEPT)->where('id')->eq($id)->fetch();
			if ($data->deleted) {
				$this->dao->update(TABLE_DEPT)->set('deleted')->eq(0)->where('id')->eq($id)->exec();
				die(js::alert($this->lang->kevinuser->successUnDelete) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent.parent'));
			} else {
				$this->dao->update(TABLE_DEPT)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
				die(js::alert($this->lang->kevinuser->successDelete) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent.parent'));
			}
		}
	}

	/**
	 * Edit dept. 
	 * 
	 * @param  int    $deptID 
	 * @access public
	 * @return void
	 */
	public function deptedit($deptID) {
		$deptModel = $this->loadModel('dept');
		if (!empty($_POST)) {
			$allChanges = $this->kevinuser->deptUpdate($deptID);
			if (!empty($allChanges)) {
				foreach ($allChanges as $classID => $changes) {
					if (empty($changes)) continue;
					$actionID = $this->loadModel('action')->create('kevinuserdept', $deptID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			if (dao::isError()) die(js::error(dao::getError()));
			die(js::alert($this->lang->kevinuser->successSave) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent.parent'));
		}
		$dept					 = $deptModel->getById($deptID);
		$users					 = $this->loadModel('user')->getPairs('noletter|noclosed');
		$this->view->optionMenu	 = $deptModel->getOptionMenu();
		$this->view->dept		 = $dept;
		$this->view->users		 = $users;
		$this->view->groups		 = $this->loadModel('group')->getPairs();
		$this->view->func		 = 'edit';
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->deptedit;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->actions	 = $this->loadModel('action')->getList('kevinuserdept', $deptID);
		$this->display('kevinuser', 'deptedit');
	}

	/**
	 * dept list.
	 * 
	 * @param  int    $recTotal,$recPerPage,$pageID
	 * @access public
	 * @return void
	 */
	public function deptlist($path='', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);
		$filter	 = [];
		
		$manager			 = $filter['manager']	 = '';
		$group				 = $filter['group']		 = '';
		$deleted				 = $filter['deleted']		 = '';
		
		if($path)
			$this->session->set('deptpath', $path);
		if (!empty($_POST)) {
			if(isset($_POST['manager'])) $this->session->set('manager', $_POST['manager']);
			if(isset($_POST['group'])){
				$this->session->set('group', $_POST['group']);
			}else{
				$this->session->set('group', '');
			}
			if(isset($_POST['deleted'])) {
				$this->session->set('deptdeleted', $_POST['deleted']);
			}else{
				$this->session->set('deptdeleted', '');
			}
			if(isset($_POST['path'])) $this->session->set('deptpath', $_POST['path']);
			die(js::locate($this->createLink('kevinuser', 'deptlist'), 'parent'));
		}
					
		if(empty($orderBy)) {
			if($this->session->deptOrderBy){
				$orderBy = $this->session->deptOrderBy;
			}else{
				$orderBy = 'id_asc';
			}
		}else{
			$this->session->set('deptOrderBy', $orderBy);
		}
		
		if($this->session->deptpath) {
			$path = $filter['path'] = $this->session->deptpath;
		}else{
			$path = $filter['path'] = '';
		}
		
		if($this->session->manager) {
			$manager = $filter['manager'] = $this->session->manager;
		}
		
		if($this->session->group) {
			$group = $filter['group'] = $this->session->group;
		}	
		
		if($this->session->deptdeleted) {
			$deleted = $filter['deleted'] = $this->session->deptdeleted;
		}
		
		$this->view->manager = $manager;
		$this->view->group		 = $group;
		
		$this->view->optionMenu	 = $this->loadModel('dept')->getOptionMenu();
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->deptlist;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$deptList				 = $this->kevinuser->getDeptList($orderBy, $pager, $filter);
		$dept = $this->kevinuser->getDept($path);
		$groups = $this->loadModel('group')->getPairs();
		if ($dept) {
			$this->session->set('deptName', $dept->name);
			$this->session->set('deptParent', !empty($dept->parentName) ? $dept->parentName : $this->lang->kevinuser->topParent);
			$groupitem = '';
			if (!empty($dept->group)) {
				$groupArray = explode(',', trim($dept->group, ','));
				foreach ($groupArray as $item)
					if(isset ($groups[$item]))
						$groupitem .= $groups[$item] . ',';
			}
			$this->session->set('deptGroup', $groupitem);
		}
		$this->view->deptName = $this->session->deptName;
		$this->view->deptParent = $this->session->deptParent;
		$this->view->deptGroup = $this->session->deptGroup;
		$this->view->deptList	 = $deptList;
		$this->view->classpairs	 = $this->kevinuser->getAllClassPairs();
		$this->view->path		 = $path;
		$this->view->grade		 = isset($grade) ? $grade : '';
		$this->view->manager	 = isset($manager) ? $manager : '';
		$this->view->group		 = isset($group) ? $group : '';
		$this->view->deleted	 = isset($deleted) ? $deleted : '';
		$this->view->pager		 = $pager;
		$this->view->orderBy	 = $orderBy;
		$this->view->groups		 = $groups;
		$this->view->recTotal	 = $recTotal;
		$this->view->recPerPage	 = $recPerPage;
		$this->view->pageID		 = $pageID;
		$this->display();
	}

	/**
	 *  View dept.
	 * 
	 * @param  int $id
	 * @access public
	 * @return void
	 */
	public function deptview($id) {
		$dept					 = $this->kevinuser->getDept($id);
		$this->view->optionMenu	 = $this->loadModel('dept')->getOptionMenu();
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->deptview;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->actions	 = $this->loadModel('action')->getList('kevinuserdept', $id);
		$this->view->accounts	 = $this->loadModel('user')->getPairs();
		$this->view->groups		 = $this->loadModel('group')->getPairs();
		$this->view->dept		 = $dept;
		$this->display();
	}
	
	/**
	 * Edit a user.
	 *
	 * @param  string|int $userID   the int user id or account
	 * @access public
	 * @return void
	 */
	public function edit($userID)
	{
		
		$this->loadModel('dept');
//		$this->lang->set('menugroup.user', 'company');
//		$this->lang->user->menu      = $this->lang->company->menu;
//		$this->lang->user->menuOrder = $this->lang->company->menuOrder;
		if(!empty($_POST))
		{
			$this->kevinuser->update($userID);
			if(dao::isError()) die(js::error(dao::getError()));
			die(js::locate($this->session->userList ? $this->session->userList : $this->createLink('kevinuser', 'browse'), 'parent'));
		}
		
		$user       = $this->kevinuser->getById($userID, 'id');
		$userGroups = $this->loadModel('group')->getByAccount($user->account);
		
		$title      = $this->lang->kevinuser->common . $this->lang->colon . $this->lang->kevinuser->edit;
		$position[] = $this->lang->kevinuser->edit;
		$this->view->title      = $title;
		$this->view->position   = $position;
		$this->view->user       = $user;
		$this->view->depts      = $this->dept->getOptionMenu();
		$this->view->userGroups = implode(',', array_keys($userGroups));
		$this->view->groups     = $this->loadModel('group')->getPairs();
		
		$this->display();
	}
	
	/**
	 * Manage contacts.
	 *
	 * @param  int    $listID
	 * @access public
	 * @return void
	 */
	public function manageContacts($listID = 0) {
		$this->loadModel('user');
		$lists = $this->user->getContactLists($this->app->user->account);
		
		/* If set $mode, need to update database. */
		if ($this->post->mode) {
			/* The mode is new: append or new a list. */
			if ($this->post->mode == 'new') {
				if ($this->post->list2Append) {
					$this->user->append2ContactList($this->post->list2Append, $this->post->users);
					die(js::locate(inlink('manageContacts', "listID={$this->post->list2Append}"), 'parent'));
				} elseif ($this->post->newList) {
					$listID = $this->user->createContactList($this->post->newList, $this->post->users);
					die(js::locate(inlink('manageContacts', "listID=$listID"), 'parent'));
				}
			} elseif ($this->post->mode == 'edit') {
				$this->user->updateContactList($this->post->listID, $this->post->listName, $this->post->users);
				die(js::locate(inlink('manageContacts', "listID={$this->post->listID}"), 'parent'));
			}
		}
		if ($this->post->users) {
			$mode	 = 'new';
			$users	 = $this->user->getContactUserPairs($this->post->users);
		} else {
			$mode	 = 'edit';
			$listID	 = $listID ? $listID : key($lists);
			if (!$listID)
				die(js::alert($this->lang->user->contacts->noListYet) . js::locate($this->createLink('kevinuser', 'browse'), 'parent'));
			
			$list				 = $this->user->getContactListByID($listID);
			$users				 = explode(',', $list->userList);
			$users				 = $this->user->getContactUserPairs($users);
			$this->view->list	 = $list;
		}
		
		$this->view->title		 = $this->lang->company->common . $this->lang->colon . $this->lang->kevinuser->manageContacts;
		$this->view->position[]	 = $this->lang->company->common;
		$this->view->position[]	 = $this->lang->kevinuser->manageContacts;
		$this->view->lists		 = $this->user->getContactLists($this->app->user->account);
		$this->view->users		 = $users;
		$this->view->listID		 = $listID;
		$this->view->mode		 = $mode;
		$this->display();
	}

	/**
	 *  Index.
	 * 
	 * @access public
	 * @return void
	 */
	public function index() {
		$this->display();
	}

	/**
	 * Batch delete records.
	 *
	 * @param string $confirm
	 * @access public
	 * @return void
	 */
	public function recordbatchdelete($confirm = 'no') {
		if ($confirm == 'no') {
			$this->session->set('recordIDList', $this->post->recordIDList);
			die(js::confirm($this->lang->kevinuser->confirmDelete, inlink('recordbatchdelete', "confirm=yes")));
		} else {
			$data = $this->dao->select('deleted')->from(TABLE_KEVIN_USER_RECORD)->where('id')->eq($this->session->recordIDList[0])->fetch();
			if($data->deleted) {
				$this->dao->update(TABLE_KEVIN_USER_RECORD)->set('deleted')->eq(0)->where('id')->in($this->session->recordIDList)->exec();
				die(js::alert($this->lang->kevinuser->successUnDelete) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent'));
			} else {
				$this->dao->update(TABLE_KEVIN_USER_RECORD)->set('deleted')->eq(1)->where('id')->in($this->session->recordIDList)->exec();
				die(js::alert($this->lang->kevinuser->successDelete) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent'));
			}
		}
	}

	/**
	 * Batch edit records.
	 *
	 * @access public
	 * @return void
	 */
	public function recordbatchedit() {
		if ($this->post->account) {
			$allChanges = $this->kevinuser->recordBatchUpdate();
			if (!empty($allChanges)) {
				foreach ($allChanges as $recordID => $changes) {
					if (empty($changes)) continue;

					$actionID = $this->loadModel('action')->create('kevinuserrecord', $recordID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			die(js::alert($this->lang->kevinuser->successBatchEdit) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent'));
		}

		$recordIDList	 = $this->post->recordIDList ? $this->post->recordIDList : die(js::locate($this->createLink('kevinuser', 'recordlist'), 'parent'));
		if (count($recordIDList) > $this->config->kevinuser->batchEditNum) {
			die(js::alert($this->lang->kevinuser->batchEditMsg) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent'));
		}
		$records		 = $this->dao->select('a.*, b.classname')
				->from(TABLE_KEVIN_USER_RECORD)->alias('a')
				->leftJoin(TABLE_KEVIN_USER_CLASS)->alias('b')
				->on('a.class=b.id')->where('a.id')->in($recordIDList)->fetchAll('id');

		$this->view->showFields		 = $this->config->kevinuser->recordBatchEditFields;
		$this->view->accounts		 = $this->loadModel('user')->getPairs();
		$this->view->classpairs		 = $this->kevinuser->getAllClassPairs();
		$this->view->title			 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->recordBatchEdit;
		$this->view->position[]		 = $this->lang->kevinuser->recordBatchEdit;
		$this->view->recordIDList	 = $recordIDList;
		$this->view->records		 = $records;
		$this->display();
	}

	/**
	 * Create a record.
	 * 
	 * @access public
	 * @return void
	 */
	public function recordcreate($id = '') {
		if (!empty($_POST)) {
			$id = $this->kevinuser->recordcreate();
			if($id == 'startDataError')
				die(js::alert($this->lang->kevinuser->startDataError));
			if (dao::isError()) die(js::error(dao::getError()));
			$this->loadModel('action')->create('kevinuserrecord', $id, 'Created');
			die(js::alert($this->lang->kevinuser->successCreate) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent.parent'));
		}
		
		if (!empty($id)){
			$record	 = $this->kevinuser->getRecord($id);
			$this->view->record	= $record;
		}
		$this->view->accounts	 = $this->loadModel('user')->getPairs();
		$this->view->classpairs	 = $this->kevinuser->getAllClassPairs();
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->recordcreate;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->func		 = "create";
		$this->display('kevinuser', 'recordedit');
	}

	/**
	 * Delete a record.
	 * 
	 * @param  int    $id 
	 * @param  string $confirm
	 * @access public
	 * @return void
	 */
	public function recorddelete($id, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevinuser->confirmDelete, inlink('recorddelete', "id=$id&confirm=yes")));
		} else {
			$data = $this->dao->select('deleted')->from(TABLE_KEVIN_USER_RECORD)->where('id')->eq($id)->fetch();
			if ($data->deleted) {
				$this->dao->update(TABLE_KEVIN_USER_RECORD)->set('deleted')->eq(0)->where('id')->eq($id)->exec();
				die(js::alert($this->lang->kevinuser->successUnDelete) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent.parent'));
			} else {
				$this->dao->update(TABLE_KEVIN_USER_RECORD)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
				die(js::alert($this->lang->kevinuser->successDelete) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent.parent'));
			}			
			
		}
	}

	/**
	 * Update the record .
	 * 
	 * @param  int    $id  
	 * @access public
	 * @return void
	 */
	public function recordedit($id) {
		if (!empty($_POST)) {
			$allChanges = $this->kevinuser->recordUpdate($id);
			if ($allChanges == 'lock') {
				die(js::alert($this->lang->kevinuser->lockData) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent.parent'));
			}
			if (!empty($allChanges)) {
				foreach ($allChanges as $classID => $changes) {
					if (empty($changes)) continue;

					$actionID = $this->loadModel('action')->create('kevinuserrecord', $classID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			if (dao::isError()) die(js::error(dao::getError()));
			die(js::alert($this->lang->kevinuser->successSave) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent.parent'));
		}
		$record					 = $this->kevinuser->getRecord($id);
		$this->view->accounts	 = $this->loadModel('user')->getPairs();
		$this->view->classpairs	 = $this->kevinuser->getAllClassPairs();
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->recordedit;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->actions	 = $this->loadModel('action')->getList('kevinuserrecord', $id);
		$this->view->record		 = $record;
		$this->view->func		 = "edit";
		$this->display();
	}

	/**
	 * record list.
	 * 
	 * @param  int    $recTotal,$recPerPage,$pageID
	 * @access public
	 * @return void
	 */
	public function recordlist($account = '', $dept = '', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);
		$filter	 = [];
		
		$class				 = $filter['class']		 = '';
		$deleted				 = $filter['deleted']		 = '';
		if($dept)	$this->session->set('dept', $dept);
		if($account)	$this->session->set('account', $account);

		if (!empty($_POST)) {
			if(isset($_POST['account'])) $this->session->set('account', $_POST['account']);
			if(isset($_POST['dept'])) $this->session->set('dept', $_POST['dept']);
			if(isset($_POST['class'])) $this->session->set('class', $_POST['class']);
			if(isset($_POST['deleted'])){
				$this->session->set('recorddeleted', $_POST['deleted']);
			}else{
				$this->session->set('recorddeleted', '');
			}
			die(js::locate($this->createLink('kevinuser', 'recordlist'), 'parent'));
		}
		
		if(empty($orderBy)) {
			if($this->session->recordOrderBy){
				$orderBy = $this->session->recordOrderBy;
			}else{
				$orderBy = 'id_desc';
			}
		}else{
			$this->session->set('recordOrderBy', $orderBy);
		}
		
		if($this->session->account) {
			$account = $filter['account'] = $this->session->account;
		}else{
			$account = $filter['account'] = '';
		}
		
		if($this->session->dept) {
			$dept = $filter['dept'] = $this->session->dept;
		}else{
			$dept = $filter['dept'] = '';
		}
		
		if($this->session->class) {
			$class = $filter['class'] = $this->session->class;
		}	
		
		if($this->session->recorddeleted) {
			$deleted = $filter['deleted'] = $this->session->recorddeleted;
		}
		
		$recordList = $this->kevinuser->getRecordList($orderBy, $pager, $filter);
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->recordlist;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->recordList	 = $recordList;
		$this->view->deleted	 = isset($deleted)?$deleted:'';
		$this->view->account	 = $account;
		$this->view->dept		 = $dept;
		$this->view->class		 = isset($class)?$class:'';
		$this->view->classpairs	 = $this->kevinuser->getAllClassPairs();
		$this->view->pager		 = $pager;
		$this->view->orderBy	 = $orderBy;
		$this->view->recTotal		 = $recTotal;
		$this->view->recPerPage		 = $recPerPage;
		$this->view->pageID	 = $pageID;
		$this->display();
	}

	/**
	 * View a record.
	 *
	 * @param  int    $id
	 * @access public
	 * @return void
	 */
	public function recordview($id) {
		$record					 = $this->kevinuser->getRecord($id);
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->recordview;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->record		 = $record;
		$this->view->accounts	 = $this->loadModel('user')->getPairs();
		$this->view->actions	 = $this->loadModel('action')->getList('kevinuserrecord', $id);
		$this->display();
	}
	
	public function userbatchedit($deptID = 0) {
		if (isset($_POST['users'])) {
			$this->view->users = $this->dao->select('*')->from(TABLE_USER)->where('account')->in($this->post->users)->orderBy('id')->fetchAll('id');
		} elseif ($_POST) {
			if ($this->post->account)
				$this->kevinuser->userbatchedit();
			die(js::locate($this->createLink('kevinuser', 'browse', "deptID=$deptID"), 'parent'));
		}
		$this->lang->set('menugroup.user', 'company');
		$this->lang->user->menu		 = $this->lang->company->menu;
		$this->lang->user->menuOrder = $this->lang->company->menuOrder;
		
		$this->view->title		 = $this->lang->company->common . $this->lang->colon . $this->lang->kevinuser->userbatchedit;
		$this->view->position[]	 = $this->lang->kevinuser->browse;
		$this->view->position[]	 = $this->lang->kevinuser->userbatchedit;
		$this->view->depts		 = $this->loadModel('dept')->getOptionMenu();
		$this->display();
	}
}
