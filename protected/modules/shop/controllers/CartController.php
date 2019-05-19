<?php

class CartController extends Controller
{

  public $layout = '//layouts/main';

  public function init(){
      if(!isset(Yii::app()->session['cart'])){
          echo 'session expirada';
      }
  }

  public function accessRules()
  {
    return 
      array(
        /*array(
          'allow',  // allow all users to perform 'index' and 'view' actions
          'actions'=>array('index','view','removeajax', 'add', 'remove', 'display', 'executePayment'),
          'users'=>array('*'),
        ),*/
        array(
          'allow', // deny all users
          'users' => array('*')
      )
    );
  }

	private $itemList=array();

	public function actionAdd($id)
	{
    //unset(Yii::app()->session['cart']); return; //to remove session key

        /*
TODO: validar in stock
        */
    $session=Yii::app()->session;

		if($session['cart']==null)
		{
		  echo 'save order by session expire';
          //throw new CException('session expire');

		  $session['cart']=array();
		}

		$cartBo = new CartBO;
    $res=$cartBo->getItem($id);

    $itemList=$session['cart'];
    //echo var_dump($itemList); return;
    $itemList = $this->updateTotal($res, $itemList);//update qty if already exists

    $session['cart']=$itemList;

    //echo var_dump($session['cart']); return;

        //return isset($res);//boolean
	}

  public function actionAddItemTest()
  {
    $this->actionAdd(11);
    $this->actionAdd(12);
    $this->actionAdd(13);
    $this->actionAdd(15);
    $this->actionAdd(16);
    $this->actionAdd(17);
  }

	private function updateTotal($item, $itemList)
	{
	  $existe=false;

    for ($pos=0; $pos < sizeof($itemList); $pos++) {
      if($item->id==$itemList[$pos]->id){
        $itemList[$pos]->qty = $itemList[$pos]->qty + 1;
        $itemList[$pos]->total = $itemList[$pos]->qty*$itemList[$pos]->price;
        $existe=true;
      }
    }

    if(!$existe)
    {
      array_push($itemList, $item);
    }

    return $itemList;
	}

/*
*@pos es la posicion del item en el array de la session,
viene en el post de la request
*/
	public function actionRemove($pos)
	{

    try
    {
      $newArr=array();
      $session=Yii::app()->session;
      $arr=$session['cart'];

      for ($key=0; $key < sizeof($arr); $key++) {
        if($key!=$pos)
        {
          array_push($newArr, $arr[$key]);
        }
      }

      $session['cart']=$newArr;
      //echo 'ok';
      //return 'OK';
    }
    catch(CException $e)
    {
      //echo 'err';
      //return 'ERR';
    }
    //throw new CException(Yii::t('yii','CHttpSession.cookieMode can only be "none", "allow" or "only".'));
	}

  /**
  *Despliega el carrito de la compra
  *@param nu: es nuevo usuario
  */
	public function actionDisplay($nu=false)
	{
    $this->pageTitle="SIDIC - Carrito";
 	  $session=Yii::app()->session;
    $fiscalData;
    if(!Yii::app()->user->isGuest){
      $fiscalData=Fiscal::model()->findAll('userid='.Yii::app()->user->id)[0];
      $fiscalData=$fiscalData!=null?$fiscalData:new Fiscal;
    }else{
      $fiscalData=new Fiscal;
    }

    $cartBO = new CartBO;

	  $this->render('display',
                   array(
                      'cart'=>$session['cart'],
                      'total'=>$cartBO->getTotalToPay(),
                      'isNewUser'=>$nu,
                      'fiscalData'=>$fiscalData
                    ));
	}

  public function actionExecutePayment(){
    include("sample/payments/test.php");
  }

	public function actionSave(){
	  return null;
	}

  public function inStock($item)
  {
    $cartBo = new CartBO();
    $item = new Item();
    $item->id=1;

    return $cartBo->inStock($item, Null);
  }

  public function actionRemoveAjax($pos)
  {
    $this->actionRemove($pos);

    $session=Yii::app()->session;
    //$total=$this->totalToPay();//total a pagar para mostrar en el summary
    $total=0.00;

//echo json_encode($session['cart']);
    $cartBO = new CartBO;
    $this->renderPartial(
                    '_display',
                    array(
                      'cart'=>$session['cart'],
                      'total'=>$cartBO->getTotalToPay(),
                    ), false, false);
  }

}
