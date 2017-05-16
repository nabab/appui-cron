<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 24/01/2017
 * Time: 05:00
 */
/** @var \bbn\mvc\controller $ctrl */

var_dump(

  $ctrl->say_plugin(),
  $ctrl->plugin_url('appui-cron'),
  $ctrl->plugin_path('bbn-cron'),
  $ctrl->get_plugin_model('buzz', ['var' => 'woo'])
);

echo $ctrl->get_plugin_view('buzz', ['var' => 'woo']);

$t = new \boo\test();
$t->say_name();