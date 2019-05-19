<?php
/* @var $this SupplierController */
/* @var $model Supplier */

$this->breadcrumbs=array(
	'Suppliers'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Supplier', 'url'=>array('index')),
	array('label'=>'Create Supplier', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#supplier-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h2>Proveedores</h2>
<?php echo CHtml::link('crear', Yii::app()->createUrl('supplier/create') ,array('class'=>'btn btn-raised btn-primary m-t-15 waves-effect')); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'supplier-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'cssFile'=>Yii::app()->theme->baseUrl.'/assets/cgridview/styles.css',
	'columns'=>array(
		'code',
		'name',
		'rfc',
		'patronal_record',
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'update' => array(
				    //'label'=>'...',     // text label of the button
				    'url'=>'Yii::app()->controller->createUrl("supplier/update",array("id"=>$data->id))',
				    'imageUrl'=>false,  // image URL of the button. If not set or false, a text link is used
				    //'options'=>array('class'=>'btn btn-raised btn-primary m-t-15 waves-effect'), // HTML options for the button tag
				    //'click'=>'...',     // a JS function to be invoked when the button is clicked
				    'visible'=>'true',   // a PHP expression for determining whether the button is visible
				),	
				'delete' => array(
				    //'label'=>'...',     // text label of the button
				    //'url'=>'...',       // a PHP expression for generating the URL of the button
				    //'imageUrl'=>'...',  // image URL of the button. If not set or false, a text link is used
				    //'options'=>array(...), // HTML options for the button tag
				    //'click'=>'...',     // a JS function to be invoked when the button is clicked
				    'visible'=>'false',   // a PHP expression for determining whether the button is visible
				),	
				'view' => array(
				    //'label'=>'...',     // text label of the button
				    //'url'=>'...',       // a PHP expression for generating the URL of the button
				    //'imageUrl'=>'...',  // image URL of the button. If not set or false, a text link is used
				    //'options'=>array(...), // HTML options for the button tag
				    //'click'=>'...',     // a JS function to be invoked when the button is clicked
				    'visible'=>'false',   // a PHP expression for determining whether the button is visible
				)			
			),
		),
	),
)); ?>
