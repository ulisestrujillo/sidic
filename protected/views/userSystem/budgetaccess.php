<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>

<div class="row">
  <div class="col-lg-12">
    <a href="#" onclick="save();" class="btn btn-raised btn-primary m-t-15 waves-effect btn-sm">Guardar</a>
  </div>

  <div class="col-md-12">
    <h3 style="margin:0px;">Acceso a presupuestos del usuario: <?php echo strtoupper($username); ?></h3>
    <small>Para ver los presupuestos, seleccione el proyecto del lado izquierdo.<br/>
    Para bloquear el acceso a los presupuestos, active la casilla de cada presupuesto.</small>

<div class="container-fluid">
  <div class="row">
    <!-- PROYECTOS  -->
    <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12">
      <h5><b>Proyectos:</b></h5>
      <div>
        <div class="row projectrow">
          <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="item">Nombre<br></div>
          </div>
        </div>        
      </div>      
      <div id="projectlist">
      </div>
    </div>

    <!-- PRESUPUESTOS -->
    <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12">
      <h5><b>Presupuestos:</b></h5>
      <div>
        <div class="row presupuestorow">
          <div class="col-md-8 col-sm-8 col-xs-8 col-lg-8">
            <div class="item">Nombre<br></div>
         </div>
          <div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 text-right">
              Todos <input id="checktodos" type="checkbox" name="" onclick="budgetCheckAll(this);" />
          </div>
        </div>
      </div>
      <div id="presupuestolist"></div>
    </div>
  </div>
</div>
  </div>
</div>

<style type="text/css">
  #projectlist{height: auto; border:1px #cecece solid; overflow: auto; padding:0;}
  #projectlist div.item{padding:3px 0; cursor:pointer;}
  #projectlist div.projectrow:hover{background:#d0edf1 !important; color:black !important;}
  #projectlist a.projectrowver:hover{color:navy; font-weight: 700;}
  #presupuestolist{height: auto; min-height: 300px; border:1px #cecece solid; overflow: auto; padding:0;}
  #presupuestolist div.item{padding:3px 0; cursor:pointer;}
  #presupuestolist div.presupuestorow:hover{background:#d0edf1 !important;}
  #presupuestolist a.presupuestorowver:hover{color:navy; font-weight: 700;}
</style>

<script src="<?php echo Yii::app()->theme->baseUrl;?>\assets\bootbox.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl.'/protected/vendor/jquerytoast/notify.min.js'; ?>"></script>
<script src="<?php echo Yii::app()->baseUrl.'/protected/vendor/tooltip/tooltip.min.js'; ?>"></script>

<script type="text/javascript">
  function getBudgetList(projectid, callerid){
    $.ajax({
      type: 'GET',
      async: true,
      contentType: "application/json",
      url: "<?php echo Yii::app()->createUrl('/project/budgetlisthtml').'&id='; ?>"+projectid,
      success: function(data){
        $('#checktodos').prop('checked',false);
        $('.projectrow').css({'background-color':'transparent','color':'black'});
        $('#'+callerid).css({'background-color':'#c6ea9c'});
        $("#presupuestolist").html(data);
        updateBudgetListChecks();
      },
      error: function(jqXHR, textStatus, errorThrown){
        Notify({
          content: 'No fue posible grabar la información.',
          rounded: true,
          color: '#f2dede'
        });
      },
    });
  }

  function getProjectList(){
    $.ajax({
      type: 'GET',
      async: true,
      contentType: "application/json",
      url: "<?php echo Yii::app()->createUrl('/project/projectlisthtml'); ?>",
      success: function(data){
        $("#projectlist").html(data);
        budgetList=<?php echo $projectList; ?>;
      },
      error: function(jqXHR, textStatus, errorThrown){
        Notify({
          content: 'No fue posible grabar la información.',
          rounded: true,
          color: '#f2dede'
        });
      },
    });   
  }  

  function save(){
    $.ajax({
      type: 'POST',
      async: true,
      data: {items:JSON.stringify(budgetList), userid:<?php echo $userid; ?>},
      url: "<?php echo Yii::app()->createUrl('/UserSystem/budgetaccess', array('userid'=>$userid)); ?>",
      success: function(data){
        Notify({
          content: 'Guardado exitoso.',
          rounded: true,
          color: '#dff0d8'
        });        
      },
      error: function(jqXHR, textStatus, errorThrown){
        Notify({
          content: 'No fue posible grabar la información.',
          rounded: true,
          color: '#f2dede'
        });
      },
    }); 
  }

  var budgetList=Array();
  function refreshBudgetList(caller, projectid, budgetid){
    var item=new Object();
    item.projectid=projectid;
    item.budgetid=budgetid;

    var found = budgetList.findIndex(function(element) {
                  return element.budgetid==budgetid;
                });

    if(found!=-1){
      budgetList.splice(found, 1);
    }

    if(caller.checked){
       budgetList[budgetList.length]=item;
    }
  }

  $(document).ready(function() {
    getProjectList();
  });

  function updateBudgetListChecks(){
    for (var i = 0; i < budgetList.length; i++) {
      if(undefined!=$('#bicheck_'+budgetList[i].budgetid)){
        $('#bicheck_'+budgetList[i].budgetid).prop('checked',true);
      }
    }
  }

  function budgetCheckAll(caller){
    $('.budgetcheck').each(function(){
      $(this).prop('checked', !caller.checked);  
      $(this).click();
    });
  }

</script>