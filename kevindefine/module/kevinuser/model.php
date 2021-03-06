<?php

/**
 * The model file of dept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: model.php 4210 2013-01-22 01:06:12Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php

class kevinuserModel extends model {
	
	/**
	 * Set menu.
	 *
	 * @param  int    $dept
	 * @access public
	 * @return void
	 */
	public function setMenu($dept = 0)
	{
		common::setMenuVars($this->lang->kevinuser->menu, 'addUser', array($dept));
		common::setMenuVars($this->lang->kevinuser->menu, 'batchAddUser', array($dept));
	}
	
	/**
	 * Batch create users.
	 *
	 * @param  int    $users
	 * @access public
	 * @return void
	 */
	public function batchCreate()
	{
		if(empty($_POST['verifyPassword']) or md5($this->post->verifyPassword) != $this->app->user->password) die(js::alert($this->lang->user->error->verifyPassword));
		
		$users    = fixer::input('post')->get();
		$data     = array();
		$accounts = array();
		for($i = 0; $i < $this->config->user->batchCreate; $i++)
		{
			if($users->account[$i] != '')
			{
				$account = $this->dao->select('account')->from(TABLE_USER)->where('account')->eq($users->account[$i])->fetch();
				if($account) die(js::error(sprintf($this->lang->user->error->accountDupl, $i+1)));
				if(in_array($users->account[$i], $accounts)) die(js::error(sprintf($this->lang->user->error->accountDupl, $i+1)));
				if(!validater::checkAccount($users->account[$i])) die(js::error(sprintf($this->lang->user->error->account, $i+1)));
				if($users->realname[$i] == '') die(js::error(sprintf($this->lang->user->error->realname, $i+1)));
				if($users->email[$i] and !validater::checkEmail($users->email[$i])) die(js::error(sprintf($this->lang->user->error->mail, $i+1)));
				$users->password[$i] = (isset($prev['password']) and $users->ditto[$i] == 'on' and empty($users->password[$i])) ? $prev['password'] : $users->password[$i];
				if(!validater::checkReg($users->password[$i], '|(.){6,}|')) die(js::error(sprintf($this->lang->user->error->password, $i+1)));
				$role = $users->role[$i] == 'ditto' ? (isset($prev['role']) ? $prev['role'] : '') : $users->role[$i];
				
				$data[$i] = new stdclass();
				$data[$i]->dept     = $users->dept[$i] == 'ditto' ? (isset($prev['dept']) ? $prev['dept'] : 0) : $users->dept[$i];
				$data[$i]->account  = $users->account[$i];
				$data[$i]->realname = $users->realname[$i];
				$data[$i]->role     = $role;
				$data[$i]->group    = $users->group[$i] == 'ditto' ? (isset($prev['group']) ? $prev['group'] : '') : $users->group[$i];
				$data[$i]->email    = $users->email[$i];
				$data[$i]->gender   = $users->gender[$i];
				$data[$i]->password = md5($users->password[$i]);
				$data[$i]->commiter = $users->commiter[$i];
				$data[$i]->join     = empty($users->join[$i]) ? '0000-00-00' : ($user->join[$i]);
				$data[$i]->skype    = $users->skype[$i];
				$data[$i]->qq       = $users->qq[$i];
				$data[$i]->yahoo    = $users->yahoo[$i];
				$data[$i]->gtalk    = $users->gtalk[$i];
				$data[$i]->wangwang = $users->wangwang[$i];
				$data[$i]->mobile   = $users->mobile[$i];
				$data[$i]->phone    = $users->phone[$i];
				$data[$i]->address  = $users->address[$i];
				$data[$i]->zipcode  = $users->zipcode[$i];
				
				/* Change for append field, such as feedback.*/
				if(!empty($this->config->user->batchAppendFields))
				{
					$appendFields = explode(',', $this->config->user->batchAppendFields);
					foreach($appendFields as $appendField)
					{
						if(empty($appendField)) continue;
						if(!isset($users->$appendField)) continue;
						$fieldList = $users->$appendField;
						$data[$i]->$appendField = $fieldList[$i];
					}
				}
				
				$accounts[$i]     = $data[$i]->account;
				$prev['dept']     = $data[$i]->dept;
				$prev['role']     = $data[$i]->role;
				$prev['group']    = $data[$i]->group;
				$prev['password'] = $users->password[$i];
			}
		}
		
		$this->loadModel('mail');
		foreach($data as $user)
		{
			if($user->group)
			{
				$group = new stdClass();
				$group->account = $user->account;
				$group->group   = $user->group;
				$this->dao->insert(TABLE_USERGROUP)->data($group)->exec();
			}
			unset($user->group);
			$this->dao->insert(TABLE_USER)->data($user)->autoCheck()->exec();
			if(dao::isError())
			{
				echo js::error(dao::getError());
				die(js::reload('parent'));
			}
			else
			{
				if($this->config->mail->mta == 'sendcloud' and !empty($user->email)) $this->mail->syncSendCloud('sync', $user->email, $user->realname);
			}
		}
	}
	
	/**
	* check the item unique from the table
	*
	* @param  object $item
	* @access public
	* @return string
	*/
	public function checkUnique($item, $table, $fields, $id = "") {
		$fields	 = explode(',', $fields);
		$count	 = count($fields);
		$sql	 = "select count(*) as count from $table where 1";
		if ($id) $sql	 .= " and id!=$id";
		foreach ($item as $key => $value) {
			$arr[$key] = $value;
		}
		foreach ($fields as $val) {
			$sql .= " and $val=" . "'$arr[$val]'";
		}
		$obj = $this->dbh->query($sql)->fetch();
		if ($obj->count > 0) {
			die(js::alert($this->lang->kevinuser->errorUnique));
		}
	}

	/**
	 * Batch delete class. 
	 * 
	 * @access public
	 * @return void
	 */
	public function classBatchDelete($classIDList) {
		$this->dao->update(TABLE_KEVIN_USER_CLASS)->set('deleted')->eq(1)->where('id')->in($classIDList)->exec();
	}

	/**
	 * Batch delete recycle class. 
	 * 
	 * @access public
	 * @return void
	 */
	public function classBatchDeleteRecycle() {
		$this->dao->delete()->from(TABLE_KEVIN_USER_CLASS)->where('id')->in($this->post->classIDList)->exec();
	}

	/**
	 * Batch update class. 
	 * 
	 * @access public
	 * @return array
	 */
	public function classBatchUpdate() {
		$classes	 = array();
		$allChanges	 = array();
		$data		 = fixer::input('post')->get();
		$oldClasses	 = $this->getClassByIdList($this->post->classIDList);
		foreach ($data->classIDList as $classID) {
			$data->payrate[$classID] /= 100;
			$classes[$classID]		 = new stdClass();

			$classes[$classID]->role		 = $data->role[$classID];
			$classes[$classID]->classify1	 = $data->classify1[$classID];
			$classes[$classID]->classify2	 = $data->classify2[$classID];
			$classes[$classID]->classify3	 = $data->classify3[$classID];
			$classes[$classID]->classify4	 = $data->classify4[$classID];
			$classes[$classID]->classname	 = $data->classify1[$classID] . "-" . $data->role[$classID] . "-" . $data->classify2[$classID] . "-" . $data->classify3[$classID];
			if ($data->classify4[$classID]) {
				$classes[$classID]->classname .= "-" . $data->classify4[$classID];
			}
			$classes[$classID]->payrate			 = $data->payrate[$classID];
			$classes[$classID]->hourFee			 = $data->hourFee[$classID];
			$classes[$classID]->conversionFee	 = $data->hourFee[$classID] * $data->payrate[$classID];
			$classes[$classID]->start			 = date('Y-m-d', strtotime($data->start[$classID]));
			$classes[$classID]->end				 = date('Y-m-d', strtotime($data->end[$classID]));
			$classes[$classID]->jobRequirements	 = $data->jobRequirements[$classID];
			$classes[$classID]->remarks			 = $data->remarks[$classID];
		}

		foreach ($classes as $classID => $class) {
			$oldClass = $oldClasses[$classID];
			$item['role'] =  $class->role;
			$item['classify1'] =  $class->classify1;
			$item['classify2'] =  $class->classify2;
			$item['classify3'] =  $class->classify3;
			$item['classify4'] =  $class->classify4;
			$this->checkUnique($item, TABLE_KEVIN_USER_CLASS, 'role,classify1,classify2,classify3,classify4',$classID);
			$this->dao->update(TABLE_KEVIN_USER_CLASS)->data($class)
				->autoCheck()
				->batchCheck($this->config->kevinuser->edit->requiredFields, 'notempty')
				->check('payrate', 'float')
				->check('hourFee', 'float')
				->check('conversionFee', 'float')
				->check('end', 'gt', $class->start)
				->check('start', 'gt', date('Y-m-d', 0))
				->check('end', 'gt', date('Y-m-d', 0))
				->where('id')->eq($classID)
				->limit(1)
				->exec();

			if (dao::isError()) die(js::error('classBatchUpdate#' . $classID . dao::getError(true)));
			$allChanges[$classID] = common::createChanges($oldClass, $class);
		}
		return $allChanges;
	}
	
	/**
	 * Create a user.
	 *
	 * @access public
	 * @return void
	 */
	public function create()
	{
		if(!$this->checkPassword()) return;
		
		$user = fixer::input('post')
			->setDefault('join', '0000-00-00')
			->setIF($this->post->password1 != false, 'password', md5($this->post->password1))
			->setIF($this->post->password1 == false, 'password', '')
			->remove('group, password1, password2, verifyPassword')
			->get();
		
		if(isset($this->config->safe->mode) and $this->computePasswordStrength($this->post->password1) < $this->config->safe->mode)
		{
			dao::$errors['password1'][] = $this->lang->user->weakPassword;
			return false;
		}
		
		if(empty($_POST['verifyPassword']) or md5($this->post->verifyPassword) != $this->app->user->password)
		{
			dao::$errors['verifyPassword'][] = $this->lang->user->error->verifyPassword;
			return false;
		}
		
		$this->dao->insert(TABLE_USER)->data($user)
			->autoCheck()
			->batchCheck($this->config->user->create->requiredFields, 'notempty')
			->check('account', 'unique')
			->check('account', 'account')
			->checkIF($this->post->email != false, 'email', 'email')
			->exec();
		if($this->post->group)
		{
			$data = new stdClass();
			$data->account = $this->post->account;
			$data->group   = $this->post->group;
			$this->dao->insert(TABLE_USERGROUP)->data($data)->exec();
		}
		
		if(!dao::isError())
		{
			$this->loadModel('mail');
			if($this->config->mail->mta == 'sendcloud' and !empty($user->email)) $this->mail->syncSendCloud('sync', $user->email, $user->realname);
		}
	}
	
	/**
	 * Check the passwds posted.
	 *
	 * @access public
	 * @return bool
	 */
	public function checkPassword($canNoPassword = false)
	{
		if(!$canNoPassword and empty($_POST['password1'])) dao::$errors['password'][] = sprintf($this->lang->error->notempty, $this->lang->kevinuser->password);
		if($this->post->password1 != false)
		{
			if($this->post->password1 != $this->post->password2) dao::$errors['password'][] = $this->lang->error->passwordsame;
			if(!validater::checkReg($this->post->password1, '|(.){6,}|')) dao::$errors['password'][] = $this->lang->error->passwordrule;
		}
		return !dao::isError();
	}
	
	
	/**
	 * Compute  password strength.
	 *
	 * @param  string    $password
	 * @access public
	 * @return int
	 */
	public function computePasswordStrength($password)
	{
		if(strlen($password) == 0) return 0;
		
		$strength = 0;
		$length   = strlen($password);
		
		$uniqueChars = '';
		$complexity  = array();
		$chars = str_split($password);
		foreach($chars as $letter)
		{
			$asc = ord($letter);
			if($asc >= 48 && $asc <= 57)
			{
				$complexity[2] = 2;
			}
			elseif($asc >= 65 && $asc <= 90)
			{
				$complexity[1] = 2;
			}
			elseif($asc >= 97 && $asc <= 122)
			{
				$complexity[0] = 1;
			}
			else
			{
				$complexity[3] = 3;
			}
			if(strpos($uniqueChars, $letter) === false) $uniqueChars .= $letter;
		}
		if(strlen($uniqueChars) > 4)$strength += strlen($uniqueChars) - 4;
		$strength += array_sum($complexity) + (2 * (count($complexity) - 1));
		if($length < 6 and $strength >= 10) $strength = 9;
		
		$strength = $strength > 29 ? 29 : $strength;
		$strength = floor($strength / 10);
		
		return $strength;
	}
	
	/**
	 * create class.
	 * 
	 * @access public
	 * @return int
	 */
	public function classCreate() {
		$class			 = fixer::input('post')->get();
		$class->payrate	 /= 100;

		$class->classname = $class->classify1 . "-" . $class->role . "-" . $class->classify2 . "-" . $class->classify3;

		if ($class->classify4) {
			$class->classname .= "-" . $class->classify4;
		}
		$item['role']			 = $class->role;
		$item['classify1']		 = $class->classify1;
		$item['classify2']		 = $class->classify2;
		$item['classify3']		 = $class->classify3;
		$item['classify4']		 = $class->classify4;
		$this->checkUnique($item, TABLE_KEVIN_USER_CLASS, 'role,classify1,classify2,classify3,classify4');
		$class->conversionFee	 = $class->hourFee * $class->payrate;
		$class->start			 = date('Y-m-d', strtotime($class->start));
		$class->end = date('Y-m-d', strtotime($class->end));
		$this->dao->insert(TABLE_KEVIN_USER_CLASS)->data($class)
			->autoCheck()
			->batchCheck($this->config->kevinuser->create->requiredFields, 'notempty')
			->check('payrate', 'float')
			->check('hourFee', 'float')
			->check('conversionFee', 'float')
			->check('end', 'gt', $class->start)
			->check('start', 'gt', date('Y-m-d', 0))
			->check('end', 'gt', date('Y-m-d', 0))
			->exec();
		if (dao::isError()) die(js::error(dao::getError()));
		$id = $this->dbh->lastInsertID();
		return $id;
	}

	/**
	 * Delete a class.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function classDelete($id) {
		$this->dao->update(TABLE_KEVIN_USER_CLASS)
			->set('deleted')
			->eq(1)
			->where('id')
			->eq($id)
			->exec();
	}

	/**
	 * Delete class in recycle.
	 *
	 * @param  int    $id
	 * @access public
	 * @return void
	 */
	public function classDeleteRecycle($id) {
		$this->dao->delete()->from(TABLE_KEVIN_USER_CLASS)->where('id')->eq($id)->exec();
	}

	/**
	 * restore class in recycle.
	 *
	 * @param  int    $id
	 * @access public
	 * @return void
	 */
	public function classUndelete($id) {
		$this->dao->update(TABLE_KEVIN_USER_CLASS)
			->set('deleted')
			->eq(0)
			->where('id')
			->eq($id)
			->exec();
	}

	/**
	 * Update class.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return array
	 */
	public function classUpdate($id) {
		$allChanges		 = array();
		$oldClass		 = $this->getClass($id);
		$class			 = fixer::input('post')->get();
		$class->payrate	 /= 100;

		$class->classname = $class->classify1 . "-" . $class->role . "-" . $class->classify2 . "-" . $class->classify3;

		if ($class->classify4) {
			$class->classname .= "-" . $class->classify4;
		}
		$item['role']			 = $class->role;
		$item['classify1']		 = $class->classify1;
		$item['classify2']		 = $class->classify2;
		$item['classify3']		 = $class->classify3;
		$item['classify4']		 = $class->classify4;
		$this->checkUnique($item, TABLE_KEVIN_USER_CLASS, 'role,classify1,classify2,classify3,classify4', $id);
		$class->conversionFee	 = $class->hourFee * $class->payrate;
		$class->start			 = date('Y-m-d', strtotime($class->start));
		$class->end = date('Y-m-d', strtotime($class->end));
		$this->dao->update(TABLE_KEVIN_USER_CLASS)
			->data($class)
			->autoCheck()
			->batchCheck($this->config->kevinuser->edit->requiredFields, 'notempty')
			->check('payrate', 'float')
			->check('hourFee', 'float')
			->check('conversionFee', 'float')
			->check('end', 'gt', $class->start)
			->check('start', 'gt', date('Y-m-d', 0))
			->check('end', 'gt', date('Y-m-d', 0))
			->where('id')
			->eq($id)
			->exec();
		$allChanges[$id]		 = common::createChanges($oldClass, $class);
		return $allChanges;
	}
	
	public static function createMemberLinkOfBrowse($dept) {
		$linkHtml = html::a(helper::createLink('kevinuser', 'browse', "param={$dept->id}"), $dept->name, '_self', "id='dept{$dept->id}'");
		return $linkHtml;
	}

	/**
	 * Batch delete dept. 
	 * 
	 * @param  array    $deptIDList 
	 * @access public
	 * @return void
	 */
	public function deptBatchDelete($deptIDList) {
		$this->dao->update(TABLE_DEPT)->set('deleted')->eq(1)->where('id')->in($deptIDList)->exec();
	}

	/**
	 * Batch update dept. 
	 * 
	 * @access public
	 * @return array
	 */
	public function deptBatchUpdate() {
		$depts		 = array();
		$allChanges	 = array();
		$data		 = fixer::input('post')->get();
		$oldDepts	 = $this->getDeptByIdList($this->post->deptIDList);
		$deptModel	 = $this->loadModel('dept');
		foreach ($data->deptIDList as $deptID) {
			$parent			 = $deptModel->getById($data->parent[$deptID]);
			$depts[$deptID]	 = new stdClass();

			$depts[$deptID]->parent	 = $data->parent[$deptID];
			$depts[$deptID]->name	 = $data->name[$deptID];
			$depts[$deptID]->manager = $data->manager[$deptID];
			$depts[$deptID]->email	 = $data->email[$deptID];
			$depts[$deptID]->code	 = $data->code[$deptID];
			$depts[$deptID]->grade	 = $parent ? $parent->grade + 1 : 1;
			$depts[$deptID]->path	 = $parent ? $parent->path . $deptID . ',' : ',' . $deptID . ',';
			$depts[$deptID]->order	 = $data->order[$deptID];
			if ($data->group[$deptID]) {
				foreach ($data->group[$deptID] as $groupID) {
					$depts[$deptID]->group .= "," . $groupID;
				}

				$depts[$deptID]->group .= ",";
			}
		}

		foreach ($depts as $deptID => $dept) {
			$oldDept = $oldDepts[$deptID];

			$this->dao->update(TABLE_DEPT)->data($dept)
				->autoCheck()
				->batchCheck('parent,name,order', 'notempty')
				->where('id')->eq($deptID)
				->limit(1)
				->exec();

			if (dao::isError()) die(js::error('deptBatchUpdate#' . $deptID . dao::getError(true)));
			$allChanges[$deptID] = common::createChanges($oldDept, $dept);
		}
		return $allChanges;
	}

	/**
	 * create dept.
	 * 
	 * @access public
	 * @return int
	 */
	public function deptCreate() {
		$postData		 = fixer::input('post')->get();
		$deptModel		 = $this->loadModel('dept');
		$parent			 = $deptModel->getById($this->post->parent);
		$dept			 = new stdClass();
		$dept->parent	 = $postData->parent;
		$dept->name		 = $postData->name;
		$dept->manager	 = $postData->manager;
		$dept->email	 = $postData->email;
		$dept->code		 = $postData->code;
		$dept->order	 = $postData->order;
		$dept->grade	 = $parent ? $parent->grade + 1 : 1;

		if ($this->post->group) {
			foreach ($this->post->group as $groupID) {
				$dept->group .= "," . $groupID;
			}

			$dept->group .= ",";
		}
		$this->dao->insert(TABLE_DEPT)->data($dept)
			->autoCheck()
			->batchCheck('parent,name,order', 'notempty')
			->exec();
		$id = $this->dbh->lastInsertID();

		$path = $parent ? $parent->path . $id . ',' : ',' . $id . ',';
		$this->dao->update(TABLE_DEPT)
			->set('path')->eq($path)
			->autoCheck()
			->where('id')
			->eq($id)
			->exec();

		return $id;
	}

	/**
	 * Delete a dept.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function deptDelete($id) {
		$this->dao->update(TABLE_DEPT)
			->set('deleted')
			->eq(1)
			->where('id')
			->eq($id)
			->exec();
	}

	/**
	 * Update dept.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return array
	 */
	public function deptUpdate($id) {
		$allChanges		 = array();
		$postData		 = fixer::input('post')->get();
		$deptModel		 = $this->loadModel('dept');
		$oldDept		 = $deptModel->getById($id);
		$parent			 = $deptModel->getById($this->post->parent);
		$dept			 = new stdClass();
		$dept->parent	 = $postData->parent;
		$dept->name		 = $postData->name;
		$dept->manager	 = $postData->manager;
		$dept->email	 = $postData->email;
		$dept->code		 = $postData->code;
		$dept->order	 = $postData->order;
		$dept->grade	 = $parent ? $parent->grade + 1 : 1;
		$dept->path		 = $parent ? $parent->path . $id . ',' : ',' . $id . ',';
		if ($this->post->group) {
			foreach ($this->post->group as $groupID) {
				$dept->group .= "," . $groupID;
			}

			$dept->group .= ",";
		}else{
			$dept->group = '';
		}
		$this->dao->update(TABLE_DEPT)
			->data($dept)
			->autoCheck()
			->batchCheck('name,order', 'notempty')
			->where('id')
			->eq($id)
			->exec();
		$allChanges[$id] = common::createChanges($oldDept, $dept);

		return $allChanges;
	}

	/**
	 * Get all accounts.
	 * 
	 * @access public
	 * @return array
	 */
	public function getAllAccounts() {
		$accounts	 = [];
		$data		 = $this->dao->select('account')->from(TABLE_USER)->orderBy('id asc')->fetchAll();
		foreach ($data as $item) {
			$accounts[$item->account] = $item->account;
		}
		return $accounts;
	}

	/**
	 * Get all class id and class name pairs.
	 * 
	 * @access public
	 * @return array
	 */
	public function getAllClassPairs() {
		$classPairs		 = [];
		$data			 = $this->dao->select('id, classname')->from(TABLE_KEVIN_USER_CLASS)->orderBy('id asc')->fetchAll();
		$classPairs['']	 = '';
		foreach ($data as $item) {
			$classPairs[$item->id] = $item->classname . "(" . $item->id . ")";
		}
		return $classPairs;
	}

	/**
	 * Get all class id and class name pairs.
	 * 
	 * @access public
	 * @return array
	 */
	public function getAllDeptPairs() {
		$deptPairs		 = [];
		$data			 = $this->dao->select('id, name')->from(TABLE_DEPT)->orderBy('id asc')->fetchAll();
		$deptPairs['']	 = '';
		foreach ($data as $item) {
			$deptPairs[$item->id] = $item->name;
		}
		return $deptPairs;
	}

	/**
	 * Get class by id.
	 * 
	 * @param  int $id 
	 * @access public
	 * @return array
	 */
	public function getClass($id) {
		$class = $this->dao->select('*')
			->from(TABLE_KEVIN_USER_CLASS)
			->where('id')->eq($id)
			->fetch();
		return $class;
	}

	/**
	 * Get by idList.
	 * 
	 * @param  array    $classIDList 
	 * @access public
	 * @return array
	 */
	public function getClassByIdList($classIDList) {
		return $this->dao->select('*')->from(TABLE_KEVIN_USER_CLASS)->where('id')->in($classIDList)->fetchAll('id');
	}

	/**
	 * Get classify1 List.
	 * 
	 * @access public
	 * @return array
	 */
	public function getClassify1List() {
		return $this->dao->select('code, titleCN')->from(TABLE_KEVINCLASS_ITEM)
				->where('parent')->eq($this->config->kevinuser->classID['class1'])
				->orderBy('order')
				->fetchPairs('code', 'titleCN');
	}

	/**
	 * Get classify2 List.
	 * 
	 * @access public
	 * @return array
	 */
	public function getClassify2List() {
		return $this->dao->select('code, titleCN')->from(TABLE_KEVINCLASS_ITEM)
				->where('parent')->eq($this->config->kevinuser->classID['class2'])
				->orderBy('order')
				->fetchPairs('code', 'titleCN');
	}

	/**
	 * Get classify3 List.
	 * 
	 * @access public
	 * @return array
	 */
	public function getClassify3List() {
		return $this->dao->select('code, titleCN')->from(TABLE_KEVINCLASS_ITEM)
				->where('parent')->eq($this->config->kevinuser->classID['class3'])
				->orderBy('order')
				->fetchPairs('code', 'titleCN');
	}

	/**
	 * Get classList.
	 * 
	 * @param  object $pager
	 * @param  array $filter 
	 * @access public
	 * @return array
	 */
	public function getClassList($orderBy, $pager, $filter) {
		$classlist = null;
		if (empty($filter)) {
			$classlist = $this->dao->select('*')
				->from(TABLE_KEVIN_USER_CLASS)
				->where('deleted')->eq(0)
				->orderBy($orderBy)
				->page($pager)
				->fetchAll();
		} else {
			$classlist = $this->dao->select('*')
				->from(TABLE_KEVIN_USER_CLASS)
				->where(true)
				->beginIF(empty($filter['deleted']))->andWhere('deleted')->eq(0)->FI()
				->beginIF(!empty($filter['deleted']))->andWhere('deleted')->eq($filter['deleted'])->FI()
				->beginIF(!empty($filter['role']))->andWhere('role')->eq($filter['role'])->FI()
				->beginIF(!empty($filter['classify1']))->andWhere('classify1')->eq($filter['classify1'])->FI()
				->beginIF(!empty($filter['classify2']))->andWhere('classify2')->eq($filter['classify2'])->FI()
				->beginIF(!empty($filter['classify3']))->andWhere('classify3')->eq($filter['classify3'])->FI()
				->orderBy($orderBy)
				->page($pager)
				->fetchAll();
		}
		return $classlist;
	}

	/**
	 * Get classList in recycle.
	 * 
	 * @param  object $pager
	 * @access public
	 * @return array
	 */
	public function getClassRecycleList($pager) {
		$classlist = $this->dao->select('*')
			->from(TABLE_KEVIN_USER_CLASS)
			->where('deleted')->eq(1)
			->orderBy('id asc')
			->page($pager)
			->fetchAll();
		return $classlist;
	}

	/**
	 * Get by id.
	 * 
	 * @param  array    $deptID 
	 * @access public
	 * @return array
	 */
	public function getDept($deptID) {
		return $this->dao->select('a.*, b.name as parentName, c.classname')
				->from(TABLE_DEPT)->alias('a')
				->leftJoin(TABLE_DEPT)->alias('b')->on('a.parent=b.id')
				->leftJoin(TABLE_KEVIN_USER_CLASS)->alias('c')
				->on('a.grade=c.id')
				->where('a.id')->eq($deptID)
				->fetch();
	}

	/**
	 * Get by idList.
	 * 
	 * @param  array    $classIDList 
	 * @access public
	 * @return array
	 */
	public function getDeptByIdList($deptIDList) {
		return $this->dao->select('*')->from(TABLE_DEPT)->where('id')->in($deptIDList)->fetchAll('id');
	}

	/**
	 * Get classList.
	 * 
	 * @param  object $pager
	 * @param  array $filter 
	 * @access public
	 * @return array
	 */
	public function getDeptList($orderBy, $pager, $filter) {
		$deptlist = null;
		if (empty($filter)) {
			$deptlist = $this->dao->select('a.id,a.name,a.parent,a.path,  a.order, a.manager, a.email, a.code, b.name as parentName, c.realname')
				->from(TABLE_DEPT)->alias('a')
				->leftJoin(TABLE_DEPT)->alias('b')->on('a.parent=b.id')
				->leftJoin(TABLE_USER)->alias('c')
				->on('a.manager=c.account')
				->where('a.deleted')->eq(0)
				->orderBy($orderBy)
				->page($pager)
				->fetchAll();
		} else {
			$path		 = !empty($filter['path']) ? "%," . $filter['path'] . ",%" : '';
			$manager = $filter['manager'];
			$deptlist	 = $this->dao->select('a.id,a.name,a.parent, a.path, a.order, a.manager, a.email, a.code,a.group, b.name as parentName, c.realname')
				->from(TABLE_DEPT)->alias('a')
				->leftJoin(TABLE_DEPT)->alias('b')->on('a.parent=b.id')
				->leftJoin(TABLE_USER)->alias('c')
				->on('a.manager=c.account')
				->where(true)
				->beginIF(empty($filter['deleted']))->andWhere('a.deleted')->eq(0)->FI()
				->beginIF(!empty($filter['deleted']))->andWhere('a.deleted')->eq($filter['deleted'])->FI()
				->beginIF(!empty($filter['manager']))->andWhere('a.manager')->like("%$manager%")->FI()
				->beginIF(!empty($filter['group']))->andWhere('a.group')->ne('')->FI()
				->beginIF(!empty($filter['path']))->andWhere('a.path')->like($path)->FI()
				->orderBy($orderBy)
				->page($pager)
				->fetchAll('id');
		}
		return $deptlist;
	}


	
	public function getDeptArray() {
		$depts = $this->dao->select('id,name')->from(TABLE_DEPT)
			->where('id')->ne(0)
			->fetchPairs('id', 'name');
		if (empty($depts))
			return array();
		return $depts;
	}

	/**
	 * Get group by account.
	 * 
	 * @param  string    $account 
	 * @access public
	 * @return array
	 */
	public function getGroupByDept($dept) {
		return $this->dao->select('t2.*')->from(TABLE_DEPT)->alias('t1')
				->leftJoin(TABLE_GROUP)->alias('t2')
				->on('t1.group = t2.id')
				->where('t1.id')->eq($dept)
				->fetchAll('id');
	}

	/**
	 * Get record by id.
	 * 
	 * @param  int $id
	 * @access public
	 * @return array
	 */
	public function getRecord($id) {
		$class = $this->dao->select('a.*, b.classname,u.dept,u.worktype,d.name')
			->from(TABLE_KEVIN_USER_RECORD)->alias('a')
			->leftJoin(TABLE_KEVIN_USER_CLASS)->alias('b')
			->on('a.class=b.id')
			->leftJoin(TABLE_USER)->alias('u')
			->on('a.account=u.account')
			->leftJoin(TABLE_DEPT)->alias('d')
			->on('u.dept=d.id')
			->where('a.id')->eq($id)
			->fetch();
		return $class;
	}

	/**
	 * Get records by idList.
	 * 
	 * @param  array    $recordIDList 
	 * @access public
	 * @return array
	 */
	public function getRecordByIdList($recordIDList) {
		return $this->dao->select('*')->from(TABLE_KEVIN_USER_RECORD)->where('id')->in($recordIDList)->fetchAll('id');
	}

	/**
	 * Get recordList.
	 * 
	 * @param  object $pager
	 * @param  array $filter 
	 * @access public
	 * @return array
	 */
	public function getRecordList($orderBy, $pager, $filter) {
		$classlist = null;
		if (empty($filter)) {
			$classlist = $this->dao->select('a.*, b.classname,u.realname, u.dept,u.worktype,d.name')
				->from(TABLE_KEVIN_USER_RECORD)->alias('a')
				->leftJoin(TABLE_KEVIN_USER_CLASS)->alias('b')
				->on('a.class=b.id')
				->leftJoin(TABLE_USER)->alias('u')
				->on('a.account=u.account')
				->leftJoin(TABLE_DEPT)->alias('d')
				->on('u.dept=d.id')
				->where('a.deleted')->eq(0)
				->orderBy($orderBy)
				->page($pager)
				->fetchAll();
		} else {
			$account = $filter['account'];
			$classlist = $this->dao->select('a.*, b.classname,u.realname, u.dept,u.worktype,d.name')
				->from(TABLE_KEVIN_USER_RECORD)->alias('a')
				->leftJoin(TABLE_KEVIN_USER_CLASS)->alias('b')
				->on('a.class=b.id')
				->leftJoin(TABLE_USER)->alias('u')
				->on('a.account=u.account')
				->leftJoin(TABLE_DEPT)->alias('d')
				->on('u.dept=d.id')
				->where(true)
				->beginIF(empty($filter['deleted']))->andWhere('a.deleted')->eq(0)->FI()
				->beginIF(!empty($filter['deleted']))->andWhere('a.deleted')->eq($filter['deleted'])->FI()
				->beginIF(!empty($filter['account']))->andWhere('a.account')->like("%$account%")->FI()
				->beginIF(!empty($filter['dept']))->andWhere('u.dept')->eq($filter['dept'])->FI()
				->beginIF(!empty($filter['class']))->andWhere('a.class')->eq($filter['class'])->FI()
				->orderBy($orderBy)
				->page($pager)
				->fetchAll();
		}

		return $classlist;
	}

	/**
	 * Get recordList in recycle.
	 * 
	 * @param  object $pager
	 * @access public
	 * @return array
	 */
	public function getRecordRecycleList($pager) {
		$classlist = $this->dao->select('*')
			->from(TABLE_KEVIN_USER_RECORD)
			->where('deleted')->eq(1)
			->orderBy('id asc')
			->page($pager)
			->fetchAll();
		return $classlist;
	}

	/**
	 * Get role List.
	 * 
	 * @access public
	 * @return array
	 */
	public function getRoleList() {
		return $this->dao->select('code, titleCN')->from(TABLE_KEVINCLASS_ITEM)
				->where('parent')->eq($this->config->kevinuser->classID['role'])
				->orderBy('order')
				->fetchPairs('code', 'titleCN');
	}
	
	/**
	 * Get user info by ID.
	 *
	 * @param  int    $userID
	 * @access public
	 * @return object|bool
	 */
	public function getById($userID, $field = 'account')
	{
		$user = $this->dao->select('*')->from(TABLE_USER)->where($field)->eq($userID)->fetch();
		if(!$user) return false;
		$user->last = date(DT_DATETIME1, $user->last);
		return $user;
	}

	/**
	 * Batch delete record. 
	 * 
	 * @param array $recordIDList
	 * @access public
	 * @return void
	 */
	public function recordBatchDelete($recordIDList) {
		$this->dao->update(TABLE_KEVIN_USER_RECORD)->set('deleted')->eq(1)->where('id')->in($recordIDList)->exec();
	}

	/**
	 * Batch update. 
	 * 
	 * @access public
	 * @return array
	 */
	public function recordBatchUpdate() {
		$records	 = array();
		$allChanges	 = array();
		$data		 = fixer::input('post')->get();
		$oldRecords	 = $this->getRecordByIdList($this->post->recordIDList);
		foreach ($data->recordIDList as $recordID) {
			$records[$recordID]			 = new stdClass();
			$records[$recordID]->account = $data->account[$recordID];
			$records[$recordID]->class	 = $data->class[$recordID];
			$records[$recordID]->start	 = $data->start[$recordID];
			$records[$recordID]->end	 = $data->end[$recordID];
		}

		foreach ($records as $recordID => $record) {
			$oldRecord = $oldRecords[$recordID];
			$item['account'] = $record->account;
			$item['class']	 = $record->class;
			$item['start']	 = $record->start;
			$item['end']	 = $record->end;
			$this->checkUnique($item, TABLE_KEVIN_USER_RECORD, 'account,class,start,end', $recordID);
			$this->dao->update(TABLE_KEVIN_USER_RECORD)->data($record)
				->autoCheck()
				->check('account', 'notempty')
				->check('class', 'notempty')
				->check('end', 'gt', $record->start)
				->check('start', 'gt', date('Y-m-d', 0))
				->check('end', 'gt', date('Y-m-d', 0))
				->where('id')->eq($recordID)
				->andWhere('locked')->eq('draft')
				->limit(1)
				->exec();

			if (dao::isError()) die(js::error('recordBatchUpdate#' . $recordID . dao::getError(true)));
			$allChanges[$recordID] = common::createChanges($oldRecord, $record);
		}
		return $allChanges;
	}

	/**
	 * Create record. 
	 * 
	 * @access public
	 * @return int
	 */
	public function recordCreate() {
		$record		 = fixer::input('post')->get();
		
		$item['account'] = $record->account;
		$item['class']	 = $record->class;
		$item['start']	 = $record->start;
		$item['end']	 = $record->end;
		$this->checkUnique($item, TABLE_KEVIN_USER_RECORD, 'account,class,start,end');
		
		$oldRecord	 = $this->dao->select('*')->from(TABLE_KEVIN_USER_RECORD)
			->where('account')->eq($record->account)
			->andWhere('locked')->eq('draft')
			->orderBy('id desc')
			->fetch();
		if ($oldRecord) {
			if($oldRecord->start>=$record->start)
				return 'startDataError';
			$month			 = date('Y-m', strtotime($record->start)) . '-01';
			$oldRecord->end	 = date('Y-m', strtotime('-1 month', strtotime($record->start))) . '-' . date('d', strtotime('-1 day', strtotime($month)));
			
			$item['account'] =  $oldRecord->account;
			$item['class'] =  $oldRecord->class;
			$item['start'] =  $oldRecord->start;
			$item['end'] =  $oldRecord->end;
			$this->checkUnique($item, TABLE_KEVIN_USER_RECORD, 'account,class,start,end', $oldRecord->id);
			$oldRecord->locked =  'lock';
			$this->dao->update(TABLE_KEVIN_USER_RECORD)
					->data($oldRecord)
					->check('account', 'notempty')
					->check('class', 'notempty')
					->check('end', 'gt', $oldRecord->start)
					->check('start', 'gt', date('Y-m-d', 0))
					->check('end', 'gt', date('Y-m-d', 0))
					->where('id')
					->eq($oldRecord->id)
					->exec();
		}

		
		$this->dao->insert(TABLE_KEVIN_USER_RECORD)->data($record)
			->autoCheck()
			->check('account', 'notempty')
			->check('class', 'notempty')
			->check('end', 'gt', $record->start)
			->check('start', 'gt', date('Y-m-d', 0))
			->check('end', 'gt', date('Y-m-d', 0))
			->exec();
		$id = $this->dbh->lastInsertID();
		return $id;
	}

	/**
	 * Delete a record.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function recordDelete($id) {
		$this->dao->update(TABLE_KEVIN_USER_RECORD)
			->set('deleted')
			->eq(1)
			->where('id')
			->eq($id)
			->exec();
	}

	/**
	 * Delete a record in recycle.
	 *
	 * @param  int    $id
	 * @access public
	 * @return void
	 */
	public function recordDeleteRecycle($id) {
		$this->dao->delete()->from(TABLE_KEVIN_USER_RECORD)->where('id')->eq($id)->exec();
	}

	/**
	 * restore a record in recycle.
	 *
	 * @param  int    $id
	 * @access public
	 * @return void
	 */
	public function recordUndelete($id) {
		$this->dao->update(TABLE_KEVIN_USER_RECORD)
			->set('deleted')
			->eq(0)
			->where('id')
			->eq($id)
			->exec();
	}

	/**
	 * Update record.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return array
	 */
	public function recordUpdate($id) {
		$allChanges	 = array();
		$oldRecord	 = $this->getRecord($id);
		if($oldRecord->locked == 'lock') return 'lock';
		$data			 = fixer::input('post')->get();
		$item['account'] = $data->account;
		$item['class']	 = $data->class;
		$item['start']	 = $data->start;
		$item['end']	 = $data->end;
		$this->checkUnique($item, TABLE_KEVIN_USER_RECORD, 'account,class,start,end', $id);
		$this->dao->update(TABLE_KEVIN_USER_RECORD)
			->data($data)
			->autoCheck()
			->check('account', 'notempty')
			->check('class', 'notempty')
			->check('end', 'gt', $data->start)
			->check('start', 'gt', date('Y-m-d', 0))
			->check('end', 'gt', date('Y-m-d', 0))
			->where('id')->eq($id)
			->andWhere('locked')->eq('draft')
			->exec();
		$allChanges[$id] = common::createChanges($oldRecord, $data);
		return $allChanges;
	}
	
	/**
	 * Update a user.
	 *
	 * @param  int    $userID
	 * @access public
	 * @return void
	 */
	public function update($userID)
	{
		if(!$this->checkPassword(true)) return;
		
		$oldUser = $this->getById($userID, 'id');
		
		$userID = $oldUser->id;
		$user = fixer::input('post')
			->setDefault('join', '0000-00-00')
			->setIF($this->post->password1 != false, 'password', md5($this->post->password1))
			->remove('password1, password2, groups,verifyPassword')
			->get();
		
		if(isset($this->config->safe->mode) and isset($user->password) and $this->computePasswordStrength($this->post->password1) < $this->config->safe->mode)
		{
			dao::$errors['password1'][] = $this->lang->kevinuser->weakPassword;
			return false;
		}
		
		if(empty($_POST['verifyPassword']) or md5($this->post->verifyPassword) != $this->app->user->password)
		{
			dao::$errors['verifyPassword'][] = $this->lang->kevinuser->error->verifyPassword;
			return false;
		}
		
		$this->dao->update(TABLE_USER)->data($user)
			->autoCheck()
			->batchCheck($this->config->user->edit->requiredFields, 'notempty')
			->check('account', 'unique', "id != '$userID'")
			->check('account', 'account')
			->checkIF($this->post->email != false, 'email', 'email')
			->where('id')->eq((int)$userID)
			->exec();
		
		/* If account changed, update the privilege. */
		if($this->post->account != $oldUser->account)
		{
			$this->dao->update(TABLE_USERGROUP)->set('account')->eq($this->post->account)->where('account')->eq($oldUser->account)->exec();
			if(strpos($this->app->company->admins, ',' . $oldUser->account . ',') !== false)
			{
				$admins = str_replace(',' . $oldUser->account . ',', ',' . $this->post->account . ',', $this->app->company->admins);
				$this->dao->update(TABLE_COMPANY)->set('admins')->eq($admins)->where('id')->eq($this->app->company->id)->exec();
				if(!dao::isError()) $this->app->user->account = $this->post->account;
			}
		}
		
		if(isset($_POST['groups']))
		{
			$this->dao->delete()->from(TABLE_USERGROUP)->where('account')->eq($this->post->account)->exec();
			foreach($this->post->groups as $groupID)
			{
				$data          = new stdclass();
				$data->account = $this->post->account;
				$data->group   = $groupID;
				$this->dao->replace(TABLE_USERGROUP)->data($data)->exec();
			}
		}
		if(!empty($user->password) and $user->account == $this->app->user->account) $this->app->user->password = $user->password;
		
		if(!dao::isError())
		{
			$this->loadModel('mail');
			if($this->config->mail->mta == 'sendcloud' and $user->email != $oldUser->email)
			{
				$this->mail->syncSendCloud('delete', $oldUser->email);
				$this->mail->syncSendCloud('sync', $user->email, $user->realname);
			}
		}
	}

	public function userbatchedit() {
		$oldUsers		 = $this->dao->select('id, account')->from(TABLE_USER)->where('id')->in(array_keys($this->post->account))->fetchPairs('id', 'account');
		$accountGroup	 = $this->dao->select('id, account')->from(TABLE_USER)->where('account')->in($this->post->account)->fetchGroup('account', 'id');
		
		$accounts = array();
		foreach ($this->post->account as $id => $account) {
			$users[$id]['account']		 = $account;
			$users[$id]['code']			 = $this->post->code[$id];
			$users[$id]['realname']		 = $this->post->realname[$id];
			$users[$id]['email']		 = $this->post->email[$id];
			$users[$id]['dept']			 = $this->post->dept[$id] == 'ditto' ? (isset($prev['dept']) ? $prev['dept'] : 0) : $this->post->dept[$id];
			$users[$id]['deptdispatch']	 = $this->post->deptdispatch[$id] == 'ditto' ? (isset($prev['dept']) ? $prev['dept'] : 0) : $this->post->deptdispatch[$id];
			
			
			if (isset($accountGroup[$account]) and count($accountGroup[$account]) > 1)
				die(js::error(sprintf($this->lang->user->error->accountDupl, $id)));
			if (in_array($account, $accounts))
				die(js::error(sprintf($this->lang->user->error->accountDupl, $id)));
			if (!validater::checkAccount($users[$id]['account']))
				die(js::error(sprintf($this->lang->user->error->account, $id)));
			if ($users[$id]['realname'] == '')
				die(js::error(sprintf($this->lang->user->error->realname, $id)));
			if ($users[$id]['email'] and ! validater::checkEmail($users[$id]['email']))
				die(js::error(sprintf($this->lang->user->error->mail, $id)));
			
			$accounts[$id]	 = $account;
			$prev['dept']	 = $users[$id]['dept'];
		}
		
		foreach ($users as $id => $user) {
			$this->dao->update(TABLE_USER)->data($user)->where('id')->eq((int) $id)->exec();
			if ($user['account'] != $oldUsers[$id]) {
				$oldAccount = $oldUsers[$id];
				$this->dao->update(TABLE_USERGROUP)->set('account')->eq($user['account'])->where('account')->eq($oldAccount)->exec();
				if (strpos($this->app->company->admins, ',' . $oldAccount . ',') !== false) {
					$admins = str_replace(',' . $oldAccount . ',', ',' . $user['account'] . ',', $this->app->company->admins);
					$this->dao->update(TABLE_COMPANY)->set('admins')->eq($admins)->where('id')->eq($this->app->company->id)->exec();
				}
				if (!dao::isError() and $this->app->user->account == $oldAccount)
					$this->app->user->account = $users['account'];
			}
		}
	}
	
}
