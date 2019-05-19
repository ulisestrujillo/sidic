<div class="container-fluid">
  <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="card">
        <div class="header">
            <h2></h2>
        </div>
        <div class="body">
<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'project-form',
  'enableAjaxValidation'=>false,
  'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>
<?php CHtml::$errorContainerTag = 'label'; ?>

          <label for="Presupuesto_name">*Nombre</label>

          <div class="row clearfix">
            <div class="col-md-4">
              <div class="form-group">
                  <div class="form-line">
            <input value="<?php echo $project->id; ?>" name="Presupuesto[projectid]" type="hidden" id="Presupuesto_projectid" />
            <input value="<?php echo $model->name; ?>" name="Presupuesto[name]" type="text" id="Presupuesto_name" class="form-control" placeholder="">
            <?php echo $form->error($model,'name', array('class'=>'error')); ?>
                  </div>
              </div>
            </div>
            <div class="col-md-4">
            <button type="submit" class="btn btn-raised btn-primary m-t-15 waves-effect"><?php echo $model->isNewRecord ? 'Crear' : 'Guardar'; ?></button>
            </div>
          </div>

<?php $this->endWidget(); ?>

        </div>
      </div>
    </div>
  </div>
</div>

