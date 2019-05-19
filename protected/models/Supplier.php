<?php

/**
 * This is the model class for table "supplier".
 *
 * The followings are the available columns in table 'supplier':
 * @property integer $supplier_id
 * @property string $code
 * @property string $name
 * @property string $rfc
 * @property string $address
 * @property string $patronal_record
 * @property string $agent
 * @property string $phone
 * @property string $email
 * @property string $bank
 * @property string $account
 * @property string $clabe
 * @property string $created
 * @property string $updated
 * @property string $active
 */
class Supplier extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'supplier';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, rfc, address, code, phone, email', 'required'),
			array('code, rfc, patronal_record, email', 'length', 'max'=>50),
			array('phone', 'length', 'max'=>30),
			array('email', 'email'),
			array('name, address, agent', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('supplier_id, code, name, rfc, address, patronal_record, agent', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'supplier_id' => 'ID',
			'code' => 'Clave',
			'name' => 'Nombre Comercial',
			'rfc' => 'RFC',
			'address' => 'Domicilio',
			'patronal_record' => 'Registro Patronal',
			'agent' => 'Representante',
			'phone' => 'Teléfono',
			'email' => 'Correo',
			'bank' => 'Banco',
			'account' => 'Cuenta',
			'clabe' => 'CLABE',
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

		$criteria->compare('supplier_id',$this->supplier_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('rfc',$this->rfc,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('patronal_record',$this->patronal_record,true);
		$criteria->compare('agent',$this->agent,true);

        $licensekey=Yii::app()->user->getState('licensekey');
        $criteria->addCondition("licensekey='".$licensekey."'");

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['supplierPerPage'],
			),
			'criteria'=>$criteria,
		));
	}

	public static function genId(){
		$licensekey=Yii::app()->user->getState('licensekey');
		$q="SELECT COALESCE(MAX(supplier_id), 0) + 1 FROM supplier WHERE licensekey = '$licensekey'";
		$r=Yii::app()->db->createCommand($q)->queryScalar();
		
		return $r;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Supplier the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
