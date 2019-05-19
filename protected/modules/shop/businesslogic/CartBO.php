<?php

class CartBO
{
  public function findItemInOrder($orderId, $itemId){
  	//comparar por fecha también
    $query='select * from cart_orderdetail where orderid = '.$orderId.' and itemid='.$itemId;

    $command=Yii::app()->db->createCommand($query);
    $res=$command->queryAll();

  	if($res){
  	  return $res;
  	}

  	return null;
  }

  public function getItem($itemId)
  {
    $model = Product::model()->findByPk($itemId);
    $item = $this->fillItem($model);

    if(isset($item))
    {
      $item->total = $item->qty * $item->price;
    }

    return $item;
  }

  public function addItem($item, $orderId){
    if(!$this->inStock($item->id)){
      return "sin existencia";
    }

    if($this->findItemInOrder($orderId, $item->id) != null){
      //actualiza precios en la orden y en el detalle
    }

  	$query="insert into cart_orderdetail(orderid, itemid, name, description, sku, qty, price, size, discount) "
  	."values(".$orderId.",".$item->id.",'".$item->name."','".$item->description."',".$item->sku.",".$item->qty.",".$item->price.",'".$item->size."',".$item->discount.")";

    $command=Yii::app()->db->createCommand($query);

    $res=$command->execute();

  	if($res){
  	  return $res;
  	}

  	return false;
  }

  /*
  *revisa las existencias
  */
  public function inStock($id){
    return 0;
  }

  public function removeItem($id){

  }

  private function fillItem($product)
  {
    $item=new Item();

    $item->id           = $product->id;
    $item->name         = $product->name;
    $item->image        = $product->image;
    $item->description  = $product->description;
    $item->sku          = $product->code;
    $item->qty          = 1;
    $item->price        = $product->price;
    $item->discount     = 0.00;
    $item->total        = 0.00;
    $item->size         = '';
    $item->color        = $product->color;

    return $item;
  }

  public function getTotalToPay()
  {
    $total=0.00;
    $session=Yii::app()->session;

    for ($pos=0; $pos < sizeof($session['cart']); $pos++) {
        $total += $session['cart'][$pos]->qty*$session['cart'][$pos]->price;
    }

    return $total;
  }

}
