<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Auth_View_Login_Header_HC_MVC extends _HC_MVC 
{
	public function render()
	{
		$return = HCM::__('Log In');
		$return = $this->make('/html/view/element')->tag('h1')
			->add( $return )
			;
		return $return;
	}
}