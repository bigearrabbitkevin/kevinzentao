<?php

include '../../control.php';

/**
 * The login function reload by Kevin.
 */
class myUser extends user {

	/**
	 * User login, identify him and authorize him.
	 * 
	 * @access public
	 * @return void
	 */
	public function login($referer = '', $from = '') {
		$this->loadModel("kevinlogin");	

		$this->setReferer($referer);

		$this->loginLink	 = $this->createLink('user', 'login');
		$this->denyLink	 = $this->createLink('user', 'deny');

		/* If user is logon, back to the rerferer. */
		if ($this->user->isLogon()) {
			$this->kevinlogin->CheckInGuest($this->app->user->account);//check gest
			$this->DieForJsonGuest();
			$this->DieForRefer($this->referer);
			die();
		}

		//no Login
		if (!(!empty($_POST) or ( isset($_GET['account']) and isset($_GET['password']))))	 {
			$this->app->loadLang('misc');
			$this->view->noGDLib	 = sprintf($this->lang->misc->noGDLib, common::getSysURL() . $this->config->webRoot);
			$this->view->title		 = $this->lang->user->login;
			$this->view->referer	 = $this->referer;
			$this->view->s			 = zget($this->config->global, 'sn');
			$this->view->keepLogin	 = $this->cookie->keepLogin ? $this->cookie->keepLogin : 'off';

			$this->display();
			die();
		}
		
		/* Passed account and password by post or get. */
 		$account	 = '';
		$password	 = '';
		if ($this->post->account) $account	 = $this->post->account;
		if ($this->get->account) $account	 = $this->get->account;
		if ($this->post->password) $password	 = $this->post->password;
		if ($this->get->password) $password	 = $this->get->password;
		if (!$password or ! $account) die(js::error('account or password is empty'));
	
		$arrOfDomainAccount = explode("@", $account);
		if (count($arrOfDomainAccount) == 2) {//get from domainFullAccount
			$user = $this->dao->select('*')->from(TABLE_USER)->where('domainFullAccount')->eq($account)->andWhere('deleted')->eq(0)->fetch();
		} else { //get from account
			$user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($account)->andWhere('deleted')->eq(0)->fetch();
		}
		if ($user) {
			if ((strtotime(date('Y-m-d H:i:s')) - strtotime($user->locked)) <= $this->config->user->lockMinutes * 60) {
				die(js::error(sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes)));
			}
		}

		$this->kevinlogin->isGuest = false;
		//login check
		if ($user){
			$this->kevinlogin->CheckInGuest($user->account);//check gest
			$user = $this->kevinlogin->identify($user, $password);
		}
		//check failed
		if (!$user) {
			if ($this->app->getViewType() == 'json')die(json_encode(array('status' => 'failed')));
			
			$fails		 = ($account) ? $this->user->failPlus($account) : 0;
			$remainTimes = $this->config->user->failTimes - $fails;
			if ($remainTimes <= 0) {
				die(js::error(sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes)));
			} else if ($remainTimes <= 3) {
				die(js::error(sprintf($this->lang->user->lockWarning, $remainTimes)));
			}
			die(js::error($this->lang->user->loginFailed));
		}
		
		//Check pass
		$account		 = $user->account;
		/* Authorize him and save to session. */
		$user->rights	 = $this->user->authorize($account);
		$user->groups	 = $this->user->getGroups($account);
		$this->session->set('user', $user);
		$this->app->user = $this->session->user;
		/* Keep login. */
		if ($this->post->keepLogin) $this->user->keepLogin($user);
		$this->DieForJsonGuest();
		
		$this->loadModel('action')->create('user', $user->id, 'login');

		/* 本地验证时，检查默认密码,用MD5密码进行查询,避免明文密码泄漏问题*/
		if (!$user->domainFullAccount) {
			$isDefult = $this->dao->select('password')
					->from(TABLE_DEFAULTPASSWORD)
					->Where('password')->eq($user->password )
					->fetchAll();

			//如果用户使用默认密码则跳到修改密码界面
			if ($isDefult) die(js::locate($this->createLink('my', 'changePassword'), 'parent'));
		}

		/* Go to the referer. */
		if (!($this->post->referer and
				strpos($this->post->referer, $this->loginLink) === false and
				strpos($this->post->referer, $this->denyLink) === false)){
			die(js::locate($this->createLink($this->config->default->module), 'parent'));
		} 
		
		//has referer
		/* Get the module and method of the referer. */
		if ($this->config->requestType == 'PATH_INFO') {
			$path	 = substr($this->post->referer, strrpos($this->post->referer, '/') + 1);
			$path	 = rtrim($path, '.html');
			if (empty($path)) $path	 = $this->config->requestFix;
			$listM	 = explode($this->config->requestFix, $path);
			$module	 = $listM[0];
			$method	 = count($listM) > 1 ? $listM[1] : '';
		}
		else {
			$url	 = html_entity_decode($this->post->referer);
			$param	 = substr($url, strrpos($url, '?') + 1);
			$listM	 = explode('&', $param);
			$module	 = $listM[0];
			$method	 = count($listM) > 1 ? $listM[1] : '';
			$module	 = str_replace('m=', '', $module);
			$method	 = str_replace('f=', '', $method);
		}

		if (common::hasPriv($module, $method)) {
			die(js::locate($this->post->referer, 'parent'));
		} else {
			die(js::locate($this->createLink($this->config->default->module), 'parent'));
		}
	}

	private function DieForJsonGuest(){
		if ($this->app->getViewType() == 'json'){
			die(json_encode(array('status' => 'success')));
		}
		if ($this->kevinlogin->isGuest){
			//die(js::error('this->kevinlogin->isGuest'));
			die(js::locate($this->createLink('my', 'index'), 'parent'));
		}
	}
	private function DieForRefer($referer){
		if ($referer and strpos($referer, $this->loginLink) === false and
				strpos($referer, $this->denyLink) === false
		) {
			die(js::locate($referer, 'parent'));
		} else {
			die(js::locate($this->createLink($this->config->default->module), 'parent'));
		}
	}
}
