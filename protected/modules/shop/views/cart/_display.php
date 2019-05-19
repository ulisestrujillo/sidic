
    <?php 
      $session=Yii::app()->session;
      if($session['cart']==null)
      {
        echo 'Volver a la lista de paquetes.';
      }
    ?>

    <?php 

    for ($pos=0; $pos < sizeof($cart); $pos++) {
  ?>
    
    <div class="row">
      <div class="col-md-2">
        <?php echo $cart[$pos]->name; ?>
      </div>
      <div class="col-md-4">
        <?php echo $cart[$pos]->description; ?>
      </div>
      <div class="col-md-2">
        <?php echo number_format($cart[$pos]->price, 2, '.', ','); ?>
      </div>
      <div class="col-md-1">
        <?php echo number_format($cart[$pos]->total, 2, '.', ','); ?>
      </div>
      <div class="col-md-1 text-right">
  <?php 
        /*echo CHtml::ajaxLink("remove item",
                                CController::createUrl('cart/removeajax&pos='.$pos), 
                                array('update' => '#cart'),
                              );*/

        echo CHtml::ajaxLink(
          $text = '&nbsp;&nbsp;quitar', 
          $url = CController::createUrl('cart/RemoveAjax/pos/'.$pos), 
          $ajaxOptions=array (
              'type'=>'GET',
              //'dataType'=>'json',
              'success'=>'function(html){
                  jQuery("#cart").html(html);
                  jQuery("#btnContinuar").remove();
                }'
              ), 
          $htmlOptions=array ()
        );

  ?>

      </div>
    </div>
<?php }?>

