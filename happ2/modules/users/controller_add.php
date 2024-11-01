<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_Controller_Add_HC_MVC extends _HC_MVC
{
	public function route_index()
	{
		$post = $this->make('/input/lib')->post();
		if( ! $post ){
			return;
		}

		$form = $this->make('form');
		$form->grab( $post );
		$this_form_values = $form->values();

		$valid = $form->validate();
		if( ! $valid ){
			$form_errors = array(
				$form->slug()	=> $form->errors()
				);
			$form_values = array(
				$form->slug()	=> $this_form_values
				);

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

	/* API */
		$api = $this->make('/http/lib/api')
			->request('/api/users')
			;

		$api->post( $values );

		$status_code = $api->response_code();
		$api_out = $api->response();

		if( substr($status_code, 0, 1) != '2' ){
			$form_errors = array(
				$form->slug()	=> $api_out['errors']
				);
			$form_values = array(
				$form->slug()	=> $this_form_values
				);

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

	// OK
		$redirect_to = $this->make('/html/view/link')
			->to('')
			->href()
			;

		return $this->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}
}