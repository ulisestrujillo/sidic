<?php

/**
 * This is the model class for table "cart_order".
 *
 * The followings are the available columns in table 'cart_order':
 * @property integer $id
 * @property integer $userid
 * @property string $folio
 * @property string $subtotal
 * @property string $tax
 * @property string $total
 * @property integer $totalitems
 * @property string $comment
 * @property string $created
 *
 * The followings are the available model relations:
 * @property CartOrderdetail[] $cartOrderdetails
 */
class Order extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cart_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid', 'required'),
			array('userid, totalitems', 'numerical', 'integerOnly'=>true),
			array('folio', 'length', 'max'=>20),
			array('subtotal, tax, total', 'length', 'max'=>10),
			array('comment, created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userid, folio, subtotal, tax, total, totalitems, comment, created', 'safe', 'on'=>'search'),
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
			'cartOrderdetails' => array(self::HAS_MANY, 'CartOrderdetail', 'orderid'),
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
			'folio' => 'Folio',
			'subtotal' => 'Subtotal',
			'tax' => 'Tax',
			'total' => 'Total',
			'totalitems' => 'Totalitems',
			'comment' => 'Comment',
			'created' => 'Created',
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
		$criteria->compare('folio',$this->folio,true);
		$criteria->compare('subtotal',$this->subtotal,true);
		$criteria->compare('tax',$this->tax,true);
		$criteria->compare('total',$this->total,true);
		$criteria->compare('totalitems',$this->totalitems);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
