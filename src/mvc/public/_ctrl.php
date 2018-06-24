<?php
/** @var $ctrl \bbn\mvc\controller */

if ( !\defined('APPUI_CRON_ROOT') ){
  define('APPUI_CRON_ROOT', $ctrl->plugin_url('appui-cron').'/');
}
$ctrl->add_data([
  'root' => APPUI_CRON_ROOT,
  'data_path' => $ctrl->plugin_data_path()
]);
$ctrl->add_inc('cron', (new \bbn\appui\cron($ctrl)));
return true;