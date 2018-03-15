<?php
/*
 * Poller for observers: runs continuously and fills users' notifications files
 *
 **/

/** @var $this \bbn\mvc\controller */
clearstatcache();
$obs = new \bbn\appui\observer($ctrl->db);
$admin = new \bbn\user\users($ctrl->db);
$active = $ctrl->plugin_data_path().'.active';
$file = $ctrl->plugin_data_path().'.poll';
$filepid = $ctrl->plugin_data_path().'.pollid';
if ( !file_exists($file) || !file_exists($active) ){
  @unlink($filepid);
  exit("GETTING OUT BECAUSE .poll OR .active is missing");
}
if ( $file_content = @file_get_contents($filepid) ){
  $pid_content = explode('|', $file_content);
  if ( $pid_content[1] && file_exists('/proc/'.$pid_content[0]) ){
    exit("There is already a process running with PID ".file_get_contents($filepid));
  }
  else{
    echo "DELETING FILEPID AS THE PROCESS IS DEAD ".implode(PHP_EOL, $pid_content).PHP_EOL;
    @unlink($filepid);
  }
}
$cron = new \bbn\appui\cron($ctrl);
file_put_contents($filepid, getmypid().'|'.time());
register_shutdown_function(function() use($filepid, $cron){
  $file_content = @file_get_contents($filepid);
  $ok = true;
  if ( $file_content ){
    echo $file_content.PHP_EOL;
    $pid_content = explode('|', $file_content);
    if ( $pid_content[1] && ($pid_content[0] != getmypid()) ){
      echo 'Different processes: '.$pid_content[0].'/'.getmypid().PHP_EOL;
      $ok = false;
    }
  }
  if ( $ok ){
    echo 'SHUTDOWN '.date('H:i:s');
    @unlink($filepid);
    $cron->poll();
  }
});




$timer = new \bbn\util\timer();
$timer->start('timeout');
$timer->start('tokens');
echo "START: ".date('H:i:s').PHP_EOL;
foreach ( $admin->get_old_tokens() as $t ){
  @\bbn\file\dir::delete(BBN_DATA_PATH."users/$id_user/tmp/tokens/$t[id]", true);
  if ( $ctrl->db->delete('bbn_users_tokens', ['id' => $t['id']]) ){
    echo '-';
  }
}
while ( file_exists($file) && file_exists($active) ){
  $res = $obs->observe($file);
  if ( is_array($res) ){
    foreach ( $res as $id_token => $o ){
      $id_user = $admin->get_user_from_token($id_token);
      $file = BBN_DATA_PATH."users/$id_user/tmp/tokens/$id_token/poller/queue/observer-".time().'.json';
      if ( \bbn\file\dir::create_path(dirname($file)) ){
        file_put_contents($file, json_encode(['observers' => $o]));
      }
    }
  }
  sleep(1);
  if ( $timer->measure('tokens') > 120 ){
    echo '?';
    $admin->clean_tokens();
    $timer->stop('tokens');
    $timer->start('tokens');
  }
  if ( $timer->measure('timeout') > 600 ){
    exit("Ending because of timeout: ".date('H:i:s'));
  }
  clearstatcache();
  @ob_end_flush();
}