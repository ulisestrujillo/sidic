<?php $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'project-grid',
  'dataProvider'=>$model->search(),
  'filter'=>$model,
  'cssFile'=>Yii::app()->theme->baseUrl.'/assets/cgridview/styles.css',
  'columns'=>array(
    'name',
    'created',
    array(
      'class'=>'CButtonColumn',
      'buttons'=>array(
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



