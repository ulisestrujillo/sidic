<div class="main">
	<h1>Mis direcciones</h1>

<div class="row-fluid">

	<div class="span7">
		<table class="table">
		<tr>
			<td>Domicilio</td>
			<td>Referencias</td>
		</tr>

		<?php 

		for ($pos=0; $pos < sizeof($addressList); $pos++) { 
			# code...
		?>
		<tr>
			<td><?php echo $addressList[$pos]->colonia.', '.$addressList[$pos]->ciudad.', '.$addressList[$pos]->estado; ?></td>
			<td><?php $addressList[$pos]->referencias; ?></td>
		</tr>
		<?php

		}

		?>	
		</table>
	</div>
	<div class="span1"></div>
	<div class="span4">
			<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			  'id'=>'address-form',
			  'enableAjaxValidation'=>false,
			  'action'=>Yii::app()->createUrl('shop/address/create'),
			  'htmlOptions' => array('enctype'=>'multipart/form-data'),
			  'type'=>'vertical', //horizontal				
			)); ?>

	<?php echo $form->errorSummary($model); ?>
	<?php 
		if(isset($angle_errors))
			foreach ($angle_errors as $err) {
				echo $form->errorSummary($err);
			}
	?>

			<div class="row">
				<?php echo $form->labelEx($model,'numerointerior'); ?>
				<?php echo $form->textField($model,'numerointerior',array('size'=>5,'maxlength'=>5)); ?>
				<?php echo $form->error($model,'numerointerior'); ?>
			</div>
			
			<div class="row">
				<?php echo $form->labelEx($model,'calle'); ?>
				<?php echo $form->textField($model,'calle',array('size'=>5,'maxlength'=>5)); ?>
				<?php echo $form->error($model,'calle'); ?>
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

		<div id="error"></div>
    <br/>

		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit', 
			'label'=>'Guardar',
		    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
		    'size'=>'normal', // null, 'large', 'small' or 'mini'
		)); ?>

		 <br/><br/>
		<?php $this->endWidget(); ?>



	</div>

</div>


</div>