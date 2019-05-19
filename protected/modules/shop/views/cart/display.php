<?php
  $this->breadcrumbs=array(
  	//$cart->title,
  	'cart'
  );
?>

  <div class="row">
    <h3>Resumen de su carrito</h3>
  </div>

  <div class="row">
    <div class="col-md-9 col-lg-9">
      <div id="cart">
        <?php 
          $this->renderPartial('_display',
                                array(
                                  'cart'=>$cart,
                                  'total'=>$total,
                                ));
        ?>
      </div>
    </div>
    <div class="col-md-3 col-lg-3">
        <?php $this->renderPartial('_summary', array(
                                  'cart'=>$cart,
                                  'total'=>$total,
                                )); 
        ?>
    </div>
  </div>

  <div class="row">
      <div class="col-md-9 col-lg-9">
      </div>
  <?php if($isNewUser==false): ?>
      <div class="col-md-3 col-lg-3">
        <?php $url=Yii::app()->createUrl('user/register'); ?>
        <a href="<?php echo $url; ?>" type="button" id="btnContinuar" class="btn btn-primary btn-sm">
          Pagar
        </a>        
      </div>
  <?php endif; ?>
  </div>

  <div class="row">
  <?php if($isNewUser): ?>
  <?php 
    $this->renderPartial('//user/billingform', array('model'=>$fiscalData));
  ?>
  <?php endif; ?>
  </div>

<!-- sección de metodos de pago -->
  <div class="row">
  <?php 
    $this->renderPartial('/payment/payment');
  ?>
  </div>
