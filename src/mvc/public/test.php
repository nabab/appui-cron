<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 24/01/2017
 * Time: 05:00
 */
/** @var \bbn\Mvc\Controller $ctrl */

;
$o = [];
$cmd = 'cron/bg';
/*
$outputfile = $ctrl->pluginPath('appui-cron').'cron/output.txt';
$pidfile = $ctrl->dataPath().'cron/pid.txt';
\bbn\X::hdump(
  //exec("php -f router.php cron/bg", $o),
  exec(sprintf("php -f router.php %s > %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile), $o),
	$o,
  'say_plugin() : '.$ctrl->getPlugin(),
  'plugin_url() : '.$ctrl->pluginUrl('appui-cron'),
  'plugin_path() : '.$ctrl->pluginPath('appui-cron'),
  'get_plugin_model() : '.$ctrl->getPluginModel('buzz', ['var' => 'woo'])
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
//echo $ctrl->getPluginView('buzz', ['var' => 'woo']);

//$t = new \boo\test();
//$t->getName();