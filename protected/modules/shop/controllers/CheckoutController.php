<?php

class CheckoutController extends Controller
{

  public $layout = '//layouts/main';

  // Uncomment the following methods and override them if needed
  /*
  public function filters()
  {
    // return the filter configuration for this controller, e.g.:
    return array(
      'inlineFilterName',
      array(
        'class'=>'path.to.FilterClass',
        'propertyName'=>'propertyValue',
      ),
    );
  }

  public function actions()
  {
    // return external action classes, e.g.:
    return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
        'class'=>'path.to.AnotherActionClass',
        'propertyName'=>'propertyValue',
      ),
    );
  }
  */

  public function init(){
      if(!isset(Yii::app()->session['cart'])){
          echo 'session expirada';
      }
  }

  public function accessRules()
  {
    return 
      array(
        array(
          'allow',  // allow all users to perform 'index' and 'view' actions
          'actions'=>array('process'),
          'users'=>array('*'),
        ),
        array(
          'deny', // deny all users
          'users' => array(
            '*'
        )
      )
    );
  }

  public function actionProcess()
  {
    $userBO = new UserBO;

    $hasAddress=$userBO->userHasAddress();

    if($hasAddress):
      $this->redirect('http://www.paypal.com');
    else:
      $this->redirect(Yii::app()->createUrl('shop/address/admin'));
    endif;
  }

}