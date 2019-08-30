<?php
/** @var $model \bbn\mvc\model */
$grid = new \bbn\appui\grid($model->db, $model->data, [
  'table' => 'bbn_cron'
]);
if ( $grid->check() ){
  $d = $grid->get_datatable();
  if ( !empty($d['data']) ){
    foreach ( $d['data'] as $i => $t ){
      if ( !empty($t['cfg']) && ($cfg = json_decode($t['cfg'], true)) ){
        $d['data'][$i] = array_merge($t, $cfg);
        unset($d['data'][$i]['cfg']);
      }
    }
  }
  $system = new bbn\file\system($model->data_path());
  $path =  $model->inc->cron->get_path().'error/tasks/';
  
  
  $d['data'] = array_map(function($a)use($system, $path){
    $a['num'] = $system->get_num_files($path.$a['id']);
    return $a;
  }, $d['data']);
  return $d;
}