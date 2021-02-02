<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\Mvc\Model*/
$r = ['success' => false, 'check' => $model->inc->cron->check()];

if ($model->inc->cron->check()) {
  $tasks = $model->inc->cron->getManager()->getNextRows(100, 3600);
  $failed = $model->inc->cron->getManager()->getFailed();
  $tasks = array_filter($tasks, function($t) use($failed){
    return \bbn\X::find($failed, ['id' => $t['id']]) === null;
  });
  $r['success'] = true;
  $r['tasks'] = $model->inc->cron->getManager()->getNextRows(100, 3600);
  $f['failed'] = $failed;
}
return $r;