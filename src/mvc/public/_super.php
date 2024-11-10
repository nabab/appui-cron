<?php
/** @var bbn\Mvc\Controller $ctrl */

if ( !\defined('APPUI_CRON_ROOT') ){
  define('APPUI_CRON_ROOT', $ctrl->pluginUrl('appui-cron').'/');
}

$ctrl->addData([
  'root'=> APPUI_CRON_ROOT,
  'data_path' => $ctrl->pluginDataPath()
]);
if (empty($ctrl->inc->cron)) {
  $ctrl->addInc('cron', (new \bbn\Cron($ctrl->db, $ctrl)));
}

return true;
