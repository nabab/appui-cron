<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 10/03/2018
 * Time: 23:03
 */
if ( isset($ctrl->post['type']) ){
  $cron = new \bbn\appui\cron($ctrl);
  var_dump("Hello from RUN");
  $cron->run($ctrl->post['type']);
}

/*
if ( file_exists($filepid) ){
  $pid_content = explode('|', file_get_contents($filepid));
  if ( $pid_content[1] && file_exists('/proc/'.$pid_content[0]) ){
    exit("There is already a process running with PID ".file_get_contents($filepid));
  }
}
file_put_contents($filepid, getmypid().'|'.time());
register_shutdown_function(function() use($filepid){
  @unlink($filepid);
});




$timer = new \bbn\util\timer();
$timer->start('timeout');
$timer->start('tokens');
$cron = new \bbn\appui\cron($ctrl);
while ( ($res = $obs->observe($file)) && file_exists($active) ){
  if ( $timer->measure('timeout') > 600 ){
    @unlink($filepid);
    $cron->poll();
    die("Ending because of timeout");
  }
  if ( is_array($res) ){
    \bbn\x::dump("RES", $res);
    foreach ( $res as $id_token => $o ){
      $id_user = $admin->get_user_from_token($id_token);
      $file = BBN_DATA_PATH."users/$id_user/tmp/tokens/$id_token/poller/queue/observer-".time().'.json';
      file_put_contents($file, json_encode(['observers' => $o]));
    }
  }
  sleep(1);
  if ( $timer->measure('tokens') > 120 ){
    echo '?';
    foreach ( $admin->get_old_tokens() as $t ){
      @\bbn\file\dir::delete(BBN_DATA_PATH."users/$id_user/tmp/tokens/$t[id]", true);
      if ( $ctrl->db->delete('bbn_users_tokens', ['id' => $t['id']]) ){
        echo '-';
      }
    }
    $timer->stop('tokens');
    $timer->start('tokens');
  }
  @ob_end_flush();
}
*/
