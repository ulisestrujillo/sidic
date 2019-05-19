<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $price
 * @property string $image
 * @property string $tags
 * @property integer $new
 * @property string $type
 * @property integer $brand_id
 * @property integer $category_id
 * @property integer $subcategory_id
 * @property integer $active
 * @property string $video
 * @property string $color
 * @property integer $in_stock
 * @property integer $showprice
 * @property string $model
 * @property integer $year
 *
 * The followings are the available model relations:
 * @property CartOrderdetail[] $cartOrderdetails
 */
class Product extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, name, description', 'required'),
			array('new, brand_id, category_id, subcategory_id, active, in_stock, showprice, year', 'numerical', 'integerOnly'=>true),
			array('code, model', 'length', 'max'=>20),
			array('name, image', 'length', 'max'=>50),
			array('price', 'length', 'max'=>10),
			array('type', 'length', 'max'=>120),
			array('video', 'length', 'max'=>255),
			array('color', 'length', 'max'=>7),
			array('tags', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, name, description, price, image, tags, new, type, brand_id, category_id, subcategory_id, active, video, color, in_stock, showprice, model, year', 'safe', 'on'=>'search'),
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
			'cartOrderdetails' => array(self::HAS_MANY, 'CartOrderdetail', 'itemid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'name' => 'Name',
			'description' => 'Description',
			'price' => 'Price',
			'image' => 'Image',
			'tags' => 'Tags',
			'new' => 'New',
			'type' => 'Type',
			'brand_id' => 'Brand',
			'category_id' => 'Category',
			'subcategory_id' => 'Subcategory',
			'active' => 'Active',
			'video' => 'Video',
			'color' => 'Color',
			'in_stock' => 'In Stock',
			'showprice' => 'Showprice',
			'model' => 'Model',
			'year' => 'Year',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('new',$this->new);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('subcategory_id',$this->subcategory_id);
		$criteria->compare('active',$this->active);
		$criteria->compare('video',$this->video,true);
		$criteria->compare('color',$this->color,true);
		$criteria->compare('in_stock',$this->in_stock);
		$criteria->compare('showprice',$this->showprice);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('year',$this->year);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
