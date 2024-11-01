<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Model_SM_HC_MVC extends _HC_ORM_WP_Custom_Post
{
	public function _init()
	{
		$this->storable->post_type = $this->app_short_name() . '-' . 'schedule';
		return parent::_init();
	}
}