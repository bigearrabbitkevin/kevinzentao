<?php
$param		 = "status=$status";
$createTask	 = html::a(helper::createLink('kevindefine', 'exportproject', $param)
		, "<i class='icon-download-alt'></i> {$this->lang->export}", '', "class='btn export'");
?>
<script>
	var appendHTML = <?php echo json_encode($createTask); ?>;
	$('div.actions').prepend(appendHTML);
</script>