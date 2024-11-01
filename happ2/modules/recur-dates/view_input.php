<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Recur_Dates_View_Input_Option_HC_MVC extends _HC_MVC
{
	protected $fields = array();
	protected $pname = NULL;

	public function set_parent_name( $pname )
	{
		$this->pname = $pname;
		return $this;
	}

	public function fields()
	{
		return $this->fields;
	}
}

interface Recur_Dates_View_Input_HC_MVC_Option_Interface
{
	public function set_parent_name( $pname );
	public function label();
	public function fields();
	public function grab( $post );
	public function is_me( $value );
	public function set_value( $value );
}

class Recur_Dates_View_Input_HC_MVC extends HC_Form_Input_Composite2
{
	protected $options = array();

	function set_value( $value )
	{
		$this->value = $value;

		reset( $this->options );
		foreach( $this->options as $type => $option ){
			if( $option->is_me($value) ){
				$this->fields['type']->set_value( $type );
				$option->set_value( $value );
			}
		}

		return $this;
	}

	function set_name( $name )
	{
		parent::set_name( $name );
		return $this->do_init();
	}

	public function do_init()
	{
	// already set
		if( isset($this->fields['type']) ){
			return $this;
		}

		$name = $this->name();

		$this->options['daily'] = $this->make('view/input/option/daily')
			->set_parent_name($name)
			;
		$this->options['daysonoff'] = $this->make('view/input/option/daysonoff')
			->set_parent_name($name)
			;
		$this->options['weekly'] = $this->make('view/input/option/weekly')
			->set_parent_name($name)
			;
		$this->options['monthly_weekday'] = $this->make('view/input/option/monthly-weekday')
			->set_parent_name($name)
			;
		$this->options['yearly'] = $this->make('view/input/option/yearly')
			->set_parent_name($name)
			;

		$type_options = array();
		reset( $this->options );
		foreach( $this->options as $type => $option ){
			$type_options[ $type ] = $option->label();
		}

		$default_option = 'daily';
		$type_name = $name . '_type';
		$this->fields['type'] = $this->make('/form/view/radio')
			->set_inline()
			->set_label( '-nolabel-' )
			->set_name($type_name)
			->set_options( $type_options )
			->set_default($default_option)
			;

		reset( $this->options );
		foreach( $this->options as $type => $option ){
			$option_fields = $option->fields($type_name);

			foreach( $option_fields as $fn => $field ){
				$field
					->set_observe($type_name . '=' . $type)
					;
				$this->fields[$type . '_' . $fn] = $field;
			}
		}

		return $this;
	}

	public function render()
	{
		$display_form = $this->make('/html/view/list');

		foreach( $this->fields as $input_name => $input ){
			$label_row = $this->make('/html/view/label-input')
				->set_label( $input->label() )
				->set_content( $input )
				->set_error( $input->error() )
				;

			$display_form
				->add( $label_row )
				;
		}

		return $this->decorate( $display_form );
	}

	public function grab( $post )
	{
		$value = NULL;

		$this->fields['type']->grab( $post );
		$type = $this->fields['type']->value();

		if( isset($this->options[$type]) ){
			$value = $this->options[$type]->grab( $post );

			// $recur_until = $this->make('/form/view/text')
				// ->set_name('recur_until')
				// ;
			// $recur_until->grab( $post );
			// $recur_until = $recur_until->value();

			// if( $recur_until == 'qty' ){
				// $recur_qty = $this->make('/form/view/text')
					// ->set_name('recur_qty')
					// ;
				// $recur_qty->grab( $post );
				// $recur_qty = $recur_qty->value();

				// if( $recur_qty ){
					// $r = $this->make('lib/when');
					// $r
						// ->interval(NULL)
						// ->count($recur_qty)
						// ;
					// $this_return = $r->string();
					// $final_value = array();
					// if( $value ){
						// $final_value[] = $value;
					// }
					// $final_value[] = $this_return;
					// $value = join(';', $final_value);
				// }
			// }
		}

		$this->set_value( $value );
		return $this;
	}
}