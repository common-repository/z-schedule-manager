<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Publish_Wordpress_Bootstrap_SM_HC_MVC extends _HC_MVC
{
	public function run()
	{
		$label = HCM::__('Publish');

		$link = $this->make('/html/view/link')
			->to('')
			->add( $this->make('/html/view/icon')->icon('edit') )
			->add( $label )
			;

		$top_menu = $this->make('/html/view/top-menu')
			->add( $link )
			;
	}
}