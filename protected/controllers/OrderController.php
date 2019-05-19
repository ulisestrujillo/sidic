<?php

class OrderController extends Controller {

  public $layout = '//layouts/admin';

  /**
   * @return array action filters
   */
  public function filters() {
    return array(
      'accessControl',
      'ajaxOnly + ocToEmail + contractToEmail + recibir + autorizar',
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules() {
    return array(
      array('allow',  // allow all users to perform 'index' and 'view' actions
        'actions'=>array('index', 'error'),
        'users'=>array('*'),
      ),
      array('allow',  // allow all users to perform 'index' and 'view' actions
        'actions'=>array('create', 'index', 'error', 'view', 'budget', 'update',
                         'PrintOc','listOrdersByBudgetId','getOrders',
                         'ReporteGeneral', 'ReporteGeneralData', 
                         'ReporteOcPorAutorizar', 'ReporteOcPorAutorizarData','OcAutorizadas', 'OcAutorizadasData',
                         'ReporteOcRecibidas', 'ReporteOcRecibidasData',
                         'ReporteOcPorPagar', 'ReporteOcPorPagarData',
                         'ReporteOcPagadas', 'ReporteOcPagadasData', 'ReporteGeneralPdf',
                         'ReporteOcPorAutorizarPdf','ReporteOcAutorizadasPdf',
                         'ReporteOcRecibidasPdf','ReporteOcPorPagarPdf','ReporteOcPagadasPdf'),
        'roles'=>array('elaborador', 'contralor', 'receptor'),
      ),
      array('allow',  // allow all users to perform 'index' and 'view' actions
        'actions'=>array('recibir'),
        'roles'=>array('receptor'),
      ),
      array('allow',  // allow all users to perform 'index' and 'view' actions
        'actions'=>array('index', 'error', 'budget', 'update', 'autorizar',
                         'listOrdersByBudgetId', 'getOrders', 'ReporteGeneral', 'ReporteGeneralData', 
                         'ReporteOcPorAutorizar', 'ReporteOcPorAutorizarData','OcAutorizadas', 'OcAutorizadasData',
                         'ReporteOcRecibidas', 'ReporteOcRecibidasData',
                         'ReporteOcPorPagar', 'ReporteOcPorPagarData',
                         'ReporteOcPagadas', 'ReporteOcPagadasData', 'ReporteGeneralPdf',
                         'ReporteOcPorAutorizarPdf','ReporteOcAutorizadasPdf',
                         'ReporteOcRecibidasPdf','ReporteOcPorPagarPdf','ReporteOcPagadasPdf'),
        'roles'=>array('autorizador'),
      ),
      array('allow', // allow authenticated user to perform 'create' and 'update' actions
        'actions'=>array('admin', 'budget', 'create', 'update', 'PrintOc', 'printContract', 
                         'index', 'error', 'getOc', 'ocToEmail', 'contractToEmail', 'autorizar',
                         'listOrdersByBudgetId', 'getOrders', 'recibir',
                         'ReporteGeneral', 'ReporteGeneralData', 
                         'ReporteOcPorAutorizar', 'ReporteOcPorAutorizarData','OcAutorizadas', 'OcAutorizadasData',
                         'ReporteOcRecibidas', 'ReporteOcRecibidasData',
                         'ReporteOcPorPagar', 'ReporteOcPorPagarData',
                         'ReporteOcPagadas', 'ReporteOcPagadasData', 'ReporteGeneralPdf',
                         'ReporteOcPorAutorizarPdf','ReporteOcAutorizadasPdf',
                         'ReporteOcRecibidasPdf','ReporteOcPorPagarPdf','ReporteOcPagadasPdf'),
        'roles'=>array('admin'),
      ),
      array('allow', // allow authenticated user to perform 'create' and 'update' actions
        'actions'=>array('getOc', 'GetOcText'),
        'users'=>array('*'),
      ),

      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }

  public function actionError() {
    //https://www.yiiframework.com/doc/guide/1.1/en/topics.error
    $this->layout="//layouts/admin";
    if ($error = Yii::app()->errorHandler->error) {
      if (Yii::app()->request->isAjaxRequest)
        echo $error['message'];
      else
        $this->render('error', $error);
    }
  }

  public function actionIndex() {
    $this->render("index");
  }

  /**
   *Muestra una relacion de ordenes de comra por partida - subpartida
   *@id es el id de la partida subpartida
   */
  public function actionBudget($id) {
    $model = BudgetItem::model()->findByPk($id);

    $criteria=new CDbCriteria;
    $criteria->addCondition("statusid=".Order::STATUS_AUTORIZADA, 'OR');
    $criteria->addCondition("statusid=".Order::STATUS_SURTIDA, 'OR');
    $criteria->addCondition("statusid=".Order::STATUS_PARCIAL, 'OR');
    $criteria->addCondition("statusid=".Order::STATUS_PORPAGAR, 'OR');
    $criteria->addCondition("statusid=".Order::STATUS_PAGADA, 'OR');
    $criteria->addCondition("budgetitemid=".$id, 'OR');
    $criteria->join="INNER JOIN budget ON budget.id=budgetid
                     INNER JOIN project ON project.id=budget.projectid AND project.licensekey='".Yii::app()->user->getState('licensekey')."'";

    $orders=Order::Model()->findAll($criteria);

    if (sizeof($model) == 0) {
      return $this->render('ordernotfound', array(
        'id' => $id,
      ));
    }

    $this->render('budgetOrder', array(
      'model' => $model,
      'orders' => $orders,
      'budgetid'=>$model->budget->id
    ));
  }

  /**
   *Crear una orden de compra para partida o subpartida
   *@id es el id de la partida o subpartida
   *
   */
  public function actionCreate($id, $projectid, $budgetid) {
    if ($id > 0 && $projectid > 0) {
      $hasRollback = false;
      $order       = new Order;
      $orderdetail = Array();
      $partida     = BudgetItem::model()->findByPk($id);
      $project     = Project::model()->findByPk($projectid);
      $subpartida  = new BudgetItem;

      if ($partida->parentid > 0) {
        $subpartida = $partida;
        $partida    = BudgetItem::model()->findByPk($partida->parentid);
      }

      $suppliers = Supplier::model()->findAll(array(
        "condition" => "active = 1 AND licensekey='".Yii::app()->user->getState('licensekey')."'"
      ));

      $transaction = $order->dbConnection->beginTransaction();

      $importeTasaCero = 0;
      try {
        if (isset($_POST["Order"])) {
          $order->budgetitemid  = $id;
          $order->budgetid      = $_POST["Order"]["budgetid"];
          $order->supplierid    = $_POST["Order"]["supplierid"];
          $order->statusid      = $order::STATUS_COLOCADA;//nace como orden colocada
          $order->initdate      = strtotime($_POST["Order"]["initdate"]);
          $order->supplydayleft = 5;
          $order->ordertype     = $_POST["Order"]["ordertype"];
          $order->address       = strtoupper($_POST["Order"]["address"]);
          $order->deliverto     = strtoupper($_POST["Order"]["deliverto"]);
          $order->phone         = $_POST["Order"]["phone"];
          $order->comment       = strtoupper($_POST["Order"]["comment"]);

          $detail  = null;
          $cont    = 0;
          $importe = 0; //importe de la orden de compra, es igual a la suma de los totales
          if (isset($_POST["OrderDetail"])) {
            foreach ($_POST["OrderDetail"] as $item) {
              $detail              = new OrderDetail;
              $detail->qty         = $item["qty"];
              $detail->unit        = $item["unit"];
              $detail->description = strtoupper($item["description"]);
              $detail->price       = strtoupper($item["price"]);
              $detail->total       = round($detail->qty * $detail->price, 2, PHP_ROUND_HALF_UP); //precision de dos digitos

              if(isset($item["zerotax"])){
                $detail->zerotax = $item["zerotax"];
              }else{
                $detail->zerotax = 0;
              }

              if($detail->zerotax == 1){
                $detail->tax = 0.00;
              }else{
                $detail->tax = round($detail->total * 0.16, 2, PHP_ROUND_HALF_UP);
              }

              if ($detail->zerotax == 0) { //solo agrega impuesto a los que no sean tasa cero
                $importe += $detail->total;
              }

              if ($detail->zerotax == 1) { //Tasa cero
                $importeTasaCero += $detail->total;
              }

              $orderdetail[$cont] = $detail;

              $cont = $cont + 1;
            }
          }

          $hasErrors = false;

          if ($order->validate()) {
            //http://php.net/manual/es/function.round.php
            $order->subtotal = round($importe + $importeTasaCero, 2, PHP_ROUND_HALF_UP);
            $order->tax      = round($importe * 0.16, 2, PHP_ROUND_HALF_UP);
            $order->total    = round($importe * 1.16, 2, PHP_ROUND_HALF_UP);
            $order->total    = round($order->total + $importeTasaCero, 2, PHP_ROUND_HALF_UP);
            $order->colocadapor=Yii::app()->user->name;
            $order->order_id = Order::genId($budgetid);
            $order->save();

            foreach ($orderdetail as $det) {
              $det->orderid = $order->id;

              if ($det->validate()) {
                $det->save();
              } else {
                $hasErrors = true;
              }
            }

            if (sizeof($orderdetail) <= 0) {
              Yii::app()->user->setFlash("missingDetail", "No puede crear una orden sin conceptos o detalles.");
              $transaction->rollback();
            } elseif ($hasErrors == true) {
              Yii::app()->user->setFlash("missingDetail", "Corrija los siguientes errores de captura.");
              $transaction->rollback();
            } else {
              $transaction->commit();
              Yii::app()->user->setFlash("orderSaved", "La orden ha sido guardada exitosamente.");

              $this->redirect(Yii::app()->createUrl('order/update', array(
                'id' => "$id",
                'projectid' => "$projectid",
                'budgetid' => "$budgetid",
                'orderid' => "$order->id"
              )));

            }
          }
        }
      }
      catch (Exception $e) {
        throw new Exception("OrderController - Error Processing Request" . $e, 1);
        $transaction->rollback();
      }

      $this->render('create', array(
        'order' => $order,
        'orderdetail' => $orderdetail,
        'suppliers' => $suppliers,
        'partida' => $partida,
        'subpartida' => $subpartida->name,
        'project' => $project,
        'budgetid' => $budgetid,
        'importeTasaCero' => number_format(round($importeTasaCero, 2, PHP_ROUND_HALF_UP), 2, '.', ',')
      ));
    } else {
      throw new CHttpException(400, 'La página que está solicitando no existe. Regrese a la consulta de presupuestos.');
    }

  }

  /**
   *Actualizacion de la orden de compra, cambio de estatus
   *, cambio de dirección, telefono, etc.
   *@id es el id de la partida o subpartida
   *
   */
  public function actionUpdate($id, $projectid, $budgetid, $orderid) {
    //valida si la orden le pertenece a este usuario
    $query="select count(*)
    from `order` o
    inner join budget b on b.id = o.budgetid
    where 
      o.id = $orderid
      and o.budgetid = $budgetid
      and b.projectid = $projectid";

    $res=Yii::app()->db->createCommand($query)->queryScalar();

    if($res==0)
    {
      $this->render('/site/unauthorized');
    }

    $projectModel=Project::Model()->findByPk($projectid);

    if ($id > 0 && $projectid > 0) {
      $hasRollback = false;
      $order       = Order::model()->findByPk($orderid);

      if(User::checkRole('elaborador') && !User::checkRole('contralor')){
        if (isset($_POST["Order"])) {
          if($_POST["Order"]["statusid"]==Order::STATUS_SURTIDA){
            Yii::app()->user->setFlash("cantCancel", CHtml::encode("Solo un usuario autorizador, puede surtir una orden de compra"));
            $this->redirect(Yii::app()->request->requestUri,
              array(
                'order' => $order,
              )
            );
          }
        }
      }

      if(User::checkRole('contralor')){
        if (isset($_POST["Order"])) {
          if($_POST["Order"]["statusid"]==Order::STATUS_PORPAGAR){
          	$invoiceid='';
          	if(isset($_POST["Order"]["invoiceid"])){
          		$invoiceid=", invoiceid='".$_POST["Order"]["invoiceid"]."' ";
          	}
            Yii::app()->db->createCommand("UPDATE `order` SET statusid=".Order::STATUS_PORPAGAR.$invoiceid." WHERE id=$orderid")->execute();
          }
          elseif($_POST["Order"]["statusid"]==Order::STATUS_PAGADA){
            $methodpayment='';
            $methodpaymentref='';

            if(isset($_POST["Order"]['methodpayment'])){
              $methodpayment=", methodpayment='".$_POST["Order"]['methodpayment']."' ";
            }
            if(isset($_POST["Order"]['methodpaymentref'])){
              $methodpaymentref=", methodpaymentref='".$_POST["Order"]['methodpaymentref']."' ";
            }
            $query="UPDATE `order` SET statusid=".Order::STATUS_PAGADA.", invoiceid='".$_POST["Order"]["invoiceid"]."'".$methodpayment.$methodpaymentref.", paiddate=now() WHERE id=$orderid";
            Yii::app()->db->createCommand($query)->execute();
          }
          else{
            Yii::app()->user->setFlash("cantCancel", CHtml::encode("Para el contralor solamente están permitidos estos estatus: POR PAGAR, PAGADA."));
          }

          $this->redirect(Yii::app()->request->requestUri,
            array(
              'order' => $order,
            )
          );
        }
      }

      $orderdetail = Array();
      $partida     = BudgetItem::model()->findByPk($id);
      $project     = Project::model()->findByPk($projectid);
      $subpartida  = new BudgetItem;

      $tope=0;

      if ($partida->parentid > 0) {
        $subpartida = $partida;
        $partida    = BudgetItem::model()->findByPk($partida->parentid);
        $tope = str_replace(",", "", $subpartida->budgettop);
      }else{
        $tope = str_replace(",", "", $partida->budgettop);
      }

      $suppliers = Supplier::model()->findAll(array(
        "condition" => "active = 1 AND licensekey='".Yii::app()->user->getState('licensekey')."'"        
      ));

      if ($order->statusid == Order::STATUS_CANCELADA) {
        Yii::app()->user->setFlash("orderPreviousCanceled", "Esta órden tiene estatus CANCELADA y no podrá ser modificada nuevamente.");
      }

      $transaction = $order->dbConnection->beginTransaction();

      try {
        $res             = -1;
        $importe         = 0; //importe de la orden de compra es igual a la suma de los totales
        $importeTasaCero = 0; //importe de articulos con tasa cero

        $continuar = true;
        if (isset($_POST["Order"])) {
          if (isset($_POST["Order"]["invoiceid"])) {
            $order->invoiceid = trim($_POST["Order"]["invoiceid"]);
          }

          $detail = null;
          $cont   = 0;

          if (isset($_POST["OrderDetail"])) {
            foreach ($_POST["OrderDetail"] as $item) {
              $detail              = new OrderDetail;
              $detail->orderid     = $orderid;
              $detail->qty         = $item["qty"];
              $detail->unit        = strtoupper($item["unit"]);
              $detail->description = strtoupper($item["description"]);
              $detail->price       = strtoupper($item["price"]);
              $detail->total       = round($detail->qty * $detail->price, 2, PHP_ROUND_HALF_UP); //precision de dos digitos

              if(isset($item["zerotax"])){
                $detail->zerotax = $item["zerotax"];
              }else{
                $detail->zerotax = 0;
              }

              if($detail->zerotax == 1){
                $detail->tax = 0.00;
              }else{
                $detail->tax = round($detail->total * 0.16, 2, PHP_ROUND_HALF_UP);
              }

              if ($detail->zerotax == 0) {
                $importe += $detail->total;
              }

              if ($detail->zerotax == 1) { //tasa cero
                $importeTasaCero += $detail->total;
              }

              $orderdetail[$cont] = $detail;

              $cont = $cont + 1;
            }

            $res = Yii::app()->db->createCommand("DELETE FROM `orderdetail` WHERE orderid = $orderid")->execute();
          } else {
            Yii::app()->user->setFlash("missingDetail", "No puede crear una orden sin conceptos o detalles.");
          }
        } else {
          $orderdetail = $order->orderdetail;

          foreach ($orderdetail as $det) {
            if ($det->zerotax == 1) {
              $importeTasaCero += $det->total;
            }
          }
        }

        $hasRollback = !$continuar;

        if ($continuar == true &&  $res > -1) {
          $order->budgetitemid  = $id;
          $order->budgetid      = $_POST["Order"]["budgetid"];
          $order->supplierid    = $_POST["Order"]["supplierid"];
          $order->statusid      = $_POST["Order"]["statusid"];

          if($order->statusid==Order::STATUS_AUTORIZADA){
            $order->autorizadapor=Yii::app()->user->name;
          }

          $order->initdate      = strtotime($_POST["Order"]["initdate"]);
          $order->supplydayleft = 5;
          $order->ordertype     = $_POST["Order"]["ordertype"];
          $order->address       = strtoupper($_POST["Order"]["address"]);
          $order->deliverto     = strtoupper($_POST["Order"]["deliverto"]);
          $order->phone         = $_POST["Order"]["phone"];
          $order->comment       = strtoupper($_POST["Order"]["comment"]);
          $order->methodpayment    = strtoupper($_POST["Order"]["methodpayment"]);
          $order->methodpaymentref = strtoupper($_POST["Order"]["methodpaymentref"]);

          if ($order->validate()) {
            $order->subtotal = round($importe + $importeTasaCero, 2, PHP_ROUND_HALF_UP);
            $order->tax      = round($importe * 0.16, 2, PHP_ROUND_HALF_UP);
            $order->total    = round($importe * 1.16, 2, PHP_ROUND_HALF_UP);
            $order->total    = round($order->total + $importeTasaCero, 2, PHP_ROUND_HALF_UP);

            if($order->methodpayment!=''){
              $order->paiddate=date("Y-m-d");
            }

            if($order->subtotal == 0){
              $order->subtotal = $importeTasaCero;
            }

            $amount=str_replace(",", "", $order->total);

            if($amount>$tope && $tope > 0){
              $hasRollback = true;
              Yii::app()->user->setFlash("topOverpass", "Esta partida tiene un tope de $".number_format(round($tope, 2, PHP_ROUND_HALF_UP), 2, '.', ',')." el cual se está intentando rebasar.");
            }else{
              $order->save();
              foreach ($orderdetail as $det) {
                if ($det->validate()) {
                  $det->save();
                } else {
                  $hasRollback = true;
                }
              }
            }
          } else {
            $hasRollback = true;
            //echo var_dump($order->getErrors()); return;
          }

          if ($hasRollback) {
            $transaction->rollback();
          } else {
            $transaction->commit();

            Yii::app()->user->setFlash("orderSaved", "La orden ha sido guardada exitosamente.");
            $this->redirect(Yii::app()->createUrl('order/update', array(
              'id' => "$id",
              'projectid' => "$projectid",
              'budgetid' => "$budgetid",
              'orderid' => "$orderid"
            )));
          }

        }
      }
      catch (Exception $e) {
        throw new Exception("OrderController - Error Processing Request" . $e, 1);
        $transaction->rollback();
      }

      $this->render('update', array(
        'order' => $order,
        'orderdetail' => $orderdetail,
        'suppliers' => $suppliers,
        'partida' => $partida,
        'subpartida' => $subpartida->name,
        'project' => $project,
        'budgetid' => $budgetid,
        'importeTasaCero' => number_format(round($importeTasaCero, 2, PHP_ROUND_HALF_UP), 2, '.', ',')
      ));

    } else {
      throw new CHttpException(400, 'La página que está solicitando no existe. Regrese a la consulta de presupuestos.');
    }
  }

  /**
  *Imprimir la orden de compra
  *@orderid: id de la orden de compra
  */
  public function actionGetOcText(){
    $this->layout="//layouts/print";

    if(!isset($_POST["orderid"])){
      throw new CHttpException(400, 'La página que está solicitando no existe. Regrese a la consulta de presupuestos.');
    }

    $orderid=$_POST["orderid"];
    $userid=$_POST["userid"];
    $logo=$_POST["logo"];
    require(Yii::getPathOfAlias("webroot").'/protected/vendor/numbertoword.php');

    $order = Order::model()->findByPk($orderid);

    if($order->statusid == 3){
      //si la orden está cancelada no se puede imprimir
      throw new CHttpException(403, "Usted no está autorizado a realizar esta acción.");
      return;
    }

    $supplier = Supplier::model()->findByPk($order->supplierid);

/* facturar a */
    $datosProveedor=strtoupper($supplier->name."<br/>".$supplier->address."<br/>".$supplier->patronal_record."<br/>".$supplier->phone);

    $datosContratante="";

    $user = User::model()->findByPk($userid);

    if($user->fiscal_data == 1){
      $datosContratante=strtoupper($user->name."<br/>".$user->address."<br/>".$user->rfc."<br/>".$user->phone_number);
    }else{
      $fiscal = $user->fiscal;
      if($fiscal !== null){
        //echo "asdf"; return;
        $datosContratante=strtoupper($user->fiscal->name."<br/>".$user->fiscal->address."<br/>".$user->fiscal->rfc."<br/>".$user->fiscal->phone_number);
      }
    }

    $totalConLetra=numtoletras($order->total);

    //orden -> presupuesto -> proyecto
    //$datosProyecto = direccion proyecto
    $project = $order->budget->project;
    $budget = $order->budget;
    $datosProyecto=strtoupper("$project->address");
    $obra=strtoupper("$project->name - $budget->name");
    $plaza=strtoupper($project->location);

    $colocadaPor=$order->colocadapor;
    $autorizadaPor=$order->autorizadapor;

    $totalPrice = $order->getSummatoryPrice();
    $summatoryTotal = $order->getSummatoryTotal();
    $totalTax = $order->getSummatoryTax();
    $summatoryTotalPlusTax = $order->getSummatoryTotalPlusTax();

    $this->render('/order/print', array(
      'order' => $order,
      'supplier' => $supplier,
      'datosProveedor' => $datosProveedor,
      'datosContratante' => $datosContratante,
      'datosProyecto' => $datosProyecto,
      'totalConLetra'=>$totalConLetra,
      'plaza'=>$plaza,
      'obra'=>$obra,
      'colocadaPor'=>$colocadaPor,
      'autorizadaPor'=>$autorizadaPor,
      'totalPrice'=>$totalPrice,
      'summatoryTotal'=>$summatoryTotal,
      'totalTax'=>$totalTax,
      'summatoryTotalPlusTax'=>$summatoryTotalPlusTax,
      'logo'=>$logo
    ));
  }

  public function actionPrintContract($orderid) {
    require_once(Yii::getPathOfAlias("webroot") . '/protected/vendor/TCPDF/tcpdf.php');

    $html = $this->getHtmlContractByOrderId($orderid);

    $order = Order::model()->findByPk($orderid);

    $pdf = $this->convertHtmlToPDF($html, $orderid, $order->budget->project->name);

    $order = null;

    //Close and output PDF document
    $pdf->Output('ulasdfadsf.pdf', 'I');
  }

  private function convertHtmlToPDF($content, $orderid, $budgetname){
    ob_start();
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    require_once(Yii::getPathOfAlias("webroot") . '/protected/vendor/TCPDF/tcpdf.php');
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('SIDIC');

    $pdf->SetTitle("SIDIC Contrato orden de compra: $orderid Proyecto: ".$budgetname);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set font
    $pdf->SetFont('helvetica', '', 9);

    // add a page
    $pdf->AddPage();

    // output the HTML content
    $pdf->writeHTML($content, false, 0, false, 0);

    // reset pointer to the last page
    $pdf->lastPage();

    ob_end_clean();

    return $pdf;
  }

  private function getHtmlContractByOrderId($orderid){
    require(Yii::getPathOfAlias("webroot") . '/protected/vendor/numbertoword.php');

    $order = Order::model()->findByPk($orderid);

    Yii::setPathOfALias("docs", Yii::getPathOfAlias("webroot") . '/protected/docs');

    $ruta = Yii::getPathOfAlias('docs');

    if (!is_dir(Yii::getPathOfAlias('docs'))) {
      mkdir(Yii::getPathOfAlias('docs'));
      chmod(Yii::getPathOfAlias('docs'), 0755);
    }

    $detalles  = $order->orderdetail;
    //partidas de la oc
    $conceptos = "";
    foreach ($detalles as $item) {
      $conceptos .= $item->description . "<br/>";
    }

    $handle = fopen($ruta . "/machotecontratoobra.txt", "r");
    $html   = "";
    $total  = numtoletras($order->total);

    $user = User::model()->findByPk(Yii::app()->user->id);

    if($user->fiscal_data == 1){
      $contratante = $user->name;
    }else{
      $contratante = $user->fiscal->name;
    }

    while (($line = fgets($handle)) !== false) {
      if (strpos($line, "{contratista}") !== false) { //nombre del proveedor
        $line = str_replace("{contratista}", $order->supplier->name, $line);
      }
      if (strpos($line, "{contratistadomicilio}") !== false) { //domicilio del proveedor
        $line = str_replace("{contratistadomicilio}", $order->supplier->name, $line);
      }
      if (strpos($line, "{contratante}") !== false) { //tomar de los datos fiscales es el representante
        $line = str_replace("{contratante}", $contratante, $line);
      }
      if (strpos($line, "{representante}") !== false) { //este es el representante del contratante
        $line = str_replace("{representante}", $contratante, $line);
      }
      if (strpos($line, "{representantedomicilio}") !== false) { //representante domicilio
        $line = str_replace("{representantedomicilio}", $order->budget->project->address, $line);
      }
      if (strpos($line, "{clavecontribuyente}") !== false) { //datos del proveedor
        $line = str_replace("{clavecontribuyente}", $order->supplier->rfc, $line);
      }
      if (strpos($line, "{registropatronal}") !== false) { //datos del proveedor
        $line = str_replace("{registropatronal}", $order->supplier->patronal_record, $line);
      }
      if (strpos($line, "{serviciosubcontratar}") !== false) { //nombre del presupuesto
        $line = str_replace("{serviciosubcontratar}", $order->budget->name, $line);
      }
      if (strpos($line, "{numeroordendecompra}") !== false) {
        $line = str_replace("{numeroordendecompra}", $order->order_id, $line);
      }
      if (strpos($line, "{direcciondelcontratante}") !== false) { //direccion del proyecto
        $line = str_replace("{direcciondelcontratante}", $order->budget->project->address, $line);
      }
      if (strpos($line, "{cantidadpagar}") !== false) { //total de la orden de compra
        $line = str_replace("{cantidadpagar}", number_format($order->total, 2, '.', ','), $line);
      }
      if (strpos($line, "{cantidadpagarconletra}") !== false) {
        $line = str_replace("{cantidadpagarconletra}", "$total", $line);
      }
      if (strpos($line, "{partidas}") !== false) { //partidas de la oc
        $line = str_replace("{partidas}", $conceptos, $line);
      }
      if (strpos($line, "{fechafinoc}") !== false) { //fecha fin trabajo oc: solo aplica insumos y mano de obra
        $line = str_replace("{fechafinoc}", date("Y-m-d"), $line);
      }
      if (strpos($line, "{contratofirmadoen}") !== false) { //plaza del proyecto domicilio
        $line = str_replace("{contratofirmadoen}", $order->budget->project->location, $line);
      }
      if (strpos($line, "{contratofirmadoenfecha}") !== false) { //fecha actual cuando se genera el contrato
        $line = str_replace("{contratofirmadoenfecha}", date("d") . " días del mes de " . date("F") . " del " . date("Y"), $line);
      }
      if (strpos($line, "{testigo1}") !== false) { //usuario que genera la oc
        $line = str_replace("{testigo1}", Yii::app()->user->name, $line);
      }
      if (strpos($line, "{testigo2}") !== false) { //usuario administrador del sistema
        $line = str_replace("{testigo2}", "JERARDO PUERTA MEDINA", $line);
      }

      $html .= $line;

    }

    fclose($handle);

    return $html;
  }

  /**
  *Sends the oc by email
  *@param orderid
  *@param userid
  *returns a json object with error code and error message
  */
  public function actionGetOc($orderid, $userid, $logo){
    $this->layout="//layouts/print";

    require(Yii::getPathOfAlias("webroot").'/protected/vendor/numbertoword.php');

    $order = Order::model()->findByPk($orderid);

    if($order->statusid == 3){
      //si la orden está cancelada no se puede imprimir
      throw new CHttpException(403, "Usted no está autorizado a realizar esta acción.");
      return;
    }

    $supplier = Supplier::model()->findByPk($order->supplierid);

/* facturar a */
    $datosProveedor=strtoupper($supplier->name."<br/>".$supplier->address."<br/>".$supplier->patronal_record."<br/>".$supplier->phone);

    $datosContratante="";

    $user = User::model()->findByPk($userid);

    if($user->fiscal_data == 1){
      $datosContratante=strtoupper($user->name."<br/>".$user->address."<br/>".$user->rfc."<br/>".$user->phone_number);
    }else{
      $fiscal = $user->fiscal;
      if($fiscal !== null){
        //echo "asdf"; return;
        $datosContratante=strtoupper($user->fiscal->name."<br/>".$user->fiscal->address."<br/>".$user->fiscal->rfc."<br/>".$user->fiscal->phone_number);
      }
    }

    $totalConLetra=numtoletras($order->total);

    //orden -> presupuesto -> proyecto
    //$datosProyecto = direccion proyecto
    $project = $order->budget->project;
    $budget = $order->budget;
    $datosProyecto=strtoupper("$project->address");
    $obra=strtoupper("$project->name - $budget->name");
    $plaza=strtoupper($project->location);

    $colocadaPor=$order->colocadapor;
    $autorizadaPor=$order->autorizadapor;

    $query="UPDATE `order` SET solicitadapor='".$user->name."' WHERE id=".$orderid;
    Yii::app()->db->createCommand($query)->execute();

    $totalPrice = $order->getSummatoryPrice();
    $summatoryTotal = $order->getSummatoryTotal();
    $totalTax = $order->getSummatoryTax();
    $summatoryTotalPlusTax = $order->getSummatoryTotalPlusTax();

    $this->render('/order/email', array(
      'order' => $order,
      'supplier' => $supplier,
      'datosProveedor' => $datosProveedor,
      'datosContratante' => $datosContratante,
      'datosProyecto' => $datosProyecto,
      'totalConLetra'=>$totalConLetra,
      'plaza'=>$plaza,
      'obra'=>$obra,
      'colocadaPor'=>$colocadaPor,
      'autorizadaPor'=>$autorizadaPor,
      'totalPrice'=>$totalPrice,
      'summatoryTotal'=>$summatoryTotal,
      'totalTax'=>$totalTax,
      'summatoryTotalPlusTax'=>$summatoryTotalPlusTax,
      'solicitante'=>$user->name,
      'logo'=>$logo.".jpg"
    ));

  }

  public function actionOcToEmail($orderid, $email_account){
    if(Yii::app()->user->isGuest == 1){
      throw new CHttpException(400, 'La página que está solicitando no existe. Regrese a la consulta de presupuestos.');
    }

    $flag=str_replace("{", "", Yii::app()->user->getState('licensekey'));
    $flag=str_replace("}", "", $flag);
    $logo='enterprise-logo_'.$flag;

    $url = "http://".$_SERVER["HTTP_HOST"]."/sidic/riogrande/index.php?r=order/getoc&orderid=$orderid&userid=".Yii::app()->user->id."&logo=".$logo;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
    // This is what solved the issue (Accepting gzip encoding)
    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
    $response = curl_exec($ch);
    curl_close($ch);

    ob_start();
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    require_once(Yii::getPathOfAlias("webroot") . '/protected/vendor/TCPDF/tcpdf.php');
    require(Yii::getPathOfAlias("webroot") . '/protected/vendor/numbertoword.php');

    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('SIDIC');

    $order = Order::model()->findByPk($orderid);

    $pdf->SetTitle("SIDIC Contrato orden de compra: $order->id Proyecto: " . $order->budget->project->name);
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->AddPage();
    $pdf->writeHTML($response, false, 0, false, 0);

    $pdf->lastPage();
    ob_end_clean();

    $supplyEmail="";

    $supplier = Supplier::model()->findByPk($order->supplierid);

    $filename="oc".$order->order_id.".pdf";
    $pdf->Output(Yii::getPathOfAlias("webroot").'/pdf/'.$filename, 'F');

    require(Yii::getPathOfAlias("webroot").'/protected/vendor/phpmailer/PHPMailerAutoload.php');

    $mail = new PHPMailer();
    $mail->IsSMTP();
    //$mail->SMTPDebug = 2;
    $mail->CharSet="UTF-8";
    $mail->SMTPSecure = "ssl";
    $mail->Host = 'mail.compasstaxi.mx';
    $mail->Port = 465;
    $mail->Username = "webmaster@compasstaxi.mx";
    $mail->Password = "iNcSxjoa9y2M";
    $mail->SMTPAuth = true;

    $mail->From = 'info@riograndehabitat.mx';
    $mail->FromName = "SIDIC";
    $email_account=$email_account;
    $mail->AddAddress($email_account);

    $mail->IsHTML(true);
    $mail->Subject = "Orden de compra #".$order->order_id;
    $mail->msgHTML('&nbsp;');
    $mail->addAttachment(Yii::getPathOfAlias("webroot").'/pdf/'.$filename, $filename);

    //send the message, check for errors
    if (!$mail->send()) {
      echo "Error";
    }else{
      echo "OK";
      /*if(strpos($email->ErrorInfo, "recipient email") !== false){
        echo "El correo del proveedor no es válido por favor asigne un correo valido al proveedor.";
      }else{
        echo "Error:";
      }*/
      //echo $mail->ErrorInfo."De momento no está funcionando el servidor de correo, si el problema persiste después de varios intentos, notifiquelo a su proveedor del sistem.";
    }

  }

  /**
  *Sends the contract by email
  *@param
  *returns OK or Error
  */
  public function actionContractToEmail($orderid, $email){
    if(Yii::app()->user->isGuest == 1){
      throw new CHttpException(400, 'La página que está solicitando no existe. Regrese a la consulta de presupuestos.');
    }

    require_once(Yii::getPathOfAlias("webroot") .'/protected/vendor/TCPDF/tcpdf.php');
    require(Yii::getPathOfAlias("webroot").'/protected/vendor/phpmailer/PHPMailerAutoload.php');

    $html = $this->getHtmlContractByOrderId($orderid);
    $order = Order::model()->findByPk($orderid);
    $pdf = $this->convertHtmlToPDF($html, $orderid, $order->budget->project->name);
    $order = null;
    $filename="contrato-de-obra".$order->order_id.".pdf";
    //Close and output PDF document
    $pdf->Output(Yii::getPathOfAlias("webroot").'/pdf/'.$filename, 'F');

    $mail = new PHPMailer();
    $mail->IsSMTP();
    //$mail->SMTPDebug = 2;
    $mail->CharSet="UTF-8";
    $mail->SMTPSecure = "ssl";
    $mail->Host = 'mail.compasstaxi.mx';
    $mail->Port = 465;
    $mail->Username = "webmaster@compasstaxi.mx";
    $mail->Password = "iNcSxjoa9y2M";
    $mail->SMTPAuth = true;
    $mail->From = 'info@riograndehabitat.mx';
    $mail->FromName = "SIDIC";
    $mail->AddAddress($email);
    $mail->IsHTML(true);
    $mail->Subject = "Contrato de obra";
    $mail->msgHTML('&nbsp;');
    $mail->addAttachment(Yii::getPathOfAlias("webroot").'/pdf/'.$filename, $filename);

    //send the message, check for errors
    if (!$mail->send()) {
      echo "Error:".$mail->ErrorInfo;
    }else{
      echo "OK";
    }

  }


  /**
  *Muestra las ordenes de compra a nivel de presupuesto
  *@budgetid es el id del presupuesto
  *devuelve un listado de ordenes de compra por @budgetid
  */
  public function actionListOrdersByBudgetId($name, $projectId){

    $this->render('listOrdersByBudgetId',array(
      "budgetName"=>$name,
      "projectId"=>$projectId
    ));

  }

  public function actionGetOrders($id, $projectId){
      $url = Yii::app()->createUrl("/order/update");
      $query="select count(*)
        from `order` as o
inner join budget b on b.id = o.budgetid
inner join project p on p.id = b.projectid and p.licensekey = '".Yii::app()->user->getState('licensekey')."'
        inner join orderstatus as est on est.id = o.statusid
        inner join supplier as prov on prov.id = o.supplierid
        inner join budgetitem as partida on partida.budgetid = b.id and partida.parentid = 0 and partida.`status` = 1
        inner join budgetitem as subpartida on subpartida.parentid = partida.id and subpartida.parentid > 0 and subpartida.`status` = 1
        where b.id=".$id;

      $res = Yii::app()->db->createCommand($query)->queryColumn();
      $count=$res[0];

      $projectId = (string)$projectId;

      $query="select
        CONCAT(\"<a href='\",
        CONCAT(\"$url&id=\",partida.id,\"&projectid=\",$projectId,\"&budgetid=\",CAST(partida.budgetid AS CHAR),\"&orderid=\",CAST(o.id as CHAR)), \"'>Ver OC</a>\") AS Url,
        o.id, o.order_id as Folio, DATE_FORMAT(o.created, '%Y-%m-%d') as Fecha, prov.name as Proveedor, o.ordertype as Tipo
        , partida.name as Partida
        , subpartida.name as Subpartida
        , est.name as Estatus
        from `order` as o
inner join budget b on b.id = o.budgetid
inner join project p on p.id = b.projectid and p.licensekey = '".Yii::app()->user->getState('licensekey')."'
        inner join orderstatus as est on est.id = o.statusid
        inner join supplier as prov on prov.id = o.supplierid
        left join budgetitem as partida on partida.budgetid = b.id and partida.parentid = 0 and partida.`status` = 1
        left join budgetitem as subpartida on subpartida.parentid = partida.id and subpartida.parentid > 0 and subpartida.`status` = 1
        where b.id=".$id;

      $res = Yii::app()->db->createCommand($query)->queryAll();

      //"page":1,"total":3,"records":20,"rows":
      $response["page"]=$_GET["page"];
      $response["total"]=$count;
      $response["records"]=100;
      $response["rows"]=$res;

      echo json_encode($response);
  }

  /**
  *@orderid: id de la orden
  */
  public function actionView($id, $projectid, $budgetid, $orderid) {
    $order=Order::model()->findByPk($orderid);
    $orderdetail = $order->orderdetail;
    $partida     = BudgetItem::model()->findByPk($id);
    $project     = Project::model()->findByPk($projectid);
    $subpartida  = new BudgetItem;
    $tope=0;

    if ($partida->parentid > 0) {
      $subpartida = $partida;
      $partida    = BudgetItem::model()->findByPk($partida->parentid);
      $tope = str_replace(",", "", $subpartida->budgettop);
    }else{
      $tope = str_replace(",", "", $partida->budgettop);
    }

    $suppliers = Supplier::model()->findAll(array(
      'condition' => 'active = 1'
    ));

    if ($order->statusid == Order::STATUS_CANCELADA) {
      Yii::app()->user->setFlash("orderPreviousCanceled", "Esta órden tiene estatus CANCELADA y no podrá ser modificada nuevamente.");
    }

    $res             = -1;
    $importe         = 0; //importe de la orden de compra es igual a la suma de los totales
    $importeTasaCero = 0; //importe de articulos con tasa cero

    $continuar = true;
    $orderdetail = $order->orderdetail;

    foreach ($orderdetail as $det) {
      if ($det->zerotax == 1) {
        $importeTasaCero += $det->total;
      }
    }

    $this->render('update', array(
      'order' => $order,
      'orderdetail' => $orderdetail,
      'suppliers' => $suppliers,
      'partida' => $partida,
      'subpartida' => $subpartida->name,
      'project' => $project,
      'budgetid' => $budgetid,
      'importeTasaCero' => number_format(round($importeTasaCero, 2, PHP_ROUND_HALF_UP), 2, '.', ',')
    ));
  }

  /**
  *Autorizar la orden de compra
  * @param orderid es el id de la orden de compra para autorizar
  * @return Boolean muestra que la orden fue o no autorizada
  */
  public function actionAutorizar($orderid){
    $rows=Yii::app()->db->createCommand("UPDATE `order` SET statusid=".Order::STATUS_AUTORIZADA." WHERE id=$orderid;")->execute();

    echo $rows==1;
  }

  /**
  *Recibir la orden de compra
  * @param OrderId es el id de la orden de compra para recibir
  * @return Boolean muestra que la orden fue o no recibida
  */
  public function actionRecibir($orderid){
    $rows=Yii::app()->db->createCommand("UPDATE `order` SET statusid=".Order::STATUS_SURTIDA." WHERE id=$orderid;")->execute();

    echo $rows==1;
  }  

  /**
  * Regresa la vista del reporte financiero
  * @param $projectid
  */
  public function actionReporteGeneral($projectid, $budgetid){
    $project = Project::model()->findByPk($projectid);
    $ydate = date("d-m-Y");
    $budget = Budget::model()->findByPk($budgetid);

    $this->render('_reportegeneral',array(
        'project'=>$project,
        'ydate' =>$ydate,
        'budgetname' =>$budget->name,
        'budgetestatus' =>$budget->active==1?'Activo':'Inactivo',
        'budgetid' =>$budget->id
    ));

  }

  /**
  * Regresa los datos para el reporte financiero
  * @param $projectid
  */
  public function actionReporteGeneralData($projectid, $budgetid){
    $repo=new OrderServiceImpl('Order');

    $count=$repo->getReporteGeneralCount($projectid, $budgetid);
    $res=$repo->getReporteGeneralData($projectid, $budgetid);

    $response["page"]=$_GET["page"];
    $response["total"]=$count;
    $response["records"]=100;
    $response["rows"]=$res;

    echo json_encode($response);
  }

  /**
  * Impresion en formato pdf del reporte general de orden de compras
  * @param $projectid
  */
  public function actionReporteGeneralPdf($projectid, $budgetid){
    $repo=new OrderServiceImpl('Order');

    $orders=$repo->getReporteGeneralPdf($projectid, $budgetid);
  }

  /**
  * Impresion en formato pdf del reporte de ordenes por autorizar
  * @param ProjectId es el id del proyecto al que pertenece el presupuesto
  * @param BudgetId es el id del presupuesto al que pertenece la orden de compra
  */
  public function actionReporteOcPorAutorizarPdf($projectid, $budgetid){
    $repo=new OrderServiceImpl('Order');

    $orders=$repo->getReporteOcPorAutorizarPdf($projectid, $budgetid);
  }  
  
  /**
  * Regresa la vista del reporte oc por autorizar
  * @param $projectid
  */
  public function actionReporteOcPorAutorizar($projectid, $budgetid){
    $project = Project::model()->findByPk($projectid);
    $ydate = date("d-m-Y");
    $budget = Budget::model()->findByPk($budgetid);

    $this->render('_reporteocporautorizar',array(
        'project'=>$project,
        'ydate' =>$ydate,
        'budgetname' =>$budget->name,
        'budgetestatus' =>$budget->active==1?'Activo':'Inactivo',
        'budgetid' =>$budget->id
    ));
  }

  
  /**
  * Regresa los datos para el reporte oc por autorizar
  * @param $projectid
  */
  public function actionReporteOcPorAutorizarData($projectid, $budgetid){
    $repo=new OrderServiceImpl('Order');

    $count=$repo->getReporteOcPorAutorizarCount($projectid, $budgetid);
    $res=$repo->getReporteOcPorAutorizarData($projectid, $budgetid);

    $response["page"]=$_GET["page"];
    $response["total"]=$count;
    $response["records"]=100;
    $response["rows"]=$res;

    echo json_encode($response);
  }


  /**
  * Regresa la vista del reporte ordenes de compra autorizadas
  * @param ProjectId
  * @param BudgetId
  */
  public function actionOcAutorizadas($projectid, $budgetid){
    $project = Project::model()->findByPk($projectid);
    $ydate = date("d-m-Y");
    $budget = Budget::model()->findByPk($budgetid);

    $this->render('_reporteocautorizadas',array(
        'project'=>$project,
        'ydate' =>$ydate,
        'budgetname' =>$budget->name,
        'budgetestatus' =>$budget->active==1?'Activo':'Inactivo',
        'budgetid' =>$budget->id
    ));

  }

  /**
  * Regresa la vista del reporte ordenes de compra autorizadas
  * @param ProjectId
  * @param BudgetId
  */
  public function actionOcAutorizadasData($projectid, $budgetid){
    $repo=new OrderServiceImpl('Order');

    $count=$repo->getReporteOcAutorizadasCount($projectid, $budgetid);
    $res=$repo->getReporteOcAutorizadasData($projectid, $budgetid);

    $response["page"]=$_GET["page"];
    $response["total"]=$count;
    $response["records"]=100;
    $response["rows"]=$res;

    echo json_encode($response);
  }

  /**
  * Impresion en formato pdf del reporte de ordenes autorizadas
  * @param ProjectId es el id del proyecto al que pertenece el presupuesto
  * @param BudgetId es el id del presupuesto al que pertenece la orden de compra
  */
  public function actionReporteOcAutorizadasPdf($projectid, $budgetid){
    $repo=new OrderServiceImpl('Order');

    $orders=$repo->getReporteOcAutorizadasPdf($projectid, $budgetid);
  }

  /**
  * Regresa la vista del reporte ordenes de compra recibidas
  * @param ProjectId
  * @param BudgetId
  */
  public function actionReporteOcRecibidas($projectid, $budgetid){
    $project = Project::model()->findByPk($projectid);
    $ydate = date("d-m-Y");
    $budget = Budget::model()->findByPk($budgetid);

    $this->render('_reporteocrecibidas',array(
        'project'=>$project,
        'ydate' =>$ydate,
        'budgetname' =>$budget->name,
        'budgetestatus' =>$budget->active==1?'Activo':'Inactivo',
        'budgetid' =>$budget->id
    ));

  }

  /**
  * Regresa la vista del reporte ordenes de compra autorizadas
  * @param ProjectId
  * @param BudgetId
  */
  public function actionReporteOcRecibidasData($projectid, $budgetid){
    $repo=new OrderServiceImpl('Order');

    $count=$repo->getReporteOcRecibidasCount($projectid, $budgetid);
    $res=$repo->getReporteOcRecibidasData($projectid, $budgetid);

    $response["page"]=$_GET["page"];
    $response["total"]=$count;
    $response["records"]=100;
    $response["rows"]=$res;

    echo json_encode($response);
  }

  /**
  * Genera en pdf un reporte de ordenes de compra recibidas
  * @param ProjectId
  * @param BudgetId
  */
  public function actionReporteOcRecibidasPdf($projectid, $budgetid){
    $repo=new OrderServiceImpl('Order');

    $orders=$repo->getReporteOcRecibidasPdf($projectid, $budgetid);
  }

  /**
  * Regresa la vista del reporte ordenes de compra por pagar
  * @param ProjectId
  * @param BudgetId
  */
  public function actionReporteOcPorPagar($projectid, $budgetid){
    $project = Project::model()->findByPk($projectid);
    $ydate = date("d-m-Y");
    $budget = Budget::model()->findByPk($budgetid);

    $this->render('_reporteocxpagar',array(
        'project'=>$project,
        'ydate' =>$ydate,
        'budgetname' =>$budget->name,
        'budgetestatus' =>$budget->active==1?'Activo':'Inactivo',
        'budgetid' =>$budget->id
    ));

  }

  /**
  * Regresa la vista del reporte ordenes de compra por pagar
  * @param ProjectId
  * @param BudgetId
  */
  public function actionReporteOcPorPagarData($projectid, $budgetid){
    $repo=new OrderServiceImpl('Order');

    $count=$repo->getReporteOcPorPagarCount($projectid, $budgetid);
    $res=$repo->getReporteOcPorPagarData($projectid, $budgetid);

    $response["page"]=$_GET["page"];
    $response["total"]=$count;
    $response["records"]=100;
    $response["rows"]=$res;

    echo json_encode($response);
  }

  /**
  * Genera un reporte en formato pdf de las ordenes de compra por pagar
  * @param ProjectId
  * @param BudgetId
  */
  public function actionReporteOcPorPagarPdf($projectid, $budgetid){
    $repo=new OrderServiceImpl('Order');

    $orders=$repo->getReporteOcPorPagarPdf($projectid, $budgetid);
  }  

  /**
  * Regresa la vista del reporte ordenes de compra pagadas
  * @param ProjectId
  * @param BudgetId
  */
  public function actionReporteOcPagadas($projectid, $budgetid){
    $project = Project::model()->findByPk($projectid);
    $ydate = date("d-m-Y");
    $budget = Budget::model()->findByPk($budgetid);

    $this->render('_reporteocpagadas',array(
        'project'=>$project,
        'ydate' =>$ydate,
        'budgetname' =>$budget->name,
        'budgetestatus' =>$budget->active==1?'Activo':'Inactivo',
        'budgetid' =>$budget->id
    ));
  }

  /**
  * Regresa la vista del reporte ordenes de compra pagadas
  * @param ProjectId
  * @param BudgetId
  */
  public function actionReporteOcPagadasData($projectid, $budgetid){
    $repo=new OrderServiceImpl('Order');

    $count=$repo->getReporteOcPagadasCount($projectid, $budgetid);
    $res=$repo->getReporteOcPagadasData($projectid, $budgetid);

    $response["page"]=$_GET["page"];
    $response["total"]=$count;
    $response["records"]=100;
    $response["rows"]=$res;

    echo json_encode($response);
  }

  /**
  * Genera un reporte de ordenes pagadas en formato pdf
  * @param ProjectId
  * @param BudgetId
  */
  public function actionReporteOcPagadasPdf($projectid, $budgetid){
    $repo=new OrderServiceImpl('Order');

    $orders=$repo->getReporteOcPagadasPdf($projectid, $budgetid);
  }

  public function actionPrintOc(){
    $url = "http://".$_SERVER["HTTP_HOST"]."/sidic/riogrande/index.php?r=order/GetOcText";
    $orderid=$_POST['orderid'];
    $userid=$_POST['userid'];
    $flag=str_replace("{", "", Yii::app()->user->getState('licensekey'));
    $flag=str_replace("}", "", $flag);
    $logo=Yii::app()->baseUrl.'/images/enterprise-logo_'.$flag.'.jpg';
  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
            "orderid=".$orderid."&userid=".$userid."&logo=".$logo);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
    // This is what solved the issue (Accepting gzip encoding)
    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
    $response = curl_exec($ch);
    curl_close($ch);

    /*generar impresion en formato pdf*/
    ob_start();
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    require_once(Yii::getPathOfAlias("webroot").'/protected/vendor/TCPDF/tcpdf.php');
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator("SIDIC");
    $pdf->SetAuthor('SIDIC');
    $pdf->SetTitle("SIDIC Órden de compra");
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->AddPage();
    $pdf->writeHTML($response, true, false, false, false, '');
    $pdf->lastPage();

    ob_end_clean();
    $pdf->Output('OrdenDeCompra_'.$orderid.'.pdf', 'I');

  }



}

