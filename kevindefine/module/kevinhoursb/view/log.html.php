<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4909 2013-06-26 07:23:50Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../kevincom/view/header.html.php'; ?>
<?php include 'titlebar.html.php'; ?>
<div style="margin: 0" class="op-calendar-new">
	<?php
	$statusArray = $this->kevinhoursb->getInofoByMonth($type);
	if (empty($statusArray)) {
		echo '<h1>Get calendar array error!</h1>';
	} else {
		$frontday			 = 6;
		?>
		<div id = "kevinhoursb" class="kevinhoursb">
			<div class="op-calendar-new-left c-clearfix">
				<div class="op-calendar-new-table-box c-gap-top">
					<table width = '100%' border = 1 class="op-calendar-new-table">
						<tbody>
							<tr class="fc-first fc-last">
								<th style="width: 205px;" class="kevinhoursb-Title-W">星期一</th>
								<th style="width: 205px;" class="kevinhoursb-Title-W">星期二</th>
								<th style="width: 205px;" class="kevinhoursb-Title-W">星期三</th>
								<th style="width: 205px;" class="kevinhoursb-Title-W">星期四</th>
								<th style="width: 205px;" class="kevinhoursb-Title-W">星期五</th>
								<th style="width: 206px;" class="kevinhoursb-Title-W">星期六</th>
								<th style="width: 207px;" class="kevinhoursb-Title-W">星期天</th>
							</tr>
							<?php
							$countOfThisMonth	 = count($statusArray);
							for ($index = 0; $index < 6; $index++) {
								echo '<tr style="vertical-align:top;">';
								for ($weekday = 0; $weekday < 7; $weekday++) {
									$showDay	 = '&nbsp;';
									$status		 = '';
									$type		 = '';
									$currentItem = current($statusArray);
									$day		 = $currentItem->date;
									$status		 = $currentItem->status;
									$type		 = $currentItem->type;
									$showDay	 = date('j', strtotime($day));
									$bgColor	 = ($currentDate === $day) ? 'bgColor=#00ff00' : '';
									$newDay		 = str_replace('-', '', $day);
									echo "<td $bgColor onClick='tdClick($newDay)'><div>";
									next($statusArray); //索引下移
									echo $showDay;
									echo '<div class = "pull-right text-muted">';
									echo html::a(helper::createLink('kevinhoursb', 'log'
													, "type=" . $newDay . '&logType=sql')
											, "<i class='icon-list-alt'></i> ", '', "title={$lang->kevinhoursb->sqlLog}");
									echo '</div>';
									echo '</div></td>';
								}
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
<div>
	<br>
	<div><?php
		if (common::hasPriv('kevinhoursb', 'logdelete')) {
			echo html::a(helper::createLink('kevinhoursb', 'logdelete', "type=$date", '', true)
					, "<i class='icon-remove'></i> " . $lang->kevinhoursb->logdelete, '', "class='btn' data-toggle='modal' data-type='iframe'");
		}
		if (common::hasPriv('kevinhoursb', 'logdeleteall')) {
			echo html::a(helper::createLink('kevinhoursb', 'logdelete', "type=all", '', true)
					, "<i class='icon-remove'></i> " . $lang->kevinhoursb->logdeleteall, '', "class='btn' data-toggle='modal' data-type='iframe'");
		}
		if (common::hasPriv('kevinhoursb', 'logdeletesql')) {
			echo html::a(helper::createLink('kevinhoursb', 'logdelete', "type=allsql", '', true)
					, "<i class='icon-remove'></i> " . $lang->kevinhoursb->logdeletesql, '', "class='btn' data-toggle='modal' data-type='iframe'");
		}
		?></div>

	<br>
	<?php echo html::textarea('desc', htmlspecialchars($contents), "rows=20 class=area-1 style='width:100%'"); ?>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
<script language="javascript">
	function tdClick(date) {
		link = createLink('kevinhoursb', 'log', 'type=' + date);
		location.href = link;
	}
</script>