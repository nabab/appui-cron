<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\model*/
$r = ['success' => false, 'check' => $model->inc->cron->check()];

if ($model->inc->cron->check()) {
  $r['success'] = true;
  $r['tasks'] = $model->inc->cron->get_manager()->get_next_rows(100, 3600);
}
return $r;