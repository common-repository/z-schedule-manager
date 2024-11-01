<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Controller_Front_SM_HC_MVC extends _HC_MVC
{
	public function route_index( $pass_params = array() )
	{
		$params = $this->_parse_atts( $pass_params );
		$uri_args = $this->make('/http/lib/uri')
			->args()
			;
		$t = $this->make('/app/lib')->run('time');

		if( isset($uri_args['range']) ){
			$range = $uri_args['range'];
		}
		elseif( isset($params['range']) && is_array($params['range']) && isset($params['range'][0]) ){
			$range = $params['range'][0];
		}
		else {
			$range = 'week';
		}

		// $range = isset($uri_args['range']) ? $uri_args['range'] : isset($params['range']) 'week';
		$date = isset($uri_args['date']) ? $uri_args['date'] : $t->setNow()->formatDate_Db();
		list( $start_date, $end_date ) = $t->getDatesRange( $date, $range );

		$all_schedules = $this->make('/http/lib/api')
			->request('/api/schedule')
			->add_param('with', '-all-')
			->get()
			->response()
			;

	// see which schedules fit
		$schm = $this->make('model/manager');
		$schedules = array();

		switch( $range ){
			case 'now':
				$now = time();
				reset( $all_schedules );
				foreach( $all_schedules as $sch ){
					$valid = $schm->valid_on_time( $sch, $now );
					if( ! $valid ){
						continue;
					}
					$schedules[] = $sch;
				}
				break;

			default:
				$t->setDateDb( $start_date );
				$rex_date = $start_date;
				while( $rex_date <= $end_date ){
					reset( $all_schedules );
					foreach( $all_schedules as $sch ){
						$valid = $schm->valid_on_date( $sch, $rex_date );
						if( ! $valid ){
							continue;
						}

						if( ! isset($schedules[$rex_date]) ){
							$schedules[$rex_date] = array();
						}
						$schedules[$rex_date][] = $sch;
					}
					$t->modify('+1 day');
					$rex_date = $t->formatDateDb();
				}
				break;
		}

		$view = $this->make('view/front')
			->run('render', $schedules, $range, $params)
			;
		return $view;
	}

	protected function _parse_atts( $pass_params = array() )
	{
	// parse params
		$default_params = array(
			'range'	=> array('week', 'month', 'now'),
			);

	// parse passed params
		foreach( $pass_params as $k => $v ){
			switch( $k ){
				case 'range':
					$pass_params[$k] = array();
					$options = explode(',', $v);
					foreach( $options as $option ){
						$option = trim( $option );
						$pass_params[$k][] = $option;
					}
					break;

				default:
					$pass_params[$k] = $v;
					break;
			}
		}

		$params = array();
		foreach( $default_params as $k => $default_v ){
			if( ! array_key_exists($k, $pass_params) ){
				$params[$k] = $default_v;
				continue;
			}

			if( ! is_array($default_v) ){
				$params[$k] = $pass_params[$k];
				continue;
			}

			if( ! is_array($pass_params[$k]) ){
				$pass_params[$k] = array( $pass_params[$k] );
			}

			$v = array();
			foreach( $pass_params[$k] as $pass_v ){
				if( in_array($pass_v, $default_v) ){
					$v[] = $pass_v;
				}
			}

			if( ! $v ){
				$v = $default_v;
			}
			$params[$k] = $v;
		}

		return $params;
	}
}