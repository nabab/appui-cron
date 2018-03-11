<?php
/**
 * User: BBN
 * Date: 10/03/2018
 * Time: 15:41
 */

/** @var \bbn\mvc\controller $ctrl The current controller */

$admin = new \bbn\user\users($ctrl->db);
$active = $ctrl->plugin_data_path().'.active';
$file = $ctrl->plugin_data_path().'.cron';
$filepid = $ctrl->plugin_data_path().'.cronid';
if ( file_exists($filepid) ){
  $pid_content = explode('|', file_get_contents($filepid));
  if ( $pid_content[1] && file_exists('/proc/'.$pid_content[0]) ){
    die(\bbn\x::dump("There is already a process running with PID ".file_get_contents($filepid)));
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
//while ( file_exists($file) && file_exists($active) ){
  if ( $timer->measure('timeout') > 7200 ){
    @unlink($filepid);
    die("Ending because of timeout");
  }
  if ( $task = $cron->get_next() ){
    $dir = \bbn\file\dir::create_path($ctrl->plugin_data_path().'cron/'.$task['id']);
    $filepid = $dir.'/.pid';
    if ( file_exists($filepid) ){
      $pid_content = explode('|', file_get_contents($filepid));
      if ( $pid_content[1] && file_exists('/proc/'.$pid_content[0]) ){
        exit("File already running");
      }
    }
    \bbn\appui\cron::execute('cron/run', $dir.'/'.date('YmdHis').'.txt');
  }
  /*
while ( $task = $cron->get_next() ){

  if ( $pid = $cron->run($task['id']) ){

  }
  }
  */
  @ob_end_flush();
  sleep(60);
//}