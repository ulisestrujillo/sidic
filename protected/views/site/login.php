<style type="text/css">
  .has-error{color:#e61616;}
</style>

<div class="block-header">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12"></div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <h2>Iniciar sesi&oacute;n</h2>
        </div>
    </div>
</div>

  <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-12"></div>
      <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="body">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'LoginForm',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

  <?php CHtml::$errorContainerTag = 'div'; ?>


  <label for="LoginForm_username">Usuario</label>
  <div class="form-group">
      <div class="form-line">
          <input value="<?php echo $model->username; ?>" name="LoginForm[username]" id="LoginForm_username" type="text" class="form-control" placeholder="">
          <?php echo $form->error($model,'username', array('class'=>'has-error')); ?>
          
      </div>
  </div>	

  <label for="LoginForm_password">Contraseña</label><a href="<?php echo Yii::app()->createUrl('/site/login'); ?>">&nbsp;Lo olvid&eacute; ?</a>
  <div class="form-group">
      <div class="form-line">
          <input value="<?php echo $model->password; ?>" name="LoginForm[password]" id="LoginForm_password" type="password" class="form-control" placeholder="">
          <?php echo $form->error($model,'password', array('class'=>'has-error')); ?>
          
      </div>
  </div>		

  <div class="input-group input-group-lg">
    <input type="checkbox" class="filled-in" name="LoginForm[rememberMe]" id="LoginForm_rememberMe">
    <label for="LoginForm_rememberMe">&nbsp;Recordarme</label>
    <?php echo $form->error($model,'rememberMe', array('class'=>'has-error')); ?>
  </div>	

 
  <div class="row">
    <div class="col-lg-4">
        
    </div> 
    <div class="col-lg-4"></div>
  </div>


  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <?php echo CHtml::submitButton('Iniciar sesión', array('class'=>'btn btn-raised btn-primary waves-effect', 'style'=>'width:100%')); ?>
    </div>
  </div>

  <?php if(!isset($_GET["cart"])): ?>
  <div class="row"><br/>
    <div class="col-lg-12 col-md-12 col-sm-12">
      <h4>¿No tienes una cuenta?</h4><br/>
      <a style="width:100%;text-decoration: underline;" type="button" 
      href="<?php echo Yii::app()->createUrl('/user/registration'); ?>">Registrate</a>
    </div>
  </div>
<?php endif; ?>
<?php $this->endWidget(); ?>
        </div>
      </div>
  </div>


