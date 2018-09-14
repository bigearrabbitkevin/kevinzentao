<?php

class kevinlogin extends control {

	public function __construct() {
		parent::__construct();
	}

	public function ajaxGetGroupPrivsByMethod($module, $method) {
		$groupObjs		 = $this->kevinlogin->getGroupPrivsByMethod($module, $method);
		$groupArray		 = array_keys($groupObjs);
		$chosenGroupStr	 = implode(',', $groupArray);
		$this->session->set('chosenmodule',$module);
		$this->session->set('chosenmethod',$method);
		$this->session->set('chosengpstr',$chosenGroupStr);
		die(html::select('groups[]', $this->loadModel('group')->getPairs(), $this->session->chosengpstr, "multiple='multiple' style='height:500px' class='form-control'"));
	}
	
	public function defaultpwd($recTotal = 0, $recPerPage = 20, $pageID = 1) {
		if (!empty($_POST)) {
			$this->kevinlogin->updateDefaultPwd();
			$vars = array('$recTotal' => $recTotal, '$recPerPage' => $recPerPage, '$pageID' => $pageID);
			die(js::locate($this->createLink('kevinlogin', 'defaultpwd', $vars), 'parent'));
		}
		/* Load pager. */
		$this->app->loadClass('pager', $static			 = true);
		if ($this->app->getViewType() == 'mhtml') $recPerPage		 = 10;
		$pager			 = pager::init($recTotal, $recPerPage, $pageID);
		$pager->recTotal = 0;
		$this->kevinlogin->CorrectDefaultPassowrdDb();//updae db
		$passwords		 = $this->kevinlogin->getPasswordList($pager);
		$this->view->title		 = $this->lang->kevinlogin->common . $this->lang->colon . $this->lang->kevinlogin->defaultpwd;
		$this->view->position[]	 = $this->lang->kevinlogin->defaultpwd;
		$this->view->pager		 = $pager;
		$this->view->passwords	 = $passwords;
		$this->view->controlType = 'defaultpwd';
		$this->display();
	}

	private function domainaccountCorrect() {
		$ldapusers = $this->dao->select('*')->from(TABLE_KEVIN_LDAPUSER)
				->where('domain')->ne('')
				->fetchAll();
		if (!$ldapusers) return true;

		foreach ($ldapusers as $user) {
			if (!$user->domain) continue;
			$this->dao->update(TABLE_USER)
					->set(domainFullAccount)->eq($user->remote . "@" . $user->domain)
					->autoCheck()
					->where('account')->eq($user->local)
					->exec();
		}

		$this->dao->delete("*")->from(TABLE_KEVIN_LDAPUSER)->exec(); //empty
		return true;
	}

	public function domainaccount($recTotal = 0, $recPerPage = 10, $pageID = 1) {
		$filter = [];
		if (!empty($_POST)) {
			$post = $_POST;
			if(isset($post['realname']) || isset($post['localname']) || isset($post['remotename'])) {
				if(isset($post['realname'])) $filter['realname'] = $post['realname'];
				if(isset($post['localname'])) $filter['localname'] = $post['localname'];
				if(isset($post['remotename'])) $filter['remotename'] = $post['remotename'];
				$this->session->set('domainaccount_filter', $filter);
			}else{
				$this->kevinlogin->updateDefaultLdapusers();
				$vars = array('$recTotal' => $recTotal, '$recPerPage' => $recPerPage, '$pageID' => $pageID);
				die(js::locate($this->createLink('kevinlogin', 'domainaccount', $vars), 'parent'));
			}
		}
		if(empty($filter) && $_SESSION['domainaccount_filter']) $filter = $_SESSION['domainaccount_filter'];
		$this->domainaccountCorrect();
		/* Load pager. */
		$this->app->loadClass('pager', $static			 = true);
		if ($this->app->getViewType() == 'mhtml') $recPerPage		 = 10;
		$pager			 = pager::init($recTotal, $recPerPage, $pageID);
		$pager->recTotal = 0;

		$this->view->title			 = $this->lang->kevinlogin->common . $this->lang->colon . $this->lang->kevinlogin->domainaccount;
		$this->view->position[]		 = $this->lang->kevinlogin->domainaccount;
		$this->view->domainaccounts	 = $this->kevinlogin->getDomainAccounts($pager, $filter);
		$this->view->pager			 = $pager;
		$this->view->controlType	 = 'domainaccount';
		$this->view->filter = $filter;
		$this->display();
	}

	public function delete($id) {
		$this->dao->delete()->from(TABLE_DEFAULTPASSWORD)->where('id')->eq($id)->exec();
		die(js::reload('parent'));
	}

	public function deleteldapuser($id) {
		$id = (int) $id;
		$this->dao->update(TABLE_USER)
				->set("domainFullAccount")->eq("")
				->where('id')->eq($id)
				->exec();
		die(js::reload('parent'));
	}

	/**
	 * Manage privleges of a group. 
	 * 
	 * @param  int    $groupID 
	 * @access public
	 * @return void
	 */
	public function managePriv() {
		$menu = '';
		$this->loadModel('group');
		foreach ($this->lang->resource as $moduleName => $action) {
			if ($this->group->checkMenuModule($menu, $moduleName)) {
				$this->app->loadLang($moduleName);
			}
		}

		if (!empty($_POST)) {
			$this->kevinlogin->updatePrivOfGroup();
			die(js::reload('parent'));
		}
		$this->view->title		 = $this->lang->company->common . $this->lang->colon . $this->lang->kevinlogin->managepriv;
		$this->view->position[]	 = $this->lang->kevinlogin->managepriv;

		foreach ($this->lang->resource as $module => $moduleActions) {
			$modules[$module] = $this->lang->$module->common;
			foreach ($moduleActions as $action) {
				$actions[$module][$action] = isset($this->lang->$module->$action) ? $this->lang->$module->$action : $action;
			}
		}
		$this->view->groups		 = $this->group->getPairs();
		$this->view->modules	 = $modules;
		$this->view->actions	 = $actions;
		$this->view->controlType = 'managepriv';

		$this->display();
	}

	/**
	 * Unlock a user.
	 * 
	 * @param  int    $account 
	 * @param  string $confirm 
	 * @access public
	 * @return void
	 */
	public function unlock($account, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevinlogin->confirmUnlock, $this->createLink('kevinlogin', 'unlock', "account=$account&confirm=yes")));
		} else {
			$this->loadModel('user')->cleanLocked($account);
			die(js::locate($this->createLink('kevinhours', 'browse'), 'parent'));
		}
	}

	public function userLock($account, $confirm = 'no') {
		if (strpos($this->app->company->admins, ",$account,") === false) {
			if ($confirm == 'no') {
				die(js::confirm($this->lang->kevinlogin->confirmLock, $this->createLink('kevinlogin', 'userLock', "account=$account&confirm=yes")));
			} else {
				$this->kevinlogin->lockUser($account);
				die(js::reload('parent.parent'));
			}
		} else {
			die(js::locate($this->createLink('kevinhours', 'browse'), 'parent'));
		}
	}

}
