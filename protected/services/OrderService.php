<?php 

interface OrderService
{

  public function getReporteGeneralTotalPresupuesto($projectid, $budgetid);
  public function getReporteGeneralCount($projectid, $budgetid);
  public function getReporteGeneralData($projectid, $budgetid);

  public function getReporteOcPorAutorizarTotalPresupuesto($projectid, $budgetid);
  public function getReporteOcPorAutorizarCount($projectid, $budgetid);
  public function getReporteOcPorAutorizarData($projectid, $budgetid);

  public function getReporteOcAutorizadasTotales($projectid, $budgetid);
  public function getReporteOcAutorizadasCount($projectid, $budgetid);
  public function getReporteOcAutorizadasData($projectid, $budgetid);

  public function getReporteOcRecibidasTotales($projectid, $budgetid);
  public function getReporteOcRecibidasCount($projectid, $budgetid);
  public function getReporteOcRecibidasData($projectid, $budgetid);

  public function getReporteOcPorPagarTotalMonto($projectid, $budgetid);
  public function getReporteOcPorPagarCount($projectid, $budgetid);
  public function getReporteOcPorPagarData($projectid, $budgetid);

  public function getReporteOcPagadasTotalMonto($projectid, $budgetid);
  public function getReporteOcPagadasCount($projectid, $budgetid);
  public function getReporteOcPagadasData($projectid, $budgetid);

  public function getReporteOcPorPagarPdf($projectid, $budgetid);
  public function getReporteOcAutorizadasPdf($projectid, $budgetid);
  public function getReporteGeneralPdf($projectid, $budgetid);
  public function getReporteOcPorAutorizarPdf($projectid, $budgetid);
  public function getReporteOcRecibidasPdf($projectid, $budgetid);
  public function getReporteOcPagadasPdf($projectid, $budgetid);

}
