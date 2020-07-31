<?php
/** @var \bbn\mvc\model $model */

$model->data['limit'] = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
$model->data['start'] = isset($model->data['start']) && is_int($model->data['start']) ? $model->data['start'] : 0;
$grid = new \bbn\appui\grid($model->db, $model->data, [
  'table' => 'bbn_cron',
  'fields' => [],
  'order' => [[
    'field' => 'next',
    'dir' => 'ASC'
  ]],
  'filters' => [[
    'field' => 'active',
    'value' => 1
  ], [
    'logic' => 'OR',
    'conditions' => [[
      'field' => 'next',
      'operator' => '>',
      'exp' => 'NOW()'
    ], [
      'field' => 'pid',
      'operator' => 'isnotnull'
    ]]
  ]]
]);
if ($grid->check()) {
  return ['cron' => $grid->get_datatable()];
}