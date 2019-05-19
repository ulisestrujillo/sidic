<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public function init()
	{
    if(Yii::app()->user->isGuest)
	    return;
		$resource=strtolower(Yii::app()->request->getQueryString());
    $key="";

    if(strpos($resource, "r=budget/list&id") !== false){
      $model = Project::Model()->findByPk(Yii::app()->request->getParam('id'));
      $key=$model->licensekey;
    }elseif(strpos($resource, "r=budget&id") !== false){
      $model = Budget::Model()->findByPk(Yii::app()->request->getParam('id'));
      $key=$model->project->licensekey;
    }elseif(strpos($resource, "r=project/update&id") !== false){
      $model = Project::Model()->findByPk(Yii::app()->request->getParam('id'));
      $key=$model->licensekey;
    }elseif(strpos($resource, "r=supplier/update&id") !== false){
      $model = Supplier::Model()->findByPk(Yii::app()->request->getParam('id'));
      $key=$model->licensekey;
    }elseif(strpos($resource, "r=usersystem/update&id") !== false){
      $model = UserSystem::Model()->findByPk(Yii::app()->request->getParam('id'));
      $key=$model->licensekey;
    }elseif(strpos($resource, "r=order/budget&id") !== false){
      $model = BudgetItem::model()->findByPk(Yii::app()->request->getParam('id'));
      $key=$model->budget->project->licensekey;
    }elseif(strpos($resource, "r=order/update&id") !== false){
      $model = Project::Model()->findByPk(Yii::app()->request->getParam('projectid'));
      $key=$model->licensekey;
    }elseif(strpos($resource, "r=privileges/index") !== false){
			if(!User::checkRole('admin')){
        $key="";
			}
    }

    $licensekey=Yii::app()->user->getState('licensekey');

    if($licensekey!=$key && $key!='')
    {
      $this->render('/site/unauthorized');
    }

	}

}