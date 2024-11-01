<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class _HC_ORM_Storable_Eva implements _HC_ORM_Storable_Interface
{
	protected $db;

	protected $type = NULL;

	protected $table_objects = 'objects';
	protected $table_objects_meta = 'objects_meta';
	protected $id_field = 'id';

	static $query_cache = array();
	protected $use_cache = TRUE;
	protected $cast = array();

	public function __construct( $db, $type )
	{
		$this->db = $db;
		$this->type = $type;
	}

	public function set_cast( $cast )
	{
		$this->cast = $cast;
		return $this;
	}

	public function count( $wheres = array() )
	{
		foreach( $wheres as $key => $key_wheres ){
			foreach( $key_wheres as $where ){
				list( $how, $value, $escape ) = $where;
				if( $how == 'IN' ){
					$this->db->where_in($key, $value);
				}
				elseif( $how == 'NOT IN' ){
					$this->db->where_not_in($key, $value);
				}
				else {
					$this->db->where($key . $how, $value, $escape);
				}
			}
		}

		$return = $this->db->count_all_results( $this->table_objects_meta );
		return $return;
	}

	public function fetch( $fields = '*', $wheres = array(), $limit = NULL, $orderby = NULL, $distinct = FALSE )
	{
		$return = array();

		if( ! is_array($fields) && ($fields != '*') ){
			$fields = array($fields);
		}

		if( $wheres ){
			if( isset($wheres['id']) ){
				$wheres['object_id'] = $wheres['id'];
				unset($wheres['id']);
			}

			foreach( $wheres as $key => $key_wheres ){
				foreach( $key_wheres as $where ){
					list( $how, $value, $escape ) = $where;

					if( $key == 'object_id' ){
						if( $how == 'IN' ){
							$this->db->where_in($this->table_objects_meta . '.' . $key, $value);
						}
						elseif( $how == 'NOT IN' ){
							$this->db->where_not_in($this->table_objects_meta . '.' . $key, $value);
						}
						else {
							$this->db->where($this->table_objects_meta . '.' . $key . $how, $value, $escape);
						}
					}
					else {
						$this->db->group_start();
							$this->db->where('meta_key', $key);
							if( $how == 'IN' ){
								$this->db->where_in( $this->table_objects_meta . '.' . 'meta_value', $value);
							}
							elseif( $how == 'NOT IN' ){
								$this->db->where_not_in( $this->table_objects_meta . '.' . 'meta_value', $value);
							}
							else {
								$this->db->where($this->table_objects_meta . '.' . 'meta_value' . $how, $value, $escape);
							}
						$this->db->group_end();
					}
				}
			}
		}

		$only_id = FALSE;
		if( is_array($fields) && (count($fields) == 1) && ($fields[0] == 'id') ){
			$only_id = TRUE;
		}

		$meta_fields = $only_id ? array('DISTINCT object_id') : array('object_id', 'meta_key', 'meta_value');

		$this->db
			->select( $meta_fields, FALSE )
			;

		$this->db
			->join( $this->table_objects, $this->table_objects_meta . '.object_id = ' . $this->table_objects . '.id'  )
			->where( $this->table_objects . '.object_type', $this->type)
			;

		if( $orderby ){
			foreach( $orderby as $ord ){
				if( $ord[0] == 'id' ){
					$this->db->order_by( 'object_id', $ord[1] );
				}
				else {
					$this->db->order_by( "meta_key = '" . $ord[0] . "'", 'DESC' );
					$this->db->order_by( 'meta_value', $ord[1] );
				}
			}
		}

		$sql = $this->db->get_compiled_select($this->table_objects_meta);

		$run = TRUE;

		if( $this->use_cache ){
			if( isset(self::$query_cache[$sql]) ){
				// echo "ON CACHE: '$sql'<br>";
				$return = self::$query_cache[$sql];
				$run = FALSE;
			}
		}

		if( $run ){
			$q = $this->db->query( $sql );
			foreach( $q->result_array() as $row ){
				if( $only_id ){
					$return[] = $row['object_id'];
				}
				else {
					if( ! isset($return[ $row['object_id'] ]) ){
						$return[ $row['object_id'] ] = array( 'id' => $row['object_id'] );
					}
					$return[ $row['object_id'] ][ $row['meta_key'] ] = $row['meta_value'];
				}
			}
		}

		if( $this->use_cache && $run ){
			// echo "SET ON CACHE: '$sql'<br>";
			self::$query_cache[$sql] = $return;
		}

		return $return;


		if( $limit ){
			$this->db->limit( $limit );
		}
		if( $orderby ){
			foreach( $orderby as $ord ){
				$this->db->order_by( $ord[0], $ord[1] );
			}
		}
	}

	public function insert( $data )
	{
		$return = NULL;

		$object_data = array(
			'object_type'	=> $this->type
			);

		if( ! $this->db->insert( $this->table_objects, $object_data ) ){
			return $return;
		}

		$object_id = $this->db->insert_id();

		if( array_key_exists('id', $data) ){
			unset($data['id']);
		}

		foreach( $data as $key => $value ){
			$meta_data = array(
				'object_id'		=> $object_id,
				'meta_key'		=> $key,
				'meta_value'	=> $value,
				);
			if( ! $this->db->insert( $this->table_objects_meta, $meta_data ) ){
				return $return;
			}
		}

		$return = $object_id;
		return $return;
	}

	public function update( $data, $wheres = array() )
	{
		if( isset($wheres['id']) ){
			$wheres['object_id'] = $wheres['id'];
			unset($wheres['id']);
		}

		foreach( $data as $k => $v ){
			if( $wheres ){
				foreach( $wheres as $key => $key_wheres ){
					foreach( $key_wheres as $where ){
						list( $how, $value, $escape ) = $where;
						if( $how == 'IN' ){
							$this->db->where_in($key, $value);
						}
						else {
							$this->db->where($key . $how, $value, $escape);
						}
					}
				}
			}

		// if have this meta
			$this->db->where('meta_key', $k);
			$this->db->select('id');
			$result = $this->db->get( $this->table_objects_meta, 1 )->result_array();

		// update
			if( $result && $result[0] ){
				$meta_id = $result[0]['id'];
				$this_data = array(
					'meta_value'	=> $v
					);

				if(
					$this->db
						->where('id', $meta_id)
						->update( $this->table_objects_meta, $this_data )
					){
						$return = TRUE;
					}
				else {
					$return = FALSE;
				}
			}
		// insert
			else {
				if( isset($wheres['object_id']) && (count($wheres['object_id']) == 1) ){
					$object_id = $wheres['object_id'][0][1];
					$meta_data = array(
						'object_id'		=> $object_id,
						'meta_key'		=> $k,
						'meta_value'	=> $v,
						);
					if( $this->db->insert( $this->table_objects_meta, $meta_data ) ){
						$return = TRUE;
					}
					else {
						$return = FALSE;
					}
				}
			}
		}

		return $return;
	}

	public function delete_all()
	{
		$ok = FALSE;

		$ids = $this->fetch('id');

		$this->db->where_in('object_id', $ids);
		$ok = $this->db->delete($this->table_objects_meta);

		$this->db->where_in('id', $ids);
		$ok = $this->db->delete($this->table_objects);

		$return = $ok;
		return $return;
	}

	public function delete( $wheres = array() )
	{
		$ok = FALSE;
		if( $wheres ){
			$ids = $this->fetch('id', $wheres);

			$this->db->where_in('object_id', $ids);
			$ok = $this->db->delete($this->table_objects_meta);

			$this->db->where_in('id', $ids);
			$ok = $this->db->delete($this->table_objects);
		}

		$return = $ok;
		return $return;
	}
}