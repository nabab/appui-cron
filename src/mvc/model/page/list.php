<?php
/** @var $model \bbn\Mvc\Model */
$grid = new \bbn\Appui\Grid($model->db, $model->data, [
  'table' => 'bbn_cron'
]);
if ( $grid->check() ){
  $system = new bbn\File\System($model->dataPath());
  $path =  $model->inc->cron->getPath().'error/tasks/';
  $d = $grid->getDatatable();
  if ( !empty($d['data']) ){
    foreach ( $d['data'] as $i => $t ){
      if ( !empty($t['cfg']) && ($cfg = json_decode($t['cfg'], true)) ){
        $d['data'][$i] = array_merge($t, $cfg);
        unset($d['data'][$i]['cfg']);
      }
      $d['data'][$i]['num'] = $system->getNumFiles($path.$t['id']);
    }
  }
  return $d;
}