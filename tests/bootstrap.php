<?php
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/../../'),
    get_include_path(),
)));

include "Zend/Loader/Autoloader.php";
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Aptoma_');
$autoloader->registerNamespace('DF_');
$autoloader->pushAutoloader(new DF_Autoloader());
