<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\model*/
$r = ['success' => false, 'check' => $model->inc->cron->check()];

if ($model->inc->cron->check()) {
  $tasks = $model->inc->cron->get_manager()->get_next_rows(100, 3600);
  $failed = $model->inc->cron->get_manager()->get_failed();
  $tasks = array_filter($tasks, function($t) use($failed){
    return \bbn\x::find($failed, ['id' => $t['id']]) === null;
  });
  $r['success'] = true;
  $r['tasks'] = $model->inc->cron->get_manager()->get_next_rows(100, 3600);
  $f['failed'] = $failed;
}
return $r;