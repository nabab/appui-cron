<?php
/**
 * @var bbn\Mvc\Controller $ctrl
 */
if ( isset($ctrl->post['type']) ){
  if ( !defined('BBN_EXTERNAL_USER_ID') && defined('BBN_EXTERNAL_USER_EMAIL') ){
    define('BBN_EXTERNAL_USER_ID', $ctrl->db->selectOne('bbn_users', 'id', ['email' => constant('BBN_EXTERNAL_USER_EMAIL')]));
  }
  $cron = new \bbn\Cron($ctrl->db, $ctrl);
  $runner = $cron->getRunner($ctrl->post);
  if (!$runner) {
    // Could not create runner
    exit(1);
  }

  $result = $runner->run();
  if ($result->hasMessage()) {
    fwrite(STDOUT, $result->message . PHP_EOL);
  }

  exit($result->code);
}
