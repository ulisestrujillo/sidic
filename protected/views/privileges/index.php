<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
    <input style="display:none;" id="btnPrivilegesGuardar" type="button" onclick="confirmationSaving();" 
       class="btn btn-raised btn-primary m-t-15 waves-effect btn-sm" value="Guardar"></input>	
	</div>

	<div class="col-md-12">
    <h3 style="margin:0px;">Derechos de usuario</h3>
    <small>Haga clic en la opci&oacute;n <i>"ver"</i> para ver los permisos asignados a cada usuario.</small>

<form name="PrivilegesForm" method="POST" action="<?php echo Yii::app()->createUrl('privileges/update'); ?>">

<div class="container-fluid">
	<div class="row">
		<div class="col-md-4 col-lg-3 col-sm-12 col-xs-12">
			<h5>Usuarios:</h5>
			<input type="hidden" id="Privileges_userid" name="Privileges[userid]" value="">
			<div id="userlist">
				<?php 
						$this->renderPartial('_users', array(
							'data'=>$users,
						));
				?>
			</div>
		</div>

		<div class="col-md-4 col-lg-3 col-sm-12 col-xs-12">
			<h5>Roles:</h5>
			<div id="roleslist">
				<?php 
						$this->renderPartial('_roles', array(
							'data'=>$roles,
						));
				?>
			</div>
			<br/><h5>Permisos asignados:</h5>
			<button style="width:100%; display:none;" type="button" id="btnRemExtraPriv" onclick="removeAllExtraItem();" class="btn btn-warning btn-xs">Quitar permisos adicionales</button>
			<div id="rolesYPermisosList">
			</div>
		</div>

		<div class="col-md-4 col-lg-6 col-sm-12 col-xs-12">
			<div class="row">
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    <h5>
<a hrerf="#" id="aditionprivilegeshelp" 
data-toggle="popover" title="Informaci&oacute;n" 
                   data-content="Son permisos adicionales al rol, que pueden ser asignados al usuario."
type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a>
            Permisos adicionales:&nbsp;&nbsp;
			    	    <input id="checkPrivileges" type="checkbox" onclick="privilegesCheckAll(this);" />
			    	  &nbsp;Seleccionar todos / ninguno</h5>
			  </div>
			</div>
			<div id="privilegeslist">
			</div>
		</div>  
  </div>
</div>

</form>

	</div>
</div>

<style type="text/css">
	#userlist{height: 568px; border:1px #cecece solid; overflow: auto; padding:0;}
	#privilegeslist{height: 400px; border:1px #cecece solid; overflow: auto; padding:0;width: 100%}
	#roleslist{height: auto; border:1px #cecece solid; overflow: auto; padding:0;}
	#rolesYPermisosList{height: 400px; border:1px #cecece solid; overflow: auto; padding:0;}
	#userlist div.item{padding:3px 0; cursor:pointer;}
	#privilegeslist div.item{padding:0 3px;}
	#roleslist div.item{padding:0 3px;}
	#rolesYPermisosList div.item{padding:0 3px;}
  #checkPrivileges{margin:0px;}
  #userlist div.userrow:hover{background:#d0edf1 !important;}
  #userlist a.userrowver:hover{color:navy; font-weight: 700;}
  .removeitem{cursor:pointer;color:red;}
</style>

<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
  	$('#aditionprivilegeshelp').popover({placement:"top"});
    localStorage.removeItem("username");
  });

	function confirmationSaving(){
    if(localStorage.getItem("username")==null){
      bootbox.alert("Para poder guardar debe modificar los derechos de un usuario por lo menos.");
      $('#btnPrivilegesGuardar').css('display','none');
    }else{
      var rolesSelected=$('#roleslist div.rolerow input:checkbox').is(':checked');
      var _message="¿Desea guardar los privilegios para el usuario: "+localStorage.getItem("username")+" ?";

      if(!rolesSelected){
        _message="<div class=\"alert alert-warning\" role=\"alert\">El usuario <i>"+localStorage.getItem("username")+"</i> no tiene ning&uacute;n rol asignado, ¿Desea continuar con el guardado?</div>";
      }

	    bootbox.confirm({
	      message: _message,
	      buttons: {
	        confirm: {
	          label: 'Si',
	          className: 'btn-success'
	        },
	        cancel: {
	          label: 'No',
	          className: 'btn-danger'
	        }
	      },
	      callback: function (result) {
	      	if(result){
	      		$('#btnPrivilegesGuardar').css('display','none');
	      		document.forms.PrivilegesForm.submit();
	      	}
	      }
	    });
    }
	}

	function privilegesCheckAll(caller){
		$('#privilegeslist div.item input:checkbox').prop('checked', caller.checked);
	}

	function getAllOperations(userid){
    $('.rolerow').css('background','transparent');
    $.ajax({
      type: 'GET',
      async: true,
      contentType: "application/json",
      url: "<?php echo Yii::app()->createUrl('/privileges/getAllOperations').'&userid='; ?>"+userid,
      success: function(data){
        privileges=JSON.parse(data)[0];
      },
      error: function(jqXHR, textStatus, errorThrown){
        alert("Se ha interrumpido la conexión de red.");
      },
    });		
	}

  function repaint(rowid){
    $('.userrow').css('background-color','white');
    $('#'+rowid).css('background-color','#60ccda !important');
  }

  function getPrivileges(userid, username, rowid){
  	repaint(rowid);
  	localStorage.setItem("username", username);
  	localStorage.setItem("userid", userid);
  	$('#Privileges_userid')[0].value=userid;
    $.ajax({
      type: 'GET',
      async: true,
      contentType: "application/json",
      url: "<?php echo Yii::app()->createUrl('/privileges/getOperations').'&userid='; ?>"+userid,
      success: function(data){
        json=JSON.parse(data);
        $('#btnPrivilegesGuardar').css('display','block');
        rolesYPermisosListPopulate(json[1], userid);
        checkPrivileges(JSON.parse(data)[2]);
        checkRoles(data);
      },
      error: function(jqXHR, textStatus, errorThrown){
        alert("El servidor no pudo procesar la solicitud.");
      },
    });
  }

  function checkRoles(data){
    roles=JSON.parse(data)[0];
  	$('#roleslist div.item input:checkbox').prop('checked', false);

    for (var i = 0; i < roles.length; i++) {
	    $('#chkRole'+roles[i]).prop('checked', true);
    }
  }

  function checkPrivileges(data){
  	unassignedop=data;
    $('#privilegeslist').html(unassignedop);
  }

  function rolesYPermisosListPopulate(data, userid){
    $('#rolesYPermisosList').html("");
    $('#btnRemExtraPriv').hide();
    if(jQuery.trim(data).length==0)
      return;
    data=data.split(";");
    var _innerHTML="";
    var extraPrivTitle="";

    for (var i = 0; i < data.length; i++) {
      if(data[i].split("|")[0]=="PARENT"){
        _innerHTML+="<div>"+data[i].split("|")[1]+"</div>";
      }else if(data[i].split("|")[1]!=undefined){
        var dato=data[i].split('|')[1];
        var click='removeExtraItem(\"'+dato.replace("_extra","")+'\",'+userid+', this);';
        var span="";

        if(dato.indexOf("_extra")!=-1){
          if(extraPrivTitle==""){
            extraPrivTitle="<div class='extraPrivTitle'><b>Permisos adicionales</b></div>";
            _innerHTML+=extraPrivTitle;
          }
          $('#btnRemExtraPriv').show();
          span="<input type='hidden' name='Privileges[extra]["+i+"]' value='"+dato.replace("_extra","")+"' /><span onclick='"+click+"' title='Quitar Permiso' class='removeitem'>[ X ]</span>&nbsp;";
        }
        if(dato.indexOf("_extra")!=-1){
         _innerHTML+="<div class='aditionalPrivilegeItem'>&nbsp;"+span+"<span class='aditionalPrivilege'>"+dato.replace("_extra","")+"</span></div>";
        }else{
          _innerHTML+="<div>&nbsp;"+span+dato.replace("_extra","")+"</div>";
        }
      }
    }
    $('#rolesYPermisosList').html(_innerHTML);
  }

  function removeExtraItem(itemname, userid, caller){
    bootbox.confirm({
      message: "¿Desea remover el permiso <b><i>"+itemname+"</i></b> ?: ",
      buttons: {
        confirm: {
          label: 'Si',
          className: 'btn-success'
        },
        cancel: {
          label: 'No',
          className: 'btn-danger'
        }
      },
      callback: function (result) {
      	if(result){
			    $.ajax({
			      type: 'GET',
			      async: true,
			      contentType: "application/json",
			      url: "<?php echo Yii::app()->createUrl('privileges/remove'); ?>"+"&itemname="+itemname+"&userid="+userid,
			      success: function(data){
			        caller.parentElement.remove();
              if($('.aditionalPrivilegeItem').length==0){
                $('.extraPrivTitle').remove();
                $('#btnRemExtraPriv').hide();
              }

			        checkPrivileges(JSON.parse(data));
			      },
			      error: function(jqXHR, textStatus, errorThrown){
			        alert("El servidor no pudo procesar la solicitud.");
			      },
			    });
      	}
      }
    });
  }

  function removeAllExtraItem(){
    bootbox.confirm({
      message: "¿Desea remover <b>todos</b> los permisos adicionales del usuario: <i>"+localStorage.getItem("username")+"</i>?",
      buttons: {
        confirm: {
          label: 'Si',
          className: 'btn-success'
        },
        cancel: {
          label: 'No',
          className: 'btn-danger'
        }
      },
      callback: function (result) {
      	var _items=Array();
				$('.aditionalPrivilege').each(function(){
				  _items[_items.length]=this.innerText;  
				});

      	if(result){
			    $.ajax({
			      type: 'POST',
			      async: true,
			      data: {items:_items},
			      url: "<?php echo Yii::app()->createUrl('privileges/removeallitems'); ?>"+"&userid="+localStorage.getItem("userid"),
			      success: function(data){
              $('#btnRemExtraPriv').hide();
              $('.aditionalPrivilegeItem').remove();
			        $('.extraPrivTitle').remove();
      				Notify({
								content: 'Permisos adicionales fueron removidos.',
								rounded: true,
								color: '#dff0d8'
							});
			      },
			      error: function(jqXHR, textStatus, errorThrown){
			        alert("El servidor no pudo procesar la solicitud.");
			      },
			    });
      	}
      }
    });
  }

</script>

<script src="https://malsup.github.io/jquery.blockUI.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>\assets\bootbox.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl.'/protected/vendor/jquerytoast/notify.min.js'; ?>"></script>
<script src="<?php echo Yii::app()->baseUrl.'/protected/vendor/tooltip/tooltip.min.js'; ?>"></script>
      