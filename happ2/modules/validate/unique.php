<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Validate_Unique_HC_MVC extends _HC_MVC
{
	public function validate( $value, $model, $field, $skip = NULL )
	{
		$return = TRUE;
		$msg = HCM::__('This value is already used');
		$id_field = 'id';

		$api = $this->make('/http/lib/api')
			->request('/api/' . $model)
			;

		$api
			->add_param($field, $value)
			->add_param('limit', 1)
			;

		if( $skip ){
			if( ! is_array($skip) ){
				$skip = array($skip);
			}
			$api
				->add_param($id_field, array('NOTIN', $skip))
				;
		}

		$result = $api
			->get()
			->response()
			;

		if( $result ){
			$return = $msg;
		}

		return $return;

	}
}