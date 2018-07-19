<?php
include '../../control.php'; 
class myProject extends project
{
    /**
     * Browse bugs of a project. 
     * 
     * @param  int    $projectID 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function bug($projectID = 0, $orderBy = 'status,id_desc', $build = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1, $type = 'unclosed')
    {
        /* Load these two models. */
        $this->loadModel('bug');
        $this->loadModel('user'); 
		$this->loadModel('kevindefine');

        /* Save session. */
        $this->session->set('bugList', $this->app->getURI(true));

        $project   = $this->commonAction($projectID);
        $products  = $this->project->getProducts($project->id);
        $productID = key($products);    // Get the first product for creating bug.

        /* Header and position. */
        $title      = $project->name . $this->lang->colon . $this->lang->project->bug;
        $position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[] = $this->lang->project->bug;

        /* Load pager and get bugs, user. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
		$bugs  = array();
		if('assignedtome' == $type) $bugs  = $this->kevindefine->getProjectBugsByMe($projectID, $orderBy, $pager, $build, 'assignedTo');
		else if('openedbyme' == $type) $bugs  = $this->kevindefine->getProjectBugsByMe($projectID, $orderBy, $pager, $build, 'openedBy');
		else if('resolvedbyme' == $type) $bugs  = $this->kevindefine->getProjectBugsByMe($projectID, $orderBy, $pager, $build, 'resolvedBy');
		else if('closedbyme' == $type) $bugs  = $this->kevindefine->getProjectBugsByMe($projectID, $orderBy, $pager, $build, 'closedBy');
		else if('assigntonull' == $type) $bugs  = $this->kevindefine->getProjectBugsAssignToNull($projectID, $orderBy, $pager, $build);
		else if('unresolved' == $type) $bugs  = $this->kevindefine->getProjectBugsUnresolved($projectID, $orderBy, $pager, $build);
		else if('unclosed' == $type) $bugs  = $this->kevindefine->getProjectBugsUnclosed($projectID, $orderBy, $pager, $build);
		else if('longlifebugs' == $type) $bugs  = $this->kevindefine->getProjectBugsByLonglifebugs($projectID, $orderBy, $pager, $build);
		else if('postponedbugs' == $type) $bugs  = $this->kevindefine->getProjectBugsByPostponedbugs($projectID, $orderBy, $pager, $build);
		else if('allbugs' == $type) $bugs  = $this->kevindefine->getProjectBugsALL($projectID, $orderBy, $pager, $build);
        $users = $this->user->getPairs('noletter');

        /* Assign. */
        $this->view->title     = $title;
        $this->view->position  = $position;
        $this->view->bugs      = $bugs;
        $this->view->tabID     = 'bug';
        $this->view->build     = $this->loadModel('build')->getById($build);
        $this->view->buildID   = $this->view->build ? $this->view->build->id : 0;
        $this->view->pager     = $pager;
        $this->view->orderBy   = $orderBy;
        $this->view->users     = $users;
        $this->view->productID = $productID;
		$this->view->pageID	   = $pageID;
		$this->view->type	   = $type;
		$this->view->projectID = $projectID;
		
        $this->display();
    }
}
