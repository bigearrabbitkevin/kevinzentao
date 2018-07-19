<?php

include '../../control.php';

/**
 * The control file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: control.php 5020 2013-07-05 02:03:26Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class myMy extends my {

	/**
	 * Change password , if use default password ,go to change
	 * 
	 * @access public
	 * @return void
	 */
	public function changePassword() {
		if ($this->app->user->account == 'guest') {
			die(js::alert('guest') . js::locate('back'));
		}

		if (!empty($_POST)) {
			$password1 = $_POST['password1'];
			if (!$password1) {
				die(js::error('Please input password!'));
			}
			$isDefult = $this->dao->select('password')
				->from(TABLE_DEFAULTPASSWORD)
				->Where('password')->eq($this->post->password1)
				->fetchAll();

			//如果用户使用默认密码则跳到修改密码界面
			if ($isDefult) {
				die(js::error('Password can not in default list!')
					. js::locate($this->createLink('my', 'changePassword'), 'parent'));
			}

			$this->user->updatePassword($this->app->user->id);
			if (dao::isError())
				die(js::error(dao::getError()));
			die(js::locate($this->createLink('my', 'profile'), 'parent'));
		}

		$this->view->title		 = $this->lang->my->common . $this->lang->colon . $this->lang->my->changePassword;
		$this->view->position[]	 = $this->lang->my->changePassword;
		$this->view->user		 = $this->user->getById($this->app->user->account);//default account from zentao 6.2

		$this->display();
	}

}
