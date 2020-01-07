<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/06/2018
 * Time: 10:51
 */
if ($model->inc->cron->check() && !empty($model->data)) {
  if (empty($model->data['id'])) {
    $res = $model->inc->cron->get_manager()->add($model->data);
  }
  else{
    $res = $model->inc->cron->get_manager()->edit($model->data['id'], $model->data);
  }
  return [
    'success' => $res ? true : false,
    'data' => $res
  ];
}
return ['success' => false];