<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Simpledb extends Model 
{
	function Simpledb() {
		parent::Model();
	}
	
	function validate_query_string($str) {
		$str = $this->input->xss_clean(trim($str));
		return $str;
	}
	
	function is_select_only($str) {
		$pos = strpos(strtolower($str), 'select');
		if( $pos === 0) { return TRUE; }
		return false;
	}
		
	function generate_table($table_array) {
		//$table_array can be the db->query() result
		$table_template = array (
	       'table_open'          => '<table border="0" cellpadding="0" cellspacing="1", style="width:100%" class="tableborder">',

	       'heading_row_start'   => '<thead><tr>',
	       'heading_row_end'     => '</tr></thead><tbody>',
	       'heading_cell_start'  => '<th>',
	       'heading_cell_end'    => '</th>',

	       'row_start'           => '<tr>',
	       'row_end'             => '</tr>',
	       'cell_start'          => '<td>',
	       'cell_end'            => '</td>',

	       'row_alt_start'       => '<tr>',
	       'row_alt_end'         => '</tr>',
	       'cell_alt_start'      => '<td>',
	       'cell_alt_end'        => '</td>',

	       'table_close'         => '</tbody></table>'
		);
		$this->table->set_template($table_template);
		return $this->table->generate($table_array);
	}

}