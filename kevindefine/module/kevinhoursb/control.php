<?php

class kevinhoursb extends control {

	public function __construct() {
		parent::__construct();
		$this->app->loadClass('kevin'); //加载kevin类
		$this->app->loadClass('date');
	}

	public function batchcreate() {
		if (!empty($_POST)) {
			$this->kevinhoursb->batchcreate();
			if (dao::isError()) {
				die(js::error(dao::getError()));
			}
			if (isonlybody()) {
				die(js::reload('parent.parent'));
			}
			die(js::locate($this->createLink('kevinhoursb', 'index', ""), 'parent'));
		}
		$this->view->title		 = $this->lang->kevinhoursb->common . $this->lang->colon . $this->lang->kevinhoursb->batchcreate;
		$this->view->position[]	 = $this->lang->kevinhoursb->batchcreate;
		$this->view->controlType = 'batchcreate';
		$this->display();
	}

	public function commonWebParam($month) {
		if ('' == $month && !is_numeric($month)) {
			$month = date('Ym');
		}

		$currentMonth = date('Y-m') . '-01';
		if (is_numeric($month) && 6 == strlen($month)) {
			$currentMonth	 = $month . '01';
			$currentMonth	 = date('Y-m', strtotime($currentMonth)) . '-01';
		}

		$this->view->lastMonth	 = date('Ym', strtotime("$currentMonth -1 month"));
		$this->view->nextMonth	 = date('Ym', strtotime("$currentMonth +1 month"));
		$this->view->thisMonth	 = date('Ym');

		$this->view->methodName		 = $this->app->getMethodName();
		$this->view->yearList		 = $this->kevinhoursb->getYearList();
		$this->view->monthList		 = $this->lang->kevinhoursb->month;
		$this->view->currentYear	 = date('Y', strtotime("$currentMonth"));
		$this->view->currentMonth	 = date('m', strtotime("$currentMonth"));
	}

	public function create($date = '') {
		if (!empty($_POST)) {
			$this->kevinhoursb->create();
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinhoursb', 'index', ""), 'parent.parent'));
		}
		$this->view->title		 = $this->lang->kevinhoursb->common . $this->lang->colon . $this->lang->kevinhoursb->create;
		$this->view->position[]	 = $this->lang->kevinhoursb->create;
		$this->view->date		 = ('' == $date) ? date('Y-m-d') : (date('Y-m-d', strtotime($date)));
		$this->view->controlType = 'create';
		$this->display();
	}

	public function itemedit($id) {
		if (!empty($_POST)) {
			$this->kevinhoursb->update($id);
			if (dao::isError()) die(js::error(dao::getError()));
			die(js::closeModal('parent.parent'));
		}
		$this->view->title		 = $this->lang->kevinhoursb->common . $this->lang->colon . $this->lang->kevinhoursb->edit;
		$this->view->position[]	 = $this->lang->kevinhoursb->edit;

		$month		 = 'thisMonth';
		$monthItem	 = $this->kevinhoursb->getById($id);
		if ($monthItem) {
			$month = $monthItem->YearMonth;
		}
		$this->view->statusArray = $this->kevinhoursb->getInofoByMonth($month);
		$this->view->monthItem	 = $monthItem;
		$this->display();
	}

	public function itemview($id) {
		if (!empty($_POST)) {
			$this->kevinhoursb->update($id);
			if (dao::isError()) die(js::error(dao::getError()));
			die(js::closeModal('parent.parent'));
		}
		$this->view->title		 = $this->lang->kevinhoursb->common . $this->lang->colon . $this->lang->kevinhoursb->edit;
		$this->view->position[]	 = $this->lang->kevinhoursb->edit;

		$month		 = 'thisMonth';
		$monthItem	 = $this->kevinhoursb->getById($id);
		if ($monthItem) {
			$month = $monthItem->YearMonth;
		}
		$this->view->statusArray = $this->kevinhoursb->getInofoByMonth($month);
		$this->view->monthItem	 = $monthItem;
		$this->display();
	}

	/**
	 * calendar.
	 *      
	 * @access public
	 * @return void
	 */
	public function index($month = '') {
		if ('' == $month) {
			$month = date('Ym');
		}
		/* The title and position. */
		$this->view->title		 = $this->lang->kevinhoursb->common . $this->lang->colon . $this->lang->kevinhoursb->index;
		$this->view->position[]	 = $this->lang->kevinhoursb->index;

		$this->commonWebParam($month);

		extract(kevin::getBeginEndTime($month));
		$this->view->date		 = (int) $month == 0 ? date(DT_DATE1) : date(DT_DATE1, strtotime($month));
		$this->view->month		 = $month;
		$this->view->account	 = $this->app->user->account;
		$this->view->controlType = 'index';

		$this->display();
	}

	/**
	 * calendar.
	 *      
	 * @access public
	 * @return void
	 */
	public function input() {
		//update kv_hoursb_monthcount_input set FinishInput = 0;
		echo "<!DOCTYPE html><html lang='zh-cn'><head>  <meta charset='utf-8'>  <meta http-equiv='X-UA-Compatible' content='IE=edge'>";
		$items = $this->dao->select('*')->from(TABLE_KEVINHOURSB_MONTHCOUNT_INPUT)
			->where('FinishInput')->eq('0')
			->fetchAll();
		echo "Input Count = " . count($items) . "<br>";
		foreach ($items as $item) {
			echo "Item $item->id:";
			if (!$item->account) {
				echo "Error Item Month = $item->YearMonth,Accunt =$item->account, Account is null";
				continue;
			}
			$existItem	 = $this->dao->select('*')->from(TABLE_KEVINHOURSB_MONTHCOUNT)
				->where('YearMonth')->eq($item->YearMonth)
				->andWhere('account')->eq($item->account)
				->fetch();
			//unset($item->id);
			$log		 = $this->kevinhoursb->logUnion($item);
			if (!$existItem) {
				echo "Insert ";
				$this->dao->insert(TABLE_KEVINHOURSB_MONTHCOUNT)
					->set('log')->eq($log)
					->set('ClassDept')->eq($item->ClassDept)
					->set('code')->eq($item->code)
					->set('code2')->eq($item->code2)
					->set('dept')->eq($item->dept)
					->set('YearMonth')->eq($item->YearMonth)
					->set('account')->eq($item->account)
					->autoCheck()->exec();
			} else {
				echo "Update ";
				$this->dao->update(TABLE_KEVINHOURSB_MONTHCOUNT)
					->set('log')->eq($log)
					->set('ClassDept')->eq($item->ClassDept)
					->set('code')->eq($item->code)
					->set('code2')->eq($item->code2)
					->set('dept')->eq($item->dept)
					->where('YearMonth')->eq($item->YearMonth)
					->andWhere('account')->eq($item->account)
					->autoCheck()->exec();
			}
			echo " Month = $item->YearMonth,Accunt =$item->account,log = $log <br>";
			$this->dao->update(TABLE_KEVINHOURSB_MONTHCOUNT_INPUT)
				->set('FinishInput')->eq('1')
				->where('id')->eq($item->id)
				->autoCheck()->exec();
		}
		echo " input is finished.<br>";
		die();
	}

	/**
	 * index.
	 *      
	 * @access public
	 * @return void
	 */
	public function itemlist($month = 'thisMonth') {
		/* The title and position. */
		if ($month == 'thisMonth') {
			$month = '201610';
		}
		$this->view->title		 = $this->lang->kevinhoursb->common . $this->lang->colon . $this->lang->kevinhoursb->index;
		$this->view->position[]	 = $this->lang->kevinhoursb->index;
		$this->commonWebParam($month);
		extract(kevin::getBeginEndTime($month));
		$this->view->items		 = $this->kevinhoursb->getList($month);
		$this->view->date		 = (int) $month == 0 ? date(DT_DATE1) : date(DT_DATE1, strtotime($month));
		$this->view->month		 = $month;
		$this->view->account	 = $this->app->user->account;
		$this->view->controlType = 'itemlist';
		$this->display();
	}

	public function calender($account = '', $month = '', $logType = 'php') {
		$date = (6 == strlen($month)) ? $month . '01' : $month;

		if ('' == $date || !is_numeric($date) || 8 != strlen($date)) {
			$date = date('Ymd');
		}
		$month = substr($date, 0, 6);
		$this->commonWebParam($month);

		$dateOfOpenFile	 = date('Ymd', strtotime($date));
		$path			 = dirname(dirname(dirname(__FILE__))) . '/tmp/log/';

		if ('sql' === $logType) {
			$filePath = $path . 'sql.' . $dateOfOpenFile . '.log.php';
		} else {
			$filePath = $path . 'php.' . $dateOfOpenFile . '.log.php';
		}

		if (file_exists($filePath)) {
			$contents = file_get_contents($filePath);
		} else {
			$contents = '该天无错误日志文件!';
		}
		$this->view->currentDate = date('Y-m-d', strtotime($date));
		$this->view->date		 = $date;
		$this->view->contents	 = $contents;
		$this->view->month		 = $month;
		$this->view->account	 = $this->app->user->account;
		$this->view->controlType = 'log';

		$this->view->title		 = $this->lang->kevinhoursb->common . $this->lang->colon . $this->lang->kevinhoursb->log;
		$this->view->position[]	 = $this->lang->kevinhoursb->log;
		$this->display();
	}

	public function logdelete($month) {
		$path = dirname(dirname(dirname(__FILE__))) . '/tmp/log/';
		if ('' != $month && is_numeric($month) && 8 === strlen($month)) {
			$filePath = $path . 'php.' . $month . '.log.php';
			if (file_exists($filePath)) {
				$result = @unlink($filePath); //删除日志
				if (!$result) {
					$this->dao->logError('ErrorDeleteLog', '', '');
					echo "<script>alert('" . dao::getError(true) . "');</script>";
				}
			}
		} else if ('all' === $month && common::hasPriv('kevinhoursb', 'logdeleteall')) {
			$filesnames = scandir($path); //获取也就是扫描文件夹内的文件及文件夹名存入数组 $filesnames
			foreach ($filesnames as $currentFileName) {
				if ('php.' === substr($currentFileName, 0, 4) && '.log.php' === substr($currentFileName, -8)) {
					$result = @unlink($path . $currentFileName); //删除日志
					if (!$result) {
						$this->dao->logError('ErrorDeleteLog', '', '');
						echo "<script>alert('" . dao::getError(true) . "');</script>";
					}
				}
			}
		} else if ('allsql' === $month && common::hasPriv('kevinhoursb', 'logdeletesql')) {
			$filesnames = scandir($path); //获取也就是扫描文件夹内的文件及文件夹名存入数组 $filesnames
			foreach ($filesnames as $currentFileName) {
				if ('sql.' === substr($currentFileName, 0, 4) && '.log.php' === substr($currentFileName, -8)) {
					$result = @unlink($path . $currentFileName); //删除日志
					if (!$result) {
						$this->dao->logError('ErrorDeleteLog', '', '');
						echo "<script>alert('" . dao::getError(true) . "');</script>";
					}
				}
			}
		}
		if (isonlybody()) {
			die(js::reload('parent.parent'));
		}
		die(js::locate($this->createLink('kevinhoursb', 'log', ""), 'parent'));
	}

	public function todo($month = '') {
		if ('' == $month) {
			$month = date('Ym');
		}

		/* The title and position. */
		$this->view->title		 = $this->lang->kevinhoursb->common . $this->lang->colon . $this->lang->kevinhoursb->todo;
		$this->view->position[]	 = $this->lang->kevinhoursb->todo;

		$this->commonWebParam($month);

		extract(kevin::getBeginEndTime($month));
		$this->view->kevinhoursbs	 = $this->kevinhoursb->getList($begin, $end);
		$this->view->date			 = (int) $month == 0 ? date(DT_DATE1) : date(DT_DATE1, strtotime($month));
		$this->view->month			 = $month;
		$this->view->account		 = $this->app->user->account;
		$this->view->controlType	 = 'todo';

		$this->display();
	}

}
