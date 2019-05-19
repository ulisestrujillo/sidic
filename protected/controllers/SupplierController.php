<?php

class SupplierController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'admin'),
				'roles'=>array('admin', 'elaborador'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Supplier;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Supplier']))
		{
			$model->attributes=$_POST['Supplier'];
			$model->bank=isset($_POST['Supplier']['bank']) ? $_POST['Supplier']['bank'] : '';
			$model->account=isset($_POST['Supplier']['account']) ? $_POST['Supplier']['account'] : '';
			$model->clabe=isset($_POST['Supplier']['clabe']) ? $_POST['Supplier']['clabe'] : '';
			$model->active=isset($_POST['Supplier']['active']) ? 1 : 0;
			$model->updated=time();
			$model->licensekey=Yii::app()->user->getState('licensekey');
			$model->supplier_id = Supplier::genId();

			if($model->validate()){
			  if($model->save()){
			    $this->redirect(array('admin'));
			  }
			}
		}

		Yii::app()->user->setFlash('created', 'Se ha creado con éxito el proveedor: {$model->name}');

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Supplier']))
		{
			$model->attributes=$_POST['Supplier'];
      $model->bank=isset($_POST['Supplier']['bank']) ? $_POST['Supplier']['bank'] : '';
      $model->account=isset($_POST['Supplier']['account']) ? $_POST['Supplier']['account'] : '';
      $model->clabe=isset($_POST['Supplier']['clabe']) ? $_POST['Supplier']['clabe'] : '';
      $model->active=isset($_POST['Supplier']['active']) ? 1 : 0;
      $model->updated=time();
      
			if($model->save()){
        Yii::app()->user->setFlash('updated', "Se ha actualizado con éxito el proveedor: {$model->name}");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Supplier');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Supplier('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Supplier']))
			$model->attributes=$_GET['Supplier'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Supplier the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$licensekey=Yii::app()->user->getState('licensekey');
		$model=Supplier::model()->findByPk($id);
		
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Supplier $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='supplier-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
