<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class RegistrationForm extends User
{
	public $verifyPassword;

	/**
	 * Declares the validation rules.
	 */
	public function rules() {
		$rules = array(
			array('password', 'required','message' => "Capturar contraseña."),
			array('email', 'required','message' => "Captura un correo electrónico válido."),
			array('verifyPassword', 'required','message' => "Capture su clave de acceso."),

			array('username', 'length', 'max'=>20, 'min' => 3,'message' => "Username incorrecto debe contener entre 3 y 20 carácteres"),
			array('password', 'length', 'max'=>128, 'min' => 4,'message' => "Password incorrecto (se permiten como mínimo 4 carácteres)."),
			array('email', 'unique', 'message' => "Este correo electrónico ya está registrado."),
			array('username', 'unique', 'message' => "Este nombre de usuario ya está registrado."),
			array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => "Las contraseñas no coinciden."),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => "Símbolos permitidos (A-z0-9)."),
		);

		return $rules;
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'verifyPassword'=>'Comprobar password.',
		);
	}
	
}