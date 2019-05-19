<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://malsup.github.io/jquery.blockUI.js"></script>

<?php if(Yii::app()->user->hasFlash('updated')): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php echo Yii::app()->user->getFlash("updated"); ?>
    </div>
<?php endif; ?>

<ol class="breadcrumb" style="margin:0px !important;">
  <li><a href="index.html"><i class="zmdi zmdi-home"></i> Sidic</a></li>
  <li><a href="javascript:void(0);">Datos Físcales</a></li>
</ol>

<div class="row" style="margin-top:12px;">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3 style="margin:0px !important;">Datos físcales</h3>
  </div>
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modalForLogo">Ver logotipo</button>
  </div>
  <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-right">
    <small class="text-muted">Los campos con asterisco son requeridos</small>
  </div>
</div>

    <div class="row clearfix">
      <div class="card">
            <?php if( $fiscal->getErrors() || $model->getErrors() ): ?>
              <div class="header">
                <h2 style="color:red;">Algunos datos no son validos</h2>
              </div>
            <?php endif; ?>
          <div class="body">

            <?php $form=$this->beginWidget('CActiveForm', array(
              'id'=>'ProfileForm',
              'enableAjaxValidation'=>false,
              'htmlOptions' => array('enctype'=>'multipart/form-data'),
            )); ?>

            <?php CHtml::$errorContainerTag = 'label'; ?>

<div class="row">

  <?php if(Yii::app()->user->hasFlash("exceedSize")): ?>
  <div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <?php echo Yii::app()->user->getFlash("exceedSize"); ?>
  </div>
  <?php endif; ?>

  <?php if(Yii::app()->user->hasFlash("uploadLogoError")): ?>
  <div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <?php echo Yii::app()->user->getFlash("uploadLogoError"); ?>
  </div>
  <?php endif; ?>

  <?php if(Yii::app()->user->hasFlash("uploadLogoSuccess")): ?>
  <div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <?php echo Yii::app()->user->getFlash("uploadLogoSuccess"); ?>
  </div>
  <?php endif; ?>

  <div class="col-lg-12 col-md-12 col-sm-12">
    <h5>Subir logotipo - solo se permiten archivos con extensión .jpg</h5>
    <input type='file' name='ProfileForm[logo]' maxlength="80" />
    <br/>
  </div>
</div>

<div class="row">
  <input value="<?php echo $model->id; ?>" name="ProfileForm[id]" type="hidden" id="ProfileForm_id" class="form-control">

  <div class="col-lg-6 col-md-12 col-sm-12">
    <label for="ProfileForm_name">*Nombre</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->name; ?>" name="ProfileForm[name]" type="text" id="ProfileForm_name" class="form-control" placeholder="">
        <?php echo $form->error($model,'name', array('class'=>'error')); ?>
      </div>
    </div>  

    <label for="ProfileForm_address">*Dirección</label>
    <div class="form-group">
      <div class="form-line">
        <textarea rows="4" class="form-control no-resize" name="ProfileForm[address]" id="ProfileForm_address"><?php echo $model->address; ?></textarea>
        <?php echo $form->error($model,'address', array('class'=>'error')); ?>
      </div>
    </div>

    <label for="ProfileForm_country">*País</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->country; ?>" name="ProfileForm[country]" 
        type="text" id="ProfileForm_country" class="form-control" placeholder="">
        <?php echo $form->error($model,'country', array('class'=>'error')); ?>
      </div>
    </div>

    <label for="ProfileForm_state">*Estado</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->state; ?>" name="ProfileForm[state]" 
        type="text" id="ProfileForm_state" class="form-control" placeholder="">
        <?php echo $form->error($model,'state', array('class'=>'error')); ?>
      </div>
    </div>

    <label for="ProfileForm_city">*Ciudad</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->city; ?>" name="ProfileForm[city]" 
        type="text" id="ProfileForm_city" class="form-control" placeholder="">
        <?php echo $form->error($model,'city', array('class'=>'error')); ?>
      </div>
    </div>

    <label for="ProfileForm_rfc">*RFC</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->rfc; ?>" name="ProfileForm[rfc]" 
        type="text" id="ProfileForm_rfc" class="form-control" placeholder="">
        <?php echo $form->error($model,'rfc', array('class'=>'error')); ?>
      </div>
    </div>

    <label for="ProfileForm_phone_number">*Teléfono</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->phone_number; ?>" name="ProfileForm[phone_number]" 
        type="text" id="ProfileForm_phone_number" class="form-control" placeholder="">
        <?php echo $form->error($model,'phone_number', array('class'=>'error')); ?>
      </div>
    </div>

    <label for="ProfileForm_email">*Correo</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->email; ?>" name="ProfileForm[email]" 
        type="text" id="ProfileForm_email" class="form-control" placeholder="">
        <?php echo $form->error($model,'email', array('class'=>'error')); ?>
      </div>
    </div>

    <div class="form-group">
      <?php if($model->fiscal_data == 1): ?>
        <input type="checkbox" checked="checked" onclick="hideFiscalData();" class="filled-in" name="ProfileForm[fiscal_data]" id="ProfileForm_fiscal_data">
      <?php else: ?>
        <input type="checkbox" onclick="hideFiscalData();" class="filled-in" name="ProfileForm[fiscal_data]" id="ProfileForm_fiscal_data">
      <?php endif; ?>

      <label for="ProfileForm_fiscal_data">Estos son mis datos fiscales ?</label>
      <?php echo $form->error($model,'fiscal_data', array('class'=>'error')); ?>
    </div> 
  </div>

<!-- datos fiscales -->

<?php 
$hide="";
if($model->fiscal_data == 1)
  $hide="none";
 ?>
  <div id="fiscal_data_form" style="display:<?php echo $hide; ?>" class="col-lg-6 col-md-6 col-sm-12">
    <label for="ProfileForm_fiscalName">*Nombre</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->fiscal->name; ?>" name="ProfileForm[fiscalName]" type="text" id="ProfileForm_fiscalName" class="form-control" placeholder="">
        <?php echo $form->error($fiscal,'name', array('class'=>'error')); ?>
      </div>
    </div>
     
    <label for="ProfileForm_fiscalAddress">*Dirección</label>
    <div class="form-group">
      <div class="form-line">
        <textarea rows="4" class="form-control no-resize" name="ProfileForm[fiscalAddress]" id="ProfileForm_fiscalAddress"><?php echo $model->fiscal->address; ?></textarea>
        <?php echo $form->error($fiscal,'address', array('class'=>'error')); ?>
      </div>
    </div>

    <label for="ProfileForm_fiscalCountry">*País</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->fiscal->country; ?>" name="ProfileForm[fiscalCountry]" 
        type="text" id="ProfileForm_fiscalCountry" class="form-control" placeholder="">
        <?php echo $form->error($fiscal,'country', array('class'=>'error')); ?>
      </div>
    </div>

    <label for="ProfileForm_fiscalState">*Estado</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->fiscal->state; ?>" name="ProfileForm[fiscalState]" 
        type="text" id="ProfileForm_fiscalState" class="form-control" placeholder="">
        <?php echo $form->error($fiscal,'state', array('class'=>'error')); ?>
      </div>
    </div>

    <label for="ProfileForm_fiscalCity">*Ciudad</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->fiscal->city; ?>" name="ProfileForm[fiscalCity]" 
        type="text" id="ProfileForm_fiscalCity" class="form-control" placeholder="">
        <?php echo $form->error($fiscal,'city', array('class'=>'error')); ?>
      </div>
    </div>

    <label for="ProfileForm_fiscalRfc">*RFC</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->fiscal->rfc; ?>" name="ProfileForm[fiscalRfc]" 
        type="text" id="ProfileForm_fiscalRfc" class="form-control" placeholder="">
        <?php echo $form->error($fiscal,'rfc', array('class'=>'error')); ?>
      </div>
    </div>

    <label for="ProfileForm_fiscal_phone_number">*Teléfono</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->fiscal->phone_number; ?>" name="ProfileForm[fiscal_phone_number]" 
        type="text" id="ProfileForm_phone_number" class="form-control" placeholder="">
        <?php echo $form->error($fiscal,'phone_number', array('class'=>'error')); ?>
      </div>
    </div>

    <label for="ProfileForm_fiscalEmail">*Correo</label>
    <div class="form-group">
      <div class="form-line">
        <input value="<?php echo $model->fiscal->email; ?>" name="ProfileForm[fiscalEmail]" 
        type="text" id="ProfileForm_fiscalEmail" class="form-control" placeholder="">
        <?php echo $form->error($fiscal,'email', array('class'=>'error')); ?>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
    <button type="submit" class="btn btn-raised btn-primary m-t-15 waves-effect" 
            onclick="$.blockUI({message: 'Actualizando información, por favor espere.'});" >Actualizar datos
    </button>
    <br/><br/><br/><br/>
  </div>
</div>
            <?php $this->endWidget(); ?>
          </div>
      </div>
    </div>

<!-- modal for enterprise logo -->
<div id="modalForLogo"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <center><img border="0" width="50%" src="<?php echo Yii::app()->baseUrl.'/images/'.$logo; ?>" class="img-responsive" alt="Responsive image"></center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
  function hideFiscalData(){
    if($('#fiscal_data_form').css('display')=='none')
      $('#fiscal_data_form').show();
    else
      $('#fiscal_data_form').hide();
  }

</script>


