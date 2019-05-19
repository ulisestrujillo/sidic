<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property integer $id
 * @property integer $budgetitemid
 * @property integer $order_id
 * @property integer $budgetid
 * @property integer $supplierid
 * @property string $total
 * @property integer $status
 * @property string $created
 * @property string $update
 * @property integer $initdate
 * @property integer $supplydayleft
 * @property string $ordertype
 * @property string $address
 * @property string $deliverto
 * @property string $phone
 * @property string $comment
 * @property string $invoiceid
 * @property decimal $subtotal
 * @property decimal $tax
 * @property datetime $paiddate
 *
 * The followings are the available model relations:
 * @property Budget $budget
 * @property Budgetitem $budgetitem
 * @property Supplier $supplier
 * @property Orderdetail[] $orderdetails
 */
class Order extends CActiveRecord
{

	const STATUS_COLOCADA = 1;
	const STATUS_AUTORIZADA = 2;
	const STATUS_CANCELADA = 3;
	const STATUS_SURTIDA = 4;
	const STATUS_PARCIAL = 5;
	const STATUS_PORPAGAR = 6;
	const STATUS_PAGADA = 7;

	const COLOCADA="COLOCADA";
	const AUTORIZADA="AUTORIZADA";
	const CANCELADA="CANCELADA";
	const SURTIDA="SURTIDA";
	const PARCIAL="PARCIAL";
	const PORPAGAR="PORPAGAR";
	const PAGADA="PAGADA";

	const TYPE_INSUMO="INSUMO";
	const TYPE_MANO_DE_OBRA="MANO DE OBRA";
	const TYPE_INSUMO_MANO_DE_OBRA="INSUMOS Y MANO DE OBRA";

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('budgetitemid, budgetid, supplierid, statusid', 'required'),
			array('address', 'required', 'message' => 'Capture el domicilio.'),
			array('initdate', 'required', 'message' => 'Capture la fecha de inicio.'),
			array('deliverto', 'required', 'message' => 'Capture quién recibe.'),
			array('phone', 'required', 'message' => 'Capture el teléfono.'),

			array('budgetitemid, budgetid, supplierid, statusid', 'numerical', 'integerOnly'=>true),
			array('invoiceid', 'length', 'max'=>30, 'message'=>'Solo se permiten hasta 30 carácteres en el folio de la factura.'),
			array('total', 'length', 'max'=>10, 'message'=>'Solo se permiten cifras de hasta diez digitos.'),
			array('address', 'length', 'max'=>500, 'message'=>'Capture hasta 500 carácteres.'),
			array('deliverto', 'length', 'max'=>50, 'message'=>'Capture hasta 50 carácteres'),
			array('phone', 'length', 'max'=>20, 'message'=>'Capture hasta 20 digitos'),
			array('comment', 'length', 'max'=>100, 'message'=>'Capture hasta 100 carácteres.'),
			array('created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, budgetitemid, budgetid, supplierid, total, statusid, created, update', 'safe', 'on'=>'search'),
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
			'budgetitem' => array(self::BELONGS_TO, 'BudgetItem', 'budgetitemid'),
			'supplier' => array(self::BELONGS_TO, 'Supplier', 'supplierid'),
			'orderstatus' => array(self::BELONGS_TO, 'OrderStatus', 'statusid'),
			'orderdetail' => array(self::HAS_MANY, 'OrderDetail', 'orderid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'budgetitemid' => 'Budgetitemid',
			'budgetid' => 'Budgetid',
			'supplierid' => 'Supplierid',
			'total' => 'Total',
			'statusid' => 'Status',
			'created' => 'Created',
			'update' => 'Update',
			'phone' => 'Teléfono',
			'deliverto' => 'Quién recibe',
			'comment' => 'Observaciones',
			'address' => 'Domicilio',
			'initdate' => 'Fecha',
			'invoiceid' => 'Factura',
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
		$criteria->compare('budgetitemid',$this->budgetitemid);
		$criteria->compare('budgetid',$this->budgetid);
		$criteria->compare('supplierid',$this->supplierid);
		$criteria->compare('total',$this->total,true);
		$criteria->compare('statusid',$this->status);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('update',$this->update,true);

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

  /**
  *Returns the sumattory of price for this orderdetail
  */
  public function getSummatoryPrice(){
    $total=0;
    foreach ($this->orderdetail as $det) {
      $total+=$det->price;
    }

    return $total;
  }

  /**
  *Returns the sumattory of total for this orderdetail
  */
  public function getSummatoryTotal(){
    $total=0;
    foreach ($this->orderdetail as $det) {
      $total+=$det->total;
    }

    return $total;
  }

  /**
  *Returns the sumattory of tax for this orderdetail
  */
  public function getSummatoryTax(){
    $total=0;
    foreach ($this->orderdetail as $det) {
      $total+=$det->tax;
    }

    return $total;
  }

  /**
  *Returns the sumattory of total + tax for this orderdetail
  */
  public function getSummatoryTotalPlusTax(){
    $total=0;
    foreach ($this->orderdetail as $det) {
      $total+=($det->total + $det->tax);
    }

    return $total;
  }

  public static function getAmountByBudgetItemId($budgetItemId, $orderId, $statusId){
    if($statusId==Order::STATUS_AUTORIZADA){
	    $query = 
			"SELECT COALESCE(sum(total),0) AS total
			FROM (
			  SELECT total FROM `order` WHERE budgetitemid = $budgetItemId AND statusid = ".Order::STATUS_AUTORIZADA."
			  UNION
			  SELECT total FROM `order` WHERE id = $orderId
			) as t";
    }else{
	    $query = 
	    "SELECT COALESCE(SUM(total),0) AS total FROM `order` 
	    WHERE budgetitemid = $budgetItemId AND statusid = ".Order::STATUS_AUTORIZADA;
    }
    
    $res = Yii::app()->db->createCommand($query)->queryColumn();
    return $res[0];
  }

  public static function getTotalByBudgetItemId($budgetItemId){
    $query = 
    "SELECT COALESCE(SUM(total),0) AS total FROM `order` 
    WHERE budgetitemid = $budgetItemId AND statusid = ".Order::STATUS_AUTORIZADA;
    
    $res = Yii::app()->db->createCommand($query)->queryColumn();
    return $res[0];
  }

  public static function getOrderStatusToString($statusId){
		$status="";

		if($statusId==Order::STATUS_AUTORIZADA){
		  $status="AUTORIZADA";
		}else
		if($statusId==Order::STATUS_COLOCADA){
		  $status="COLOCADA";
		}else
		if($statusId==Order::STATUS_CANCELADA){
		  $status="CANCELADA";
		}else
		if($statusId==Order::STATUS_SURTIDA){
		  $status="SURTIDA";
		}else
		if($statusId==Order::STATUS_PARCIAL){
		  $status="PARCIAL";
		}else
		if($statusId==Order::STATUS_PORPAGAR){
		  $status="PORPAGAR";
		}else
		if($statusId==Order::STATUS_PAGADA){
		  $status="PAGADA";
		}

		return $status;

  }

	public static function genId($budgetId){
		$q="SELECT COALESCE(MAX(order_id), 0) + 1 FROM `order` WHERE budgetid = '$budgetId'";
		$r=Yii::app()->db->createCommand($q)->queryScalar();
		
		return $r;
	}

}









