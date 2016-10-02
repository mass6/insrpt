<?php
// This is global bootstrap for autoloading 
require_once codecept_root_dir() .'/tests/commons/TestCommons.php';

include __DIR__.'/../vendor/autoload.php';
//$app = require_once __DIR__.'/../bootstrap/start.php';
//\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages');
//$app->boot();