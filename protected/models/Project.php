<?php

/**
 * This is the model class for table "project".
 *
 * The followings are the available columns in table 'project':
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $address
 * @property string $location
 */
class Project extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, name, address, location', 'required'),
			array('code, location', 'length', 'max'=>50),
			array('name, address', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('code, name, address, location', 'safe', 'on'=>'search'),
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
			'budgets' => array(self::HAS_MANY, 'Budget', 'projectid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Código',
			'name' => 'Nombre',
			'address' => 'Dirección',
			'location' => 'Plaza',
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
    /*aqui se hace la consulta tomando en cuenta la relacion que existe entre el usuario y los proyectos y presupuestos*/
    /*ya que algunos usuarios no tienen acceso a todos los proyectos y presupuestos*/

		$criteria=new CDbCriteria;
		$query="
		SELECT rel.id,rel.projectid, rel.budgetid, rel.userid
		,((SELECT COUNT(*) FROM budget WHERE projectid = rel.projectid) = 
		(SELECT COUNT(*) FROM budgetuser_rel WHERE userid = rel.userid AND projectid = rel.projectid )) AS eq
		FROM budgetuser_rel rel
		WHERE userid=".Yii::app()->user->id;

    $projects = Yii::app()->db->createCommand($query)->queryAll();

    $projectIdList="0";
    foreach ($projects as $pro) {
    	if($pro["eq"]==1){
        $projectIdList.=$pro["projectid"].',';
    	}
    }

    if(strlen($projectIdList)>0 && $projectIdList!='0'){
    	$projectIdList.='0';
    }

    $licensekey=Yii::app()->user->getState('licensekey');

    //Se consultan solos los proyectos que le corresponden al usuario según su licencia
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('location',$this->location,true);
		$criteria->addCondition('id not in('.$projectIdList.')');
		$criteria->addCondition("licensekey='".$licensekey."'");

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['projectPerPage'],
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Project the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
