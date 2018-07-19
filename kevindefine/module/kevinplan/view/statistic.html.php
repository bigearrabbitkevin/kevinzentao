<?php include '../../kevinhours/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<!-- ECharts单文件引入 -->
<?php js::import($jsRoot . 'echarts/echarts.min.js');?> 
<?php
if (!isset($this->kevinplan->Filter)) {
	echo 'Error. Please set Filter First!';
	die();
}
$Filter		 = & $this->kevinplan->Filter;
$deptlist[0] = ''; //empty

$Filter->groupcreate = 0;
$Filter->url		 = helper::createLink('kevinplan', 'projectFilter');

$Filter->deptlist = $this->kevinplan->getdeptpairs(0, 'Detail');

//设定不需要显示的列
$Filter->ShowColList['member']	 = 0;
$Filter->ShowColList['project']	 = 0;
$Filter->ShowColList['projectName']	 = 0;
$Filter->ShowColList['deleted']	 = 0;

$Filter->methodName = 'statistic';
?>
<div class='side' id='treebox'>
	<a class='side-handle' data-id='kevinplanTree'><i class='icon-caret-left'></i></a>
	<div class='side-body'>
		<div class='panel panel-sm'>
			<div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']);?> <strong>
					<?php common::printLink('kevinplan', 'memberlist', "type=&plan=&project=0", $lang->kevinplan->planFilter);?></strong>
			</div>	
			<div >
				<?php include 'projectfilter.html.php';?>
			</div>
			<div class='panel-heading nobr'><?php echo "<i class='icon-bar-chart'></i>";?> <strong><?php echo '统计列表';?></strong></div>
			<div class='panel-body'>
				<ul class='tree'>
					<?php
					foreach ($this->lang->kevinplan->statisticType as $currentStatisticType => $currentStatisticName) {
						echo '<li>' . html::a(helper::createLink('kevinplan', 'statistic', "type=$currentStatisticType"), $currentStatisticName, '', "class='link'");
						if ($statisticType == $currentStatisticType) echo html::icon($lang->icons['story']);
						echo '</li>';
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>	
<div class='main'>
	<?php
	if ($statisticType) include 'statistic' . $statisticType . '.html.php';
	else echo '<h1>no such type:"' . $statisticType . ' "</h1>';
	?>
</div>

<?php include '../../kevincom/view/footer.html.php';?>