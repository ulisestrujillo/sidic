<title>SIDIC - Proyectos</title>
<div class="row">
	<div class="col-md-12">
    <h3 style="margin-top:0px;">Proyectos</h3>
<?php $access=User::getPrivileges(); ?>
<?php if(Yii::app()->user->checkAccess('admin') || $access["crearProyecto"]): ?>
		<a class="btn btn-primary" href="<?php echo Yii::app()->createUrl('project/create'); ?>" 
			role="button">Crear Proyecto</a>
<?php endif; ?>			

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'project-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'cssFile'=>Yii::app()->theme->baseUrl.'/assets/cgridview/styles.css',
	'columns'=>array(
		'code',
		'name',
		'address',
		'location',
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'update' => array(
				    //'label'=>'...',     // text label of the button
				    //'url'=>'...',       // a PHP expression for generating the URL of the button
				    //'imageUrl'=>false,  // image URL of the button. If not set or false, a text link is used
				    //'options'=>array('class'=>'btn btn-raised btn-primary m-t-15 waves-effect'), // HTML options for the button tag
				    //'click'=>'...',     // a JS function to be invoked when the button is clicked
				    'visible'=>Yii::app()->user->checkAccess('admin')==true?'true':'false',   // a PHP expression for determining whether the button is visible
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
				    //'label'=>'Ver presupuesto',     // text label of the button
				    'url'=>'Yii::app()->controller->createUrl("budget/list",array("id"=>$data->primaryKey))',       // a PHP expression for generating the URL of the button
				    //'imageUrl'=>false,  // image URL of the button. If not set or false, a text link is used
				    //'options'=>array(...), // HTML options for the button tag
				    //'click'=>'...',     // a JS function to be invoked when the button is clicked
				    'visible'=>'true',   // a PHP expression for determining whether the button is visible
				)			
			),
		),
	),
)); ?>

	</div>
</div>
