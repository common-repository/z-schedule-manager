<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class _HC_Rest_Api extends _HC_MVC
{
	protected $model = NULL;
	protected $validator = NULL;

// custom end points
	public function custom_get_count( $args = array() )
	{
		$model = $this->make( $this->model() );

		$where = $this->_build_where( $args );
		foreach( $where as $wh ){
			list( $k, $compare, $v ) = $wh;
			$model->where( $k, $compare, $v );
		}

		$return = $model->count();
		$return = json_encode( $return );

		return $this->make('/http/view/response')
			->set_status_code('200')
			->set_view( $return )
			;
	}

	public function set_model( $model ){
		$this->model = $model;
		return $this;
	}
	public function model()
	{
		if( $this->model ){
			$return = $this->model;
		}
		else {
			$return = '/' . $this->module() . '/model';
		}
		return $return;
	}

	public function set_validator( $validator ){
		$this->validator = $validator;
		return $this;
	}
	public function validator()
	{
		if( $this->validator ){
			$return = $this->validator;
		}
		else {
			$return = '/' . $this->module() . '/validator';
		}
		return $return;
	}

	public function get( $where = NULL )
	{
		$args = hc2_parse_args( func_get_args(), TRUE );

		if( isset($args['custom']) ){
			$what = $args['custom'];
			unset($args['custom']);

			if( method_exists($this, 'custom_get_' . $what) ){
				return call_user_func( array($this, 'custom_get_' . $what), $args );
			}
			else {
				return;
			}
		}

		if( isset($args['distinct']) ){
			$distinct_prop = $args['distinct'];
			unset( $args['distinct'] );
			return $this->get_distinct( $distinct_prop, $args );
		}
		elseif( isset($args['id']) && (! is_array($args['id'])) ){
			$return = $this->_get_one( $args['id'], $args );

			if( $return === NULL ){
				return $this->make('/http/view/response')
					->set_status_code('404')
					;
			}
			else {
				$return = json_encode( $return );
				return $this->make('/http/view/response')
					->set_status_code('200')
					->set_view( $return )
					;
			}
		}
		else {
			$return = $this->_get_many( $args );

			$return = json_encode( $return );
			return $this->make('/http/view/response')
				->set_status_code('200')
				->set_view( $return )
				;
		}
	}

	private function _build_where( $args )
	{
		$return = array();

		$compares = array();
		$allowed_compares = array('=', '<>', '>=', '<=', '>', '<', 'IN', 'NOTIN', 'LIKE');

		foreach( $args as $k => $v ){
			if( ! is_array($v) ){
				$compares[] = array($k, '=', $v);
			}
			else {
				while( $this_compare = array_shift($v) ){
					if( in_array($this_compare, array('IN', 'NOTIN')) ){
						if( is_array($v) && isset($v[0]) && is_array($v[0]) ){
							$v = array_shift( $v );
						}

						$compares[] = array($k, $this_compare, $v);
						$v = array();
					}
					else {
						$this_v = array_shift($v);
						$compares[] = array($k, $this_compare, $this_v);
					}
				}
			}
		}

		foreach( $compares as $cmp ){
			list( $k, $compare, $v ) = $cmp;

			$compare = trim( $compare );
			$compare = strtoupper( $compare );
			if( ! in_array($compare, $allowed_compares) ){
				echo "COMPARING BY '$compare' IS NOT ALLOWED!<br>";
				exit;
			}

			if( in_array($compare, array('IN', 'NOTIN')) ){
				if( ! is_array($v) ){
					echo 'V IS NOT ARRAY!';
					if( ! strlen($v) ){
						$v = 0;
					}
					$v = array($v);
				}
			}

			if( $compare == 'NOTIN' ){
				$compare = 'NOT IN';
			}

			$return[] = array( $k, $compare, $v );
		}
		return $return;
	}

	public function get_many( $args = array() )
	{
		$byid = FALSE;
		if( isset($args['byid']) ){
			$byid = TRUE;
		}

		$model = $this->_prepare_get_many( $args );

		$entries = $model
			->run('fetch-many')
			;
		$return = array();
		foreach( $entries as $e ){
			$e = $e->run('to-array');
			if( $byid ){
				$return[$e['id']] = $e;
			}
			else {
				$return[] = $e;
			}
		}
		return $return;
	}

	protected function _prepare_get_many( $args = array() )
	{
		$model = $this->make( $this->model() );

		$byid = FALSE;
		$with = array();

		if( isset($args['byid']) ){
			$byid = TRUE;
			unset($args['byid']);
		}

		if( isset($args['with']) ){
			$with = is_array($args['with']) ? $args['with'] : array($args['with']);
			foreach( $with as $w ){
				$model->with( $w );
			}
			unset($args['with']);
		}

		if( isset($args['limit']) ){
			$model->limit( $args['limit'] );
			unset($args['limit']);
		}

		if( isset($args['sort']) ){
			$sort = is_array($args['sort']) ? $args['sort'] : array($args['sort']);
			$k = array_shift($sort);
			$how = array_shift($sort);
			if( ! strlen($how) ){
				$how = 'asc';
			}
			else {
				$how = $how ? 'asc' : 'desc';
			}
			$model->order_by( $k, $how );
			unset($args['sort']);
		}

		$where = $this->_build_where( $args );
		foreach( $where as $wh ){
			list( $k, $compare, $v ) = $wh;
			$model->where( $k, $compare, $v );
		}

		return $model;
	}

	public function get_distinct( $pname, $args )
	{
		if( ! isset($args['sort']) ){
			$args['sort'] = $pname;
		}
		$model = $this->_prepare_get_many( $args );

		$return = $model
			->fetch_distinct( $pname )
			;

		$return = json_encode( $return );

		return $this->make('/http/view/response')
			->set_status_code('200')
			->set_view( $return )
			;
	}

	protected function _get_many( $args = array() )
	{
		$byid = FALSE;
		if( isset($args['byid']) ){
			$byid = TRUE;
		}

		$fields = array();
		if( isset($args['select']) ){
			$fields = $args['select'];
			unset($args['select']);
		}

		$model = $this->_prepare_get_many( $args );

		if( $fields ){
			$model->fetch_fields( $fields );
		}

		$entries = $model
			->run('fetch-many')
			;
		$return = array();
		foreach( $entries as $e ){
			$e = $e->run('to-array');
			if( $fields && count($fields) == 1 ){
				$return[] = array_shift($e);
			}
			else {
				if( $byid ){
					$return[$e['id']] = $e;
				}
				else {
					$return[] = $e;
				}
			}
		}

		return $return;
	}

	protected function _get_one( $id, $args = array() )
	{
		$return = array();
		$model = $this->make( $this->model() );

		if( isset($args['with']) ){
			$with = is_array($args['with']) ? $args['with'] : array($args['with']);
			foreach( $with as $w ){
				$model->with( $w );
			}
		}

		$fields = array();
		if( isset($args['select']) ){
			$fields = $args['select'];
			unset($args['select']);
		}

		if( $fields ){
			$model->fetch_fields( $fields );
		}

		$model = $model
			->where_id('=', $id)
			->fetch_one()
			;

		if( ! $model->exists() ){
			$return = NULL;
			return $return;
		}


		$return = $model->run('to-array');

		if( $fields && count($fields) == 1 ){
			$return = array_shift($return);
		}

		return $return;
	}

// create
	public function post( $json_input )
	{
		$values = json_decode( $json_input, TRUE );
		$validator = $this->make( $this->validator() );
		$valid = $validator->run( 'validate', $values );

		if( ! $valid ){
			$errors = $validator->errors();

			$return = array();
			$return['errors'] = $errors;

			$return = json_encode( $return );

			return $this->make('/http/view/response')
				->set_status_code('422')
				->set_view( $return )
				;
		}

		$model = $this->make( $this->model() );
		$model->from_array( $values );

		$model->run('save');

		$return = $model->run('to-array');
		$return = json_encode( $return );

		return $this->make('/http/view/response')
			->set_status_code('201')
			->set_view( $return )
			;
	}

// update
	public function put( $id, $json_input, $validate_change_only = TRUE )
	{
		$supplied_values = json_decode( $json_input, TRUE );

		$model = $this->make( $this->model() );

		$with = array();
		foreach( array_keys($supplied_values) as $k ){
			if( $model->is_related($k) ){
				$with[] = $k;
			}
		}

		foreach( $with as $w ){
			$model->with( $w );
		}

		$model = $model
			->where_id('=', $id)
			->fetch_one()
			;

		$current_values = $model->run('to-array');
		$check_values = array_merge( $current_values, $supplied_values );

		$validator = $this->make( $this->validator() );
		$valid = $validator->run( 'validate', $check_values );

		if( ! $valid ){
			$errors = $validator->errors();
			$return = array();
			$return['errors'] = $errors;

			$return = json_encode( $return );

			return $this->make('/http/view/response')
				->set_status_code('422')
				->set_view( $return )
				;
		}

		$model->from_array( $supplied_values );
		$model->run('save');

		$return = $model->run('to-array');
		$return = json_encode( $return );

		return $this->make('/http/view/response')
			->set_status_code('200')
			->set_view( $return )
			;
	}

// delete
	public function delete( $id ){
		$model = $this->make( $this->model() )
			->where_id('=', $id)
			->fetch_one()
			;
		$model->run('delete');

		return $this->make('/http/view/response')
			->set_status_code('204')
			;
	}
}