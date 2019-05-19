<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>SIDIC</title>
<!-- Favicon-->
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/plugins/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css"/>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/plugins/morrisjs/morris.css" />
<!-- Custom Css -->
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/main.css">
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/color_skins.css">

</head>
<body class="theme-orange">

<!-- Top Bar -->
<nav class="navbar">
    <div class="col-12">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="<?php echo Yii::app()->createUrl('/site/admin'); ?>">SIDIC</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<section class="">
    <br/><br/><br/>
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-4">
             <?php echo $content; ?>
        </div>
    </div>
</section>

<!-- Jquery Core Js --> 
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/bundles/libscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js --> 
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/bundles/vendorscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js --> 

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/bundles/jvectormap.bundle.js"></script> <!-- JVectorMap Plugin Js -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/bundles/morrisscripts.bundle.js"></script><!-- Morris Plugin Js -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/bundles/sparkline.bundle.js"></script> <!-- Sparkline Plugin Js -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob Plugin Js -->

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/bundles/mainscripts.bundle.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/pages/index.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/pages/charts/jquery-knob.min.js"></script>
</body>
</html>