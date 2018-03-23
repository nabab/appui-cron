<?php
/** @var $model \bbn\mvc\model */
$grid = new \bbn\appui\grid($model->db, $model->data, [
  'table' => 'bbn_cron'
]);
if ( $grid->check() ){
  return $grid->get_datatable();
}