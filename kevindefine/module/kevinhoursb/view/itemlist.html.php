<?php include '../../kevinhours/view/header.html.php'; ?>
<?php include 'titlebar.html.php'; ?>

<?php
?>
<div class='main'>
	<div id='featurebar'>
		<ul class='nav'>
			<?php
			foreach ($lang->kevinhoursb->periods as $period => $label) {
				$vars = "date=$period";
				if ($period == 'before') $vars .= "&account={$app->user->account}&status=undone";
				echo "<li id='$period'>" . html::a(inlink('itemlist', $vars), $label) . '</li>';
			}
			?>
			<script>$('#<?php echo $month; ?>').addClass('active')</script>
		</ul>
		<?php echo $lang->kevinhoursb->ClassDept; ?>
	</div><!--'table table-condensed table-hover table-striped tablesorter table-fixed' -->
	<table class='' id='todoList' border = 1>
		<thead>
			<tr class='text-center'>
				<th class=' {sorter:false} nobr'><?php echo $lang->actions; ?></th>
				<th>    <?php echo $lang->idAB; ?></th>
				<th class='w-status'><?php echo $lang->kevinhoursb->YearMonth; ?></th>
				<th class='text-left'>  <?php echo $lang->kevinhoursb->realname; ?></th>
				<?php
				$tableTitle = '';
				for ($x = 1; $x <= 31; $x++) {
					$tableTitle.= '<th>' . $x . '</th>';
				}
				echo $tableTitle;
				?>
				<th class='w-status'><?php echo $lang->kevinhoursb->status; ?></th>
				<th class='w-id'>  <?php echo $lang->kevinhoursb->code; ?></th>
				<th class='w-100px'>  <?php echo $lang->kevinhoursb->DeptName; ?></th>
				<th> <?php echo $lang->kevinhoursb->ChuQin; ?></th>
				<th> <?php echo $lang->kevinhoursb->GongChu; ?></th>
				<th> <?php echo $lang->kevinhoursb->TiaoXiu; ?></th>
				<th> <?php echo $lang->kevinhoursb->ShangJia; ?></th>
				<th> <?php echo $lang->kevinhoursb->ChanJia; ?></th>
				<th> <?php echo $lang->kevinhoursb->BingJia; ?></th>
				<th> <?php echo $lang->kevinhoursb->ShiJia; ?></th>
				<th> <?php echo $lang->kevinhoursb->NianJia; ?></th>
				<th> <?php echo $lang->kevinhoursb->GuoJia; ?></th>
				<th> <?php echo $lang->kevinhoursb->ShuangXiu; ?></th>
				<th> <?php echo $lang->kevinhoursb->PingShi; ?></th>
				<th> <?php echo $lang->kevinhoursb->ZheHe; ?></th>
				<th> <?php echo $lang->kevinhoursb->ZhongBan; ?></th>
				<th> <?php echo $lang->kevinhoursb->YeBan; ?></th>
				<th> <?php echo $lang->kevinhoursb->DaYeBan; ?></th>
				<th> <?php echo $lang->kevinhoursb->Hours; ?></th>
				<th> <?php echo $lang->kevinhoursb->Days; ?></th>
				<th> <?php echo $lang->kevinhoursb->YuShu; ?></th>
				<th> <?php echo $lang->kevinhoursb->ZheHeDays; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$id = 0;
			foreach ($items as $item): $id+=1;
				$kaoq		 = '';
				$tableShow	 = '';
				for ($x = 1; $x <= 31; $x++) {
					$day = 'D' . $x;
					$kaoq .= $item->$day . ',';
					$tableShow.= '<td  border = 1 class="nobr">' . $item->$day . '</td>';
				}
				?>
				<tr class='text-center'>
					<td class='text-right nobr'>
						<?php
						commonModel::printIcon('kevinhoursb', 'itemview', "id=$item->id", '', 'list', 'search', '', 'iframe', true);
						commonModel::printIcon('kevinhoursb', 'itemedit', "id=$item->id", '', 'list', 'pencil', '', 'iframe', true);
						?>
					</td>
					<td><?php echo html::checkbox('ItemCheck' . $item->id, $item->id); ?></td>
					<td><?php echo $item->YearMonth; ?></td>
					<td class="nobr text-left"><?php echo $item->realname; ?></td>
					<?php echo $tableShow; ?>
					<td class="nobr"><?php echo $lang->kevinhoursb->statusList[$item->status]; ?></td>
					<td><?php echo $item->code; ?></td>
					<td class="nobr"><?php echo $item->DeptName; ?></td>
					<td> <?php echo $item->ChuQin; ?></td>
					<td> <?php echo $item->GongChu; ?></td>
					<td> <?php echo $item->TiaoXiu; ?></td>
					<td> <?php echo $item->ShangJia; ?></td>
					<td> <?php echo $item->ChanJia; ?></td>
					<td> <?php echo $item->BingJia; ?></td>
					<td> <?php echo $item->ShiJia; ?></td>
					<td> <?php echo $item->NianJia; ?></td>
					<td> <?php echo $item->GuoJia; ?></td>
					<td> <?php echo $item->ShuangXiu; ?></td>
					<td> <?php echo $item->PingShi; ?></td>
					<td> <?php echo $item->ZheHe; ?></td>
					<td> <?php echo $item->ZhongBan; ?></td>
					<td> <?php echo $item->YeBan; ?></td>
					<td> <?php echo $item->DaYeBan; ?></td>
					<td> <?php echo $item->Hours; ?></td>
					<td> <?php echo $item->Days; ?></td>
					<td> <?php echo $item->YuShu; ?></td>
					<td> <?php echo $item->ZheHeDays; ?></td>


				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
