<?php
/* @var $this UserSystemController */
/* @var $model UserSystem */
/* @var $form CActiveForm */

?>
<style type="text/css">
    input.error{border:1px solid #e61616;}
    textarea.error{border:1px solid #e61616;}
    div.form-group.has-error{color:#e61616;}
</style>
<title>SIDIC - Crear usuario de sistema</title>
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<div class="block-header">
    <div class="row">
      <div class="col-lg-12" >
        <a href="<?php echo Yii::app()->createUrl('/UserSystem/budgetaccess', array('userid'=>$model->id)) ?>" 
        class="btn btn-raised btn-primary m-t-15 waves-effect btn-sm">Acceso a presupuestos</a>
      </div>
    </div>
    <div class="row">
        <div class="col-lg-7 col-md-6 col-sm-12">
            <h2>Usuario de sistema
            <small class="text-muted">Los campos con asterisco son requeridos</small>
            </h2>
        </div>
        <div class="col-lg-5 col-md-6 col-sm-12">
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i> Sidic</a></li>
                <li class="breadcrumb-item active">Usuarios</li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
  <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="card">
              <div class="header">
                  <h2></h2>
              </div>
              <div class="body">

                <?php $form=$this->beginWidget('CActiveForm', array(
                  'id'=>'userSystem-form',
                  'enableAjaxValidation'=>false,
                  'htmlOptions' => array('enctype'=>'multipart/form-data'),
                )); ?>

                <?php CHtml::$errorContainerTag = 'div'; ?> 

<?php if(!$model->isNewRecord): ?>
                    <label for="username">*Usuario</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input <input value="<?php echo $model->username; ?>" name="UserSystem[username]" type="text" id="UserSystem_username" class="form-control" placeholder="">
                            <?php echo $form->error($model,'username', array('class'=>'form-group has-error')); ?>
                        </div>
                    </div>
<?php endif; ?>                     

                    <label for="name">*Nombre</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input <input value="<?php echo $model->name; ?>" name="UserSystem[name]" type="text" id="UserSystem_name" class="form-control" placeholder="">
                            <?php echo $form->error($model,'name', array('class'=>'form-group has-error')); ?>
                        </div>
                    </div>  
                    <label for="address">*Dirección</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input <input value="<?php echo $model->address; ?>" name="UserSystem[address]" type="text" id="UserSystem_address" class="form-control" placeholder="">
                            <?php echo $form->error($model,'address', array('class'=>'form-group has-error')); ?>
                        </div>
                    </div>  
                    <label for="phone_number">*Teléfono</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input <input value="<?php echo $model->phone_number; ?>" name="UserSystem[phone_number]" type="text" id="UserSystem_phone_number" class="form-control" placeholder="">
                            <?php echo $form->error($model,'phone_number', array('class'=>'form-group has-error')); ?>
                        </div>
                    </div>  
                    <label for="email">*Correo</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input <input value="<?php echo $model->email; ?>" name="UserSystem[email]" type="text" id="UserSystem_email" class="form-control" placeholder="">
                            <?php echo $form->error($model,'email', array('class'=>'form-group has-error')); ?>
                        </div>
                    </div>  
<?php 
  if($model->isNewRecord):
?>                    
                    <label for="username">*Usuario</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input <input value="<?php echo $model->username; ?>" name="UserSystem[username]" type="text" id="UserSystem_username" class="form-control" placeholder="">
                            <?php echo $form->error($model,'username', array('class'=>'form-group has-error')); ?>
                        </div>
                    </div>  
                    <label for="password">*Contraseña</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input <input value="<?php echo $model->password; ?>" name="UserSystem[password]" type="password" id="UserSystem_password" class="form-control" placeholder="">
                            <?php echo $form->error($model,'password', array('class'=>'form-group has-error')); ?>
                        </div>
                    </div>

<?php endif; ?>

<?php if($model->isNewRecord): ?>
  <div class="row">
                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="rol">Rol</label>
                    <div class="form-group">
                        <div class="form-line">
                            <select onchange="showOperations(this);" name="UserSystem[role]" id="UserSystem_role">
                              <option value="elaborador" <?php echo $model->role=='elaborador'?'selected':''; ?>>elaborador</option>
                              <option value="autorizador" <?php echo $model->role=='autorizador'?'selected':''; ?>>autorizador</option>
                              <option value="receptor" <?php echo $model->role=='receptor'?'selected':''; ?>>receptor</option>
                              <option value="contralor" <?php echo $model->role=='contralor'?'selected':''; ?> >contralor</option>
                              <option value="admin" <?php echo $model->role=='admin'?'selected':''; ?> >admin</option>
                            </select>
                        </div>
                    </div>
                  </div>
                  <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <label for="rol">Operaciones permitidas</label>
                    <div id="divoperations"></div>
                  </div>
  </div>
<?php endif; ?>

  <div class="input-group input-group-lg">
        <div class="demo-switch-title">Activo</div>
        <div class="switch">
            <label>
                <input type="checkbox" name="UserSystem[active]" id="UserSystem_active"
<?php echo $model->active==1 ? 'checked=""' : '' ?>
                >
                <span class="lever switch-col-blue"></span></label>
        </div>
    </div>

                    <button type="submit" class="btn btn-raised btn-primary m-t-15 waves-effect">
<?php echo $model->isNewRecord ? 'Crear' : 'Guardar'; ?>                      

                    </button>
<?php 
  echo CHtml::link('cancelar', Yii::app()->createUrl('UserSystem/admin'),
                    array('class'=>'btn btn-raised btn-primary m-t-15 waves-effect')); 
?>

                  <?php $this->endWidget(); ?>
              </div>
          </div>
      </div>
  </div>
</div>

<script type="text/javascript">
  var taskOperationList;  
  $(document).ready(function(){
    taskOperationList=JSON.parse('<?php echo $taskOperationList; ?>');
    $('#UserSystem_role').change();
  });

  function showOperations(caller){
    var operations=taskOperationList[caller.value+'Tarea'];
    var strOperations='';

    for (var i = 0; i < operations.length; i++) {
      if(i+1==operations.length){
        strOperations+=operations[i];
      }else{
        strOperations+=operations[i]+'-';
      }
    }
    $('#divoperations').html(strOperations);
  }
</script>