<?php
/**
 * @var bbn\Mvc\Controller $ctrl
 */
if ( isset($ctrl->post['type']) ){
  if ( !defined('BBN_EXTERNAL_USER_ID') && defined('BBN_EXTERNAL_USER_EMAIL') ){
    define('BBN_EXTERNAL_USER_ID', $ctrl->db->selectOne('bbn_users', 'id', ['email' => BBN_EXTERNAL_USER_EMAIL]));
  }
  $cron = new \bbn\Cron($ctrl->db, $ctrl);
  $runner = $cron->getRunner($ctrl->post);
  //$runner->output(_('Executing from'), __FILE__);
  //$runner->output(_('Starting at'), Date('Y-m-d H:i:s'));
  $runner->run();
}