<?php
/**
 * kevin calendar
 * @copyright   kevin
 */
?>
<?php include '../../kevincom/view/header.html.php'; ?>
<?php
include 'titlebar.html.php';

$hasTodoCreate	 = common::hasPriv('todo', 'create');
?>

<div style="margin: 0" class="op-calendar-new">
	<?php
	$statusArray	 = $this->kevinhoursb->getInofoByMonth($type, $controlType);
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
									$strTemp = '<td><div class = "div-kevinhoursb-day ';
									$currentItem = current($statusArray);
									$day		 = $currentItem->date;
									$status		 = $currentItem->status;
									$type		 = $currentItem->type;
									$showDay	 = date('j', strtotime($day)) or '&nbsp;';
									next($statusArray); //索引下移
									if ('C' == $type) {
										if ('hol' == $status) {
											$strTemp .= 'kevinhoursb-day-S';
										} else if ('law' == $status) {
											$strTemp .= 'kevinhoursb-day-H';
										} else if ('nor' == $status) {
											$strTemp .= 'kevinhoursb-day-W';
										} else {
											$strTemp .= 'kevinhoursb-day-otherMonth';
										}
									} else {
										$strTemp .= 'kevinhoursb-day-otherMonth';
									}

									$strTemp .= '">';
									$strTemp .= $showDay;
									$strTemp .= '<div class = "pull-right text-muted">';
									if ($currentItem->desc){
										$strTemp .= $currentItem->desc;
									}
									if ($hasTodoCreate) {
										$strTemp .= html::a(helper::createLink('todo', 'create'
											, "date=" . str_replace('-', '', $day), '', true)
											, "<i class='icon-plus-sign'></i> ", '', " data-toggle='modal' data-type='iframe'");
									}
									$strTemp .='</div>';

									if (!empty($currentItem->todos)) {
										foreach ($currentItem->todos as $todo) {
											$href = $this->createLink('todo', 'view', "id=$todo->id", '', true);
											$strTemp .= "<a href=$href data-toggle='modal' data-type='iframe'><div class='fc-event'><span>{$todo->name}</span></div></a>";
										}
									}
									$strTemp .= '</div></td>';
									echo $strTemp;
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
    <div style = "display: none;" class = "op-calendar-new-holidaytip"></div>
</div>

<?php
include '../../kevincom/view/footer.html.php';
