<?php

class kevinerrcodeModel extends model {
	/**
	 * Construct function
	 */
	public function __construct() {
		parent::__construct();
		$this->app->loadClass('kevin'); //加载kevin类
	}
	
	/**
	 * create	
	 * @access public
	 * @param  Object $iData
	 * @return id
	 */
	public function create($iData) {
		unset($iData->id);	//不可以设定id
		$this->dao->insert(TABLE_KEVINERRCODE)->data($iData)
			->autoCheck()
			->exec();
		return $this->dao->lastInsertID();
	}
	
	/**
	 * getById	
	 * @access public
	 * @param  int $iID
	 * @return id
	 */
	public function getById($iID) {
		return $this->dao->findById((int) $iID)->from(TABLE_KEVINERRCODE)->fetch();
	}
	
	/**
	 * getList	
	 * @access public
	 * @param  none
	 * @return list
	 */
	public function getList() {
		return $this->dao->select('*')->from(TABLE_KEVINERRCODE)
			->fetchAll();
	}
	
	/**
	 * update	
	 * @access public
	 * @param  data object $iData
	 * @return void
	 */
	public function update($iData) {
		$ret = kevin_create_new_errcode();
		$id = isset($iData->id)?$iData->id:-1;
		$ret->data  = new stdclass();
		$ret->data->id = $id;
		$ret->data->inputData = $iData;
		if ($id <1) {
			$ret->errcode = 10;
			$ret->errmsg  = 'Please input an id which >0.';
			return $ret;
		}		
		else if (!$iData) {
			$ret->errcode = 11;
			$ret->errmsg  = 'Post parameters are not enough';
			return $ret;
		}

		$oldItem = $this->getById($id);
		if(!$oldItem){
			$ret->errcode = 10;
			$ret->errmsg  = 'Can not find item with id = '.$id;
			return $ret;
		}
		else if($oldItem->status != 100){
			$ret->errcode = 10;
			$ret->errmsg  = 'Only staus = 100 can be updated. status ='.$oldItem->status;
			return $ret;
		}
		//过滤数据的部分
		$data = [];
		foreach($iData as $key => $value) {
			if(isset($oldItem->$key)){
				if($key != 'id' && $oldItem->$key != $value){
					$data[$key] = $value;//不相等，进行更新
				} 
			}
		}
		
		if(!$data) {
			$ret->errcode = 12;
			$ret->errmsg  = 'All data is same.No need to update.';
			return $ret;
		}
		
		try{
			$this->dao->update(TABLE_KEVINERRCODE)->data($data)
			->where('id')->eq($id)
			->autoCheck()
			->exec();	
		}
		catch (Exception $e) {
			$ret->errcode = 2;
			$ret->errmsg  =$e->getMessage(); 
			return $ret;
		}
		
		if (dao::isError()) {
			$ret->errcode = 12;
			$ret->errmsg  = dao::getError(true);
		}
		return $ret;
	}

}
