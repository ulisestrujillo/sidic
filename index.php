<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',6);

require_once($yii);

// enable gzip compression
$app = Yii::createWebApplication($config);
// attaching a handler to application start
Yii::app()->onBeginRequest = function($event)
{
// starting output buffering with gzip handler
return ob_start("ob_gzhandler");
};
// attaching a handler to application end
Yii::app()->onEndRequest = function($event)
{
// releasing output buffer
return ob_end_flush();
};
$app->run();