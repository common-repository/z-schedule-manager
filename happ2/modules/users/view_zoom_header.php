<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_View_Zoom_Header_HC_MVC extends _HC_MVC
{
	public function render( $model )
	{
		$presenter = $this->make('presenter')
			->set_data($model)
			;
		$return = $presenter->present_title();

		$return = $this->make('/html/view/element')->tag('h1')
			->add($return)
			;

		$id_view = $this->make('/html/view/element')->tag('span')
			->add( 'id: ' . $model['id'] )
			->add_attr('class', 'hc-right-sm')
			->add_attr('class', 'hc-theme-block-info')
			->add_attr('class', 'hc-muted-2')
			;

		$return = $this->make('/html/view/container')
			->add( $id_view )
			->add( $return )
			;

		return $return;
	}
}