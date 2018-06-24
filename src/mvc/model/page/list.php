<?php
/** @var $model \bbn\mvc\model */
$grid = new \bbn\appui\grid($model->db, $model->data, [
  'table' => 'bbn_cron',
  'filters' => ['actif' => 1]
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
  return $d;
}