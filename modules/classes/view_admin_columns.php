<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Classes_View_Admin_Columns_SM_HC_MVC extends _HC_MVC
{
	public function columns( $columns )
	{
		$return = array(
			'cb'			=> $columns['cb'],
			'title'			=> $columns['title'],
			);
		// $return['id']	= 'ID';
		return $return;
	}
}