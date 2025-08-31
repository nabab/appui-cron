<?php
/**
 * What is my purpose?
 *
 **/

use bbn\X;
use bbn\Str;
use bbn\File\System;
set_time_limit(0);
/** @var bbn\Mvc\Controller $ctrl */
$fs = new System();
$path = $ctrl->dataPath() . 'plugins/appui-cron/log';
$fs->cd($path);
$tasks = 'tasks';
$dirs = $fs->getDirs($tasks);
$todo = [
  'cron',
  'poll',
  ...$dirs
];

$json = 0;
$numFiles = 0;
$empty = 0;
$step = 10000;
$current = 0;
$nextStep = 0;
$deleted = 0;
$numEmptyFolders = 0;
//$max = 1000000;
$numTodo = count($todo);
$today = date('Y-m-d');
$forgetContent = ['poll', 'altares'];
foreach ($todo as $dirIdx => $dir) {
  $cronFile = strpos($dir, 'tasks/') === 0 ? $ctrl->db->selectOne('bbn_cron', 'file', ['id' => substr($dir, 6)]) : $dir;
  $years = $fs->getDirs($dir);
  $numYears = count($years);
  foreach ($years as $yearIdx => $yearDir) {
    $months = $fs->getDirs($yearDir);
    $numMonth = count($months);
    foreach ($months as $monthIdx => $monthDir) {
      $days  = $fs->getDirs($monthDir);
      $numDays = count($days);
      foreach ($days as $dayIdx => $dayDir) {
        $newFile = null;
        $newJson = null;
        $filesToDelete = [];
        $bits = $fs->getDirs($dayDir);
        $numBits = count($bits);
        foreach ($bits as $bitIdx => $bit) {
          $files = $fs->getFiles($bit);
          $numFiles += count($fs->getFiles($bit));
          foreach ($files as $f) {
            $content = trim($fs->getContents($f));
            if (empty($content)) {
              $empty++;
            }
            elseif (in_array($cronFile, $forgetContent)) {
              $content = 'Dummy';
            }
            
            $time = X::basename($f, '.txt');
            if (!$newFile) {
              [$y, $m, $d, $h, $mn, $s] = X::split($time, '-');
              $newFile = "$dir/$y/$y-$m-$d.json";
              $newJson = $fs->exists($newFile) ? $fs->decodeContents($newFile, 'json', true) : [];
            }
            if (!X::getRow($newJson, ['time' => $time])) {
              $newJson[] = ['time' => $time, 'output' => $content];
            }

            $filesToDelete[] = $f;
            $current++;
            if ($current > $nextStep) {
              $nextStep += $step;
              X::log([
                "Current: $cronFile",
                "Folder ".($dirIdx + 1)." out of $numTodo",
                "Year ".($yearIdx + 1)." out of $numYears",
                "Month ".($monthIdx + 1)." out of $numMonth",
                "Day ".($dayIdx + 1)." out of $numDays",
                "Container ".($bitIdx + 1)." out of $numBits",
                "Empty: $empty",
                "Deleted: $deleted",
              ], 'cron-clean');
            }
          }
        }
        if ($newFile) {
          if ($today !== "$y-$m-$d") {
            if (!empty($newJson)) {
              if ($fs->putContents($newFile, json_encode($newJson))) {
                $json++;
                foreach ($filesToDelete as $fd) {
                  $deleted += (int)$fs->delete($fd);
                }
              }
            }
          }
        }
      }
    }
  }
  $numEmptyFolders += $fs->deleteEmptyDirs($dir);
}

if ($deleted) {
  X::log([
    "Current: $cronFile",
    "Folder ".($dirIdx + 1)." out of $numTodo",
    "Year ".($yearIdx + 1)." out of $numYears",
    "Month ".($monthIdx + 1)." out of $numMonth",
    "Day ".($dayIdx + 1)." out of $numDays",
    "Container ".($bitIdx + 1)." out of $numBits",
    "Empty: $empty",
    "Deleted: $deleted",
  ], 'cron-clean');
  X::hdump(
    "DELETED: $deleted",
    "DELETED FOLDERS: $numEmptyFolders",
    "JSON CREATED: $json",
    "TOTAL: $numFiles"
  );
}

