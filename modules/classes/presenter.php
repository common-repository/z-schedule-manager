<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Classes_Presenter_SM_HC_MVC extends _HC_MVC_Model_Presenter
{
	public function present_title()
	{
		$return = $this->data('post_title');
		return $return;
	}
}