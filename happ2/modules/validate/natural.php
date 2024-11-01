<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
// Is a Natural number (0,1,2,3, etc.)
class Validate_Natural_HC_MVC extends _HC_MVC
{
	protected $msg;

	public function _init()
	{
		$this->msg = HCM::__('This field must contain only positive numbers.');
		return $this;
	}

	public function validate( $value )
	{
		$return = (bool) preg_match( '/^[0-9]+$/', $value);
		if( ! $return ){
			$return = $this->msg;
		}
		return $return;
	}

	public function render( $return )
	{
		$return
			->reset_attr('type')
			;

		$return
			->add_attr('type', 'number')
			->add_attr('min', 0)
			// ->add_attr('max', 100)
			->add_attr('step', 1)
			->add_attr('pattern', '\d+')
			->add_attr('oninvalid', "this.setCustomValidity('" . addslashes($this->msg) . "')")
			;
		return $return;
	}
}