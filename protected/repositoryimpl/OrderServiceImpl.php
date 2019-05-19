<?php
require(Yii::getPathOfAlias("webroot").'/protected/services/OrderService.php');

class OrderServiceImpl extends Controller implements OrderService
{

  public function getReporteGeneralCount($projectid, $budgetid)
  {
    $query="SELECT COUNT(*) FROM (
              SELECT
              concat(
                '<a href=\"index.php?r=order/update&id=',b.id
                ,'&projectid=',$projectid,'&budgetid=',bg.id
                ,'&orderid=',o.id,'\">',o.id,'</a>') AS Folio
                ,o.id as Folio1
              ,case when b.parentid = 0 then
                b.name
              end as Partida,
              case when b.parentid > 0 then
               (select name from budgetitem where id = b.id and parentid = b.parentid)
              end as Subpartida
              ,s.name as Proveedor
              ,CASE WHEN o.statusid = 1  THEN
                'COLOCADA' 
              WHEN o.statusid = 2 THEN
                'AUTORIZADA'
              WHEN o.statusid = 4 THEN
                'RECIBIDA'
              WHEN o.statusid = 7 THEN
                'PAGADA'
              END as Estatus
              , b.budgettop AS TopePresupuesto
              from budget bg
              inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
              inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
              inner join supplier s on s.id = o.supplierid
              where b.budgetid = $budgetid and b.status = 1
              order by o.id) AS T;";

    $res = Yii::app()->db->createCommand($query)->queryColumn();
    $count=$res[0];

    return $count;

  }

  public function getReporteGeneralData($projectid, $budgetid)
  {
    $query="SELECT
              concat(
                '<a href=\"index.php?r=order/update&id=',b.id
                ,'&projectid=',$projectid,'&budgetid=',bg.id
                ,'&orderid=',o.id,'\">',o.id,'</a>') AS Folio
              ,o.id as Folio1
              ,case when b.parentid = 0 then
                b.name
              end as Partida,
              case when b.parentid > 0 then
               (select name from budgetitem where id = b.id and parentid = b.parentid)
              end as Subpartida
              ,s.name as Proveedor
              ,CASE WHEN o.statusid = 1  THEN
                'COLOCADA' 
              WHEN o.statusid = 2 THEN
                'AUTORIZADA'
              WHEN o.statusid = 4 THEN
                'RECIBIDA'
              WHEN o.statusid = 7 THEN
                'PAGADA'
              END as Estatus
              , b.budgettop AS TopePresupuesto
              from budget bg
              inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
              inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
              inner join supplier s on s.id = o.supplierid
              where b.budgetid = $budgetid and b.status = 1
              order by o.id;";

    $res = Yii::app()->db->createCommand($query)->queryAll();

    return $res;

  }

  /**
  *Devuelve el total del presupuesto del reporte general de ordenes de compra
  *@param
  */
  public function getReporteGeneralTotalPresupuesto($projectid, $budgetid)
  {
    $query="SELECT
              SUM(b.budgettop) AS TotalPresupuesto
            from budget bg
            inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            left join budgetitem bi on bi.budgetid = b.budgetid and bi.parentid = b.id and bi.parentid > 0
            inner join supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            and b.parentid = 0 and b.status = 1
            order by o.id;";

    $res = Yii::app()->db->createCommand($query)->queryScalar();

    return $res;

  }

  public function getReporteOcPorAutorizarCount($projectid, $budgetid)
  {
    $query="SELECT COUNT(*) FROM (
            select
            o.id as Folio
            ,case when b.parentid = 0 then
              b.name
            end as Partida,
            case when b.parentid > 0 then
             (select name from budgetitem where id = b.id and parentid = b.parentid)
            end as Subpartida
            ,s.name as Proveedor
            ,o.total as Monto
            ,'SI' as Colocada
            ,'' as Autorizar
            ,b.budgettop as TopePresupuesto
            from budget bg
            inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            inner join supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            and b.status = 1
            and o.statusid in (1)
            order by o.id) AS T;";

    $res = Yii::app()->db->createCommand($query)->queryColumn();
    $count=$res[0];

    return $count;

  }

  public function getReporteOcPorAutorizarData($projectid, $budgetid){
    $query="SELECT
            concat(
              '<a href=\"index.php?r=order/update&id=',b.id
              ,'&projectid=',$projectid,'&budgetid=',bg.id
              ,'&orderid=',o.id,'\">',o.id,'</a>') AS Folio
            ,o.id as Folio1
            ,case when b.parentid = 0 then
              b.name
            end as Partida,
            case when b.parentid > 0 then
             (select name from budgetitem where id = b.id and parentid = b.parentid)
            end as Subpartida
            ,s.name as Proveedor
            ,o.total as Monto
            ,'SI' as Colocada
            ,concat('<a class=\"autoriza',o.id,'\" href=\"#\" onclick=\"autorizarOrden(',o.id,', this);\">Autorizar</a>') AS Autorizar
            ,b.budgettop as TopePresupuesto
            from budget bg
            inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            inner join supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            and b.status = 1
            and o.statusid in (1)
            order by o.id;";

    $res = Yii::app()->db->createCommand($query)->queryAll();

    return $res;
  }

  public function getReporteOcPorAutorizarTotalPresupuesto($projectid, $budgetid){
    $query="SELECT
              SUM(b.budgettop) AS TotalPresupuesto
            from budget bg
            inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            left join budgetitem bi on bi.budgetid = b.budgetid and bi.parentid = b.id and bi.parentid > 0
            inner join supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            and b.parentid = 0 and b.status = 1
            and o.statusid in (1)
            order by o.id;";

    $res = Yii::app()->db->createCommand($query)->queryScalar();

    return $res;
  }

  public function getReporteOcAutorizadasCount($projectid, $budgetid){
    $query="SELECT COUNT(*) FROM (
              select
              o.id AS Folio
              ,case when b.parentid = 0 then
                b.name
              end as Partida,
              case when b.parentid > 0 then
               (select name from budgetitem where id = b.id and parentid = b.parentid)
              end as Subpartida
              ,s.name as Proveedor
              ,o.total as Monto
              ,'SI' as Autorizada
              ,'Recibir' AS Recibir
              ,b.budgettop as TopePresupuesto
              from budget bg
              inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
              inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
              inner join supplier s on s.id = o.supplierid
              where b.budgetid = $budgetid
              and b.status = 1
              and o.statusid = 2
              order by o.id) AS T;";

    $res = Yii::app()->db->createCommand($query)->queryColumn();
    $count=$res[0];

    return $count;
  }

  public function getReporteOcAutorizadasData($projectid, $budgetid){
    $puedeRecibir=User::checkRole('receptor') || User::checkRole('admin');

    $query="SELECT
            concat(
              '<a href=\"index.php?r=order/update&id=',b.id
              ,'&projectid=',$projectid,'&budgetid=',bg.id
              ,'&orderid=',o.id,'\">',o.id,'</a>') AS Folio
            ,o.id as Folio1
            ,case when b.parentid = 0 then
              b.name
            end as Partida,
            case when b.parentid > 0 then
             (select name from budgetitem where id = b.id and parentid = b.parentid)
            end as Subpartida
            ,s.name as Proveedor
            ,o.total as Monto
            ,'SI' as Autorizada, ";
              
    if($puedeRecibir==true){
      $query.=" concat('<a class=\"recibir',o.id,'\" href=\"#\" onclick=\"recibirOrden(',o.id,', this);\">Recibir</a>') ";
    }else{
      $query.=" '' ";
    }
              $query.=" AS Recibir
            ,b.budgettop as TopePresupuesto
            from budget bg
            inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            inner join supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            and b.status = 1
            and o.statusid = 2
            order by o.id";

    $res = Yii::app()->db->createCommand($query)->queryAll();

    return $res;
  }

  public function getReporteOcAutorizadasTotales($projectid, $budgetid){
    $query="SELECT
              SUM(o.total) AS TotalMonto
              ,SUM(b.budgettop) AS TotalPresupuesto
            from budget bg
            inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            left join budgetitem bi on bi.budgetid = b.budgetid and bi.parentid = b.id and bi.parentid > 0
            inner join supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            and b.parentid = 0 and b.status = 1
            and o.statusid = 2
            order by o.id;";

    $res = Yii::app()->db->createCommand($query)->queryAll();

    return $res;
  }

  public function getReporteOcRecibidasCount($projectid, $budgetid){
    $query="SELECT COUNT(*) FROM (
              SELECT
              concat(
                '<a href=\"index.php?r=order/update&id=',o.id
                ,'&projectid=',$projectid,'&budgetid=',bg.id
                ,'&orderid=',o.id,'\">',o.id,'</a>') AS Folio
              ,case when b.parentid = 0 then
                b.name
              end as Partida,
              case when b.parentid > 0 then
               (select name from budgetitem where id = b.id and parentid = b.parentid)
              end as Subpartida
              ,s.name as Proveedor
              ,o.total as Monto
              ,'SI' as Recibida
              ,b.budgettop as TopePresupuesto
              from budget bg
              inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
              inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
              inner join supplier s on s.id = o.supplierid
              where b.budgetid = $budgetid
              and b.status = 1
              and o.statusid = 4 /*surtida o recibida*/
              order by o.id) AS T;";

    $res = Yii::app()->db->createCommand($query)->queryColumn();
    $count=$res[0];

    return $count;
  }

  public function getReporteOcRecibidasData($projectid, $budgetid){
    $query="SELECT
            concat(
              '<a href=\"index.php?r=order/update&id=',b.id
              ,'&projectid=',$projectid,'&budgetid=',bg.id
              ,'&orderid=',o.id,'\">',o.id,'</a>') AS Folio
              ,o.id as Folio1
              ,case when b.parentid = 0 then
                b.name
              end as Partida,
              case when b.parentid > 0 then
               (select name from budgetitem where id = b.id and parentid = b.parentid)
              end as Subpartida
            ,s.name as Proveedor
            ,o.total as Monto
            ,'SI' as Recibida
            ,b.budgettop as TopePresupuesto
            from budget bg
            inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            inner join supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            and b.status = 1
            and o.statusid = 4
            order by o.id";

    $res = Yii::app()->db->createCommand($query)->queryAll();

    return $res;
  }

  public function getReporteOcRecibidasTotales($projectid, $budgetid){
    $query="SELECT
              SUM(o.total) AS TotalMonto
              ,SUM(b.budgettop) AS TotalPresupuesto
            from budget bg
            inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            left join budgetitem bi on bi.budgetid = b.budgetid and bi.parentid = b.id and bi.parentid > 0
            inner join supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            and b.parentid = 0 and b.status = 1
            and o.statusid = 4 /*surtida o recibida*/
            order by o.id";

    $res = Yii::app()->db->createCommand($query)->queryAll();

    return $res;
  } 

  public function getReporteOcPorPagarTotalMonto($projectid, $budgetid){
    $query="SELECT
              SUM(o.total) as TotalMonto
            from budget bg
            inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            left join budgetitem bi on bi.budgetid = b.budgetid and bi.parentid = b.id and bi.parentid > 0
            inner join supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            and b.parentid = 0 and b.status = 1
            and o.statusid = 6 /*surtida o recibida*/
            and o.paiddate = '1900-01-01'
            order by o.id";

    $res = Yii::app()->db->createCommand($query)->queryScalar();

    return $res;
  } 

  public function getReporteOcPorPagarCount($projectid, $budgetid){
    $query="SELECT COUNT(*) FROM (
              SELECT
              concat(
                '<a href=\"index.php?r=order/update&id=',o.id
                ,'&projectid=',$projectid,'&budgetid=',bg.id
                ,'&orderid=',o.id,'\">',o.id,'</a>') AS Folio
                ,o.id as Folio1
              ,case when b.parentid = 0 then
                b.name
              end as Partida,
              case when b.parentid > 0 then
               (select name from budgetitem where id = b.id and parentid = b.parentid)
              end as Subpartida
              ,s.name as Proveedor
              ,o.total as Monto
              ,'1900-01-01' as FechaParaPagar
              from budget bg
              inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
              inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
              inner join supplier s on s.id = o.supplierid
              where b.budgetid = $budgetid
              and b.status = 1
              and o.statusid = 6 /*surtida o recibida*/
              and o.paiddate = '1900-01-01'
              order by o.id) AS T;";

    $res = Yii::app()->db->createCommand($query)->queryColumn();
    $count=$res[0];

    return $count;
  }

  public function getReporteOcPorPagarData($projectid, $budgetid){
    $query="SELECT
            concat(
              '<a href=\"index.php?r=order/update&id=',b.id
              ,'&projectid=',$projectid,'&budgetid=',bg.id
              ,'&orderid=',o.id,'\">',o.id,'</a>') AS Folio
              ,o.id as Folio1
              ,case when b.parentid = 0 then
                b.name
              end as Partida,
              case when b.parentid > 0 then
               (select name from budgetitem where id = b.id and parentid = b.parentid)
              end as Subpartida
            ,s.name as Proveedor
            ,o.total as Monto
            ,'1900-01-01' as FechaParaPagar
            from budget bg
            inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            inner join supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            and b.status = 1
            and o.statusid = 6 /*surtida o recibida*/
            and o.paiddate = '1900-01-01'
            order by o.id";

    $res = Yii::app()->db->createCommand($query)->queryAll();

    return $res;
  }

  public function getReporteOcPagadasTotalMonto($projectid, $budgetid){
    $query="SELECT
              SUM(o.total) AS TotalMonto
            FROM budget bg
            INNER JOIN budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            INNER JOIN `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            LEFT JOIN budgetitem bi on bi.budgetid = b.budgetid and bi.parentid = b.id and bi.parentid > 0
            INNER JOIN supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            AND b.parentid = 0 AND b.status = 1
            AND o.statusid = 7 /*pagada*/
            AND o.paiddate != '1900-01-01'
            ORDER BY o.id";

    $res = Yii::app()->db->createCommand($query)->queryScalar();

    return $res;
  }

  public function getReporteOcPagadasCount($projectid, $budgetid){
    $query="SELECT COUNT(*) FROM (
              SELECT
              concat(
                '<a href=\"index.php?r=order/update&id=',o.id
                ,'&projectid=',$projectid,'&budgetid=',bg.id
                ,'&orderid=',o.id,'\">',o.id,'</a>') AS Folio
                ,case when b.parentid = 0 then
                  b.name
                end as Partida,
                case when b.parentid > 0 then
                 (select name from budgetitem where id = b.id and parentid = b.parentid)
                end as Subpartida
              ,s.name as Proveedor
              ,o.total as Monto
              ,DATE_FORMAT(paiddate,'%Y-%m-%d') as FechaDePago
              ,o.methodpayment as MetodoDePago
            from budget bg
            inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            inner join supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            and b.status = 1
            and o.statusid = 7 /*pagada*/
            and o.paiddate != '1900-01-01'
            order by o.id) AS T;";

    $res = Yii::app()->db->createCommand($query)->queryAll();

    return $res;
  }

  public function getReporteOcPagadasData($projectid, $budgetid){
    $query="SELECT
            concat(
              '<a href=\"index.php?r=order/update&id=',b.id
              ,'&projectid=',$projectid,'&budgetid=',bg.id
              ,'&orderid=',o.id,'\">',o.id,'</a>') AS Folio
            ,o.id as Folio1
            ,case when b.parentid = 0 then
              b.name
            end as Partida,
            case when b.parentid > 0 then
             (select name from budgetitem where id = b.id and parentid = b.parentid)
            end as Subpartida
            ,s.name as Proveedor
            ,o.total as Monto
            ,DATE_FORMAT(paiddate,'%Y-%m-%d') as FechaDePago
            ,o.methodpayment as MetodoDePago
            from budget bg
            inner join budgetitem b on b.budgetid = bg.id AND bg.projectid = $projectid
            inner join `order` o on o.budgetid = b.budgetid and o.budgetitemid = b.id
            inner join supplier s on s.id = o.supplierid
            where b.budgetid = $budgetid
            and b.status = 1
            and o.statusid = 7 /*pagada*/
            and o.paiddate != '1900-01-01'
            order by o.id";

    $res = Yii::app()->db->createCommand($query)->queryAll();

    return $res;
  }

  /**
  *Genera un reporte de ordenes por pagar en formato pdf
  *@param ProjectId es el id del proyecto al que pertenece el presupuesto
  *@param BudgetId es el id del presupuesto el cual pertenece al ProjectId
  */
  public function getReporteOcPorPagarPdf($projectid, $budgetid){
    require_once(Yii::getPathOfAlias("webroot") . '/protected/vendor/TCPDF/tcpdf.php');

    $repo=new OrderServiceImpl('Order');

    $orders=$this->getReporteOcPorPagarData($projectid, $budgetid);

    $project = Project::model()->findByPk($projectid);
    $fecha = date("d-m-Y");
    $budget = Budget::model()->findByPk($budgetid);
    $budgetstatus=$budget->active==1?'Activo':'Inactivo';

    $encabezado="
    <table width=\"100%\">
      <tr>
        <td style=\"width:50%\">
          <table>
            <tr><td><b>Proyecto:</b></td><td>$project->name</td></tr>
            <tr><td><b>Presupuesto:</b></td><td>$budget->name</td></tr>
            <tr><td><b>Plaza:</b></td><td>$project->location</td></tr>
            <tr><td><b>Direcci&oacute;n:</b></td><td>$project->address</td></tr>
          </table>
        </td>
        <td style=\"width:50%\">
          <table>
            <tr><td><b>Fecha:</b></td><td>$fecha</td></tr>
            <tr><td></td><td></td></tr>
            <tr><td></td><td></td></tr>
            <tr><td><b>Estatus del presupuesto:</b></td><td>$budgetstatus</td></tr>
          </table>
        </td>
      </tr>
    </table><br/><br/>";

    $html="<table width='100 %'>";
    $html.="<tr><hr>";
    $html.="<td style=\"width:5%\"><b>Folio</b></td>
            <td style=\"width:14%\"><b>Partida</b></td>
            <td style=\"width:14%\"><b>Subpartida</b></td>
            <td style=\"width:47%\"><b>Proveedor</b></td>
            <td style=\"width:10%;\"><b>Monto</b></td>
            <td style=\"width:10%;\"><b>Fecha Pagar</b></td>";
    $html.="<hr/><br/></tr>";

    foreach ($orders as $order) {
      $html.="<tr>";
      $html.="<td>".$order["Folio1"]."</td>";
      $html.="<td>".$order["Partida"]."</td>";
      $html.="<td>".$order["Subpartida"]."</td>";
      $html.="<td>".$order["Proveedor"]."</td>";
      $html.="<td style=\"text-align:right;\">".number_format(round($order["Monto"], 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
      $html.="<td>".$order["FechaParaPagar"]."</td>";
      $html.="</tr>";
    }

    $totalmonto=$this->getReporteOcPorPagarTotalMonto($projectid, $budgetid);

    $html.="<tr><td colspan=\"6\"><br/></td></tr><tr>";
    $html.="<td colspan=\"5\" style=\"text-align: right;\">$ ".number_format(round($totalmonto, 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
    $html.="</tr>";
    $html.="</table>";

    ob_start();
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator("SIDIC");
    $pdf->SetAuthor('SIDIC');
    $pdf->SetTitle("Reporte de ordenes por pagar");
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', '', 9);

    // set default header data
    $pdf->SetHeaderData("/../../../../../images/enterprise-logo.jpg", 30, "SIDIC", "Reporte de ordenes por pagar");

    //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->writeHTML($encabezado.$html, true, false, false, false, '');
    $pdf->lastPage();

    ob_end_clean();
    $pdf->Output('ReporteDeOrdenesPorPagar.pdf', 'I');
  }
  

  /**
  *Genera un reporte de ordenes de compra autorizadas en formato pdf
  *@param ProjectId es el id del proyecto al que pertenece el presupuesto
  *@param BudgetId es el id del presupuesto el cual pertenece al ProjectId
  */
  public function getReporteOcAutorizadasPdf($projectid, $budgetid){
    require_once(Yii::getPathOfAlias("webroot") . '/protected/vendor/TCPDF/tcpdf.php');

    $repo=new OrderServiceImpl('Order');

    $orders=$this->getReporteOcAutorizadasData($projectid, $budgetid);

    $project = Project::model()->findByPk($projectid);
    $ydate = date("d-m-Y");
    $budget = Budget::model()->findByPk($budgetid);
    $budgetstatus=$budget->active==1?'Activo':'Inactivo';

    $encabezado="
    <table width=\"100%\">
      <tr>
        <td style=\"width:50%\">
          <table>
            <tr><td><b>Proyecto:</b></td><td>$project->name</td></tr>
            <tr><td><b>Presupuesto:</b></td><td>$budget->name</td></tr>
            <tr><td><b>Plaza:</b></td><td>$project->location</td></tr>
            <tr><td><b>Direcci&oacute;n:</b></td><td>$project->address</td></tr>
          </table>
        </td>
        <td style=\"width:50%\">
          <table>
            <tr><td><b>Fecha:</b></td><td>$ydate</td></tr>
            <tr><td></td><td></td></tr>
            <tr><td></td><td></td></tr>
            <tr><td><b>Estatus del presupuesto:</b></td><td>$budgetstatus</td></tr>
          </table>
        </td>
      </tr>
    </table><br/><br/>";

    $html="<table width='100 %'>";
    $html.="<tr><hr>";
    $html.="<td style=\"width:5%\"><b>Folio</b></td>
            <td style=\"width:14%\"><b>Partida</b></td>
            <td style=\"width:14%\"><b>Subpartida</b></td>
            <td style=\"width:47%\"><b>Proveedor</b></td>
            <td style=\"width:10%;\"><b>Monto</b></td>
            <td style=\"width:10%; text-align:right;\"><b>Tope ppto.</b></td>";
    $html.="<hr/><br/></tr>";

    foreach ($orders as $order) {
      $html.="<tr>";
      $html.="<td>".$order["Folio1"]."</td>";
      $html.="<td>".$order["Partida"]."</td>";
      $html.="<td>".$order["Subpartida"]."</td>";
      $html.="<td>".$order["Proveedor"]."</td>";
      $html.="<td style=\"text-align:right;\">".number_format(round($order["Monto"], 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
      $html.="<td style=\"text-align:right;\">".number_format(round($order["TopePresupuesto"], 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
      $html.="</tr>";
    }

    $totales=$this->getReporteOcAutorizadasTotales($projectid, $budgetid);
    $totalmonto=0;
    $totalppto=0;

    foreach ($totales as $item) {
      $totalmonto=$item["TotalMonto"];  
      $totalppto=$item["TotalPresupuesto"];  
    }

    $html.="<tr><td colspan=\"6\"><br/></td></tr><tr>";
    $html.="<td colspan=\"5\" style=\"text-align: right;\">$ ".number_format(round($totalmonto, 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
    $html.="<td style=\"text-align: right;\">$ ".number_format(round($totalppto, 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
    $html.="</tr>";
    $html.="</table>";

    ob_start();
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator("SIDIC");
    $pdf->SetAuthor('SIDIC');
    $pdf->SetTitle("Reporte de ordenes autorizadas");
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', '', 9);

    // set default header data
    $pdf->SetHeaderData("/../../../../../images/enterprise-logo.jpg", 30, "SIDIC", "Reporte de ordenes autorizadas");

    //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->writeHTML($encabezado.$html, true, false, false, false, '');
    $pdf->lastPage();

    ob_end_clean();
    $pdf->Output('ReporteDeOrdenesAutorizadas.pdf', 'I');
  }

  /**
  * Genera en pdf un reporte general de ordenes de compra
  * @param ProjectId
  * @param BudgetId
  */
  public function getReporteGeneralPdf($projectid, $budgetid){
    require_once(Yii::getPathOfAlias("webroot") . '/protected/vendor/TCPDF/tcpdf.php');

    $repo=new OrderServiceImpl('Order');

    $orders=$this->getReporteGeneralData($projectid, $budgetid);

    $project = Project::model()->findByPk($projectid);
    $ydate = date("d-m-Y");
    $budget = Budget::model()->findByPk($budgetid);
    $budgetstatus=$budget->active==1?'Activo':'Inactivo';

    $encabezado="
    <table width=\"100%\">
      <tr>
        <td style=\"width:50%\">
          <table>
            <tr><td><b>Proyecto:</b></td><td>$project->name</td></tr>
            <tr><td><b>Presupuesto:</b></td><td>$budget->name</td></tr>
            <tr><td><b>Plaza:</b></td><td>$project->location</td></tr>
            <tr><td><b>Direcci&oacute;n:</b></td><td>$project->address</td></tr>
          </table>
        </td>
        <td style=\"width:50%\">
          <table>
            <tr><td><b>Fecha:</b></td><td>$ydate</td></tr>
            <tr><td></td><td></td></tr>
            <tr><td></td><td></td></tr>
            <tr><td><b>Estatus del presupuesto:</b></td><td>$budgetstatus</td></tr>
          </table>
        </td>
      </tr>
    </table><br/><br/>";

    $html="<table width='100 %'>";
    $html.="<tr><hr>";
    $html.="<td style=\"width:5%\"><b>Folio</b></td>
            <td style=\"width:14%\"><b>Partida</b></td>
            <td style=\"width:14%\"><b>Subpartida</b></td>
            <td style=\"width:44%\"><b>Proveedor</b></td>
            <td style=\"width:10%\"><b>Estatus</b></td>
            <td style=\"width:10%; text-align:right;\"><b>Tope ppto.</b></td>";
    $html.="<hr/><br/></tr>";

    foreach ($orders as $order) {
      $html.="<tr>";
      $html.="<td>".$order["Folio1"]."</td>";
      $html.="<td>".$order["Partida"]."</td>";
      $html.="<td>".$order["Subpartida"]."</td>";
      $html.="<td>".$order["Proveedor"]."</td>";
      $html.="<td>".$order["Estatus"]."</td>";
      $html.="<td style=\"text-align:right;\">".number_format(round($order["TopePresupuesto"], 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
      $html.="</tr>";
    }

    $totalpresupuesto=$this->getReporteGeneralTotalPresupuesto($projectid, $budgetid);

    $html.="<tr><td colspan=\"6\"><br/></td></tr><tr>";
    $html.="<td colspan=\"6\" style=\"text-align: right;\">$ ".number_format(round($totalpresupuesto, 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
    $html.="</tr>";
    $html.="</table>";

    ob_start();
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator("SIDIC");
    $pdf->SetAuthor('SIDIC');
    $pdf->SetTitle("Reporte General de ordenes de compra");
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', '', 9);

    // set default header data
    $pdf->SetHeaderData("/../../../../../images/enterprise-logo.jpg", 30, "SIDIC", "Reporte General de ordenes de compra");

    //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->writeHTML($encabezado.$html, true, false, false, false, '');
    $pdf->lastPage();

    ob_end_clean();
    $pdf->Output('ReporteGeneralDeOrdenesDeCompra.pdf', 'I');
  }

  /**
  * Genera en pdf un reporte de ordenes de compra por autorizar
  * @param ProjectId
  * @param BudgetId
  */
  public function getReporteOcPorAutorizarPdf($projectid, $budgetid){
    require_once(Yii::getPathOfAlias("webroot") . '/protected/vendor/TCPDF/tcpdf.php');

    $repo=new OrderServiceImpl('Order');

    $orders=$this->getReporteOcPorAutorizarData($projectid, $budgetid);

    $project = Project::model()->findByPk($projectid);
    $ydate = date("d-m-Y");
    $budget = Budget::model()->findByPk($budgetid);
    $budgetstatus=$budget->active==1?'Activo':'Inactivo';

    $encabezado="
    <table width=\"100%\">
      <tr>
        <td style=\"width:50%\">
          <table>
            <tr><td><b>Proyecto:</b></td><td>$project->name</td></tr>
            <tr><td><b>Presupuesto:</b></td><td>$budget->name</td></tr>
            <tr><td><b>Plaza:</b></td><td>$project->location</td></tr>
            <tr><td><b>Direcci&oacute;n:</b></td><td>$project->address</td></tr>
          </table>
        </td>
        <td style=\"width:50%\">
          <table>
            <tr><td><b>Fecha:</b></td><td>$ydate</td></tr>
            <tr><td></td><td></td></tr>
            <tr><td></td><td></td></tr>
            <tr><td><b>Estatus del presupuesto:</b></td><td>$budgetstatus</td></tr>
          </table>
        </td>
      </tr>
    </table><br/><br/>";

    $html="<table width='100 %'>";
    $html.="<tr><hr>";
    $html.="<td style=\"width:5%\"><b>Folio</b></td>
            <td style=\"width:14%\"><b>Partida</b></td>
            <td style=\"width:14%\"><b>Subpartida</b></td>
            <td style=\"width:36%\"><b>Proveedor</b></td>
            <td style=\"width:10%;\"><b>Monto</b></td>
            <td style=\"width:10%\"><b>Colocada</b></td>
            <td style=\"width:10%; text-align:right;\"><b>Tope ppto.</b></td>";
    $html.="<hr/><br/></tr>";

    foreach ($orders as $order) {
      $html.="<tr>";
      $html.="<td>".$order["Folio1"]."</td>";
      $html.="<td>".$order["Partida"]."</td>";
      $html.="<td>".$order["Subpartida"]."</td>";
      $html.="<td>".$order["Proveedor"]."</td>";
      $html.="<td style=\"text-align:right;\">".number_format(round($order["Monto"], 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
      $html.="<td>".$order["Colocada"]."</td>";
      $html.="<td style=\"text-align:right;\">".number_format(round($order["TopePresupuesto"], 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
      $html.="</tr>";
    }

    $totalpresupuesto=$this->getReporteOcPorAutorizarTotalPresupuesto($projectid, $budgetid);

    $html.="<tr><td colspan=\"7\"><br/></td></tr><tr>";
    $html.="<td colspan=\"7\" style=\"text-align: right;\">$ ".number_format(round($totalpresupuesto, 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
    $html.="</tr>";
    $html.="</table>";

    ob_start();
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator("SIDIC");
    $pdf->SetAuthor('SIDIC');
    $pdf->SetTitle("Reporte de ordenes por autorizar");
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetHeaderData("/../../../../../images/enterprise-logo.jpg", 30, "SIDIC", "Reporte de ordenes por autorizar");
    $pdf->AddPage();
    $pdf->writeHTML($encabezado.$html, true, false, false, false, '');
    $pdf->lastPage();

    ob_end_clean();
    $pdf->Output('ReporteDeOrdenesPorAutorizar.pdf', 'I');
  }

  /**
  * Genera en pdf un reporte de ordenes recibidas
  * @param ProjectId
  * @param BudgetId
  */
  public function getReporteOcRecibidasPdf($projectid, $budgetid){
    require_once(Yii::getPathOfAlias("webroot") . '/protected/vendor/TCPDF/tcpdf.php');

    $repo=new OrderServiceImpl('Order');

    $orders=$this->getReporteOcRecibidasData($projectid, $budgetid);

    $project = Project::model()->findByPk($projectid);
    $ydate = date("d-m-Y");
    $budget = Budget::model()->findByPk($budgetid);
    $budgetstatus=$budget->active==1?'Activo':'Inactivo';

    $encabezado="
    <table width=\"100%\">
      <tr>
        <td style=\"width:50%\">
          <table>
            <tr><td><b>Proyecto:</b></td><td>$project->name</td></tr>
            <tr><td><b>Presupuesto:</b></td><td>$budget->name</td></tr>
            <tr><td><b>Plaza:</b></td><td>$project->location</td></tr>
            <tr><td><b>Direcci&oacute;n:</b></td><td>$project->address</td></tr>
          </table>
        </td>
        <td style=\"width:50%\">
          <table>
            <tr><td><b>Fecha:</b></td><td>$ydate</td></tr>
            <tr><td></td><td></td></tr>
            <tr><td></td><td></td></tr>
            <tr><td><b>Estatus del presupuesto:</b></td><td>$budgetstatus</td></tr>
          </table>
        </td>
      </tr>
    </table><br/><br/>";

    $html="<table width='100 %'>";
    $html.="<tr><hr>";
    $html.="<td style=\"width:5%\"><b>Folio</b></td>
            <td style=\"width:14%\"><b>Partida</b></td>
            <td style=\"width:14%\"><b>Subpartida</b></td>
            <td style=\"width:47%\"><b>Proveedor</b></td>
            <td style=\"width:10%;\"><b>Monto</b></td>
            <td style=\"width:10%; text-align:right;\"><b>Tope ppto.</b></td>";
    $html.="<hr/><br/></tr>";

    foreach ($orders as $order) {
      $html.="<tr>";
      $html.="<td>".$order["Folio1"]."</td>";
      $html.="<td>".$order["Partida"]."</td>";
      $html.="<td>".$order["Subpartida"]."</td>";
      $html.="<td>".$order["Proveedor"]."</td>";
      $html.="<td style=\"text-align:right;\">".number_format(round($order["Monto"], 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
      $html.="<td style=\"text-align:right;\">".number_format(round($order["TopePresupuesto"], 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
      $html.="</tr>";
    }

    $totales=$this->getReporteOcRecibidasTotales($projectid, $budgetid);
    $totalmonto=0;
    $totalppto=0;

    foreach ($totales as $item) {
      $totalmonto=$item["TotalMonto"];  
      $totalppto=$item["TotalPresupuesto"];  
    }

    $html.="<tr><td colspan=\"6\"><br/></td></tr><tr>";
    $html.="<td colspan=\"5\" style=\"text-align: right;\">$ ".number_format(round($totalmonto, 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
    $html.="<td style=\"text-align: right;\">$ ".number_format(round($totalppto, 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
    $html.="</tr>";
    $html.="</table>";

    ob_start();
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator("SIDIC");
    $pdf->SetAuthor('SIDIC');
    $pdf->SetTitle("Reporte de ordenes autorizadas");
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', '', 9);

    // set default header data
    $pdf->SetHeaderData("/../../../../../images/enterprise-logo.jpg", 30, "SIDIC", "Reporte de ordenes recibidas");

    //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->writeHTML($encabezado.$html, true, false, false, false, '');
    $pdf->lastPage();

    ob_end_clean();
    $pdf->Output('ReporteDeOrdenesRecibidas.pdf', 'I');
  }

  /**
  * Genera en pdf un reporte de ordenes pagadas
  * @param ProjectId
  * @param BudgetId
  */
  public function getReporteOcPagadasPdf($projectid, $budgetid){
    require_once(Yii::getPathOfAlias("webroot") . '/protected/vendor/TCPDF/tcpdf.php');

    $repo=new OrderServiceImpl('Order');

    $orders=$this->getReporteOcPagadasData($projectid, $budgetid);

    $project = Project::model()->findByPk($projectid);
    $ydate = date("d-m-Y");
    $budget = Budget::model()->findByPk($budgetid);
    $budgetstatus=$budget->active==1?'Activo':'Inactivo';

    $encabezado="
    <table width=\"100%\">
      <tr>
        <td style=\"width:50%\">
          <table>
            <tr><td><b>Proyecto:</b></td><td>$project->name</td></tr>
            <tr><td><b>Presupuesto:</b></td><td>$budget->name</td></tr>
            <tr><td><b>Plaza:</b></td><td>$project->location</td></tr>
            <tr><td><b>Direcci&oacute;n:</b></td><td>$project->address</td></tr>
          </table>
        </td>
        <td style=\"width:50%\">
          <table>
            <tr><td><b>Fecha:</b></td><td>$ydate</td></tr>
            <tr><td></td><td></td></tr>
            <tr><td></td><td></td></tr>
            <tr><td><b>Estatus del presupuesto:</b></td><td>$budgetstatus</td></tr>
          </table>
        </td>
      </tr>
    </table><br/><br/>";

    $html="<table width='100 %'>";
    $html.="<tr><hr>";
    $html.="<td style=\"width:5%\"><b>Folio</b></td>
            <td style=\"width:14%\"><b>Partida</b></td>
            <td style=\"width:14%\"><b>Subpartida</b></td>
            <td style=\"width:37%\"><b>Proveedor</b></td>
            <td style=\"width:10%;\"><b>Monto</b></td>
            <td style=\"width:10%;\"><b>Fecha de pago</b></td>
            <td style=\"width:10%;\"><b>Forma de pago</b></td>";
    $html.="<hr/><br/></tr>";

    foreach ($orders as $order) {
      $html.="<tr>";
      $html.="<td>".$order["Folio1"]."</td>";
      $html.="<td>".$order["Partida"]."</td>";
      $html.="<td>".$order["Subpartida"]."</td>";
      $html.="<td>".$order["Proveedor"]."</td>";
      $html.="<td style=\"text-align:right;\">".number_format(round($order["Monto"], 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
      $html.="<td>".$order["FechaDePago"]."</td>";
      $html.="<td>".$order["MetodoDePago"]."</td>";
      $html.="</tr>";
    }

    $totalmonto=$this->getReporteOcPagadasTotalMonto($projectid, $budgetid);

    $html.="<tr><td colspan=\"5\"><br/></td></tr><tr>";
    $html.="<td colspan=\"5\" style=\"text-align: right;\">$ ".number_format(round($totalmonto, 2, PHP_ROUND_HALF_UP), 2, '.', ',')."</td>";
    $html.="</tr>";
    $html.="</table>";

    ob_start();
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator("SIDIC");
    $pdf->SetAuthor('SIDIC');
    $pdf->SetTitle("Reporte de ordenes pagadas");
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', '', 9);

    // set default header data
    //$pdf->SetHeaderData("", "0px", "SIDIC", "Reporte de ordenes pagadas");
    $pdf->SetHeaderData("/../../../../../images/enterprise-logo.jpg", 30, "SIDIC", "Reporte de ordenes pagadas");

    //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->writeHTML($encabezado.$html, true, false, false, false, '');
    $pdf->lastPage();

    ob_end_clean();
    $pdf->Output('ReporteDeOrdenesPagadas.pdf', 'I');
  }

}