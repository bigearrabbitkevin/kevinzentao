<?php
$createTask = '';
$this->loadModel("kevindefine");
$param = "story={$story->id}";
$createTask = html::a(helper::createLink('kevindefine', 'createtask', $param), "<i class='icon-smile'></i> {$this->lang->kevindefine->decomposition}", '', "class='btn export'");
if($createTask <>"")
{
?>
<script>
var appendHTML = <?php echo json_encode($createTask);?>;
var actions = $('div.actions');
for (var i=0; i < actions.length; i++) 
{
	var div1 = $(actions[i]).children().first();
	div1.prepend( appendHTML);
}
</script>
<?php
}
?>