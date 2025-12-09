<?php
$launched = [];

$cron = new \bbn\Cron($ctrl->db, $ctrl);
$files = ['', 'poll', 'cron'];
$globalFile = $cron->getStatusPath('active');
if (is_file($globalFile)) {
  $taskFile = $cron->getStatusPath('cron');
  if (is_file($taskFile)) {
    $taskPid = $cron->getPidPath(['type' => 'cron']);
    $do = true;
    if (is_file($taskPid)) {
      $pid = file_get_contents($taskPid);
      if (is_file('/proc/'.$pid)) {
        $do = false;
      }
    }

    if ($do) {
      $cron->launchTaskSystem();
      $launched[] = 'cron';
    }
  }

  $pollFile = $cron->getStatusPath('poll');
  if (is_file($pollFile)) {
    $pollPid = $cron->getPidPath(['type' => 'poll']);
    $do = true;
    if (is_file($pollPid)) {
      $pid = file_get_contents($pollPid);
      if (is_file('/proc/'.$pid)) {
        $do = false;
      }
    }

    if ($do) {
      $cron->launchPoll();
      $launched[] = 'poll';
    }
  }
}

if (count($launched)) {
  foreach ($launched as $l) {
    echo "Launched $l\n";
  }
}
