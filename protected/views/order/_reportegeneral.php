<!-- http://trirand.com/blog/jqgrid/jqgrid.html -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jqgrid/js/jquery.jqGrid.min.js"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jqgrid/js/i18n/grid.locale-es.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jqgrid/css/ui.jqgrid.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jqgrid/src/css/ui.multiselect.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="https://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->theme->baseUrl; ?>/vendor/jqgrid/css/ui.jqgrid.css" /> 

<ol class="breadcrumb">
  <li><a href="<?php echo Yii::app()->createUrl('/budget', array("id"=>$budgetid)); ?>">Volver al presupuesto</a></li>
</ol>

<div class="row">
  <div class="col-lg-12 col-md-6 col-sm-12">
    <h2>Reporte General de oc</h2>
  </div>
</div>

<div class="row">
  <div class="col-lg-12 col-md-6 col-sm-12">
    <span class="spantext">Proyecto: <?php echo $project->code; ?></span>
    <span class="spantext textright">Fecha: <?php echo $ydate; ?></span>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 col-md-6 col-sm-12">
    <span class="spantext">Presupuesto: <?php echo $budgetname; ?></span>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 col-md-6 col-sm-12">
    <span class="spantext">Plaza: <?php echo $project->location; ?></span>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 col-md-6 col-sm-12">
    <span class="spantext">Direccion: <?php echo $project->address; ?></span>
    <span class="spantext textright">Estatus del presupuesto: <?php echo $budgetestatus; ?></span>
  </div>
</div>

<table id="jqGrid"></table>
<div id="jqGridPager"></div>

<script type="text/javascript"> 
    
$(document).ready(function () {
//"page":1,"total":3,"records":20,"rows":

    var _url='<?php echo Yii::app()->createUrl('order/reportegeneraldata')."&projectid=".$project->id."&budgetid=".$budgetid."&page=1"; ?>';
    var opt={decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$"};

    $("#jqGrid").jqGrid({
      url: _url,
      mtype: "GET",
      datatype: "json",
      page: 1,
      colNames: ['Folio', 'Partida', 'Subpartida', 'Proveedor', 'Estatus', 'Tope Presupuesto'],

      colModel: [
        { name: 'Folio', key: false, width: 20 },
        { name: 'Partida', key: false, width: 40 },
        { name: 'Subpartida', key: false, width: 40 },
        { name: 'Proveedor', key: false, width: 40 },
        { name: 'Estatus', key: false, width: 25 },
        { name: 'TopePresupuesto', key: false, width: 50, align: 'right',
					formatter:'currency', formatoptions: opt
        },
      ],
      loadonce: true,
      autowidth:true,
      height: 600,
      rowNum: 50,
      userDataOnFooter: true,
      footerrow: true,
      pager: "#jqGridPager",      
      gridComplete: function() {
        var $grid = $('#jqGrid');
        var colSumTope = $grid.jqGrid('getCol', 'TopePresupuesto', false, 'sum');
        $grid.jqGrid('footerData', 'set', { TopePresupuesto: colSumTope });
    }
    });

    $('#jqGrid').jqGrid('filterToolbar');

    $('#jqGrid').jqGrid('navGrid',"#jqGridPager", {                
      search: false, // show search button on the toolbar
      add: false,
      edit: false,
      del: false,
      refresh: false
    }).navButtonAdd('#jqGridPager', {
      caption: "<strong>Imprimir</strong>",
      buttonicon: "ui-icon-plusthick",
      title: 'Imprimir',
      onClickButton: function() {
        window.open("<?php echo Yii::app()->createUrl('order/reportegeneralpdf')."&projectid=".$project->id."&budgetid=".$budgetid."&page=1"; ?>","_blank");
      }
    });/*.navButtonAdd('#jqGridPager', {
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
<style>
  .textright{float: right;}
  .spantext{font-size: 16px;margin-bottom: 5px;}
</style>


