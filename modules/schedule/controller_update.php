<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Controller_Update_SM_HC_MVC extends _HC_MVC
{
	public function route_index( $id )
	{
		$post = $this->make('/input/lib')->post();
		if( ! $post ){
			return;
		}

		$form = $this->make('form');

		$form_errors = array();
		$form_values = array();

		$form->grab( $post );
		$valid = $form->validate();

		$form_values[ $form->slug() ] = $form->values();
		if( ! $valid ){
			$form_errors[ $form->slug() ] = $form->errors();
		}

		if( $form_errors ){
			$session = $this->make('/session/lib');
			$session
				->set_flashdata('form_errors', $form_errors)
				->set_flashdata('form_values', $form_values)
				;
			$redirect_to = $this->make('/html/view/link')
				->to('-referrer-')
				->href()
				;
			return $this->make('/http/view/response')
				->set_redirect($redirect_to) 
				;
		}

		$values = $form->values();

		$values = $form->run('to-model', $values);
		$values['id'] = $id;

	/* API */
		$api = $this->make('/http/lib/api')
			->request('/api/schedule')
			;

		$api->put( $id, $values );

		$status_code = $api->response_code();
		$api_out = $api->response();

		if( $status_code != '200' ){
			$form->set_errors( $api_out['errors'] );
			$form_errors[ $form->slug() ] = $form->errors();

			$session = $this->make('/session/lib');
			$session
				->set_flashdata('form_errors', $form_errors)
				->set_flashdata('form_values', $form_values)
				;
			$redirect_to = $this->make('/html/view/link')
				->to('-referrer-')
				->href()
				;
			return $this->make('/http/view/response')
				->set_redirect($redirect_to) 
				;
		}
	}
}