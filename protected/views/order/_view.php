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
      echo 'Orden de compra: '.$_GET["orderid"];
    ?>
  </li>
</ol>

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
      <div class="col-md-6">
        <?php echo $order->id; ?>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Tipo</label></div>
      <div class="col-md-6">
        <?php echo $order->ordertype; ?>
      </div>
    </div>
  </div>  
</div>

<div class="row">
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Estatus</label></div>
      <div class="col-md-6">
        <?php echo $order->orderstatus->name; ?>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Partida</label></div>
      <div class="col-md-6">
        <?php echo $partida->name; ?>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Sub-Partida</label></div>
      <div class="col-md-6">
        <?php $subpartida; ?>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="row">
      <div class="col-md-6"><label for="inputEmail3">Fecha inicio</label></div>
      <div class="col-md-6">
<?php echo $order->isNewRecord ? '':date('Y-m-d', $order->initdate); ?>
      </div>
    </div>
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
              <?php 
                $supplier=new Supplier();
                $supplier = $supplier::model()->findbyPk($order->supplierid);
                echo $supplier->id.'-'.strtoupper($supplier->code).' - '.strtoupper($supplier->name); 
              ?>
            </div>
          </div>

          <div class="form-group">
            <?php echo $form->labelEx($order,'address',  array('for'=>'address', 'class'=>'col-sm-3 control-label')); ?>
            <div class="col-sm-9">
              <?php echo $order->isNewRecord ? $project->address : $order->address; ?>
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
      <?php echo $order->isNewRecord ? Yii::app()->user->name : $order->deliverto; ?>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Tel&eacute;fono</label>
    <div class="col-sm-9">
      <?php echo $order->phone; ?>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Observaciones</label>
    <div class="col-sm-9">
      <?php echo $order->comment; ?>
    </div>
  </div>
    </fieldset>  
  </div>
</div>

<br/><br/>
<table class="table table-condensed" id="orderDetailTable">
  <tr>
    <td style="width: 2%;">Tasa 0</td>
    <td style="width: 5%;">Cant.</td>
    <td style="width: 5%;">Unidad</td>
    <td style="width: 54%;">Concepto</td>
    <td style="width: 12%;">P. Unit.</td>
    <td style="width: 12%;">Importe</td>
    <td style="width: 2%;">
    </td>
  </tr>

<?php 
  $deleteDetail="";
  $deleteDetail='<td></td>';

if(sizeof($orderdetail)>0):
  $index=-1;
  foreach ($orderdetail as $det) {
    $index++;
    $zerotax=$det->zerotax==1?'SI':'NO';
    echo '<tr class="typed" id="row_'.$index.'">'
    .'<td>'.$zerotax.'</td>'
    .'<td>'.$det->qty.'</td>'
    .'<td>'.$det->unit.'</td>'
    .'<td>'.$det->description.'</td>'
    .'<td>'.$det->price.'</td>'
    .'<td>'.$det->total.'</td>'
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

<?php $this->endWidget(); ?>

<script>
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

    console.log("checkbox: "+control.name, control.value);
  }

  $( document ).ready(function() {
    $('#ocEmailError').hide();
    $('#ocEmailSuccess').hide();
    var btnEmail = $("#btnOCByEmail");

    if(btnEmail){
      btnEmail.click(function(event) {
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
    $.blockUI({ message: 'Enviando la orden de compra al correo del proveedor, por favor espere' });
    $.ajax({
      type: "GET",
      contentType: "application/json; charset=utf-8",
      url: "<?php echo Yii::app()->createUrl('/order/octoemail', array('orderid'=>$order->id)) ?>",
      dataType: "json",
      async: true,
      error: function (response) {
        if(response.responseText=='OK'){
          $("#ocEmailSuccess").show();
        }else{
          $("#ocEmailError").show();
        }
        $.unblockUI();
      },
    }).done(function(response) {
        $.unblockUI();
      })

  }
</script>


<script src="<?php echo Yii::app()->theme->baseUrl;?>\assets\bootbox.min.js"></script>

