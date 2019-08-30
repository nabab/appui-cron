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
