<?php 

class OrderBO
{

  /*
  *@order  es el model order
  */
  public function saveOrder($order){
    if(!$this->orderExists($userId)){
    //crear la orden
    }

    //regresa el orderId
    return null;
  }

  public function orderExists($orderId){
    //comparar por fecha también
    $query='select * from cart_order where id = '.$orderId;

    $command=Yii::app()->db->createCommand($query);
    $res=$command->queryAll();

    if($res){
      return true;
    }

    return false;
  }

}