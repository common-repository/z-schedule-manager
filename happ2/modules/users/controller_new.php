<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_Controller_New_HC_MVC extends _HC_MVC
{
	public function route_index()
	{
		$values = array(
			'is_admin'	=> 1,
			);
		$options = array(
			'is_admin'	=> array(1),
			);

		$form = $this->make('form')
			->set_values( $values )
			->set_options( $options )
			;

		$view = $this->make('view/new')
			->run('render', $form)
			;
		$view = $this->make('view/new/layout')
			->run('render', $view)
			;
		$view = $this->make('/layout/view/body')
			->set_content($view)
			;
		return $this->make('/http/view/response')
			->set_view($view)
			;
	}
}