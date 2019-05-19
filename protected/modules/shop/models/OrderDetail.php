<?php

/**
 * This is the model class for table "cart_orderdetail".
 *
 * The followings are the available columns in table 'cart_orderdetail':
 * @property integer $id
 * @property integer $orderid
 * @property integer $itemid
 * @property string $name
 * @property string $description
 * @property string $sku
 * @property integer $qty
 * @property string $price
 * @property string $total
 * @property string $size
 * @property string $discount
 *
 * The followings are the available model relations:
 * @property CartItem $item
 * @property CartOrder $order
 */
class OrderDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cart_orderdetail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('itemid', 'required'),
			array('orderid, itemid, qty', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('sku, price, total, discount', 'length', 'max'=>10),
			array('size', 'length', 'max'=>5),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, orderid, itemid, name, description, sku, qty, price, total, size, discount', 'safe', 'on'=>'search'),
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
			'item' => array(self::BELONGS_TO, 'CartItem', 'itemid'),
			'order' => array(self::BELONGS_TO, 'CartOrder', 'orderid'),
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
			'itemid' => 'Itemid',
			'name' => 'Name',
			'description' => 'Description',
			'sku' => 'Sku',
			'qty' => 'Qty',
			'price' => 'Price',
			'total' => 'Total',
			'size' => 'Size',
			'discount' => 'Discount',
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
		$criteria->compare('itemid',$this->itemid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('sku',$this->sku,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('total',$this->total,true);
		$criteria->compare('size',$this->size,true);
		$criteria->compare('discount',$this->discount,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Orderdetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
