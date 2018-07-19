<?php

if(isset($lang->user->code)):
$codeStr = html::input('code', '', "class='form-control'");
$tr = <<<EOT
<tr>
  <th>{$lang->user->code}</th>
  <td>$codeStr</td>
</tr>
EOT;
?>
<script>
var appendHTML = <?php echo json_encode($tr);?>;
$('#realname').parent().parent().after(appendHTML);
</script>

<?php
endif;
if(isset($lang->user->deptdispatch)):
$codeStr = html::select('deptdispatch', $depts, $deptID, "class='form-control chosen'");
$tr = <<<EOT
<tr>
  <th>{$lang->user->deptdispatch}</th>
  <td class='w-p50'>$codeStr</td>
</tr>
EOT;
?>
<script>
var appendHTML = <?php echo json_encode($tr);?>;
$('#dept').parent().parent().after(appendHTML);
</script>
<?php
endif;
?>
