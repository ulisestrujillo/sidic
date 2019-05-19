<?php

class ProjectController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/admin';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'ajaxOnly + budgetlisthtml + projectListHtml', // we only allow deletion via POST request
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
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','view'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update', 'admin','budgetlisthtml','ProjectListHtml'),
                'roles'=>array('admin'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('admin', 'view'),
                'roles'=>array('autorizador', 'elaborador', 'contralor', 'receptor','budgetlisthtml','ProjectListHtml'),
            ),
            array('deny', // allow admin user to perform 'admin' and 'delete' actions
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new Project;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Project']))
        {
            $model->attributes=$_POST['Project'];
            $model->licensekey=Yii::app()->user->getState('licensekey');

            if($model->save())
                $this->redirect(Yii::app()->createUrl('project/update', array('id'=>$model->id)));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Project']))
        {
            $model->attributes=$_POST['Project'];

            if($model->save())
        Yii::app()->user->setFlash('updated', "Se ha actualizado con éxito el proveedor: {$model->name}");
                $this->redirect(array('admin'));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider=new CActiveDataProvider('Project');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
          $model=new Project('search');

          $model->unsetAttributes();  // clear any default values
          if(isset($_GET['Project']))
              $model->attributes=$_GET['Project'];

          $this->render('admin',array(
              'model'=>$model,
          ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Project the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Project::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Project $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='project-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
    *Action que muestra un listado de los presupuestos pertenecientes a un proyecto en formato html
    *este llamado solo es por ajax
    *@id es el id del proyecto
    */
    public function actionBudgetListHtml($id){
      $project=Project::Model()->findByPk($id);
      $budgetList="";
      $index=0;

      foreach ($project->budgets as $budget) {
        $index=$index+1;
        $budgetList.=
        '<div class="row presupuestorow">
          <div class="col-md-8 col-sm-8 col-xs-8 col-lg-8">
            <div class="item">'.$budget->name.'<br></div>
          </div>
          <div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 text-right">
            <input class="budgetcheck" id="bicheck_'.$budget->id.'" onclick="refreshBudgetList(this, '.$id.','.$budget->id.');" name="BudgetAccess['.$index.'][budgetid]" type="checkbox" />
          </div>
        </div>';
      }

      if($budgetList==''){
        $budgetList="SIN PRESUPUESTOS";
      }

      echo $budgetList;
    }

    /**
    *Action que muestra un listado de los projectos en formto html
    *este llamado solo es por ajax
    *@id es el id del proyecto
    */
    public function actionProjectListHtml(){
      $licensekey=Yii::app()->user->getState('licensekey');
      $project=Project::Model()->findAll("licensekey='".$licensekey."'");
      $projectList="";
      $id=0;

      foreach ($project as $item) {
        $id=$id+1;
        $projectList.=
        '<div class="row projectrow" id="user_'.$id.'">
          <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="item" onclick="getBudgetList('.$item->id.', '."'user_$id'".');">'.$item->name.' ('.sizeof($item->budgets).')<br></div>
          </div>
        </div>';
      }

      echo $projectList;
    }


}
