<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\model*/
if ( isset($model->data['id']) ){
  $model->data['res']['success'] = $model->inc->cron->get_manager()->activate($model->data['id']);
}
return $model->data['res'];