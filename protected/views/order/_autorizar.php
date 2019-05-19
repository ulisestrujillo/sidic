<!-- https://fengyuanchen.github.io/datepicker/ -->
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://malsup.github.io/jquery.blockUI.js"></script>

<ol class="breadcrumb">
  <li><a href="<?php echo Yii::app()->createUrl('project/admin'); ?>">Proyectos</a></li>
  <li><a href="<?php echo Yii::app()->createUrl('budget/list', array('id'=>$_GET["projectid"])); ?>">Presupuesto</a></li>
  <li><a href="<?php echo Yii::app()->createUrl('budget/', array('id'=>$_GET["budgetid"])); ?>">Partidas</a></li>
  <li><a href="<?php echo Yii::app()->createUrl('order/budget', array('id'=>$_GET["id"])); ?>">Ordenes</a></li>
  <li class="active">
    <?php 
      if($order->isNewRecord){
        echo 'Nueva orden de compra: '; 
      }else{
        echo 'Orden de compra: '.$_GET["orderid"];
      }
    ?>
  </li>
</ol>

<div class="row">

<?php if(Yii::app()->user->hasFlash("cantCancel")): ?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?php echo Yii::app()->user->getFlash("cantCancel"); ?>
</div>
<?php endif; ?>
<?php if(Yii::app()->user->hasFlash("topOverpass")): ?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?php echo Yii::app()->user->getFlash("topOverpass"); ?>
</div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash("missingDetail")): ?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?php echo Yii::app()->user->getFlash("missingDetail"); ?>
</div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash("orderSaved")): ?>
<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?php echo Yii::app()->user->getFlash("orderSaved"); ?>
</div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash("orderPreviousCanceled")): ?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?php echo Yii::app()->user->getFlash("orderPreviousCanceled"); ?>
</div>
<?php endif; ?>

</div>
  <br/>

<style>
textarea{text-transform: uppercase;}
input[type="text"]{text-transform: uppercase;}
input.error{border:1px solid #e61616;}
textarea.error{border:1px solid #e61616;}
div.form-group.has-error{color:#e61616;}
fieldset{
  border: 1px solid #ddd !important;
  margin: 0;
  xmin-width: 0;
  padding: 10px;       
  position: relative;
  border-radius:4px;
  padding-left:10px!important;
} 
  
legend{
  font-size:12px;
  margin-bottom: 0px;
  width: 35%; 
  border: 1px solid #ddd;
  border-radius: 4px; 
  padding: 5px 5px 5px 10px; 
  background-color: #ffffff;
}
</style>

<link rel="stylesheet" href="<?php echo Yii::app()->baseUrl.'/protected/vendor/datepicker/dist/datepicker.css'; ?>">

<form target="_blank" method="post" action="<?php echo Yii::app()->createUrl('order/print'); ?>" name="printForm">
  <input type="hidden" name="orderid" value="<?php echo $order->id; ?>">
</form>

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'Order',
  'enableAjaxValidation'=>false,
  'htmlOptions' => array('enctype'=>'multipart/form-data', 'class'=>'', 'name'=>'Order'),
)); ?> 
<?php CHtml::$errorContainerTag = 'div'; ?> 
<input type="hidden" name="Order[budgetid]" value="<?php echo $budgetid; ?>">

<div class="row">
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Folio</label></div>
      <div class="col-md-6"><input type="text" value="<?php echo $order->isNewRecord ? 0 : $order->id; ?>" name="Order[id]" disabled="disabled" class="form-control input-sm"></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Tipo</label></div>
      <div class="col-md-6">
    <?php $ordertype=$order->ordertype; ?>
    <?php echo $ordertype; ?>
      </div>
    </div>
  </div>  
</div>

<div class="row">
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Estatus</label></div>
      <div class="col-md-6">
        <?php $statusid=$order->statusid; ?>
        <input type="hidden" value="<?php echo $order->isNewRecord ? 1:$order->statusid; ?>" id="Order_statusid" name="Order[statusid]" />
        <select onchange="setStatus(this);" class="form-control input-sm" id="Order_statusid" name="Order[statusid]" <?php echo $order->isNewRecord == 1 ? "disabled='disabled'":""; ?>>
          <option <?php echo $order->isNewRecord == 1 ? "selected='selected'":""; ?> value="<?php echo Order::STATUS_COLOCADA; ?>"><?php echo Order::COLOCADA; ?></option>
          <option value="<?php echo Order::STATUS_AUTORIZADA; ?>" <?php echo $statusid==Order::STATUS_AUTORIZADA ? "selected":"" ?>><?php echo Order::AUTORIZADA; ?></option>

  <?php if($order->statusid==Order::STATUS_CANCELADA): ?>
          <option value="<?php echo Order::STATUS_CANCELADA; ?>" <?php echo $statusid==Order::STATUS_CANCELADA ? "selected":"" ?>><?php echo Order::CANCELADA; ?></option>
  <?php endif; ?>

          <option value="<?php echo Order::STATUS_SURTIDA; ?>" <?php echo $statusid==Order::STATUS_SURTIDA ? "selected":"" ?>><?php echo Order::SURTIDA; ?></option>
          <option value="<?php echo Order::STATUS_PARCIAL; ?>" <?php echo $statusid==Order::STATUS_PARCIAL ? "selected":"" ?>><?php echo Order::PARCIAL; ?></option>
        </select>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Partida</label></div>
      <div class="col-md-6"><input class="form-control input-sm" type="text" value="<?php echo $partida->name; ?>" disabled="disabled"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Sub-Partida</label></div>
      <div class="col-md-6"><input class="form-control input-sm" type="text" value="<?php echo $subpartida; ?>" disabled="disabled"></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Fecha inicio</label></div>
      <div class="col-md-6">
<?php $value=$order->isNewRecord ? '':date('Y-m-d', $order->initdate); ?>
<?php if(Yii::app()->user->checkAccess('admin')): ?>
        <?php echo $form->textField($order, 'initdate', array('value'=>"$value", 'class'=>'form-control input-sm', 'data-toggle'=>'datepicker', 'placeholder'=>'aaaa-dd-mm')); ?>
        <?php echo $form->error($order,'initdate', array('class'=>'form-group has-error')); ?>
<?php else: ?>
<?php echo date('Y-m-d', $order->initdate); ?>
<input type="hidden" name="Order[initdate]" value="<?php echo $order->initdate; ?>" />
<?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="col-md-12 col-sm-12">
      <?php if($order->statusid!=Order::STATUS_CANCELADA): ?>
        <button type="button" id="btnSave" onclick="validate();" class="btn btn-primary btn-sm">
          <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
        </button>
      <?php endif; ?>
  </div>
</div>
<br/>
<div class="row">
  <div class="col-md-6">
    <fieldset> 
      <legend>Información del proyecto</legend>
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 col-lg-4 control-label">Proyecto</label>
            <div class="col-sm-8">
              <small><?php echo $project->name; ?></small>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-6">
          <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 col-lg-2 control-label">Plaza</label>
            <div class="col-sm-10 col-lg-10">
              <small><?php echo $project->location; ?></small>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-lg-12">
          <div class="form-group">
            <div class="col-sm-12 col-lg-12">
            <label for="inputPassword3" >Proveedor</label>
<?php if(Yii::app()->user->checkAccess('admin')): ?>
              <select class="form-control input-sm" name="Order[supplierid]">
                <?php 
                  foreach ($suppliers as $supplier) {
                    ?><option <?php echo $order->supplierid == $supplier->id ? 'selected' : '';  ?> value="<?php echo $supplier->id; ?>"><?php echo $supplier->id.'-'.strtoupper($supplier->code).' - '.strtoupper($supplier->name); ?></option><?php
                  }
                ?>
              </select>
<?php else: ?>              
<?php echo $order->supplier->name; ?>
<input type="hidden" name="Order[supplierid]" value="<?php echo $order->supplier->id;?>" />
<?php endif; ?>              
            </div>
          </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-lg-12">
          <div class="form-group">
            <?php echo $form->labelEx($order,'address',  array('for'=>'address', 'class'=>'col-sm-3 col-lg-3 control-label')); ?>
            <div class="col-sm-9">
              <?php $address=$order->isNewRecord ? $project->address : $order->address; ?>
              <?php if(Yii::app()->user->checkAccess('admin')): ?>
              <?php   echo $form->textArea($order, 'address', array('value'=>"$address", 'class'=>'form-control input-sm', 'maxlength'=>'500', 'rows'=>'3')); ?>
              <?php   echo $form->error($order,'address', array('class'=>'form-group has-error')); ?>            
              <?php else: ?>              
                <input type="hidden" name="Order[address]" value="<?php echo $address ?>" />
                <?php echo $address; ?>
              <?php endif; ?>              
            </div>
          </div>
        </div>
      </div>
    </fieldset>
  </div>

  <div class="col-md-6">
    <fieldset>     
      <legend>Datos de entrega</legend>

  <div class="row">
  <div class="form-group">
      <label for="inputEmail3" class="col-sm-3 col-md-3 col-lg-3 control-label">Qui&eacute;n recibe</label>
      <div class="col-sm-9 col-lg-9">
        <?php $deliverto = $order->isNewRecord ? Yii::app()->user->name : $order->deliverto; ?>
        <?php echo $deliverto; ?>
      </div>
  </div>
  </div>
  <div class="row">
  <div class="form-group">
      <label for="inputPassword3" class="col-sm-3 col-md-3 col-lg-3 control-label">Tel&eacute;fono</label>
      <div class="col-sm-9 col-lg-9">
        <?php echo $order->phone; ?>
      </div>
    </div>
  </div>
  <div class="row">
  <div class="form-group">
      <label for="inputPassword3" class="col-sm-3 col-md-3 col-lg-3 control-label">Observaciones</label>
      <div class="col-sm-9 col-lg-9">
        <?php echo $order->comment; ?>
      </div>
    </div>
  </div>


    </fieldset>  
  </div>
</div>

<?php $this->endWidget(); ?>

<script src="<?php echo Yii::app()->baseUrl.'/protected/vendor/datepicker/dist/datepicker.min.js' ?>"></script>
<script>
  $(function() {
    $('[data-toggle="datepicker"]').datepicker({
      autoHide: true,
      zIndex: 2048,
      autoPick: true,
      format: 'yyyy/mm/dd',
    });
  });

  function setStatus(obj){
    $("#Order_statusid").val(obj.value);
  }

  function validate(){
    if($("#Order_statusid").val()==<?php echo Order::STATUS_CANCELADA; ?>){
      bootbox.confirm({
          message: "¿Seguro que desea cancelar la Órden de Compra, si lo hace no la podrá editar nuevamente?",
          buttons: {
              confirm: {
                  label: 'Si',
                  className: 'btn-success'
              },
              cancel: {
                  label: 'No',
                  className: 'btn-danger'
              }
          },
          callback: function (result) {
            if(result){
              document.forms["Order"].submit();
            }
          }
      });
    }else{
      document.forms["Order"].submit();
    }
  }

</script>


<script src="<?php echo Yii::app()->theme->baseUrl;?>\assets\bootbox.min.js"></script>

