<?php

/**
 * This is the model class for table "budgetitem".
 *
 * The followings are the available columns in table 'budgetitem':
 * @property integer $id
 * @property integer $parentid
 * @property integer $budgetid
 * @property string $name
 * @property string $budgettop
 *
 * The followings are the available model relations:
 * @property Budget $budget
 * @property Order[] $orders
 */
class BudgetItem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'budgetitem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parentid, budgetid, name, budgettop', 'required'),
			array('parentid, budgetid', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('budgettop', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parentid, budgetid, name, budgettop', 'safe', 'on'=>'search'),
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
			'budget' => array(self::BELONGS_TO, 'Budget', 'budgetid'),
			'orders' => array(self::HAS_MANY, 'Order', 'budgetitemid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parentid' => 'parentid',
			'budgetid' => 'Budgetid',
			'name' => 'Name',
			'budgettop' => 'Budgettop',
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
		$criteria->compare('parentid',$this->parentid);
		$criteria->compare('budgetid',$this->budgetid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('budgettop',$this->budgettop,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Budgetitem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
