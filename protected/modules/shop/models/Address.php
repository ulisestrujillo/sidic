<?php

/**
 * This is the model class for table "user_address".
 *
 * The followings are the available columns in table 'user_address':
 * @property integer $id
 * @property integer $userid
 * @property string $calle
 * @property string $numerointerior
 * @property string $numeroexterior
 * @property string $cpostal
 * @property string $estado
 * @property string $ciudad
 * @property string $colonia
 * @property string $referencias
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Address extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('calle, numerointerior, cpostal, estado, ciudad, colonia', 'required'),
			array('userid', 'numerical', 'integerOnly'=>true),
			array('numerointerior, numeroexterior', 'length', 'max'=>5),
			array('cpostal', 'length', 'max'=>10),
			array('estado, ciudad, colonia', 'length', 'max'=>128),
			array('referencias', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userid, calle, numerointerior, numeroexterior, cpostal, estado, ciudad, colonia, referencias', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'userid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userid' => 'Userid',
			'calle' => 'Calle',
			'numerointerior' => 'Numerointerior',
			'numeroexterior' => 'Numeroexterior',
			'cpostal' => 'Cpostal',
			'estado' => 'Estado',
			'ciudad' => 'Ciudad',
			'colonia' => 'Colonia',
			'referencias' => 'Referencias',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('calle',$this->calle,true);
		$criteria->compare('numerointerior',$this->numerointerior,true);
		$criteria->compare('numeroexterior',$this->numeroexterior,true);
		$criteria->compare('cpostal',$this->cpostal,true);
		$criteria->compare('estado',$this->estado,true);
		$criteria->compare('ciudad',$this->ciudad,true);
		$criteria->compare('colonia',$this->colonia,true);
		$criteria->compare('referencias',$this->referencias,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Address the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
