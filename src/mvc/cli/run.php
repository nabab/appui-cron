<?php
\bbn\x::log("We execute from plugin/mvc/cli/run", 'cron2');
/**
 * @var \bbn\mvc\controller $ctrl
 */
if ( isset($ctrl->post['type']) ){
  if ( !defined('BBN_EXTERNAL_USER_ID') && defined('BBN_EXTERNAL_USER_EMAIL') ){
    define('BBN_EXTERNAL_USER_ID', $ctrl->db->select_one('bbn_users', 'id', ['email' => BBN_EXTERNAL_USER_EMAIL]));
  }
  $cron = new \bbn\cron($ctrl->db, $ctrl);
  $runner = $cron->get_runner($ctrl->post);
  var_dump("Hello from RUN");
  \bbn\x::log("Type is ok: ".$ctrl->post['type'], 'cron2');
  $runner->run();
}
