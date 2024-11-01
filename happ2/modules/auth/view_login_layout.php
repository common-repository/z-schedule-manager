<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Auth_View_Login_Layout_HC_MVC extends _HC_MVC
{
	public function render( $content )
	{
		$header = $this->make('view/login/header');

		$out = $this->make('/layout/view/content-header-menubar')
			->set_content( $content )
			->set_header( $header )
			;

		return $out;
	}
}