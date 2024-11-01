<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Presenter_SM_HC_MVC extends _HC_MVC_Model_Presenter
{
	public function present_status()
	{
		$return = $this->make('/html/view/element')->tag('span')
			->add_attr('class', 'hc-theme-label')
			->add_attr('class', 'hc-white')
			;

		$status = $this->data('post_status');
		$statuses = get_post_statuses();
		$status_text = isset($statuses[$status]) ? $statuses[$status] : $status;

		if( $status == 'publish' ){
			$return
				->add_attr('class', 'hc-bg-olive')
				->add( $status_text )
				;
		}
		else {
			$return
				->add_attr('class', 'hc-bg-darkgrey')
				->add( $status_text )
				;
		} 
		return $return;
	}

	public function present_title()
	{
		$t = $this->make('/app/lib')->run('time');
		$return = array();

	// duration & time
		// $time_view = $this->present_time();
		// if( $time_view ){
			// $time_view = '[' . $time_view . ']';
			// $return[] = $time_view;
		// }

	// number of bookings
		$qty = $this->data('qty');
		if( $qty ){
			$qty_view = sprintf( HCM::__('Repeat %d Times'), $qty );
			$return[] = $qty_view;
		}

	// date view
		$date_view = '';
		$date_start = $this->data('date_start');
		$date_end = $this->data('date_end');
		if( $date_start && $date_end ){
			$date_view = $t->formatDateRange($date_start, $date_end);
		}
		elseif( $date_start ){
/* translators: from date, for example From 1 Jun 2016 */
			$date_view = sprintf( HCM::__('From %s'), $t->setDateDb($date_start)->formatDate() );
		}
		elseif( $date_end ){
/* translators: until date, for example Until 1 Jun 2016 */
			$date_view = sprintf( HCM::__('Until %s'), $t->setDateDb($date_end)->formatDate() );
		}
		else {
			// $date_view = HCM::__('Permanently');
		}
		if( $date_view ){
			$return[] = $date_view;
		}

	// recurrence
		$recur_view = $this->present_recurrence();
		if( $recur_view ){
			$return[] = $recur_view;
		}

		$status = $this->data('post_status');
		if( $status != 'publish' ){
			$return[] = $this->present_status();
		}

		if( ! $return ){
			$return = $this->make('/html/view/icon')->icon('exclamation');
		}
		else {
			$return = join( ' ', $return );
		}

		return $return;
	}

	public function present_time()
	{
		$return = NULL;

		$t = $this->make('/app/lib')->run('time');

		$duration = $this->data('duration');
		$time_start = $this->data('time_start');

		if( ! (strlen($duration) && strlen($time_start)) ){
			$return = $this->make('/html/view/icon')->icon('exclamation');
			return $return;
		}

		$duration_seconds = $t->durationToSeconds($duration);
		$t
			->setNow()
			->setStartDay()
			;
		$t->modify('+ ' . $time_start . ' seconds');
		$return = $t->formatTime( $duration_seconds );

		return $return;
	}

	public function present_recurrence()
	{
		$return = NULL;

		$recur = $this->data('recur');
		if( $recur ){
			$when = $this->make('/recur-dates/lib/when');
			$when->rrule( $recur );
			$return = $when->human_readable();
		}
		return $return;
	}
}