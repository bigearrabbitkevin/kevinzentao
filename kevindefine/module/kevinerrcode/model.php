<?php

class kevinerrcodeModel extends model {
	/**
	 * Construct function
	 */
	public function __construct() {
		parent::__construct();
		$this->app->loadClass('kevin'); //加载kevin类
	}

	public function create($iData) {
		$this->dao->insert(TABLE_KEVINERRCODE)->data($iData)
			->autoCheck()
			->exec();
		return $this->dao->lastInsertID();
	}

	public function getById($iID) {
		return $this->dao->findById((int) $iID)->from(TABLE_KEVINERRCODE)->fetch();
	}

	public function getList() {
		return $this->dao->select('*')->from(TABLE_KEVINERRCODE)
			->fetchAll();
	}
	
	public function update($iData) {
		$id = $iData->id;
		unset($iData->id);
		$this->dao->update(TABLE_KEVINERRCODE)->data($iData)
			->where('id')->eq($id)
			->autoCheck()
			->exec();
	}

}
