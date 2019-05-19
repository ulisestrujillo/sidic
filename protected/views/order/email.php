<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />

<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl.'/assets/css/print.css'; ?>">
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<th width="33.3333%"></th>
		<th width="33.3333%"></th>
		<th width="33.3333%"></th>
	</tr>
	<tr>
		<td width="30%"><h1><img width="100px" src="<?php echo Yii::app()->baseUrl.'/images/'.$logo; ?>" border="0" /></h1></td>
		<td width="40%"><h1>Orden de compra</h1></td>
		<td width="30%">
				<table width="100%" class="table">
				  <tbody>
				    <tr>
				      <th align="right">Fecha:</th>
				      <td align="left"><?php 
				      	    $date=date_create($order->created); 
				            echo date_format($date,"Y-m-d"); 
				          ?>
				      </td>
				    </tr>
				    <tr>
				      <th align="right">Folio Núm:</th>
				      <td align="left"><?php echo str_pad($order->order_id, 6, "0", STR_PAD_LEFT); ?></td>
				    </tr>
				  </tbody>
				</table>
		</td>
	</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<th width="50%"></th>
		<th width="50%"></th>
	</tr>
	<tr>
		<td><br/><br/></td><td></td>
	</tr>
  <tr>
  	<td style="text-align: center; color: #02029e;"><strong>Para proveedor: <?php echo $supplier->id; ?></strong></td>
  	<td style="text-align: center; color: #02029e;"><strong>Facturar a:</strong></td>
  </tr>
  <tr>
  	<td>
      <?php echo $datosProveedor; ?>
  	</td>
		<td>
      <?php echo $datosContratante; ?>
  	</td>
  </tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<th width="50%"></th>
		<th width="50%"></th>
	</tr>
	<tr>
		<td><br/><br/></td><td></td>
	</tr>
  <tr>
  	<td style="text-align: center; color: #02029e;"><strong>Entregar en:</strong></td>
  	<td style="text-align: center; color: #02029e;"><strong>Identificación:</strong></td>
  </tr>
  <tr>
  	<td>
		  <?php echo $datosProyecto; ?>
    </td>
  	<td>
  		
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><strong>Obra:</strong></td>
					<td><?php echo strtoupper($obra); ?></td>
				</tr>
				<tr>
					<td><strong>Plaza:</strong></td>
					<td><?php echo strtoupper($plaza); ?></td>
				</tr>
				<tr>
					<td><strong>Status:</strong></td>
					<td><?php echo strtoupper($order->orderstatus->name); ?></td>
				</tr>
			</table>

  	</td>
  </tr>
</table>

<!-- conceptos -->
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
  	<td colspan="6"><br/><br/><br/><br/></td>
  </tr>
	<tr style="color: #02029e;">
		<td width="5%"><strong>Cant.</strong></td><!-- cantidad -->
		<td width="5%"><strong>Unidad</strong></td><!-- unidad -->
		<td width="45%"><strong>&nbsp;&nbsp;Concepto</strong></td><!-- concepto -->
		<td width="10%" style="text-align: right;"><strong>P. Unit</strong></td><!-- P.unitario -->
		<td width="10%" style="text-align: right;"><strong>Importe</strong></td><!-- importe -->
		<td width="10%" style="text-align: right;"><strong>I.V.A.</strong></td><!-- impuesto -->
		<td width="10%" style="text-align: right;">&nbsp;&nbsp;<strong>Neto</strong></td><!-- neto -->
	</tr>
  <tr>
  	<td colspan="6"><br/></td>
  </tr>

<?php 
foreach ($order->orderdetail as $det) {
?>
	<tr>
		<td width="5%"><?php echo $det->qty; ?></td><!-- cantidad -->
		<td width="5%"><?php echo $det->unit; ?></td><!-- unidad -->
		<td width="45%">&nbsp;&nbsp;
			<?php echo $det->description; ?>
		</td><!-- concepto -->
		<td width="10%" style="text-align: right;"><?php echo number_format($det->price, 2, '.', ','); ?></td><!-- P.unitario -->
		<td width="10%" style="text-align: right;"><?php echo number_format($det->total, 2, '.', ','); ?></td><!-- importe -->
		<td width="10%" style="text-align: right;"><?php echo number_format($det->tax, 2, '.', ','); ?></td><!-- Impuesto -->
		<td width="10%" style="text-align: right;">&nbsp;&nbsp;<?php echo number_format($det->total + $det->tax, 2, '.', ','); ?></td><!-- Neto -->
	</tr>

<?php } ?>  

	<tr>
		<td colspan="3"></td>
		<td colspan="4"><hr/></td>
	</tr>

	<tr>
		<td><br/></td><!-- unidad -->
		<td><br/></td><!-- unidad -->
		<td width="45%" style="text-align: right;">
	   <strong>Totales:&nbsp;&nbsp;&nbsp;</strong>
		</td><!-- concepto -->

		<td width="10%" style="text-align: right;"><?php echo number_format($totalPrice, 2, '.', ','); ?></td><!-- P.unitario -->
		<td width="10%" style="text-align: right;"><?php echo number_format($summatoryTotal, 2, '.', ','); ?></td><!-- importe -->
		<td width="10%" style="text-align: right;"><?php echo number_format($totalTax, 2, '.', ','); ?></td><!-- Impuesto -->
		<td width="10%" style="text-align: right;"><?php echo number_format($summatoryTotalPlusTax, 2, '.', ','); ?></td><!-- Total -->
	</tr>

  <tr>
  	<td colspan="7"><br/><br/></td>
  </tr>

  <tr>
  	<td colspan="7"><strong>Total en letra:</strong> 
  		***(SON: <?php echo $totalConLetra; ?>)***</td>
  </tr>
</table>


<!-- conceptos -->
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
  	<td colspan="7"><br/><br/></td>
  </tr>
  <tr>
  	<td colspan="7"><strong>Observaciones: </strong><?php echo strtoupper($order->comment); ?> </td>
  </tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
  	<td colspan="4"><br/><br/></td>
  </tr>
  <tr>
  	<td width="33.3333%" style="text-align: center"><?php echo $autorizadaPor; ?></td>
  	<td width="33.3333%" style="text-align: center"><?php echo strtoupper($solicitante); ?></td><!-- nombre del que imprime la orden -->
  	<td width="33.3333%" style="text-align: center"><?php echo $colocadaPor; ?></td><!-- nombre de quien coloca -->
  </tr>
  <tr>
  	<td width="33.3333%" style="text-align: center"><strong>Autoriza Orden Jefe</strong></td>
  	<td width="33.3333%" style="text-align: center"><strong>Solicitante</strong></td>
  	<td width="33.3333%" style="text-align: center"><strong>Colocó</strong></td>
  </tr>
  <tr>
  	<td colspan="4"><br/><br/><br/><br/><br/><br/></td>
  </tr>
</table>

