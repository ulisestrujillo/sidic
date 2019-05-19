<?php 
$this->renderPartial('_form',   array(
  'order' => $order,
  'orderdetail' => $orderdetail,
  'suppliers' => $suppliers,
  'partida' => $partida,
  'subpartida' => $subpartida,
  'project' => $project,
  'budgetid' => $budgetid,
  'importeTasaCero' => $importeTasaCero,
)); 
?>