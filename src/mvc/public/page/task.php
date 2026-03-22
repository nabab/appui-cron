<?php
use bbn\Str;

/** @var bbn\Mvc\Controller $ctrl */
if (
  (Str::pos($ctrl->getConstant('baseURL') ?: '', APPUI_CRON_ROOT . 'page/task/') !== 0) &&
  ($id = $ctrl->arguments[0]) &&
  Str::isUid($id) &&
  ($task = $ctrl->inc->cron->getManager()->getCron($id))
){
  if ( is_array($task['cfg']) ){
    $task = array_merge($task, $task['cfg']);
    unset($task['cfg']);
  }
  $ctrl->addData($task);
  $ctrl->obj->url = APPUI_CRON_ROOT . 'page/task/' . $id;
  $ctrl->setIcon('nf nf-seti-project')
       ->combo($task['file'], true);
}