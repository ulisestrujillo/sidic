<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
  'Login',
);
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'login-form',
  'enableAjaxValidation'=>false,
  'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

  <?php echo $form->textField($model,'username'); ?>

  <?php echo $form->passwordField($model,'password'); ?>

  <div id="error"></div>
  <?php 
      echo CHtml::ajaxSubmitButton('Ingresar',
                                    CController::createUrl('/site/login'), 
                                    $ajaxOptions=array (
                                      'success'=>'function(data){
                                          jQuery("#error").html(data); 

                                          if(data=="")
                                            window.location="'.CController::createUrl('cart/display').'";
                                        }'
                                      )
                                    );

   ?>

<?php $this->endWidget(); ?>

</div><!-- form -->
<!-- </div> -->
