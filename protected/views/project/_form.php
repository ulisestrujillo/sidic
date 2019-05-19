<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<div class="block-header">
    <div class="row">
        <div class="col-lg-7 col-md-6 col-sm-12">
            <h2>Proyecto <?php echo $model->name; ?>
<?php if(!$model->isNewRecord): ?>
  <?php $url = Yii::app()->createUrl('/budget/list').'&id='.$model->id; ?>
  </h2><a href="<?php echo $url; ?>" style="color: #337ab7;text-decoration: none;">Ver presupuestos </a>
<?php endif ?>
              <br/><small class="text-muted">Los campos con asterisco son requeridos</small>
        </div>
        <div class="col-lg-5 col-md-6 col-sm-12">
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i> Sidic</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0);">Catálogos</a></li>
                <li class="breadcrumb-item active">Proyecto</li>
            </ul>
        </div>
    </div>
</div>

	<div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="card">
              <div class="header">
                  <h2></h2>
              </div>
              <div class="body">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'project-form',
  // Please note: When you enable ajax validation, make sure the corresponding
  // controller action is handling ajax validation correctly.
  // There is a call to performAjaxValidation() commented in generated controller code.
  // See class documentation of CActiveForm for details on this.
  'enableAjaxValidation'=>false,
  'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

<?php CHtml::$errorContainerTag = 'label'; ?>

  <label for="Project_code">*Clave</label>
  <div class="form-group">
      <div class="form-line">
          <input value="<?php echo $model->code; ?>" name="Project[code]" id="Project_code" type="text" class="form-control" placeholder="">
          <?php echo $form->error($model,'code', array('class'=>'error')); ?>
          
      </div>
  </div>  


  <label for="Project_name">*Nombre</label>
  <div class="form-group">
      <div class="form-line">
          <input value="<?php echo $model->name; ?>" name="Project[name]" id="Project_name" type="text" class="form-control" placeholder="">
          <?php echo $form->error($model,'name', array('class'=>'error')); ?>
          
      </div>
  </div>  


  <label for="Project_address">*Dirección</label>
  <div class="form-group">
      <div class="form-line">
          <input value="<?php echo $model->address; ?>" name="Project[address]" id="Project_address" type="text" class="form-control" placeholder="">
          <?php echo $form->error($model,'address', array('class'=>'error')); ?>
          
      </div>
  </div>  


  <label for="Project_location">*Plaza</label>
  <div class="form-group">
      <div class="form-line">
          <input value="<?php echo $model->location; ?>" name="Project[location]" id="Project_location" type="text" class="form-control" placeholder="">
          <?php echo $form->error($model,'location', array('class'=>'error')); ?>
          
      </div>
  </div>  

    <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', array('class'=>'btn btn-raised btn-primary m-t-15 waves-effect')); ?>
    <?php echo CHtml::link('Cancelar', Yii::app()->createUrl('project/admin') ,array('class'=>'btn btn-raised btn-primary m-t-15 waves-effect')); ?>

<?php $this->endWidget(); ?>

              </div>
          </div>
      </div>
  </div>
