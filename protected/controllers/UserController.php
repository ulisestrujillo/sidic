<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';
  private $_model;
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

/**
	 * @return array action filters
	 */
  public function filters() {
    return array(
      'accessControl',
      'ajaxOnly + saveBillingData',
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
				'actions'=>array('registration', 'confirmation', 'register'),
				'users'=>array('*'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('profile','update','admin', 'savebillingdata'),
				'users'=>array('@'),
			),
			array('deny',  // allow all users to perform 'index' and 'view' actions
				'users'=>array('*'),
			),
		);
	}	

	/**
	 * Registration user
	 */
	public function actionRegistration() 
	{
		$this->layout="//layouts/main";
		$model=new RegistrationForm;

		if(isset($_POST['RegistrationForm']))
		{
      $userModel = new User;
			$model->attributes=$_POST['RegistrationForm'];
			
			if($model->validate() ){
        $pass=$userModel->hashPassword($model->password);
				$model->password=$pass;
				$model->verifyPassword=$pass;
        $model->updated=time();
        $model->name="";

				if($model->save()){
				  $this->redirect(Yii::app()->createUrl('/site/page', array('view'=>'confirmation')) );
				}else{
				  $model->password =$_POST['RegistrationForm']['password'];
			  }
			}
		}

		$this->render('/user/registration', array('model'=>$model));
	}

	/**
	 * Registro de usuario desde el carrito de la compra
	 */
	public function actionRegister()
	{
		$this->layout="//layouts/main";
		$model=new RegistrationForm;

		if(isset($_POST['RegistrationForm']))
		{
      $userModel = new User;
			$model->attributes=$_POST['RegistrationForm'];

			if($model->validate() ){
        $token=com_create_guid();

        $pass=$userModel->hashPassword($model->password);
				$model->password=$pass;
				$model->verifyPassword=$pass;
        $model->updated=time();
        $model->active=1;
        $model->name="";
        $model->licensekey=$token;

				if($model->save()){
          /*Por cada nuevo usuario se genera una licencia nueva con fecha de expiracion de un año sin activar*/
          $query="INSERT INTO license(licensekey, expiration) VALUES('{$token}', DATE_ADD(now(), INTERVAL 365 DAY));";

          Yii::app()->db->createCommand($query)->execute();

          $loginForm=new LoginForm;

          $loginForm->username=$model->username;
          $loginForm->password=$_POST['RegistrationForm']['password'];

          $loginForm->login();

				  $this->redirect(Yii::app()->createUrl('/shop/cart/display', 
				  	array('nu'=>true)
				  ));
				}else{
				  $model->password =$_POST['RegistrationForm']['password'];
			  }
			}
		}

		$this->render('/user/cartregister', array('model'=>$model));
	}	

	public function actionProfile(){
		$model = new ProfileForm;
		$this->layout="//layouts/profile";
		$flag=str_replace("{", "", Yii::app()->user->getState('licensekey'));
		$flag=str_replace("}", "", $flag);
		$logo='enterprise-logo_'.$flag.'.jpg';
			
		if(isset($_POST['ProfileForm'])){
			$this->Update($_POST['ProfileForm']);
		}else{
		  $fiscal = new Fiscal;
			$model=User::model()->findByPk(Yii::app()->user->id);

			if($model->fiscal==null)
			  $model->fiscal = new Fiscal;
				
			$flag=str_replace("{", "", Yii::app()->user->getState('licensekey'));
			$flag=str_replace("}", "", $flag);
			$logo='enterprise-logo_'.$flag.'.jpg';
					
			$this->render('/user/profile', 
				array(
					'model'=>$model,
					'fiscal'=>$model->fiscal,
					'logo'=>$logo
				)
			);
		}
	}

  /***
  *Muestra el formulario para capturar los datos de facturación del nuevo usuario que se registra
  *desde el carrito de la compra
  */
	public function actionSaveBillingData(){
		$model = new BillingForm;
		$this->layout="//layouts/profile";

		if(isset($_POST['BillingForm'])){

			$model->name=$_POST['BillingForm']['name'];
			$model->address=$_POST['BillingForm']['address'];
			$model->country=$_POST['BillingForm']['country'];
			$model->state=$_POST['BillingForm']['state'];
			$model->city=$_POST['BillingForm']['city'];
			$model->phone_number=$_POST['BillingForm']['phone_number'];
			$model->rfc=$_POST['BillingForm']['rfc'];

      if($model->validate()){
      	$modelUser=User::model()->findByPk(Yii::app()->user->id);
      	$email=$modelUser->email;
      	$userId=Yii::app()->user->id;

				$query = 
				"DELETE FROM fiscal WHERE userid = $userId;
				INSERT INTO fiscal(userid,name,lastname,address,country,state,city,rfc,phone_number,email)
				VALUES($userId,'$model->name','','$model->address','$model->country','$model->state','$model->city', '$model->rfc','$model->phone_number','$email');";

        Yii::app()->db->createCommand($query)->execute();
        $datosFacturacion="$model->name $model->address $model->city, $model->state, $model->country";

      	$response["code"]="200";
      	$response["message"]="";
      	$response["data"]=$datosFacturacion;

        echo json_encode($response);
      	return;
      }else{
      	$response["code"]="500";
      	$response["message"]="ERROR";
      	$response["data"]=json_encode($model->getErrors());
      	echo json_encode($response);
      	return;
      }

		}

    $this->render('//shop/views/cart/display', array(
		  'model'=>$model
    ));

	}

	private function uploadLogo(){
	  $image = CUploadedFile::getInstanceByname('ProfileForm[logo]');

	  if($image != null)
	  {
      if($image->size >= 100000){
	      Yii::app()->user->setFlash('exceedSize', 'Sólo se permiten imágenes de hasta 100 kilobyes');
      }else{
		    if(strpos(strtolower($image->name), ".jpg")==""){
		      Yii::app()->user->setFlash('uploadLogoError', 'Sólo se permiten imágenes con extensión .jpg');
		    }
		    else
		    {
					$flag=str_replace("{", "", Yii::app()->user->getState('licensekey'));
					$flag=str_replace("}", "", $flag);
				
					$file=Yii::getPathOfAlias('webroot').'/images/enterprise-logo_'.$flag.'.jpg';
				  $res = move_uploaded_file($image->tempName, $file);

		      if($res == true){
						Yii::app()->user->setFlash('uploadLogoSuccess', 'Se subió el logotipo exitosamente.');
		      }else{
		        Yii::log("Error al subir el logotipo: ", 'error', 'application.controllers.UserController');
		      }
		    }
      }
	  }
	}

  public function Update($form, $logo){
    $fiscal = new Fiscal();
    $model = $this->loadModel(Yii::app()->user->id);
    $profile=new ProfileForm;

    $this->uploadLogo();

    if($model->fiscal == null)
    	$model->fiscal = new Fiscal;

    if($model->fiscal!= null)
      $fiscal = $model->fiscal;

    $model->attributes=$form;
    $profile->attributes=$form;
    $profile->fiscal=$fiscal;

		$model->name=$form['name'];
		$model->address=$form['address'];
		$model->country=strtoupper($form['country']);
		$model->state=strtoupper($form['state']);
		$model->city=strtoupper($form['city']);
		$model->rfc=$form['rfc'];
		$model->phone_number=$form['phone_number'];
		$model->email=$form['email'];
    $model->updated=time();
		
		$model->fiscal_data=0;
		if(isset($form['fiscal_data'])){
		  $model->fiscal_data=1;
		  $profile->fiscal_data=1;
		}

    //datos fiscales
		$model->fiscal->userid=$model->id;
		$model->fiscal->name=$form['fiscalName'];
		$model->fiscal->address=$form['fiscalAddress'];
		$model->fiscal->country=strtoupper($form['fiscalCountry']);
		$model->fiscal->state=strtoupper($form['fiscalState']);
		$model->fiscal->city=strtoupper($form['fiscalCity']);
		$model->fiscal->rfc=$form['fiscalRfc'];
		$model->fiscal->phone_number=$form['fiscal_phone_number'];
		$model->fiscal->email=$form['fiscalEmail'];

    if ($profile->validate()){
    	if($model->fiscal_data==0){
    		$fiscal->attributes=$model->fiscal;

        if($fiscal->validate())
          $fiscal->save();
      }
      $model->save();
      Yii::app()->user->setFlash('updated', 'Se han actualizado los datos satisfactoriamente.');
    }else{
    	$profile->fiscal_data = $model->fiscal_data;
    	$model=$profile;
    	if($model->fiscal_data==0){
    		$fiscal->attributes=$model->fiscal;

        $fiscal->validate();
      }    	
    }

    $model->id=Yii::app()->user->id;    

		$this->render('/user/profile', 
			array(
				'model'=>$model,
				'fiscal'=>$fiscal,
				'logo'=>$logo,
			)
		);    

  }

  public function loadModel($id){
    $model=User::model()->findByPk($id);
		
		/*if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');*/
		return $model;

  }


}