<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>

<title>SIDIC - &Oacute;rdenes de compra partida: <?php echo $model->name; ?></title>

<ol class="breadcrumb">
  <li class="active"><a href="<?php echo Yii::app()->createUrl('budget',array('id'=>$budgetid) ); ?>">Regresar a presupuesto</a></li>
</ol>      
<div class="block-header">
  <div class="row">
    <div class="col-lg-12">
      <h3 style="margin:0px;">Ordenes de compra de la partida: <?php echo $model->name; ?></h3>
    </div>
  </div>
  <div class="row">
    <!--<div class="col-lg-9 col-md-9 col-sm-9">
      <button id="btnPorPagar" type="button" class="btn btn-raised btn-primary m-t-15 waves-effect">Por pagar</button>
      <button id="btnPorSurtir" type="button" class="btn btn-raised btn-primary m-t-15 waves-effect">Por surtir</button>
      <button id="btnPorTodas" type="button" class="btn btn-raised btn-primary m-t-15 waves-effect">Todas</button>
    </div>-->
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
                <th>Folio</th>
                <th>Fecha creaci&oacute;n</th>
                <th>Fecha inicio</th>
                <th>Dias por surtir</th>
                <th>Proveedor</th>
                <th>Tipo</th>
                <th>Estatus</th>
                <th>Detalle</th>
            </tr>
<?php 

foreach ($orders as $key => $order) {
  //if($order->statusid!=$order::STATUS_CANCELADA && $order->statusid!=$order::STATUS_COLOCADA){
    $folio=$order->id;
    $fechacreacion=$order->created;
    $fechainicio=$order->initdate == null ? "Sin iniciar" : date('Y-m-d h:i a',$order->initdate);
    $diasrestantes=$order->supplydayleft;
    $proveedor=$order->supplier["name"];
    $tipo=$order->ordertype;
    $estatus=$order->orderstatus->name;

    $url=Yii::app()->createUrl('order/update', array(
                                                "id"=>$model->id,
                                                "projectid"=>$model->budget->project->id,
                                                "budgetid"=>$model->budgetid,
                                                "orderid"=>$order->id
                                              ));
    echo "<tr>";
    echo "<td>$folio</td>";
    echo "<td>$fechacreacion</td>";
    echo "<td>$fechainicio</td>";
    echo "<td>$diasrestantes</td>";
    echo "<td>$proveedor</td>";
    echo "<td>$tipo</td>";
    echo "<td>$estatus</td>";
    echo "<td><a href='".$url."'>Ver</a></td>";
    echo "</tr>";




  //}
}
?>

              </table>

</div>

              </div>
          </div>
      </div>
  </div>
<?php if(sizeof($model->orders)==0){ ?>
  <h2>Esta partida no tiene ordenes a&uacute;n</h2>
<?php  }?>
</div>



<style>
  .hover{cursor:pointer;}
</style>