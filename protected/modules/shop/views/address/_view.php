<?php
/* @var $this AddressController */
/* @var $data Address */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userid')); ?>:</b>
	<?php echo CHtml::encode($data->userid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('calle')); ?>:</b>
	<?php echo CHtml::encode($data->calle); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('numerointerior')); ?>:</b>
	<?php echo CHtml::encode($data->numerointerior); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('numeroexterior')); ?>:</b>
	<?php echo CHtml::encode($data->numeroexterior); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cpostal')); ?>:</b>
	<?php echo CHtml::encode($data->cpostal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estado')); ?>:</b>
	<?php echo CHtml::encode($data->estado); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('ciudad')); ?>:</b>
	<?php echo CHtml::encode($data->ciudad); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('colonia')); ?>:</b>
	<?php echo CHtml::encode($data->colonia); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('referencias')); ?>:</b>
	<?php echo CHtml::encode($data->referencias); ?>
	<br />

	*/ ?>

</div>