<?php
/** @var $ctrl \bbn\Mvc\Controller */

if ( !\defined('APPUI_CRON_ROOT') ){
  define('APPUI_CRON_ROOT', $ctrl->pluginUrl('appui-cron').'/');
}
$ctrl->addData([
  'root'=> APPUI_CRON_ROOT,
  'data_path' => $ctrl->pluginDataPath()
]);
$ctrl->addInc('cron', (new \bbn\Cron($ctrl->db, $ctrl)));
return true;