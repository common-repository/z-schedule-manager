<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_View_Zoom_SM_HC_MVC extends _HC_MVC
{
	public function render( $post )
	{
		$id = $post->ID;

		$form = $this->make('form');

		$current_screen = get_current_screen();
		if( $current_screen->action != 'add' ){
			$api = $this->make('/http/lib/api')
				->request('/api/schedule')
				->add_param('id', $id)
				->add_param('with', '-all-')
				;

			$model = $api
				->get()
				->response()
				;

			$values = $form->run('from-model', $model);
			$form->set_values( $values );
		}

		$out = $this->make('/html/view/form')
			->set_route('/schedule/update')
			->run('set-form', $form)
			;

		$inputs = $form->inputs();
		foreach( $inputs as $input_name => $input ){
			$input_view = $this->make('/html/view/label-input')
				->set_label( $input->label() )
				->set_content( $input )
				->set_error( $input->error() )
				;
			$out
				->add( $input_view )
				;
		}

		return $out;
	}

	public function echo_render( $post )
	{
		echo $this->render( $post );
	}
}