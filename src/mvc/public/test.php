<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 24/01/2017
 * Time: 05:00
 */
/** @var \bbn\mvc\controller $ctrl */

;
$o = [];
$cmd = 'cron/bg';
$outputfile = BBN_DATA_PATH.'cron/output.txt';
$pidfile = BBN_DATA_PATH.'cron/pid.txt';
\bbn\x::hdump(
  //exec("php -f router.php cron/bg", $o),
  exec(sprintf("php -f router.php %s > %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile), $o),
	$o,
  'say_plugin() : '.$ctrl->say_plugin(),
  'plugin_url() : '.$ctrl->plugin_url('appui-cron'),
  'plugin_path() : '.$ctrl->plugin_path('appui-cron'),
  'get_plugin_model() : '.$ctrl->get_plugin_model('buzz', ['var' => 'woo'])
);

//exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile));
//This launches the command $cmd, redirects the command output to $outputfile, and writes the process id to $pidfile.

//That lets you easily monitor what the process is doing and if it's still running.
/*
function isRunning($pid){
    try{
        $result = shell_exec(sprintf("ps %d", $pid));
        if( count(preg_split("/\n/", $result)) > 2){
            return true;
        }
    }catch(Exception $e){}

    return false;
}
*/
//echo $ctrl->get_plugin_view('buzz', ['var' => 'woo']);

//$t = new \boo\test();
//$t->say_name();