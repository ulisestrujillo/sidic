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
 <!-- Bootstrap CSS CDN -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<!-- Our Custom CSS -->
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/style.css">
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

</body>
</html>