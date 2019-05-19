<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel {
	public $username;
	public $password;
	public $rememberMe;
	private $_identity;
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules() {
		return array(
		// username and password are required
		array(
		'username',
		'required',
		'message' => "Capturar nombre de usuario." 
		),
		array(
		'password',
		'required',
		'message' => "Capturar contraseña." 
		),
		// rememberMe needs to be a boolean
		array(
		'rememberMe',
		'boolean' 
		),
		// password needs to be authenticated
		array(
		'password',
		'authenticate' 
		) 
		);
	}
	
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels() {
		return array(
		'rememberMe' => 'Recordarme la siguiente vez' 
		);
	}
	
	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute, $params) {
		if (!$this->hasErrors()) {
			$this->_identity = new UserIdentity($this->username, $this->password);
			
			if (!$this->_identity->authenticate()) {
				$this->addError('password', 'Nombre de usuario o password están incorrectos.');
			} else {
				$query = "SELECT COUNT(*) AS total FROM user WHERE id = " . $this->_identity->id . " AND active=1";
				$totRec = Yii::app()->db->createCommand($query)->queryScalar();
				
				if ($totRec <= 0) {
					$this->addError('password', "Debe activar su cuenta de usuario antes de poder iniciar sesión.");
				} else {
					/* validar la vigencia de la licencia */
					$query = "SELECT
						CASE WHEN now()>=expiration THEN
						  1 ELSE 0
						END AS expirated
						FROM license WHERE licensekey='" . Yii::app()->user->getState('licensekey') . "'";
					
					$rset = Yii::app()->db->createCommand($query)->queryAll();
					if ($rset[0]["expirated"] == 1) {
						$this->addError('password', "Su licencia ha expirado, comuniquese al departamento de ventas.");
					}
				}
			}
		}
	}
	
	/**
	 * Logs in the user using the given username and password in the model.
	 *
	 * @return boolean whether login is successful
	 */
	public function login() {
		if ($this->_identity === null) {
			$this->_identity = new UserIdentity($this->username, $this->password);
			$this->_identity->authenticate();
		}
		
		if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
			$duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
			Yii::app()->user->login($this->_identity, $duration);
			return true;
		} else
			return false;
	}
	protected function beforeValidate() {
		if ($this->rememberMe == 'on') {
			$this->rememberMe = 1;
		} else {
			$this->rememberMe = 0;
		}
		
		return true;
	}
}
