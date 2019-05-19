<?php 
$this->renderPartial('_index',   array(
  'arrayBudgetTopOverPassList'=>$arrayBudgetTopOverPassList,
  'partidas' => $partidas,
  'project' => $project,
  'maxid' => $maxid,
  'budgetName' => $budgetName,
  'budgetId' => $budgetId,  
)); 
?>