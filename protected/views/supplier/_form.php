<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<div class="block-header">
    <div class="row">
        <div class="col-lg-7 col-md-6 col-sm-12">
            <h2>Proveedores
            <small class="text-muted">Los campos con asterisco son requeridos</small>
            </h2>

           <h2><small class="text-muted">
            <?php 
               if(Yii::app()->user->hasFlash('updated')){
                 echo Yii::app()->user->getFlash('updated');
               }
             ?>            
           </small></h2>               
        </div>
        <div class="col-lg-5 col-md-6 col-sm-12">
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i> Sidic</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0);">Catálogos</a></li>
                <li class="breadcrumb-item active">Proveedores</li>
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
                  'id'=>'supplier-form',
                  'enableAjaxValidation'=>false,
                  'htmlOptions' => array('enctype'=>'multipart/form-data'),
                )); ?>

                <?php CHtml::$errorContainerTag = 'label'; ?>

                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="col-lg-6 col-md-6 col-sm-6">
                        <label for="Supplier_code">*Clave</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input value="<?php echo $model->code; ?>" name="Supplier[code]" type="text" id="Supplier_code" class="form-control" placeholder="">
                                <?php echo $form->error($model,'code', array('class'=>'error')); ?>
                            </div>
                        </div>  
                        <label for="Supplier_name">*Nombre</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input value="<?php echo $model->name; ?>" name="Supplier[name]" type="text" id="Supplier_name" class="form-control" placeholder="">
                                <?php echo $form->error($model,'name', array('class'=>'error')); ?>
                            </div>
                        </div>  
                        <label for="Supplier_rfc">*RFC</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input value="<?php echo $model->rfc; ?>" name="Supplier[rfc]" type="text" id="Supplier_rfc" class="form-control" placeholder="">
                                <?php echo $form->error($model,'rfc', array('class'=>'error')); ?>
                            </div>
                        </div>  
                        <label for="Supplier_address">*Domicilio</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input value="<?php echo $model->address; ?>" name="Supplier[address]" type="text" id="Supplier_address" class="form-control" placeholder="">
                                <?php echo $form->error($model,'address', array('class'=>'error')); ?>
                            </div>
                        </div>  
                        <label for="Supplier_patronal_record">Registro patronal</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input value="<?php echo $model->patronal_record; ?>" name="Supplier[patronal_record]" type="text" id="Supplier_patronal_record" class="form-control" placeholder="">
                            </div>
                        </div>  

                        <div class="input-group input-group-lg">
                            <div class="demo-switch-title">Activo</div>
                            <div class="switch">
                                <label>
                                    <input type="checkbox" name="Supplier[active]" id="Supplier_active"
                    <?php echo $model->active==1 ? 'checked=""' : '' ?>
                                    >
                                    <span class="lever switch-col-blue"></span></label>
                            </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6">
                        <label for="Supplier_agent">Representante</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input value="<?php echo $model->agent; ?>" name="Supplier[agent]" type="text" id="Supplier_agent" class="form-control" placeholder="">
                            </div>
                        </div>  

                        <label for="Supplier_phone">*Teléfono</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input value="<?php echo $model->phone; ?>" name="Supplier[phone]" type="text" id="Supplier_phone" class="form-control" placeholder="">
                                <?php echo $form->error($model,'phone', array('class'=>'error')); ?>
                            </div>
                        </div>  

                        <label for="Supplier_email">*Correo</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input value="<?php echo $model->email; ?>" name="Supplier[email]" type="text" id="Supplier_email" class="form-control" placeholder="">
                                <?php echo $form->error($model,'email', array('class'=>'error')); ?>
                            </div>
                        </div>  

                        <label for="Supplier_bank">Banco</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input value="<?php echo $model->bank; ?>" name="Supplier[bank]" type="text" id="Supplier_bank" class="form-control" placeholder="">
                                <?php echo $form->error($model,'bank', array('class'=>'error')); ?>
                            </div>
                        </div>  

                        <label for="Supplier_account">Cuenta</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input value="<?php echo $model->account; ?>" name="Supplier[account]" type="text" id="Supplier_account" class="form-control" placeholder="">
                                <?php echo $form->error($model,'account', array('class'=>'error')); ?>
                            </div>
                        </div>  

                        <label for="Supplier_clabe">CLABE</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input value="<?php echo $model->clabe; ?>" name="Supplier[clabe]" type="text" id="Supplier_clabe" class="form-control" placeholder="">
                                <?php echo $form->error($model,'clabe', array('class'=>'error')); ?>
                            </div>
                        </div>  

                      </div>
                    </div>
                </div>


                    <button type="submit" class="btn btn-raised btn-primary m-t-15 waves-effect"><?php echo $model->isNewRecord ? 'Crear' : 'Guardar'; ?></button>
    <?php echo CHtml::link('Cancelar', Yii::app()->createUrl('supplier/admin') ,array('class'=>'btn btn-raised btn-primary m-t-15 waves-effect')); ?>

                  <?php $this->endWidget(); ?>
              </div>
          </div>
      </div>
  </div>
</div>
