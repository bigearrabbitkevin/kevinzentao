<?php

class kevinloginModel extends model {

	public $isGuest = false;

	public function CheckInGuest($account) {
		$this->isGuest = false;
		//检查是否为guest
		if(array_key_exists($account,$this->config->kevinlogin->guestList)){
			$this->isGuest = true;
		}
	}
	
	//correct password db
	public function CorrectDefaultPassowrdDb() {
		//query colums
		$colomnResults = $this->dao->query("SHOW columns FROM zt_defaultpassword")->fetchAll();
		//$colomnList = array();
		$hasSource = false;
		foreach ($colomnResults as $column) {
			//$colomnList[$column->Field] = $column;
			if( "source" == $column->Field) $hasSource = true;
		}
		//die(json_encode($colomnList));

		if($hasSource) return true;
		

		//insert sorce column
		$this->dao->exec("ALTER TABLE `zt_defaultpassword` ADD `source` char(32) NOT NULL DEFAULT '' COMMENT 'source for password'");
		
		$pwds = $this->dao->select('*')->from(TABLE_DEFAULTPASSWORD)->fetchAll();
		foreach ($pwds as $item) {
			$this->dao->update(TABLE_DEFAULTPASSWORD)
				->set("source")->eq($item->password)
				->set("password")->eq(md5($item->password))
				->where('id')->eq($item->id)
				->exec();
		}
		return true;
	}
	
	public function getGroupPrivsByMethod($module, $method) {
		$groupObjs = $this->dao->select('*')->from(TABLE_GROUPPRIV)
				->where('module')->eq($module)
				->andWhere('method')->eq($method)
				->fetchAll('group');
		return $groupObjs;
	}

	public function getPasswordList($pager = null) {
		$pwds = $this->dao->select('*')->from(TABLE_DEFAULTPASSWORD)
				->orderBy('id')
				->page($pager)
				->fetchAll();
		return $pwds;
	}

	public function getDomainAccounts($pager = null, $filter) {
		$ldapusers = $this->dao->select('id,account,realname,domainFullAccount')->from(TABLE_USER)
				->where('domainFullAccount')->ne('')
				->beginIF(!empty($filter) && !empty($filter['realname']))->andWhere('realname')->like('%'.$filter['realname'].'%')->FI()
				->beginIF(!empty($filter) && !empty($filter['localname']))->andWhere('account')->like('%'.$filter['localname'].'%')->FI()
				->beginIF(!empty($filter) && !empty($filter['remotename']))->andWhere('domainFullAccount')->like('%'.$filter['remotename'].'%')->FI()
				->orderBy('account')
				->page($pager)
				->fetchAll();
		return $ldapusers;
	}
	
	/*
	 * identify $user if has $domainFullAccount,use ldap 
	 */

	public function identify(&$user, $password) {
		if (!$user or ! $password) return false;
		$domainFullAccount	 = $user->domainFullAccount;
		$newmd5Password		 = $password;
		$passwordLength = strlen($password);
		if($passwordLength != 32)  {//加密
			$newmd5Password = md5($password); //zentao type
		}
		if($this->isGuest){
			if($newmd5Password == $user->password )	return $user; //不记录登陆
			else return false;
		}
		
		if ($domainFullAccount) {
			$arrOfDomainAccount = explode("@", $domainFullAccount);
			if (count($arrOfDomainAccount) != 2) return false;
			if(empty($this->config->kevinlogin->domainIP)){
				$domain		 = $arrOfDomainAccount[1];
			}else{
				$domain = $this->config->kevinlogin->domainIP;
			}
			$ldapConnect = ldap_connect("ldap://{$domain}");
			if (!$ldapConnect) return false;
			ldap_set_option($ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ldapConnect, LDAP_OPT_REFERRALS, 0);

			$bind			 = @ldap_bind($ldapConnect, "$domainFullAccount", $password);
			ldap_close($ldapConnect);
			if (!$bind) return false;
			$password		 = ""; //clear	
		} else { //local check
			if($newmd5Password != $user->password )	return false; //password not fit
		}
		if(!$user) return false;//error
		
		//log visit		
		$data = new stdClass();
 		$data->ip		 = $this->server->remote_addr;
		$data->last		 = $this->server->request_time;
		if($newmd5Password != $user->password) $data->password = $newmd5Password;
		if(0 != $user->fails) $data->fails = 0;
		if( '0000-00-00 00:00:00' != $user->locked) $data->locked =  '0000-00-00 00:00:00';

		$this->dao->update(TABLE_USER)->data($data)->autoCheck()->where('id')->eq((int)$user->id)->exec();
		
		$user->last	 = date(DT_DATETIME1, $user->last);
		$user->password = $newmd5Password;		// update Local password same to remote

		return $user;
	}

	public function LdapClearDomainFullAccount($ldapuser) {
		$domainFullAccount	 = $ldapuser->domainFullAccount;
		if (!$domainFullAccount) return true; 
		$arr				 = explode("@", $domainFullAccount);
		if (count($arr) != 2) return false; //email错误,用帐户
		if (!$arr[1] || !$arr[0]) return false;

		$this->dao->update(TABLE_USER)->set("domainFullAccount = null")
				->where('domainFullAccount')->eq($ldapuser->domainFullAccount)->exec();
		return true;
	}
	
	public function LdapUpdateFullAccount(&$ldapuser) {
		if (!$this->LdapClearDomainFullAccount($ldapuser)) return false;

		$this->dao->update(TABLE_USER)
				->set("domainFullAccount")->eq($ldapuser->domainFullAccount)
				->autoCheck()
				->where('account')->eq($ldapuser->account)
				->exec();
	}
	
	public function lockUser($account) {
		$this->dao->update(TABLE_USER)->set('fails')->eq(0)->set('locked')->eq('2030-01-01 00:00:00')
				->where('account')->eq($account)
				->exec();
	}

	public function setdefaultpwd($pwd) {
		$passwordMd5 = md5($pwd);
		$tempObj = $this->dao->select('*')->from(TABLE_DEFAULTPASSWORD)
						->where('password')->eq($passwordMd5)->fetch();
		if ($tempObj) return false;

		$this->dao->insert(TABLE_DEFAULTPASSWORD)
			->set("password")->eq($passwordMd5)
			->set("source")->eq($pwd)
			->exec();
		return true;
	}

	public function updateDefaultPwd() {
		$data	 = fixer::input('post')->get();
		$pwdList = $this->post->pwdList ? $this->post->pwdList : array();

		if (!empty($pwdList)) {
			/* Initialize todos from the post data. */
			foreach ($pwdList as $pwdID) {
				$pwd = $data->source[$pwdID];
				if ('' === $pwd) {
					continue;
				}
				if ($pwdID > 0) {
					$this->updatePwd($pwdID, $pwd);
				} else {
					$this->setdefaultpwd($pwd);
				}
			}
		}
		if (dao::isError()) {
			echo js::error(dao::getError());
			die(js::reload('parent'));
		}
	}

	public function updateDefaultLdapusers() {
		$data = fixer::input('post')->get();

		if (!($data && isset($data->userIdList))) return false;

		/* Initialize todos from the post data. */
		foreach ($data->userIdList as $userID) {
			$ldapuser					 = new stdClass();
			$ldapuser->domainFullAccount = $data->domainFullAccount[$userID];
			$ldapuser->id				 = $userID;
			$ldapuser->account			 = $data->accountList[$userID];
			if (!$ldapuser->account) continue;
			if (strlen($ldapuser->domainFullAccount) > 0) {
				$arrOfDomainAccount = explode("@", $ldapuser->domainFullAccount);
				if (count($arrOfDomainAccount) != 2) {//get from domainFullAccount	
					$ldapuser->domainFullAccount = "";
				}
			}
			if (!$ldapuser->domainFullAccount) $ldapuser->domainFullAccount = "";

			$this->LdapUpdateFullAccount($ldapuser);
		}


		if (dao::isError()) {
			echo js::error(dao::getError());
			die(js::reload('parent'));
		}
	}

	/**
	 * Update privilege by module.
	 * 
	 * @access public
	 * @return void
	 */
	public function updatePrivOfGroup() {
		$module	 = $this->post->module;
		$method = $this->post->actions;
		if ($module == false or $method == false) return false; //no module or $method
		//1,删除权限
		$this->dao->delete()->from(TABLE_GROUPPRIV)
				->where('module')->eq($module)
				->andWhere('method')->eq($method)->exec();
		//2,插入权限
		$data	 = new stdclass();
		$groups =$this->post->groups;
		foreach ($groups as $group) {
			$data->group	 = $group;
			$data->module	 = $module;
			$data->method	 = $method;
			$this->dao->insert(TABLE_GROUPPRIV)
					->data($data)
					->exec();
		}
		return true;
	}

	public function updatePwd($id, $password) {
		$this->dao->update(TABLE_DEFAULTPASSWORD)
				->set("password")->eq(md5($password))
				->set("source")->eq($password)
				->where('id')->eq($id)
				->exec();
	}

    /**
     * Update password 
     * 
     * @param  string $account 
     * @access public
     * @return void
     */
    public function updatePassword($password,$account)
    {
		$newmd5Password = $password;
		$passwordLength = strlen($password);
		if($passwordLength != 32)  {//加密
			$newmd5Password = md5($password); //zentao type
		}

        $this->dao->update(TABLE_USER)->set('password')->eq($newmd5Password)->where('id')->$account->eq($account)->exec();
    }
	
	public function unionKeyCheck($ldapuser) {
		if (!$ldapuser->domainFullAccount) return true;
		$ldapusers = $this->dao->select('*')->from(TABLE_USER)
				->where('domainFullAccount')->eq($ldapuser->domainFullAccount)
				->fetch();
		if ($ldapusers) return false;
		return true;
	}

}
