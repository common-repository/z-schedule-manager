<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Publish_Wordpress_View_Header_SM_HC_MVC extends _HC_MVC 
{
	public function render()
	{
		$return = HCM::__('Publish');
		$return = $this->make('/html/view/element')->tag('h1')
			->add( $return )
			;
		return $return;
	}
}