<?php
/*
 * Describe what it does!
 *
 * @var $ctrl \bbn\mvc\controller 
 *
 */
if (count($ctrl->arguments)) {
	if ( !empty($ctrl->post['data']) && !empty($ctrl->post['data']['fpath']) ){
		$ctrl->add_data(['fpath' => $ctrl->post['data']['fpath']]);
		unset($ctrl->post['data']);
	}
	$ctrl->add_data(['id' => $ctrl->arguments[0]])->action();
}