<?php 

interface BudgetService
{

  public function getBudgetById($id);
  /*recibe el budgetid*/
  public function getPartidasSubPartidasRel($budgetid);

}
