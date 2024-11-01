<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_View_Index_Menubar_HC_MVC extends _HC_MVC 
{
	public function render()
	{
		$menubar = $this->make('/html/view/container');
		return $menubar;
	}
}