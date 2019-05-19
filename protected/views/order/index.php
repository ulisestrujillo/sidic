<?php
/* @var $this SupplierController */
/* @var $model Supplier */
/* @var $form CActiveForm */
?>
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'Budget',
  'enableAjaxValidation'=>false,
  'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

<div class="block-header">
  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3">
      <h2>Ordenes de compra de la partida: </h2>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-9">
      <button id="btnPorPagar" type="button" class="btn btn-raised btn-primary m-t-15 waves-effect">Por pagar</button>
      <button id="btnPorSurtir" type="button" class="btn btn-raised btn-primary m-t-15 waves-effect">Por surtir</button>
      <button id="btnPorTodas" type="button" class="btn btn-raised btn-primary m-t-15 waves-effect">Todas</button>
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

<div class="row">

            <table id="tblPartidas" class="table table-hover">
            <tr id="row_0">
                <th style="display:none;">id</th>
                <th style="display:none;">parentid</th>
                <th>partida</th>
                <th>subpartida</th>
                <th>oc</th>
                <th>tope</th>
                <th></th>
            </tr>
              <?php 
              foreach ($ordenes as $key => $order) {
                $id=$res["id"];
                $parentid=$res["parentid"];

                echo "<tr data-type=\"$type\" id=\"row_$key\" onclick=\"selecRow('row_$key', $directparentid, '$type');\" >";
                echo "<td>";
                echo $partida;
                echo "  <input type=\"hidden\" name=\"Budget[$key][id]\" value=\"$id\">";
                echo "  <input type=\"hidden\" name=\"Budget[$key][parentid]\" value=\"$directparentid\">";
                echo "</td>";
                echo "<td><input type=\"hidden\" name=\"Budget[$key][name]\" value=\"$name\" />$subpartida</td>";
                echo "<td>$oc</td>";
                echo "<td><input type=\"hidden\" name=\"Budget[$key][budgettop]\" value=\"$tope\"/>$tope</td>";
                echo "<td><span class='hover' onclick='removeItem($id, this)'>x</span></td>";
                echo "</tr>";
              }
              ?>

              </table>

</div>

<?php $this->endWidget(); ?>
              </div>
          </div>
      </div>
  </div>
</div>

<script>
</script>

<style>
  .hover{cursor:pointer;}
</style>