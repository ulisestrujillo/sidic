<?php
/* @var $this SupplierController */
/* @var $model Supplier */
/* @var $form CActiveForm */
?>
<div class="col-lg-12 col-md-12 col-sm-12">  
  <div class="container-fluid">
    <div class="row clearfix">
      <div class="card">
          <div class="body">
            <?php $form=$this->beginWidget('CActiveForm', array(
              'id'=>'BillingForm',
              'enableAjaxValidation'=>false,
              'htmlOptions' => array('enctype'=>'multipart/form-data'),
              'action'=>""
            )); ?>

            <?php CHtml::$errorContainerTag = 'div'; ?>

<div class="row" style="padding: 0px;">

  ¿QUE DEBE HACER EL SISTEMA CUANDO EL USURIO ELIMINRA EL UNICO O ULTIMO ITEM DE CARRITO?
  <!-- resumen de datos de facturacion con botón para editar  -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12" id="billingSummary"
                 style="display:none; background-color: #f7f5f5;padding: 0 10px;margin: 0px;">
              <h3>Informaci&oacute;n de facturaci&oacute;n 
                <small><a href="#" onclick="editBillingData(); return false;">Modificar</a></small></h3>

              <div class="form-group">
                  <div class="form-line" id="billingSummaryBody">
                      <?php echo "asdfasdf" ?>
                  </div>
              </div>  
            </div>

  <!-- Formulario de captura de datos de facturación  -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12" id="billingForm"
                 style="display:block; background-color: #f7f5f5;padding: 0 10px;margin: 0px;">
              <h3>Informaci&oacute;n de facturaci&oacute;n</h3>
              <label for="BillingForm_name">*Nombre Completo</label>
              <div class="form-group">
                  <div class="form-line">
                      <input value="<?php echo $model->name; ?>" name="BillingForm[name]" type="text" id="BillingForm_name" 
                      class="form-control" placeholder="">
                      <?php echo $form->error($model,'name', array('class'=>'error')); ?>
                  </div>
              </div>  

              <label for="BillingForm_address">*Dirección</label>
              <div class="form-group">
                  <div class="form-line">
                      <textarea rows="1" class="form-control no-resize" name="BillingForm[address]" id="BillingForm_address"><?php echo $model->address; ?></textarea>
                      <?php echo $form->error($model,'address', array('class'=>'error')); ?>
                  </div>
              </div>

              <label for="BillingForm_country">*País</label>
              <div class="form-group">
                  <div class="form-line">
                      <input value="<?php echo $model->country; ?>" name="BillingForm[country]" 
                      type="text" id="BillingForm_country" class="form-control" placeholder="">
                      <?php echo $form->error($model,'country', array('class'=>'error')); ?>
                  </div>
              </div>

              <div class="row">
                <div class="col-md-12" style="padding:0px;">
                  <div class="col-md-6">
                    <label for="BillingForm_state">*Estado</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input value="<?php echo $model->state; ?>" name="BillingForm[state]" 
                            type="text" id="BillingForm_state" class="form-control" placeholder="">
                            <?php echo $form->error($model,'state', array('class'=>'error')); ?>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="BillingForm_city">*Ciudad</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input value="<?php echo $model->city; ?>" name="BillingForm[city]" 
                            type="text" id="BillingForm_city" class="form-control" placeholder="">
                            <?php echo $form->error($model,'city', array('class'=>'error')); ?>
                        </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12" style="padding:0px;">
                  <div class="col-md-6">
                    <label for="BillingForm_phone_number">*Teléfono</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input value="<?php echo $model->phone_number; ?>" name="BillingForm[phone_number]" 
                            type="text" id="BillingForm_phone_number" class="form-control" placeholder="">
                            <?php echo $form->error($model,'phone_number', array('class'=>'error')); ?>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="BillingForm_rfc">RFC (opcional)</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input value="<?php echo $model->rfc; ?>" name="BillingForm[rfc]" 
                            type="text" id="BillingForm_rfc" class="form-control" placeholder="ABCD-123456-ABC">
                            <?php echo $form->error($model,'rfc', array('class'=>'error')); ?>
                        </div>
                    </div>
                  </div>
              </div>

            </div>
            <input type="button" onclick="saveBillingData()" class="btn btn-raised btn-primary m-t-15 waves-effect" value="Guardar" />                
            <br/><br/>
</div>

            <?php $this->endWidget(); ?>
          </div>
      </div>
    </div>
  </div>
</div>  

<script type="text/javascript">
  function saveBillingData(){
    billingForm=new Object();
    billingForm.name=$("#BillingForm_name").val();
    billingForm.address=$("#BillingForm_address").val();
    billingForm.country=$("#BillingForm_country").val();
    billingForm.state=$("#BillingForm_state").val();
    billingForm.city=$("#BillingForm_city").val();
    billingForm.phone_number=$("#BillingForm_phone_number").val();
    billingForm.rfc=$("#BillingForm_rfc").val();

    $.ajax({
      type: 'POST',
      async: true,
      data: {BillingForm:billingForm},
      url: "<?php echo Yii::app()->createUrl('user/SaveBillingData'); ?>",
      success: function(data){
        response=JSON.parse(data);

        if(response.code=="500"){
          errors=JSON.parse(response.data);
        }else
        if(response.code=="200"){
          $("#billingSummaryBody").html(response.data);
          $("#billingSummary").show();
          $("#billingForm").hide();
          Notify({
            content: 'Datos de facturaci&oacute;n fuer&oacute;n actualizados.',
            rounded: true,
            color: '#dff0d8'
          });
        }
      },
      error: function(jqXHR, textStatus, errorThrown){
        alert("El servidor no pudo procesar la solicitud.");
      },
    });
  }  

  function editBillingData(){
    $("#billingSummaryBody").html("");
    $("#billingSummary").hide();
    $("#billingForm").show();

  }

</script>
<script src="<?php echo Yii::app()->baseUrl.'/protected/vendor/jquerytoast/notify.min.js'; ?>"></script>
