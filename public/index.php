<?php
define('APPLICATION_PATH', dirname(__DIR__));

$application = new Yaf\Application(APPLICATION_PATH . "/conf/application.ini");
$application->bootstrap()->run();

