<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_Print_View_Form_HC_MVC extends _HC_MVC
{
	public function print_view( $args, $src )
	{
		$is_print_view = $this->make('/print/controller')->run('is-print-view');
		if( $is_print_view ){
			$src->set_readonly();
		}
	}
}