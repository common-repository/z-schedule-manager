<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_View_New_Header_HC_MVC extends _HC_MVC 
{
	public function render()
	{
		$return = HCM::__('New User');

		$return = $this->make('/html/view/element')->tag('h1')
			->add($return)
			;
		return $return;
	}
}