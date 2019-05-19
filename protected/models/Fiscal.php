<?php

/**
 * This is the model class for table "fiscal".
 *
 * The followings are the available columns in table 'fiscal':
 * @property integer $id
 * @property integer $userid
 * @property string $name
 * @property string $lastname
 * @property string $address
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $rfc
 * @property string $phone_number
 * @property string $email
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Fiscal extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fiscal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid, name, address, country, state, city, rfc, phone_number, email', 'required'),
			array('name, country, state, city, phone_number', 'length', 'max'=>50),
			array('lastname, email', 'length', 'max'=>128),
			array('email', 'email'),
			array('address', 'length', 'max'=>255),
			array('rfc', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('name, address, country, state, city, rfc, phone_number, email', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'lastname' => 'Lastname',
			'address' => 'Address',
			'country' => 'Country',
			'state' => 'State',
			'city' => 'City',
			'rfc' => 'Rfc',
			'phone_number' => 'Phone Number',
			'email' => 'Email',
			'created' => 'Created',
			'updated' => 'Updated',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('rfc',$this->rfc,true);
		$criteria->compare('phone_number',$this->phone_number,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Fiscal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
