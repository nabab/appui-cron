<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\model*/
$r = ['success' => false];
if ( isset($model->data['ctrl']) ){
  $cron = new \bbn\appui\cron($model->data['ctrl']);
  $r['success'] = true;
  $r['tasks'] = $cron->get_next_rows(100, 3600);
}
return $r;