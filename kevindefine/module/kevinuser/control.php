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
	 * Delete hours deptset.
	 *
	 * @param  int $id
	 * @access public
	 * @return void
	 */
	public function deletedeptuser($id) {
		$id = (int) $id;
		$this->dao->delete()->from(TABLE_KEVIN_DEPTSET)
				->where('id')->eq($id)
				->exec();
		die(js::reload('parent'));
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
	 *  View and update deptset.
	 * 
	 * @param  int $recTotal,$recPerPage,$pageID
	 * @access public
	 * @return void
	 */
	public function deptset($recTotal = 0, $recPerPage = 10, $pageID = 1) {
		if (!empty($_POST)) {
			$messages = $this->kevinuser->updateDeptUsers();
			if (dao::isError()) die(js::error(dao::getError()));
			$vars = array('recTotal' => $recTotal, 'recPerPage' => $recPerPage, 'pageID' => $pageID);
			if(!empty($messages)){
				$message = '';
				foreach ($messages as $key =>$item) {
					if($key == 0) {
						$message .= $item;
					}else{
						$message .= ','.$item;
					}
				}
				die(js::alert($message) .js::locate($this->createLink('kevinuser', 'deptset', $vars), 'parent.parent'));
			}else{
				die(js::locate($this->createLink('kevinuser', 'deptset', $vars), 'parent.parent'));
			}
		}

		/* Load pager. */
		$this->app->loadClass('pager', $static			 = true);
		if ($this->app->getViewType() == 'mhtml') $recPerPage		 = 10;
		$pager			 = pager::init($recTotal, $recPerPage, $pageID);
		$pager->recTotal = 0;

		$this->view->title			 = $this->lang->kevinuser->common . $this->lang->colon . $this->lang->kevinuser->deptset;
		$this->view->position[]		 = $this->lang->kevinuser->deptset;
		$this->view->deptaccounts	 = $this->kevinuser->getDeptset($pager);
		$this->view->pager			 = $pager;
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

}
