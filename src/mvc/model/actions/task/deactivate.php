<?php

/** @var bbn\Mvc\Model $model */
$res = ['success' => false];
if ( isset($model->data['id']) ){
  $res['success'] = $model->inc->cron->getManager()->deactivate($model->data['id']);
}
return $res;