<?php

class UserSystemController extends Controller
{
		/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete',
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('activation'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create', 'update', 'budgetaccess'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCreate()
	{
		$model=new UserSystem;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UserSystem']))
		{
      $transaction = $model->dbConnection->beginTransaction();

			try {
	      $userModel = new User;
				$model->attributes=$_POST['UserSystem'];
	      $model->active=isset($_POST['UserSystem']['active']) ? 1 : 0;
		    $model->role=$_POST['UserSystem']['role'];
		    $pass="";
	      $time=time();

				Yii::log("Inicia actionCreate: ", 'info', 'application.controllers.UserSystemController');
				if($model->validate()){
          $pass=$userModel->hashPassword($model->password);
				  
				  Yii::log("INSERT INTO user(name, address, phone_number, fiscal_data, email, username, password, updated)", "info");
				  Yii::log("VALUES('{$model->name}','{$model->address}','{$model->phone_number}',1,'{$model->email}','{$model->username}','{$pass}', {$time});", "info");

          $licensekey=Yii::app()->user->getState('licensekey');
	        $query="INSERT INTO user(name, address, phone_number, fiscal_data, email, username, password, updated, licensekey)";
	        $query.="VALUES('{$model->name}','{$model->address}','{$model->phone_number}',1,'{$model->email}','{$model->username}','{$pass}', {$time}, '{$licensekey}');";
	        $totalrecords=Yii::app()->db->createCommand($query)->execute();

          $query="SELECT id FROM user WHERE email = '$model->email'";
	        $userId=Yii::app()->db->createCommand($query)->queryScalar();

					$auth=Yii::app()->authManager;
					$authItems=$auth->getItemChildren($model->role."Tarea");

					foreach ($authItems as $authItem) {
						if(!$auth->isAssigned($authItem->name, $userId) ) {
							$auth->assign($authItem->name, $userId);
						}
					}

					if(!$auth->isAssigned($model->role, $userId) ) {
						$auth->assign($model->role, $userId);
					}

	        $activationKey=sha1(mt_rand(10000,99999).time().$model->email);
	        $activation_url = $this->createAbsoluteUrl('/userSystem/activation',array("activeKey" =>$activationKey, "email" => $model->email));

	        UserSystem::sendMail($model->email
																        	,"Bienvenido a SIDIC"
																          ,"Hola $model->name, <br/> tus credenciales de acceso son las siguientes: <br/><br/>
																            usuario: $model->username <br/> password: $model->password <br/><br/>
																            Para activar tu cuenta ingresa a la siguiente dirección: <br/>".$activation_url
																        );

				  Yii::log("Total de registros insertados: {$totalrecords}", "info");
				  Yii::log("Termina actionCreate: ", 'info', 'application.controllers.UserSystemController');

			    $transaction->commit();
				  $this->redirect(array('admin'));
				}
			} catch (Exception $e) {
			  $transaction->rollback();
				Yii::log("Ocurrió excepción:", $e);
			}
		}

		$auth=Yii::app()->authManager;
		$tasks=$auth->getTasks();
		$taskOperationList=Array();
    $operations=Array();

		foreach ($tasks as $task) {
		  $operations=$auth->getItemChildren($task->name);
      $operationList=Array();

		  foreach ($operations as $ope) {
        array_push($operationList, $ope->name);
		  }

		  $taskOperationList[$task->name]=$operationList;
		}

		$this->render('create',array(
			'model'=>$model,
			'taskOperationList'=>json_encode($taskOperationList)
		));

		Yii::log("Termina actionCreate: ", 'info', 'application.controllers.UserSystemController');
	}

	public function actionUpdate()
	{
		$model=$this->loadModel();

		if(isset($_POST['UserSystem']))
		{
      $transaction = $model->dbConnection->beginTransaction();

			try {
				$model->attributes=$_POST['UserSystem'];

				$model->name=$_POST['UserSystem']['name'];
				$model->address=$_POST['UserSystem']['address'];
				$model->phone_number=$_POST['UserSystem']['phone_number'];
				$model->email=$_POST['UserSystem']['email'];
	      $model->active=isset($_POST['UserSystem']['active']) ? 1 : 0;
	      $model->updated=time();
	      //$model->role=$_POST['UserSystem']['role'];

	      if(trim($model->name) == '')
	      	$model->addError('name', 'Debe capturar el nombre de usuario.');

	      if(trim($model->address) == '')
	      	$model->addError('address', 'Debe capturar la dirección.');

	      if(trim($model->phone_number) == '')
	      	$model->addError('phone_number', 'Debe capturar el número de teléfono.');

				/*$auth=Yii::app()->authManager;
				$authItems=$auth->getItemChildren($model->role."Tarea");

				foreach ($authItems as $authItem) {
					if(!$auth->isAssigned($authItem->name, $model->id) ) {
						$auth->assign($authItem->name, $model->id);
					}
				}

				if(!$auth->isAssigned($model->role, $model->id) ) {
					$auth->assign($model->role, $model->id);
				}*/

				if(!$model->hasErrors() && $model->save()){
          $transaction->commit();
				  $this->redirect(array('admin'));
				}else{
          $transaction->rollback();
				}
			} catch (Exception $e) {
        $transaction->rollback();
			}

		}

		$this->render('update',array(
			'model'=>$model,
			'taskOperationList'=>json_encode('[{}]')
		));
	}

	public function actionView()
	{
		$this->render('view');
	}

	public function actionAdmin()
	{
		$model=new UserSystem('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['UserSystem']))
			$model->attributes=$_GET['UserSystem'];

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
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=User::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}	

  
 	/**
	 * Activar el registro de usuario.
  */
  public function actionActivation($activeKey,$email){
  	$this->layout="";
 
    Yii::log("Inicia activación de usuario: ", 'info', 'application.controllers.UserSystemController'); 
  	$query="UPDATE user SET active=1, activekey='$activeKey' WHERE email='$email'";

    $result=Yii::app()->db->createCommand($query)->query();

    Yii::log("Termina activación de usuario: ", 'info', 'application.controllers.UserSystemController');

 		$this->render('_activation');
  }

  /**
  *Muestra un formulario para controlar el acceso a los presupuestos
  *@param User Id es el id del usuario al que se le va a controlar el acceso
  */
  public function actionBudgetAccess($userid){
    if(isset($_POST['items'])){
    	$items=json_decode($_POST['items']);
      
      $query="DELETE FROM budgetuser_rel WHERE userid = $userid;";
    	foreach ($items as $item) {
        $query.="INSERT INTO budgetuser_rel(projectid, budgetid, userid)";
        $query.="SELECT ".$item->projectid.",".$item->budgetid.",".$userid.";";
    	}
    	
      $result=Yii::app()->db->createCommand($query)->execute();
    }

    $projectList=BudgetUserRel::model()->findAll(array(
						        'condition' => 'userid='.$userid
						      ));

    $budgetList=Array();
    $item=null;
    
    foreach ($projectList as $rel) {
    	$item=Array();
    	$item["projectid"]=$rel->projectid;
    	$item["budgetid"]=$rel->budgetid;
    	array_push($budgetList, $item);
    }

    $budgetList=json_encode($budgetList);

    $user=User::model()->findByPk($userid);

    $this->render('budgetaccess',
    	array(
    		'userid'=>$userid,
    		'projectList'=>$budgetList,
    		'username'=>$user->name
    ));
  }


}