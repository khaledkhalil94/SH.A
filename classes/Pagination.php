<?php 
require_once('init.php');
class Pagination {
	
	public $rpp = 4;
	public $current_page = 1;
	public $total_count;


	function __construct($rpp, $current_page, $total_count) {
		$this->rpp = $rpp;
		$this->current_page = $current_page;
		$this->total_count = $total_count;
	}


	public function offset(){
		return ($this->current_page - 1) * $this->rpp;
	}

	public function total_pages(){
		return ceil($this->total_count/$this->rpp);
	}

	public function count(){
		return count($this->get_users());
	}

	public function next_page(){
		return $this->current_page + 1;
	}

	public function prev_page(){
		return $this->current_page - 1;
	}

	public function has_next_page(){
		return $this->next_page() <= $this->total_pages() ? true : false;
	}

	public function has_prev_page(){
		return $this->prev_page() >= 1 ? true : false;
	}



} ?>