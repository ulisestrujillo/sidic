<?php

class BudgetServiceImpl implements BudgetService
{

  public function getBudgetById($id)
  {
    $budget=Budget::model()->findByPk($id);
    return $budget;
  }

  /**
  *Devuelve un arreglo con la relacion de partidas subpartidas
  */
  public function getPartidasSubPartidasRel($budgetid){
    $partidas = BudgetItem::Model()->findAll(array(
                          "condition"=>"budgetid = $budgetid and parentid = 0 and status=1",
                          "order"=>"sort",
                      ));

    $orderSumTotal=0;

    $result=[];

    $index=0;
    $tope=0;
    $orderUrl="";
    foreach ($partidas as $partida) {
      $order = new Order();
      $orderSumTotal = $total = Yii::app()->db->createCommand("SELECT SUM(total) as total FROM `order` WHERE budgetitemid = $partida->id AND statusid IN (".Order::STATUS_AUTORIZADA.",".Order::STATUS_SURTIDA.",".Order::STATUS_PARCIAL.",".Order::STATUS_PORPAGAR.",".Order::STATUS_PAGADA.")" )->queryScalar();
      
      if($partida->status==1){
        $orderUrl="<span style='color:blue; cursor:pointer' onclick=\"viewOrders(0);\">Ver ordenes</span>";
        if(sizeof($partida->orders)>0)
          $orderUrl="<span style='color:blue; cursor:pointer' onclick=\"viewOrders($partida->id);\">Ver ordenes</span>";
      }else{
        $orderUrl="";
      }

      $budget=[];
      $budget["id"]=$partida->id;
      $budget["parentid"]=$partida->parentid;
      $budget["realparentid"]=$partida->id;
      $budget["partida"]=$partida->name;
      $budget["subpartida"]="";
      $budget["name"]=$partida->name;
      $orderSumTotal=number_format($orderSumTotal, 2, '.', ',');

      $tope = number_format($partida->budgettop, 2, '.', ',');
      $budget["tope"]=$tope;
      $budget["type"]="PARTIDA";
      $budget["active"]=$partida->status;

      $subpartidas= BudgetItem::Model()->findAll(array("condition" => "parentid = $partida->id and status=1 ","order"=>"sort"));

      $budget["oc"]="";

      if(sizeof($subpartidas)==0){
        if(strlen($orderUrl)>0){
          $budget["oc"]="$orderUrl - $$orderSumTotal";
        }
        else{
          $budget["oc"]="$$orderSumTotal";
        }
      }
      $result[++$index]=$budget;

      foreach ($subpartidas as $item) {
        $orderSumTotal = $total = Yii::app()->db->createCommand("SELECT SUM(total) as total FROM `order` WHERE budgetitemid = $item->id AND statusid IN (".Order::STATUS_AUTORIZADA.",".Order::STATUS_SURTIDA.",".Order::STATUS_PARCIAL.",".Order::STATUS_PORPAGAR.",".Order::STATUS_PAGADA.")")->queryScalar();
        
        if($item->status==1){
          $orderUrl="<span style='color:blue; cursor:pointer' onclick=\"viewOrders(0);\">Ver ordenes</span>";
          if(sizeof($item->orders)>0)
            $orderUrl="<span style='color:blue; cursor:pointer' onclick=\"viewOrders($item->id);\">Ver ordenes</span>";
        }else{
          $orderUrl="";
        }
        $budget=[];
        $budget["id"]=$item->id;
        $budget["parentid"]=$item->parentid;
        $budget["realparentid"]=$partida->id;
        $budget["partida"]="";
        $budget["subpartida"]=$item->name;
        $budget["name"]=$item->name;
        $orderSumTotal=number_format($orderSumTotal, 2, '.', ',');
        
        if(strlen($orderUrl)>0){
          $budget["oc"]="$orderUrl - $$orderSumTotal";
        }
        else{
          $budget["oc"]="$$orderSumTotal";
        }
        
        $tope = number_format($item->budgettop, 2, '.', ',');
        $budget["tope"]=$tope;
        $budget["type"]="SUBPARTIDA";
        $budget["active"]=$item->status;
        $result[++$index]=$budget;
      }

      $order = null;

    }

    return $result;

  }

}