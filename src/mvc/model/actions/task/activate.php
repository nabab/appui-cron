<?php

/** @var bbn\Mvc\Model $model */
if ( isset($model->data['id']) ){
  $model->data['res']['success'] = $model->inc->cron->getManager()->activate($model->data['id']);
}
return $model->data['res'];