<?php
if(isset($user->code)):
$codeStr = html::input('code', $user->code, "class='form-control'");
$tr = <<<EOT
  <th>{$lang->user->code}</th>
EOT;
?>
<script>
var row1 = $('#realname').parent().parent()[0];
row1.removeChild(row1.cells[3]);
var appendHTML =<?php echo json_encode($tr);?>;
$('#realname').parent().after(appendHTML);
row1.cells[3].innerHTML = <?php echo json_encode($codeStr);?>;
</script>
<?php endif;
if(isset($user->deptdispatch)):
$deptdispatch = (0 == $user->deptdispatch) ? $user->dept : $user->deptdispatch;
$codeStr = html::select('deptdispatch', $depts, $deptdispatch, "class='form-control chosen'");
$tr = <<<EOT
<tr>
  <th>{$lang->user->deptdispatch}</th>
  <td class='w-p40' colspan='3'>$codeStr</td>
</tr>
EOT;
?>
<script>
var row1 = $('#role').parent().parent()[0];

var appendHTML =<?php echo json_encode($tr);?>;
$('#role').parent().parent().after(appendHTML);
</script>
<?php endif;?>
<script>
  $('#account').attr('ReadOnly','true');
</script>";
<?php if(isset($user->cardId)):
	$cardidStr = html::input('cardId', $user->cardId, "class='form-control'");
?>
<script>
var newTr = document.createElement("tr");
var newtd =  document.createElement("th");
newtd.innerHTML = <?php echo json_encode('Card ID');?>;
newTr.appendChild(newtd);
newtd =  document.createElement("td");
newtd.innerHTML = <?php echo json_encode($cardidStr);?>;
newTr.appendChild(newtd);
document.getElementsByTagName('tbody')[0].appendChild(newTr);</script>
<?php endif;?>