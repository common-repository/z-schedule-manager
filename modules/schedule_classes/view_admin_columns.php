<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Classes_View_Admin_Columns_SM_HC_MVC extends _HC_MVC
{
	public function extend_columns( $return )
	{
		$return['class'] = HCM::__('Class');
		return $return;
	}

	public function extend_sortable_columns( $return )
	{
		$custom = array(
			'class'		=> 'class',
		);
		return wp_parse_args( $custom, $return );
	}

	public function extend_custom_columns( $return, $args )
	{
		$column = array_shift( $args );
		$post_id = array_shift( $args );

		$api = $this->make('/http/lib/api')
			->request('/api/schedule')
			->add_param('id', $post_id)
			->add_param('with', '-all-')
			;

		$model = $api
			->get()
			->response()
			;

		$my_return = NULL;
		switch( $column ){
			case 'class':
				if( isset($model['class']) && $model['class'] ){
					$p2 = $this->make('/classes/presenter')
						->set_data( $model['class'] )
						;
					$my_return = $p2->present_title();
				}
				else {
					$my_return = $this->make('/html/view/icon')->icon('exclamation');
				}
				break;
		}

		if( $my_return ){
			$return = $this->make('/html/view/element')->tag('a')
				->add_attr('href', admin_url('post.php?post=' . $post_id . '&action=edit'))
				->add( $my_return )
				;
		}

		echo $return;
	}
}