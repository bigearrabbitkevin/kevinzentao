<?php

class kerrcodeModel extends model {
	/**
	 * Construct function
	 */
	public function __construct() {
		parent::__construct();
		$this->app->loadClass('kevin'); //加载kevin类
	}
	
	/**
	 * approve	
	 * @access public
	 * @param  Object $iData
	 * @return id
	 */
	public function approve($iData) {
		$ret = kevin_create_new_errcode();
		if(!$iData){
			$ret->errcode = 11;
			$ret->errmsg  = 'iData is empty';
			return $ret;
		}
		
		$data = new stdclass();
		//下面几个可以设定
		$data->id = (isset($iData->id))?trim($iData->id):'';
		$data->status = (isset($iData->status))?trim($iData->status):0;
		$data->status = intval($data->status);
		$data->id = intval($data->id);

		$oldItem = $this->getById($data->id);;
		$ret->data = $data;
		//检查是否需要修改
		if($data->id <1){
			$ret->errcode = 11;
			$ret->errmsg  = 'Item which id <1 can not be modified.';
		}else if($data->status != 200 && $data->status != 300){
			$ret->errcode = 11;
			$ret->errmsg  = 'status only can change to 200 or 300';
		}else if($data->status == $oldItem->status){
			$ret->errcode = 11;
			$ret->errmsg  = 'status is same';
		}
		if ($ret->errcode>0) {
			return $ret;
		}
		$this->dao->update(TABLE_KEVINERRCODE)->data($data)
			->where('id')->eq($data->id)
			->autoCheck()
			->exec();
		if (dao::isError()) {
			$ret->errcode = 13;
			$ret->errmsg  = dao::getError(true);
		} 
		//成功后标明修改为指定的status
			
		return $ret;
	}
	
	/**
	 * create	
	 * @access public
	 * @param  Object $iData
	 * @return id
	 */
	public function create($iData) {
		
		$ret = kevin_create_new_errcode();
		$data = new stdclass();
		//下面几个可以设定
		$data->name = (isset($iData->name))?trim($iData->name):'';
		$data->code = (isset($iData->code))?trim($iData->code):'';
		$data->nameEn = (isset($iData->nameEn))?trim($iData->nameEn):'';
		$data->description = (isset($iData->description))?trim($iData->description):'';
		$data->status = 100;//草稿
		
		$ret->data = $data;
		if($data->name == ''){
			$ret->errcode = 11;
			$ret->errmsg  = 'Name is empty';
		}else if($data->code == ''){
			$ret->errcode = 11;
			$ret->errmsg  = 'code is empty';
		}else if($data->nameEn == ''){
			$ret->errcode = 11;
			$ret->errmsg  = 'nameEn is empty';
		}
		if ($ret->errcode>0) {
			return $ret;
		}

		$this->dao->insert(TABLE_KEVINERRCODE)->data($data)
			->autoCheck()
			->check('code','unique')
			->exec();
		if (dao::isError()) {
			$ret->errcode = 13;
			$ret->errmsg  = dao::getError(true);
		} else {
			$ret->data->id = $this->dao->lastInsertID();
		}
			
		return $ret;
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
	public function update($iData,$iGetNewData = false) {
		$ret = kevin_create_new_errcode();
		if (empty($iData)) {
			$ret->errcode = 11;
			$ret->errmsg  = 'Post parameters are not enough';
			return $ret;
		}
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
		//Update
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
		
		//成功后检出新数据
		if ($iGetNewData && 0 == $ret->errcode) {
			$ret->data->newItem = $this->getById($ret->data->id);;
		}

		return $ret;
	}

}
