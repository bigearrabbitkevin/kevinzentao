<?php

/**
 * The kevindeviceModel
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevindevice
 */
?>
<?php

class kevindeviceModel extends model {
	
	public function checkTime($time,$type){
		$year=date('Y',strtotime($time));
		$month=date('m',strtotime($time));
		return $this->dao->select('*')->from($this->config->objectTables[$type])->where('deleted')->eq(0)->andWhere('year(time)')->eq($year)->andWhere('month(time)')->eq($month)->fetch();
	}
	
	/**
     * 创建随机的字符串。
     * Create random string. 
     * 
     * @param  int    $length 
     * @param  string $skip A-Z|a-z|0-9
     * @static
     * @access public
     * @return void
     */
    public function createRandomStr($length, $skip = '')
    {
        $str  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $skip = str_replace('A-Z', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', $skip);
        $skip = str_replace('a-z', 'abcdefghijklmnopqrstuvwxyz', $skip);
        $skip = str_replace('0-9', '0123456789', $skip);
        for($i = 0; $i < strlen($skip); $i++)
        {
            $str = str_replace($skip[$i], '', $str);
        }

        $strlen = strlen($str);
        while($length > strlen($str)) $str .= $str;

        $str = str_shuffle($str); 
        return substr($str,0,$length); 
    }
	
	public function devalchk(&$dev,$title,&$users){
		$deval='';
		if($title=='desc')$deval=$dev->description;
		elseif($title=='group')$deval=$dev->groupName;
		elseif($title=='dept')$deval=$dev->deptName;
		elseif($title=='type')$deval=$this->lang->kevindevice->DevTypeList[$dev->$title];
		elseif($title=='status')$deval=$this->lang->kevindevice->StatusList[$dev->$title];
		elseif($title=='user'){
			if($dev->$title){
				$enuser=str_split($dev->$title);
				$deval=strtoupper($enuser[0]).':'.$dev->$title;
			}
		}elseif($title=='charge'){
			if(isset($users[$dev->charge]))$deval=$users[$dev->charge];
		}elseif($title=='count'){
			if($dev->$title)$deval=$dev->$title;
		}elseif($title=='join'||$title=='dieDate'||$title=='repairstart'||$title=='repairend'){
			if($dev->$title!="0000-00-00")$deval=$dev->$title;
		}else{
			$deval=$dev->$title;
		}
		return $deval;
	}
	
	/**
	 * Create a user.
	 * 
	 * @access public
	 * @return void
	 */
	public function devcreate() {
		$device			 = fixer::input('post')
			->setDefault('join', '0000-00-00')
			->get();
		if (!$device->group) $device->group	 = 0;

		$this->dao->insert(TABLE_KEVINDEVICE_DEVLIST)->data($device)
			->autoCheck()
			->batchCheck($this->config->kevindevice->devcreate->requiredFields, 'notempty')
			->check('nametype', 'unique')
			->exec();
		if (dao::isError()) return;
	}

	/**
	 * Delete a user.
	 * 
	 * @param  int    $deviceid 
	 * @access public
	 * @return void
	 */
	public function devdelete($deviceid) {
		$this->dao->update(TABLE_KEVINDEVICE_DEVLIST)->set('deleted = 1')
			->where('id')->eq((int) $deviceid)
			->exec();
		//$this->dao->delete()->from(TABLE_KEVINDEVICE_DEVLIST)->where('id')->eq($deviceid)->exec();
		//$this->dao->delete()->from(TABLE_KEVINDEVICE_GROUPLIST)->where('`user`')->eq($deviceid)->exec();
	}

	/**
	 * Get user info by ID.
	 * 
	 * @param  int    $deviceid 
	 * @access public
	 * @return object|bool
	 */
	public function devGetById($deviceid) {
		$device = $this->dao->select('*')->from(TABLE_KEVINDEVICE_DEVLIST)->where('id')->eq($deviceid)->fetch();
		return $device;
	}

	public function devStatusCheck() {
		$dismatches	 = $this->dao->select('a.id,a.status,a.group,b.type')->from(TABLE_KEVINDEVICE_DEVLIST)->alias('a')
			->leftJoin(TABLE_KEVINDEVICE_GROUP)->alias('b')->on('a.group = b.id')
			->where('a.status')->eq('discard')
			->orWhere('b.type')->eq('discard')
			->fetchAll();
		$group		 = $this->dao->select('id')->from(TABLE_KEVINDEVICE_GROUP)->where('type')->eq('discard')->fetch();
		foreach ($dismatches as $dismatch) {
			if ($dismatch->status != $dismatch->type) {
				if ($dismatch->status == 'discard') $this->dao->update(TABLE_KEVINDEVICE_DEVLIST)->set('group')->eq((int) $group->id)
						->where('id')->eq($dismatch->id)->andWhere('deleted')->eq(0)
						->exec();
				elseif ($dismatch->type == 'discard') $this->dao->update(TABLE_KEVINDEVICE_DEVLIST)->set('status')->eq('normal')
						->where('id')->eq($dismatch->id)->andWhere('deleted')->eq(0)
						->exec();
			}
		}
	}

	/**
	 * Get devices by sql.
	 * 
	 * @param  int    $query 
	 * @param  int    $pager 
	 * @access public
	 * @return void
	 */
	public function devGetByQuery($groupID = 0, $pager = null, $orderBy = 'id') {
		$this->devStatusCheck();
		if ($groupID) {
			return $this->dao->select('a.*,b.name as groupName,c.name as deptName')
					->from(TABLE_KEVINDEVICE_DEVLIST)->alias('a')
					->leftJoin(TABLE_KEVINDEVICE_GROUP)->alias('b')->on('a.group = b.id')
					->leftJoin(TABLE_DEPT)->alias('c')->on('a.dept = c.id')
					->where('a.group')->eq((int) $groupID)
					->orderBy($orderBy)
					->page($pager)
					->fetchAll();
		}
		//no group select all
		return $this->dao->select('a.*,b.name as groupName,c.name as deptName')->from(TABLE_KEVINDEVICE_DEVLIST)->alias('a')
				->leftJoin(TABLE_KEVINDEVICE_GROUP)->alias('b')->on('a.group = b.id')
				->leftJoin(TABLE_DEPT)->alias('c')->on('a.dept = c.id')
				->orderBy($orderBy)
				->page($pager)
				->fetchAll();
	}

	/**
	 * Get devices by sql.
	 * 
	 * @param  int    $query 
	 * @param  int    $pager 
	 * @access public
	 * @return void
	 */
	public function devGetByGroup($groupID = 0, $orderBy = 'name') {
		if ($groupID) {
			return $this->dao->select('*')
					->from(TABLE_KEVINDEVICE_DEVLIST)
					->where('`group`')->eq((int) $groupID)
					->andWhere('deleted')->eq(0)
					->orderBy($orderBy)
					->fetchAll();
		}
		return null;
	}

	/**
	 * Get user pairs of a group.
	 * 
	 * @param  int    $groupID 
	 * @access public
	 * @return array
	 */
	public function devGetPairs($groupID = 0) {
		if (0 == $groupID) {
			return $this->dao->select('id,name')
					->from(TABLE_KEVINDEVICE_DEVLIST)
					->Where('deleted')->eq(0)
					->orderBy('name')
					->fetchPairs();
		}
		return $this->dao->select('id,name')
				->from(TABLE_KEVINDEVICE_DEVLIST)
				->where('`group`')->eq((int) $groupID)
				->andWhere('deleted')->eq(0)
				->orderBy('name')
				->fetchPairs();
	}

	/**
	 * Update a user.
	 * 
	 * @param  int    $deviceid 
	 * @access public
	 * @return void
	 */
	public function devupdate($deviceid) {
		$oldUser	 = $this->devGetById($deviceid);
		if (!$oldUser) return;
		$deviceid	 = (int) $deviceid;
		$device		 = fixer::input('post')
			->setDefault('join', '0000-00-00')
			->get();

		if (!$device->group) $device->group = 0;
		$this->dao->update(TABLE_KEVINDEVICE_DEVLIST)->data($device)
			->autoCheck()
			->batchCheck($this->config->kevindevice->devedit->requiredFields, 'notempty')
			->where('id')->eq((int) $deviceid)
			->exec();
		if (dao::isError()) return;
	}

	/**
	 * Create a group.
	 * 
	 * @access public
	 * @return bool
	 */
	public function groupcreate() {
		$group = fixer::input('post')->get();
		return $this->dao->insert(TABLE_KEVINDEVICE_GROUP)->data($group)->batchCheck($this->config->kevindevice->groupcreate->requiredFields, 'notempty')->exec();
	}

	/**
	 * Copy a group.
	 * 
	 * @param  int    $groupID 
	 * @access public
	 * @return void
	 */
	public function groupcopy($groupID) {
		$group = fixer::input('post')->remove('options')->get();
		$this->dao->insert(TABLE_KEVINDEVICE_GROUP)->data($group)->check('name', 'unique')->check('name', 'notempty')->exec();
		if ($this->post->options == false) return;
		if (!dao::isError()) {
			$newGroupID	 = $this->dao->lastInsertID();
			$options	 = join(',', $this->post->options);
			if (strpos($options, 'copyPriv') !== false) $this->copyPriv($groupID, $newGroupID);
			if (strpos($options, 'copyUser') !== false) $this->copyUser($groupID, $newGroupID);
		}
	}

	/**
	 * Delete a group.
	 * 
	 * @param  int    $groupID 
	 * @param  null   $null      compatible with that of model::delete()
	 * @access public
	 * @return void
	 */
	public function groupdelete($groupID, $null = null) {
		$this->dao->update(TABLE_KEVINDEVICE_GROUP)->set('deleted = 1')
			->where('id')->eq((int) $groupID)
			->exec();
		//$this->dao->delete()->from(TABLE_KEVINDEVICE_GROUP)->where('id')->eq($groupID)->exec();
	}

	/**
	 * Get group by id.
	 * 
	 * @param  int    $groupID 
	 * @access public
	 * @return object
	 */
	public function groupGetById($groupID) {
		return $this->dao->findById($groupID)->from(TABLE_KEVINDEVICE_GROUP)->fetch();
	}

	/**
	 * Get group by userID.
	 * 
	 * @param  int    $deviceid 
	 * @access public
	 * @return array
	 */
	public function groupGetByDevice($deviceid) {
		return $this->dao->select('a.*')->from(TABLE_KEVINDEVICE_GROUP)->alias('a')
				->leftJoin(TABLE_KEVINDEVICE_DEVLIST)->alias('b')
				->on('a.id = b.group')
				->where('b.id')->eq($deviceid)
				->fetchAll('id');
	}

	/**
	 * Get group lists.
	 * 
	 * @access public
	 * @return array
	 */
	public function groupGetList() {
		return $this->dao->select('*')->from(TABLE_KEVINDEVICE_GROUP)->orderBy('id')->fetchAll();
	}

	/**
	 * Update a group.
	 * 
	 * @param  int    $groupID 
	 * @access public
	 * @return void
	 */
	public function groupUpdate($groupID) {
		$group = fixer::input('post')->get();
		return $this->dao->update(TABLE_KEVINDEVICE_GROUP)->data($group)
				->batchCheck($this->config->kevindevice->nameRequire->requiredFields, 'notempty')
				->where('id')->eq($groupID)->exec();
	}

	/**
	 * Get kevindevice pairs.
	 * 
	 * @access public
	 * @return array
	 */
	public function groupGetPairs() {
		return $this->dao->select('id, name')->from(TABLE_KEVINDEVICE_GROUP)->fetchPairs();
	}

	/**
	 * Update devices.
	 * 
	 * @param  int    $groupID 
	 * @access public
	 * @return void
	 */
	public function groupUpdateUser($groupID) {
		/* Delete old. */
		$this->dao->delete()->from(TABLE_KEVINDEVICE_GROUPLIST)->where('`group`')->eq($groupID)->exec();

		/* Insert new. */
		if ($this->post->members == false) return;
		foreach ($this->post->members as $userid) {
			$data		 = new stdclass();
			$data->user	 = $userid;
			$data->group = $groupID;
			$this->dao->insert(TABLE_KEVINDEVICE_GROUPLIST)->data($data)->exec();
		}
	}
	
	public function maintainCreate(){
		$item = fixer::input('post')->get();
		$chk=$this->checkTime($item->time,'kevindevice_maintainlist');
		if($chk)dao::$errors['maintain'][]='Month is exist!';
		$this->dao->insert(TABLE_KEVINDEVICE_MAINTAINLIST)->data($item)
			->autoCheck()
			->batchCheck($this->config->kevindevice->maintaincreate->requiredFields, 'notempty')
			->exec();
	}
	
	public function maintainDelete($maintainID){
		$this->dao->update(TABLE_KEVINDEVICE_MAINTAINLIST)->set('deleted')->eq(1)
			->where('id')->eq((int)$maintainID)
			->exec();
	}

	/**
	 * Get user info by ID.
	 * 
	 * @param  int    $deviceid 
	 * @access public
	 * @return object|bool
	 */
	public function maintainGetById($maintainID) {
		$maintain = $this->dao->select('*')->from(TABLE_KEVINDEVICE_MAINTAINLIST)->where('id')->eq($maintainID)->fetch();
		return $maintain;
	}
	
	/**
	 * Get maintains by sql.
	 * 
	 * @param  int    $query 
	 * @param  int    $pager 
	 * @access public
	 * @return void
	 */
	public function maintainGetByQuery($year = 0,$pager=null,$orderBy) {
		return $this->dao->select('*,log+platform as total')
					->from(TABLE_KEVINDEVICE_MAINTAINLIST)
					->where('deleted')->eq(0)
					->beginIF($year)->andWhere('year(time)')->eq((int)$year)->fi()
					->orderBy($orderBy)
					->page($pager)
					->fetchAll();
	}
	
	public function maintainUpdate($maintainID){
		$olditem=$this->maintainGetById($maintainID);
		if(!$olditem)return false;
		$item = fixer::input('post')->get();
		$chk=$this->checkTime($item->time,'kevindevice_maintainlist');
		if($chk&&$chk->id!=$maintainID)dao::$errors['maintain'][]='Month is exist!';
		$this->dao->update(TABLE_KEVINDEVICE_MAINTAINLIST)->data($item)
			->autoCheck()
			->batchCheck($this->config->kevindevice->maintainedit->requiredFields, 'notempty')
			->where('id')->eq($maintainID)
			->exec();
	}
	
	/**
	 * Get maintains by sql.
	 * 
	 * @param  int    $query 
	 * @param  int    $pager 
	 * @access public
	 * @return void
	 */
	public function maintainYearGet() {
		return $this->dao->select('distinct year(time),year(time)')
					->from(TABLE_KEVINDEVICE_MAINTAINLIST)
					->where('deleted')->eq(0)
					->orderBy('time desc')
					->fetchPairs();
	}
		/**
	 * Create a skemitter lettering.
	 * 
	 * @access public
	 * @return void
	 */
	public function sendoutCreate() {
		$item = fixer::input('post')->get();
		$chk=$this->checkTime($item->time,'kevindevice_sendoutlist');
		if($chk)die(js::alert ("Month is exist!"));
		$this->dao->insert(TABLE_KEVINDEVICE_SENDOUTLIST)->data($item)
			->autoCheck()
			->batchCheck($this->config->kevindevice->sendoutcreate->requiredFields, 'notempty')
			->exec();
		return $this->dao->lastInsertID();
	}
	
	public function sendoutGetById($id){
		return $this->dao->select('*')->from(TABLE_KEVINDEVICE_SENDOUTLIST)->where('id')->eq($id)->fetch();
	}
		
	/**
	 * Get sendouts by sql.
	 * 
	 * @param  int    $query 
	 * @param  int    $pager 
	 * @access public
	 * @return void
	 */
	public function sendoutGetByQuery($year = 0,$pager=null,$orderBy) {
		return $this->dao->select('*')
					->from(TABLE_KEVINDEVICE_SENDOUTLIST)
					->where('deleted')->eq(0)
					->beginIF($year)->andWhere('year(time)')->eq((int)$year)->fi()
					->orderBy($orderBy)
					->page($pager)
					->fetchAll();
	}
	
	public function sendoutGetList($orderBy,$pager){
		return $this->dao->select('*')->from(TABLE_KEVINDEVICE_SENDOUTLIST)
					->orderBy($orderBy)
					->page($pager)
					->fetchAll();
	}

	public function sendoutUpdate($id){
		$isexist=$this->sendoutGetById($id);
		if(!$isexist)die(js::alert ("Sendout ID is not exist!"));
		$item = fixer::input('post')->get();
		$chk=$this->checkTime($item->time,'kevindevice_sendoutlist');
		if($chk&&$id!=$chk->id)die(js::alert ("Month is exist!"));
		$this->dao->update(TABLE_KEVINDEVICE_SENDOUTLIST)->data($item)
			->autoCheck()
			->batchCheck($this->config->kevindevice->sendoutedit->requiredFields, 'notempty')
			->where('id')->eq($id)
			->exec();
	}
	
	/**
	 * Get maintains by sql.
	 * 
	 * @param  int    $query 
	 * @param  int    $pager 
	 * @access public
	 * @return void
	 */
	public function sendoutYearGet() {
		return $this->dao->select('distinct year(time),year(time)')
					->from(TABLE_KEVINDEVICE_SENDOUTLIST)
					->where('deleted')->eq(0)
					->orderBy('time desc')
					->fetchPairs();
	}
	
	/**
	 * Create a skemitter lettering.
	 * 
	 * @access public
	 * @return void
	 */
	public function spotCreate() {
		$item = fixer::input('post')->get();
		$isrepeat=$this->spotGetByName($item->name);
		if($isrepeat)die(js::alert ("Check spot is exist!"));
		$this->dao->insert(TABLE_KEVINDEVICE_SPOTCHKLIST)->data($item)
			->autoCheck()
			->batchCheck($this->config->kevindevice->spotchkcreate->requiredFields, 'notempty')
			->exec();
		return $this->dao->lastInsertID();
	}
	
	public function spotGetById($id){
		return $this->dao->select('*')->from(TABLE_KEVINDEVICE_SPOTCHKLIST)->where('id')->eq($id)->fetch();
	}
	
	public function spotGetByName($name){
		return $this->dao->select('*')->from(TABLE_KEVINDEVICE_SPOTCHKLIST)->where('name')->eq($name)->fetch();
	}
	
	public function spotGetList($orderBy,$pager){
		return $this->dao->select('*')->from(TABLE_KEVINDEVICE_SPOTCHKLIST)
					->orderBy($orderBy)
					->page($pager)
					->fetchAll();
	}

	public function spotUpdate($id){
		$isexist=$this->spotGetById($id);
		if(!$isexist)die(js::alert ("Spot check ID is not exist!"));
		$item = fixer::input('post')->get();
		$isrepeat=$this->spotGetByName($item->name);
		if($isrepeat&&$id!=$isrepeat->id)die(js::alert ("Name is exist!"));
		$this->dao->update(TABLE_KEVINDEVICE_SPOTCHKLIST)->data($item)
			->autoCheck()
			->batchCheck($this->config->kevindevice->spotchkedit->requiredFields, 'notempty')
			->where('id')->eq($id)
			->exec();
	}
	
	/**
	 * statistic By Group.
	 * 
	 * @access public
	 * @return array
	 */
	public function statisticByGroup() {
		return $this->dao->select('count(*) as deviceCount, a.`group`,b.name as groupName')
				->from(TABLE_KEVINDEVICE_DEVLIST)->alias('a')
				->leftJoin(TABLE_KEVINDEVICE_GROUP)->alias('b')->on('a.group = b.id')
				->where('a.deleted')->eq('0')
				->groupBy('`group`')
				->orderBy('group')
				->fetchAll();
	}

	/**
	 * statistic By dept.
	 * 
	 * @access public
	 * @return array
	 */
	public function statisticByDept() {
		return $this->dao->select('count(*) as deviceCount, a.`group`,b.name as groupName,b.id as groupID')
				->from(TABLE_KEVINDEVICE_DEVLIST)->alias('a')
				->leftJoin(TABLE_DEPT)->alias('b')->on('a.dept = b.id')
				->where('a.deleted')->eq('0')
				->groupBy('`dept`')
				->orderBy('dept')
				->fetchAll();
	}

	/**
	 * statistic By maintain.
	 * 
	 * @access public
	 * @return array
	 */
	public function statisticByMaintain($year) {
		if($year)return $this->dao->select('sum(platform+log) as deviceCount,month(time) as groupName')
					->from(TABLE_KEVINDEVICE_MAINTAINLIST)
					->where('year(time)')->eq((int)$year)
					->andWhere('deleted')->eq(0)
					->groupBy('groupName')
					->orderBy('groupName')
					->fetchAll(); 
		else return $this->dao->select('sum(platform+log) as deviceCount,year(time) as groupName')
					->from(TABLE_KEVINDEVICE_MAINTAINLIST)
					->where('deleted')->eq(0)
					->groupBy('groupName')
					->orderBy('groupName')
					->fetchAll(); 
	}
	
	/**
	 * statistic By sendout.
	 * 
	 * @access public
	 * @return array
	 */
	public function statisticBySendout($year) {
		if($year)return $this->dao->select('sum(total) as totalCount,sum(sendout) as sendoutCount,month(time) as groupName')
					->from(TABLE_KEVINDEVICE_SENDOUTLIST)
					->where('year(time)')->eq((int)$year)
					->andWhere('deleted')->eq(0)
					->groupBy('groupName')
					->orderBy('groupName')
					->fetchAll(); 
		else return $this->dao->select('sum(total) as totalCount,sum(sendout) as sendoutCount,year(time) as groupName')
					->from(TABLE_KEVINDEVICE_SENDOUTLIST)
					->where('deleted')->eq(0)
					->groupBy('groupName')
					->orderBy('groupName')
					->fetchAll(); 
	}
	
	/**
	 * statistic By dept.
	 * 
	 * @access public
	 * @return array
	 */
	public function statisticByType() {
		return $this->dao->select('count(*) as deviceCount, b.type as groupName')
				->from(TABLE_KEVINDEVICE_DEVLIST)->alias('a')
				->leftJoin(TABLE_KEVINDEVICE_GROUP)->alias('b')->on('a.group=b.id')
				->where('a.deleted')->eq('0')
				->groupBy('b.type')
				->orderBy('b.type')
				->fetchAll();
	}

	/**
	 * statistic By year.
	 * 
	 * @access public
	 * @return array
	 */
	public function statisticByYear() {
		$devyears	 = $this->dao->select('count(*) as deviceCount,a.join')
			->from(TABLE_KEVINDEVICE_DEVLIST)->alias('a')
			->leftJoin(TABLE_KEVINDEVICE_GROUP)->alias('b')->on('a.group=b.id')
			->where('b.type')->eq('station')
			->andwhere('a.deleted')->eq('0')
			->andwhere('b.deleted')->eq('0')
			->groupBy('a.join')
			->orderBy('a.join')
			->fetchAll();
		$countarray	 = array();
		$finalarray	 = array();
		foreach ($devyears as $devyear) {
			$yearlimit						 = time() - strtotime($devyear->join);
			$devyear->groupName				 = ceil($yearlimit / 60 / 60 / 24 / 365) - 1;
			if ($devyear->join == "0000-00-00") $devyear->groupName				 = 'unknown';
			if (isset($countarray[$devyear->groupName])) $countarray[$devyear->groupName]+=$devyear->deviceCount;
			else $countarray[$devyear->groupName] = $devyear->deviceCount;
		}
		foreach ($countarray as $grpname => $devcount) {
			$finalarray[$grpname]	 = new stdClass();
			$item					 = &$finalarray[$grpname];
			if ($grpname === 'unknown') $item->groupName		 = $this->lang->kevindevice->StatusList[$grpname];
			else $item->groupName		 = '满' . $grpname . '年';
			$item->deviceCount		 = $devcount;
		}
		return $finalarray;
	}

}
