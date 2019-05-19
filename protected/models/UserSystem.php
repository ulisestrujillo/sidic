<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class UserSystem extends User
{
	public $id;
	public $name;
	public $address;
	public $phone_number;
	public $email;
	public $username;
	public $password;
	public $role;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, address, phone_number, email, username, password', 'required'),
			array('username', 'unique', 'message' => "Este nombre de usuario ya existe pruebe con otro."),
			array('email', 'unique', 'message' => "Este correo electrónico ya existe pruebe con otro."),
			// email has to be a valid email address
			array('email', 'email'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('username, name, address, phone_number, email', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>'Usuario',
			'name'=>'Nombre',
			'address'=>'Dirección',
			'phone_number'=>'Teléfono',
			'email'=>'Correo',
			'password'=>'Contraseña',
			'role'=>'Rol',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

    $licensekey=Yii::app()->user->getState('licensekey');
    
		$criteria->compare('username',$this->username,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone_number',$this->phone_number,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('role',$this->role,true);
		$criteria->addCondition("licensekey='".$licensekey."'");

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
	    'pagination'=>array(
	        'pageSize'=>Yii::app()->params['userPerPage'],
	    ),
		));
	}

  /**
  * sendMail Method
  */
	public static function sendMail($email,$subject,$message) {
    require(Yii::getPathOfAlias("webroot").'/protected/vendor/phpmailer/PHPMailerAutoload.php');
        
 	  $mail = new PHPMailer();
    $mail->SMTPSecure = 'tls';
    $mail->Username = "autoescuelaprofesionalculiacan@gmail.com";
    $mail->Password = "Auto35cu3l4";
    //$mail->AddAddress("ulises.trujillo@primuslabs.com");
    //$mail->AddReplyTo('ulises.trujillo.aguirre@gmail.com', 'la tienda');
    $mail->AddAddress($email);
    $mail->FromName = "SIDIC";
    $mail->Host = gethostbyname('smtp.gmail.com');
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->CharSet="UTF-8";
    
    $mail->AddReplyTo('mileniowebs@gmail.com', 'Milenio Webs');
    $mail->From = 'info@sidic.mx';
    $mail->IsHTML(true);
    $mail->Subject = $subject;
    $mail->msgHTML($message);

    $mail->send();
	}

}