<?php

/**
 * This is the model class for table "orderdetail".
 *
 * The followings are the available columns in table 'orderdetail':
 * @property integer $id
 * @property integer $orderid
 * @property integer $qty
 * @property integer $unit
 * @property string $description
 * @property decimal $price
 * @property decimal $unitprice
 * @property cecimal $total
 * @property decimal $tax
 * @property integer $zerotax
 *
 * The followings are the available model relations:
 * @property Order $order
 */
class OrderDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orderdetail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('orderid, unit, description, price, qty', 'required'),
			array('id, orderid', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>100),
			array('price, total, unit', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, orderid, qty, unit, description, price, total', 'safe', 'on'=>'search'),
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
			'order' => array(self::BELONGS_TO, 'Order', 'orderid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'orderid' => 'Orderid',
			'qty' => 'Qty',
			'unit' => 'Unit',
			'description' => 'Description',
			'price' => 'Price',
			'total' => 'Total',
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
		$criteria->compare('orderid',$this->orderid);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('unit',$this->unit);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('total',$this->total,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
