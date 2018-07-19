<?php if (!isset($subMenu)) $subMenu = ''; ?>
<div id='featurebar'>
	<div class='actions'>
		<?php
		if ($methodName == "itemlist") common::printIcon('kevinstore', 'itemcreate', '', '', 'button', 'plus');
		//else common::printIcon('kevinstore', 'groupcreate', '', '', 'button', 'plus', '', 'iframe', true, "data-width='550'");
		?>
	</div>
	<?php if (isset($showStyle)) : ?>
		<div id = "ShowStyleDiv" class='pull-right'>
			<form id='ShowStyleForm' method="post" class='form-condensed'>
				<ul class='nav'>
					<li><?php echo $lang->kevinstore->showStyle; ?></li>
					<li><?php echo html::select("showStyle", $lang->kevinstore->showStyleList, $showStyle, "class='' onchange= \"SubmitFormByID('ShowStyleForm')\" "); ?>	</li>
				</ul>
			</form>
		</div>
	<?php endif; ?>
</div>