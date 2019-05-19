<?php 
$access=Array();
$access=User::getPrivileges();
?>

<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'Budget',
  'enableAjaxValidation'=>false,
  'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>
<title>SIDIC - Presupuesto: <?php echo $budgetName; ?></title>
<div class="msg"></div>
<ol class="breadcrumb">
  <li class="active"><a href="<?php echo Yii::app()->createUrl('/budget/list',array('id'=>$project->id)); ?>">Regresar a presupuestos</a></li>
</ol>
<div class="block-header">
  <div class="row">
    <div class="col-lg-12 col-md-6 col-sm-12">
      <?php if(Yii::app()->user->hasFlash("MissingTemplateId")): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Información:&nbsp;</strong><?php echo Yii::app()->user->getFlash("MissingTemplateId"); ?>
        </div>
      <?php endif; ?>
      <?php if(Yii::app()->user->hasFlash("budgetTopOverpassItems")): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Información:&nbsp;</strong><?php echo Yii::app()->user->getFlash("budgetTopOverpassItems"); ?>
        </div>
      <?php endif; ?>

      <?php if(Yii::app()->user->hasFlash("ZeroBudgetItems")): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Información:&nbsp;</strong><?php echo Yii::app()->user->getFlash("ZeroBudgetItems"); ?>
        </div>
      <?php endif; ?>

      <?php if(Yii::app()->user->hasFlash("delete_message")): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Información:&nbsp;</strong><?php echo Yii::app()->user->getFlash("delete_message"); ?>
        </div>
      <?php endif; ?>
    
      <h3 style="margin-top:0px;">Presupuesto: <?php echo $budgetName; ?></h3>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-9">
      <!--<button type="button" class="btn btn-raised btn-primary m-t-15 waves-effect">Cargar Plantilla</button>-->
      <?php if($access[User::OP_CREAR_PARTIDA]): ?>
            <button id="btnAddPartida" type="button" class="btn btn-raised btn-primary m-t-15 waves-effect">(+) Partida</button>
      <?php endif; ?>
      <?php if($access[User::OP_CREAR_SUBPARTIDA]): ?>
            <button id="btnAddSubPartida" type="button" class="btn btn-raised btn-primary m-t-15 waves-effect">(+) SubPartida</button>
      <?php endif; ?>
      <?php if($access[User::OP_CREAR_ORDEN]): ?>
            <button type="button" class="btn btn-raised btn-primary m-t-15 waves-effect" onclick="createOrder();">Agregar O. Compra</button>
      <?php endif; ?>
      <?php if($access[User::OP_GUARDAR_PRESUPUESTO]): ?>
            <button type="submit" class="btn btn-raised btn-success m-t-15 waves-effect" onclick="return valida();" >Guardar</button>
      <?php endif; ?>
            <a class="btn btn-raised btn-default m-t-15 waves-effect" href="<?php echo Yii::app()->createUrl('/order/listOrdersByBudgetId', array("projectId"=>$project->id, "id"=>$budgetId, "name"=>CHtml::encode($budgetName))); ?>"; >Ver ordenes de compra</a>
      <?php if($access[User::OP_CREAR_PARTIDA]): ?>
<?php if(count($partidas)==0): ?>
            <button id="btnAddPlantilla" onclick="ShowHidePanelPlantilla(); return false;"
              type="button" class="btn btn-default m-t-15 waves-effect">Plantillas</button>
<?php endif; ?>              
      <?php endif; ?>
    </div>
  </div>

<br/>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <?php $url = Yii::app()->createUrl("order/reportegeneral", array("projectid"=>$project->id, "budgetid"=>$budgetId)); ?>
    <a href="<?php echo $url; ?>" class="btn btn-raised btn-primary m-t-15 waves-effect btn-sm">
    Reporte General</a>
    <?php $urloc = Yii::app()->createUrl("order/reporteocporautorizar", array("projectid"=>$project->id, "budgetid"=>$budgetId)); ?>
    <a href="<?php echo $urloc; ?>" class="btn btn-raised btn-primary m-t-15 waves-effect btn-sm">
    Ordenes por autorizar</a>
    <?php 
      $url = Yii::app()->createUrl("order/ocautorizadas", array("projectid"=>$project->id, "budgetid"=>$budgetId));
    ?>
    <a href="<?php echo $url; ?>" class="btn btn-raised btn-primary m-t-15 waves-effect btn-sm">
    Ordenes autorizadas</a>
    <a href="<?php echo Yii::app()->createUrl("order/reporteocrecibidas", array("projectid"=>$project->id, "budgetid"=>$budgetId)); ?>" class="btn btn-raised btn-primary m-t-15 waves-effect btn-sm">
    Ordenes recibidas</a>
    <a href="<?php echo Yii::app()->createUrl("order/reporteocporpagar", array("projectid"=>$project->id, "budgetid"=>$budgetId)); ?>" class="btn btn-raised btn-primary m-t-15 waves-effect btn-sm">
    Ordenes por pagar</a>
    <a href="<?php echo Yii::app()->createUrl("order/reporteocpagadas", array("projectid"=>$project->id, "budgetid"=>$budgetId)); ?>" class="btn btn-raised btn-primary m-t-15 waves-effect btn-sm">
    Ordenes pagadas</a>    
  </div>
</div>


<?php if(count($partidas)==0): ?>
  <script type="text/javascript">
    function ShowHidePanelPlantilla(){
      if($("div#panelPlantilla")[0].style.display=='none')
        $("div#panelPlantilla").fadeIn(800);
      else
        $("div#panelPlantilla").fadeOut(800);
    }
    function LoadThisTemplate(){

      bootbox.confirm("Desea cargar esta plantilla", 
        function(result){
          if(result){
            document.forms.Budget.submit();
          }
        }
      );
    }
  </script>
  <div class="row" id="panelPlantilla" style="display:none;">
    <br/>
    <div class="col-lg-9 col-md-9 col-sm-9">
      <fieldset>
        <legend>Seleccione la plantilla que desee cargar</legend>
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-4">
            <select name="Template[templateId]" class="form-control">
              <option value="0">Seleccione plantilla</option>
              <option value="1">Prototipo Vivienda</option>
              <option value="2">Vivienda 1 Nivel</option>
              <option value="3">Vivienda 3 Niveles</option>
              <option value="4">Edificio de departamentos 6 departamentos</option>
            </select>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4">
            <button type="button" onclick="LoadThisTemplate();" class="btn btn-primary btn-sm">Cargar esta plantilla</button>
            <button type="button" class="btn btn-default btn-sm" onclick="ShowHidePanelPlantilla(); return false;">Cerrar</button>
          </div>
        </div>
      </fieldset>
    </div>
  </div>
<?php endif; ?>  
</div>

<div class="container-fluid">
  <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="card">
              <div class="header">
                  <h2></h2>
              </div>
              <div class="body">

<?php CHtml::$errorContainerTag = 'label'; ?>

<div class="row">

<table id="tblPartidas" class="table table-hover" cellspacing="0" cellpadding="0" >
<tr id="row_0">
    <th style="display:none;">id</th>
    <th style="display:none;">parentid</th>
    <td>&nbsp;</td>
    <th>partida</th>
    <th>subpartida</th>
    <th>oc</th>
    <th>tope</th>
    <th></th>
</tr>
  <?php 

  foreach ($partidas as $key => $res) {
    $id=$res["id"];//budgetid
    $hiddenId = "Budget_".$key."_budgettop";
    $txtTope = "txtTope_".$key;
    $parentid=$res["parentid"];
    $directparentid=$res["realparentid"];
    $partida=$res["partida"];
    $subpartida=$res["subpartida"];
    $oc=$res["oc"];
    $tope=$res["tope"];
    $type=$res["type"];
    $name=$res["name"];
    $active=$res["active"];
    $color=$active==0 ? "#f15f7924":"transparent";
    $redColor="";

    foreach ($arrayBudgetTopOverPassList as $item) {
      if($item["id"]==$id){
        $tope=$item["total"];
        $redColor=" border-color:red; ";
      }
    }

    if(array_search($id, $arrayBudgetTopOverPassList) !== FALSE){
    }

    echo "<tr draggable='true' budgetid=\"$id\" parentid=\"$parentid\" class=\"active$active\" style=\"background-color:$color\" data-type=\"$type\" id=\"row_$key\" onclick=\"selecRow('row_$key', $directparentid, '$type', $active, $id);\" >";
    echo "<td><span><i class='fas fa-sort'></i></span></td>";
    echo "<td style='width:23.83%'>";
    echo $partida;
    echo "  <input type=\"hidden\" name=\"Budget[$key][id]\" value=\"$id\">";
    echo "  <input type=\"hidden\" name=\"Budget[$key][parentid]\" value=\"$directparentid\">";
    echo "</td>";
    echo "<td style='width:31.17%'><input type=\"hidden\" name=\"Budget[$key][name]\" value=\"$name\" />$subpartida</td>";
    echo "<td style='width:22.8%'>$oc</td>";
    echo "<td style='width:18.87%'><input type=\"hidden\" id=\"$hiddenId\" name=\"Budget[$key][budgettop]\" value=\"$tope\"/><input id=\"$txtTope\" class=\"txtTope\" onkeyup=\"setNewTope(this, '$hiddenId');\" onblur=\"turnDisabled('$txtTope');\" style=\"$redColor width:100px;\" type=\"text\" disabled=\"disabled\" value=\"$tope\" />";

    if($access[User::OP_EDITAR_TOPE]){
      echo "<button onclick=\"enableEditTope('$hiddenId', '$txtTope'); return false; \" class=\"btn btn-primary btn-xs\" aria-label=\"Left Align\" title=\"Editar tope\"><span class=\"glyphicon glyphicon-edit\" aria-hidden=\"true\"></span></button>";
    }
    echo "</td>";

    if($access[User::OP_ELIMINAR_PARTIDA]){
      if($active==1){
        echo "<td style='width:3.33%'><span class='hover glyphicon glyphicon-remove-circle' onclick=\"removeItem($id)\"></span></td>";
      }
    }else{
      echo "<td style='width:3.33%'></td>";
    }
    echo "</tr>";

}//foreach
  ?>

  </table>

</div>

<?php $this->endWidget(); ?>
              </div>
          </div>
      </div>
  </div>
</div>

<input type="hidden" id="hiddenBudgetItemId" value="" />

<script>
  function selecRow(rid, parentid, type, active, budgetitemid){
    if(active==1){
      $("#hiddenBudgetItemId").val(budgetitemid);
      localStorage.setItem('selectedRow', rid);
      localStorage.setItem('parentid', parentid);
      $('#tblPartidas tr.active1').css('background-color','transparent');
      $("#"+rid).css('background-color','lightblue');
    }
  }

  $(document).ready(function(){
    localStorage.setItem('rowIdentity', <?php echo $maxid; ?>);//es el id de las partidas subpartidas que el usuario va a gregando dinamicamente por medio del boton "agregar partida"
    localStorage.setItem("selectedRow", "undefined");//se reinicia esta variable al recargar la pagina

    //si el renglon seleccionado es una partida le debemos insertar
    //una subpartida, pero si es una subpartida se le debe insertar igualmente
    //una supartida
    $("#btnAddSubPartida").click(function(){
      var _partida="";
      var _subpartida="";
      var _parentid=localStorage.getItem('parentid');

      if(localStorage.getItem('rowIdentity') == null || localStorage.getItem('rowIdentity') == "undefined"){
        localStorage.setItem('rowIdentity', 0);
      }else{
        localStorage.setItem('rowIdentity', parseInt(localStorage.getItem('rowIdentity')) + 1);
      }

      _rowIdentity = localStorage.getItem('rowIdentity');

      if(localStorage.getItem("selectedRow")!="undefined"){
        var selected=$("#"+localStorage.getItem("selectedRow"));
        var rowId=localStorage.getItem("selectedRow");

        _partida = "<tr parentid="+_parentid+">";
        _partida += "<td style=\"display:none;\"></td>";
        _partida += "<td><span onclick=\"this.parentElement.parentElement.remove();\">X<i class=\"fa fa-fw fa-remove\"></i></span></td>";
        _partida += "<td>"
        _partida += "<input name=\"Budget["+_rowIdentity+"][id]\" value=\"0\" id=\"Budget_id\" type=\"hidden\" />";
        _partida += "<input name=\"Budget["+_rowIdentity+"][parentid]\" value="+_parentid+" id=\"Budget_parentid\" type=\"hidden\" />";
        _partida += "<input name=\"Budget["+_rowIdentity+"][budgettop]\" value=\"0\" id=\"Budget_budgettop\" type=\"hidden\" />";
        _partida += "<input name=\"Budget["+_rowIdentity+"][name]\" value=\"\" id=\"Budget_name\" style=\"width:150px;\" type=\"text\" class=\"border-input-text\" />";
        _partida += "</td>";
        _partida += "<td><a href=\"<?php echo Yii::app()->createUrl('/order') ?>\">ver o.c.</a></td>";
        _partida += "<td></td>";
        _partida += "</tr>";

        $(_partida).insertAfter("#"+rowId);
      }else{
        alert("Primero seleccione una partida subpartida.");
      }
    });

    $("#btnAddPartida").click(function(){
      var _partida="";
      _rowIdentity = localStorage.getItem('rowIdentity');

      _parentid = 0;

      _partida = "<tr ondrag=\"handleDragStart(event);\">";
      _partida += "<td style=\"display:none;\"></td>";
      _partida += "<td style=\"display:none;\"></td>";
      _partida += "<td><span onclick=\"this.parentElement.parentElement.remove();\">X<i class=\"fa fa-fw fa-remove\"></i></span></td>";
      _partida += "<td>"
      _partida += "<input name=\"Budget["+_rowIdentity+"][id]\" value=\"0\" id=\"Budget_id\" type=\"hidden\" />";
      _partida += "<input name=\"Budget["+_rowIdentity+"][parentid]\" value="+_parentid+" id=\"Budget_parentid\" type=\"hidden\" />";
      _partida += "<input name=\"Budget["+_rowIdentity+"][budgettop]\" value=\"0\" id=\"Budget_budgettop\" type=\"hidden\" />";
      _partida += "<input name=\"Budget["+_rowIdentity+"][name]\" value=\"\" id=\"Budget_name\" style=\"width:150px;\" type=\"text\" class=\"budget-input-text\" />";
      _partida += "</td>";
      _partida += "<td><a href=\"<?php echo Yii::app()->createUrl('/order') ?>\">ver o.c.</a></td>";
      _partida += "<td></td>";
      _partida += "</tr>";

      totRows = $("#tblPartidas tr").length;
      if(totRows==1){
        $(_partida).insertAfter("#row_0");
      }else{
        $(_partida).insertAfter("#row_"+(totRows-1));
      }

    });
  });

  function valida(){
    return true; //confirm("Desea guardar el presupuesto ?");
  }

  function createOrder(){
    if($("#hiddenBudgetItemId").val()==""){
      bootbox.alert("Para agregar una orden de compra debe seleccionar la partida o subpartida.");
      return;
    }
    var budgetitemid=$("#hiddenBudgetItemId").val();/*partidaid*/
    var xurl="<?php echo Yii::app()->createUrl('/order/create') ?>";
    var params="&id="+budgetitemid+"&projectid=<?php echo $project->id; ?>&budgetid=<?php echo $budgetId; ?>";

    window.location = xurl+params;
  }

  function removeItem(_id){
    //check it has subpartidas via ajax
    //param id es el id de la partida subpartida
    var budgetid=<?php echo $budgetId; ?>;
    if(confirm("Desea remover la partida / subpartida ?")==true){
      var url="<?php echo Yii::app()->createUrl('budget/delete'); ?>&id="+_id+"&budgetid="+budgetid;
      window.location=url;
    }
  }

  function viewOrders(_budgetItemId){
    if(_budgetItemId==0){
      if(confirm("No existen ordenes para está partida, desea crear una ?"))
        createOrder();
    }else{
      var url="<?php echo Yii::app()->createUrl('/order/budget'); ?>";
      url = url + '&id=' + _budgetItemId;
      window.location=url;
    }

  }

  function setNewTope(newTope, hdnTope){
    //asignar el nuevo tope al hidden que viaja hasta el controller
    $('#'+hdnTope).val( newTope.value );
  }

  function enableEditTope(hdnTope, txtNewTope){
    $('.txtTope').attr("disabled", "disabled");
    $('#'+txtNewTope).removeAttr("disabled");
  }

  function turnDisabled(obj){
    $('#'+obj).attr("disabled", "disabled");
  }

</script>

<style>
  .hover{cursor:pointer;}
  .budget-input-text{border:1px solid; border-radius: 3px;}
  [draggable] {
    -moz-user-select: none;
    -khtml-user-select: none;
    -webkit-user-select: none;
    user-select: none;
    /* Required to make elements draggable in old WebKit */
    -khtml-user-drag: element;
    -webkit-user-drag: element;
  }
  table#tblPartidas tr td{cursor: move;}
  .over{border: 2px dashed #000;}
  fieldset{
    border: 1px solid #ddd !important;
    margin: 0;
    xmin-width: 0;
    padding: 10px;       
    position: relative;
    border-radius:4px;
    padding-left:10px!important;
  } 
  legend{
    font-size:12px;
    margin-bottom: 0px;
    width: 35%; 
    border: 1px solid #ddd;
    border-radius: 4px; 
    padding: 5px 5px 5px 10px; 
    background-color: #ffffff;
  }
  .block{cursor:no-drop; !important;}
</style>

<script src="<?php echo Yii::app()->theme->baseUrl;?>\assets\bootbox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script type="text/javascript">
  var dragSrcEl = null;
  var docFrag;
  var arrPadreEHijos;

  function handleDragStart(e) {
    this.style.opacity = '0.7'; // this / e.target is the source node.

    dragSrcEl = this;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('dataType', this.dataset.type);
    e.dataTransfer.setData('budgetId', this.attributes["budgetid"].value);
    e.dataTransfer.setData('parentId', this.attributes["parentid"].value);
    e.dataTransfer.setData('parentId', this.attributes["parentid"].value);
    e.dataTransfer.setData('carryingGroupOfItems', false);

    var origenEsPartidaConHijos=dragSrcEl.dataset.type=='PARTIDA'
        && $('#tblPartidas tr[parentid*='+dragSrcEl.attributes["budgetid"].value+']').length>0
        && dragSrcEl.attributes["budgetid"].value!=0;

    if(origenEsPartidaConHijos){
      e.dataTransfer.setData('carryingGroupOfItems', true);
      arrPadreEHijos=Array();
      arrPadreEHijos[arrPadreEHijos.length]=this;
      rows=$('#tblPartidas tr[parentid*='+this.attributes["budgetid"].value+']');
      
      for (var i = 0; i < rows.length; i++) {
        arrPadreEHijos[arrPadreEHijos.length]=rows[i];
      }
    }
  }

  function handleDragOver(e) {
    if(dragSrcEl.dataset.type=='PARTIDA'
      && this.dataset.type=='SUBPARTIDA'){
      this.style.backgroundColor='#f2dede';
      return false;
    }else{
      this.style.backgroundColor='#dff0d8';
    }

    /*
      no se puede cambiar de padre a una subpartida
    */
    var origenEsSubPartida=dragSrcEl.dataset.type=='SUBPARTIDA';
    var seCambiaDePadre=this.attributes["parentid"].value!=dragSrcEl.attributes["parentid"].value;

    if(origenEsSubPartida && seCambiaDePadre){
      this.style.backgroundColor='#f2dede';
      return false;
    }    

    if (e.preventDefault) {
      e.preventDefault(); // Necessary. Allows us to drop.
    }
    e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
    return false;
  }

  function handleDragEnter(e) {
    // this / e.target is the current hover target.
    this.classList.add('over');

/*TODO: validar que al arrastrar a una region no valida se muestre un cursor de error*/
    var origenEsPartidaConHijos=dragSrcEl.dataset.type=='PARTIDA'
        && $('#tblPartidas tr[parentid*='+dragSrcEl.attributes["budgetid"].value+']').length>0
        && dragSrcEl.attributes["budgetid"].value!=0;
    var destinoEsPartidaSinHijos=this.dataset.type=='PARTIDA'
        && $('#tblPartidas tr[parentid*='+this.attributes["budgetid"].value+']').length==0
        && this.attributes["budgetid"].value!=0;

    if(origenEsPartidaConHijos && destinoEsPartidaSinHijos){
      return false;
    }    
  }

  function handleDragLeave(e) {
    this.style.backgroundColor='transparent';
    this.classList.remove('over');  // this / e.target is previous target element.
  }

  function handleDrop(e) {
    this.style.backgroundColor='transparent';

    /*
      valida si se está arrastrando una partida con hijos
    */
    // this / e.target is current target element.
    if (e.stopPropagation) {
      e.stopPropagation(); // stops the browser from redirecting.
    }

    var carryingGroupOfItems=e.dataTransfer.getData('carryingGroupOfItems');
    var origenEsPartida=e.dataTransfer.getData('dataType')=='PARTIDA';
    var destinoEsPartidaConHijos=
       this.dataset.type=='PARTIDA' &&
       $('#tblPartidas tr[parentid*='+this.attributes["budgetid"].value+']').length>0;

    if(carryingGroupOfItems=="true"){
      docFrag=document.createDocumentFragment();
      for (var i = 0; i < arrPadreEHijos.length; i++) {
        docFrag.appendChild(arrPadreEHijos[i]);
      }

      if(mouseYDirection=='up'){
        $('#'+this.id).before(docFrag);
      }else{
        var lastChild;
        if(origenEsPartida && destinoEsPartidaConHijos){
          lastChild=$('#tblPartidas tr[parentid*='+this.attributes["budgetid"].value+']');
          $('#'+lastChild[lastChild.length-1].id).after(docFrag);
        }else{
          $('#'+this.id).after(docFrag);
        }
      }
    }else{
      docFrag=document.createDocumentFragment();
      docFrag.appendChild(dragSrcEl);

      if(mouseYDirection=='up'){
        $('#'+this.id).before(docFrag);
      }else{
        if(origenEsPartida && destinoEsPartidaConHijos){
          var lastChild;
          lastChild=$('#tblPartidas tr[parentid*='+this.attributes["budgetid"].value+']');

          $('#'+lastChild[lastChild.length-1].id).after(docFrag);
        }else{
          $('#'+this.id).after(docFrag);
        }
      }
    }

    arrPadreEHijos=undefined;
    docFrag=undefined;

    return false;
  }

  function handleDragEnd(e) {
    // this/e.target is the source node.
    $('#tblPartidas tr').each(function(){
      this.classList.remove('over');
      this.style.opacity = '1';
    });
  }

  $(document).ready(function(){
    if (!Modernizr.draganddrop) {
    }  

    $('#tblPartidas tr td').css({'padding-top':'1px','padding-bottom':'1px'});
    
    var rows=document.querySelector('#tblPartidas').getElementsByTagName("tr");
    for(i=0; i<rows.length; i++){
      rows[i].addEventListener('dragstart', handleDragStart, false);
      rows[i].addEventListener('dragover', handleDragOver, false);
      rows[i].addEventListener('dragenter', handleDragEnter, false);
      rows[i].addEventListener('dragleave', handleDragLeave, false);
      rows[i].addEventListener('drop', handleDrop, false);
      rows[i].addEventListener('dragend', handleDragEnd, false);
    }
  });

  var bodyElement = document.querySelector("body");
  bodyElement.addEventListener("mousemove", getMouseDirection, false);
   
  var mouseXDirection = "";
  var mouseYDirection = "";
  var oldX = 0;
  var oldY = 0;
   
  function getMouseDirection(e) {
    if (oldX < e.pageX) {
        mouseXDirection = "right";
    } else {
        mouseXDirection = "left";
    }
 
    //deal with the vertical case
    if (oldY < e.pageY) {
        mouseYDirection = "down";
    } else {
        mouseYDirection = "up";
    }
 
    oldX = e.pageX;
    oldY = e.pageY;
  }  

</script>
