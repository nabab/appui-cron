<?php
/*
 * Describe what it does!
 *
 * @var $ctrl \bbn\mvc\controller 
 *
 */
if (count($ctrl->arguments)) {
	$ctrl->add_data(['id' => $ctrl->arguments[0]])->action();
}