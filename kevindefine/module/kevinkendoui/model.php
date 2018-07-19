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

class kevinkendouiModel extends model {

    /**
     * Construct function, load model of kevinkendoui.
     * 
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    public function getlistComputer() {
        $stmt = $this->dao->select('a.*, count(a.id) AS sumCount')->from(TABLE_KEVINKENDOUI)->alias('a')
                ->GroupBy('a.computer')
                ->orderBy('sumCount desc')
                ->query();
        return $stmt->fetchAll();
    }

    public function getlistall() {
        $stmt = $this->dao->select('*')->from(TABLE_KEVINKENDOUI) ->query();
        return $stmt->fetchAll();
    }
}
