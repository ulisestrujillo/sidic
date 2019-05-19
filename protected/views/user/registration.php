<?php
/* @var $this RegistrationFormController */
/* @var $model RegistrationForm */
/* @var $form CActiveForm */
?>

<div class="block-header">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h2>Registro de usuario<small class="text-muted">Los campos con asterisco son requeridos</small>
            </h2>
        </div>
    </div>
</div>

<div class="container-fluid">
  <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="card">
              <div class="header">
                  <h2></h2>
              </div>
              <div class="body">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'RegistrationForm',
  // Please note: When you enable ajax validation, make sure the corresponding
  // controller action is handling ajax validation correctly.
  // See class documentation of CActiveForm for details on this,
  // you need to use the performAjaxValidation()-method described there.
  'enableAjaxValidation'=>false,
)); ?>
  <?php CHtml::$errorContainerTag = 'label'; ?>

  <label for="username">*Usuario</label>
  <div class="form-group">
      <div class="form-line">
          <input value="<?php echo $model->username; ?>" name="RegistrationForm[username]" id="RegistrationForm_username" type="text" class="form-control" placeholder="">
          <?php echo $form->error($model,'username', array('class'=>'error')); ?>
          
      </div>
  </div>

  <label for="email">*Email</label>
  <div class="form-group">
      <div class="form-line">
          <input value="<?php echo $model->email; ?>" name="RegistrationForm[email]" id="RegistrationForm_email" type="text" class="form-control" placeholder="">
          <?php echo $form->error($model,'email', array('class'=>'error')); ?>
          
      </div>
  </div>  

  <label for="password">*Contraseña</label>
  <div class="form-group">
      <div class="form-line">
          <input value="<?php echo $model->password; ?>" name="RegistrationForm[password]" id="RegistrationForm_password" type="password" class="form-control" placeholder="">
          <?php echo $form->error($model,'password', array('class'=>'error')); ?>
          
      </div>
  </div>  

  <label for="verifyPassword">*Confirma contraseña</label>
  <div class="form-group">
      <div class="form-line">
          <input value="<?php echo $model->verifyPassword; ?>" name="RegistrationForm[verifyPassword]" id="RegistrationForm_verifyPassword" type="password" class="form-control" placeholder="">
          <?php echo $form->error($model,'verifyPassword', array('class'=>'error')); ?>
          
      </div>
  </div>  

  <?php echo CHtml::submitButton('Crear', array('class'=>'btn btn-raised btn-primary m-t-15 waves-effect')); ?>

  <div class="row">
    <div class="col-lg-3 m-t-20"></div>
    <div class="col-lg-6 m-t-20">
      <a href="<?php echo Yii::app()->createUrl('/site/login'); ?>">Ya estás registrado ?</a>
    </div> 
    <div class="col-lg-3 m-t-20"></div>
  </div>  

  <?php $this->endWidget(); ?>

              </div>
          </div>
      </div>
  </div>
</div>
