<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Model_Manager_SM_HC_MVC extends _HC_MVC
{
	public function valid_on_time( $schedule, $test_time )
	{
		$t = $this->make('/app/lib')->run('time');
		$t->setTimestamp( $test_time );
		$check_date = $t->formatDateDb();

		$schedule_time_start = $schedule['time_start'];


		$return = TRUE;

		$duration = isset($schedule['duration']) ? $schedule['duration'] : NULL;
		if( ! $duration ){
			$return = FALSE;
			return $return;
		}

		$date_start = isset($schedule['date_start']) ? $schedule['date_start'] : NULL;

		if( ! isset($schedule['date_end']) ){
			$date_end = NULL;
		}
		else {
			$t->setDateDb( $schedule['date_end'] );
			$t->modify( '+' . $schedule['time_start'] . ' seconds' );
			$t->modify( '+' . $duration );
			$date_end = $t->formatDateDb();
		}

		$qty = isset($schedule['qty']) ? $schedule['qty'] : NULL;
		$recur = isset($schedule['recur']) ? $schedule['recur'] : NULL;

		if( $date_start && $date_end ){
			if( $check_date < $date_start ){
				$return = FALSE;
			}
			elseif( $check_date > $date_end ) {
				$return = FALSE;
			}
		}
		elseif( $date_start ){
			if( $check_date < $date_start ){
				$return = FALSE;
			}
		}
		elseif( $date_end ){
			if( $check_date > $date_end ){
				$return = FALSE;
			}
		}

		if( ! $return ){
			return $return;
		}

		// now check recurrency
		if( ! $recur ){
			return $return;
		}

		$when = $this->make('/recur-dates/lib/when');
		$when->rrule( $recur );

		$return = FALSE;

		if( $date_start ){
			$when->start_from( $date_start );
		}

		$tested_qty = 0;
		$test_date = $when->next();

		while( $test_date <= $check_date ){
			if( $date_end && ($test_date > $date_end) ){
				break;
			}

			if( $qty ){
				$tested_qty++;
				if( $tested_qty > $qty ){
					break;
				}
			}

			$t->setDateDb( $test_date );
			$t->modify( '+' . $schedule['time_start'] . ' seconds' );
			$this_time_start = $t->getTimestamp();
			$t->modify( '+' . $duration );
			$this_time_end = $t->getTimestamp();

			if( ($test_time >= $this_time_start)  && ($test_time <= $this_time_end) ){
				$return = TRUE;
				break;
			}

			$test_date = $when->next();
		}
		return $return;
	}

	public function valid_on_date( $schedule, $check_date )
	{
		$return = TRUE;

		$duration = isset($schedule['duration']) ? $schedule['duration'] : NULL;
		if( ! $duration ){
			$return = FALSE;
			return $return;
		}

		$date_start = isset($schedule['date_start']) ? $schedule['date_start'] : NULL;
		$date_end = isset($schedule['date_end']) ? $schedule['date_end'] : NULL;
		$qty = isset($schedule['qty']) ? $schedule['qty'] : NULL;
		$recur = isset($schedule['recur']) ? $schedule['recur'] : NULL;

		if( $date_start && $date_end ){
			if( $check_date < $date_start ){
				$return = FALSE;
			}
			elseif( $check_date > $date_end ) {
				$return = FALSE;
			}
		}
		elseif( $date_start ){
			if( $check_date < $date_start ){
				$return = FALSE;
			}
		}
		elseif( $date_end ){
			if( $check_date > $date_end ){
				$return = FALSE;
			}
		}

		if( ! $return ){
			return $return;
		}

		// now check recurrency
		if( ! $recur ){
			return $return;
		}

		$when = $this->make('/recur-dates/lib/when');
		$when->rrule( $recur );

		$return = FALSE;

		if( $date_start ){
			$when->start_from( $date_start );
		}

		$tested_qty = 0;
		$test_date = $when->next();
		while( $test_date <= $check_date ){
			if( $date_end && ($test_date > $date_end) ){
				break;
			}

			if( $qty ){
				$tested_qty++;
				if( $tested_qty > $qty ){
					break;
				}
			}

			if( $test_date == $check_date ){
				$return = TRUE;
				break;
			}
			$test_date = $when->next();
		}
		return $return;
	}
}