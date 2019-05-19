<?php 
$this->renderPartial('_list',   array(
  'project' => $project,
  'budgetAccessList' => $budgetAccessList,
  'model' => $model,
  'budgets' => $budgets,
)); 
?>