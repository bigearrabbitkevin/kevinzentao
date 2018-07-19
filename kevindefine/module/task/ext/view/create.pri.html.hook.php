<?php
$currentStory = false;
if(0 != $task->story)$currentStory = $this->story->getById($task->story);
$tr = <<<EOT
  <td class='text-right'>
  <table align = 'right' width = 100%><tr><td class='text-right'>同需求：</td>
  <td><span class="input-group-btn"> <a href="javascript:copyStoryInfo();" id="copyInfoButton" class="btn"> 所有</a></span></td></table>
  </td>
EOT;
?>
<script>
var appendHTML = <?php echo json_encode($tr);?>;
$('#type').parent().after(appendHTML);

if(document.getElementById('pri').selectedIndex == 0)document.getElementById('pri').selectedIndex = 1;
if($('#type').attr('value') == '') $('#type').attr('value', 'devel'); 

/* Copy story info to task info. */
function copyStoryInfo()
{
	<?php if($currentStory && "" != $currentStory->assignedTo):?>
	if(document.getElementById('assignedTo').value == '')
	{
		document.getElementById('assignedTo').value = '<?php echo $currentStory->assignedTo;?>';
		var spanObj = document.getElementById('assignedTo_chosen').firstChild.firstChild;
		spanObj.innerHTML = <?php echo "\"" . $members[$currentStory->assignedTo] . "\"";?>;
		var abbr=document.createElement("abbr");
		abbr.className = 'search-choice-close';
		spanObj.parentNode.appendChild(abbr);
	}
	<?php endif?>
	KevinCopyStoryDefault();
	if(document.getElementById('aftercontinueAdding').checked) document.getElementById('aftertoStoryList').checked = 'checked';
}
</script>
