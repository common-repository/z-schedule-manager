<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_View_Front_SM_HC_MVC extends _HC_MVC
{
	public function render( $schedules, $range, $params = array() )
	{
		$out = $this->make('/html/view/container');
		$t = $this->make('/app/lib')->run('time');

		$header = $this->run('prepare-header');
		$sort = $this->run('prepare-sort');

		switch( $range ){
			case 'now':
				$t->setNow();
				$this_label = $t->formatFull();
				$this_label = $this->make('/html/view/element')->tag('h2')
					->add( $this_label )
					;
				$out->add( $this_label );

				if( $schedules ){
					$rows = array();
					foreach( $schedules as $e ){
						$rows[$e['id']] = $this->run('prepare-row', $e);

						$table = $this->make('/html/view/sorted-table')
							->set_header($header)
							->set_rows($rows)
							->set_sort($sort)
							->set_striped(FALSE)
							;

						$out->add( $table );
					}
				}
				else {
					$not_found_text = HCM::__('No Schedule Found');
					$out->add( $not_found_text );
				}
			break;

			default:
				foreach( $schedules as $date => $date_schedules ){
					$rows = array();
					foreach( $date_schedules as $e ){
						$rows[$e['id']] = $this->run('prepare-row', $e);
					}

					$t->setDateDb( $date );
					$day_label = $t->formatDateFull();
					$day_label = $this->make('/html/view/element')->tag('h2')
						->add( $day_label )
						;

					$table = $this->make('/html/view/sorted-table')
						->set_header($header)
						->set_rows($rows)
						->set_sort($sort)
						->set_striped(FALSE)
						;

					$out->add( $day_label );
					$out->add( $table );
				}
			break;
		}

		$allowed_ranges = isset($params['range']) ? $params['range'] : array('now', 'day', 'week', 'month');
		$date_nav = $this->make('/html/view/date-nav')
			->set_allowed( $allowed_ranges )
			->run('render')
			;

		$out->prepend( $date_nav );

		return $out;
	}

	
	public function prepare_header()
	{
		$return = array(
			'time'	=> HCM::__('Time')
			);
		return $return;
	}

	public function prepare_sort()
	{
		$return = array(
			'time'	=> 1
			);
		return $return;
	}

	public function prepare_row( $e )
	{
		$return = array();

		$p = $this->make('presenter')
			->set_data( $e )
			;

		$row = array();
		$row['time']		= $e['time_start'];

		$time_view = $p->present_time();
		$row['time_view'] = $time_view;

		return $row;
	}
}