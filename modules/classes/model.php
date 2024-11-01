<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Classes_Model_SM_HC_MVC extends _HC_ORM_WP_Custom_Post
{
	public function _init()
	{
		$this->storable->post_type = $this->app_short_name() . '-' . 'class';
		return $this;
	}
}