<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
	private $_id;
	private $_name;
	
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * 
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {
		$user = User::model ()->find("LOWER(username)=?", array (
				strtolower($this->username) 
		));
		
		if ($user === null)
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		else if (! $user->validatePassword ( $this->password ))
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		else {
			$this->_id = $user->id;
			$this->_name = $user->name;
			
			Yii::app ()->user->setState ( 'profile', $user->profile );
			Yii::app ()->user->setState ( 'email', $user->email );
			
			$licensekey = Yii::app ()->user->getState ( 'licensekey' );
			if (! $licensekey) {
				Yii::app ()->user->setState ( 'licensekey', $user->licensekey );
			}
			
			$this->errorCode = self::ERROR_NONE;
		}
		return $this->errorCode == self::ERROR_NONE;
	}
	
	/**
	 *
	 * @return integer the ID of the user record
	 */
	public function getId() {
		return $this->_id;
	}
	
	/**
	 *
	 * @return integer the ID of the user record
	 */
	public function getName() {
		return $this->_name;
	}
}