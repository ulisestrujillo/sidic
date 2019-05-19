<title>SIDIC</title>
<!-- Favicon-->
<link rel="icon" href="favicon.ico" type="image/x-icon">

 <!-- Bootstrap CSS CDN -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<!-- Our Custom CSS -->
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/style.css">
</head>

    <body>

        <div class="wrapper">
            <!-- Sidebar Holder -->
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h3>SIDIC</h3>
                </div>

                <ul class="list-unstyled components">
                    <li class="active">
                        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">
                            <i class="glyphicon glyphicon-home"></i>
                            Home
                        </a>
                        <ul class="collapse list-unstyled" id="homeSubmenu">
                            <li><a href="#">Home 1</a></li>
                            <li><a href="#">Home 2</a></li>
                            <li><a href="#">Home 3</a></li>
                        </ul>
                    </li>
                    <li>
                        <!--<a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false">
                            <i class="glyphicon glyphicon-duplicate"></i>
                            Catálogos
                        </a>-->
                        <!--<ul class="collapse list-unstyled" id="pageSubmenu">-->
                            <li> <a href="<?php echo Yii::app()->createUrl('/supplier/admin'); ?>">Proveedores</a></li>
                            <li> <a href="<?php echo Yii::app()->createUrl('/project/admin'); ?>">Proyecto</a></li>
                            <li> <a href="<?php echo Yii::app()->createUrl('/UserSystem/admin'); ?>">Usuarios</a></li>
                        <!--</ul>-->
                    </li>
                    <li>
                        <a href="#pageCuenta" data-toggle="collapse" aria-expanded="false">
                            <i class="glyphicon glyphicon-duplicate"></i>
                            Cuenta
                        </a>
                        <ul class="collapse list-unstyled" id="pageCuenta">
                            <li> <a href="<?php echo Yii::app()->createUrl('/user/profile'); ?>">Datos Físcales</a></li>
                        </ul>
                    </li>
                    <!--<li>
                        <a href="#pageLogotipo" data-toggle="collapse" aria-expanded="false">
                            <i class="glyphicon glyphicon-duplicate"></i>
                            Configuración
                        </a>
                        <ul class="collapse list-unstyled" id="pageLogotipo">
                            <li> <a href="<?php echo Yii::app()->createUrl('/site/logo'); ?>">Logotipo</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="glyphicon glyphicon-paperclip"></i>
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="glyphicon glyphicon-send"></i>
                            Contact
                        </a>
                    </li>
                -->
                    <li><a href="<?php echo Yii::app()->createUrl('/site/logout'); ?>" role="button">
                      <i class="glyphicon glyphicon-log-out"></i>
                      Salir</a></li>
                </ul>

            </nav>

            <!-- Page Content Holder -->
            <div class="container-fluid">
                <nav class="navbar navbar-default">
                    <div class="navbar-header">
                    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
                        <i class="glyphicon glyphicon-resize-horizontal"></i>
                    </button>
                    </div>

                    <!--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                          <li><a class="btn btn-default" href="<?php //echo Yii::app()->createUrl('/site/logout'); ?>" role="button">Salir</a></li>
                        </ul>
                    </div>-->
                </nav>

                <div id="content">
  <?php echo $content; ?>

                </div>
                
            </div>

        </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

     <script type="text/javascript">
         $(document).ready(function () {
             $('#sidebarCollapse').on('click', function () {
                 $('#sidebar').toggleClass('active');
             });
         });
     </script>


    </body>
</html>
