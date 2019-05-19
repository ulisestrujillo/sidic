<?php

class User extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_user':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $email
	 * @var string $profile
	 */

  //fiscal data
  public $fiscalName;
  public $fiscalAddress;
  public $fiscalCountry;
  public $fiscalState;
  public $fiscalCity;
  public $fiscalRfc;
  public $fiscal_phone_number;
  public $fiscalEmail; 
  public $fiscal_data;
  public $licensekey;

	const OP_ELIMINAR_PARTIDA = "eliminarPartida";
	const OP_GUARDAR_PRESUPUESTO = "guardarPresupuesto";
	const OP_EDITAR_TOPE = "editarTope";
	const OP_CREAR_ORDEN = "crearOrden";
	const OP_AUTORIZAR_ORDEN = "autorizarOrden";
	const OP_CREAR_PARTIDA = "crearPartida";
	const OP_CREAR_SUBPARTIDA = "crearSubpartida";
	const OP_VER_PRESUPUESTO = "verPresupuesto";
	const OP_CREAR_PRESUPUESTO = "crearPresupuesto";
	const OP_CREAR_PROVEEDOR = "crearProveedor";
	const OP_CREAR_PROYECTO = "crearProyecto";
	const OP_PAGAR_ORDEN = "pagarOrden";
	const OP_PORPAGAR_ORDEN = "porpagarOrden";
	const OP_RECIBIR_ORDEN = "recibirOrden";
	const OP_RECIBIR_ORDEN_PARCIAL = "recibirOrdenParcial";

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email, name', 'required'),
			array('username, password, email', 'length', 'max'=>128),
			array('profile', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'posts' => array(self::HAS_MANY, 'Post', 'author_id'),
			'fiscal' => array(self::HAS_ONE, 'Fiscal', 'userid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'profile' => 'Profile',
		);
	}

	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		return CPasswordHelper::verifyPassword($password,$this->password);
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @return string hash
	 */
	public function hashPassword($password)
	{
		return CPasswordHelper::hashPassword($password);
	}

  public static function getPrivileges(){
		$privileges=Array();

		$privileges[User::OP_ELIMINAR_PARTIDA]=false;
		$privileges[User::OP_GUARDAR_PRESUPUESTO]=false;
		$privileges[User::OP_EDITAR_TOPE]=false;
		$privileges[User::OP_CREAR_ORDEN]=false;
		$privileges[User::OP_RECIBIR_ORDEN_PARCIAL]=false;
		$privileges[User::OP_AUTORIZAR_ORDEN]=false;
		$privileges[User::OP_CREAR_PARTIDA]=false;
		$privileges[User::OP_CREAR_SUBPARTIDA]=false;
		$privileges[User::OP_CREAR_PRESUPUESTO]=false;
		$privileges[User::OP_VER_PRESUPUESTO]=false;
		$privileges[User::OP_CREAR_PROVEEDOR]=false;
		$privileges[User::OP_CREAR_PROYECTO]=false;

    $auth=Yii::app()->authManager;

    $operations = $auth->getOperations(Yii::app()->user->id);

    foreach ($operations as $item) {

    	if($privileges[User::OP_ELIMINAR_PARTIDA]==false)
	      $privileges[User::OP_ELIMINAR_PARTIDA]=$item->name==User::OP_ELIMINAR_PARTIDA?true:false;

    	if($privileges[User::OP_GUARDAR_PRESUPUESTO]==false)
				$privileges[User::OP_GUARDAR_PRESUPUESTO]=$item->name==User::OP_GUARDAR_PRESUPUESTO?true:false;

    	if($privileges[User::OP_EDITAR_TOPE]==false)
				$privileges[User::OP_EDITAR_TOPE]=$item->name==User::OP_EDITAR_TOPE?true:false;

    	if($privileges[User::OP_CREAR_ORDEN]==false)
				$privileges[User::OP_CREAR_ORDEN]=$item->name==User::OP_CREAR_ORDEN?true:false;

    	if($privileges[User::OP_RECIBIR_ORDEN_PARCIAL]==false)
				$privileges[User::OP_RECIBIR_ORDEN_PARCIAL]=$item->name==User::OP_RECIBIR_ORDEN_PARCIAL?true:false;

    	if($privileges[User::OP_AUTORIZAR_ORDEN]==false)
				$privileges[User::OP_AUTORIZAR_ORDEN]=$item->name==User::OP_AUTORIZAR_ORDEN?true:false;

    	if($privileges[User::OP_CREAR_PARTIDA]==false)
	      $privileges[User::OP_CREAR_PARTIDA]=$item->name==User::OP_CREAR_PARTIDA?true:false;

    	if($privileges[User::OP_CREAR_SUBPARTIDA]==false)
	      $privileges[User::OP_CREAR_SUBPARTIDA]=$item->name==User::OP_CREAR_SUBPARTIDA?true:false;

    	if($privileges[User::OP_CREAR_PRESUPUESTO]==false)
	      $privileges[User::OP_CREAR_PRESUPUESTO]=$item->name==User::OP_CREAR_PRESUPUESTO?true:false;

    	if($privileges[User::OP_VER_PRESUPUESTO]==false)
	      $privileges[User::OP_VER_PRESUPUESTO]=$item->name==User::OP_VER_PRESUPUESTO?true:false;

    	if($privileges[User::OP_CREAR_PROVEEDOR]==false)
	      $privileges[User::OP_CREAR_PROVEEDOR]=$item->name==User::OP_CREAR_PROVEEDOR?true:false;

    	if($privileges[User::OP_CREAR_PROYECTO]==false)
	      $privileges[User::OP_CREAR_PROYECTO]=$item->name==User::OP_CREAR_PROYECTO?true:false;

    }

		return $privileges;

  }

  /**
  *
  *@param $roleName: rolename for check against user's roles
  */
  public static function checkRole($roleName){
  	$roles=Yii::app()->authManager->getRoles(Yii::app()->user->id);
    $roleFound=false;

  	if($roles){
      foreach ($roles as $role) {
      	if($role->name==$roleName){
          $roleFound=true;
      		break;
      	}
      }

  	}

  	return $roleFound;
  }
 
}


