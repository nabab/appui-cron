<?php
/**
 * @var \bbn\mvc\controller $ctrl
 */
if ( isset($ctrl->post['type']) ){
  if ( !defined('BBN_EXTERNAL_USER_ID') && defined('BBN_EXTERNAL_USER_EMAIL') ){
    define('BBN_EXTERNAL_USER_ID', $ctrl->db->select_one('bbn_users', 'id', ['email' => BBN_EXTERNAL_USER_EMAIL]));
  }
  $cron = new \bbn\cron($ctrl->db, $ctrl);
  $runner = $cron->get_runner($ctrl->post);
  //$runner->output(_('Executing from'), __FILE__);
  //$runner->output(_('Starting at'), date('Y-m-d H:i:s'));
  $runner->run();
}