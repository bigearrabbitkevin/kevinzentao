<?php

/**
 * The kevinstoreModel
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinstore
 */
?>
<?php

class kevinstoreModel extends model {

	/**
	 * Create a user.
	 * 
	 * @access public
	 * @return void
	 */
	public function itemcreate() {
		$item		 = fixer::input('post')
			->setDefault('join', '0000-00-00')
			->get();
		if (!$item->group) $item->group = 0;

		$this->dao->insert(TABLE_KEVINSTROE_ITEM)->data($item)
			->autoCheck()
			->batchCheck($this->config->kevinstore->itemcreate->requiredFields, 'notempty')
			->check('nametype', 'unique')
			->exec();
		if (dao::isError()) return;
	}

	/**
	 * Delete a user.
	 * 
	 * @param  string    $number 
	 * @access public
	 * @return void
	 */
	public function itemdelete($number) {
		$this->dao->update(TABLE_KEVINSTROE_ITEM)->set('deleted')->eq('1')
			->where('number')->eq($number)
			->exec();
	}

	/**
	 * Get user info by ID.
	 * 
	 * @param  string    $number 
	 * @access public
	 * @return object|bool
	 */
	public function itemGet($number) {
		$item = $this->dao->select('*')->from(TABLE_KEVINSTROE_ITEM)->where('number')->eq($number)->fetch();
		return $item;
	}

	/**
	 * Get itemList by sql.
	 * 
	 * @param  string    $query 
	 * @param  int    $pager 
	 * @access public
	 * @return void
	 */
	public function itemGetByQuery($group = '', $pager = null, $orderBy = 'number') {
		//$this->itemStatusCheck();
		return $this->dao->select('a.*')
				->from(TABLE_KEVINSTROE_ITEM)->alias('a')
				->where('a.deleted')->eq(0)
				->beginIF($group)->andWhere('a.group')->eq($group)->fi()
				->orderBy($orderBy)
				->page($pager)
				->fetchAll();
	}

	/**
	 * Get itemList by sql.
	 * 
	 * @param  int    $query 
	 * @param  int    $pager 
	 * @access public
	 * @return void
	 */
	public function itemGetByGroup($group = 0, $orderBy = 'name') {
		if ($group) {
			return $this->dao->select('*')
					->from(TABLE_KEVINSTROE_ITEM)
					->where('`group`')->eq((int) $group)
					->andWhere('deleted')->eq(0)
					->orderBy($orderBy)
					->fetchAll();
		}
		return null;
	}

	/**
	 * Get user pairs of a group.
	 * 
	 * @param  int    $group 
	 * @access public
	 * @return array
	 */
	public function itemGetPairs($group = 0) {
		if (0 == $group) {
			return $this->dao->select('id,name')
					->from(TABLE_KEVINSTROE_ITEM)
					->Where('deleted')->eq(0)
					->orderBy('name')
					->fetchPairs();
		}
		return $this->dao->select('id,name')
				->from(TABLE_KEVINSTROE_ITEM)
				->where('`group`')->eq((int) $group)
				->andWhere('deleted')->eq(0)
				->orderBy('name')
				->fetchPairs();
	}

	/**
	 * Update a user.
	 * 
	 * @param  char    $number 
	 * @access public
	 * @return void
	 */
	public function itemupdate($item) {
		if (!$item) {
			dao::$errors["itemupdate"][] = "can not find the item with number:$number.";
			return;
		}
		$itemPost					 = fixer::input('post')->get();
		$itemPost->lastEditedBy		 = $this->app->user->account;
		$itemPost->lastEditedDate	 = helper::now();
		if ($item->number != $itemPost->number) {
			$itemTarget = $this->itemGet($itemPost->number);
			if ($itemTarget) {
				dao::$errors["itemupdate"][] = "Exist the new number:$itemPost->number. Please set another number.";
				return;
			}
		} else {
			unset($itemPost->number);
		}

		$this->dao->update(TABLE_KEVINSTROE_ITEM)->data($itemPost)
			->autoCheck()
			->batchCheck($this->config->kevinstore->itemedit->requiredFields, 'notempty')
			->where('number')->eq($item->number)
			->exec();
	}

	/**
	 * Create a group.
	 * 
	 * @access public
	 * @return bool
	 */
	public function groupcreate() {
		$group = fixer::input('post')->get();
		return $this->dao->insert(TABLE_KEVINSTROE_GROUP)->data($group)->batchCheck($this->config->kevinstore->groupcreate->requiredFields, 'notempty')->exec();
	}

	/**
	 * Copy a group.
	 * 
	 * @param  int    $group 
	 * @access public
	 * @return void
	 */
	public function groupcopy($group) {
		$group = fixer::input('post')->remove('options')->get();
		$this->dao->insert(TABLE_KEVINSTROE_GROUP)->data($group)->check('name', 'unique')->check('name', 'notempty')->exec();
		if ($this->post->options == false) return;
		if (!dao::isError()) {
			$newgroup	 = $this->dao->lastInsertID();
			$options	 = join(',', $this->post->options);
			if (strpos($options, 'copyPriv') !== false) $this->copyPriv($group, $newgroup);
			if (strpos($options, 'copyUser') !== false) $this->copyUser($group, $newgroup);
		}
	}

	/**
	 * Delete a group.
	 * 
	 * @param  int    $group 
	 * @param  null   $null      compatible with that of model::delete()
	 * @access public
	 * @return void
	 */
	public function groupdelete($group, $null = null) {
		$this->dao->update(TABLE_KEVINSTROE_GROUP)->set('deleted = 1')
			->where('id')->eq((int) $group)
			->exec();
		//$this->dao->delete()->from(TABLE_KEVINSTROE_GROUP)->where('id')->eq($group)->exec();
	}

	/**
	 * Get group by id.
	 * 
	 * @param  int    $group 
	 * @access public
	 * @return object
	 */
	public function groupGetById($group) {
		return $this->dao->findById($group)->from(TABLE_KEVINSTROE_GROUP)->fetch();
	}

	/**
	 * Get group by userID.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return array
	 */
	public function groupGetBystore($id) {
		return $this->dao->select('a.*')->from(TABLE_KEVINSTROE_GROUP)->alias('a')
				->leftJoin(TABLE_KEVINSTROE_ITEM)->alias('b')
				->on('a.id = b.group')
				->where('b.id')->eq($id)
				->fetchAll('id');
	}

	/**
	 * Get group lists.
	 * 
	 * @access public
	 * @return array
	 */
	public function groupGetList() {
		return $this->dao->select('distinct `group` as id, `group` as name')->from(TABLE_KEVINSTROE_ITEM)->fetchAll();
	}

	/**
	 * Get group pairs.
	 * 
	 * @access public
	 * @return array
	 */
	public function groupGetPairs() {
		return $this->dao->select('distinct `group`')->from(TABLE_KEVINSTROE_ITEM)->fetchPairs();
	}

	/**
	 * Update a group.
	 * 
	 * @param  int    $group 
	 * @access public
	 * @return void
	 */
	public function groupUpdate($group) {
		$group = fixer::input('post')->get();
		return $this->dao->update(TABLE_KEVINSTROE_GROUP)->data($group)
				->batchCheck($this->config->kevinstore->nameRequire->requiredFields, 'notempty')
				->where('id')->eq($group)->exec();
	}

	/**
	 * Update stores.
	 * 
	 * @param  int    $group 
	 * @access public
	 * @return void
	 */
	public function groupUpdateUser($group) {
		/* Delete old. */
		$this->dao->delete()->from(TABLE_KEVINSTROE_GROUPLIST)->where('`group`')->eq($group)->exec();

		/* Insert new. */
		if ($this->post->members == false) return;
		foreach ($this->post->members as $userid) {
			$data		 = new stdclass();
			$data->user	 = $userid;
			$data->group = $group;
			$this->dao->insert(TABLE_KEVINSTROE_GROUPLIST)->data($data)->exec();
		}
	}

	/**
	 * Create a user.
	 * 
	 * @access public
	 * @return void
	 */
	public function rowcreate() {
		$row		 = fixer::input('post')
			->setDefault('join', '0000-00-00')
			->get();
		if (!$row->group) $row->group	 = 0;

		$this->dao->insert(TABLE_KEVINSTROE_ROW)->data($row)
			->autoCheck()
			->batchCheck($this->config->kevinstore->rowcreate->requiredFields, 'notempty')
			->check('nametype', 'unique')
			->exec();
		if (dao::isError()) return;
	}

	/**
	 * Delete a user.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function rowdelete($id) {
		$this->dao->update(TABLE_KEVINSTROE_ROW)->set('deleted')->eq('1')
			->where('id')->eq($id)
			->exec();
	}

	/**
	 * Get user info by ID.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return object|bool
	 */
	public function rowGet($id) {
		$row = $this->dao->select('a.*,b.name,b.group,b.subType')->from(TABLE_KEVINSTROE_ROW)->alias('a')
				->leftjoin(TABLE_KEVINSTROE_ITEM)->alias('b')->on('a.number = b.number')
			->where('a.id')->eq($id)->fetch();
		return $row;
	}

	/**
	 * Get rowList by sql.
	 * 
	 * @param  string    $query 
	 * @param  int    $pager 
	 * @access public
	 * @return void
	 */
	public function rowGetByQuery($group = '', $pager = null, $orderBy = 'number') {
		//$this->rowStatusCheck();
		return $this->dao->select('a.*,b.name,b.group,b.subType')
				->from(TABLE_KEVINSTROE_ROW)->alias('a')
				->leftjoin(TABLE_KEVINSTROE_ITEM)->alias('b')->on('a.number = b.number')
				->where('a.deleted')->eq(0)
				->beginIF($group)->andWhere('b.group')->eq($group)->fi()
				->orderBy($orderBy)
				->page($pager)
				->fetchAll();
	}

	/**
	 * Get rowList by sql.
	 * 
	 * @param  int    $query 
	 * @param  int    $pager 
	 * @access public
	 * @return void
	 */
	public function rowGetByGroup($group = 0, $orderBy = 'name') {
		if ($group) {
			return $this->dao->select('*')
					->from(TABLE_KEVINSTROE_ROW)
					->where('`group`')->eq((int) $group)
					->andWhere('deleted')->eq(0)
					->orderBy($orderBy)
					->fetchAll();
		}
		return null;
	}

	/**
	 * Get user pairs of a group.
	 * 
	 * @param  int    $group 
	 * @access public
	 * @return array
	 */
	public function rowGetPairs($group = 0) {
		if (0 == $group) {
			return $this->dao->select('id,name')
					->from(TABLE_KEVINSTROE_ROW)
					->Where('deleted')->eq(0)
					->orderBy('name')
					->fetchPairs();
		}
		return $this->dao->select('id,name')
				->from(TABLE_KEVINSTROE_ROW)
				->where('`group`')->eq((int) $group)
				->andWhere('deleted')->eq(0)
				->orderBy('name')
				->fetchPairs();
	}

	/**
	 * Update a user.
	 * 
	 * @param  char    $id 
	 * @access public
	 * @return void
	 */
	public function rowupdate($row) {
		if (!$row) {
			dao::$errors["rowupdate"][] = "can not find the row.";
			return;
		}
		$rowPost				 = fixer::input('post')->get();
		$rowPost->lastEditedBy	 = $this->app->user->account;
		$rowPost->lastEditedDate = helper::now();
		$rowPost->stdPrice = (double)$rowPost->stdPrice;
		$rowPost->count = (int)$rowPost->count;
		$totalPrice = $rowPost->count *$row->stdPrice;
		
		if ($totalPrice != $row->totalPrice)$rowPost->totalPrice =$totalPrice;
		unset($rowPost->id);	
		unset($rowPost->nuimber);

		$this->dao->update(TABLE_KEVINSTROE_ROW)->data($rowPost)
			->autoCheck()
			->batchCheck($this->config->kevinstore->rowedit->requiredFields, 'notempty')
			->where('id')->eq($row->id)
			->exec();
	}

	/**
	 * statistic By Group.
	 * 
	 * @access public
	 * @return array
	 */
	public function statisticByGroup() {
		return $this->dao->select('count(*) as storeCount, `group`,b.name as groupName')
				->from(TABLE_KEVINSTROE_ITEM)->alias('a')
				->leftJoin(TABLE_KEVINSTROE_GROUP)->alias('b')->on('a.group = b.id')
				->where('a.deleted')->eq('0')
				->groupBy('`group`')
				->orderBy('`group`')
				->fetchAll();
	}

	/**
	 * statistic By dept.
	 * 
	 * @access public
	 * @return array
	 */
	public function statisticByDept() {
		return $this->dao->select('count(*) as storeCount, `group`,b.name as groupName,b.id as group')
				->from(TABLE_KEVINSTROE_ITEM)->alias('a')
				->leftJoin(TABLE_DEPT)->alias('b')->on('a.dept = b.id')
				->where('a.deleted')->eq('0')
				->groupBy('`dept`')
				->orderBy('dept')
				->fetchAll();
	}

	/**
	 * statistic By dept.
	 * 
	 * @access public
	 * @return array
	 */
	public function statisticByType() {
		return $this->dao->select('count(*) as storeCount, b.type as groupName')
				->from(TABLE_KEVINSTROE_ITEM)->alias('a')
				->leftJoin(TABLE_KEVINSTROE_GROUP)->alias('b')->on('a.group=b.id')
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
		$itemyears	 = $this->dao->select('count(*) as storeCount,a.join')
			->from(TABLE_KEVINSTROE_ITEM)->alias('a')
			->leftJoin(TABLE_KEVINSTROE_GROUP)->alias('b')->on('a.group=b.id')
			->where('b.type')->eq('station')
			->andwhere('a.deleted')->eq('0')
			->andwhere('b.deleted')->eq('0')
			->groupBy('a.join')
			->orderBy('a.join')
			->fetchAll();
		$countarray	 = array();
		$finalarray	 = array();
		foreach ($itemyears as $itemyear) {
			$yearlimit							 = time() - strtotime($itemyear->join);
			$itemyear->groupName				 = ceil($yearlimit / 60 / 60 / 24 / 365) - 1;
			if ($itemyear->join == "0000-00-00") $itemyear->groupName				 = 'unknown';
			if (isset($countarray[$itemyear->groupName])) $countarray[$itemyear->groupName]+=$itemyear->storeCount;
			else $countarray[$itemyear->groupName]	 = $itemyear->storeCount;
		}
		foreach ($countarray as $grpname => $itemcount) {
			$finalarray[$grpname]	 = new stdClass();
			$item					 = &$finalarray[$grpname];
			if ($grpname === 'unknown') $item->groupName		 = $this->lang->kevinstore->StatusList[$grpname];
			else $item->groupName		 = '满' . $grpname . '年';
			$item->storeCount		 = $itemcount;
		}
		return $finalarray;
	}

	/**
	 * Get group pairs.
	 * 
	 * @access public
	 * @return array
	 */
	public function subTypeGetPairs() {
		return $this->dao->select('distinct `subType`')->from(TABLE_KEVINSTROE_ITEM)->fetchPairs();
	}

}
