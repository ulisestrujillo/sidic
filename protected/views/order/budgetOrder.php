<?php
$this->renderPartial('_budgetOrder', array(
  'model' => $model,
  'orders' => $orders,
  'budgetid'=>$budgetid
));
?>