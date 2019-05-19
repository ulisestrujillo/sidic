
<script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jqgrid/js/jquery.jqGrid.min.js"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jqgrid/js/i18n/grid.locale-es.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jqgrid/css/ui.jqgrid.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jqgrid/src/css/ui.multiselect.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="https://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jqgrid/css/ui.jqgrid.css" /> 

<ol class="breadcrumb">
  <li><a href="<?php echo Yii::app()->createUrl('/budget/list', array("id"=>$projectId)); ?>">Volver</a></li>
</ol>

<div class="row">
  <div class="col-lg-12 col-md-6 col-sm-12">
    <h3 style="margin-top:0px;">Reporte de órdenes de compra del presupuesto: <?php echo CHtml::decode($budgetName); ?></h3>
  </div>
</div>

<table id="jqGrid"></table>
<div id="jqGridPager"></div>

<script type="text/javascript"> 
    
$(document).ready(function () {
//"page":1,"total":3,"records":20,"rows":

    var _url='<?php echo Yii::app()->createUrl('order/getOrders')."&projectId=".$_GET["projectId"]."&id=".$_GET["id"]."&page=1"; ?>';

    $("#jqGrid").jqGrid({
      url: _url,
      mtype: "GET",
      datatype: "json",
      page: 1,
      colNames: ['Folio', 'Fecha', 'Proveedor', 'Tipo', 'Partida', 'Subpartida', 'Estatus', 'Ver OC'],
      colModel: [
        { name: 'Folio', key: true, width: 40 },
        { 
          name: 'Fecha', 
          width: 75,
          sorttype:'date',
          //formatter: 'date',
          srcformat: 'Y-m-d',
          newformat: 'Y-m-d',
          searchoptions: {
            dataInit: function (element) {
              $(element).datepicker({
                id: 'orderDate_datePicker',
                dateFormat: 'yy-mm-dd',
                maxDate: new Date(2020, 0, 1),
                showOn: 'focus'
              });
            }
          }
        },
        { name: 'Proveedor', key: true, width: 200 },
        { name: 'Tipo', key: true, width: 50 },
        { name: 'Partida', key: true, width: 50 },
        { name: 'Subpartida', key: true, width: 95 },
        { name: 'Estatus', key: true, width: 50 },
        { name: 'Url', key: true, width: 50 },
      ],
      loadonce: true,
      width: 1000,
      height: 700,
      rowNum: 50,
      userDataOnFooter: true,
      pager: "#jqGridPager"
    });

    $('#jqGrid').jqGrid('filterToolbar');

    $('#jqGrid').jqGrid('navGrid',"#jqGridPager", {                
      search: false, // show search button on the toolbar
      add: false,
      edit: false,
      del: false,
      refresh: false
    })/*.navButtonAdd('#jqGridPager', {
      caption: "<strong>Exportar</strong>",
      buttonicon: "ui-icon-plusthick",
      title: 'Exportar',
      onClickButton: function() {
        $("#jqGrid").jqGrid("exportToCsv",{
          separator: ",",
          separatorReplace : "", // in order to interpret numbers
          quote : '"', 
          escquote : '"', 
          newLine : "\r\n", // navigator.userAgent.match(/Windows/) ? '\r\n' : '\n';
          replaceNewLine : " ",
          includeCaption : true,
          includeLabels : true,
          includeGroupHeader : true,
          includeFooter: true,
          fileName : "jqGridExport.csv",
          returnAsString : false
        })
      }
    });*/

});

</script>


