<?php
/* @var $this AddressController */
/* @var $model Address */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'address-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'userid'); ?>
		<?php echo $form->textField($model,'userid'); ?>
		<?php echo $form->error($model,'userid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'calle'); ?>
		<?php echo $form->textArea($model,'calle',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'calle'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'numerointerior'); ?>
		<?php echo $form->textField($model,'numerointerior',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'numerointerior'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'numeroexterior'); ?>
		<?php echo $form->textField($model,'numeroexterior',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'numeroexterior'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cpostal'); ?>
		<?php echo $form->textField($model,'cpostal',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'cpostal'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'estado'); ?>
		<?php echo $form->textField($model,'estado',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'estado'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ciudad'); ?>
		<?php echo $form->textField($model,'ciudad',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'ciudad'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'colonia'); ?>
		<?php echo $form->textField($model,'colonia',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'colonia'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'referencias'); ?>
		<?php echo $form->textArea($model,'referencias',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'referencias'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->