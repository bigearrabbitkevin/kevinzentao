<?php
$href = helper::createLink('kevindefine', 'createTask', 'storyID=currentStoryID');
$this->loadModel("kevindefine");
$title = $this->lang->kevindefine->decomposition;
?>
<script>
var id,name,appendHTML,actionTd
var checkboxArray = $('input');
var href = "<?php echo $href;?>";
for (var i=0; i < checkboxArray.length; i++) 
{
	name = checkboxArray[i].name;
	if( name.substring(0,11) == 'storyIDList')
	{
		id = name.substring(12,name.length -1);
		var currentHref = href.replace(/currentStoryID/g, id);
		actionTd = $(checkboxArray[i]).parent().parent().children('td').last();
		appendHTML = '<a href=' + currentHref + ' target="" class="btn-icon export" title="<?php echo $title?>"><i class="icon-task-create icon-smile"></i></a>';
		actionTd.prepend(appendHTML);
	}
}
</script>

