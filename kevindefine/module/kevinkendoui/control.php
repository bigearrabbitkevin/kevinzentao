<?php
/**
 * The sample of Kendo UI
 *
 * @copyright   Kevin
 * @license    Free
 * @author     Kevin
 * @package     kevinkendoui
 * @version    1.0
 * @link        http://www.zentao.net
 */
?>
<?php

class kevinkendoui extends control {

    public function __construct() {
        parent::__construct();
    }

    public function sample() {
        $this->display();
    }

    public function getlist($type) {
        switch ($type) {
            case 'Computer':
                $items = $this->kevinkendoui->getlistComputer();
                break;
            case 'All':
                $items = $this->kevinkendoui->getlistAll();
                break;
        }
        echo json_encode($items);
    }

}

