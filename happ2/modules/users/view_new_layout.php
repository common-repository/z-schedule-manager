<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_View_New_Layout_HC_MVC extends _HC_MVC
{
	public function render( $content )
	{
		$header = $this->make('view/new/header');
		$menubar = $this->make('view/new/menubar');

		$out = $this->make('/layout/view/content-header-menubar')
			->set_content( $content )
			->set_header( $header )
			->set_menubar( $menubar )
			;

		return $out;
	}
}