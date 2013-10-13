<?
define('ROOT', __DIR__);
$sLgFile = '../lg.php';
if (!defined('LG') AND file_exists($sLgFile))
    include_once($sLgFile);
include 'model/Settings.php';
include 'FacebookEvents.php';
