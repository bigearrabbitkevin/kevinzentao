<?php include '../../kevinhours/view/header.html.php'; ?>
<?php
//头部判断
if (empty($statusArray)) {
	echo '<h1>Get calendar array error!</h1>';
	die();
}
?>
<div class='container mw-400px'>
	<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
		<div style="margin: 0" class="op-calendar-new">
			<?php
			//$month = month(statusArray.front());
			$frontday			 = 6;
			echo $monthItem->YearMonth;
			?>
			<div id = "kevinhoursb" class="kevinhoursb">
				<div class="op-calendar-new-left c-clearfix">
					<div class="op-calendar-new-table-box c-gap-top">
						<table width = '100%' border = 1 class="op-calendar-new-table">
							<tbody>
								<tr class="fc-first fc-last">
									<th style="width: 205px;" class="kv-Title-W">星期一</th>
									<th style="width: 205px;" class="kv-Title-W">星期二</th>
									<th style="width: 205px;" class="kv-Title-W">星期三</th>
									<th style="width: 205px;" class="kv-Title-W">星期四</th>
									<th style="width: 205px;" class="kv-Title-W">星期五</th>
									<th style="width: 206px;" class="kv-Title-W">星期六</th>
									<th style="width: 207px;" class="kv-Title-W">星期天</th>
								</tr>
								<?php
								$countOfThisMonth	 = count($statusArray);
								for ($index = 0; $index < 6; $index++) {
									echo '<tr style="vertical-align:top;">';
									for ($weekday = 0; $weekday < 7; $weekday++) {
										echo '<td>';
										echo '<div class = "pull-right text-muted">';
										echo '</div>';
										echo '<div class = "div-kv-day ';
										$showDay		 = '&nbsp;';
										$status			 = '';
										$type			 = '';
										$currentItem	 = current($statusArray);
										$day			 = $currentItem->date;
										$status			 = $currentItem->status;
										$type			 = $currentItem->type;
										$showDay		 = date('j', strtotime($day));
										next($statusArray); //索引下移
										$isCurrentMonth	 = ('C' == $type);
										if ($isCurrentMonth) { //当前月份
											if ('hol' == $status) {
												echo 'kv-day-S';
											} else if ('law' == $status) {
												echo 'kv-day-H';
											} else if ('nor' == $status) {
												echo 'kv-day-W';
											} else {
												echo 'kv-day-otherMonth';
											}
										} else {
											echo 'kv-day-otherMonth';
										}

										echo '">';
										if ($isCurrentMonth) echo $showDay;
										echo '</div>';

										if ($isCurrentMonth) {
											$day	 = 'D' . $showDay;
											$kaoq	 = $monthItem->$day;

											echo $kaoq;
										}

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
			<div style = "display: none;" class = "op-calendar-new-holidaytip"></div>
		</div>
	</form>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
