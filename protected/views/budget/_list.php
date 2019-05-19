<?php
/* @var $this SupplierController */
/* @var $model budget */
/* @var $form CActiveForm */

$priveleges=User::getPrivileges();
?>
<title>SIDIC - Presupuestos del proyecto: <?php echo $project->name; ?></title>
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<ol class="breadcrumb">
  <li class="active"><a href="<?php echo Yii::app()->createUrl('/project/admin'); ?>">Regresar a proyectos</a></li>
</ol>
<div class="block-header">
  <div class="row">
    <div class="col-lg-12 col-md-6 col-sm-12">
      <h3 style="margin:0px;">Presupuestos del proyecto: <?php echo $project->code.' '.$project->name; ?></h3>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <?php if($priveleges[User::OP_CREAR_PRESUPUESTO]): ?>
    <?php   echo $this->renderPartial('/budget/create', array('project'=>$project, 'model'=>$model)); ?>
    <?php endif; ?>
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

<div class="row">

<table id="tblPartidas" class="table table-hover">
<tr id="row_0">
    <th>Nombre</th>
    <th>Fecha creación</th>
    <th>O. de compra</th>
    <th></th>
</tr>
  <?php 
  $show=true;
  foreach ($budgets as $pre) {
    $show=true;
    foreach ($budgetAccessList as $budget) {
      if($pre->id==$budget["id"]){
        $show=false;
        break;
      }
    }
  
    if($show==true){
      $created=$pre->created;
      $name=$pre->name;
      $url=Yii::app()->createUrl('budget', array('id'=>$pre->id));
      $urlToReport = Yii::app()->createUrl('/order/listOrdersByBudgetId', array('projectId'=>$project->id, 'id'=>$pre->id, 'name'=>CHtml::encode($name)));

      echo "</tr>";
      echo "<td>$name</td>";
      echo "<td>$created</td>";
      echo "<td><a href=\"$urlToReport\">Ver Órdenes</a></td>";

      if($priveleges[User::OP_VER_PRESUPUESTO] || User::checkRole('contralor') || User::checkRole('autorizador'))
      {
          echo "<td><a href=\"$url\">Ver</a></td>";
      }
          echo "</tr>";
    }

  }
  ?>

  </table>

</div>

              </div>
          </div>
      </div>
  </div>
</div>


<style>
  .hover{cursor:pointer;}
</style>

