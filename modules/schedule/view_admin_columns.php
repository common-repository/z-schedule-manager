<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_View_Admin_Columns_SM_HC_MVC extends _HC_MVC
{
	public function __call( $what, $args )
	{
		$real_method = 'run_' . $what;
		if( method_exists($this, $real_method) ){
			$pass_args = $args;
			array_unshift( $pass_args, $real_method );
			return call_user_func_array( 
				array($this, 'run'),
				$pass_args
				);
		}
	}

	public function run_columns( $columns )
	{
		$return = array(
			'cb'		=> $columns['cb'],
			'my_title'	=> HCM::__('Title'),
			'time'		=> HCM::__('Time'),
			);
		return $return;
	}

	public function run_sortable_columns( $columns )
	{
		$custom = array(
			'my_title'	=> 'my_title',
			'time'		=> 'time_start',
		);
		return wp_parse_args( $custom, $columns );
	}

	public function run_custom_columns( $column, $post_id )
	{
		$return = '';
		$api = $this->make('/http/lib/api')
			->request('/api/schedule')
			->add_param('id', $post_id)
			->add_param('with', '-all-')
			;

		$model = $api
			->get()
			->response()
			;

		$p = $this->make('presenter')
			->set_data( $model )
			;

		switch( $column ){
			case 'my_title':
				$return = $p->present_title();
				break;

			case 'time':
				$return = $p->present_time();
				break;
		}

		if( $return ){
			$return = $this->make('/html/view/element')->tag('a')
				->add_attr('href', admin_url('post.php?post=' . $post_id . '&action=edit'))
				->add( $return )
				;
			if( in_array($column, array('my_title')) ){
				$return
					->add_attr('class', 'row-title')
					;
			}
			echo $return;
		}
	}
}