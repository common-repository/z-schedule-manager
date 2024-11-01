<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Publish_Wordpress_View_Menubar_SM_HC_MVC extends _HC_MVC 
{
	public function render()
	{
		$menubar = $this->make('/html/view/container');
		return $menubar;
	}
}