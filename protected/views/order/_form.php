<title>SIDIC - Orden de compra <?php echo $order->id; ?></title>
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
        echo 'Orden de compra: '.$order->order_id;
      }
    ?>
  </li>
</ol>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  </div>

<?php
  $canUpdate=true;
  if(User::checkRole("elaborador") && $order->statusid==Order::STATUS_AUTORIZADA){
    $canUpdate=false;
  }
?>

<?php if(User::checkRole("elaborador") && $order->statusid==Order::STATUS_AUTORIZADA): ?>
<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  El estatus de la orden es AUTORIZADA, el usuario <i><b>elaborador</b></i> no tiene permiso para modificarla.
</div>
<?php endif; ?>

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

<form target="_blank" method="post" action="<?php echo Yii::app()->createUrl('order/printoc'); ?>" name="printForm">
  <input type="hidden" name="userid" value="<?php echo Yii::app()->user->id; ?>">
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
  <?php $estatusDeLaOrden=Order::getOrderStatusToString($order->statusid); ?>
  <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
      <?php echo $estatusDeLaOrden; ?>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-4">
      <div class="col-md-6"><label for="inputEmail3">Folio</label></div>
      <div class="col-md-6"><input type="text" value="<?php echo $order->isNewRecord ? 0 : $order->order_id; ?>" name="Order[id]" disabled="disabled" class="form-control input-sm"></div>
  </div>
  <div class="col-md-6">
    <div class="row">
      <div class="col-md-4"><label for="inputEmail3">Tipo</label></div>
      <div class="col-md-6">

<?php $ordertype=$order->ordertype; ?>
        <select class="form-control input-sm" name="Order[ordertype]">
          <option value="<?php echo Order::TYPE_INSUMO; ?>" <?php echo $ordertype=="INSUMO" ? "selected":""; ?>>1 - INSUMO</option>
          <option value="<?php echo Order::TYPE_MANO_DE_OBRA; ?>" <?php echo $ordertype=="MANO DE OBRA" ? "selected":""; ?>>2 - MANO DE OBRA</option>
          <option value="<?php echo Order::TYPE_INSUMO_MANO_DE_OBRA; ?>" <?php echo $ordertype=="INSUMOS Y MANO DE OBRA" ? "selected":""; ?>>3 - INSUMOS Y MANO DE OBRA</option>
        </select>
<!--
1-INSUMO; 
2-MANO DE OBRA; 
3-MIXTA ES DECIR DE INSUMO Y MANO DE OBRA
-->
      </div>
    </div>
  </div>  
</div>

<?php $statusid=$order->statusid; ?>
<div class="row" style="margin-top: 5px; margin-bottom: 5px;">
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Estatus</label></div>
      <div class="col-md-6">
        <input type="hidden" value="<?php echo $order->isNewRecord ? 1:$order->statusid; ?>" id="Order_statusid" name="Order[statusid]" />
<select onchange="setStatus(this);" class="form-control input-sm" id="Order_statusid" name="Order[statusid]" <?php echo $order->isNewRecord == 1 ? "disabled='disabled'":""; ?>>
<?php if(User::checkRole('elaborador')): ?>
  <option value="<?php echo Order::STATUS_AUTORIZADA; ?>" <?php echo $statusid==Order::STATUS_AUTORIZADA ? "selected":"" ?>><?php echo Order::AUTORIZADA; ?></option>
  <option value="<?php echo Order::STATUS_COLOCADA; ?>" <?php echo $statusid==Order::STATUS_COLOCADA ? "selected":"" ?>><?php echo Order::COLOCADA; ?></option>
  <option value="<?php echo Order::STATUS_CANCELADA; ?>" <?php echo $statusid==Order::STATUS_CANCELADA ? "selected":"" ?>><?php echo Order::CANCELADA; ?></option>
<?php endif; ?>
<?php if(User::checkRole('autorizador')): ?>
  <option value="<?php echo Order::STATUS_AUTORIZADA; ?>" <?php echo $statusid==Order::STATUS_AUTORIZADA ? "selected":"" ?>><?php echo Order::AUTORIZADA; ?></option>
  <option value="<?php echo Order::STATUS_COLOCADA; ?>" <?php echo $statusid==Order::STATUS_COLOCADA ? "selected":"" ?>><?php echo Order::COLOCADA; ?></option>
  <option value="<?php echo Order::STATUS_CANCELADA; ?>" <?php echo $statusid==Order::STATUS_CANCELADA ? "selected":"" ?>><?php echo Order::CANCELADA; ?></option>
<?php endif; ?>
<?php if(User::checkRole('receptor')): ?>
  <option value="<?php echo Order::STATUS_SURTIDA; ?>" <?php echo $statusid==Order::STATUS_SURTIDA ? "selected":"" ?>><?php echo Order::SURTIDA; ?></option>
  <option value="<?php echo Order::STATUS_PARCIAL; ?>" <?php echo $statusid==Order::STATUS_PARCIAL ? "selected":"" ?>><?php echo Order::PARCIAL; ?></option>
<?php endif; ?>
<?php if(User::checkRole('contralor')): ?>
  <?php if($statusid!=Order::STATUS_PAGADA):?>
  <option value="<?php echo Order::STATUS_SURTIDA; ?>" <?php echo $statusid==Order::STATUS_SURTIDA ? "selected":"" ?>><?php echo Order::SURTIDA; ?></option>
  <option value="<?php echo Order::STATUS_PORPAGAR; ?>" <?php echo $statusid==Order::STATUS_PORPAGAR ? "selected":"" ?>><?php echo Order::PORPAGAR; ?></option>
  <?php endif; ?>
  <option value="<?php echo Order::STATUS_PAGADA; ?>" <?php echo $statusid==Order::STATUS_PAGADA ? "selected":"" ?>><?php echo Order::PAGADA; ?></option>
<?php endif; ?>
<?php if(User::checkRole('admin')): ?>
  <option value="<?php echo Order::STATUS_COLOCADA; ?>" <?php echo $statusid==Order::STATUS_COLOCADA ? "selected":"" ?>><?php echo Order::COLOCADA; ?></option>
  <option value="<?php echo Order::STATUS_AUTORIZADA; ?>" <?php echo $statusid==Order::STATUS_AUTORIZADA ? "selected":"" ?>><?php echo Order::AUTORIZADA; ?></option>
  <option value="<?php echo Order::STATUS_CANCELADA; ?>" <?php echo $statusid==Order::STATUS_CANCELADA ? "selected":"" ?>><?php echo Order::CANCELADA; ?></option>
  <option value="<?php echo Order::STATUS_SURTIDA; ?>" <?php echo $statusid==Order::STATUS_SURTIDA ? "selected":"" ?>><?php echo Order::SURTIDA; ?></option>
  <option value="<?php echo Order::STATUS_PARCIAL; ?>" <?php echo $statusid==Order::STATUS_PARCIAL ? "selected":"" ?>><?php echo Order::PARCIAL; ?></option>
  <option value="<?php echo Order::STATUS_PORPAGAR; ?>" <?php echo $statusid==Order::STATUS_PORPAGAR ? "selected":"" ?>><?php echo Order::PORPAGAR; ?></option>
  <option value="<?php echo Order::STATUS_PAGADA; ?>" <?php echo $statusid==Order::STATUS_PAGADA ? "selected":"" ?>><?php echo Order::PAGADA; ?></option>
<?php endif; ?>

</select>
      </div>
    </div>
  </div>


<!--
Si la factura está pagada solamente se puede cambiar a estatus surtida o parcial
-->
  <?php if($order->statusid == Order::STATUS_PAGADA || $order->statusid == Order::STATUS_PORPAGAR): ?>
  <div class="col-md-6">
    <div class="row">
      <div class="col-md-4"><label for="Order_invoiceid">Folio Factura</label></div>
      <div class="col-md-4">
        <input type="text" name="Order[invoiceid]" id="Order_invoiceid" class="form-control input-sm" value="<?php echo $order->invoiceid; ?>">
        <?php echo $form->error($order,'invoiceid', array('class'=>'form-group has-error')); ?>
      </div>
      <div class="col-md-4"></div>
    </div>
  </div>  
  <?php endif; ?>
</div>

<div class="row" style="margin-top: 5px; margin-bottom: 5px;">
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Partida</label></div>
      <div class="col-md-6"><input class="form-control input-sm" type="text" value="<?php echo $partida->name; ?>" disabled="disabled"></div>
    </div>
  </div>
  <div id="methodpayment" <?php echo $order->statusid==Order::STATUS_PAGADA?'':'style="display: none;"'; ?> >
    <div class="col-md-4">
      <div class="row">
        <div class="col-md-6"><label for="inputEmail3">Forma de pago</label></div>
        <div class="col-md-6">
          <select class="form-control input-sm" id="Order_methodpayment" name="Order[methodpayment]">
            <option value="" <?php echo $order->methodpayment==''?'selected':''; ?>>SELECCIONE</option>
            <option value="TRANSFERENCIA" <?php echo $order->methodpayment=='TRANSFERENCIA'?'selected':''; ?>>Transferencia</option>
            <option value="CHEQUE" <?php echo $order->methodpayment=='CHEQUE'?'selected':''; ?>>Cheque</option>
            <option value="EFECTIVO" <?php echo $order->methodpayment=='EFECTIVO'?'selected':''; ?>>Efectivo</option>
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="col-md-2"><label for="inputEmail3">Ref:</label></div>
      <div class="col-md-10">
        <input class="form-control input-sm" maxlength="50" type="text" 
               value="<?php echo $order->methodpaymentref; ?>" id="Order_methodpaymentref" name="Order[methodpaymentref]" />
      </div>
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
        <?php echo $form->textField($order, 'initdate', array('value'=>"$value", 'class'=>'form-control input-sm', 'data-toggle'=>'datepicker', 'placeholder'=>'aaaa-dd-mm')); ?>
        <?php echo $form->error($order,'initdate', array('class'=>'form-group has-error')); ?>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="col-md-12 col-sm-12">
      <?php if($order->statusid!=Order::STATUS_CANCELADA && $canUpdate): ?>
        <button type="button" id="btnSave" onclick="validate();" class="btn btn-primary btn-sm">
          <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
        </button>
      <?php endif; ?>

      <?php if($order->isNewRecord == false): ?>
          <button onclick="$('#dialogOCPorEmail').modal(); return false;" id="btnOCByEmail" class="btn btn-default" aria-label="Left Align" title="Enviar O.C. por E-mail">
            <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
          </button>
      <?php endif; ?>

      <?php if($order->ordertype == Order::TYPE_MANO_DE_OBRA || $order->ordertype == Order::TYPE_INSUMO_MANO_DE_OBRA): ?>
        <a onclick="$('#dialogPrintContract').modal(); return false;" 
           title="Imprimir contrato de obra" class="btn btn-primary btn-sm">Imprimir contrato</a>
      <?php endif; ?>

      <?php if($order->isNewRecord == false): ?>
          <button onclick="document.forms['printForm'].submit(); return false;" class="btn btn-default" aria-label="Left Align" title="Imprimir órden de compra">
            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
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
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Proyecto</label>
            <div class="col-sm-8">
              <small><?php echo $project->name; ?></small>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">Plaza</label>
            <div class="col-sm-10">
              <small><?php echo $project->location; ?></small>
            </div>
          </div>
        </div>
      </div>
        <div class="col-md-12">
          <div class="form-group">
            <div class="col-sm-12">
            <label for="inputPassword3" >Proveedor</label>
              <select class="form-control input-sm" name="Order[supplierid]">
                <?php 
                  foreach ($suppliers as $supplier) {
                    ?><option <?php echo $order->supplierid == $supplier->id ? 'selected' : '';  ?> value="<?php echo $supplier->id; ?>"><?php echo $supplier->id.'-'.strtoupper($supplier->code).' - '.strtoupper($supplier->name); ?></option><?php
                  }
                ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <?php echo $form->labelEx($order,'address',  array('for'=>'address', 'class'=>'col-sm-3 control-label')); ?>
            <div class="col-sm-9">
              <?php $address=$order->isNewRecord ? $project->address : $order->address; ?>
              <?php echo $form->textArea($order, 'address', array('value'=>"$address", 'class'=>'form-control input-sm', 'maxlength'=>'500', 'rows'=>'3')); ?>
              <?php echo $form->error($order,'address', array('class'=>'form-group has-error')); ?>            
            </div>
          </div>

    </fieldset>
  </div>

  <div class="col-md-6">
    <fieldset>     
      <legend>Datos de entrega</legend>

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">Qui&eacute;n recibe</label>
    <div class="col-sm-9">
      <?php $deliverto = $order->isNewRecord ? Yii::app()->user->name : $order->deliverto; ?>
      <?php echo $form->textField($order, 'deliverto', array('class'=>'form-control input-sm', 'maxlength'=>'50', 'value'=>$deliverto)); ?>
      <?php echo $form->error($order,'deliverto', array('class'=>'form-group has-error')); ?>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Tel&eacute;fono</label>
    <div class="col-sm-9">
      <?php echo $form->textField($order, 'phone', array('class'=>'form-control input-sm', 'maxlength'=>'20')); ?>
      <?php echo $form->error($order,'phone', array('class'=>'form-group has-error')); ?>      
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Observaciones</label>
    <div class="col-sm-9">
      <?php echo $form->textArea($order, 'comment', array('class'=>'form-control input-sm', 'maxlength'=>'100', 'rows'=>'2')); ?>
    </div>
  </div>


    </fieldset>  
  </div>
</div>

<br/><br/>
<table class="table table-condensed" id="orderDetailTable">
  <tr>
    <td style="width: 2%;">&nbsp;</td>
    <td style="width: 2%;">&nbsp;</td>
    <td style="width: 5%;">&nbsp;</td>
    <td style="width: 54%;">&nbsp;</td>
    <td style="width: 12%;">&nbsp;</td>
    <td style="width: 2%;"><button onclick="deleteAllTyped();" type="button" class="btn btn-default btn-xs">Eliminar toda la captura</button></td>
    <td style="width: 1%;">&nbsp;</td>
  </tr>
  <tr>
    <td style="width: 2%;">Tasa 0</td>
    <td style="width: 5%;">Cant.</td>
    <td style="width: 5%;">Unidad</td>
    <td style="width: 54%;">Concepto</td>
    <td style="width: 12%;">P. Unit.</td>
    <td style="width: 12%;">Importe</td>
    <td style="width: 2%;">
<?php if($canUpdate): ?>
      <span onclick="addOrderDetail();" style="cursor:pointer;" class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
<?php endif; ?>
    </td>
  </tr>

<?php 
  $deleteDetail="";
if($canUpdate){
  $deleteDetail='<td><span onclick="delOrderDetailConfirm(this.parentElement.parentElement.id);" style="cursor:pointer;" class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td>';
}
if(sizeof($orderdetail)>0):
  $index=-1;
  foreach ($orderdetail as $det) {
    $index++;
    echo '<tr class="typed" id="row_'.$index.'">'
    .'<td>'.$form->checkBox($det, "zerotax", array('onclick'=>'checkBoxUpdateValue(this);', 'id'=>'OrderDetail_zerotax_'.$index, 'name'=>'OrderDetail['.$index.'][zerotax]')).'</td>'
    .'<td>'.$form->textField($det, "qty", array('class'=>'form-control input-sm', 'id'=>'OrderDetail_qty_'.$index, 'name'=>'OrderDetail['.$index.'][qty]', 'maxlength'=>'7')).'</td>'
    .'<td>'.$form->textField($det, "unit", array('class'=>'form-control input-sm', 'id'=>'OrderDetail_unit_'.$index, 'name'=>'OrderDetail['.$index.'][unit]', 'maxlength'=>'8')).'</td>'
    .'<td>'.$form->textField($det, "description", array('class'=>'form-control input-sm', 'id'=>'OrderDetail_description_'.$index, 'name'=>'OrderDetail['.$index.'][description]', 'maxlength'=>'100')).'</td>'
    .'<td>'.$form->textField($det, "price", array('class'=>'form-control input-sm', 'id'=>'OrderDetail_price_'.$index, 'name'=>'OrderDetail['.$index.'][price]', 'onblur'=>'calculateTotal(this.id);')).'</td>'
    .'<td>'.$form->textField($det, "total", array('class'=>'form-control input-sm', 'id'=>'OrderDetail_total_'.$index, 'disabled'=>'disabled')).'</td>'
    .$deleteDetail.'</tr>';

  } 
endif;
?>  
</table>

<table class="table table-condensed" id="orderDetailTable">
  <tr>
    <td style="width: 2%;">&nbsp;</td>
    <td style="width: 5%;">&nbsp;</td>
    <td style="width: 54%;">&nbsp;</td>
    <td style="width: 12%;">&nbsp;</td>
    <td style="width: 12%;">&nbsp;</td>
    <td style="width: 2%;">&nbsp;</td>
    <td style="width: 1%;">&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Tasa Cero</td>
    <td><input style="width:157px" class="form-control input-sm" disabled="disabled" id="Order_subtotal" type="text" value="$<?php echo $importeTasaCero; ?>"/></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>SubTotal</td>
    <td><input style="width:157px" class="form-control input-sm" disabled="disabled" id="Order_subtotal" type="text" value="$<?php echo $order->subtotal; ?>"/></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>IVA</td>
    <td><input style="width:157px" disabled="disabled" class="form-control input-sm" type="text" id="Order_tax" value="$<?php echo $order->tax; ?>"/></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Total</td>
    <td><input style="width:157px" disabled="disabled" class="form-control input-sm" type="text" id="Order_total" value="$<?php echo $order->total; ?>"/></td>
    <td></td>
  </tr>
</table>

<div id="removeDetailModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Eliminar concepto</h4>
      </div>
      <div class="modal-body">
        <p>Si elimina este concepto perderá lo que haya capturado, ¿Desea eliminar de todos modos?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="delOrderDetail();" >Aceptar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- dialogo impresion contrato -->
<div id="dialogPrintContract" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Seleccione una opci&oacute;n</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="col-md-6 col-lg-6">
              <a href="<?php echo Yii::app()->createUrl("order/PrintContract/", array("orderid"=>$order->id)); ?>" 
                 onclick="$('#dialogPrintContract').modal('hide');"
                 target="_blank" title="Imprimir contrato de obra" class="btn btn-primary">Imprimir contrato</a><br/><br/>
            </div>
            <div class="col-md-6 col-lg-6">
            </div>
          </div>
          <div class="col-md-6 col-lg-12">
            <div class="col-md-6 col-lg-6">
              <div class="input-group">
                <span class="input-group-addon" id="basic-addon1">@</span>
                <input type="email" class="form-control" placeholder="email@correo.com" 
                name="txtEmailContract" id="txtEmailContract">
              </div>
            </div>
            <div class="col-md-6 col-lg-6">
              <button id="btnSendContract" type="button" class="btn btn-primary" >Enviar contrato por email</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- fin dialogo impresion contrato -->

<?php 
  $this->renderPartial('_enviarocporemail');  
?>

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

  function sendContractByEmail(){
    if( jQuery.trim($("#txtEmailContract").val()).length==0 ){
      bootbox.alert("Por favor capture un correo para enviar el contrato de obra.");
    }else{
      email = $("#txtEmailContract");
      $.blockUI({ message: 'Enviando el contrato de obra por email, por favor espere' });

      $.ajax({
        type: 'GET',
        async: true,
        contentType: "application/json",
        url: "<?php echo Yii::app()->createUrl('/order/contractToEmail').'&orderid='.$order->id.'&email='; ?>"+email.val(),
        success: function(data){
          $.unblockUI();
          bootbox.alert("Se ha enviado con exito el contrato de obra por E-Mail");
        },
        error: function(jqXHR, textStatus, errorThrown){
          $.unblockUI();
          bootbox.alert("No fue posible enviar el contrato de obra por correo, si el problema persiste, contacte con su administrador de sistema.");
        },
      });

      $("#dialogPrintContract").modal('hide');
      email.val('');
    }
  }

  function deleteAllTyped(){
    bootbox.confirm({
      message: "¿Desea eliminar todo lo capturado?",
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
        if(result)
          $('#orderDetailTable tr.typed').remove();
      }
    });
  }

  function setStatus(obj){
    $("#Order_statusid").val(obj.value);
    $('#methodpayment').css('display','none');
    $('#Order_methodpayment').val('');
    $('#Order_methodpaymentref').val('');
    if(obj.value=="7"){
      $('#methodpayment').css('display','block');
      $('#Order_invoiceid').css('display','block');
    }
  }

  function addOrderDetail(){
    var orderDetailTable=$("#orderDetailTable");
    var row="";

    row+='<tr class="typed">';
    
    row+='<td><input value="0" onclick="checkBoxUpdateValue(this);" type="checkbox" class="" id="OrderDetail_zerotax_0"         name="OrderDetail[0][zerotax]"          /></td>';
    row+='<td><input type="text" class="form-control input-sm" id="OrderDetail_qty_0"         name="OrderDetail[0][qty]"          value="1" maxlength="7" /></td>';
    row+='<td><input type="text" class="form-control input-sm" id="OrderDetail_unit_0"        name="OrderDetail[0][unit]"         value="PZA" maxlength="8" /></td>';
    row+='<td><input type="text" class="form-control input-sm" id="OrderDetail_description_0" name="OrderDetail[0][description]"  value="" maxlength="100" /></td>';
    row+='<td><input type="text" class="form-control input-sm" id="OrderDetail_price_0"       name="OrderDetail[0][price]"        value="" onblur="calculateTotal(this.id);" /></td>';
    row+='<td><input type="text" class="form-control input-sm" id="OrderDetail_total_0" name="OrderDetail[0][total]" disabled="disabled" value="" /></td>';
<?php if($canUpdate):?>
    row+='<td><span onclick="delOrderDetailConfirm(this.parentElement.parentElement.id);" style="cursor:pointer;" class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td>';
<?php endif; ?>
    row+='</tr>';

    orderDetailTable.append(row);

    renameOrderDetailControls();
  }

  function delOrderDetailConfirm(_rowid){
    $('#removeDetailModal').attr("rowToDelete", _rowid);
    $('#removeDetailModal').modal();
  }

  function delOrderDetail(){
    $('table tr#'+ $('#removeDetailModal').attr("rowToDelete") )[0].remove();
    renameOrderDetailControls();
    $('#removeDetailModal').modal('hide');
  }

  function renameOrderDetailControls(){
    var zerotaxControl = $("input[id^=OrderDetail_zerotax_]");
    var zerotaxControlHiden = $("input[id^=ytOrderDetail_zerotax_]");
    var qtyControl = $("input[id^=OrderDetail_qty_]");
    var unitControl = $("input[id^=OrderDetail_unit_]");
    var descriptionControl = $("input[id^=OrderDetail_description_]");
    var priceControl = $("input[id^=OrderDetail_price_]");
    var totalControl = $("input[id^=OrderDetail_total_]");
    var tableRows = orderDetailTable=$("#orderDetailTable tr");
    
    //zerotax
    for(i=0;i<zerotaxControl.length; i++){
      zerotaxControl[i].id="OrderDetail_zerotax_"+(i);
      zerotaxControl[i].attributes["name"].value="OrderDetail["+i+"][zerotax]";
    }
    for(i=0;i<zerotaxControlHiden.length; i++){
      zerotaxControlHiden[i].id="ytOrderDetail_zerotax_"+(i);
      zerotaxControlHiden[i].attributes["name"].value="OrderDetail["+i+"][zerotax]";
    }
    //qty
    for(i=0;i<qtyControl.length; i++){
      qtyControl[i].id="OrderDetail_qty_"+(i);
      qtyControl[i].attributes["name"].value="OrderDetail["+i+"][qty]";
    }
    //unit
    for(i=0;i<unitControl.length; i++){
      unitControl[i].id="OrderDetail_unit_"+(i);
      unitControl[i].attributes["name"].value="OrderDetail["+i+"][unit]";
    }
    //descriptionControl
    for(i=0;i<descriptionControl.length; i++){
      descriptionControl[i].id="OrderDetail_description_"+(i);
      descriptionControl[i].attributes["name"].value="OrderDetail["+i+"][description]";
    }
    //priceControl
    for(i=0;i<priceControl.length; i++){
      priceControl[i].id="OrderDetail_price_"+(i);
      priceControl[i].attributes["name"].value="OrderDetail["+i+"][price]";
    }
    //totalControl
    for(i=0;i<totalControl.length; i++){
      totalControl[i].id="OrderDetail_total_"+(i);
      totalControl[i].attributes["name"].value="OrderDetail["+i+"][total]";
    }

    //rows
    for(i=1;i<tableRows.length; i++){
      tableRows[i].id="row_"+(i);
    }

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

  function printSubTotal(){
    $("#Order_subtotal").val(0);
  }

  function printIva(){
    $("#Order_iva").val(0);
  }

  function printTotal(){
    $("#Order_total").val(0);
  }

  /**
  *rid: es el row id desde el cual fue invocada calculateTotal
  */
  function calculateTotal(rid){
    var _index=rid.split("_")[2];
    var _price=$("#OrderDetail_price_"+_index).val();
    var _qty=$("#OrderDetail_qty_"+_index).val();

    $("#OrderDetail_total_"+_index).val(_qty*_price);
  }

  function checkBoxUpdateValue(control){
    if(control.checked == true){
      control.value="1";
    }else{
      control.value="0";
    }
    $('#yt'+control.id).val(control.value);
  }

  $( document ).ready(function() {
    var btnSendOCPorEmail = $("#btnSendOCPorEmail");

    if(btnSendOCPorEmail){
      btnSendOCPorEmail.click(function(event) {
        sendOcByEmail();
        return false;
      });
    }

    if($("#btnSendContract")){
      $("#btnSendContract").click(function(event) {
        sendContractByEmail(); return false;
      });
    }
  });

  function sendOcByEmail(){
    var txtEmail=$('#txtSendOCPorEmail').val();
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    
    if(re.test(String(txtEmail).toLowerCase())){
      $('#dialogOCPorEmail').modal('hide');
      $.blockUI({ message: 'Enviando la orden de compra por correo, por favor espere' });
      var link="<?php echo Yii::app()->createUrl('/order/octoemail', array('orderid'=>$order->id)) ?>";
      $.ajax({
        type: "GET",
        contentType: "application/json; charset=utf-8",
        url: link + "&email_account="+txtEmail,
        dataType: "json",
        async: true,
        error: function (response) {
          if(response.responseText=='OK'){
            bootbox.alert('La o.c. se ha enviado, por correo.');
          }else{
            bootbox.alert('Verifique que el correo sea un correo valido, si el problema persiste contacte con su administrador de sistema.');
          }
          $.unblockUI();
        },
      }).done(function(response) {
        $('#dialogOCPorEmail').modal('hide');
        $.unblockUI();
      })
    }else{
      bootbox.alert("Por favor capture un correo v&aacute;lido.");
    }
  }
</script>


<script src="<?php echo Yii::app()->theme->baseUrl;?>\assets\bootbox.min.js"></script>
