<?php
include '../../control.php'; 
class myProject extends project
{
    /**
     * Browse stories of a project.
     * 
     * @param  int    $projectID 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function story($projectID = 0, $orderBy = '', $type = 'unclosed', $param = 0, $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        /* Load these models. */
        $this->loadModel('story');
        $this->loadModel('user');
        $this->loadModel('task');
        $this->app->loadLang('testcase');

        /* Save session. */
        $this->app->session->set('storyList', $this->app->getURI(true));

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->projectStoryOrder ? $this->cookie->projectStoryOrder : 'pri';
        setcookie('projectStoryOrder', $orderBy, $this->config->cookieLife, $this->config->webRoot);

        $project = $this->commonAction($projectID);

		$this->loadModel('kevindefine');
		
        /* Header and position. */
        $title      = $project->name . $this->lang->colon . $this->lang->project->story;
        $position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[] = $this->lang->project->story;

        /* The pager. */
		$stories = array();
		if('unclosed' == $type) $stories    = $this->kevindefine->getProjectStoriesUnclosed($projectID, $orderBy);
		else if('allstory' == $type)$stories    = $this->story->getProjectStories($projectID, $orderBy);
		else if ('assignedtome' == $type) $stories    = $this->kevindefine->getProjectStoriesByMe($projectID, $orderBy, 'assignedTo');
		else if('openedbyme' == $type) $stories    = $this->kevindefine->getProjectStoriesByMe($projectID, $orderBy, 'openedBy');
		else if('reviewedbyme' == $type) $stories    = $this->kevindefine->getProjectStoriesByMe($projectID, $orderBy, 'reviewedBy');
		else if('closedbyme' == $type) $stories    = $this->kevindefine->getProjectStoriesByMe($projectID, $orderBy, 'closedBy');
		else if('draftstory' == $type) $stories    = $this->kevindefine->getProjectStoriesByStatus($projectID, $orderBy, 'draft');
		else if('activestory' == $type) $stories    = $this->kevindefine->getProjectStoriesByStatus($projectID, $orderBy, 'active');
		else if('changedstory' == $type) $stories    = $this->kevindefine->getProjectStoriesByStatus($projectID, $orderBy, 'changed');
		else if('willclose' == $type) $stories    = $this->kevindefine->getProjectStoriesWillClose($projectID, $orderBy);
		else if('closedstory' == $type) $stories    = $this->kevindefine->getProjectStoriesByStatus($projectID, $orderBy, 'closed');
		else if('uncreatetask' == $type) $stories    = $this->kevindefine->getProjectStoriesUncreateTask($projectID, $orderBy);

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);
        $storyTasks = $this->task->getStoryTaskCounts(array_keys($stories), $projectID);
        $users      = $this->user->getPairs('noletter');

        /* Save storyIDs session for get the pre and next story. */
        $storyIDs = '';
        foreach($stories as $story) $storyIDs .= ',' . $story->id;
        $this->session->set('storyIDs', $storyIDs . ',');

        /* Get project's product. */
        $productID = 0;
        $products = $this->loadModel('product')->getProductsByProject($projectID);
        if($products) $productID = key($products);

        /* Assign. */
        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->productID  = $productID;
		$this->view->projectID  = $projectID;
		$this->view->type  		= $type;
        $this->view->stories    = $stories;
        $this->view->summary    = $this->product->summary($stories);
        $this->view->orderBy    = $orderBy;
        $this->view->storyTasks = $storyTasks;
        $this->view->tabID      = 'story';
        $this->view->users      = $users;
        $this->view->param        = $param;

        $this->display();
    }
}
