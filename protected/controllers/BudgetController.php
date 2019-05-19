<?php

class BudgetController extends Controller
{

  public $layout='//layouts/admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
    return array(
      array('allow',  // deny all users
        'actions'=>array('index', 'list','save'),
        'roles'=>array('elaborador', 'autorizador','contralor', 'receptor'),
      ),
      array('allow',  // deny all users
        'actions'=>array('index', 'delete', 'list','save'),
        'roles'=>array('admin'),
      ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }

  /**
  *@id es el id del presupuesto igual a budgetid
  */
  public function actionIndex($id)
  {
    $budgetid = $id;
    $budgetModel = Budget::Model()->findByPk($budgetid);

    $budgetService=new BudgetServiceImpl;
    $model=new BudgetItem();
    $arrayBudgetTopOverPassList=Array();
    $arrayIsEmpty=true;
    $canIContinue=true;
    $loadTemplate=false;

    if(isset($_POST['Budget']) || (isset($_POST['Template']) && $_POST['Template']['templateId']!="0" )){
      //echo var_dump($_POST['Budget']); return;

      if(isset($_POST['Template']['templateId']) && $_POST['Template']['templateId']!="0"){
        $loadTemplate=true;
        $canIContinue=true;
      }else
        foreach ($_POST['Budget'] as $item) {
          if(!isset($item["parentid"])){
            Yii::app()->user->setFlash("ZeroBudgetItems",
              "No puede guardar un presupuesto sin partidas.");
            $canIContinue=false;
          };
        }
      
      if($canIContinue){
        $transaction = $model->dbConnection->beginTransaction();

        try{
          //borrar partidas del presupuesto
          $query="";

          //guardar partidas-subpartidas
          $canSave=true;
          $item=Array();
          
          if($loadTemplate){
            $templateid=$_POST['Template']['templateId'];
            $query="SELECT AUTO_INCREMENT-1
                    FROM information_schema.tables
                    WHERE table_name = 'budgetitem'
                    AND table_schema = DATABASE();";

            $nextBudgetItemId=Yii::app()->db->createCommand($query)->queryScalar();

            $query="SELECT id, templateid, templatename, isparent, name FROM templateitem WHERE templateid = $templateid ORDER BY sorting;";
            $templateItems=Yii::app()->db->createCommand($query)->queryAll();
            $templateParentId=0;

            foreach ($templateItems as $item) {
              if($item["isparent"]==1){
                $templateParentId=$nextBudgetItemId+=1;
                $query="INSERT INTO budgetitem(budgetid, parentid, name, budgettop, sort, status)
                        VALUES($budgetid, 0, '{$item['name']}', 0, 0, 1);";
              }else{
                $nextBudgetItemId+=1;
                $query="INSERT INTO budgetitem(budgetid, parentid, name, budgettop, sort, status)
                        VALUES($budgetid, $templateParentId, '{$item['name']}', 0, 0, 1);";
              }

              $query=Yii::app()->db->createCommand($query)->execute();
            }
          }

          if($loadTemplate==false){
            $sortidx=0;
            foreach ($_POST['Budget'] as $partida) {
              $sortidx+=1;
              $canSave=true;
              $parentid=  $partida["parentid"];
              $name=      $partida["name"];
              $budgettop= $partida["budgettop"];        
              $id=        $partida["id"];

              $orderTotalAmount=Order::getTotalByBudgetItemId($id);
              $orderTotalAmount=str_replace(",", "", $orderTotalAmount);

              if(str_replace(",", "", $budgettop) < $orderTotalAmount){
                $arrayIsEmpty=false;
                $canSave=false;
                $item["id"]=$id;
                $item["total"]=$budgettop;

                array_push($arrayBudgetTopOverPassList, $item);
              }

              if($id<1){
                //insertar la nueva partida
                $query.="INSERT INTO budgetitem(budgetid,parentid,name,budgettop) values($budgetid,$parentid,'$name',$budgettop);";
              }else{
                if($budgettop > 0 && $canSave==true){
                  $newTop = str_replace(array(","), "", $budgettop);
                  $query.="UPDATE budgetitem SET sort = $sortidx, budgettop = $newTop WHERE id=$id;";
                }else{
                  $query.="UPDATE budgetitem SET sort = $sortidx WHERE id=$id;";
                }
              }
            }

            if($query!=""){
              $res = Yii::app()->db->createCommand($query)->execute();
            }
          }//if($loadTemplate==false)


          $transaction->commit();

        }
        catch (Exception $e) {
          throw new Exception("BudgetController - Error Processing Request".$e, 1);
          $transaction->rollback();
        }

      } //if($canIContinue)

		}//if(isset($_POST['Budget'])){

    if(isset($_POST['Template']) && $_POST['Template']['templateId']=="0" ){
      Yii::app()->user->setFlash("MissingTemplateId", "Seleccione una plantilla para cargar.");
    }

		$project = $budgetModel->project;

    $partidas=$budgetService->getPartidasSubPartidasRel($budgetid);
    $res=Yii::app()->db->createCommand("select max(id) as maxid from budgetitem where budgetid = $budgetid")->queryRow();

    if($arrayIsEmpty==false){
      Yii::app()->user->setFlash("budgetTopOverpassItems", "Los montos en color rojo no deben ser menores al total de las ordenes de compra de la partida.");
    }

		$this->render('index',array(
      'arrayBudgetTopOverPassList'=>$arrayBudgetTopOverPassList,
      'partidas' => $partidas,
      'project' => $project,
      'maxid' => $res["maxid"]==null ? 1 : $res["maxid"],
      'budgetName' => $budgetModel->name,
      'budgetId' => $budgetModel->id,
		));

		//no se pueden borrar las partidas-subpartidas que tienen ordenes de compra activas
	}

  /**
  *Elimina una partida o subpartida por su id,
  *si esta tiene hijos no se podr� eliminar, esta accion se invoca via ajax;
  *regresa un true o false como resultado de si se puede o no eliminar la partida o subpartida
  *@id es el id de la partida o subpartida que se desea eliminar
  */
  public function actionDelete($id, $budgetid){
    //ver si la partida tiene ordenes de compra relacionadas
    $val = Yii::app()->db->createCommand("SELECT count(*) as total FROM `order` WHERE budgetitemid = $id")->queryRow();
    $res = "";
    $res = Yii::app()->db->createCommand("SELECT name FROM `budgetitem` WHERE id = $id")->queryRow();
    $res=$res["name"];

    if($val["total"] > "0"){
      Yii::app()->user->setFlash("delete_message", "No se puede eliminar la partida / sub-partida <strong>'$res'</strong> porque tiene ordenes de compra relacionadas.");
    }
    else{
      //validar que no tenga subpartidas
      $val = Yii::app()->db->createCommand("SELECT count(*) as total FROM budgetitem WHERE parentid = $id AND status = 1")->queryRow();
      if($val["total"] > 0){//<-- no se puede inactivar
        Yii::app()->user->setFlash("delete_message", "No se puede eliminar la partida / sub-partida <strong>'$res'</strong> porque tiene subpartidas relacionadas.");
      }else{
        //si lo podemos inactivar porque no tiene ordenes de compra
        Yii::app()->db->createCommand("UPDATE budgetitem SET status = 0 WHERE id = $id OR parentid = $id")->execute();
      }
    }

    $this->redirect(array('index','id'=>$budgetid));
  }


  /**
  *Action que muestra un listado de los presupuestos pertenecientes a un proyecto
  *@id es el id del proyecto
  */
  public function actionList($id){
    //buscar obtener los presupuestos del proyecto
    $budget = new Budget;
    $project=Project::Model()->findByPk($id);

    $query=
      "SELECT b.id
       FROM budget b
       INNER JOIN budgetuser_rel br on br.budgetid = b.id 
       AND br.userid = ".Yii::app()->user->id."
       WHERE b.projectid = $id";

    $budgetAccessList=Yii::app()->db->createCommand($query)->queryAll();
    $budgets=Budget::Model()->findAll("projectid=$id");

    if(isset($_POST["Presupuesto"])){
      $budget->projectid=$_POST["Presupuesto"]["projectid"];
      $budget->name=$_POST["Presupuesto"]["name"];

      if($budget->validate())
      {
        $budget->save();
        $this->redirect(array('budget/list', 'id' => $id));
      }
    }

    $this->render('list',array(
      'project' => $project,
      'budgetAccessList' => $budgetAccessList,
      'model' => $budget,
      'budgets' => $budgets,
    ));
  }

  /**
  *Guarda los presupuestos o proeyectos seleccionados.
  *
  *
  */
  public function actionSave(){
    if(isset($_POST["BudgetAccess"]))
    {
      echo var_dump($_POST["BudgetAccess"]);
    }

  }  

}