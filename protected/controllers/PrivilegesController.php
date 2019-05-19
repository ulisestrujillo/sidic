<?php
class PrivilegesController extends Controller {
	public $layout = '//layouts/admin';

	// Uncomment the following methods and override them if needed
	public function filters() {
		return array(
			'accessControl',
		  'ajaxOnly + removeAllItems + getOperations + remove',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 *
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
			array(
				'allow', // deny all users
				'actions' => array('index', 'update', 'getOperations', 'remove', 'RemoveAllItems'),
				'roles' => array(
					'admin' 
				) 
			),
			array(
				'deny', // allow all users to perform 'index' and 'view' actions
				'users' => array(
					'*' 
				) 
			) 
		);
	}

	public function actionIndex() {
		$licensekey = Yii::app()->user->getState('licensekey');
		$users = User::Model()->findAll("licensekey='".$licensekey."'");
		$auth = Yii::app()->authManager;
		$roles = $auth->getRoles();
		
		$this->render('index', array(
			'users' => $users,
			'roles' => $roles 
		));
	}

	public function actionUpdate() {
		$auth = Yii::app()->authManager;
		$roles = $auth->getRoles();
		$userid = 0;
		$extraPrivileges = null;
		
		if(isset($_POST["Privileges"]) && isset($_POST["Privileges"]['userid'])){
			$userid = $_POST["Privileges"]['userid'];
			if(isset($_POST["Privileges"]['extra'])){
				$extraPrivileges = $_POST["Privileges"]['extra'];
			}
		}
		
		// remover roles al usuario
		foreach ( $roles as $rol ){
			$auth->revoke($rol->name, $userid);
		}
		
		// remover operaciones al usuario
		$operations = $auth->getOperations();
		foreach ( $operations as $operation ){
			if(!isset($extraPrivileges)){
				$auth->revoke($operation->name, $userid);
			}
			else if(isset($extraPrivileges) && !in_array($operation->name, $extraPrivileges)){
				$auth->revoke($operation->name, $userid);
			}
		}
		
		$newRole = $this->extractRole($_POST["Privileges"]);
		$newOperation = $this->extractOperations($_POST["Privileges"]);
		
		// asignar nuevo rol + operaciones del rol
		foreach ( $newRole as $role ){
			$auth->assign($role, $userid);
			$children = $auth->getItemChildren($role . "Tarea");
			
			foreach ( $children as $item ){
				if(!$auth->isAssigned($item->name, $userid))
					$auth->assign($item->name, $userid);
			}
		}
		
		// asignar nuevas operaciones o permisos
		foreach ( $newOperation as $operation ){
			if(!$auth->isAssigned($operation, $userid))
				$auth->assign($operation, $userid);
		}
		
		$this->redirect(Yii::app()->createUrl('privileges/index'));
	}

	private function extractRole($roles) {
		$result = Array();
		if(isset($roles['role'])){
			foreach ( $roles['role'] as $item ){
				if(isset($item))
					array_push($result, $item);
			}
		}
		
		return $result;
	}

	private function extractOperations($operations) {
		$result = Array();
		if(isset($operations['operation'])){
			foreach ( $operations['operation'] as $item ){
				if(isset($item)){
					array_push($result, $item);
				}
			}
		}
		
		return $result;
	}

	/**
	 * Obtener todas las operaciones de todos los roles que no estén asignadas al usuario seleccionado
	 *
	 * @param
	 *        	Usuario Id es el id del usuario del que se van a obtener sus operaciones
	 */
	private function getAllOperations($userid) {
		$auth = Yii::app()->authManager;
		$rbac = Array();
		$operations = Array();
		$allOperations = Array();
		
		$operations = $auth->getOperations();
		
		foreach ( $operations as $item ){
			if(!$auth->isAssigned($item->name, $userid)){
				array_push($allOperations, $item->name);
			}
		}
		
		return $this->operationsToHTML($allOperations);
	}

	/**
	 * Obtener las operaciones por usuario
	 *
	 * @param
	 *        	Usuario Id es el id del usuario del que se van a obtener sus operaciones
	 */
	public function actionGetOperations($userid) {
		$userModel = User::Model()->findByPk($userid);
		$licensekey = Yii::app()->user->getState('licensekey');
		
		if($licensekey != $userModel->licensekey){
			$respuesta["data"] = "Usted no cuenta con privilegios.";
			echo json_encode($respuesta);
			return;
		}
		
		$auth = Yii::app()->authManager;
		$rolesList = $auth->getRoles($userid);
		
		$rbac = Array();
		$roles = Array();
		
		foreach ( $rolesList as $item ){
			array_push($roles, $item->name);
		}
		
		$rbac[0] = $roles;
		$rbac[1] = $this->getAllPrivilegesAllRoles($userid); // roles y privilegios
		$rbac[2] = $this->getAllOperations($userid);
		
		echo json_encode($rbac);
	}

	/**
	 * Crea un listado de operaciones en formato html
	 * basado en un arreglo de operaciones asignadas al usuario
	 *
	 * @param
	 *        	Operaciones
	 */
	private function operationsToHTML($operaciones) {
		$html = "";
		$i = 0;
		$id = "";
		$checked = "";
		
		foreach ( $operaciones as $item ){
			$id = "privilege_" . ++$i;
			$checked = "";
			if($item[0] == "1"){
				$checked = 'checked="checked"';
			}
			
			$html .= '<div class="item">
			<input ' . $checked . ' type="checkbox" id="' . $id . '" value="' . $item . '" name="Privileges[operation][' . $i . ']">&nbsp;' . $item . '<br/></div>';
		}
		
		return $html;
	}

	/**
	 * Regresa en formato html todos los privilegios de todos los roles del usuario seleccionado
	 *
	 * @param
	 *        	Usuario Id
	 */
	private function getAllPrivilegesAllRoles($userid) {
		$auth = Yii::app()->authManager;
		$authItems = $auth->getAuthItems(2, $userid);
		$operations = $auth->getOperations($userid);
		$permisos = "";
		
		if(sizeof($operations) == 0){
			return "";
		}
		
		foreach ( $authItems as $item ){
			$permisos .= "PARENT|<b>$item->name</b>";
			$children = $auth->getItemChildren($item->name . "Tarea");
			
			foreach ( $children as $child ){
				if($auth->isAssigned($child->name, $userid)){
					$permisos .= ";CHILD|" . $child->name;
				}
			}
			if(sizeof($children) > 0){
				$permisos .= ";";
			}
		}
		
		$tag = "";
		foreach ( $operations as $operation ){
			$tag = $operation->name;
			if(strpos($permisos, $tag) == false){
				$permisos .= ";CHILD|" . $tag . "_extra";
			}
		}
		
		return $permisos;
	}

	/**
	 * Remueve un privilegio al usuarios
	 *
	 * @param
	 *        	Name es el nombre del privilegio a remover
	 * @param
	 *        	User Id es el id del usuario que tiene asignado el privilegio
	 */
	public function actionRemove($itemname, $userid) {
		$auth = Yii::app()->authManager;
		$auth->revoke($itemname, $userid);
		$rbac = $this->getAllOperations($userid);
		
		echo json_encode($rbac);
	}

	/**
	 * Remueve varios privilegios de un usuario
	 *
	 * @param
	 *        	Items son los items a eliminar
	 * @param
	 *        	User Id es el id del usuario que tiene asignado el privilegio
	 */
	public function actionRemoveAllItems($userid) {
		$userModel = User::Model()->findByPk($userid);
		$licensekey = Yii::app()->user->getState('licensekey');
		
		if($licensekey != $userModel->licensekey){
			$respuesta["data"] = "Usted no cuenta con privilegios.";
			echo json_encode($respuesta);
			return;
		}
		
		if(isset($_POST["items"])){
			$auth = Yii::app()->authManager;
			$items = $_POST["items"];
			foreach ( $items as $itemName ){
				$auth->revoke($itemName, $userid);
			}
		}
	}
}