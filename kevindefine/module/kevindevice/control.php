<?php

/**
 * The control file of kevindevice module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevindevice
 */
class kevindevice extends control {

	/**
	 * Construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->app->loadClass('date');
		$this->loadModel('dept');
		$this->loadModel('kevincom');
	}

	/**
	 * Copy a kevindevice.
	 * 
	 * @param  int    $groupID 
	 * @access public
	 * @return void
	 */
	public function groupcopy($groupID) {
		if (!empty($_POST)) {
			$this->kevindevice->groupcopy($groupID);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevindevice', 'browse'), 'parent'));
		}

		$this->view->title		 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->groupcopy;
		$this->view->position[]	 = $this->lang->kevindevice->groupcopy;
		$this->view->group		 = $this->kevindevice->groupGetById($groupID);
		$this->view->subMenu	 = "grouplist";
		$this->display();
	}

	/**
	 * Create a kevindevice.
	 * 
	 * @access public
	 * @return void
	 */
	public function groupcreate() {
		if (!empty($_POST)) {
			$this->kevindevice->groupcreate();
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevindevice', 'grouplist'), 'parent'));
		}

		$this->view->title		 = $this->lang->kevindevice->title . $this->lang->colon . $this->lang->kevindevice->groupcreate;
		$this->view->position[]	 = $this->lang->kevindevice->groupcreate;
		$this->view->subMenu	 = "grouplist";
		$this->display();
	}

	/**
	 * Delete a kevindevice.
	 * 
	 * @param  int    $groupID 
	 * @param  string $confirm  yes|no
	 * @access public
	 * @return void
	 */
	public function groupdelete($groupID, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevindevice->confirmDelete . $this->lang->kevindevice->group . "?", $this->createLink('kevindevice', 'groupdelete', "groupID=$groupID&confirm=yes"))
					. js::closeModal('parent.parent', 'this'));
		} else {
			$this->kevindevice->groupdelete($groupID);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevindevice', 'grouplist'), 'parent'));
		}
	}

	/**
	 * Edit a kevindevice.
	 * 
	 * @param  int    $groupID 
	 * @access public
	 * @return void
	 */
	public function groupedit($groupID) {
		if (!empty($_POST)) {
			$this->kevindevice->groupUpdate($groupID);
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevindevice', 'browse'), 'parent'));
		}

		$title					 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->groupedit;
		$position[]				 = $this->lang->kevindevice->groupedit;
		$this->view->title		 = $title;
		$this->view->position	 = $position;
		$this->view->group		 = $this->kevindevice->groupGetById($groupID);
		$this->view->subMenu	 = "grouplist";

		$this->display();
	}

	/**
	 * Browse groups.
	 * 
	 * @param  int    $companyID 
	 * @access public
	 * @return void
	 */
	public function grouplist() {

		$title		 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->grouplist;
		$position[]	 = $this->lang->kevindevice->grouplist;

		$groups					 = $this->kevindevice->groupGetList();
		$groupUsers				 = array();
		foreach ($groups as $group)
			$groupUsers[$group->id]	 = $this->kevindevice->devGetPairs($group->id);

		$this->view->title		 = $title;
		$this->view->position	 = $position;
		$this->view->groups		 = $groups;
		$this->view->groupUsers	 = $groupUsers;
		$this->view->subMenu	 = "grouplist";

		$this->display();
	}

	/**
	 * view a group.
	 * 
	 * @param  int $groupID   the int group id
	 * @access public
	 * @return void
	 */
	public function groupview($groupID) {
		if (!$groupID) {
			die(js::error("Input group ID wrong, must a right number!"));
		}
		$this->view->group = $this->kevindevice->groupGetById($groupID);

		if (!$this->view->group) {
			die(js::error("Can not find the group by this id = " . $groupID));
		}

		$title					 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->groupedit;
		$position[]				 = $this->lang->kevindevice->grouplist;
		$this->view->title		 = $title;
		$this->view->devices	 = $this->kevindevice->devGetByGroup($groupID);
		$this->view->position	 = $position;

		$this->view->subMenu = "grouplist";
		$this->display();
	}

	/**
	 * Create a suer.
	 * 
	 * @param  int    $deptID 
	 * @access public
	 * @return void
	 */
	public function devcreate($deptID = 0) {
		if (!empty($_POST)) {
			$this->kevindevice->devcreate();

			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevindevice', 'devlist'), 'parent'));
		}
		$groups		 = $this->dao->select('id, name, type')->from(TABLE_KEVINDEVICE_GROUP)->fetchAll();
		$groupList	 = array('' => '');
		$typeGroup	 = array();
		foreach ($groups as $group) {
			$groupList[$group->id]	 = $group->name;
			if ($group->type) $typeGroup[$group->type] = $group->id;
		}

		$title					 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->devlist;
		$position[]				 = $this->lang->kevindevice->common;
		$position[]				 = $this->lang->kevindevice->devlist;
		$this->view->title		 = $title;
		$this->view->position	 = $position;

		$this->view->depts		 = $this->dept->getOptionMenu();
		$this->view->groupList	 = $groupList;
		$this->view->typeGroup	 = $typeGroup;
		$this->view->deptID		 = $deptID;
		$this->view->subMenu	 = "devlist";

		$this->display();
	}

	/**
	 * Delete a device.
	 * 
	 * @param  int    $deviceID 
	 * @param  string $confirm  yes|no
	 * @access public
	 * @return void
	 */
	public function devdelete($deviceID, $confirm = 'no') {
		if (!$deviceID) return;
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevindevice->confirmDelete . $this->lang->kevindevice->dev
							, $this->createLink('kevindevice', 'devdelete', "userID=$deviceID&confirm=yes"))
					. js::closeModal('parent.parent', 'this'));
		} else {
			$this->kevindevice->devdelete($deviceID);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevindevice', 'devlist'), 'parent'));
		}
	}

	/**
	 * Edit a device.
	 * 
	 * @param  int $deviceID   the int device id
	 * @access public
	 * @return void
	 */
	public function devedit($deviceID) {
		if (!empty($_POST)) {
			$this->kevindevice->devupdate($deviceID);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevindevice', 'devlist'), 'parent'));
		}
		$this->loadModel("user");
		$device				 = $this->kevindevice->devGetById($deviceID);
		if(!$device) die(js::error("Can not Get Device with id = " . $deviceID));
		
		$this->view->users	 = $this->user->getPairs('nodeleted');
		$users = &$this->view->users;
		if(!array_key_exists($device->user,$users)) $users[$device->user]=$device->user;
		if(!array_key_exists($device->charge,$users)) $users[$device->charge]=$device->user;

		$deviceGroups		 = $this->kevindevice->groupGetByDevice($device->id);

		$title						 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->devedit;
		$position[]					 = $this->lang->kevindevice->devlist;
		$this->view->title			 = $title;
		$this->view->position		 = $position;
		$this->view->device			 = $device;
		$this->view->depts			 = $this->dept->getOptionMenu();
		$this->view->deviceGroups	 = implode(',', array_keys($deviceGroups));
		$this->view->groups			 = $this->kevindevice->groupGetPairs();
		$this->view->subMenu		 = "devlist";

		$this->display();
	}
	
	/**
	 * Browse group and devices.
	 * 
	 * @param  int    $param 
	 * @param  string $type 
	 * @param  string $orderBy 
	 * @param  int    $recTotal 
	 * @param  int    $recPerPage 
	 * @param  int    $pageID 
	 * @access public
	 * @return void
	 */
	public function devlist($param = 0, $type = 'bygroup', $orderBy = 'id', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		$groupID = $type == 'bygroup' ? (int) $param : 0;

		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);

		$sort = $this->kevincom->appendOrder($orderBy);

		$kevin_showStyle = 1; //默认简单
		//if post ,delete filter key word
		if (!empty($_POST)) {
			$kevin_showStyle = $this->post->showStyle;
			if ($kevin_showStyle) {
				$this->session->set("kevin_showStyle", $kevin_showStyle); //save sesison
			}
		} else {
			if (isset($_SESSION['kevin_showStyle'])) {
				$kevin_showStyle = $_SESSION['kevin_showStyle']; //获得session中保存的筛选关键词
			}
		}

		$this->loadModel("user");
		$this->view->users		 = $this->user->getPairs();
		$this->view->showStyle	 = $kevin_showStyle;

		//$userQuery = ;
		$devices				 = $this->kevindevice->devGetByQuery($groupID, $pager, $sort);
		$this->view->targetgroup = ($groupID) ? $this->kevindevice->groupGetById($groupID) : 0;

		$groups					 = $this->kevindevice->groupGetList();
		$this->view->title		 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->devlist;
		$this->view->position[]	 = $this->lang->kevindevice->common;
		$this->view->devices	 = $devices;
		$this->view->orderBy	 = $orderBy;
		$this->view->groupID	 = $groupID;
		$this->view->pager		 = $pager;
		$this->view->param		 = $param;
		$this->view->groups		 = $groups;
		$this->view->type		 = $type;
		$this->view->subMenu	 = "devlist";

		$this->display();
	}
	
	public function devxlsexport($groupID = 0,$orderBy = 'id'){
		$devices = $this->kevindevice->devGetByQuery($groupID, null, $orderBy);
		$this->loadModel("user");
		$users=$this->user->getPairs();
		$configRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR . '../../lib';
		require_once $configRoot.'/phpexcel/PHPExcel.php';
		require_once $configRoot.'/phpexcel/PHPExcel/Writer/Excel5.php';
		
		$randname	 = $this->kevindevice->createRandomStr(10);
		$filename	 = '..\..\www\data\tmp\\' . $randname . '.xls';
		if (!file_exists('..\..\www\data\tmp')) @mkdir('..\..\www\data\tmp');

		$handle		 = fopen($filename, 'w');
		$objPHPExcel = \PHPExcel_IOFactory::load($filename);
		
		$titles=&$this->config->kevindevice->titles;
		$objPHPExcel->setActiveSheetIndex(0);
		for($i=0;$i<=count($devices);$i++){
			$col='A';
			foreach($titles as $row=>$title){
				if($i===0){
					if($title=='id')$thead='ID';
					else $thead=$this->lang->kevindevice->$title;
					$objPHPExcel->getActiveSheet()->setCellValue(($col++).($i+1),$thead);
				}else{
					$dev=&$devices[$i-1];
					$objPHPExcel->getActiveSheet()->setCellValue(($col++).($i+1),$this->kevindevice->devalchk($dev,$title,$users));
				}
			}
		}
		fclose($handle);
		ob_end_clean();
		header('Content-type:application/vnd.ms-excel;charset=UTF-8BOM');
		header('Content-Disposition:attachment;filename="' . $this->lang->kevindevice->devxlsexport . '.xls' . '"');
		header("Pragma:nono-cache");
		header("Expires:0");
		$objWriter	 = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		@unlink($filename);
	}
	
	/**
	 * view a device.
	 * 
	 * @param  int $deviceID   the int device id
	 * @access public
	 * @return void
	 */
	public function devview($deviceID) {
		if (!$deviceID)die(js::error("Input device ID wrong, must a right number!"));

		$device = $this->kevindevice->devGetById($deviceID);
		if (!$device)die(js::error("Can not find the device with id = " . $deviceID));

		$deviceGroups = $this->kevindevice->groupGetByDevice($device->id);

		$dept = $this->dept->getByID($device->dept);

		$this->loadModel("user");
		$users = $this->user->getPairs('nodeleted');
		if(!array_key_exists($device->user,$users)) $users[$device->user]=$device->user;
		if(!array_key_exists($device->charge,$users)) $users[$device->charge]=$device->user;
		
		if ($device) {
			$device->userName	 = $users[$device->user];
			$device->chargeName	 = $users[$device->charge];
		} else {
			$device->chargeName	 = "";
			$device->userName	 = "";
		}

		$title						 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->devview;
		$position[]					 = $this->lang->kevindevice->devlist;
		$this->view->title			 = $title;
		$this->view->position		 = $position;
		$this->view->device			 = $device;
		$this->view->deptName		 = (!$dept) ? "" : $dept->name;
		$this->view->deviceGroups	 = $deviceGroups;
		$this->view->subMenu		 = "devlist";


		$this->display();
	}

	public function maintaincreate(){
		if (!empty($_POST)) {
			$maintainID = $this->kevindevice->maintainCreate();
			if (dao::isError()) die(js::error(dao::getError()));
			
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevindevice', 'maintainlist'), 'parent'));
		}

		//网页位置
		$this->view->title		 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->bosscreate;
		$this->view->position[]	 = $this->lang->kevindevice->maintaincreate;

		$this->display();
	}
	
	public function maintaindelete($maintainID, $confirm = 'no'){
		if (!$maintainID) return;
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevindevice->confirmDelete . $this->lang->kevindevice->maintain,$this->createLink('kevindevice', 'maintaindelete', "maintainID=$maintainID&confirm=yes")));
		} else {
			$this->kevindevice->maintainDelete($maintainID);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevindevice', 'maintainlist'), 'parent'));
		}
	}
	
	public function maintainedit($maintainID){
		if (!empty($_POST)) {
			$this->kevindevice->maintainUpdate($maintainID);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::reload('parent.parent'));
			
			die(js::locate($this->createLink('kevindevice', 'maintainlist'), 'parent'));
		}
		
		$maintain = $this->kevindevice->maintainGetById($maintainID);
		if(!$maintain) die(js::error("Can not Get Maintain record with id = " . $maintainID));

		$title						 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->devedit;
		$position[]					 = $this->lang->kevindevice->devlist;
		$this->view->title			 = $title;
		$this->view->position		 = $position;
		$this->view->maintain		 = $maintain;
		$this->view->subMenu		 = "maintainlist";

		$this->display();
	}
	
	/**
	 * Browse maintain records.
	 * 
	 * @param  int    $param 
	 * @param  string $type 
	 * @param  string $orderBy 
	 * @param  int    $recTotal 
	 * @param  int    $recPerPage 
	 * @param  int    $pageID 
	 * @access public
	 * @return void
	 */
	public function maintainlist($year = 0, $orderBy = 'time_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1) {

		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);
		$sort = $this->kevincom->appendOrder($orderBy);

		$maintains				 = $this->kevindevice->maintainGetByQuery($year, $pager, $sort);
		$years=$this->kevindevice->maintainYearGet();
		
		$sumlog=$sumplatform=$sumtotal=0;
		foreach($maintains as $maintain){
			$sumlog+=$maintain->log;
			$sumplatform+=$maintain->platform;
			$sumtotal+=$maintain->total;
		}
		
		$this->view->title		 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->devlist;
		$this->view->position[]	 = $this->lang->kevindevice->common;
		$this->view->maintains	 = $maintains;
		$this->view->sumlog		 = $sumlog;
		$this->view->sumplatform = $sumplatform;
		$this->view->sumtotal		 = $sumtotal;
		$this->view->orderBy	 = $orderBy;
		$this->view->pager		 = $pager;
		$this->view->years		 = $years;
		$this->view->selectyear	 = $year;
		$this->view->subMenu	 = "maintainlist";

		$this->display();
	}
	
	/**
	 * Create a color.
	 * 
	 * @access public
	 * @return void
	 */
	public function sendoutcreate(){
		if (!empty($_POST)) {
			$sendoutid = $this->kevindevice->sendoutcreate();
			if (dao::isError()) die(js::error(dao::getError()));
			
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevindevice', 'sendoutlist'), 'parent'));
		}

		//网页位置
		$this->view->title		 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->sendoutcreate;
		$this->view->position[]	 = $this->lang->kevindevice->sendoutcreate;
		$this->display();
	}
	
	public function sendoutdelete($sendoutID, $confirm = 'no') {
		if (!$sendoutID || $sendoutID <= 0) die(js::error("ID is wrong! =" . $sendoutID));

		if ($confirm == 'no')die(js::confirm($this->lang->kevindevice->sendoutdeleteconfirm . " id = " . $sendoutID, inlink('sendoutdelete', "sendoutID=$sendoutID&confirm=yes")));
		
		$this->dao->update(TABLE_KEVINDEVICE_SENDOUTLIST)->set('deleted')->eq(1)->where('id')->eq((int) $sendoutID)->andwhere('deleted')->eq(0)->exec() or $this->dao->update(TABLE_KEVINDEVICE_SENDOUTLIST)->set('deleted')->eq(0)->where('id')->eq((int) $sendoutID)->andwhere('deleted')->eq(1)->exec();

		
		if (dao::isError()) die(js::error(dao::getError()));

		if (isonlybody()) die(js::reload('parent.parent'));
		die(js::locate($this->createLink('kevindevice', 'sendoutlist'), 'parent')); //'lamptypelist'), 'parent'));
	}
	
	/**
	 * Edit a color.
	 * 
	 * @param int $id
	 * @access public
	 * @return void
	 */
	public function sendoutedit($sendoutID){
		if (!empty($_POST)) {
			$this->kevindevice->sendoutUpdate($sendoutID);
			if (dao::isError()) die(js::error(dao::getError()));
			
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevindevice', 'sendoutlist'), 'parent'));
		}

		$this->view->item=$this->kevindevice->sendoutGetById($sendoutID);
		//网页位置
		$this->view->title		 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->spotchkedit;
		$this->view->position[]	 = $this->lang->kevindevice->spotchkedit;
		$this->display();
	}
	
	public function sendoutlist($year = 0,$orderBy = 'time_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);
		$sort = $this->kevincom->appendOrder($orderBy);

		$sendouts = $this->kevindevice->sendoutGetByQuery($year, $pager, $sort);
		$years	  = $this->kevindevice->sendoutYearGet();
		
		$sumsendout=$sumtotal=0;
		foreach($sendouts as $sendout){
			$sumsendout+=$sendout->sendout;
			$sumtotal+=$sendout->total;
		}
		
		$this->view->title		 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->sendoutlist;
		$this->view->position[]	 = $this->lang->kevindevice->common;
		$this->view->sendouts	 = $sendouts;
		$this->view->sumsendout	 = $sumsendout;
		$this->view->sumtotal	 = $sumtotal;
		$this->view->orderBy	 = $orderBy;
		$this->view->pager		 = $pager;
		$this->view->years		 = $years;
		$this->view->selectyear	 = $year;
		$this->view->subMenu	 = "sendoutlist";

		$this->display();
	}
	
	public function spotcheck(){
		if (!empty($_POST)) {

			$this->loadModel('file');
			$changes = array();
			$files	 = array();
			$now	 = helper::now();
			$ispublic	 = array();
			for($i=1;$i<=25;$i++){
				$fileno="files$i";
				$labelno="labels$i";
				$emptyflag=0;
				if(isset($_FILES[$fileno])&&$_FILES[$fileno]){
					if(is_array($_FILES[$fileno]['name'])){
						extract($_FILES[$fileno]);
						foreach ($name as $id => $filename) {
							if($filename!==''){
								$_POST[$labelno][$id]	 = 0;
							}else $emptyflag=1;
						}
					}elseif($_FILES[$fileno]['name']=='')$emptyflag=1;
					if(!$emptyflag)$files[$i] = $this->file->getUpload($fileno, $labelno);
				}
			}

			$spotchkitem=$this->post->spotchkitem;
			$spotchkitem=mb_convert_encoding($spotchkitem, "GBK","UTF-8");

//			引入PHPExcel插件
			set_include_path('.' . PATH_SEPARATOR . $this->config->kevindevice->excelincludepath . PATH_SEPARATOR . get_include_path());
			require_once 'PHPExcel.php';
			require_once 'PHPExcel\IOFactory.php';
			require_once 'PHPExcel\Reader\Excel2007.php';
			require_once 'PHPExcel\RichText.php';
			require_once 'PHPExcel\Worksheet.php';
			$chkitems=array();
			if(!$spotchkitem||!file_exists($spotchkitem))$chkitems=$this->dao->select('id,name')->from(TABLE_KEVINDEVICE_SPOTCHKLIST)->where('deleted')->eq(0)->fetchPairs();
			else{		
				$itemobj		 = PHPExcel_IOFactory::load($spotchkitem);
				$itemsheet		 = $itemobj->setActiveSheetIndex(0);
//				获取总行数
				$itemrows		 = $itemsheet->getHighestRow();
				for($i=1;$i<=$itemrows;$i++){
//					获取各行第二列数据
					$str = $itemsheet->getCellByColumnAndRow(1, $i)->getValue();
					$str = $str;
//					去除单元格格式信息及前后空格
					if (is_a($str, 'PHPExcel_RichText')) $str = $str->getPlainText();
					$str = trim($str);
					if($str)$chkitems[$i]=$str;
				}
			}
			
			$sendouts = $totals = array();
			ini_set('max_execution_time','0');
			foreach ($files as $index => $filearr) {
				if(!$filearr)  continue;
				foreach ($filearr as $id => $file) {

					if ($file['size'] == 0) continue;
					$rows=file($file['tmpname'],FILE_SKIP_EMPTY_LINES);
					$table=$tr=$td=$th=$index=$convert=0;
					foreach ($rows as $rawrow) {
						$row=html_entity_decode($rawrow, ENT_QUOTES, "UTF-8");
						if($convert)$row=mb_convert_encoding($row, 'UTF-8', 'GBK');
//						如果编码为GBK，则进行转码
						if(stripos($row,'<meta ')!==false&&stripos($row, 'charset=gb2312')!==false)$convert=1;
						if(stripos($row, '<table ')!==false)$table=1;
						if($table&&stripos($row, '<tr')!==false){$tr=1;$compos=$timepos=0;$comprow=$month='';}
						if($tr&&stripos($row, '<td')!==false){
							$td=1;$compos++;$timepos++;
						}
						if($tr&&stripos($row, '<th')){
							$th=1;$compos++;$timepos++;
						}
						if($td||$th){
							$text=trim(strip_tags($row));
//							html标签不完全过滤处理
							if(substr_count($text, '>')){
								$tags=explode('>', $text);
								$text=trim($tags[1]);
							}
							if($text=='公司')$chkcompos=$compos;
							elseif(isset($chkcompos)&&$compos==$chkcompos&&$index)$comprow.=$text;
							if($text=='Date Released'||$text=='发放日期')$chktimepos=$compos;
							elseif(isset($chktimepos)&&$timepos==$chktimepos&&$index){
								if($text){
									$month = date('Y-m',  strtotime($text));
									if(!isset($sendouts[$month]))$sendouts[$month]=0;
									if(!isset($totals[$month]))$totals[$month]=0;
								}
							}
						}

						if(stripos($row,'</td>')!==false)$td=0;
						if(stripos($row,'</th>')!==false)$th=0;
						if(stripos($row,'</tr>')!==false){
							$index++;
							$tr=0;
							if($month){
								$totals[$month]++;
								if(in_array(trim($comprow), $chkitems))$sendouts[$month]++;
							}
						}
						if(stripos($row,'</table>')!==false)$table=0;
					}
					if(!$totals){
//						加载源文件
						$objPHPExcel	 = PHPExcel_IOFactory::load($spotchkfile);
//						读取第一张sheet表
						$sheet			 = $objPHPExcel->setActiveSheetIndex(0);
//						获取总行数
						$highestRow		 = $sheet->getHighestRow();
						$highestColumn	 = ord($sheet->getHighestColumn())-65;
						$sendouts		 = array();
						$endfor=0;

						for($row=1;$row<=$highestRow;$row++){
							if($endfor){
//								获取所需公司、时间数据
								$maker= $sheet->getCellByColumnAndRow($chkcompcol, $row)->getValue();
								$time = $sheet->getCellByColumnAndRow($chktimecol, $row)->getValue();

//								去除单元格格式信息及前后空格
								$maker = trim(is_a($maker, 'PHPExcel_RichText')? $maker->getPlainText():$maker);
//								处理时间格式
								$time=  gmdate("Y-m", PHPExcel_Shared_Date::ExcelToPHP($time));
								if(!$maker||!$time) continue;
								$day=date('Y-m', strtotime($time));

								if(!isset($totals[$day]))$totals[$day]=0;
								if(!isset($sendouts[$day]))$sendouts[$day]=0;
								if(in_array($maker, $chkitems))$sendouts[$day]++;
								$totals[$day]++;
							}else{
//								确定列位置
								for($col=0;$col<=$highestColumn;$col++){
									$title = $sheet->getCellByColumnAndRow($col, $row)->getValue();
									$title = trim(is_a($title, 'PHPExcel_RichText')? $title->getPlainText():$title);

									if($title=='公司')$chkcompcol=$col;
									if($title=='Date Released'||$title=='发放日期')$chktimecol=$col;
									if(isset($chkcompcol)&&isset($chktimecol))$endfor=1;
								}
							}
						}
					}
				}
			}
			
			$oldatas=$this->dao->select("left(time,7) as month,sendout,total")->from(TABLE_KEVINDEVICE_SENDOUTLIST)->where('deleted')->eq(0)->fetchAll();
			$sendmap=$totalmap=array();
			foreach ($oldatas as $data) {
				$sendmap[$data->month]=$data->sendout;
				$totalmap[$data->month]=$data->total;
			}
			foreach ($totals as $daytime => $total) {
				$data=new stdClass();
				$data->time=$daytime.'-01';
				if(isset($sendmap[$daytime])&&isset($totalmap[$daytime])){
					$data->total=$totalmap[$daytime]+$total;
					if(isset($sendouts[$daytime]))$data->sendout=$sendmap[$daytime]+$sendouts[$daytime];
					$this->dao->update(TABLE_KEVINDEVICE_SENDOUTLIST)->data($data)->where("left(time,7)")->eq($daytime)->exec();
				}else{
					$data->total=$total;
					if(isset($sendouts[$daytime]))$data->sendout=$sendouts[$daytime];
					$this->dao->insert(TABLE_KEVINDEVICE_SENDOUTLIST)->data($data)->exec();
				}
			}
		}
		
		die(js::locate($this->createLink('kevindevice', 'sendoutlist')));
	}
	
	/**
	 * Create a color.
	 * 
	 * @access public
	 * @return void
	 */
	public function spotchkcreate(){
		if (!empty($_POST)) {
			$spotid = $this->kevindevice->spotcreate();
			if (dao::isError()) die(js::error(dao::getError()));
			
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevindevice', 'spotchklist'), 'parent'));
		}

		//网页位置
		$this->view->title		 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->spotchkcreate;
		$this->view->position[]	 = $this->lang->kevindevice->spotchkcreate;
		$this->display();
	}
	
	public function spotchkdelete($id, $confirm = 'no') {
		if (!$id || $id <= 0) {
			die(js::error("ID is wrong! =" . $id));
		}

		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevindevice->spotchkdeleteconfirm . " id = " . $id, inlink('spotchkdelete', "id=$id&confirm=yes")));
		}
		$this->dao->update(TABLE_KEVINDEVICE_SPOTCHKLIST)->set('deleted')->eq(1)->where('id')->eq((int) $id)->andwhere('deleted')->eq(0)->exec() or $this->dao->update(TABLE_KEVINDEVICE_SPOTCHKLIST)->set('deleted')->eq(0)->where('id')->eq((int) $id)->andwhere('deleted')->eq(1)->exec();

		
		if (dao::isError()) die(js::error(dao::getError()));

		if (isonlybody()) die(js::reload('parent.parent'));
		die(js::locate($this->createLink('kevindevice', 'spotchklist'), 'parent')); //'lamptypelist'), 'parent'));
	}
	
	/**
	 * Edit a color.
	 * 
	 * @param int $id
	 * @access public
	 * @return void
	 */
	public function spotchkedit($id){
		if (!empty($_POST)) {
			$this->kevindevice->spotUpdate($id);
			if (dao::isError()) die(js::error(dao::getError()));
			
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevindevice', 'spotchklist'), 'parent'));
		}

		$this->view->item=$this->kevindevice->spotGetById($id);
		//网页位置
		$this->view->title		 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->spotchkedit;
		$this->view->position[]	 = $this->lang->kevindevice->spotchkedit;
		$this->display();
	}
		
	public function spotchklist($orderBy = 'id', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);

		$this->view->spotchks	 = $this->kevindevice->spotGetList($orderBy,$pager);
		
		$this->view->title		 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->spotchklist;
		$this->view->position[]	 = $this->lang->kevindevice->common;
		$this->view->orderBy	 = $orderBy;
		$this->view->pager		 = $pager;
		$this->view->subMenu	 = "spotchklist";

		$this->display();
	}
	
	/**
	 * Statistic kevindevices. 
	 * @param  string    $date 
	 * @param  string    $type 
	 * @access public
	 * @return void
	 */
	public function statistic($type = 'group',$year=0) {
		//网页位置
		$this->view->title		 = $this->lang->kevindevice->common . $this->lang->colon . $this->lang->kevindevice->statistic;
		$this->view->position[]	 = $this->lang->kevindevice->statistic;

		//绘制图表
		if ('group' == $type) {
			$this->view->items = $this->kevindevice->statisticByGroup();
		}else if('dept' == $type) {
			$this->view->items = $this->kevindevice->statisticByDept();
		}else if('type' == $type) {
			$this->view->items = $this->kevindevice->statisticByType();
		}else if('yearlimit' == $type) {
			$this->view->items = $this->kevindevice->statisticByYear();
		}else if('maintain' == $type) {
			$this->view->items = $this->kevindevice->statisticByMaintain($year);
			$years			   = $this->kevindevice->maintainYearGet();
			array_unshift($years, '');
			$years=array_combine($years, $years);
			$this->view->years = $years;
			$this->view->year  = $year;
		}else if('sendout' == $type) {
			$this->view->items = $this->kevindevice->statisticBySendout($year);
			$years			   = $this->kevindevice->sendoutYearGet();
			array_unshift($years, '');
			$years=array_combine($years, $years);
			$this->view->years = $years;
			$this->view->year  = $year;
		}else {
			$this->view->ErrorMsg = 'input type is wrong. no suche type of "' . $type . '"';
		}
		//页面参数
		$this->view->statisticType = $type;
		$this->view->subMenu	 = "statistic";
		$this->display();
	}

}
