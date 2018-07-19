<?php

include '../../control.php';

/**
 * The login function reload by Kevin.
 */
class myUser extends user {

	/**
     * Logout.
     * 
     * @access public
     * @return void
     */
    public function logout($referer = 0)
    {
        if(isset($this->app->user->id)){
			$this->loadModel("kevinlogin");
			//检查是否为guest
			if(!array_key_exists($this->app->user->account,$this->config->kevinlogin->guestList)){
				$this->loadModel('action')->create('user', $this->app->user->id, 'logout');
			}
		}
        session_destroy();
        setcookie('za', false);
        setcookie('zp', false);

        if($this->app->getViewType() == 'json') die(json_encode(array('status' => 'success')));
        $vars = !empty($referer) ? "referer=$referer" : '';
        $this->locate($this->createLink('user', 'login', $vars));
    }
}
