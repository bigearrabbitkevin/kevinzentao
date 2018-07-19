<?php if (!isset($subMenu)) $subMenu = ''; ?>
<div id='featurebar'>
	<div class='actions'>
		<?php
		if ($subMenu == "devlist"){
			common::printIcon('kevindevice', 'devxlsexport', "groupID=$groupID&orderBy=$orderBy", '', 'button', 'print');
			common::printIcon('kevindevice', 'devcreate', '', '', 'button', 'plus');
		}
		elseif ($subMenu =='maintainlist')common::printIcon('kevindevice', 'maintaincreate', '', '', 'button', 'plus', '', 'iframe', true, "data-width='550'");
		elseif ($subMenu =='sendoutlist')common::printIcon('kevindevice', 'sendoutcreate', '', '', 'button', 'plus', '', 'iframe', true, "data-width='550'");
		elseif ($subMenu =='spotchklist')common::printIcon('kevindevice', 'spotchkcreate', '', '', 'button', 'plus', '', 'iframe', true, "data-width='550'");
		elseif ($subMenu == 'statistic'&&($statisticType=='maintain'||$statisticType=='sendout'))echo "<ul class='nav'><li>{$lang->kevindevice->year}</li><li class='w-100px'>".html::select('year', $years, $year, "class='form-control chosen' onchange=statisticbyear(this.value,'$statisticType')").'</li>';
		else common::printIcon('kevindevice', 'groupcreate', '', '', 'button', 'plus', '', 'iframe', true, "data-width='550'");
		?>
	</div>
	<?php if (isset($showStyle)) : ?>
		<div id = "ShowStyleDiv" class='pull-right'>
			<form id='ShowStyleForm' method="post" class='form-condensed'>
				<ul class='nav'>
					<li><?php echo $lang->kevindevice->showStyle; ?></li>
					<li><?php echo html::select("showStyle", $lang->kevindevice->showStyleList, $showStyle, "class='' onchange= \"SubmitFormByID('ShowStyleForm')\" "); ?>	</li>
				</ul>
			</form>
		</div>
	<?php endif; ?>
</div>