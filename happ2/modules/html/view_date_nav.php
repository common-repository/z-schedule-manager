<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
include_once( dirname(__FILE__) . '/view_grid.php' );
class Html_View_Date_Nav_HC_MVC extends Html_View_Grid_HC_MVC
{
	private $range = 'week'; // may be week or day
	private $date_param = 'date';
	private $range_param = 'range';
	private $date = '';
	// private $enabled = array('day', 'week', 'month', 'custom', 'upcoming', 'all');
	private $enabled = array('day', 'week', 'month', 'custom');

	public function from_array( $array )
	{
		if( array_key_exists($this->date_param(), $array) ){
			$this->set_date( $array[$this->date_param()] );
		}
		if( array_key_exists($this->range_param(), $array) ){
			$this->set_range( $array[$this->range_param()] );
		}
		return $this;
	}

	public function _init()
	{
		$t = $this->make('/app/lib')->run('time');
		$t->setNow();
		$this->set_date( $t->formatDate_Db() );

		$uri = $this->make('/http/lib/uri');
		$args = array();
		if( $uri->arg($this->date_param()) ){
			$args[$this->date_param()] = $uri->arg($this->date_param());
		}
		if( $uri->arg($this->range_param()) ){
			$args[$this->range_param()] = $uri->arg($this->range_param());
		}
		$this->from_array( $args );
		return $this;
	}

	private function _form_day()
	{
		$return = new _HC_Form;
		$return
			->set_input( 'date', $this->make('/form/view/date') )
			;
		return $return;
	}

	private function _form_custom_range()
	{
		$return = new _HC_Form;
		$return
			->set_input( 'start_date',	$this->make('/form/view/date') )
			->set_input( 'end_date',	$this->make('/form/view/date') )
			;
		return $return;
	}

	// returns the array of params that will be later used for redirect
	public function grab( $post )
	{
		$return = array(
			'date'	=> $this->date(),
			'range'	=> $this->range(),
			);

		switch( $this->range() ){
			case 'custom':
				$form = $this->_form_custom_range();
				$form->grab( $post );
				$values = $form->values();

				if( $values['end_date'] <= $values['start_date'] ){
					$values['end_date'] = $values['start_date'];
				}
				$return['date'] = $values['start_date'] . '_' . $values['end_date'];
				$return['range'] = 'custom';
				break;

			case 'day':
				$form = $this->_form_day();
				$form->grab( $post );
				$values = $form->values();

				$return['date'] = $values['date'];
				$return['range'] = 'day';
				break;
		}

		return $return;
	}

	function set_date( $date )
	{
		$this->date = $date;
	}
	function date()
	{
		return $this->date;
	}

	function set_allowed( $enabled )
	{
		return $this->set_enabled( $enabled );
	}

	function set_enabled( $enabled )
	{
		$this->enabled = $enabled;
		return $this;
	}
	function enabled()
	{
		return $this->enabled;
	}

	function set_range( $range )
	{
		$this->range = $range;
	}
	function range()
	{
		return $this->range;
	}

	function set_date_param( $param )
	{
		$this->date_param = $param;
	}
	function date_param()
	{
		return $this->date_param;
	}

	function set_range_param( $param )
	{
		$this->range_param = $param;
	}
	function range_param()
	{
		return $this->range_param;
	}

	private function _nav_title( $readonly = FALSE )
	{
		$t = $this->make('/app/lib')->run('time');
		$nav_title = '';

		switch( $this->range() ){
			case 'now':
				$nav_title = HCM::__('Now');
				break;

			case 'all':
				$nav_title = HCM::__('All Time');
				break;

			case 'upcoming':
				/* translators: it refers to the upcoming time range */
				$nav_title = HCM::__('Upcoming');
				break;

			case 'day':
				$start_date = $this->date();
				$t->setDateDb( $start_date );
				$nav_title = $t->formatDate();
				break;

			case 'custom':
				list( $start_date, $end_date ) = explode('_', $this->date());
				$nav_title = $t->formatDateRange( $start_date, $end_date );
				break;

			case 'day':
				$t->setDateDb( $this->date() );
				$start_date = $end_date = $t->formatDate_Db();
				$nav_title = $t->formatDateRange( $start_date, $end_date );
				$nav_title = HCM::__('Day');
				break;

			case 'week':
				$t->setDateDb( $this->date() );
				list( $start_date, $end_date ) = $t->getDatesRange( $this->date(), 'week' );
				$nav_title = $t->formatDateRange( $start_date, $end_date );
				break;

			case 'month':
				$t->setDateDb( $this->date() );
				$nav_title = $t->getMonthName() . ' ' . $t->getYear();
				break;
		}

		return $nav_title;
	}

	private function _render_range_selector( $start_date, $end_date )
	{
		$link = $this->make('/http/lib/uri');

		$out = $this->make('view/select-links');

		$range_options = array();

	/* now */
		$this_params = array(
			'-' . $this->range_param()	=> 'now',
			'-' . $this->date_param()	=> NULL,
			);
		$range_options['now'] = array(
			HCM::__('Now'),
			$link->url('-', $this_params)
			);

	/* day */
		$this_params = array(
			'-' . $this->range_param()	=> 'day',
			'-' . $this->date_param()		=> $start_date ? $start_date : NULL,
			);
		$range_options['day'] = array(
			HCM::__('Day'),
			$link->url('-', $this_params)
			);

	/* week */
		$this_params = array(
			'-' . $this->range_param()	=> 'week',
			'-' . $this->date_param()		=> $start_date ? $start_date : NULL,
			);
		$range_options['week'] = array(
			HCM::__('Week'),
			$link->url('-', $this_params)
			);

	/* month */
		$this_params = array(
			'-' . $this->range_param()	=> 'month',
			'-' . $this->date_param()		=> $start_date ? $start_date : NULL,
			);
		$range_options['month'] = array(
			HCM::__('Month'),
			$link->url('-', $this_params)
			);

	/* custom */
		$date_param = '';
		if( $start_date && $end_date ){
			$date_param = $start_date . '_' . $end_date;
		}
		elseif( $start_date ){
			$date_param = $start_date;
		}
		$this_params = array(
			'-' . $this->range_param()	=> 'custom',
			'-' . $this->date_param()	=> $date_param ? $date_param : NULL,
			);
		$range_options['custom'] = array(
			HCM::__('Custom Range'),
			$link->url('-', $this_params)
			);

	/* upcoming */
		$this_params = array(
			'-' . $this->range_param()	=> 'upcoming',
			);
		$range_options['upcoming'] = array(
/* translators: it refers to the upcoming time range */
			HCM::__('Upcoming'),
			$link->url('-', $this_params)
			);

	/* all */
		$this_params = array(
			'-' . $this->range_param()	=> 'all',
			);
		$range_options['all'] = array(
			HCM::__('All Time'),
			$link->url('-', $this_params)
			);

		$enabled = $this->enabled();
		$this_range = $this->range();

		foreach( $range_options as $k => $v ){
			if( ! in_array($k, $enabled) ){
				continue;
			}
			$subitem = $range_options[$k];
			$out->add_option( $k, $subitem[0], $subitem[1] );
		}

		$out->set_selected( $this_range );
		return $out;
	}

	private function _render_arrow_buttons( $before_date, $after_date )
	{
		$link = $this->make('/http/lib/uri');

		$out = $this->make('view/list-inline')
			->set_gutter(0)
			;

		switch( $this->range() ){
			case 'month':
			case 'week':
			case 'day':
				$this_params =  array(
					'-' . $this->date_param()		=> $before_date,
					'-' . $this->range_param()	=> $this->range(),
					);
				$out->add( 
					'before',
					$this->make('view/element')->tag('a')
						->add_attr('href', $link->url('-', $this_params))
						->add( $this->make('/html/view/icon')->icon('arrow-left') )
					);

				$this_params =  array(
					'-' . $this->date_param()		=> $after_date,
					'-' . $this->range_param()	=> $this->range(),
					);
				$out->add( 
					'after',
					$this->make('view/element')->tag('a')
						->add_attr('href', $link->url('-', $this_params))
						->add( $this->make('/html/view/icon')->icon('arrow-right') )
					);

				foreach( $out->children() as $child ){
					$child
						->add_attr('class', 'hc-btn')
						->add_attr('class', 'hc-p2')
						->add_attr('class', 'hc-rounded')
						->add_attr('class', 'hc-mr1')
						->add_attr('class', 'hc-align-center')
						->add_attr('class', 'hc-bg-silver')
						;
				}
		}

		if( $out->children() ){
			return $out;
		}
	}

	private function _render_current_range( $start_date, $end_date )
	{
		$out = NULL;
		$link = $this->make('/http/lib/uri');
		$enabled = $this->enabled();
		$t = $this->make('/app/lib')->run('time');

		switch( $this->range() ){
			case 'all':
			case 'upcoming':
				break;

			case 'week':
			case 'month':
				$nav_title = $this->_nav_title();
				$out = $this->make('view/element')->tag('span')
					->add( $nav_title )
					->add_attr('class', 'hc-theme-box')
					;
				break;

			case 'day':
				if( in_array($this->range(), $enabled) ){
					$form = $this->_form_day();

					$form->set_values( 
						array(
							'date'	=> $start_date,
							)
						);

					$out = $this->make('view/form')
						->add_attr('action', $link->url('-', array('handle' => 'date-nav')) )
						;
					$out
						->add(
							$this->make('view/list-inline')
								->set_gutter(1)
								->add(
									$form->input('date')
									)
								->add(
									$this->make('view/element')->tag('input')
										->add_attr('type', 'submit')
										->add_attr('title', HCM::__('OK') )
										->add_attr('value', HCM::__('OK') )
										->add_attr('class', 'hc-btn', 'hc-btn-submit')
									)
								)
						;
				}
			/* otherwise display it readonly */
				else {
					$out = $this->make('view/element')->tag('span')
						->add( $t->formatDate() )
						->add_attr('class', 'hc-theme-box')
						;
				}

				break;

			case 'custom':
			/* now add form */
				if( in_array($this->range(), $enabled) ){
					$form = $this->_form_custom_range();
					$form->set_values( 
						array(
							'start_date'	=> $start_date,
							'end_date'		=> $end_date,
							)
						);

					$out = $this->make('view/form')
						->add_attr('action', $link->url('-', array('handle' => 'date-nav')) )
						;

					$out
						->add(
							$this->make('view/list-inline')
								->set_gutter(1)
								->add(
									$form->input('start_date')
									)
								->add(
									'-'
									)
								->add(
									$form->input('end_date')
									)
								->add(
									$this->make('view/element')->tag('input')
										->add_attr('type', 'submit')
										->add_attr('title', HCM::__('OK') )
										->add_attr('value', HCM::__('OK') )
										->add_attr('class', 'hc-btn', 'hc-btn-submit')
									)
								)
						;
				}
			/* otherwise display it readonly */
				else {
					$out = $this->make('view/element')->tag('span')
						->add( $t->formatDateRange( $start_date, $end_date ) )
						->add_attr('class', 'hc-theme-box')
						;

				}

				$out
					->add_attr('class', 'hc-mb1')
					->add_attr('class', 'hc-mr1')
					;

				break;
		}
		
		if( $out ){
			return $out;
		}
	}

	function render()
	{
		$readonly = $this->readonly();
		if( $readonly ){
			$nav_title = $this->_nav_title( $readonly );
			$return = $this->make('view/element')->tag('span')
				->add( $nav_title )
				->add_attr('class', 'hc-p2')
				->add_attr('class', 'hc-border')
				->add_attr('class', 'hc-rounded')
				->add_attr('class', 'hc-inline-block')
				;
			return $return;
		}

		$link = $this->make('/http/lib/uri');
		$t = $this->make('/app/lib')->run('time');

		$before_date = $after_date = 0;
		switch( $this->range() ){
			case 'all':
				$t->setNow();
				$start_date = $end_date = 0;
				// $start_date = $end_date = $t->formatDate_Db();
				break;

			case 'upcoming':
				$t->setNow();
				$start_date = $end_date = 0;
				break;

			case 'custom':
				list( $start_date, $end_date ) = explode('_', $this->date());

				$t->setDateDb($start_date)->modify('-1 day');
				$before_date =  $t->formatDate_Db();

				$t->setDateDb($end_date)->modify( '+1 day' );
				$after_date =  $t->formatDate_Db();
				break;

			case 'now':
			case 'day':
				$t->setDateDb( $this->date() );
				$start_date = $end_date = $t->formatDate_Db();

				$t->modify( '-1 day' );
				$before_date =  $t->formatDate_Db();

				$t->setDateDb( $this->date() );
				$t->modify( '+1 day' );
				$after_date =  $t->formatDate_Db();
				break;

			case 'week':
				$t->setDateDb( $this->date() );

				$start_date = $t->setStartWeek()->formatDate_Db();
				$end_date = $t->setEndWeek()->formatDate_Db();

				$t->setDateDb( $this->date() );
				$t->modify( '-1 week' );
				$t->setStartWeek();
				$before_date =  $t->formatDate_Db();

				$t->setDateDb( $this->date() );
				$t->setEndWeek();
				$t->modify( '+1 day' );
				$after_date =  $t->formatDate_Db();
				break;

			case 'month':
				$t->setDateDb( $this->date() );

				$start_date = $t->setStartMonth()->formatDate_Db();
				$end_date = $t->setEndMonth()->formatDate_Db();

				$month_view = $t->getMonthName() . ' ' . $t->getYear();

				$t->setDateDb( $this->date() );
				$t->modify( '-1 month' );
				$t->setStartMonth();
				$before_date =  $t->formatDate_Db();

				$t->setDateDb( $this->date() );
				$t->setEndMonth();
				$t->modify( '+1 day' );
				$after_date =  $t->formatDate_Db();
				break;
		}

	/* range selector */
		$_out = array();
		$enabled = $this->enabled();
		if( count($enabled) > 1 ){
			$_out['range_selector'] = $this->_render_range_selector( $start_date, $end_date );
		}
		$_out['current_range'] = $this->_render_current_range( $start_date, $end_date );
		$_out['arrow_buttons'] = $this->_render_arrow_buttons( $before_date, $after_date );

	/* print view */
		$nav_title = $this->_nav_title();
		$out = $this->make('/html/view/list-inline')
			->set_gutter(2)
			;

		foreach( $_out as $k => $v ){
			if( $v ){
				$v
					// ->add_attr('style', 'vertical-align: top;')
					->add_attr('class', 'hc-inline-block')
					;

				$out
					->add( $k, $v )
					;
			}
		}

		return $out;
	}
}