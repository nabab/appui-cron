<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 03/12/2016
 * Time: 15:30
 */
$res = ['success' => false];
if ( isset($model->data['id']) ){
  $timer = new \bbn\util\timer();
  $timer->start();
  $cron = $model->db->rselect('bbn_cron', [], ['id' => $model->data['id']]);
  exec('cd '.$model->app_path().';php -f router.php '.$cron['file'].';', $r);
  $res['time'] = $timer->measure();
  $res['success'] = 1;
  $res['file'] = $cron['file'];
  if ( \is_array($r) ){
    $res['output'] = '';
    foreach ( $r as $s ){
      $res['output'] .= '<div class="bbn-form-full">'.nl2br($s, false).'</div>'.PHP_EOL;
    }
  }
}
return $res;