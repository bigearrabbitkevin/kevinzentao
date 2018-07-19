<?php
//------------------1. cardId-------------
 if(isset($user->cardId)):
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
