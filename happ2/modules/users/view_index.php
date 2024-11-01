<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_View_Index_HC_MVC extends _HC_MVC
{
	public function render( $entries )
	{
		$header = $this->run('prepare-header');
		$sort = $this->run('prepare-sort');

		$rows = array();
		reset( $entries );
		foreach( $entries as $e ){
			$rows[ $e['id'] ] = $this->run('prepare-row', $e);
		}

		$out = $this->make('/html/view/container');

		if( $rows ){
			$table = $this->make('/html/view/sorted-table')
				->set_header( $header )
				->set_sort( $sort )
				->set_rows( $rows )
				;
			$out->add( $table );
		}

		return $out;
	}

	public function prepare_header()
	{
		$return = array(
			'display_name'	=> HCM::__('Display Name'),
			'email'			=> HCM::__('Email'),
			'id'			=> 'ID',
			);
		return $return;
	}

	public function prepare_sort()
	{
		$return = array(
			'display_name'	=> 1,
			'email'			=> 1,
			'id'			=> 1,
			);
		return $return;
	}

	public function prepare_row( $e )
	{
		$row = array();

		$p = $this->make('presenter')
			->set_data($e)
			;

		$row = array();
		$row['display_name']	= $e['display_name'];
		$display_name_view = $e['display_name'];
		$display_name_view = $this->make('/html/view/link')
			->to('zoom', array('id' => $e['id']))
			->add($display_name_view)
			;
		$row['display_name_view']	= $display_name_view;

		$row['email']		= $e['email'];
		$row['id']			= $e['id'];
		$id_view = $this->make('/html/view/element')->tag('span')
			->add_attr('class', 'hc-fs2')
			->add_attr('class', 'hc-muted-2')
			->add( $e['id'] )
			;
		$row['id_view']	= $id_view;

		return $row;
	}
}