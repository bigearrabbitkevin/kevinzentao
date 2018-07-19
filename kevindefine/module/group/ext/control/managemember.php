<?php

include '../../control.php';

/**
 * The group function reload by Kevin.
 */
class mygroup extends group {

    /**
     * Manage members of a group.
     * 
     * @param  int    $groupID 
     * @param  int    $deptID
     * @access public
     * @return void
     */
	public function manageMember($groupID, $deptID = 0) {
		if(!empty($_POST))
        {
            $this->group->updateUser($groupID);
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            //die(js::alert('Save successfully!'));
			die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }
        $group      = $this->group->getById($groupID);
        $groupUsers = $this->group->getUserPairs($groupID);
		$deptusers=$this->dao->select('account,account as code')->from(TABLE_USER)->where('dept')->eq($deptID)->fetchPairs('account','code');
        $allUsers   = $this->loadModel('dept')->getDeptUserPairs($deptID);
        $otherUsers = array_diff_assoc($allUsers, $groupUsers);

        $title      = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->manageMember;
        $position[] = $group->name;
        $position[] = $this->lang->group->manageMember;

        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->group      = $group;
        $this->view->deptTree   = $this->loadModel('dept')->getTreeMenu($rooteDeptID = 0, array('deptModel', 'createGroupManageMemberLink'), $groupID);
		$this->view->deptusers  = $deptusers;
        $this->view->groupUsers = $groupUsers;
        $this->view->otherUsers = $otherUsers;

        $this->display();
	}
}
